<?php $container = 1; ?>
@extends('layouts.user')
@section('head')
@endsection
@section('content')
    <div class="service-edit-wrap">
        <form class=""
            action="{{ route('property.vendor.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
            method="post">
            @csrf
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{ $row->id ? __('Edit: ') . $row->title : __('Add new Business') }}</h1>
                    @if ($row->id)
                        <div class="title-action">
                            <a class="btn btn-info"
                                href="{{ route('property.vendor.dokan.index', ['property_id' => $row->id]) }}">
                                <i class="fa fa-hand-o-right"></i> {{ __('Manage Stores') }}
                            </a>
                            {{-- <a href="{{ route('property.vendor.dokan.availability.index', ['property_id' => $row->id]) }}"
                                class="btn btn-warning">
                                <i class="fa fa-calendar"></i> {{ __('Availability Stores') }}
                            </a> --}}
                        </div>
                    @endif
                    @if ($row->slug)
                        <p class="item-url-demo">{{ __('Permalink') }}: {{ url('listing') }}/<a href="#"
                                class="open-edit-input" data-name="slug">{{ $row->slug }}</a>
                        </p>
                    @endif
                </div>
                <div class="pt-3">
                    @if ($row->slug)
                        <a class="btn btn-primary btn-sm" href="{{ $row->getDetailUrl(request()->query('lang')) }}" >{{ __('View Business') }}</a>
                    @endif
                </div>
            </div>
            <div class="col-lg-12 mb10"></div>
            @include('admin.message')
            <div class="mb-3">
                @if ($row->id)
                    @include('Language::admin.navigation')
                @endif
            </div>

            <div class="row">
                <div class="col-md-9">
                    @include('Property::admin.property.content', [
                        'hide_gallery' => true,
                        'property_type' => 1,
                    ])
                    @include('Property::admin.property.location')
                    @include('Property::admin.property.surrounding')
                    @include('Core::admin/seo-meta/seo-meta')

                </div>
                <div class="col-md-3">
                    <div class="panel">
                        <div class="panel-title"><strong>{{ __('Publish') }}</strong></div>
                        <div class="panel-body">
                            @if (is_default_lang())
                                <div>
                                    <label class="cursor-pointer"><input @if ($row->status == 'publish') checked @endif type="radio" name="status" value="publish"> {{ __('Publish') }}
                                    </label>
                                </div>
                                <div>
                                    <label class="cursor-pointer"><input  @if ($row->status == 'unpublish') checked @endif type="radio" name="status" value="unpublish"> {{ __('Unpublish') }}
                                    </label>
                                </div>
                                <div>
                                    <label class="cursor-pointer"><input @if ($row->status == 'draft') checked @endif type="radio" name="status" value="draft"> {{ __('Draft') }}
                                    </label>
                                </div>
                            @endif
                            <div class="my_profile_setting_input text-center">
                                <button type="submit" class="btn btn2 ">{{ __('Save') }}</button>
                            </div>
                        </div>
                    </div>
                    <div class="panel">
                        <div class="panel-title"><strong>{{ __('Business Type') }}</strong></div>
                        <div class="panel-body">
                            <select name="business_type" class="form-control">
                                <option value="1" @if ($row->business_type == '1') selected @endif>Product</option>
                                <option value="2" @if ($row->business_type == '2') selected @endif>Service</option>
                                <option value="3" @if ($row->business_type == '3') selected @endif>Product/Service</option>
                            </select>
                        </div>
                    </div>
                    @if (is_default_lang())
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Category') }} *</strong></div>
                            <div class="panel-body">
                                <div class="form-group">
                                    <div class="terms-scrollable">
                                        <?php
                                        $traverse = function ($categories, $prefix = '') use (&$traverse, $row) {
                                        foreach ($categories as $category) {
                                        $checked = '';
                                        foreach ($row->categories as $cat){
                                            if ($category->id == $cat->id){
                                                $checked = 'checked';
                                                break;
                                            }
                                        }
                                        ?>
                                        <label class="term-item d-block">
                                            <input {{ $checked }} type="checkbox" name="categories[]"
                                                value="{{ $category->id }}">
                                            <span class="term-name">{{ $prefix . ' ' . $category->name }}</span>
                                        </label>
                                        <?php
                                        $traverse($category->children, $prefix . '-');
                                        }
                                        };
                                        $traverse($property_category);
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="panel panel-image">
                        <div class="panel-title"><strong>{{ __('Business Image') }}</strong></div>
                        <div class="panel-body">
                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id', $row->image_id) !!}
                        </div>
                    </div>
                    @include('Property::admin.property.attributes')
                </div>
            </div>
        </form>
    </div>
@endsection

@section('footer')
    <script type="text/javascript" src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/condition.js?_ver=' . config('app.asset_version')) }}"></script>
    <script type="text/javascript" src="{{ url('module/core/js/map-engine.js?_ver=' . config('app.asset_version')) }}">
    </script>
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function($) {
            new BravoMapEngine('map_content', {
                fitBounds: true,
                center: [{{ $row->map_lat ?? '23.753' }}, {{ $row->map_lng ?? '90.417' }}],
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

            $(".open-edit-input").on("click", function(e) {
                e.preventDefault();
                var val = $(this).text();
                $(this).after('<input type="text" name="slug" value="' + val + '">').remove();
            });

            var condition_object =
                'select, input[type="radio"]:checked, input[type="text"], input[type="hidden"], input.ot-numeric-slider-hidden-input,input[type="checkbox"]'; // condition function to show and hide sections

            $('body').on('change.conditionals', condition_object, function(e) {
                run_condition_engine();
            });
            run_condition_engine();

            function run_condition_engine() {
                $('[data-condition]').each(function() {
                    var passed;
                    var conditions = get_match_condition($(this).data('condition'));
                    var operator = ($(this).data('operator') || 'and').toLowerCase();
                    $.each(conditions, function(index, condition) {
                        var target = $('[name=' + condition.check + ']');
                        var targetEl = !!target.length && target.first();

                        if (!target.length || !targetEl.length && condition.value.toString() !=
                            '') {
                            return;
                        }

                        var v1 = targetEl.length ? targetEl.val().toString() : '';
                        var v2 = condition.value.toString();
                        var result;

                        if (targetEl.length && targetEl.attr('type') == 'radio') {
                            v1 = $('[name=' + condition.check + ']:checked').val();
                        }

                        if (targetEl.length && targetEl.attr('type') == 'checkbox') {
                            v1 = targetEl.is(':checked') ? v1 : '';
                        }

                        switch (condition.rule) {
                            case 'less_than':
                                result = parseInt(v1) < parseInt(v2);
                                break;

                            case 'less_than_or_equal_to':
                                result = parseInt(v1) <= parseInt(v2);
                                break;

                            case 'greater_than':
                                result = parseInt(v1) > parseInt(v2);
                                break;

                            case 'greater_than_or_equal_to':
                                result = parseInt(v1) >= parseInt(v2);
                                break;

                            case 'contains':
                                result = v1.indexOf(v2) !== -1 ? true : false;
                                break;

                            case 'is':
                                result = v1 == v2;
                                break;

                            case 'not':
                                result = v1 != v2;
                                break;
                        }

                        if ('undefined' == typeof passed) {
                            passed = result;
                        }

                        switch (operator) {
                            case 'or':
                                passed = passed || result;
                                break;

                            case 'and':
                            default:
                                passed = passed && result;
                                break;
                        }
                    });

                    if (passed) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }

                    passed = undefined;
                });
            }

            function get_match_condition(condition) {
                var match;
                var regex =
                    /(.+?):(is|not|contains|less_than|less_than_or_equal_to|greater_than|greater_than_or_equal_to)\((.*?)\),?/g;
                var conditions = [];

                while (match = regex.exec(condition)) {
                    conditions.push({
                        'check': match[1],
                        'rule': match[2],
                        'value': match[3] || ''
                    });
                }

                return conditions;
            } // Please do not edit condition section if you don't understand what it is
        })
    </script>
@endsection
