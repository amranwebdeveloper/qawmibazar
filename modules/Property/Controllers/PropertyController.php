<?php

namespace Modules\Property\Controllers;

use App\Http\Controllers\Controller;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyDokan;
use Illuminate\Http\Request;
use Modules\Location\Models\Location;
use Modules\Location\Models\LocationCategory;
use Modules\Review\Models\Review;
use Modules\Core\Models\Attributes;
use Modules\News\Models\News;
use Modules\Core\Models\SEO;
use DB;
use Modules\Property\Models\PropertyCategory;
use Modules\Property\Models\PropertyDokanProductCategory;
use Modules\Property\Models\PropertyDokanProduct;

class PropertyController extends Controller
{
    protected $propertyClass;
    protected $newsClass;
    protected $propertyDokanClass;
    protected $propertyDokanProductClass;
    protected $locationClass;
    protected $locationCategoryClass;
    protected $propertyCategoryClass;
    protected $propertyDokanProductCategoryClass;
    protected $attributeClass;
    protected $reviewClass;
    public function __construct()
    {
        $this->propertyClass            = Property::class;
        $this->newsClass                = News::class;
        $this->propertyDokanClass    = PropertyDokan::class;
        $this->propertyDokanProductClass    = PropertyDokanProduct::class;
        $this->locationClass         = Location::class;
        $this->propertyCategoryClass = PropertyCategory::class;
        $this->propertyDokanProductCategoryClass = PropertyDokanProductCategory::class;
        $this->attributeClass        = Attributes::class;
        $this->reviewClass           = Review::class;
        $this->locationCategoryClass = LocationCategory::class;
    }

    public function callAction($method, $parameters)
    {
        if (!Property::isEnable()) {
            return redirect('/');
        }
        return parent::callAction($method, $parameters); // TODO: Change the autogenerated stub
    }

    public function index(Request $request)
    {
        $is_ajax = $request->query('_ajax');
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
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'rows'               => $list,
            'list_location'      => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'list_category'      => $this->propertyCategoryClass::where('status', 'publish')->get()->toTree(),
            'property_min_max_price' => $this->propertyClass::getMinMaxPrice(),
            'markers'            => $markers,
            "blank"              => 1,
            "filter"             => $request->query('filter'),
            "seo_meta"           => $this->propertyClass::getSeoMetaForPageList()
        ];

        if ($is_ajax) {
            return $this->sendSuccess([
                'html'    => view('Property::frontend.layouts.search-map.list-item', $data)->render(),
                "markers" => $data['markers']
            ]);
        }
        $data['attributes'] = $this->attributeClass::where('service', 'property')->with(['terms', 'translations'])->get();

        $layout = $request->input('_layout') ? $request->input('_layout') : setting_item("property_page_search_layout", 1);

        if ($layout == "map1") {
            return view('Property::frontend.search-map', $data);
        }

        $data['view'] = 'Property::frontend.layouts.search.list-item-v1';
        if ($layout == 'v1') {
            $data['view'] = 'Property::frontend.layouts.search.list-item-v1';
        }

        return view('Property::frontend.search', $data);
    }

    public function detail(Request $request, $slug)
    {
        $layout_id = $request->input('layout') ? $request->input('layout') : setting_item("property_page_single_layout", 1);
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $row = $this->propertyClass::where('slug', $slug)->with(['location', 'translations', 'hasWishList', 'user'])->first();

        $news = $this->newsClass::where('property_id', $row->id)->orWhere('location_id',  $row->location_id)->orderBy('created_at', 'desc')->limit(3)->get();
        if (empty($row) or !$row->hasPermissionDetailView()) {
            return redirect('/');
        }
        $translation = $row->translateOrOrigin(app()->getLocale());
        $property_related = [];
        $location_id = $row->location_id;
        if (!empty($location_id)) {
            $property_related = $this->propertyClass::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$row->id])->with(['location', 'translations', 'hasWishList'])->get();
        }
        $review_list = Review::where('object_id', $row->id)->where('object_model', 'property')->where("status", "approved")->orderBy("id", "desc")->with('author')->paginate(setting_item('property_review_number_per_page', 5));
        $row->view = $row->view + 1;
        $row->save();
        $category = $row->categories->first();
        $data = [
            'row'               => $row,
            'news'              => $news,
            'propertyDokans'    => $this->propertyDokanClass::where('parent_id', $row->id)->where('status', 'publish')->with(['location', 'translations', 'user'])->get(),
            'translation'       => $translation,
            'property_related'  => $property_related,
            'booking_data'      => $row->getBookingData(),
            'list_location'     => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'location_category' => $this->locationCategoryClass::where("status", "publish")->with('location_category_translations')->get(),
            'list_category'     => $this->propertyCategoryClass::where('status', 'publish')->get()->toTree(),
            'property_min_max_price' => $this->propertyClass::getMinMaxPrice(),
            'review_list'       => $review_list,
            'seo_meta'          => $row->getSeoMetaWithTranslation(app()->getLocale(), $translation),
            'body_class'        => 'is_single',
            'breadcrumbs' => [
                [
                    'name' => __('Businesss'),
                    'url'  => 'listing'
                ],
//                [
//                    'name' => __($category->name),
//                    'url'  => $category->getDetailUrl()
//                ],
                [
                    'name'  => __($row->title),
                    'class' => 'active'
                ],
            ],
        ];
        $this->setActiveMenu($row);
        $blade = 'Property::frontend.detail';
        if ($layout_id == 1) {
            $blade = 'Property::frontend.detail';
        } elseif ($layout_id == 2) {
            $blade = 'Property::frontend.detail_v2';
        } elseif ($layout_id == 3) {
            $blade = 'Property::frontend.detail_v3';
        }

        return view($blade, $data);
    }
    public function searchForSelect2(Request $request)
    {
        $search = $request->query('q');
        $query = Property::where("bc_properties.status", "publish");
        if ($search) {
            $query->where('bc_properties.title', 'like', '%' . $search . '%');

            if (setting_item('site_enable_multi_lang') && setting_item('site_locale') != app()->getLocale()) {

                $query->orWhere(function ($query) use ($search) {
                    $query->where('bc_property_translations.name', 'LIKE', '%' . $search . '%');
                });
            }
        }
        $res = $query->orderBy('title', 'asc')->limit(20)->get();
        if (!empty($res) and count($res)) {
            $list_json = [];
            foreach ($res as $value) {
                $translate = $value->translateOrOrigin(app()->getLocale());
                $list_json[] = [
                    'id' => $value->id,
                    'text' => $translate->title,
                    'href' => $value->getDetailUrl(),
                    'html' => '<div class="property_city_home6 tac-xsd">
													<div class="thumb">
                                                        <a href="' . $value->getDetailUrl() . '">
                                                            <img class="w100" src="' . $value->image_url . '" alt="Miami">
                                                        </a>
                                                    </div>
													<div class="details">
                                                        <a href="' . $value->location->getDetailUrl() . '">
                                                            <h4>' . $translate->title . '</h4>
                                                            <p>' . $value->location->name . '</p>
                                                        </a>
                                                    </div>
												</div>'
                ];
            }
            return response()->json(['results' => $list_json]);
        }
        return response()->json(['results' => [], 'message' => __("Not found")]);
    }

    public function dokan_index(Request $request)
    {
        $is_ajax = $request->query('_ajax');
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
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'rows'               => $list,
            'list_location'      => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'list_category'      => $this->propertyCategoryClass::where('status', 'publish')->get()->toTree(),
            'property_min_max_price' => $this->propertyClass::getMinMaxPrice(),
            'markers'            => $markers,
            "blank"              => 1,
            "filter"             => $request->query('filter'),
            "seo_meta"           => $this->propertyDokanClass::getSeoMetaForPageList()
        ];

        if ($is_ajax) {
            return $this->sendSuccess([
                'html'    => view('Property::frontend.layouts.search-map.list-item', $data)->render(),
                "markers" => $data['markers']
            ]);
        }
        $data['attributes'] = $this->attributeClass::where('service', 'property')->with(['terms', 'translations'])->get();

        $layout = $request->input('_layout') ? $request->input('_layout') : setting_item("property_page_search_layout", 1);

        if ($layout == "map1") {
            return view('Property::frontend.search-map', $data);
        }

        $data['view'] = 'Property::frontend.layouts.search.list-item-v1';
        if ($layout == 'v1') {
            $data['view'] = 'Property::frontend.layouts.search.list-item-v1';
        }

        return view('Property::frontend.search', $data);
    }
    public function dokan_detail(Request $request, $slug, $slug_dokan)
    {
        $layout_id = $request->input('layout') ? $request->input('layout') : setting_item("property_page_single_layout", 1);
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $dokanrow = $this->propertyDokanClass::where('slug', $slug_dokan)->with(['location', 'translations', 'user'])->first();
        if (empty($dokanrow) or !$dokanrow->hasPermissionDetailView()) {
            return redirect('/');
        }
        $translation = $dokanrow->translateOrOrigin(app()->getLocale());
        $property_related = [];
        $location_id = $dokanrow->location_id;
        if (!empty($location_id)) {
            $property_related = $this->propertyClass::where('location_id', $location_id)->where("status", "publish")->take(4)->whereNotIn('id', [$dokanrow->id])->with(['location', 'translations', 'hasWishList'])->get();
        }
        $review_list = Review::where('object_id', $dokanrow->id)->where('object_model', 'dokan')->where("status", "approved")->orderBy("id", "desc")->with('author')->paginate(setting_item('property_review_number_per_page', 5));
        $dokanrow->view = $dokanrow->view + 1;
        $dokanrow->save();

        $row = $this->propertyClass::where('slug', $slug)->with(['location', 'translations', 'hasWishList', 'user'])->first();
        $seo_meta=$dokanrow->getSeoMetaWithTranslation(app()->getLocale(), $translation);
        $seo_meta['full_url'] = url("/").'/'.$slug.'/'.$dokanrow->slug;
        $category = $row->categories->first();
        $data = [
            'row'               => $row,
            'dokanrow'          => $dokanrow,
            'products'          => $this->propertyDokanProductClass::where('parent_id', $dokanrow->id)->where('status', 'publish')->with(['translations', 'user'])->get(),
            'translation'       => $translation,
            'property_related'  => $property_related,
            // 'booking_data'      => $dokanrow->getBookingData(),
            'list_location'     => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'list_category'     => $this->propertyCategoryClass::where('status', 'publish')->get()->toTree(),
            'property_min_max_price' => $this->propertyClass::getMinMaxPrice(),
            'review_list'       => $review_list,
            "seo_meta"          => $seo_meta,
            'body_class'        => 'is_single',
            'breadcrumbs' => [
                [
                    'name' => __('Businesss'),
                    'url'  => 'listing'
                ],
//                [
//                    'name' => __($category->name),
//                    'url'  => $category->getDetailUrl()
//                ],
                [
                    'name'  => __($row->title),
                    'url'  => $row->getDetailUrl()
                ],
                [
                    'name'  => __($dokanrow->title),
                    'class' => 'active'
                ],
            ],
        ];
        $this->setActiveMenu($dokanrow);

        return view('Property::frontend.layouts.details.dokan-details', $data);
    }

     public function product_index(Request $request)
    {
        $is_ajax = $request->query('_ajax');
        $list = call_user_func([$this->propertyDokanProductClass, 'search'], $request);
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
        $limit_location = 48;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $data = [
            'rows'         => $this->propertyDokanProductClass::where('status', 'publish')->limit($limit_location)->get(),
            'list_location'      => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
//            'category'           => $this->propertyDokanProductCategoryClass::where('status', 'publish')->where('id', $productrows->category_id)->first(),
//            'dokanrow'           => $this->propertyDokanClass::where('id', $productrows->parent_id)->with(['location', 'translations', 'user'])->first(),
//            'propertyrow'        => $this->propertyDokanProductClass::where('id', $productrows->parent_id)->where('status', 'publish')->with(['translations', 'user'])->first(),
            'product_min_max_price' => $this->propertyDokanProductClass::getMinMaxPrice(),
            'markers'            => $markers,
            "blank"              => 1,
            "filter"             => $request->query('filter'),
            "seo_meta"           => $this->propertyDokanProductClass::getSeoMetaForPageList()
        ];

        if ($is_ajax) {
            return $this->sendSuccess([
                'html'    => view('Property::frontend.layouts.search-map.list-item', $data)->render(),
                "markers" => $data['markers']
            ]);
        }
        $data['attributes'] = $this->attributeClass::where('service', 'property')->with(['terms', 'translations'])->get();


        return view('Property::frontend.product.index', $data);
    }
    public function product_detail(Request $request, $slug, $slug_dokan, $slug_product)
    {
        $layout_id = $request->input('layout') ? $request->input('layout') : setting_item("property_page_single_layout", 1);
        $limit_location = 15;
        if (empty(setting_item("property_location_search_style")) or setting_item("property_location_search_style") == "normal") {
            $limit_location = 1000;
        }
        $dokanrow = $this->propertyDokanClass::where('slug', $slug_dokan)->with(['location', 'translations', 'user'])->first();
        $productrow = $this->propertyDokanProductClass::where('slug', $slug_product)->with(['translations', 'user'])->first();
        if (empty($dokanrow) or !$dokanrow->hasPermissionDetailView()) {
            return redirect('/');
        }
        $translation = $productrow->translateOrOrigin(app()->getLocale());
        $product_related = [];

        $product_related = $this->propertyDokanProductClass::where("status", "publish")->where('category_id',$productrow->category_id)->orWhere('parent_id',$productrow->parent_id)->take(6)->whereNotIn('id', [$productrow->id])->with(['translations'])->get();

        $review_list = Review::where('object_id', $dokanrow->id)->where('object_model', 'dokan')->where("status", "approved")->orderBy("id", "desc")->with('author')->paginate(setting_item('property_review_number_per_page', 5));
        $dokanrow->view = $dokanrow->view + 1;
        $dokanrow->save();

        $row = $this->propertyClass::where('slug', $slug)->with(['location', 'translations', 'hasWishList', 'user'])->first();

        $seo_meta=$dokanrow->getSeoMetaWithTranslation(app()->getLocale(), $translation);
        $seo_meta['full_url'] = url("/").'/'.$slug.'/'.$slug_dokan.'/'.$productrow->slug;
        $category = $row->categories->first();
        $data = [
            'row'               => $row,
            'dokanrow'          => $dokanrow,
            'productrow'        => $productrow,
            'translation'       => $translation,
            'product_related'  => $product_related,
            // 'booking_data'      => $dokanrow->getBookingData(),
            'list_location'     => $this->locationClass::where('status', 'publish')->limit($limit_location)->with(['translations'])->get()->toTree(),
            'category'     => $this->propertyDokanProductCategoryClass::where('status', 'publish')->where('id',$productrow->category_id)->first(),
            'property_min_max_price' => $this->propertyClass::getMinMaxPrice(),
            'review_list'       => $review_list,
            'seo_meta'          => $seo_meta,
            'body_class'        => 'is_single',
            'breadcrumbs' => [
                [
                    'name' => __('Businesss'),
                    'url'  => 'listing'
                ],
//                [
//                    'name' => __($category->name),
//                    'url'  => $category->getDetailUrl()
//                ],
                [
                    'name'  => __($row->title),
                    'url'  => $row->getDetailUrl()
                ],
                [
                    'name'  => __($dokanrow->title),
                    'url'  => url('/').'/listing/'.$row->slug.'/'.$dokanrow->slug
                ],
                [
                    'name'  => __($productrow->title),
                    'class' => 'active'
                ],
            ],
        ];
        $this->setActiveMenu($dokanrow);

        return view('Property::frontend.layouts.details.product-details', $data);
    }
    public function checkAvailability()
    {
        $property_id = \request('property_id');
        if (\request()->input('firstLoad') == "false") {
            $rules = [
                'property_id'   => 'required',
                'start_date' => 'required:date_format:Y-m-d',
                'end_date'   => 'required:date_format:Y-m-d',
                'adults'     => 'required',
            ];
            $validator = \Validator::make(request()->all(), $rules);
            if ($validator->fails()) {
                return $this->sendError($validator->errors()->all());
            }

            if (strtotime(\request('end_date')) - strtotime(\request('start_date')) < DAY_IN_SECONDS) {
                return $this->sendError(__("Dates are not valid"));
            }
            if (strtotime(\request('end_date')) - strtotime(\request('start_date')) > 30 * DAY_IN_SECONDS) {
                return $this->sendError(__("Maximum day for booking is 30"));
            }
        }

        $property = $this->propertyClass::find($property_id);
        if (empty($property_id) or empty($property)) {
            return $this->sendError(__("Property not found"));
        }

        if (\request()->input('firstLoad') == "false") {
            $numberDays = abs(strtotime(\request('end_date')) - strtotime(\request('start_date'))) / 86400;
            if (!empty($property->min_day_stays) and  $numberDays < $property->min_day_stays) {
                return $this->sendError(__("You must to book a minimum of :number days", ['number' => $property->min_day_stays]));
            }

            if (!empty($property->min_day_before_booking)) {
                $minday_before = strtotime("today +" . $property->min_day_before_booking . " days");
                if (strtotime(\request('start_date')) < $minday_before) {
                    return $this->sendError(__("You must book the service for :number days in advance", ["number" => $property->min_day_before_booking]));
                }
            }
        }

        $dokans = $property->getDokansAvailability(request()->input());

        return $this->sendSuccess([
            'dokans' => $dokans
        ]);
    }
}