@extends('layouts.app')
@section('head')
@endsection
@section('content')
    <section class="our-listing pb30-991">
        <div class="container">
            <div class="col-lg-12">
                <div class="breadcrumb_content style2">
                    <h1 class="breadcrumb_title">{{ __('All Categories') }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Categories</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                @if (!empty($propertyCategoryHeader))
                    @foreach ($propertyCategoryHeader as $categoryHeader)
                        <div class="col-sm-6 col-md-4 col-xl-2">
                            <a href="{{ $categoryHeader->getDetailUrl() }}">
                                <div class="icon-box text-center">
                                    <div class="icon">
                                        @if ($categoryHeader->image_id)
                                            <img src="{{ \Modules\Media\Helpers\FileHelper::url($categoryHeader->image_id, 'thumb') }}"
                                                alt="{{ $categoryHeader->name }}" />
                                        @endif
                                    </div>
                                    <div class="content-details">
                                        <div class="title">{{ $categoryHeader->name }}</div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

@endsection

@section('footer')
@endsection
