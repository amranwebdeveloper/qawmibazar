@if (is_default_lang())
    <div class="row">

        <div class="col-md-4">
            <div class="form-group">
                <label>{{ __('Contact Person Profile') }} </label>
                {!! \Modules\Media\Helpers\FileHelper::fieldUpload('person_profile_id', $row->person_profile_id) !!}
            </div>
        </div>
        <div class="col-md-8">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label>{{ __('Contact Person') }} </label>
                        <input type="text" value="{{ $row->contact_person }}" min="1"
                            placeholder="{{ __('Contact Person') }}" name="contact_person" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Contact Email') }} </label>
                        <input type="text" value="{{ $row->email }}" min="1" placeholder="{{ __('Email') }}"
                               name="email" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Contact Phone') }} </label>
                        <input type="text" value="{{ $row->contact_phone }}" min="1"
                               placeholder="{{ __('Contact Phone') }}" name="contact_phone" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Contact Whatsapp') }} </label>
                        <input type="text" value="{{ $row->contact_whatsapp }}" min="1"
                               placeholder="{{ __('Contact Whatsapp') }}" name="contact_whatsapp" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>{{ __('Website') }} </label>
                        <input type="text" value="{{ $row->website }}" min="1" placeholder="{{ __('Website') }}"
                               name="website" class="form-control">
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group-item">
                <label class="control-label">{{ __('Socials List') }}</label>
                <div class="g-items-header">
                    <div class="row">
                        <div class="col-md-5">{{ __('Icon Class') }}</div>
                        <div class="col-md-5">{{ __('Url') }}</div>
                        <div class="col-md-1"></div>
                    </div>
                </div>
                <div class="g-items">
                    @if (!empty($row->socials))
                        @php
                            if (!is_array($row->socials)) {
                                $row->socials = json_decode($row->socials);
                            }
                        @endphp
                        @foreach ($row->socials as $key => $social)
                            @php $social = (array)$social @endphp
                            <div class="item" data-number="{{ $key }}">
                                <div class="row">
                                    <div class="col-md-5">
                                        <select  name="socials[{{ $key }}][icon_class]" class="form-control">
                                            <option value="fa fa-facebook" @if($social['icon_class']=='fa fa-facebook') selected @endif>Facebook</option>
                                            <option value="fa fa-google" @if($social['icon_class']=='fa fa-google') selected @endif>Google</option>
                                            <option value="fa fa-linkedin" @if($social['icon_class']=='fa fa-linkedin') selected @endif>Linkedin</option>
                                            <option value="fa fa-youtube" @if($social['icon_class']=='fa fa-youtube') selected @endif>Youtube</option>
                                            <option value="fa fa-instagram" @if($social['icon_class']=='fa fa-instagram') selected @endif>Instagram</option>
                                            <option value="fa fa-pinterest" @if($social['icon_class']=='fa fa-pinterest') selected @endif>Pinterest</option>
                                            <option value="fa fa-twitter" @if($social['icon_class']=='fa fa-twitter') selected @endif>Twitter</option>
                                            <option value="fa fa-tiktok" @if($social['icon_class']=='fa fa-tiktok') selected @endif>Tiktok</option>
                                            <option value="fa fa-flickr" @if($social['icon_class']=='fa fa-flickr') selected @endif>Flickr</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <input type="text" name="socials[{{ $key }}][url]"
                                            class="form-control" value="{{ $social['url'] }}"
                                            placeholder="https://facebook.com">
                                    </div>
                                    <div class="col-md-1">
                                        <span class="btn btn-danger btn-sm btn-remove-item"><i
                                                class="fa fa-trash"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
                <div class="text-right">
                    <span class="btn btn-info btn-sm btn-add-item"><i class="icon ion-ios-add-circle-outline"></i>
                        {{ __('Add item') }}</span>
                </div>
                <div class="g-more hide">
                    <div class="item" data-number="__number__">
                        <div class="row">
                            <div class="col-md-5">
                                <select __name__="socials[__number__][icon_class]" class="form-control">
                                    <option value="fa fa-facebook">Facebook</option>
                                    <option value="fa fa-google">Google</option>
                                    <option value="fa fa-linkedin">Linkedin</option>
                                    <option value="fa fa-youtube">Youtube</option>
                                    <option value="fa fa-instagram">Instagram</option>
                                    <option value="fa fa-pinterest">Pinterest</option>
                                    <option value="fa fa-twitter">Twitter</option>
                                    <option value="fa fa-tiktok">Tiktok</option>
                                    <option value="fa fa-flickr">Flickr</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <input type="text" __name__="socials[__number__][url]" class="form-control"
                                    placeholder="https://facebook.com" />
                            </div>
                            <div class="col-md-1">
                                <span class="btn btn-danger btn-sm btn-remove-item"><i class="fa fa-trash"></i></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- @if (is_default_lang())
        <div class="row">
            <div class="col-lg-12">
                <div class="form-group">
                    <label class="control-label">{{ __('Minimum day stay requirements') }}</label>
                    <input type="number" name="min_day_stays" class="form-control" value="{{ $row->min_day_stays }}"
                        placeholder="{{ __('Ex: 2') }}">
                    <i>{{ __('Leave blank if you dont need to set minimum day stay option') }}</i>
                </div>
            </div>
        </div>
        <hr>
    @endif

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('Number of beds') }} </label>
                <input type="number" value="{{ $row->beds ?? 1 }}" min="1" max="10"
                    placeholder="{{ __('Number') }}" name="beds" class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('Store Size') }} </label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" name="size" value="{{ $row->size ?? 0 }}"
                        placeholder="{{ __('Store size') }}">
                    <div class="input-group-append">
                        <span class="input-group-text">{!! size_unit_format() !!}</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('Max Adults') }} </label>
                <input type="number" min="1" value="{{ $row->adults ?? 1 }}" name="adults"
                    class="form-control">
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label>{{ __('Max Children') }} </label>
                <input type="number" min="0" value="{{ $row->children ?? 0 }}" name="children"
                    class="form-control">
            </div>
        </div>
    </div> --}}
@endif
