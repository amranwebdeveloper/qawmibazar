@php
    $translation = $productrow->translateOrOrigin(app()->getLocale());
    $reviewData = Modules\Review\Models\Review::getTotalViewAndRateAvg($productrow['id'], 'product');
    $category = Modules\Property\Models\PropertyDokanProductCategory::where('id',$item->category_id)->first();
    $relateDokan = Modules\Property\Models\PropertyDokan::where('id',$item->parent_id)->first();
    $relateProperty = Modules\Property\Models\Property::where('id',$relateDokan->parent_id)->first();


@endphp
<div class="feat_property ">
    <div class="thumb">
        <a href="{{ $relateProperty->getDetailUrl() .'/'.$relateDokan->slug.'/' . $item->slug }}">
            @if ($item->image_id)
                <img class="img-whp" src="{{ get_file_url($item->image_id) }}" alt="{{ $item->title }}">
            @else
                <img src="{{ get_file_url(setting_item('default_property_image')) }}" alt="{{ $item->title }}">
            @endif
        </a>
        <div class="thmb_cntnt">
            <ul class="tag mb0">
                @php
                    $current = date('H:i');
                @endphp
                @if ($item->view > 0)
                    <li class="list-inline-item">
                        <i class="icofont-eye-alt"></i> {{ $item->view }}
                    </li>
                @endif
            </ul>
            @if ($item->is_featured)
                <ul class="tag2 mb0">
                    <li class="list-inline-item">
                        <a>{{ __('Featured') }}</a>
                    </li>
                </ul>
            @endif
            <ul class="listing_reviews">
                @for ($i = 0; $i < 5; $i++)
                    @if ($i < (int) $reviewData['sbc_total'])
                        <li class="list-inline-item"><a class="text-white"><span
                                    class="fa fa-star"></span></a></li>
                    @else
                        <li class="list-inline-item"><a class="text-white"><span
                                    class="fa fa-star-o"></span></a></li>
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
            {{--                        @if ($row->vendor)--}}
            <div class="badge_icon">
                {{--                                <a href="{{ $row->vendor->profile_url }}">--}}
                <img src="{{ get_file_url($dokanrow->person_profile_id, 'large') }}" alt="{{ $item->title }}" />
                {{--                                </a>--}}
            </div>
            {{--                        @endif--}}
            <a href="{{ $relateProperty->getDetailUrl() .'/'.$relateDokan->slug.'/' . $item->slug }}"
               @if (!empty($blank)) target="_self" @endif>
                <h3 class="title">{{ $item->title }} </h3>
            </a>

            @if ($item->price)
                <li class="list-inline-item">
                    Price:  <strong>{{$item->sale_price}} ৳ </strong> | <del>{{$item->price}}</del>
                </li>
            @endif
            <p>{{ get_exceprt($item->content, 11, '...') }}</p>
            <ul class="prop_details mb0">
                @if ($item->phone)
                    <li class="list-inline-item"><span class="flaticon-phone mr15"></span><a href="tel:{{ $item->phone }}" target="_blank" rel="nofollow noopener">{{ $item->phone }}</a></li>
                @endif
            </ul>
            <a class="btn btn-success" data-toggle="modal" data-target="#modal-{{ $item->id }}" aria-expanded="false">
                <i class="icofont-cart"></i> {{__('Add to Cart')}}
            </a>
        </div>
        <div class="fp_footer">
            <ul class="fp_meta float-left mb0">
                <li class="list-inline-item">{{ $category->name }}</li>
            </ul>
        </div>
    </div>
</div>
