@extends('layouts.app')
@section('head')
    <link rel="stylesheet" type="text/css" href="{{ asset('libs/ion_rangeslider/css/ion.rangeSlider.min.css') }}" />
@endsection
@section('content')
    <div class="bc_detail_location">
        <section class="our-listing pb30-991" style="background: url('{{ get_file_url($row->banner_image_id, 'full') }}')">
            <div class="container">

                <div class="row">
                    <div class="col-lg-12">
                        <div class="breadcrumb_content style2">
                            <h2 class="breadcrumb_title">{{ $attribute->name }}</h2>
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                <li class="breadcrumb-item"><a href="{{ url('/attribute') }}">{{ __('Attribute') }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{ $attribute->name }}</li>
                            </ol>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="dn db-lg mt30 mb0 tac-767">
                            <div id="main2">
                                <span id="open2" class="fa fa-filter filter_open_btn style2">
                                    {{ __('Show Filter') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container">
                <div class="row">
                    @php $layout = request()->query('layout') @endphp
                    @if ($rows->total() > 0)
                        @foreach ($rows as $row)
                            <div class="col-sm-3 col-md-4 col-xl-2">
                                <a href="{{ $row->getTermDetailUrl($attribute->slug) }}">
                                    <div class="icon-box text-center">
                                        <div class="icon">
                                            @if ($row->image_id)
                                                <img src="{{ \Modules\Media\Helpers\FileHelper::url($row->image_id, 'thumb') }}"
                                                    alt="{{ $row->name }}" />
                                            @endif
                                        </div>
                                        <div class="content-details">
                                            <div class="title">{{ $row->name }}</div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12">
                            <div class="border rounded p-3 bg-white">
                                {{ __('Business not found') }}
                            </div>
                        </div>
                    @endif

                    <div class="col-lg-12 mt20">
                        <div class="mbp_pagination">
                            {{ $rows->appends(request()->query())->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('footer')
    {!! App\Helpers\MapEngine::scripts() !!}

    <script type="text/javascript" src="{{ asset('libs/ion_rangeslider/js/ion.rangeSlider.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('libs/sticky/jquery.sticky.js') }}"></script>
@endsection
