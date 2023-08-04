@extends('admin.layouts.app')
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('All Businesses') }}</h1>
            <div class="title-actions">
                <a href="{{ route('property.admin.create') }}" class="btn btn-primary">{{ __('Add new Business') }}</a>
            </div>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if (!empty($rows))
                    <form method="post" action="{{ route('property.admin.bulkEdit') }}"
                        class="filter-form filter-form-left d-flex justify-content-start">
                        {{ csrf_field() }}
                        <select name="action" class="form-control">
                            <option value="">{{ __(' Bulk Actions ') }}</option>
                            <option value="publish">{{ __(' Publish ') }}</option>
                            <option value="draft">{{ __(' Move to Draft ') }}</option>
                            <option value="pending">{{ __('Move to Pending') }}</option>
                            <option value="clone">{{ __(' Clone ') }}</option>
                            <option value="delete">{{ __(' Delete ') }}</option>
                        </select>
                        <button data-confirm="{{ __('Do you want to delete?') }}"
                            class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{ __('Apply') }}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                <form method="get" action="{{ route('property.admin.index') }} "
                    class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    @if (!empty($rows) and $property_manage_others)
                        <?php
                        $user = !empty(Request()->vendor_id) ? App\User::find(Request()->vendor_id) : false;
                        \App\Helpers\AdminForm::select2(
                            'vendor_id',
                            [
                                'configs' => [
                                    'ajax' => [
                                        'url' => url('/admin/module/user/getForSelect2'),
                                        'dataType' => 'json',
                                        'data' => ['user_type' => 'vendor'],
                                    ],
                                    'allowClear' => true,
                                    'placeholder' => __('-- Vendor --'),
                                ],
                            ],
                            !empty($user->id) ? [$user->id, $user->name_or_email . ' (#' . $user->id . ')'] : false,
                        );
                        ?>
                    @endif
                    <input type="text" name="s" value="{{ Request()->s }}"
                        placeholder="{{ __('Search by name') }}" class="form-control">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{ __('Search') }}</button>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{ __('Found :total items', ['total' => $rows->total()]) }}</i></p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bc-form-item">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60px"><input type="checkbox" class="check-all"></th>
                                    <th> {{ __('Name') }}</th>
                                    <th width="200px"> {{ __('Location') }}</th>
                                    <th width="130px"> {{ __('Author') }}</th>
                                    <th width="100px"> {{ __('Status') }}</th>
                                    <th width="100px"> {{ __('Reviews') }}</th>
                                    <th width="100px"> {{ __('Date') }}</th>
                                    <th width="100px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($rows->total() > 0)
                                    @foreach ($rows as $row)
                                        <tr class="{{ $row->status }}">
                                            <td><input type="checkbox" name="ids[]" class="check-item"
                                                    value="{{ $row->id }}">
                                            </td>
                                            <td class="title">
                                                <a
                                                    href="{{ route('property.admin.edit', ['id' => $row->id]) }}">{{ $row->title }}</a>
                                            </td>
                                            <td>{{ $row->location->name ?? '' }}</td>
                                            <td>
                                                @if (!empty($row->author))
                                                    {{ $row->author->getDisplayName() }}
                                                @else
                                                    {{ __('[Author Deleted]') }}
                                                @endif
                                            </td>
                                            <td><span class="badge badge-{{ $row->status }}">{{ $row->status }}</span>
                                            </td>
                                            <td>
                                                <a target="_blank"
                                                    href="{{ url('/admin/module/review?service_id=' . $row->id . '&service=property') }}"
                                                    class="review-count-approved">
                                                    {{ $row->getNumberReviewsInService() }}
                                                </a>
                                            </td>
                                            <td>{{ display_date($row->updated_at) }}</td>

                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-info dropdown-toggle" type="button"
                                                        id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true"
                                                        aria-expanded="false">
                                                        {{ __('Action') }}
                                                    </button>
                                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                        <a class="dropdown-item"
                                                            href="{{ route('property.admin.edit', ['id' => $row->id]) }}">{{ __('Edit Business') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('property.admin.dokan.index', ['property_id' => $row->id]) }}">{{ __('Manage Stores') }}</a>
                                                        <a class="dropdown-item"
                                                            href="{{ route('property.admin.dokan.availability.index', ['property_id' => $row->id]) }}">{{ __('Manage Stores Availability') }}</a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                @else
                                    <tr>
                                        <td colspan="7">{{ __('No Business found') }}</td>
                                    </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </form>
                {{ $rows->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection