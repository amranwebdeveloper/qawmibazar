@include('Property::admin.dokan.form-detail.content')
@include('Property::admin.dokan.form-detail.pricing')
@include('Property::admin.dokan.form-detail.attributes')
@include('Property::admin.dokan.form-detail.availability')
@include('Property::admin.dokan.form-detail.seo-meta')

{{-- @include('Property::admin.dokan.form-detail.ical') --}}
@if (is_default_lang())
    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label><strong>{{ __('Status') }}</strong> </label>
                <select name="status" class="custom-select">
                    <option value="publish" @if ($row->status == 'publish') selected @endif>{{ __('Publish') }}</option>
                    <option value="pending" @if ($row->status == 'pending') selected @endif>{{ __('Pending') }} </option>
                    <option value="draft" @if ($row->status == 'draft') selected @endif>{{ __('Draft') }} </option>
                </select>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label><strong>{{ __('Business Type') }}</strong></label>
                <select name="business_type" class="custom-select">
                    <option value="1" @if ($row->business_type == '1') selected @endif>Product</option>
                    <option value="2" @if ($row->business_type == '2') selected @endif>Service</option>
                    <option value="3" @if ($row->business_type == '3') selected @endif>Product/Service</option>
                </select>
            </div>
        </div>
    </div>
@endif
