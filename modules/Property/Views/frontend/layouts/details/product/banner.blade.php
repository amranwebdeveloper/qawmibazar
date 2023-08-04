<div class="home10-mainslider">
    <?php $banner_images = $productrow->getBannerImagesDokan(); ?>
    @if ($banner_images || $productrow->banner_image_id)
        <div class="container-fluid p0 dokan">
            <div class="row">
                <div class="col-lg-12">
                    <div class="main-banner-wrapper home10">
                        <div class="dokan-banner">
                            <img src="{{ get_file_url($productrow->banner_image_id, 'large') }}" alt="{{ $productrow->title }}" />
                        </div>
                        {{-- <div class="banner-style-one owl-theme owl-carousel">
                            @foreach ($banner_images as $key => $val)
                                <div class="slide slide-one"
                                    style="background-image: url({{ $val['large'] }});height: 400px;"></div>
                            @endforeach
                        </div>
                        <div class="carousel-btn-block banner-carousel-btn">
                            <span class="carousel-btn left-btn"><i
                                    class="flaticon-arrow-pointing-to-left left"></i></span>
                            <span class="carousel-btn right-btn"><i
                                    class="flaticon-arrow-pointing-to-right right"></i></span>
                        </div><!-- /.carousel-btn-block banner-carousel-btn --> --}}
                    </div><!-- /.main-banner-wrapper -->
                </div>
            </div>
        </div>
    @endif
    <div class="container ">
        <div class="row listing_single_row style2">
            <div class="col-lg-12 col-xl-12">
                <div class="single_property_title listing_single_v1">
                    <div class="media">
                        <div class="media-body">
                            <h1 class="mt-0">{{ $productrow->title }} - {{ $dokanrow->title }}
                                @if (!empty($productrow->location->name))
                                    in {{ $productrow->location->name }}
                                @else
                                @endif
                            </h1>
                        </div><!-- /.main-banner-wrapper -->
                    </div>
                </div>
            </div>
            <div class="col-lg-8 col-xl-7">
                <div class="single_property_title listing_single_v1">
                    <div class="media">
                        <div class="media-body ">
                            <ul class="mb40 agency_profile_contact listing_single_v1">
                                @if ($dokanrow->location)
                                    <li class="list-inline-item"><a href="{{ $dokanrow->location->getDetailUrl() }}"><span
                                                class="flaticon-pin"></span> {{ $dokanrow->location->name }}</a></li>
                                @endif
                                @if ($dokanrow->phone)
                                    <li class="list-inline-item"><a href="tel:{{ $dokanrow->phone }}"><span
                                                class="flaticon-phone"></span> {{ $dokanrow->phone }}</a></li>
                                @endif
                            </ul>

                            <div class="sspd_review listing_single_v1">
                                <ul class="mb0">
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
                                    @if ($productrow->price_range)
                                        <li class="list-inline-item ml20">
                                            <span class="price_range">
                                                @for ($i = 0; $i < $productrow->price_range; $i++)
                                                    $
                                                @endfor
                                            </span>
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
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xl-5">
                <div class="single_property_social_share listing_single_v1 mt20 mt0-lg">
                    <div class="spss style2 listing_single_v1 mt30 float-left fn-lg">
                        <ul class="mb0">
                            <li class="list-inline-item icon social-share">
                                <a href="#"><span class="flaticon-upload"></span></a>
                                <ul class="share-wrapper">
                                    <li>
                                        <a class="facebook"
                                           href="https://www.facebook.com/sharer/sharer.php?u={{ url()->current() }}&amp;title={{ $translation->title }}"
                                           target="_blank" rel="noopener" original-title="{{ __('Facebook') }}">
                                            <i class="fa fa-facebook fa-lg"></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="twitter"
                                           href="https://twitter.com/share?url={{ url()->current() }}&amp;title={{ $translation->title }}"
                                           target="_blank" rel="noopener" original-title="{{ __('Twitter') }}">
                                            <i class="fa fa-twitter fa-lg"></i>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li class="list-inline-item"><a class="text-white" href="#">{{ __('Share') }}</a>
                            </li>
                            <li class="list-inline-item icon">
                                <a href="#" class="service-wishlist {{ $productrow->isWishListProduct() }}"
                                   data-id="{{ $productrow->id }}" data-type="{{ $productrow->type }}">
                                    <span class="fa fa-heart-o"></span>
                                </a>
                            </li>
                            <li class="list-inline-item"><a class="text-white" href="#">{{ $productrow->isWishListProduct() ? __('Saved') : __('Save') }}</a></li>
                        </ul>
                    </div>
                    @if (setting_item('property_enable_review'))
                        <div class="price listing_single_v1 mt25 float-right fn-lg">
                            <a href="#bc-reviews" class="btn btn-thm spr_btn">{{ __('Submit Review') }}</a>
                        </div>
                    @endif
                </div>
            </div>
            <div class="col-lg-12 col-xl-12">
                <div class="breadcrumb_content">
                    @if (!empty($breadcrumbs))
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">
                                    {{ __('Home') }}</a></li>
                            @foreach ($breadcrumbs as $breadcrumb)
                                <li class="breadcrumb-item {{ $breadcrumb['class'] ?? '' }}" aria-current="page">
                                    @if (!empty($breadcrumb['url']))
                                        <a href="{{ url($breadcrumb['url']) }}">{{ $breadcrumb['name'] }}</a>
                                    @else
                                        {{ $breadcrumb['name'] }}
                                    @endif
                                </li>
                            @endforeach
                        </ol>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
