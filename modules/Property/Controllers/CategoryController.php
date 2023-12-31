<?php

namespace Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyCategory;
use Modules\Property\Models\PropertyCategoryTranslation;

class CategoryController extends Controller
{

    public $property_category;
    public $property;
    public function __construct(PropertyCategory $property_category, Property $property)
    {
        $this->property_category = $property_category;
        $this->property = $property;
    }
    public function index(Request $request)
    {
        $listCategory = $this->property_category::query();
        if (!empty($search = $request->query('s'))) {
            $listCategory->where('name', 'LIKE', '%' . $search . '%');
        }
        $listCategory->orderBy('created_at', 'desc');

        $data = [
            'rows'        => $listCategory->get()->toTree(),
            'row'         => new $this->property_category(),
            'translation'    => new PropertyCategoryTranslation(),
            'propertyCategoryHeader' => PropertyCategory::where('status', 'publish')->orderBy('created_at', 'desc')->get(),
            'breadcrumbs' => [
                [
                    'name' => __('Property'),
                    'url'  => 'frontend/property'
                ],
                [
                    'name'  => __('Category'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Property::frontend.category.index', $data);
    }
    public function detail(Request $request, $slug)
    {
        $row = $this->property_category::where('slug', $slug)->where("status", "publish")->first();;
        if (empty($row)) {
            return redirect('/');
        }

        $translation = $row->translateOrOrigin(app()->getLocale());
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'row' => $row,
            'translation' => $translation,
            'list_location'      => Location::where('status', 'publish')->limit($limit_location)->with(['translations'])->get(),
            'list_category'      => PropertyCategory::where('status', 'publish')->get()->toTree(),
            'property_min_max_price' => Property::getMinMaxPrice(),
            'rows' => $this->property->getListProperties($row->id),
            'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
        ];
        $this->setActiveMenu($row);
        return view('Property::frontend.category.detail', $data);
    }
}
