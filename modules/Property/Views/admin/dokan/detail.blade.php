@extends('admin.layouts.app')

@section('content')
    <form
        action="{{ route('property.admin.dokan.store', ['property_id' => $property->id, 'id' => $row->id ? $row->id : '-1', 'lang' => request()->query('lang')]) }}"
        method="post">
        @csrf
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="d-flex justify-content-between mb20">
                        <div class="">
                            <h1 class="title-bar">{{ $row->id ? __('Edit: ') . $row->title : __('Add new Store') }}</h1>
                        </div>
                        @if($row->id)
                            <div class="">
                                @if ($row->id)
                                    <a class="btn btn-warning btn-md" href="{{ route('property.admin.product.index', ['dokan_id' => $row->id]) }}" target="_blank"><i class="fa fa-hand-o-right"></i> {{ __('Manage Products') }}</a>
                                @endif
                                @if ($row->slug)
                                    <a class="btn btn-primary btn-sm" href="{{url('/').'/listing/'.$property->slug.'/'.$row->slug}}" target="_blank">{{ __('View Store') }}</a>
                                @endif
                            </div>
                        @endif
                    </div>
                    @include('admin.message')
                    @if ($row->id)
                        @include('Language::admin.navigation')
                    @endif
                    <div class="lang-content-box">
                        <div class="panel">
                            <div class="panel-title"><strong>{{ __('Store information') }}</strong></div>
                            <div class="panel-body">
                                @include('Property::admin.dokan.form')
                            </div>
                            <div class="panel-footer">
                                <button class="btn btn-success" type="submit"><i class="fa fa-save"></i>
                                    {{ __('Save Changes') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
@endsection
@section('script.body')
    {!! App\Helpers\MapEngine::scripts() !!}
    <script>
        jQuery(function($) {
            new BravoMapEngine('map_content', {
                disableScripts: true,
                fitBounds: true,
                center: [{{ $row->map_lat ?? '23.753' }}, {{ $row->map_lng ?? '90.417' }}],
                zoom: {{ $row->map_zoom ?? '8' }},
                ready: function(engineMap) {
                    @if ($row->map_lat && $row->map_lng)
                    engineMap.addMarker([{{ $row->map_lat }}, {{ $row->map_lng }}], {
                        icon_options: {}
                    });
                    @endif
                    engineMap.on('click', function(dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                    engineMap.on('zoom_changed', function(zoom) {
                        $("input[name=map_zoom]").attr("value", zoom);
                    });
                    engineMap.searchBox($('.bc_searchbox'), function(dataLatLng) {
                        engineMap.clearMarkers();
                        engineMap.addMarker(dataLatLng, {
                            icon_options: {}
                        });
                        $("input[name=map_lat]").attr("value", dataLatLng[0]);
                        $("input[name=map_lng]").attr("value", dataLatLng[1]);
                    });
                }
            });
        })
    </script>
@endsection
