@extends('layouts.app')
@section('head')
@endsection
@section('content')

    @php
    if (setting_item('property_enable_review')) {
        $reviewData = Modules\Review\Models\Review::getTotalViewAndRateAvg($productrow->id, 'dokan');
        $review_meta = Modules\Review\Models\Review::getReviewMetaAvg($productrow->id, 'dokan');
    }
    @endphp

    @include('Property::frontend.layouts.details.product.banner', ['reviewData' => $reviewData])

    <section class="our-agent-single pb30-991">
        <article class="container">
            <div class="row">
                <div class="col-md-12 col-lg-8">
                    <h2 class="mt-0">{{ $productrow->title }} </h2>
                    <div class="row">
                        <div class="col-lg-6">
                            <img src="{{ get_file_url($productrow->image_id, 'large') }}" alt="{{ $productrow->title }}" />
                        </div>
                        <div class="col-lg-6">

                            <p>Category : {{$category->name}}</p>
                            <div class="sspd_review listing_single_v1">
                                <ul class="mb0">
                                    @if ($productrow->price)
                                        <li class="list-inline-item ">
                                            <span class="price">
                                               Price: {{$productrow->sale_price}} | <del>{{$productrow->price}}</del>
                                            </span>
                                        </li>
                                    @endif

                                    @if (setting_item('property_enable_review') and $reviewData['total_review'] > 0)
                                        @for ($i = 0; $i < 5; $i++)
                                            @if ($i < (int) $reviewData['sbc_total'])
                                                <li class="list-inline-item"><i class="fa fa-star"></i></li>
                                            @else
                                                <li class="list-inline-item"><i class="fa fa-star-o"></i></li>
                                            @endif
                                        @endfor
                                        <li class="list-inline-item text-white">(
                                            @if ($reviewData['total_review'] > 1)
                                                {{ __(':number Reviews', ['number' => $reviewData['total_review']]) }}
                                            @else
                                                {{ __(':number Review', ['number' => $reviewData['total_review']]) }}
                                            @endif
                                            )
                                        </li>
                                    @endif

                                    @if ($productrow->view > 0)
                                        <li class="list-inline-item ml20">
                                            <span class="price_range">
                                                <i class="icofont-eye-alt"></i> {{ $productrow->view }}
                                            </span>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                            <p>Product Details:
                                {!! clean($productrow->short_description) !!}
                            </p>
                            @if(isset($productrow->measurement_chart))
                            <p><a href="" class="btn btn-info" data-toggle="modal" data-target="#modal-measurement-chart" aria-expanded="false">measurement chart</a>
                            </p>
                            @endif

                            <div class="modal fade" id="modal-measurement-chart" >
                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">{{ $productrow->title }}</h4>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body">
                                            <img src="{{ get_file_url($productrow->measurement_chart, 'large') }}" alt="{{ $productrow->title }}" />
                                        </div>
                                        <!-- Modal footer -->
                                        <div class="modal-footer">
                                            <span class="btn btn-secondary" data-dismiss="modal">{{__("Close")}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @if(isset($productrow->tags))
                               <?php  $tags = explode(",", $productrow->tags);
                                $final_tags = implode(', ', $tags); ?>
                            <p>Tags : {{$final_tags}}</p>
                                @endif

                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 pl15-767 pr15-767">
                            <div class="listing_single_description mb30">
                                <h3 class="mb30 mt30">{{ __('Overview') }}</h3>
                                <div class="overview">
                                    {!! clean($productrow->content) !!}
                                </div>
                            </div>
                            <div class="listing_single_description mb30">
                                <h3 class="mb30">{{ __('Specification') }}</h3>
                                <div class="overview">
                                    {!! clean($productrow->specification) !!}
                                </div>
                            </div>
                        </div>

                        @include('Property::frontend.layouts.details.product.features')

                        @include('Property::frontend.layouts.details.product.gallery')

                        @include('Property::frontend.layouts.details.product.faq')

                        @include('Property::frontend.layouts.details.product.video')

                        @if (setting_item('dokan_enable_review'))
                            <div id="reviews" class="col-lg-12">
                                @include('Review::frontend.dokan-list-review', [
                                    'review_service_id' => $productrow['id'],
                                    'review_service_type' => 'dokan',
                                    'reviewData' => $reviewData,
                                ])
                            </div>
                        @endif

                    </div>
                </div>

                <div class="col-lg-4 col-xl-4">
                    <div class="listing_single_sidebar">

                        @if ($productrow->enable_open_hours)
                            <div class="sidebar_opening_hour_widget pb20">
                                <h4 class="title mb15">{{ __('Hours') }}
                                    @if ($productrow->is_open)
                                        <small class="text-thm2 float-right">{{ __('Now Open') }}</small>
                                    @else
                                        <small class="text-danger float-right">{{ __('Closed') }}</small>
                                    @endif
                                </h4>
                                <ul class="list_details mb0">
                                    @foreach ($productrow->open_hours as $key => $val)
                                        @switch($key)
                                            @case(1)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Monday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break

                                            @case(2)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Tuesday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break

                                            @case(3)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Wednesday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break

                                            @case(4)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Thursday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break

                                            @case(5)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Friday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break

                                            @case(6)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Saturday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break

                                            @case(7)
                                                @if (!empty($val['enable']))
                                                    <li>{{ __('Sunday') }} <span
                                                            class="float-right">{{ date('h:i a', strtotime($val['from'])) }} –
                                                            {{ date('h:i a', strtotime($val['to'])) }}</span></li>
                                                @endif
                                            @break
                                        @endswitch
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (!empty($productrow->categories) && count($productrow->categories) > 0)
                            <div class="sidebar_category_widget">
                                <h4 class="title mb30">{{ __('Categories') }}</h4>
                                <ul class="mb0">
                                    @foreach ($productrow->categories as $key => $category)
                                        <li class="{{ count($productrow->categories) - 1 != $key ? 'mb25' : '' }}"><a
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

{{--                        @if (!empty($productrow->user))--}}
                            <div class="sidebar_author_widget">
                                <h4 class="title mb25">{{ __('Seller') }}</h4>
                                <div class="media">
                                    <img class="mr-3 avatar" src="{{ get_file_url($dokanrow->person_profile_id, 'large') }}" alt="{{ $dokanrow->contact_person }}" />
                                    <div class="media-body">
                                        <h5 class="mt15 mb0"> {{ $dokanrow->contact_person }} </h5>
                                    </div>
                                </div>
                                <ul class="contact_list list-unstyled mb15">
                                    @if($dokanrow->contact_phone)<li><span class="flaticon-phone mr15"></span><a href="tel:{{ $dokanrow->contact_phone }}" target="_blank" rel="nofollow noopener">{{ $dokanrow->contact_phone }}</a></li>@endif
                                    @if($dokanrow->contact_whatsapp)<li><span class="flaticon-whatsapp mr15"></span>
                                        <a href="https://api.whatsapp.com/send?phone={{ $dokanrow->contact_whatsapp }}&amp;text=Hey! I need know about {{ $dokanrow->title }} | {{setting_item('site_title')}}" class="whatsapp-float" target="_blank" rel="nofollow noopener noreferrer"><img src="https://cdn.policyx.com/images/whats-up-message.webp" class="img-fluid" alt="whatsapp" width="128" height="44"></a>
                                    </li>@endif
{{--                                    @if($dokanrow->email)<li><span class="flaticon-email mr15"></span><a href="mailto:{{ $dokanrow->email }}">{{ $dokanrow->email }}</a></li>@endif--}}
                                    @if($dokanrow->website)<li><span class="flaticon-link mr15"></span><a href="{{ $dokanrow->website }}" target="_blank" rel="nofollow noopener">{{ $dokanrow->website }}</a></li>@endif
                                </ul>
                            </div>
{{--                        @endif--}}

                        <div class="sidebar_contact_business_widget">
                            <h4 class="title mb25">{{ __('Contact Store Manager') }}</h4>
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
                                            value="{{ !empty($productrow->user) ? $productrow->user->id : 0 }}">
                                        <input type="hidden" name="object_id" value="{{ $productrow->id }}">
                                        <input type="hidden" name="object_model" value="dokan">
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
        </article>
    </section>

    @include('Property::frontend.layouts.details.product.related')

@endsection
@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function($) {
            new BravoMapEngine('dokan-map-canvas', {
                fitBounds: true,
                center: [{{ $productrow->map_lat ?? '23.777' }}, {{ $productrow->map_lng ?? '90.399' }}],
                zoom: {{ $productrow->map_zoom ?? '8' }},
                ready: function(engineMap) {
                    @if ($productrow->map_lat && $productrow->map_lng)
                        engineMap.addMarker([{{ $productrow->map_lat }},
                            {{ $productrow->map_lng }}
                        ], {
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
