@if (count($news) > 0)
    <section class="feature-property news bgc-f4">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="main-title text-center">
                        <h2>{{ __('Blogs: Business News & Ideas') }}</h2>
                    </div>
                </div>
                @foreach ($news as $new)
                    <div class="col-lg-4">
                        <div class="feat_property ">
                            <div class="thumb">
                                <a href="{{ url('/') }}/news/{{ $new->slug }}">
                                    @if ($new->image_id)
                                        <img class="img-whp" src="{{ get_file_url($new->image_id, 'medium') }}"
                                            alt="{{ $new->title }}">
                                    @else
                                        <img src="{{ get_file_url(setting_item('default_property_image')) }}"
                                            alt="{{ $new->title }}">
                                    @endif
                                </a>
                            </div>
                            <div class="details">
                                <div class="tc_content">
                                    <a href="{{ url('/') }}/news/{{ $new->slug }}"
                                        @if (!empty($blank)) @endif>
                                        <h4 class="title">{{ $new->title }}</h4>
                                    </a>
                                    <p>{{ get_exceprt($new->content, 11, '...') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endif
