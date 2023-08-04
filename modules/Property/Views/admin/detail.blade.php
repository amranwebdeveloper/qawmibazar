@extends('admin.layouts.app')

@section('content')
    <form
        action="{{ route('property.admin.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
        method="post">
        @csrf
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{ $row->id ? __('Edit: ') . $row->title : __('Add new Business') }}</h1>
                    @if ($row->slug)
                        <p class="item-url-demo">{{ __('Permalink') }}: {{ url('listing') }}/<a href="#"
                                class="open-edit-input" data-name="slug">{{ $row->slug }}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if ($row->id)
                        <a class="btn btn-warning btn-xs"
                            href="{{ route('property.admin.dokan.index', ['property_id' => $row->id]) }}" target="_blank"><i
                                class="fa fa-hand-o-right"></i> {{ __('Manage Stores') }}</a>
                    @endif
                    @if ($row->slug)
                        <a class="btn btn-primary btn-sm" href="{{ $row->getDetailUrl(request()->query('lang')) }}"
                            target="_blank">{{ __('View Business') }}</a>
                    @endif
                </div>
            </div>
            @include('admin.message')
            @if ($row->id)
                @include('Language::admin.navigation')
            @endif
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">
                        @include('Property::admin.property.content')
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
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                                        {{ __('Save Changes') }}</button>
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
                                            <label class="term-item">
                                                <input {{ $checked }} type="checkbox" name="categories[]" value="{{ $category->id }}">
                                                <span class="term-name">{{ $prefix . ' ' . $category->name }}</span>
                                            </label>
                                            <?php  $traverse($category->children, $prefix . '-');
                                                    }
                                                };
                                            $traverse($property_category);
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{ __('Availability') }}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>{{ __('Business Featured') }}</label>
                                        <br>
                                        <label>
                                            <input type="checkbox" name="is_featured"
                                                @if ($row->is_featured) checked @endif value="1">
                                            {{ __('Enable featured') }}
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="panel">
                                <div class="panel-title"><strong>{{ __('Feature Image') }}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group image-feature">
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id', $row->image_id) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                        @if (is_default_lang())
                            <div class="panel">
                                <div class="panel-title"><strong>{{ __('Author Setting') }}</strong></div>
                                <div class="panel-body">
                                    <div class="form-group">
                                        <?php
                                        $user = !empty($row->create_user) ? App\User::find($row->create_user) : false;
                                        \App\Helpers\AdminForm::select2(
                                            'create_user',
                                            [
                                                'configs' => [
                                                    'ajax' => [
                                                        'url' => url('/admin/module/user/getForSelect2'),
                                                        'dataType' => 'json',
                                                    ],
                                                    'allowClear' => true,
                                                    'placeholder' => __('-- Select User --'),
                                                ],
                                            ],
                                            !empty($user->id) ? [$user->id, $user->getDisplayName() . ' (#' . $user->id . ')'] : false,
                                        );
                                        ?>
                                    </div>
                                </div>
                            </div>
                        @endif

                        @include('Property::admin.property.attributes')
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection

@section('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
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
            var cw = $('.attach-demo .image-item').width();
            $('.attach-demo .image-item').css({
                'height': cw + 'px'
            });
            var cw1 = $('.image-feature .dungdt-upload-box-normal').width();
            $('.image-feature .dungdt-upload-box-normal').css({
                'height': cw1 + 'px'
            });
        })
    </script>
@endsection
