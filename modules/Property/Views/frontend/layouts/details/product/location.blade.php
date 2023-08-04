<div class="lss_contact_location ">
    <h4 class="mb25">{{ __("Store Location") }}</h4>
    <div class="sidebar_map mb30">
        <div class="lss_map h200" id="dokan-map-canvas"></div>
    </div>
    <ul class="contact_list list-unstyled mb15">
        @if($translation->address)
        <li class="df"><span class="flaticon-pin mr15"></span>
            {{ $translation->address }}
        </li>
        @endif
        @if($productrow->phone)
        <li><span class="flaticon-phone mr15"></span><a href="tel:{{ $productrow->phone }}" target="_blank">{{ $productrow->phone }}</a></li>
        @endif
{{--        @if($productrow->email)--}}
{{--        <li><span class="flaticon-email mr15"></span><span class="__cf_email__" data-cfemail="65161015150a171125160e0a09044b060a08">[email&#160;protected]</span></li>--}}
{{--        @endif--}}
        @if($productrow->website)
        <li><span class="flaticon-link mr15"></span><a href="{{ $productrow->website }}" target="_blank" rel="nofollow noopener">{{ $productrow->website }}</a></li>
        @endif
    </ul>
    @if(!empty($productrow->socials))
        <ul class="sidebar_social_icon mb0">
            @foreach($productrow->socials as $social)
            <li class="list-inline-item"><a href="{{ $social['url'] }}" target="_blank"><i class="{{ $social['icon_class'] }}"></i></a></li>
            @endforeach
        </ul>
    @endif
</div>
