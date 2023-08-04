@extends('layouts.app')
@section('head')
@endsection
@section('content')
    <section class="our-listing">
        <div class="container">
            <div class="col-lg-12">
                <div class="breadcrumb_content style2">
                    <h2 class="breadcrumb_title">{{ __('All Collections') }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Collections</li>
                    </ol>
                </div>
            </div>

{{--            <div class="row">--}}
{{--                <div class="listing_filter_row dif db-767">--}}
{{--                    <div class="col-sm-12 col-md-4 col-lg-4 col-xl-5">--}}
{{--                        <div class="left_area tac-xsd mb30-767">--}}
{{--                            @include('Property::frontend.layouts.search.loop.result-count')--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="col-sm-12 col-md-8 col-lg-8 col-xl-7">--}}
{{--                        <div class="listing_list_style tac-767">--}}
{{--                            @include('Property::frontend.layouts.search.loop.orderby')--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </section>
    <section class="product-listing">
        <div class="container">
            <div class="row">
                @foreach ($rows as $row)
                    @php
                        $translation = $row->translateOrOrigin(app()->getLocale());
                        $reviewData = Modules\Review\Models\Review::getTotalViewAndRateAvg($row['id'], 'product');
                        $category = Modules\Property\Models\PropertyDokanProductCategory::query()->where('id',$row['category_id'])->first();
                        $dokan = Modules\Property\Models\PropertyDokan::query()->where('id',$row['parent_id'])->first();
                        $property = Modules\Property\Models\Property::query()->where('id',$dokan['parent_id'])->first();
                    @endphp
                    @if($dokan->status == 'publish')
                        <div class="item-listting col-lg-4 col-md-4">
                            <div class="feat_property ">
                                <div class="thumb">
                                    <a href="{{ $property->getDetailUrl().'/'.$dokan->slug.'/'.$row->slug }}">
                                        @if ($row->image_url)
                                            <img class="img-whp" src="{{ $row->image_url }}" alt="{{ $translation->title }}">
                                        @else
                                            <img src="{{ get_file_url(setting_item('default_property_image'), 'full') }}" alt="{{ $translation->title }}">
                                        @endif
                                    </a>
                                    <div class="product_cntnt">
                                        <ul class="tag mb0">
                                            <li class="list-inline-item">
                                                {{$row->price}} tk
                                            </li>
                                            @if ($row->view > 0)
                                                <li class="list-inline-item">
                                                    <i class="icofont-eye-alt"></i> {{ $row->view }}
                                                </li>
                                            @endif
                                        </ul>
                                        <ul class="fp_meta float-right mb0">
                                            <li class="list-inline-item"><a class="service-wishlist icon {{ $row->isWishListProduct() }}" data-id="{{ $row->id }}" data-type="property"><i
                                                        class="@if ($row->hasWishList) fa fa-heart @else fa fa-heart-o @endif"></i></a>
                                            </li>
                                        </ul>
                                        @if ($row->is_featured)
                                            <ul class="tag2 mb0">
                                                <li class="list-inline-item">
                                                    <a>{{ __('Featured') }}</a>
                                                </li>
                                            </ul>
                                        @endif
                                        <ul class="listing_reviews">
                                            @for ($i = 0; $i < 5; $i++)
                                                @if ($i < (int) $reviewData['sbc_total'])
                                                    <li class="list-inline-item"><a class="text-white"><span class="fa fa-star"></span></a></li>
                                                @else
                                                    <li class="list-inline-item"><a class="text-white"><span class="fa fa-star-o"></span></a></li>
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
                                        </ul>
                                    </div>
                                </div>
                                <div class="details">
                                    <div class="tc_content">
                                        @if ($dokan['person_profile_id'] )
                                            <div class="badge_icon">
                                                <img src="{{ get_file_url($dokan['person_profile_id']) }}" alt="President of {{ $dokan['contact_person'] }}">
                                            </div>
                                        @endif
                                        <a href="{{ $property->getDetailUrl().'/'.$dokan->slug.'/'.$row->slug }}" @if (!empty($blank))   @endif>
                                            <h4 class="title">{{ $translation->title }}</h4>
                                        </a>
                                        @if(isset($category->name))<p>Category: {{ $category->name }}</p> @endif
                                        <p>{{ get_exceprt($row->short_description, 30, '...') }}</p>
                                        <ul class="prop_details mb0">
                                            @if ($dokan->contact_phone)
                                                <li class="list-inline-item"><span class="flaticon-phone mr15"></span><a
                                                        href="tel:{{ $dokan->contact_phone }}" target="_blank">{{ $dokan->contact_phone }}</a></li>
                                            @endif
                                            @if (!empty($dokan->location->name))
                                                <li class="list-inline-item">
                                                    <a href="{{ $dokan->location->getDetailUrl() }}"><span
                                                            class="flaticon-pin pr5"></span>{{ $dokan->location->name }}</a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                    <div class="fp_footer">
                                        <ul class="fp_meta float-left mb0">
                                            @if ($dokan->image_id)
                                                <li class="list-inline-item"><a href="{{ $property->getDetailUrl().'/'.$dokan->slug }}"><img
                                                            src="{{ \Modules\Media\Helpers\FileHelper::url($dokan->image_id) }}"
                                                            alt="{{ $dokan->title }}"></a></li>
                                            @endif
                                            <li class="list-inline-item"><a href="{{ $property->getDetailUrl().'/'.$dokan->slug }}">{{ $dokan->title }}</a>
                                            </li>
                                        </ul>

                                    </div>
                                </div>
                            </div>

                        </div>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endsection

@section('footer')
@endsection
