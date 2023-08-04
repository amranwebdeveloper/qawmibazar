@extends('layouts.user')
@section('head')
@endsection
@section('content')
    <h2 class="title-bar no-border-bottom">
        {{ $row->id ? __('Edit: ') . $row->title : __('Add new dokan') }}
        <div class="title-action">
            <a class="btn btn-info" href="{{ route('property.vendor.dokan.index', ['property_id' => $property->id]) }}">
                <i class="fa fa-hand-o-right"></i> {{ __('Manage Stores') }}
            </a>
        @if($row->id)
                @if ($row->id)
                    <a class="btn btn-warning btn-md" href="{{ route('property.vendor.product.index', ['dokan_id' => $row->id]) }}" ><i class="fa fa-hand-o-right"></i> {{ __('Manage Products') }}</a>
                @endif
                @if ($row->slug)
                    <a class="btn btn-primary btn-sm" href="{{url('/').'/listing/'.$property->slug.'/'.$row->slug}}" target="_blank">{{ __('View Store') }}</a>
                @endif
        @endif
        </div>
    </h2>
    @include('admin.message')
    @if ($row->id)
        @include('Language::admin.navigation')
    @endif
    <div class="lang-content-box">
        <form novalidate action="{{ route('property.vendor.dokan.store', ['property_id' => $property->id, 'id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
            class="needs-validation" method="post">
            @csrf
            <div class="form-add-service">
                <div class="nav nav-tabs nav-fill" id="nav-tab" role="tablist">
                    <a data-toggle="tab" href="#nav-tour-content" aria-selected="true"
                        class="active">{{ __('1. Store Content') }}</a>
                    @if (is_default_lang())
                        <a data-toggle="tab" href="#nav-tour-pricing" aria-selected="false">{{ __('2. Contact') }}</a>
                        <a data-toggle="tab" href="#nav-location" aria-selected="false">{{ __('3. Location') }}</a>
                        <a data-toggle="tab" href="#nav-attribute" aria-selected="false">{{ __('4. Attributes') }}</a>
                        <a data-toggle="tab" href="#nav-availability" aria-selected="false">{{ __('5. Availability') }}</a>
                        <a data-toggle="tab" href="#nav-seo" aria-selected="false">{{ __('6. SEO Options') }}</a>
                    @endif
                </div>
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade show active" id="nav-tour-content">
                        @include('Property::admin.dokan.form-detail.content')
                        <a class="btn btn-primary" data-toggle="tab" href="#nav-tour-pricing" aria-selected="true">Next</a>
                    </div>
                    @if (is_default_lang())
                        <div class="tab-pane fade" id="nav-tour-pricing">
                            @include('Property::admin.dokan.form-detail.pricing')
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-tour-content"
                                aria-selected="false">Back</a>
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-location"
                                aria-selected="false">Next</a>
                        </div>
                        <div class="tab-pane fade" id="nav-location">
                            @include('Property::admin.dokan.form-detail.location')
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-tour-pricing"
                                aria-selected="false">Back</a>
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-attribute"
                                aria-selected="false">Next</a>
                        </div>
                        <div class="tab-pane fade" id="nav-attribute">
                            @include('Property::admin.dokan.form-detail.attributes')
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-location"
                                aria-selected="false">Back</a>
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-availability"
                                aria-selected="false">Next</a>
                        </div>
                        <div class="tab-pane fade" id="nav-availability">
                            @include('Property::admin.dokan.form-detail.availability')
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-attribute" aria-selected="false">Back</a>
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-seo" aria-selected="false">Next</a>
                        </div>

                        <div class="tab-pane fade" id="nav-seo">
                            @include('Core::admin/seo-meta/seo-meta')
                            <a class="btn btn-primary" data-toggle="tab" href="#nav-availability"
                                aria-selected="false">Back</a>
                        </div>
                    @endif
                </div>
            </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>{{ __('Status') }}</strong> </label>
                            <select name="status" class="custom-select">
                                <option value="publish" @if ($row->status == 'publish') selected @endif>{{ __('Publish') }}</option>
                                <option value="pending" @if ($row->status == 'pending') selected @endif>{{ __('Pending') }}</option>
                                <option value="draft" @if ($row->status == 'draft') selected @endif>{{ __('Draft') }} </option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label><strong>{{ __('Store Type') }}</strong></label>
                            <select name="business_type" class="form-control">
                                <option value="1" @if ($row->business_type == '1') selected @endif>Product</option>
                                <option value="2" @if ($row->business_type == '2') selected @endif>Service</option>
                                <option value="3" @if ($row->business_type == '3') selected @endif>Product/Service</option>
                            </select>
                        </div>
                    </div>
                </div>
            <div class="d-flex justify-content-between">
                <button class="btn btn-primary btn_submit" type="submit"><i class="fa fa-save"></i> {{ __('Save Changes') }}</button>
            </div>
        </form>
    </div>
@endsection
@section('footer')
    <script type="text/javascript" src="{{ asset('libs/tinymce/js/tinymce/tinymce.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/condition.js?_ver='.config('app.asset_version')) }}"></script>
    <script type="text/javascript">
        $('#enable_open_hours').click(function() {
            $(".view_enable_open_hours").toggle(this.checked);
        });
        jQuery(function($) {
            $(".btn_submit").click(function() {
                $(this).closest("form").submit();
            });
        });
    </script>
@endsection
