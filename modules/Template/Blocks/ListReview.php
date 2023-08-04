<?php

namespace Modules\Template\Blocks;


use Modules\Template\Blocks\BaseBlock;
use Modules\Review\Models\Review;


class ListReview extends BaseBlock
{
    function __construct()
    {
        $this->setOptions([
            'settings' => [
                [
                    'id'        => 'title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Title')
                ],
                [
                    'id'        => 'sub_title',
                    'type'      => 'input',
                    'inputType' => 'text',
                    'label'     => __('Sub title')
                ],
                [
                    'id'        => 'number',
                    'type'      => 'input',
                    'inputType' => 'number',
                    'label'     => __('Number Item')
                ],
                [
                    'id'            => 'order',
                    'type'          => 'radios',
                    'label'         => __('Order'),
                    'values'        => [
                        [
                            'value'   => 'id',
                            'name' => __("Date Create")
                        ],
                        [
                            'value'   => 'title',
                            'name' => __("Title")
                        ],
                    ]
                ],
                [
                    'id'            => 'order_by',
                    'type'          => 'radios',
                    'label'         => __('Order By'),
                    'values'        => [
                        [
                            'value'   => 'asc',
                            'name' => __("ASC")
                        ],
                        [
                            'value'   => 'desc',
                            'name' => __("DESC")
                        ],
                    ]
                ],
            ],
            'category' => __("Other Block")
        ]);
    }

    public function getName()
    {
        return __('List Reviews');
    }

    public function content($model = [])
    {

        $model_property = Review::select("bc_review.*");

        if (empty($model['order'])) $model['order'] = "id";
        if (empty($model['order_by'])) $model['order_by'] = "desc";
        if (empty($model['number'])) $model['number'] = 5;

        $model_property->orderBy("bc_review." . $model['order'], $model['order_by']);
        $model_property->where("bc_review.status", "approved");
        $model_property->where("bc_review.is_homepage", 1);
        $model_property->groupBy("bc_review.id");
        $list = $model_property->limit($model['number'])->get();

        $data = [
            'rows'       => $list,
            'title'      => $model['title'],
            'sub_title'       => $model['sub_title'],
        ];

        return view('Template::frontend.blocks.review.index', $data);
    }
}
