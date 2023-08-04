@extends('layouts.app')
@section('head')
@endsection
@section('content')

    @php
        use Carbon\Carbon;
       if (setting_item('property_enable_review')) {
           $reviewData = Modules\Review\Models\Review::getTotalViewAndRateAvg($row->id, 'property');
           $review_meta = Modules\Review\Models\Review::getReviewMetaAvg($row->id, 'property');
       }
    @endphp

    @include('Property::frontend.layouts.details.property-banner', ['reviewData' => $reviewData])

    <section class="our-agent-single pb30-991">
        <div class="container">
            <div class="row">
                <div class="col-md-12 col-lg-8">
                    <div class="row">
                        <div class="col-lg-12 pl15-767 pr15-767">
                            <div class="listing_single_description mb30">
                                <h2 class="mb30">{{ __('Overview') }}</h2>
                                <div class="overview">
                                    {!! clean($row->content) !!}
                                </div>
                            </div>
                        </div>

                        @include('Property::frontend.layouts.details.property-dokans')

                        @include('Property::frontend.layouts.details.property_features')

                        @include('Property::frontend.layouts.details.property-faq')

                        @include('Property::frontend.layouts.details.property-video')

                        @include('Property::frontend.layouts.details.gallery_property')

                        @include('Property::frontend.layouts.details.property-surrounding')

                        @if (setting_item('property_enable_review'))
                            <div id="reviews">
                                @include('Review::frontend.list-review', [
                                    'review_service_id' => $row['id'],
                                    'review_service_type' => 'property',
                                    'reviewData' => $reviewData,
                                ])
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-lg-4 col-xl-4">
                    <div class="listing_single_sidebar">

                        @include('Property::frontend.layouts.details.property-location')

                        @if ($row->enable_open_hours)
                            <div class="sidebar_opening_hour_widget pb20">
                                <h4 class="title mb15">{{ __('Business Hours') }}
                                    @if ($row->is_open)
                                        <small class="text-thm2 float-right">{{ __('Now Open') }}</small>
                                    @else
                                        <small class="text-danger float-right">{{ __('Closed') }}</small>
                                    @endif
                                </h4>
                                <ul class="list_details mb0">
                                    @foreach ($row->open_hours as $key => $val)
                                        @switch($key)
                                            @case(6)
                                            @if (!empty($val['enable']))
                                                <li @if(Carbon::now()->dayOfWeek == 6) class="today" @endif>{{ __('Saturday') }} <span
                                                        class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                            @else
                                                <li class="closed">{{ __('Saturday') }} <span class="float-right">Closed</span></li>
                                            @endif
                                            @break

                                            @case(7)
                                            @if (!empty($val['enable']))
                                                <li @if(Carbon::now()->dayOfWeek == 7) class="today" @endif>{{ __('Sunday') }} <span
                                                        class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                            @else
                                                <li class="closed">{{ __('Sunday') }} <span class="float-right">Closed</span></li>
                                            @endif
                                            @break

                                            @case(1)
                                                @if (!empty($val['enable']))
                                                    <li @if(Carbon::now()->dayOfWeek == 1) class="today" @endif>{{ __('Monday') }} <span class="float-right">{{ date('h:i a', strtotime($val['from'])) }} – {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @else
                                                    <li class="closed">{{ __('Monday') }} <span class="float-right">Closed</span></li>
                                                @endif
                                            @break

                                            @case(2)
                                                @if (!empty($val['enable']))
                                                    <li @if(Carbon::now()->dayOfWeek == 2) class="today" @endif>{{ __('Tuesday') }} <span class="float-right">{{ date('h:i a', strtotime($val['from'])) }} – {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                            @else
                                                <li class="closed">{{ __('Tuesday') }} <span class="float-right">Closed</span></li>
                                                @endif
                                            @break

                                            @case(3)
                                                @if (!empty($val['enable']))
                                                    <li @if(Carbon::now()->dayOfWeek == 3) class="today" @endif>{{ __('Wednesday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                            @else
                                                <li class="closed">{{ __('Wednesday') }} <span class="float-right">Closed</span></li>
                                                @endif
                                            @break

                                            @case(4)
                                                @if (!empty($val['enable']))
                                                    <li @if(Carbon::now()->dayOfWeek == 4) class="today" @endif>{{ __('Thursday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                            @else
                                                <li class="closed">{{ __('Thursday') }} <span class="float-right">Closed</span></li>
                                                @endif
                                            @break

                                            @case(5)
                                                @if (!empty($val['enable']))
                                                    <li @if(Carbon::now()->dayOfWeek == 5) class="today" @endif>{{ __('Friday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                            @else
                                                <li class="closed">{{ __('Friday') }} <span class="float-right">Closed</span></li>
                                                @endif
                                            @break

                                        @endswitch
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (!empty($row->categories) && count($row->categories) > 0)
                            <div class="sidebar_category_widget">
                                <h4 class="title mb30">{{ __('Categories') }}</h4>
                                <ul class="mb0">
                                    @foreach ($row->categories as $key => $category)
                                        <li class="{{ count($row->categories) - 1 != $key ? 'mb25' : '' }}"><a
                                                href="{{ $category->getDetailUrl() }}">
                                                @if ($category->image_id)
                                                    <img class="mr5"
                                                        src="{{ \Modules\Media\Helpers\FileHelper::url($category->image_id) }}"
                                                        alt="{{ $category->name }}">
                                                @endif {{ $category->name }}
                                            </a></li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (!empty($row->price) or !empty($row->price_from))
                            <div class="sidebar_pricing_widget">
                                <h4 class="title mb20">
                                    @if (empty($row->price))
                                        {{ __('Price') }}
                                    @else
                                        {{ __('Price Range') }}
                                    @endif
                                </h4>
                                <ul class="mb0">
                                    <li><a href="#">{{ __('Price') }}: <span
                                                class="float-right heading-color">{{ $row->display_price_single }}</span></a>
                                    </li>
                                </ul>
                            </div>
                        @endif

                        @if (!empty($row->user))
                            <div class="sidebar_author_widget">
                                <h4 class="title mb25">{{ __('Publisher') }}</h4>
                                <div class="media">
                                    <a target="_self" href="{{ $row->user->profile_url }}">
                                        <img class="mr-3 avatar" src="{{ $row->user->getAvatarUrl() }}" alt="author.png">
                                    </a>
                                    <div class="media-body">
                                        <h5 class="mt15 mb0">
                                            <a target="_self"
                                                href="{{ $row->user->profile_url }}">{{ $row->user->getDisplayName() }}</a>
                                        </h5>
                                        <p class="mb0">{{ $row->user->job }}</p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="sidebar_contact_business_widget">
                            <h4 class="title mb25">{{ __('Contact Today') }}</h4>
                            <form action="{{ route('vendor.contact') }}" method="POST" class="agent_contact_form">
                                @csrf
                                <ul class="business_contact mb0">
                                    <li class="search_area">
                                        <div class="form-group">
                                            <input type="text" name="name" class="form-control" id="exampleInputName1"
                                                placeholder="{{ __('Name') }}">
                                        </div>
                                    </li>
                                    <li class="search_area">
                                        <div class="form-group">
                                            <input type="email" name="email" class="form-control" id="exampleInputEmail"
                                                placeholder="{{ __('Email') }}">
                                        </div>
                                    </li>
                                    <li class="search_area">
                                        <div class="form-group">
                                            <input type="number" name="phone" class="form-control" id="exampleInputName2"
                                                placeholder="{{ __('Phone') }}">
                                        </div>
                                    </li>
                                    <li class="search_area">
                                        <div class="form-group">
                                            <textarea id="form_message" name="message" class="form-control" rows="5" required="required"
                                                placeholder="{{ __('Message') }}"></textarea>
                                        </div>
                                    </li>
                                    <li>
                                        <input type="hidden" name="vendor_id"
                                            value="{{ !empty($row->user) ? $row->user->id : 0 }}">
                                        <input type="hidden" name="object_id" value="{{ $row->id }}">
                                        <input type="hidden" name="object_model" value="property">
                                    </li>
                                    <li>
                                        <div class="search_option_button">
                                            <button type="submit" class="btn btn-block btn-thm h55"><span
                                                    class="text">{{ __('Send Message') }}</span><i
                                                    class="fa fa-spin fa-spinner"></i></button>
                                        </div>
                                    </li>
                                </ul>
                                <div class="form-mess"></div>
                            </form>
                        </div>

                        @if (!empty(setting_item('sidebar_ad_banners')))
                            <div class="sidebar_contact_business_widget">
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
                </div>
            </div>
        </div>
    </section>

    @include('Property::frontend.layouts.details.property-related')
    @include('Property::frontend.layouts.details.property-news')

    @if (!empty(setting_item('footer_ad_banners')))
        @php
            $footer_ad_banners = setting_item('footer_ad_banners');

            if (!empty($footer_ad_banners)) {
                $footer_ad_banners = json_decode($footer_ad_banners, true);
            }
            if (empty($footer_ad_banners) or !is_array($footer_ad_banners)) {
                $footer_ad_banners = [];
            }
            $randomfooterad = array_rand($footer_ad_banners, 1);
            $footer_ad_banners = $footer_ad_banners[$randomfooterad];
        @endphp
        <section class="our-agent-single pb30-991">
            <div class="container">
                <div class="row itinerary-list">
                    <div class="col-md-12">
                        <a href="{{ $footer_ad_banners['url'] }}" target="{{ $footer_ad_banners['target'] }}">
                            <img src="{{ !empty($footer_ad_banners['ad_banner']) ? get_file_url($footer_ad_banners['ad_banner'], 'full') : '' }}"
                                alt="{{ $footer_ad_banners['ad_title'] }}" />
                        </a>
                    </div>
                </div>
            </div>
        </section>
    @endif
@endsection
@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function($) {
            new BravoMapEngine('map-canvas', {
                fitBounds: true,
                center: [{{ $row->map_lat ?? '23.777' }}, {{ $row->map_lng ?? '90.399' }}],
                zoom: {{ $row->map_zoom ?? '8' }},
                ready: function(engineMap) {
                    @if ($row->map_lat && $row->map_lng)
                        engineMap.addMarker([{{ $row->map_lat }}, {{ $row->map_lng }}], {
                            icon_options: {}
                        });
                    @endif
                    engineMap.on('click', function(dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.on('zoom_changed', function(zoom) {
                        $("input[name=map_zoom]").attr("value", zoom);
                    });
                    engineMap.searchBox($('.bc_searchbox'), function(dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                }
            });

            if ($(".popup-img-review").length > 0) {
                $(".review_upload_photo_list").each(function() {
                    $(this).find('.popup-img-review').magnificPopup({
                        type: "image",
                        gallery: {
                            enabled: true,
                        }
                    });
                });
            }
        })
    </script>
@endsection
