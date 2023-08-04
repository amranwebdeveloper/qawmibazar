<?php
namespace Modules\Property\Models;

use App\BaseModel;

class PropertyDokanTerm extends BaseModel
{
    protected $table = 'bc_property_dokan_term';
    protected $fillable = [
        'term_id',
        'target_id'
    ];
}
