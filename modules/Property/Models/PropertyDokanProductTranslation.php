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
use Modules\User\Models\UserWishListProduct;

class PropertyDokanProductTranslation extends Bookable
{
    use SoftDeletes;
    protected $table = 'bc_property_dokan_product_translations';
    public $type = 'property_dokan_product_translation';

    protected $fillable = [
        'title',
        'content',
        'short_description',
        'specification',
        'status',
    ];

    protected $seo_type = 'product_translation';


    protected $casts = [
        'faqs' => 'array',
    ];
    protected $bookingClass;
    protected $reviewClass;
    protected $propertyDateClass;
    protected $propertyDokanProductTermClass;
    protected $propertyDokanTranslationClass;
    protected $userWishListClass;
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->bookingClass = Booking::class;
        $this->reviewClass = Review::class;
        $this->propertyDokanProductTermClass = PropertyDokanProductTerm::class;
        $this->propertyDokanTranslationClass = PropertyDokanTranslation::class;
        $this->userWishListClass = UserWishListProduct::class;
    }

    public static function getModelName()
    {
        return __("Product");
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
