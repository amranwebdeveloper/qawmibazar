@if ($dokanrow->video)
    <div class="col-lg-12 pl15-767">
        <div class="listing_single_video">
            <h3 class="mb30">{{ __('Corporate Video') }}</h3>
            <div class="property_video">
                <div class="thumb mb-4">
                    <img class="pro_img img-fluid w100"
                         src="{{ \Modules\Media\Helpers\FileHelper::url($dokanrow->banner_image_id, 'full') }}"
                         alt="video banner">
                    <div class="overlay_icon">
                        <a class="video_popup_btn popup-youtube" href="{{ $dokanrow->video }}" target="_blank" ref="nofollow"><span
                                class="fa fa-play body-color"></span></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
