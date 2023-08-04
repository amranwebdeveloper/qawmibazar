<?php

namespace Modules\Property;

use Modules\Core\Helpers\SitemapHelper;
use Modules\ModuleServiceProvider;
use Modules\Property\Models\Property;
use Modules\Property\Models\PropertyDokan;
use Modules\Property\Models\PropertyDokanProduct;

class ModuleProvider extends ModuleServiceProvider
{

    public function boot(SitemapHelper $sitemapHelper)
    {

        $this->loadMigrationsFrom(__DIR__ . '/Migrations');

        if (is_installed() and Property::isEnable()) {

            $sitemapHelper->add("property", [app()->make(Property::class), 'getForSitemap']);
        }
    }
    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->register(RouterServiceProvider::class);
    }

    public static function getAdminMenu()
    {
        if (!Property::isEnable()) return [];
        return [
            'property' => [
                "position" => 10,
                'url'        => 'admin/module/property',
                'title'      => __('Business'),
                'icon'       => 'ion ion-md-home',
                'permission' => 'property_view',
                'children'   => [
                    'add' => [
                        'url'        => 'admin/module/property',
                        'title'      => __('All Businesses'),
                        'permission' => 'property_view',
                    ],
                    'create' => [
                        'url'        => 'admin/module/property/create',
                        'title'      => __('Add new Business'),
                        'permission' => 'property_create',
                    ],
                    'property_category' => [
                        'url'        => 'admin/module/property/category',
                        'title'      => __('Categories'),
                        'permission' => 'property_manage_others',
                    ],
                    'attribute' => [
                        'url'        => 'admin/module/property/attribute',
                        'title'      => __('Attributes'),
                        'permission' => 'property_manage_attributes',
                    ],
                    'dokan_attribute' => [
                        'url'        => route('property.admin.dokan.attribute.index'),
                        'title'      => __('Dokan Attributes'),
                        'permission' => 'property_manage_attributes',
                    ],
                    'product_attribute' => [
                        'url'        => route('property.admin.product.attribute.index'),
                        'title'      => __('Product Attributes'),
                        'permission' => 'property_manage_attributes',
                    ],
                    'product_category' => [
                        'url'        => 'admin/module/property/product-category',
                        'title'      => __('Product Categories'),
                        'permission' => 'property_manage_others',
                    ],
                    'property_contact' => [
                        'url'        => 'admin/module/property/contact',
                        'title'      => __('Contact property'),
                        'permission' => 'property_manage_others',
                    ],
                ]
            ]
        ];
    }

    public static function getBookableServices()
    {
        return [
            'property' => Property::class,
            'dokan' => PropertyDokan::class,
            'product' => PropertyDokanProduct::class,
        ];
    }

    public static function getMenuBuilderTypes()
    {
        if (!Property::isEnable()) return [];
        return [
            'property' => [
                'class' => Property::class,
                'name'  => __("Businesses"),
                'items' => Property::searchForMenu(),
                'position' => 41
            ]
        ];
    }

    public static function getUserMenu()
    {
        $res = [];
        if (Property::isEnable()) {
            $res['property'] = [
                'url'        => route('property.vendor.index'),
                'title'      => __("My Listings"),
                'icon'  => "flaticon-list",
                'position'   => 32,
                'permission' => 'property_view',
            ];
        }
        return $res;
    }

    public static function getTemplateBlocks()
    {
        if (!Property::isEnable()) return [];
        return [
            'list_property' => "\\Modules\\Property\\Blocks\\ListProperty",
            'property_term_featured_box' => "\\Modules\\Property\\Blocks\\PropertyTermFeaturedBox",
        ];
    }

    public static function getReviewableServices()
    {
        return [
            'property' => Property::class,
        ];
    }
}
