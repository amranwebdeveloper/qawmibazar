<div class="form-group">
    <label>{{ __('Store Name') }} <span class="text-danger">*</span></label>
    <input type="text" required value="{!! clean($translation->title) !!}" placeholder="{{ __('Store name') }}" name="title" class="form-control">
</div>
<div class="form-group">
    <label>{{ __('Slug Name') }} <span class="text-danger">(Optional)</span></label>
    <input type="text" value="{!! clean($row->slug) !!}" placeholder="{{ __('Store Slug') }}" name="slug" class="form-control">
</div>
<div class="form-group">
    <label>{{ __('Store Description') }}</label>
    <textarea name="content" cols="30" rows="5" class="form-control">{{ $translation->content }}</textarea>
</div>
@if (is_default_lang())
    <div class="form-group">
        <label class="control-label">{{ __('Youtube Video') }}</label>
        <input type="text" name="video" class="form-control" value="{{ $row->video }}"
            placeholder="{{ __('Youtube link video') }}">
    </div>
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label>{{ __('Feature Image') }} </label>
                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('image_id', $row->image_id) !!}
            </div>
        </div>
        <div class="col-md-4">
            <div class="form-group">
                <label>{{ __('Banner Image') }} </label>
                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('banner_image_id', $row->banner_image_id) !!}
            </div>
        </div>
    </div>
    <div class="form-group">
        <label>{{ __('Gallery') }}</label>
        {!! \Modules\Media\Helpers\FileHelper::fieldGalleryUpload('gallery', $row->gallery) !!}
    </div>
    <hr>
@endif
