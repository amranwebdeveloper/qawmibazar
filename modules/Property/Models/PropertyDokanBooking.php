<?php

namespace Modules\Property\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Modules\Booking\Models\Bookable;
use Modules\Booking\Models\Booking;
use Modules\Core\Models\SEO;
use Modules\Media\Helpers\FileHelper;
use Modules\Review\Models\Review;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Property\Models\PropertyTranslation;
use Modules\User\Models\UserWishList;

class PropertyDokanBooking extends Bookable
{
    protected $table = 'bc_property_dokan_bookings';

    public static function getTableName()
    {
        return with(new static)->table;
    }

    public function scopeInRange($query,$start,$end){
        $query->where('bc_property_dokan_bookings.start_date','<=',$end)->where('bc_property_dokan_bookings.end_date','>',$start);
    }

    public function scopeActive($query)
    {
        return $query->join('bravo_bookings', function ($join) {
            $join->on('bravo_bookings.id', '=', $this->table . '.booking_id');
        })->whereNotIn('bravo_bookings.status', Booking::$notAcceptedStatus)->where('bravo_bookings.deleted_at', null);
    }

    public function dokan(){
        return $this->hasOne(PropertyDokan::class,'id','dokan_id')->withDefault();
    }
    public function booking(){
    	return $this->belongsTo(Booking::class,'booking_id');
    }

    public static function getByBookingId($id){
        return parent::query()->where([
            'booking_id'=>$id
        ])->get();
    }
}
