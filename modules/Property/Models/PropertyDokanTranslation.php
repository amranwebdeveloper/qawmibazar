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

class PropertyDokanTranslation extends Bookable
{
    use SoftDeletes;
    protected $table = 'bc_property_dokan_translations';
    public $type = 'property_dokan_translation';

    protected $fillable = [
        'title',
        'content',
        'status',
        'products',
        'socials',
    ];

    protected $seo_type = 'dokan_translation';


    protected $casts = [
        'products' => 'array',
        'socials' => 'array',
    ];
    protected $bookingClass;
    protected $reviewClass;
    protected $propertyDateClass;
    protected $propertyDokanTermClass;
    protected $propertyTranslationClass;
    protected $userWishListClass;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->reviewClass = Review::class;
        $this->propertyDokanTermClass = PropertyDokanTerm::class;
        $this->propertyTranslationClass = PropertyTranslation::class;
        $this->userWishListClass = UserWishList::class;
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
}
