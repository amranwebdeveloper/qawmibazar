@extends('admin.layouts.app')

@section('content')
    <form action="{{ route('news.admin.store', ['id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
        method="post" class="dungdt-form">
        <div class="container-fluid">
            <div class="d-flex justify-content-between mb20">
                <div class="">
                    <h1 class="title-bar">{{ $row->id ? __('Edit post: ') . $row->title : __('Add new Post') }}</h1>
                    @if ($row->slug)
                        <p class="item-url-demo">{{ __('Permalink') }}:
                            {{ url((request()->query('lang') ? request()->query('lang') . '/' : '') . config('news.news_route_prefix')) }}/<a
                                href="#" class="open-edit-input" data-name="slug">{{ $row->slug }}</a>
                        </p>
                    @endif
                </div>
                <div class="">
                    @if ($row->slug)
                        <a class="btn btn-primary btn-sm" href="{{ $row->getDetailUrl(request()->query('lang')) }}"
                            target="_blank">{{ __('View Post') }}</a>
                    @endif
                </div>
            </div>
            @include('admin.message')
            @include('Language::admin.navigation')
            <div class="lang-content-box">
                <div class="row">
                    <div class="col-md-9">
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('News content') }}</strong></div>
                            <div class="panel-body">
                                @csrf
                                @include('News::admin/news/form', ['row' => $row])
                            </div>
                        </div>
                        @include('Core::admin/seo-meta/seo-meta')
                    </div>
                    <div class="col-md-3">
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Publish') }}</strong></div>
                            <div class="panel-body">
                                @if (is_default_lang())
                                    <div>
                                        <label><input @if ($row->status == 'publish') checked @endif type="radio"
                                                name="status" value="publish"> {{ __('Publish') }}
                                        </label>
                                    </div>
                                    <div>
                                        <label><input @if ($row->status == 'draft') checked @endif type="radio"
                                                name="status" value="draft"> {{ __('Draft') }}
                                        </label>
                                    </div>
                                @endif
                                <div class="text-right">
                                    <button class="btn btn-primary" type="submit"><i class="fa fa-save"></i>
                                        {{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </div>

                        @if (is_default_lang())
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>{{ __('Category') }} </label>
                                        <select name="cat_id" class="form-control">
                                            <option value="">{{ __('-- Please Select --') }} </option>
                                            <?php
                                            $traverse = function ($categories, $prefix = '') use (&$traverse, $row) {
                                                foreach ($categories as $category) {
                                                    $selected = '';
                                                    if ($row->cat_id == $category->id) {
                                                        $selected = 'selected';
                                                    }
                                                    printf("<option value='%s' %s>%s</option>", $category->id, $selected, $prefix . ' ' . $category->name);
                                                    $traverse($category->children, $prefix . '-');
                                                }
                                            };
                                            $traverse($categories);
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label"> {{ __('Tag') }}</label>
                                        <div class="">
                                            <input type="text" data-role="tagsinput" value="{{ $row->tag }}"
                                                placeholder="{{ __('Enter tag') }}" name="tag"
                                                class="form-control tag-input">
                                            <br>
                                            <div class="show_tags">
                                                @if (!empty($tags))
                                                    @foreach ($tags as $tag)
                                                        <span class="tag_item">{{ $tag->name }}<span
                                                                data-role="remove"></span>
                                                            <input type="hidden" name="tag_ids[]"
                                                                value="{{ $tag->id }}">
                                                        </span>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Property') }} </label>
                                        <select name="property_id" class="form-control">
                                            <option value="">{{ __('-- Please Select --') }} </option>
                                            <?php
                                            $traverse = function ($properties, $prefix = '') use (&$traverse, $row) {
                                                foreach ($properties as $property) {
                                                    $selected = '';
                                                    if ($row->property_id == $property->id) {
                                                        $selected = 'selected';
                                                    }
                                                    printf("<option value='%s' %s>%s</option>", $property->id, $selected, $prefix . ' ' . $property->title);
                                                }
                                            };
                                            $traverse($properties);
                                            ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">{{ __('Locations') }} </label>
                                        @if (!empty($is_smart_search))
                                            <div class="form-group-smart-search">
                                                <div class="form-content">
                                                    <?php
                                                    $location_name = '';
                                                    $list_json = [];
                                                    $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name, $row) {
                                                        foreach ($locations as $location) {
                                                            $translate = $location->translateOrOrigin(app()->getLocale());
                                                            if ($row->location_id == $location->id) {
                                                                $location_name = $translate->name;
                                                            }
                                                            $list_json[] = [
                                                                'id' => $location->id,
                                                                'title' => $prefix . ' ' . $translate->name,
                                                            ];
                                                            $traverse($location->children, $prefix . '-');
                                                        }
                                                    };
                                                    $traverse($news_locations);
                                                    ?>
                                                    <div class="smart-search">
                                                        <input type="text"
                                                            class="smart-search-location parent_text form-control"
                                                            placeholder="{{ __('-- Please Select --') }}"
                                                            value="{{ $location_name }}"
                                                            data-onLoad="{{ __('Loading...') }}"
                                                            data-default="{{ json_encode($list_json) }}">
                                                        <input type="hidden" class="child_id" name="location_id"
                                                            value="{{ $row->location_id ?? Request::query('location_id') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        @else
                                            <div class="">
                                                <select name="location_id" class="form-control">
                                                    <option value="">{{ __('-- Please Select --') }}</option>
                                                    <?php
                                                    $traverse = function ($locations, $prefix = '') use (&$traverse, $row) {
                                                        foreach ($locations as $location) {
                                                            $selected = '';
                                                            if ($row->location_id == $location->id) {
                                                                $selected = 'selected';
                                                            }
                                                            printf("<option value='%s' %s>%s</option>", $location->id, $selected, $prefix . ' ' . $location->name);
                                                            $traverse($location->children, $prefix . '-');
                                                        }
                                                    };
                                                    $traverse($news_locations);
                                                    ?>
                                                </select>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif


                        @if (is_default_lang())
                            <div class="panel">
                                <div class="panel-body">
                                    <h3 class="panel-body-title"> {{ __('Banner Image') }}</h3>
                                    <div class="form-group">
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id', $row->banner_image_id) !!}
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if (is_default_lang())
                            <div class="panel">
                                <div class="panel-body">
                                    <h3 class="panel-body-title"> {{ __('Feature Image') }}</h3>
                                    <div class="form-group">
                                        {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id', $row->image_id) !!}
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
