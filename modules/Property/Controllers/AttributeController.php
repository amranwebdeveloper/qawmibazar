<?php

namespace Modules\Property\Controllers;


use App\Http\Controllers\Controller;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyDokan;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationCategory;
use Modules\Property\Models\PropertyCategory;
use Modules\Property\Models\PropertyCategoryTranslation;
use Illuminate\Support\Str;
use Modules\AdminController;
use Modules\Core\Models\Attributes;
use Modules\Core\Models\AttributesTranslation;
use Modules\Core\Models\Terms;
use Modules\Core\Models\TermsTranslation;
use Illuminate\Support\Facades\DB;

class AttributeController extends Controller
{
    protected $attributesClass;
    protected $termsClass;
    public $property_category;
    public $property;
    public function __construct(PropertyCategory $property_category, Property $property)
    {
        $this->attributesClass = Attributes::class;
        $this->termsClass = Terms::class;
        $this->property_category = $property_category;
        $this->property = $property;
    }
    public function index(Request $request)
    {
        $listAttr = $this->attributesClass::where("service", 'property');

        if (!empty($search = $request->query('s'))) {
            $listAttr->where('name', 'LIKE', '%' . $search . '%');
        }
        $listAttr->orderBy('created_at', 'desc');
        $data = [

            'rows'        => $listAttr->get(),
            'row'         => new $this->attributesClass(),
            'propertyAttributeHeader'    => new AttributesTranslation(),
            'breadcrumbs' => [
                [
                    'name' => __('Property'),
                    'url'  => 'frontend/property'
                ],
                [
                    'name'  => __('Attribute'),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Property::frontend.attribute.index', $data);
    }
    public function detail(Request $request, $slug)
    {
        $row = $this->attributesClass::where('slug', $slug)->first();
        if (empty($row)) {
            return redirect('/');
        }
        $listTerms = $this->termsClass::where("attr_id", $row->id);
        if (!empty($search = $request->query('s'))) {
            $listTerms->where('name', 'LIKE', '%' . $search . '%');
        }
        $listTerms->orderBy('created_at', 'desc');

        $translation = $row->translateOrOrigin(app()->getLocale());
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'rows'  => $listTerms->paginate(20),
            'attribute' => $row,
            "row"         => new $this->termsClass(),
            'translation'    => new TermsTranslation(),
            'property_min_max_price' => Property::getMinMaxPrice(),
            'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
            'breadcrumbs' => [
                [
                    'name' => __('Property'),
                    'url'  => 'frontend/property'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'frontend/property/attribute'
                ],
                [
                    'name'  => __('Attribute: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        $this->setActiveMenu($row);
        return view('Property::frontend.attribute.detail', $data);
    }

    public function term_detail(Request $request, $slug, $term_slug)
    {

        $attributerow = $this->attributesClass::where('slug', $slug)->first();
        $row = $this->termsClass::where('slug', $term_slug)->where('attr_id', $attributerow->id)->first();
        if (empty($row)) {
            return redirect()->back()->with('error', __('Term not found'));
        }
        $translation = $row->translateOrOrigin($request->query('lang'));
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'row'         => $row,
            'attribute'         => $attributerow,
            'translation'    => $translation,
            'list_location'      => Location::where('status', 'publish')->limit($limit_location)->with(['translations'])->get(),
            'list_category'      => PropertyCategory::where('status', 'publish')->get()->toTree(),
            'enable_multi_lang' => true,
            'property_min_max_price' => Property::getMinMaxPrice(),
            'rows' => $this->property->getListPropertiesByTerm($row->id),
            'seo_meta' => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
            'breadcrumbs' => [
                [
                    'name' => __('Property'),
                    'url'  => 'frontend/property'
                ],
                [
                    'name' => __('Attributes'),
                    'url'  => 'frontend/property/attribute'
                ],
                [
                    'name' => $attributerow->name,
                    'url'  => 'frontend/property/attribute/' . $attributerow->slug
                ],
                [
                    'name'  => __('Term: :name', ['name' => $row->name]),
                    'class' => 'active'
                ],
            ]
        ];
        return view('Property::frontend.attribute.term-detail', $data);
    }


    public function getForSelect2(Request $request)
    {
        $pre_selected = $request->query('pre_selected');
        $selected = $request->query('selected');

        if ($pre_selected && $selected) {
            if (is_array($selected)) {
                $query = $this->termsClass::getForSelect2Query('property');
                $items = $query->whereIn('bc_terms.id', $selected)->take(50)->get();
                return response()->json([
                    'items' => $items
                ]);
            }

            if (empty($item)) {
                return response()->json([
                    'text' => ''
                ]);
            } else {
                return response()->json([
                    'text' => $item->name
                ]);
            }
        }
        $q = $request->query('q');
        $query = $this->termsClass::getForSelect2Query('property', $q);
        $res = $query->orderBy('bc_terms.id', 'desc')->limit(20)->get();
        return response()->json([
            'results' => $res
        ]);
    }
}
