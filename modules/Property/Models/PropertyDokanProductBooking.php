<?php


namespace Modules\Property\Models;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use ICal\ICal;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Helpers\FileHelper;
use Modules\User\Models\UserWishListProduct;
use Modules\Core\Models\SEO;
use Modules\Review\Models\Review;
use Modules\Property\Models\PropertyTranslation;

class PropertyDokanProductBooking extends Bookable
{
    use SoftDeletes;
    protected $table = 'bc_property_dokan_products';
    public $type = 'product';
    public $availabilityClass = PropertyDokanDate::class;

    protected $fillable = [
        'title',
        'content',
        'status',
        'price',
    ];

    protected $seo_type = 'product';
    protected $casts = [
        'faqs'  => 'array',
    ];

    protected $userWishListDokanProductClass;
    protected $bookingClass;
    protected $dokanDateClass;
    protected $propertyDokanTermClass;
    protected $dokanBookingClass;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->dokanDateClass = PropertyDokanDate::class;
        $this->userWishListDokanProductClass = UserWishListProduct::class;
        $this->propertyDokanTermClass = PropertyDokanTerm::class;
        $this->dokanBookingClass = PropertyDokanBooking::class;
    }

    public static function getModelName()
    {
        return __("Dokan");
    }

    public static function getTableName()
    {
        return with(new static)->table;
    }


    public function terms()
    {
        return $this->hasMany($this->propertyDokanTermClass, "target_id");
    }

    public function isAvailableAt($filters = [])
    {

        if (empty($filters['start_date']) or empty($filters['end_date'])) return true;

        $filters['end_date'] = date("Y-m-d", strtotime($filters['end_date'] . " -1day"));

        $dokanDates =  $this->getDatesInRange($filters['start_date'], $filters['end_date']);
        $allDates = [];
        $tmp_price = 0;
        $tmp_night = 0;
        $period = periodDate($filters['start_date'], $filters['end_date'], true);
        foreach ($period as $dt) {
            $allDates[$dt->format('Y-m-d')] = [
                'number' => $this->number,
                'price' => $this->price
            ];
            $tmp_night++;
        }
        if (!empty($dokanDates)) {
            foreach ($dokanDates as $row) {
                if (!$row->active or !$row->number or !$row->price) return false;

                if (!array_key_exists(date('Y-m-d', strtotime($row->start_date)), $allDates)) continue;

                $allDates[date('Y-m-d', strtotime($row->start_date))] = [
                    'number' => $row->number,
                    'price' => $row->price
                ];
            }
        }

        $dokanBookings = $this->getBookingsInRange($filters['start_date'], $filters['end_date']);
        if (!empty($dokanBookings)) {
            foreach ($dokanBookings as $dokanBooking) {
                $period = periodDate($dokanBooking->start_date, $dokanBooking->end_date, false);
                foreach ($period as $dt) {
                    $date = $dt->format('Y-m-d');
                    if (!array_key_exists($date, $allDates)) continue;
                    $allDates[$date]['number'] -= $dokanBooking->number;
                    if ($allDates[$date]['number'] <= 0) {
                        return false;
                    }
                }
            }
        }

        if (!empty($this->ical_import_url)) {
            $startDate = $filters['start_date'];
            $endDate = $filters['end_date'];
            $timezone = setting_item('site_timezone', config('app.timezone'));
            try {
                $icalevents   =  new Ical($this->ical_import_url, [
                    'defaultTimeZone' => $timezone
                ]);
                $eventRange  = $icalevents->eventsFromRange($startDate, $endDate);
                if (!empty($eventRange)) {
                    foreach ($eventRange as $item => $value) {
                        if (!empty($date = $value->dtstart_array[2])) {
                            $allDates[date('Y-m-d', $date)]['number'] -= 1;
                            if ($allDates[date('Y-m-d', $date)]['number'] <= 0) {
                                return false;
                            }
                        }
                    }
                }
            } catch (\Exception $exception) {
                return $this->sendError($exception->getMessage());
            }
        }

        $this->tmp_number = !empty($allDates) ?  (int) min(array_column($allDates, 'number')) : 0;
        if (empty($this->tmp_number)) return false;

        //Adult - Children
        if (!empty($filters['adults']) and $this->adults * $this->tmp_number < $filters['adults']) {
            return false;
        }
        if (!empty($filters['children']) and $this->children * $this->tmp_number < $filters['children']) {
            return false;
        }

        $this->tmp_price = array_sum(array_column($allDates, 'price'));
        $this->tmp_dates = $allDates;
        $this->tmp_nights = $tmp_night;

        return true;
    }
    public function getBannerImagesDokan()
    {
        if (empty($this->banner_images))
            return $this->banner_images;
        $list_item = [];
        $items = explode(",", $this->banner_images);
        foreach ($items as $k => $item) {
            if ($item) {
                $large = FileHelper::url($item, 'full');
                $thumb = FileHelper::url($item, 'thumb');
                $list_item[] = [
                    'large' => $large,
                    'thumb' => $thumb
                ];
            }
        }
        return $list_item;
    }
    public function getDatesInRange($start_date, $end_date)
    {
        $query = $this->dokanDateClass::query();
        $query->where('target_id', $this->id);
        $query->where('start_date', '>=', date('Y-m-d H:i:s', strtotime($start_date)));
        $query->where('end_date', '<=', date('Y-m-d H:i:s', strtotime($end_date)));

        return $query->take(100)->get();
    }

    public function getBookingsInRange($from, $to)
    {
        return $this->dokanBookingClass::query()
            ->where('bc_property_dokan_bookings.dokan_id', $this->id)
            ->active()
            ->inRange($from, $to)
            ->get(['bc_property_dokan_bookings.*']);
    }

    public function getGallery($featuredIncluded = false)
    {
        if (empty($this->gallery))
            return $this->gallery;
        $list_item = [];
        if ($featuredIncluded and $this->image_id) {
            $list_item[] = [
                'large' => FileHelper::url($this->image_id, 'full'),
                'thumb' => FileHelper::url($this->image_id, 'thumb')
            ];
        }
        $items = explode(",", $this->gallery);
        foreach ($items as $k => $item) {
            $large = FileHelper::url($item, 'full');
            $thumb = FileHelper::url($item, 'thumb');
            if (!$thumb) continue;
            $list_item[] = [
                'large' => $large,
                'thumb' => $thumb
            ];
        }
        return $list_item;
    }
    public function getDetailUrl()
    {
        return url(app_get_locale(false, false, '/') . config('property.property_dokan_route_prefix') . '/' . $this->slug);
    }
    public function hasWishListDokan()
    {
        return $this->hasOne($this->userWishListDokanClass, 'object_id', 'id')->where('object_model', 'dokan')->where('user_id', Auth::id() ?? 0);
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'create_user', 'id');
    }
    public function isWishListDokan()
    {
        if (Auth::id()) {
            if (!empty($this->hasWishListDokan) and !empty($this->hasWishListDokan->id)) {
                return 'active';
            }
        }
        return '';
    }
    static public function getSeoMetaForPageList()
    {
        $meta['seo_title'] = __("Search for Properties");
        if (!empty($title = setting_item_with_lang("property_page_list_seo_title", false))) {
            $meta['seo_title'] = $title;
        } else if (!empty($title = setting_item_with_lang("property_page_search_title"))) {
            $meta['seo_title'] = $title;
        }
        $meta['seo_image'] = null;
        if (!empty($title = setting_item("property_page_list_seo_image"))) {
            $meta['seo_image'] = $title;
        } else if (!empty($title = setting_item("property_page_search_banner"))) {
            $meta['seo_image'] = $title;
        }
        $meta['seo_desc'] = setting_item_with_lang("property_page_list_seo_desc");
        $meta['seo_header'] = setting_item_with_lang("property_page_list_seo_header");
        $meta['seo_share'] = setting_item_with_lang("property_page_list_seo_share");
        $meta['full_url'] = url(config('property.property_route_prefix'));
        return $meta;
    }

    public function getReviewEnable()
    {
        return setting_item("dokan_enable_review", 0);
    }

    public function getReviewApproved()
    {
        return setting_item("dokan_review_approved", 0);
    }

    public function check_enable_review_after_booking()
    {
        $option = setting_item("dokan_enable_review_after_booking", 0);
        if ($option) {
            $number_review = $this->reviewClass::countReviewByServiceID($this->id, Auth::id()) ?? 0;
            $number_booking = $this->bookingClass::countBookingByServiceID($this->id, Auth::id()) ?? 0;
            if ($number_review >= $number_booking) {
                return false;
            }
        }
        return true;
    }


    public function check_allow_review_after_making_completed_booking()
    {
        $options = setting_item("property_allow_review_after_making_completed_booking", false);
        if (!empty($options)) {
            $status = json_decode($options);
            $booking = $this->bookingClass::select("status")
                ->where("object_id", $this->id)
                ->where("object_model", $this->type)
                ->where("customer_id", Auth::id())
                ->orderBy("id", "desc")
                ->first();
            $booking_status = $booking->status ?? false;
            if (!in_array($booking_status, $status)) {
                return false;
            }
        }
        return true;
    }

    public static function getReviewStats()
    {
        $reviewStats = [];
        if (!empty($list = setting_item("dokan_review_stats", []))) {
            $list = json_decode($list, true);
            foreach ($list as $item) {
                $reviewStats[] = $item['title'];
            }
        }
        return $reviewStats;
    }

    public function getNumberReviewsInService($status = false)
    {
        return $this->reviewClass::countReviewByServiceID($this->id, false, $status, $this->type) ?? 0;
    }
}
