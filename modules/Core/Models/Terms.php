<?php
namespace Modules\Core\Models;

use App\BaseModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Terms extends BaseModel
{
    use SoftDeletes;
    protected $table = 'bc_terms';
    protected $fillable = [
        'name',
        'content',
        'image_id',
        'icon',
    ];
    protected $slugField     = 'slug';
    protected $slugFromField = 'name';

    /**
     * @param $term_IDs array or number
     * @return mixed
     */
    static public function getTermsById($term_IDs){
        $listTerms = [];
        if(empty($term_IDs)) return $listTerms;
        $terms = parent::query()->with(['translations','attribute'])->find($term_IDs);
        if(!empty($terms)){
            foreach ($terms as $term){
                if(!empty($attr = $term->attribute)){
                    if(empty($listTerms[$term->attr_id]['child'])) $listTerms[$term->attr_id]['parent'] = $attr;
                    if(empty($listTerms[$term->attr_id]['child'])) $listTerms[$term->attr_id]['child'] = [];
                    $listTerms[$term->attr_id]['child'][] = $term;
                }
            }
        }
        return $listTerms;
    }

    public function attribute()
    {
        return $this->hasOne("Modules\Core\Models\Attributes", "id" , "attr_id");
    }


    public static function getForSelect2Query($service,$q=false)
    {
        $query =  static::query()->select('bc_terms.id', DB::raw('CONCAT(at.name,": ",bc_terms.name) as text'))
        ->join('bc_attrs as at','at.id','=','bc_terms.attr_id')
        ->where("at.service",$service)
        ->whereRaw("at.deleted_at is null");

        if ($query) {
            $query->where('bc_terms.name', 'like', '%' . $q . '%');
        }
        return $query;
    }

    static public function getTermsByIdForAPI($term_IDs){
        $listTerms = null;
        if(empty($term_IDs)) return $listTerms;
        $terms = parent::query()->with(['translations','attribute'])->find($term_IDs);
        if(!empty($terms)){
            foreach ($terms as $term){
                $attr = $term->attribute;
                $attrTranslation = $attr->translateOrOrigin(app()->getLocale());
                $dataAttr = array(
                    'id'=>$attr->id,
                    'title'=>$attrTranslation->name,
                    'slug'=>$attr->slug,
                    'service'=>$attr->service,
                    'display_type'=>$attr->display_type,
                    'hide_in_single'=>$attr->hide_in_single,
                );
                if(!empty($dataAttr) and empty($dataAttr['hide_in_single'])){
                    if(empty($listTerms[$term->attr_id]['child'])) $listTerms[$term->attr_id]['parent'] = $dataAttr;
                    if(empty($listTerms[$term->attr_id]['child'])) $listTerms[$term->attr_id]['child'] = [];
                    $termTranslation = $term->translateOrOrigin(app()->getLocale());
                    $dataAttr = array(
                        'id'=>$term->id,
                        'title'=>$termTranslation->name,
                        'content'=>$term->content,
                        'image_id'=>get_file_url($term->image_id,'full'),
                        'icon'=>$term->icon,
                        'attr_id'=>$term->attr_id,
                        'slug'=>$term->slug,
                    );
                    $listTerms[$term->attr_id]['child'][] = $dataAttr;
                }
            }
        }
        return $listTerms;
    }

    public function dataForApi(){
        $translation = $this->translateOrOrigin(app()->getLocale());
        return [
            'id'=>$this->id,
            'name'=>$translation->name,
            'slug'=>$this->slug,
        ];
    }
    public function getTermDetailUrl($attribute_slug)
    {
        return url(app_get_locale(false, false, '/') . config('property.property_attribute_route_prefix') . '/' . $attribute_slug . '/' . $this->slug);
    }
}
