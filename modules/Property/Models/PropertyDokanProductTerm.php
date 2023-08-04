<?php
namespace Modules\Property\Models;

use App\BaseModel;

class PropertyDokanProductTerm extends BaseModel
{
    protected $table = 'bc_property_dokan_product_term';
    protected $fillable = [
        'term_id',
        'target_id'
    ];
}
