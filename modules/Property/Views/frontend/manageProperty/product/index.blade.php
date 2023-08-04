@extends('layouts.user')
@section('head')
@endsection
@section('content')
    <h2 class="title-bar">
        {{ __('Manage Stores') }}
        <div class="title-action">
            <a href="{{ route('property.vendor.dokan.edit', ['property_id' => $property->id, 'id' => $dokan->id]) }}" class="btn btn-info"><i class="fa fa-hand-o-right"></i>{{ __('Back to Store') }}</a>

            {{-- <a href="{{ route('property.vendor.dokan.availability.index', ['dokan_id' => $dokan->id]) }}"
                class="btn btn-warning"><i class="fa fa-calendar"></i> {{ __('Availability Stores') }}</a> --}}
            <a href="{{ route('property.vendor.product.create', ['dokan_id' => $dokan->id]) }}" class="btn btn-success"><i class="fa fa-plus" aria-hidden="true"></i> {{ __('Add Product') }}</a>
        </div>
    </h2>
    @include('admin.message')
    @if ($rows->total() > 0)
        <div class="bc-list-item">
            <div class="bravo-pagination">
                <span
                    class="count-string">{{ __('Showing :from - :to of :total Stores', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                {{ $rows->appends(request()->query())->links() }}
            </div>
            <div class="list-item">
                <div class="row">
                    @foreach ($rows as $row)
                        <div class="col-md-12">
                            @include('Property::frontend.manageProperty.product.loop-list')
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="bravo-pagination">
                <span
                    class="count-string">{{ __('Showing :from - :to of :total Stores', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                {{ $rows->appends(request()->query())->links() }}
            </div>
        </div>
    @else
        {{ __('No Store') }}
    @endif
@endsection
@section('footer')
@endsection
