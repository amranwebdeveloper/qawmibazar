<?php

namespace Modules\Property\Models;

use App\BaseModel;

class PropertyDokanProductCategoryTranslation extends BaseModel
{
    protected $table = 'bc_property_dokan_product_category_translations';
    protected $fillable = [
        'name',
        'content',
    ];
    protected $cleanFields = [
        'content'
    ];
}
