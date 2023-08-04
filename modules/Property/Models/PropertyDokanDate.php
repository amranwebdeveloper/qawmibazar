<?php
namespace Modules\Property\Models;

use App\BaseModel;

class PropertyDokanDate extends BaseModel
{
    protected $table = 'bc_property_dokan_dates';

    protected $casts = [
        'person_types'=>'array'
    ];

    public static function getDatesInRanges($start_date,$end_date){
        return static::query()->where([
            ['start_date','>=',$start_date],
            ['end_date','<=',$end_date],
        ])->take(100)->get();
    }
}
