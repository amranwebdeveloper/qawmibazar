<!-- Search Field Modal -->
@if (!empty(setting_item('enable_search_header')))
    <section class="modal fade search_dropdown" id="staticBackdrop" data-backdrop="static" data-keyboard="false"
        tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="popup_modal_wrapper">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-lg-12">
                                    <a class="close closer" data-dismiss="modal" aria-label="Close"
                                        href="#"><span><img
                                                src="{{ asset('dist/frontend/guido/icons/close.svg') }}"
                                                alt=""></span></a>
                                </div>
                            </div>
                        </div>
                        <div class="container">

                            {{-- <div class="row">
                                <div class="col-lg-12">
                                    <div class="">{{ __('Search') }}</div>
                                </div>
                                <div
                                    class="col-12 col-lg-6 my-2 smart-search smart-search-property-container position-relative mb-3">
                                    <select class="smart-search-property form-control w-100"
                                        placeholder="{{ __('-- Please Select --') }}" value=""
                                        data-onLoad="{{ __('Loading...') }}"></select>
                                </div>
                            </div> --}}
                            @if (!empty(setting_item('enable_category_box')) and !empty(setting_item('header_category_box')))
                                <div class="row">
                                    <div class="col-lg-11 mb10">
                                        <div class="">{{ __('Filter by Category') }}</div>
                                    </div>
                                    <div class="col-lg-1 mb10">
                                        <a href="{{ url('/') }}/category">View All</a>
                                    </div>
                                    @if (!empty($propertyCategoryHeader))
                                        @foreach ($propertyCategoryHeader as $categoryHeader)
                                            <div class="col-sm-6 col-md-4 col-xl-2">
                                                <a href="{{ $categoryHeader->getDetailUrl() }}">
                                                    <div class="icon-box text-center">
                                                        <div class="icon">
                                                            @if ($categoryHeader->image_id)
                                                                <img src="{{ \Modules\Media\Helpers\FileHelper::url($categoryHeader->image_id, 'thumb') }}"
                                                                    alt="{{ $categoryHeader->name }}" />
                                                            @endif
                                                        </div>
                                                        <div class="content-details">
                                                            <div class="title">{{ $categoryHeader->name }}</div>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                            @endif
                            @if (!empty(setting_item('enable_location_box')) and !empty(setting_item('header_location_box')))
                                <div class="row">
                                    <div class="col-lg-11 mb15 mt15">
                                        <h3>{{ __('Discover by Division') }}</h3>
                                    </div>
                                    <div class="col-lg-1 mb10">
                                        <a href="{{ url('/') }}/location">View All</a>
                                    </div>
                                    @if (!empty($propertyLocationHeader))
                                        @foreach ($propertyLocationHeader as $locationHeader)
                                            <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                                                <div class="property_city_home6 tac-xsd">
                                                    <div class="thumb">
                                                        <a href="{{ $locationHeader->getDetailUrl() }}">
                                                            <img class="w100" src="{{ $locationHeader->image_url }}"
                                                                alt="{{ $locationHeader->name }}">
                                                        </a>
                                                    </div>
                                                    <div class="details">
                                                        <a href="{{ $locationHeader->getDetailUrl() }}">
                                                            <h4>{{ $locationHeader->name }}</h4>
                                                            <p>@php
                                                                if (empty($locationHeader->parent_id)) {
                                                                    $countparent = DB::table('bc_locations')
                                                                        ->where('bc_locations.parent_id', $locationHeader->id)
                                                                        ->get();
                                                                    $countproperty = 0;
                                                                    foreach ($countparent as $child) {
                                                                        //echo $child->id;
                                                                        $rows = DB::table('bc_locations')
                                                                            ->join('bc_properties', 'bc_locations.id', '=', 'bc_properties.location_id')
                                                                            // ->join('bc_properties', 'bc_locations.parent_id', '=', 'bc_properties.location_id')
                                                                            ->where('bc_properties.location_id', $child->id)
                                                                            ->get();

                                                                        $countproperty += count($rows);
                                                                    }
                                                                    echo $countproperty > 1 ? $countproperty . ' Businesses' : $countproperty . ' Business';
                                                                } else {
                                                                    echo $locationHeader->property_count > 1 ? $locationHeader->property_count . ' Businesses' : $locationHeader->property_count . ' Business';
                                                                }

                                                            @endphp</p>

                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif


<a class="scrollToHome" href="#" style="display: inline;"><i class="fa fa-angle-up"></i></a>
@if (!is_api() and empty($is_user_page))
    <section class="footer_one home1">
        <div class="container pb70">
            <div class="row">
                @if ($list_widget_footers = setting_item_with_lang('list_widget_footer'))
                    <?php $list_widget_footers = json_decode($list_widget_footers);
                    $count=1;?>
                    @foreach ($list_widget_footers as $key => $item)
                        <div class="col-lg-{{ $item->size ?? '3' }} col-md-6">
                            <div class="footer_qlink_widget pl-0">

                                <h4 class="title">
                                    {{ $item->title }}
                                </h4>
                                <div class="context">
                                    @if ($count==1)
                                        <div >
                                            <a href="{{ url(app_get_locale(false, '/')) }}" class="navbar_brand mb10">
                                                <img class="logo1 img-fluid" src="{{ get_file_url(setting_item('logo_white_id')) }}" alt="{{ $item->title }} logo">
                                            </a>
                                        </div>
                                    @endif
                                    {!! clean($item->content) !!}
                                </div>
                            </div>
                        </div>
                        <?php
                            $count++;
                        ?>
                    @endforeach
                @endif
                <div class="col-sm-7 col-md-6 col-lg-4 col-xl-4">
                    <div class="footer_social_widget">
                        <iframe src="https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2FQawmibazar2023%2F&tabs&width=340&height=130&small_header=false&adapt_container_width=false&hide_cover=false&show_facepile=false&appId" width="100%" height="200" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowfullscreen="true" allow="autoplay; clipboard-write; encrypted-media; picture-in-picture; web-share"></iframe>
                        {{-- <h4>Subscribe</h4>
							<p class="text-white mb20">We don’t send spam so don’t worry.</p>
							<form action="{{route('newsletter.subscribe')}}" class="subcribe-form bc-subscribe-form bc-form footer_mailchimp_form">
								@csrf
								<div class="form-row align-items-center">
									<div class="col-auto">

										<input type="text" name="email" class="form-control email-input" placeholder="{{__('Your Email')}}">
										<button type="submit" class="btn btn-primary btn-submit">{{__('Subscribe')}}
											<i class="fa fa-spinner fa-pulse fa-fw"></i>
										</button>
									</div>

								</div>
								<div class="form-mess"></div>
							</form> --}}
                    </div>
                </div>

            </div>
        </div>
        <hr>
        <div class="container pt20 pb30">
            <div class="row">
                <div class="col-md-9 col-lg-9">
                    {!! setting_item_with_lang('footer_text_left') !!}
                </div>
                <div class="col-md-3 col-lg-3">
                    <div class="footer_logo_widget text-center mb15-767">
                        <div class="wrapper">
                            <div class="logo text-center">
                                @if (!empty(setting_item('footer_logo_id')))
                                    <img src="{{ get_file_url(setting_item('footer_logo_id')) }}"
                                        alt="footer-logo.svg">
                                @endif
                                {{-- <span class="logo_title text-white pl15">{{ setting_item('site_title') }}</span> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endif

@include('Layout::parts.login-register-modal')
@include('Layout::parts.chat')
@if (Auth::id())
    @include('Media::browser')
@endif
<link rel="stylesheet" href="{{ asset('libs/flags/css/flag-icon.min.css') }}">

{!! \App\Helpers\Assets::css(true) !!}

{{-- Lazy Load --}}
<script src="{{ asset('libs/lazy-load/intersection-observer.js') }}"></script>
<script async src="{{ asset('libs/lazy-load/lazyload.min.js') }}"></script>
<script>
    // Set the options to make LazyLoad self-initialize
    window.lazyLoadOptions = {
        elements_selector: ".lazy",
        // ... more custom settings?
    };

    // Listen to the initialization event and get the instance of LazyLoad
    window.addEventListener('LazyLoad::Initialized', function(event) {
        window.lazyLoadInstance = event.detail.instance;
    }, false);
</script>
<script src="{{ asset('libs/lodash.min.js') }}"></script>
<script src="{{ asset('libs/jquery-3.6.0.min.js') }}"></script>

<script src="{{ asset('libs/vue/vue' . (!env('APP_DEBUG') ? '.min' : '') . '.js') }}"></script>
<script src="{{ asset('libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('libs/bootbox/bootbox.min.js') }}"></script>
@if (Auth::id())
    <script src="{{ asset('module/media/js/browser.js?_ver=' . config('app.asset_version')) }}"></script>
@endif
<script type="text/javascript" src="{{ asset('libs/daterange/moment.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('libs/daterange/daterangepicker.min.js') }}"></script>
<script src="{{ asset('libs/select2/js/select2.min.js') }}"></script>
<script src="{{ asset('js/functions.js?_ver=' . config('app.asset_version')) }}"></script>

<script src="{{ asset('dist/frontend/js/jquery-migrate-3.0.0.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/popper.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/jquery.mmenu.all.js') }}"></script>
<script src="{{ asset('dist/frontend/js/ace-responsive-menu.js') }}"></script>
<script src="{{ asset('dist/frontend/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/isotop.js') }}"></script>
<script src="{{ asset('dist/frontend/js/snackbar.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/simplebar.js') }}"></script>
<script src="{{ asset('dist/frontend/js/parallax.js') }}"></script>
<script src="{{ asset('dist/frontend/js/scrollto.js') }}"></script>
<script src="{{ asset('dist/frontend/js/jquery-scrolltofixed-min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/jquery.counterup.js') }}"></script>
<script src="{{ asset('dist/frontend/js/wow.min.js') }}"></script>
<script src="{{ asset('dist/frontend/js/progressbar.js') }}"></script>
<script src="{{ asset('dist/frontend/js/slider.js') }}"></script>
<script src="{{ asset('dist/frontend/js/timepicker.js') }}"></script>
<!-- Custom script for all pages -->

<script src="{{ asset('dist/frontend/js/script.js') }}"></script>

@if (setting_item('tour_location_search_style') == 'autocompletePlace' ||
    setting_item('hotel_location_search_style') == 'autocompletePlace' ||
    setting_item('car_location_search_style') == 'autocompletePlace' ||
    setting_item('space_location_search_style') == 'autocompletePlace' ||
    setting_item('hotel_location_search_style') == 'autocompletePlace' ||
    setting_item('event_location_search_style') == 'autocompletePlace')
    {!! App\Helpers\MapEngine::scripts() !!}
@endif
<script src="{{ asset('libs/pusher.min.js') }}"></script>
<script src="{{ asset('js/home.js?_ver=' . config('app.asset_version')) }}"></script>

@if (!empty($is_user_page))
    <script src="{{ asset('dist/frontend/js/dashboard-script.js') }}"></script>
    <script src="{{ asset('module/user/js/user.js?_ver=' . config('app.asset_version')) }}"></script>
@endif
@if (setting_item('cookie_agreement_enable') == 1 and
    request()->cookie('booking_cookie_agreement_enable') != 1 and
    !is_api() and
    !isset($_COOKIE['booking_cookie_agreement_enable']))
    <div class="booking_cookie_agreement p-3 d-flex fixed-bottom">
        <div class="content-cookie">{!! setting_item_with_lang('cookie_agreement_content') !!}</div>
        <button class="btn save-cookie">{!! setting_item_with_lang('cookie_agreement_button_text') !!}</button>
    </div>
    <script>
        var save_cookie_url = '{{ route('core.cookie.check') }}';
    </script>
    <script src="{{ asset('js/cookie.js?_ver=' . config('app.asset_version')) }}"></script>
@endif
@if (!empty(setting_item('inbox_enable')))
    <script src="{{ asset('module/core/js/chat-engine.js') }}"></script>
@endif
{!! \App\Helpers\Assets::js(true) !!}

@yield('footer')

@php \App\Helpers\ReCaptchaEngine::scripts() @endphp
