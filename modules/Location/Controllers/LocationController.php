<?php

namespace Modules\Location\Controllers;

use App\Http\Controllers\Controller;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationTranslation;
use Illuminate\Http\Request;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyCategory;


class LocationController extends Controller
{
    public $location;
    protected $propertyClass;
    public function __construct(Location $location)
    {
        $this->propertyClass         = Property::class;
        $this->location = $location;
    }

    public function index(Request $request)
    {
        $listLocation = Location::query();
        if (!empty($search = $request->query('s'))) {
            $listLocation->where('name', 'LIKE', '%' . $search . '%');
        }
        $listLocation->orderBy('created_at', 'asc');
        $list = call_user_func([$this->propertyClass, 'search'], $request);
        $markers = [];
        if (!empty($list)) {
            foreach ($list as $row) {
                $markers[] = [
                    "id"      => $row->id,
                    "title"   => $row->title,
                    "lat"     => (float)$row->map_lat,
                    "lng"     => (float)$row->map_lng,
                    "gallery" => $row->getGallery(true),
                    "infobox" => view('Property::frontend.layouts.search.loop.loop-gird', ['row' => $row, 'disable_lazyload' => 1, 'wrap_class' => 'infobox-item'])->render(),
                    'marker'  => url('images/icons/png/pin.png'),
                ];
            }
        }
        $allproperty = $list;
        $data = [
            'rows'        => $list,
            'row'         => new $this->location(),
            'alldivisions' =>  Location::where('status', 'publish')->whereNull('parent_id')->withCount('property')->get(),
            'alldistricts' =>  Location::where('status', 'publish')->whereNotNull('parent_id')->withCount('property')->get(),
            'translation' => new LocationTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Location'),
                    'url'  => route('location.index')
                ],
                [
                    'name'  => __('All'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Location::frontend.index', $data);
    }

    public function detail(Request $request, $slug)
    {
        $row = $this->location::where('slug', $slug)->where("status", "publish")->first();;
        if (empty($row)) {
            return redirect('/');
        }
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $translation = $row->translateOrOrigin(app()->getLocale());
        $data = [
            'row' => $row,
            'list_location'      => Location::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'list_category'      => PropertyCategory::where('status', 'publish')->get()->toTree(),
            'translation' => $translation,
            'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
        ];
        $this->setActiveMenu($row);
        return view('Location::frontend.detail', $data);
    }

    public function searchForSelect2(Request $request)
    {
        $search = $request->query('search');
        $query = Location::select('bc_locations.*', 'bc_locations.name as title')->where("bc_locations.status", "publish");
        if ($search) {
            $query->where('bc_locations.name', 'like', '%' . $search . '%');

            if (setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale()) {
                $query->leftJoin('bc_location_translations', function ($join) use ($search) {
                    $join->on('bc_locations.id', '=', 'bc_location_translations.origin_id');
                });
                $query->orWhere(function ($query) use ($search) {
                    $query->where('bc_location_translations.name', 'LIKE', '%' . $search . '%');
                });
            }
        }
        $res = $query->orderBy('name', 'asc')->limit(20)->get();
        if (!empty($res) and count($res)) {
            $list_json = [];
            foreach ($res as $location) {
                $translate = $location->translateOrOrigin(app()->getLocale());
                $list_json[] = [
                    'id' => $location->id,
                    'title' => $translate->name,
                ];
            }
            return $this->sendSuccess(['data' => $list_json]);
        }
        return $this->sendError(__("Location not found"));
    }
}
