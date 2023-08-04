@include('Property::admin.product.form-detail.content')
@include('Property::admin.product.form-detail.pricing')
@include('Property::admin.product.form-detail.attributes')

<div class="panel">
    <div class="panel-title"><strong>{{ __('SEO Options') }}</strong></div>
    <div class="panel-body">
        <div class="form-group">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#seo_1">{{ __('General Options') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#seo_2">{{ __('Share Facebook') }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#seo_3">{{ __('Share Twitter') }}</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="seo_1">
                    <div class="form-group">
                        <label class="control-label">{{ __('Seo Title') }}</label>
                        <input type="text" name="property_page_list_seo_title" class="form-control"
                            placeholder="{{ __('Enter title...') }}"
                            value="{{ setting_item_with_lang('property_page_list_seo_title', request()->query('lang')) }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{ __('Seo Description') }}</label>
                        <input type="text" name="property_page_list_seo_desc" class="form-control"
                            placeholder="{{ __('Enter description...') }}"
                            value="{{ setting_item_with_lang('property_page_list_seo_desc', request()->query('lang')) }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{ __('Seo Header') }}</label>
                        <textarea name="property_page_list_seo_header" class="form-control" placeholder="{{ __('Enter description...') }}">
                        {{ setting_item_with_lang('property_page_list_seo_header', request()->query('lang')) }}
                        </textarea>
                    </div>
                    @if (is_default_lang())
                        <div class="form-group form-group-image">
                            <label class="control-label">{{ __('Featured Image') }}</label>
                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload(
                                'property_page_list_seo_image',
                                $settings['property_page_list_seo_image'] ?? '',
                            ) !!}
                        </div>
                    @endif
                </div>
                @php
                    $seo_share = json_decode(setting_item_with_lang('property_page_list_seo_desc', request()->query('lang'), '[]'), true);
                @endphp
                <div class="tab-pane" id="seo_2">
                    <div class="form-group">
                        <label class="control-label">{{ __('Facebook Title') }}</label>
                        <input type="text" name="property_page_list_seo_share[facebook][title]" class="form-control"
                            placeholder="{{ __('Enter title...') }}"
                            value="{{ $seo_share['facebook']['title'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{ __('Facebook Description') }}</label>
                        <input type="text" name="property_page_list_seo_share[facebook][desc]" class="form-control"
                            placeholder="{{ __('Enter description...') }}"
                            value="{{ $seo_share['facebook']['desc'] ?? '' }}">
                    </div>
                    @if (is_default_lang())
                        <div class="form-group form-group-image">
                            <label class="control-label">{{ __('Facebook Image') }}</label>
                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload(
                                'property_page_list_seo_share[facebook][image]',
                                $seo_share['facebook']['image'] ?? '',
                            ) !!}
                        </div>
                    @endif
                </div>
                <div class="tab-pane" id="seo_3">
                    <div class="form-group">
                        <label class="control-label">{{ __('Twitter Title') }}</label>
                        <input type="text" name="property_page_list_seo_share[twitter][title]" class="form-control"
                            placeholder="{{ __('Enter title...') }}"
                            value="{{ $seo_share['twitter']['title'] ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label class="control-label">{{ __('Twitter Description') }}</label>
                        <input type="text" name="property_page_list_seo_share[twitter][desc]" class="form-control"
                            placeholder="{{ __('Enter description...') }}"
                            value="{{ $seo_share['twitter']['title'] ?? '' }}">
                    </div>
                    @if (is_default_lang())
                        <div class="form-group form-group-image">
                            <label class="control-label">{{ __('Twitter Image') }}</label>
                            {!! \Modules\Media\Helpers\FileHelper::fieldUpload(
                                'property_page_list_seo_share[twitter][image]',
                                $seo_share['twitter']['image'] ?? '',
                            ) !!}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
{{-- @include('Property::admin.product.form-detail.ical') --}}
@if (is_default_lang())
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><strong>{{ __('Status') }}</strong> </label>
                <select name="status" class="custom-select">
                    <option value="publish">{{ __('Publish') }}</option>
                    <option value="pending" @if ($row->status == 'pending') selected @endif>{{ __('Pending') }}</option>
                    <option value="draft" @if ($row->status == 'draft') selected @endif>{{ __('Draft') }} </option>
                </select>
            </div>
        </div>

        <div class="col-md-6">
            <div class="form-group">
                <label><strong>{{ __('Store Type') }}</strong></label>
                <select name="business_type" class="custom-select">
                    <option value="1" @if ($row->business_type == '1') selected @endif>Product</option>
                    <option value="2" @if ($row->business_type == '2') selected @endif>Service</option>
                    <option value="3" @if ($row->business_type == '3') selected @endif>Product/Service</option>
                </select>
            </div>
        </div>
    </div>
@endif
