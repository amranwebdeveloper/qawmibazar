    <div class="col-md-12">
        <h3 class="mb20">{{ __('Store - Office | ') }} {{ $row->title }}
{{--             @if (!empty($row->location->name)) in {{ $row->location->name }}--}}
{{--            @else--}}
{{--            @endif--}}
        </h3>
    </div>
    @foreach ($propertyDokans as $propertyDokan)
        @php
            $reviewDokanData = Modules\Review\Models\Review::getTotalViewAndRateAvg($propertyDokan->id, 'dokan');
            $review_dokan_meta = Modules\Review\Models\Review::getReviewMetaAvg($propertyDokan->id, 'dokan');
        @endphp
        <div class="col-md-6">
            <div class="feat_property ">
                <div class="thumb">
                    <a href="{{ $row->getDetailUrl() . '/' . $propertyDokan->slug }}">
                        @if ($propertyDokan->image_id)
                            <img class="img-whp" src="{{ get_file_url($propertyDokan->image_id) }}"
                                alt="{{ $propertyDokan->title }}">
                        @else
                            <img src="{{ get_file_url(setting_item('default_property_image')) }}"
                                alt="{{ $propertyDokan->title }}">
                        @endif
                    </a>
                    <div class="thmb_cntnt">
                        <ul class="tag mb0">
                            @php
                                $current = date('H:i');
                            @endphp

                            @if ($propertyDokan->price_range)
                                <li class="list-inline-item">
                                    @for ($i = 0; $i < $propertyDokan->price_range; $i++)
                                        $
                                    @endfor
                                </li>
                            @endif

                            <li class="list-inline-item">
                                @if ($propertyDokan->close_time >= $current && $propertyDokan->open_time <= $current)
                                    Open
                                @else
                                    Close
                                @endif
                            </li>
                            @if ($propertyDokan->view > 0)
                                <li class="list-inline-item">
                                    <i class="icofont-eye-alt"></i> {{ $propertyDokan->view }}
                                </li>
                            @endif
                        </ul>
                        @if ($propertyDokan->is_featured)
                            <ul class="tag2 mb0">
                                <li class="list-inline-item">
                                    <a>{{ __('Featured') }}</a>
                                </li>
                            </ul>
                        @endif
                        <ul class="listing_reviews">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < (int) $reviewDokanData['sbc_total'])
                                    <li class="list-inline-item"><a class="text-white"><span
                                                class="fa fa-star"></span></a></li>
                                @else
                                    <li class="list-inline-item"><a class="text-white"><span
                                                class="fa fa-star-o"></span></a></li>
                                @endif
                            @endfor
                            <li class="list-inline-item text-white">(
                                @if ($reviewDokanData['total_review'] > 1)
                                    {{ __(':number Reviews', ['number' => $reviewDokanData['total_review']]) }}
                                @else
                                    {{ __(':number Review', ['number' => $reviewDokanData['total_review']]) }}
                                @endif
                                )
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="details">
                    <div class="tc_content">
                        @if ($row->vendor)
                            <div class="badge_icon"><a href="{{ $row->vendor->profile_url }}"><img
                                        src="{{ $row->vendor->getAvatarUrl() }}"
                                        alt="{{ $row->vendor->getDisplayName() }}"></a></div>
                        @endif
                        <a href="{{ $row->getDetailUrl() . '/' . $propertyDokan->slug }}"
                            @if (!empty($blank))  @endif>
                            <h3 class="title">{{ $propertyDokan->title }} </h3>
                        </a>
                        <p>{{ get_exceprt($propertyDokan->content, 11, '...') }}</p>
                        <ul class="prop_details mb0">
                            @if ($propertyDokan->phone)
                                <li class="list-inline-item"><span class="flaticon-phone mr15"></span><a
                                        href="tel:{{ $propertyDokan->phone }}"
                                        target="_blank">{{ $propertyDokan->phone }}</a></li>
                            @endif
                            @if (!empty($row->location->name))
                                <li class="list-inline-item">
                                    <a href="{{ $row->location->getDetailUrl() }}"><span
                                            class="flaticon-pin pr5"></span>{{ $row->location->name }}</a>
                                </li>
                            @endif
                        </ul>
                    </div>
                    <div class="fp_footer">
                        @if (!empty(($category = $row->categories->first())))
                            <ul class="fp_meta float-left mb0">
                                @if ($category->image_id)
                                    <li class="list-inline-item"><a href="{{ $category->getDetailUrl() }}"><img
                                                src="{{ \Modules\Media\Helpers\FileHelper::url($category->image_id) }}"
                                                alt="{{ $category->name }}"></a></li>
                                @endif
                                <li class="list-inline-item"><a
                                        href="{{ $category->getDetailUrl() }}">{{ $category->name }}</a></li>
                            </ul>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    @endforeach
