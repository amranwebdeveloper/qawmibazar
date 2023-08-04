<?php

namespace Modules\Property\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\FrontendController;
use Modules\Core\Models\Attributes;
use Modules\Property\Models\PropertyDokan;
use Modules\Property\Models\PropertyDokanTerm;
use Modules\Property\Models\PropertyDokanTranslation;
use Modules\Location\Models\Location;
use Modules\Property\Models\Property;
use Modules\Review\Models\Review;
use Modules\Core\Models\SEO;
use Modules\Property\Models\PropertyTerm;
use Modules\Property\Models\PropertyTranslation;
use Modules\Vendor\Models\BcContactObject;

class VendorDokanController extends FrontendController
{
    protected $propertyClass;
    protected $dokanTermClass;
    protected $attributesClass;
    protected $locationClass;
    protected $reviewClass;
    /**
     * @var PropertyDokan
     */
    protected $dokanClass;
    protected $currentProperty;
    protected $dokanTranslationClass;

    public function __construct()
    {
        parent::__construct();
        $this->propertyClass = Property::class;
        $this->dokanTermClass = PropertyDokanTerm::class;
        $this->attributesClass = Attributes::class;
        $this->locationClass = Location::class;
        $this->dokanClass = PropertyDokan::class;
        $this->dokanTranslationClass = PropertyDokanTranslation::class;
        $this->reviewClass           = Review::class;
    }

    protected function hasPropertyPermission($property_id = false)
    {
        if (empty($property_id)) return false;
        $property = $this->propertyClass::find($property_id);
        if (empty($property)) return false;
        if (!$this->hasPermission('property_update') and $property->create_user != Auth::id()) {
            return false;
        }
        $this->currentProperty = $property;
        return true;
    }
    public function index(Request $request, $property_id)
    {
        $this->checkPermission('property_view');

        if (!$this->hasPropertyPermission($property_id)) {
            abort(403);
        }
        $query = $this->dokanClass::query();
        $query->orderBy('id', 'desc');
        if (!empty($property_name = $request->input('s'))) {
            $query->where('title', 'LIKE', '%' . $property_name . '%');
            $query->orderBy('title', 'asc');
        }
        $query->where('parent_id', $property_id);
        $data = [
            'rows'               => $query->with(['author'])->paginate(20),
            'breadcrumbs'        => [
                [
                    'name' => __('Properties'),
                    'url'  => route('property.vendor.index')
                ],
                [
                    'name' => __('Property: :name', ['name' => $this->currentProperty->title]),
                    'url'  => route('property.vendor.edit', [$this->currentProperty->id])
                ],
                [
                    'name'  => __('All Dokans'),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Dokan Management"),
            'property' => $this->currentProperty,
            'row' => new $this->dokanClass(),
            'translation' => new $this->dokanTranslationClass(),
            'attributes'     => $this->attributesClass::where('service', 'dokan')->get(),
        ];
        return view('Property::frontend.manageProperty.dokan.index', $data);
    }

    public function create($property_id)
    {
        $this->checkPermission('property_update');

        if (!$this->hasPropertyPermission($property_id)) {
            abort(403);
        }
        $row = new $this->dokanClass();
        $translation = new $this->dokanTranslationClass();
        $data = [
            'row'            => $row,
            'translation'    => $translation,
            'attributes'     => $this->attributesClass::where('service', 'dokan')->get(),
            'property_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'enable_multi_lang' => true,
            'breadcrumbs'    => [
                [
                    'name' => __('Properties'),
                    'url'  => route('property.vendor.index')
                ],
                [
                    'name' => __('Property: :name', ['name' => $this->currentProperty->title]),
                    'url'  => route('property.vendor.edit', [$this->currentProperty->id])
                ],
                [
                    'name' => __('All Dokans'),
                    'url'  => route("property.vendor.dokan.index", ['property_id' => $this->currentProperty->id])
                ],
                [
                    'name'  => __('Create'),
                    'class' => 'active'
                ],
            ],
            'page_title'         => __("Create Dokan"),
            'property' => $this->currentProperty
        ];
        return view('Property::frontend.manageProperty.dokan.detail', $data);
    }

    public function edit(Request $request, $property_id, $id)
    {
        $this->checkPermission('property_update');

        if (!$this->hasPropertyPermission($property_id)) {
            abort(403);
        }

        $row = $this->dokanClass::find($id);
        if (empty($row) or $row->parent_id != $property_id) {
            return redirect(route('property.vendor.dokan.index', ['property_id' => $property_id]));
        }

        $translation = $row->translateOrOrigin($request->query('lang'));

        $data = [
            'row'            => $row,
            'translation'    => $translation,
            "selected_terms" => $row->terms->pluck('term_id'),
            'attributes'     => $this->attributesClass::where('service', 'dokan')->get(),
            'property_location' => $this->locationClass::where("status", "publish")->get()->toTree(),
            'enable_multi_lang' => true,
            'breadcrumbs'    => [
                [
                    'name' => __('Properties'),
                    'url'  => route('property.vendor.index')
                ],
                [
                    'name' => __('Property: :name', ['name' => $this->currentProperty->title]),
                    'url'  => route('property.vendor.edit', [$this->currentProperty->id])
                ],
                [
                    'name' => __('All Dokans'),
                    'url'  => route("property.vendor.dokan.index", ['property_id' => $this->currentProperty->id])
                ],
                [
                    'name' => __('Edit dokan: :name', ['name' => $row->title]),
                    'class' => 'active'
                ],
            ],
            'page_title' => __("Edit: :name", ['name' => $row->title]),
            'property' => $this->currentProperty
        ];
        return view('Property::frontend.manageProperty.dokan.detail', $data);
    }

    public function store(Request $request, $property_id, $id)
    {

        if (!$this->hasPropertyPermission($property_id)) {
            abort(403);
        }
        if ($id > 0) {
            $this->checkPermission('property_update');
            $row = $this->dokanClass::find($id);
            if (empty($row)) {
                return redirect(route('property.vendor.index'));
            }
            if ($row->parent_id != $property_id) {
                return redirect(route('property.vendor.dokan.index'));
            }
        } else {
            $this->checkPermission('property_create');
            $row = new $this->dokanClass();
            $row->status = "publish";
        }

        $dataKeys = [
            'title',
            'slug',
            'content',
            'business_type',
            'image_id',
            'banner_image_id',
            'person_profile_id',
            'video',
            'gallery',
            'location_id',
            'address',
            'map_lat',
            'map_lng',
            'map_zoom',
            'price',
            'number',
            'beds',
            'size',
            'adults',
            'children',
            'status',
            'email',
            'website',
            'contact_person',
            'phone',
            'contact_phone',
            'contact_whatsapp',
            'enable_open_hours',
            'open_hours',
            'products',
            'socials',
            'status',
        ];

        $row->fillByAttr($dataKeys, $request->input());
        $row->ical_import_url  = $request->ical_import_url;

        if (!empty($id) and $id == "-1") {
            $row->parent_id = $property_id;
        }

        $res = $row->saveOriginOrTranslation($request->input('lang'), true);

        if ($res) {
            if (!$request->input('lang') or is_default_lang($request->input('lang'))) {
                $this->saveTerms($row, $request);
            }

            if ($id > 0) {
                return redirect()->back()->with('success',  __('Dokan updated'));
            } else {
                return redirect(route('property.vendor.dokan.edit', ['property_id' => $property_id, 'id' => $row->id]))->with('success', __('Dokan created'));
            }
        }        //SEO
        $metaSeo = SEO::where('object_id', $row->id)->where('object_model', 'dokan')->first();
            $metaSeo->seo_index = 1;
            $metaSeo->seo_title = $row->property_page_list_seo_title;
            $metaSeo->seo_desc = $row->property_page_list_seo_desc;
            $metaSeo->seo_header = $row->property_page_list_seo_header;
            $metaSeo->seo_image = $row->property_page_list_seo_image;
            $metaSeo->seo_share = $row->property_page_list_seo_share;
            $metaSeo->save();

    }

    public function saveTerms($row, $request)
    {
        if (empty($request->input('terms'))) {
            $this->dokanTermClass::where('target_id', $row->id)->delete();
        } else {
            $term_ids = $request->input('terms');
            foreach ($term_ids as $term_id) {
                $this->dokanTermClass::firstOrCreate([
                    'term_id' => $term_id,
                    'target_id' => $row->id
                ]);
            }
            $this->dokanTermClass::where('target_id', $row->id)->whereNotIn('term_id', $term_ids)->delete();
        }
    }

    public function delete($property_id, $id)
    {
        $this->checkPermission('property_delete');
        $user_id = Auth::id();
        $query = $this->dokanClass::where("parent_id", $property_id)->where("id", $id)->first();
        if (!empty($query)) {
            $query->delete();
        }
        return redirect()->back()->with('success', __('Delete dokan success!'));
    }

    public function bulkEdit(Request $request, $property_id, $id)
    {
        $this->checkPermission('property_update');
        $action = $request->input('action');
        $user_id = Auth::id();
        $query = $this->dokanClass::where("parent_id", $property_id)->where("id", $id)->first();
        if (empty($id)) {
            return redirect()->back()->with('error', __('No item!'));
        }
        if (empty($action)) {
            return redirect()->back()->with('error', __('Please select an action!'));
        }
        if (empty($query)) {
            return redirect()->back()->with('error', __('Not Found'));
        }
        switch ($action) {
            case "make-hide":
                $query->status = "draft";
                break;
            case "make-publish":
                $query->status = "publish";
                break;
        }
        $query->save();
        return redirect()->back()->with('success', __('Update success!'));
    }
}
