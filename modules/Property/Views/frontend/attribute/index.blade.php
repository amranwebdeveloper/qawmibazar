@extends('layouts.app')
@section('head')
@endsection
@section('content')
    <section class="our-listing pb30-991">
        <div class="container">
            <div class="col-lg-12">
                <div class="breadcrumb_content style2">
                    <h2 class="breadcrumb_title">{{ __('All Attributes') }}</h2>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Attributes</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                @if (!empty($rows))
                    @foreach ($rows as $attributeHeader)
                        <div class="col-sm-6 col-md-4 col-xl-2">
                            <a href="{{ $attributeHeader->getAttributeDetailUrl() }}">
                                <div class="icon-box text-center">
                                    <div class="icon">
                                        @if ($attributeHeader->image_id)
                                            <img src="{{ \Modules\Media\Helpers\FileHelper::url($attributeHeader->image_id, 'thumb') }}"
                                                alt="{{ $attributeHeader->name }}" />
                                        @endif
                                    </div>
                                    <div class="content-details">
                                        <div class="title">{{ $attributeHeader->name }}</div>
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
