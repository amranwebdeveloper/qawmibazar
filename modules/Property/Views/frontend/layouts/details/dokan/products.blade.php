@if($products)
    <div class="col-md-12  mt30">
        <h2 class="mb20">{{ __('Products & Services - ') }} {{ $dokanrow->title }}</h2>
    </div>

    @foreach ($products as $product)
        @php
            $reviewDokanData = Modules\Review\Models\Review::getTotalViewAndRateAvg($product->id, 'product');
            $review_dokan_meta = Modules\Review\Models\Review::getReviewMetaAvg($product->id, 'product');
        @endphp
        <div class="col-md-6">
            <div class="feat_property ">
                <div class="thumb">
                    <a href="{{ $row->getDetailUrl() .'/'.$dokanrow->slug.'/' . $product->slug }}">
                        @if ($product->image_id)
                            <img class="img-whp" src="{{ get_file_url($product->image_id) }}" alt="{{ $product->title }}">
                        @else
                            <img src="{{ get_file_url(setting_item('default_property_image')) }}" alt="{{ $product->title }}">
                        @endif
                    </a>
                    <div class="thmb_cntnt">
                        <ul class="tag mb0">
                            @php
                                $current = date('H:i');
                            @endphp
                            @if ($product->view > 0)
                                <li class="list-inline-item">
                                    <i class="icofont-eye-alt"></i> {{ $product->view }}
                                </li>
                            @endif
                        </ul>
                        @if ($product->is_featured)
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
{{--                        @if ($row->vendor)--}}
                            <div class="badge_icon">
{{--                                <a href="{{ $row->vendor->profile_url }}">--}}
                                    <img src="{{ get_file_url($dokanrow->person_profile_id, 'large') }}" alt="{{ $product->title }}" />
{{--                                </a>--}}
                            </div>
{{--                        @endif--}}
                        <a href="{{ $row->getDetailUrl() .'/'.$dokanrow->slug.'/' . $product->slug }}"
                           @if (!empty($blank)) target="_self" @endif>
                            <h3 class="title">{{ $product->title }} </h3>
                        </a>

                            @if ($product->price)
                                <li class="list-inline-item">
                                    Price:  <strong>{{$product->sale_price}} ৳ </strong> | <del>{{$product->price}}</del>
                                </li>
                            @endif
                        <p>{{ get_exceprt($product->content, 11, '...') }}</p>
                        <ul class="prop_details mb0">
                            @if ($product->phone)
                                <li class="list-inline-item"><span class="flaticon-phone mr15"></span><a href="tel:{{ $product->phone }}" target="_blank" rel="nofollow noopener">{{ $product->phone }}</a></li>
                            @endif
                        </ul>
                            <a class="btn btn-success" data-toggle="modal" data-target="#modal-{{ $product->id }}" aria-expanded="false">
                                <i class="icofont-cart"></i> {{__('Add to Cart')}}
                            </a>
                    </div>
                    <div class="fp_footer">
                        @if (!empty(($category = $row->categories->first())))
                            <ul class="fp_meta float-left mb0">
                                @if ($category->image_id)
                                    <li class="list-inline-item"><a href="{{ $category->getDetailUrl() }}"><img src="{{ \Modules\Media\Helpers\FileHelper::url($category->image_id) }}"
                                                alt="{{ $category->name }}"></a></li>
                                @endif
                                <li class="list-inline-item"><a href="{{ $category->getDetailUrl() }}">{{ $category->name }}</a></li>
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modal-{{ $product->id }}">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content">
                    <!-- Modal Header -->
                    <div class="modal-header">
                        <h4 class="modal-title">{{ $product->title }} Enquiry</h4>
                    </div>
                    <!-- Modal body -->
                    <div class="modal-body">
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
                                    <input type="hidden" name="vendor_id" value="{{ !empty($product->user) ? $product->user->id : 0 }}">
                                    <input type="hidden" name="object_id" value="{{ $product->id }}">
                                    <input type="hidden" name="object_model" value="product">
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
                    <!-- Modal footer -->
                    <div class="modal-footer">
                        <span class="btn btn-secondary" data-dismiss="modal">{{__("Close")}}</span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@endif

