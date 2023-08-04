<?php

namespace Modules\Property\Models;

use App\BaseModel;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\SoftDeletes;

class PropertyDokanProductCategory extends BaseModel
{
    use SoftDeletes;
    use NodeTrait;
    protected $table = 'bc_property_dokan_product_categories';
    protected $fillable = [
        'name',
        'content',
        'slug',
        'status',
        'parent_id',
        'image_id',
        'banner_image_id'
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';

    public static function getModelName()
    {
        return __("Product Category");
    }

    public function property()
    {
        return $this->hasMany('Modules\Property\Models\PropertyDokanProduct', 'category_id');
    }

    public function propertyDokan()
    {
        return $this->hasMany('Modules\Property\Models\PropertyDokan', 'category_id');
    }

    public static function searchForMenu($q = false)
    {
        $query = static::select('id', 'name');
        if (strlen($q)) {
            $query->where('name', 'like', "%" . $q . "%");
        }
        $a = $query->limit(10)->get();
        return $a;
    }

    public function getDetailUrl()
    {
        return url(app_get_locale(false, false, '/') . config('property.property_cat_route_prefix') . '/' . $this->slug);
    }
}
