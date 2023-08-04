<section class="our-listing  pb30-991"
    style="background-image:url('{{ get_file_url(setting_item_with_lang('property_page_search_backgraound'), 'full') }}')">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                @include('Property::frontend.layouts.search.filter-search-mobile')
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumb_content style2 mb0-991">
                    @php $page_search_title = setting_item_with_lang('property_page_search_title', '', __('Business List')) @endphp
                    <h1 class="breadcrumb_title">{{ $page_search_title }}</h1>
                    <p class="">{{ setting_item_with_lang('property_page_search_paragraph') }}</p>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ $page_search_title }}</li>
                    </ol>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="dn db-lg mt30 mb0 tac-767">
                    <div id="main2">
                        <span id="open2" class="fa fa-filter filter_open_btn style2"> {{ __('Show Filter') }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="our-listing pt20">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-xl-4">
                <div class="sidebar_listing_grid1 dn-lg bgc-f4">
                    <div class="sidebar_listing_list">
                        <div class="sidebar_advanced_search_widget">
                            <h2>Filter Business</h2>
                            <ul class="sasw_list mb0">
                                @include('Property::frontend.layouts.form-search.sidebar')
                            </ul>
                        </div>
                    </div>
                </div>

                @if (!empty(setting_item('sidebar_ad_banners')))
                    <div class="sidebar_contact_business_widget mt-3 d-none d-sm-block">
                        @php
                            $sidebar_ad_banners = setting_item('sidebar_ad_banners');

                            if (!empty($sidebar_ad_banners)) {
                                $sidebar_ad_banners = json_decode($sidebar_ad_banners, true);
                            }
                            if (empty($sidebar_ad_banners) or !is_array($sidebar_ad_banners)) {
                                $sidebar_ad_banners = [];
                            }
                            $random = array_rand($sidebar_ad_banners, 1);
                            $sidebar_ad_banners = $sidebar_ad_banners[$random];
                        @endphp
                        <div class="row itinerary-list">
                            <div class="col-md-12">
                                <a href="{{ $sidebar_ad_banners['url'] }}"
                                   target="{{ $sidebar_ad_banners['target'] }}">
                                    <img src="{{ !empty($sidebar_ad_banners['ad_banner']) ? get_file_url($sidebar_ad_banners['ad_banner'], 'full') : '' }}"
                                         alt="{{ $sidebar_ad_banners['ad_title'] }}" />
                                </a>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
            <div class="col-md-12 col-lg-8">
                <div class="row">
                    <div class="listing_filter_row dif db-767">
                        <div class="col-sm-12 col-md-4 col-lg-4 col-xl-5">
                            <div class="left_area tac-xsd mb30-767">
                                @include('Property::frontend.layouts.search.loop.result-count')
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-8 col-lg-8 col-xl-7">
                            <div class="listing_list_style tac-767">
                                @include('Property::frontend.layouts.search.loop.orderby')
                            </div>
                        </div>
                    </div>
                </div>
                <div class="list-item">
                    <div class="row">
                        @php $layout = Request::input('layout') @endphp
                        @if ($rows->total() > 0)
                            @foreach ($rows as $row)
                                @if ($layout == 'list')
                                    <div class="item-listting col-lg-12">
                                        @include('Property::frontend.layouts.search.loop.loop-list')
                                    </div>
                                @else
                                    <div class="item-listting col-lg-6 col-md-6">
                                        @include('Property::frontend.layouts.search.loop.loop-gird')
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="col-lg-12">
                                <div class="border rounded p-3 bg-white">
                                    {{ __('Business not found') }}
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-lg-12 mt20">
                    <div class="mbp_pagination">
                        {{ $rows->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>
            <div class="col-lg-12 mt20">
                <div class="text-center">
                    <p>{{ setting_item_with_lang('property_page_search_footer_text') }}</p>
                </div>
            </div>
        </div>
    </div>
</section>
