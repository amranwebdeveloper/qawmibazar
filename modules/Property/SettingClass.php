<?php

namespace  Modules\Property;

use Modules\Core\Abstracts\BaseSettingsClass;
use Modules\Core\Models\Settings;

class SettingClass extends BaseSettingsClass
{
    public static function getSettingPages()
    {
        return [
            [
                'id'   => 'property',
                'title' => __("Property Settings"),
                'position' => 20,
                'view' => "Property::admin.settings.property",
                "keys" => [
                    'property_disable',
                    'property_page_search_title',
                    'property_page_search_paragraph',
                    'property_page_search_footer_text',
                    'property_page_search_backgraound',
                    'property_page_search_layout',
                    'property_page_single_layout',
                    'property_page_search_banner',
                    'property_category_attribute',
                    'property_location_search_style',

                    'property_enable_review',
                    'property_review_approved',
                    'property_enable_review_after_booking',
                    'property_review_number_per_page',
                    'property_review_stats',

                    'dokan_enable_review',
                    'dokan_review_approved',
                    'dokan_enable_review_after_booking',
                    'dokan_review_number_per_page',
                    'dokan_review_stats',

                    'product_enable_review',
                    'product_review_approved',
                    'product_enable_review_after_booking',
                    'product_review_number_per_page',
                    'product_review_stats',

                    'property_page_list_seo_title',
                    'property_page_list_seo_desc',
                    'property_page_list_seo_header',
                    'property_page_list_seo_image',
                    'property_page_list_seo_share',

                    'dokan_page_list_seo_title',
                    'dokan_page_list_seo_desc',
                    'dokan_page_list_seo_header',
                    'dokan_page_list_seo_image',
                    'dokan_page_list_seo_share',

                    'product_page_list_seo_title',
                    'product_page_list_seo_desc',
                    'product_page_list_seo_header',
                    'product_page_list_seo_image',
                    'product_page_list_seo_share',

                    'property_booking_buyer_fees',
                    'property_vendor_create_service_must_approved_by_admin',
                    'property_allow_vendor_can_change_their_booking_status',
                    'property_search_fields',
                    'property_sidebar_search_fields',
                    'property_map_search_fields',

                    'property_allow_review_after_making_completed_booking',
                    'property_deposit_enable',
                    'property_deposit_type',
                    'property_deposit_amount',
                    'property_deposit_fomular',
                ],
                'html_keys' => []
            ]
        ];
    }
}
