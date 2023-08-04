@if (is_default_lang())
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>{{ __('price') }} </label>
                <input type="text" value="{{ $row->price }}" min="1" placeholder="{{ __('price') }}" name="price" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>{{ __('sale_price') }} </label>
                <input type="text" value="{{ $row->sale_price }}" min="1" placeholder="{{ __('sale_price') }}" name="sale_price" class="form-control">
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>{{ __('purchase_price') }} </label>
                <input type="text" value="{{ $row->purchase_price }}" min="1" placeholder="{{ __('purchase_price') }}" name="purchase_price" class="form-control">
            </div>
        </div>
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('discount') }} </label>--}}
{{--                <input type="text" value="{{ $row->discount }}" min="1" placeholder="{{ __('discount') }}" name="discount" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('discount_type') }} <span class="text-danger"></span></label>--}}
{{--                <select name="discount_type" class="form-control">--}}
{{--                    <option value="flat">Flat</option>--}}
{{--                    <option value="percentage">Percentage</option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('discount_start_date') }} </label>--}}
{{--                <input type="date" value="{{ $row->discount_start_date }}" min="1" placeholder="{{ __('discount start date') }}" name="discount_start_date" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('discount_end_date') }} </label>--}}
{{--                <input type="date" value="{{ $row->discount_end_date }}" min="1" placeholder="{{ __('discount end date') }}" name="discount_end_date" class="form-control">--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('tax') }} </label>
                <input type="text" value="{{ $row->tax }}" min="1" placeholder="{{ __('tax') }}" name="tax" class="form-control">
            </div>
        </div>
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('tax_type') }} <span class="text-danger"></span></label>--}}
{{--                <select name="tax_type" class="form-control">--}}
{{--                    <option></option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('shipping_cost') }} </label>
                <input type="text" value="{{ $row->shipping_cost }}" min="1" placeholder="{{ __('shipping_cost') }}" name="shipping_cost" class="form-control">
            </div>
        </div>
{{--        <div class="col-md-6">--}}
{{--            <div class="form-group">--}}
{{--                <label>{{ __('shipping_type') }} <span class="text-danger"></span></label>--}}
{{--                <select  name="shipping_type" class="form-control">--}}
{{--                    <option></option>--}}
{{--                </select>--}}
{{--            </div>--}}
{{--        </div>--}}
    </div>
@endif
