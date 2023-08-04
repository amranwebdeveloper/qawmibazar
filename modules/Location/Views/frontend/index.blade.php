@php
use Modules\Property\Models\Property;
@endphp
@extends('layouts.app')
@section('head')
@endsection
@section('content')
    <section class="our-listing">
        <div class="container">
            <div class="col-lg-12">
                <div class="breadcrumb_content style2">
                    <h1 class="breadcrumb_title">{{ __('All Locations') }} | {{ setting_item('site_title') }}</h1>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">All Locations</li>
                    </ol>
                </div>
            </div>

            <div class="row">
                @if (!empty($alldivisions))
                    <div class="col-lg-12">
                        <h2 class="breadcrumb_title">{{ __('All Divisions') }}</h2>
                    </div>
                    @foreach ($alldivisions as $locationHeader)
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="property_city_home6 tac-xsd">
                                <div class="thumb">
                                    <a href="{{ $locationHeader->getDetailUrl() }}">
                                        <img class="w100" src="{{ $locationHeader->image_url }}"
                                            alt="{{ $locationHeader->name }}">
                                    </a>
                                </div>
                                <div class="details">
                                    <a href="{{ $locationHeader->getDetailUrl() }}">
                                        <h4>{{ $locationHeader->name }}</h4>
                                        <p>
                                            @php
                                                if (empty($locationHeader->parent_id)) {
                                                    $countparent = DB::table('bc_locations')
                                                        ->where('bc_locations.parent_id', $locationHeader->id)
                                                        ->get();
                                                    $countproperty = 0;
                                                    foreach ($countparent as $child) {
                                                        //echo $child->id;
                                                        $rows = DB::table('bc_locations')
                                                            ->join('bc_properties', 'bc_locations.id', '=', 'bc_properties.location_id')
                                                            // ->join('bc_properties', 'bc_locations.parent_id', '=', 'bc_properties.location_id')
                                                            ->where('bc_properties.location_id', $child->id)
                                                            ->get();

                                                        $countproperty += count($rows);
                                                    }
                                                    echo $countproperty > 1 ? $countproperty . ' Businesses' : $countproperty . ' Business';
                                                } else {
                                                    echo $locationHeader->property_count > 1 ? $locationHeader->property_count . ' Businesses' : $locationHeader->property_count . ' Business';
                                                }

                                            @endphp</p>
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif

                @if (!empty($alldistricts))
                    <div class="col-lg-12">
                        <h2 class="breadcrumb_title">{{ __('All Districts') }}</h2>
                    </div>
                    @foreach ($alldistricts as $locationHeader)
                        <div class="col-sm-6 col-md-6 col-lg-4 col-xl-3">
                            <div class="property_city_home6 tac-xsd">
                                <div class="thumb">
                                    <a href="{{ $locationHeader->getDetailUrl() }}">
                                        <img class="w100" src="{{ $locationHeader->image_url }}"
                                            alt="{{ $locationHeader->name }}">
                                    </a>
                                </div>
                                <div class="details">
                                    <a href="{{ $locationHeader->getDetailUrl() }}">
                                        <h4>{{ $locationHeader->name }}</h4>
                                        <p>
                                            @php
                                                if (empty($locationHeader->parent_id)) {
                                                    $countparent = DB::table('bc_locations')
                                                        ->where('bc_locations.parent_id', $locationHeader->id)
                                                        ->get();
                                                    $countproperty = 0;
                                                    foreach ($countparent as $child) {
                                                        //echo $child->id;
                                                        $rows = DB::table('bc_locations')
                                                            ->join('bc_properties', 'bc_locations.id', '=', 'bc_properties.location_id')
                                                            // ->join('bc_properties', 'bc_locations.parent_id', '=', 'bc_properties.location_id')
                                                            ->where('bc_properties.location_id', $child->id)
                                                            ->get();

                                                        $countproperty += count($rows);
                                                    }
                                                    echo $countproperty > 1 ? $countproperty . ' Businesses' : $countproperty . ' Business';
                                                } else {
                                                    echo $locationHeader->property_count > 1 ? $locationHeader->property_count . ' Businesses' : $locationHeader->property_count . ' Business';
                                                }

                                            @endphp</p>
                                        </p>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

@endsection

@section('footer')
@endsection
