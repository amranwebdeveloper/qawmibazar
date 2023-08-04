@if (!empty($location_category) and !empty($row->surrounding))
    <div class="col-lg-12 g-surrounding">
        <div class="location-title">
            <h3 class="mb-4">{{ __("What's Nearby") }}</h3>
            @foreach ($location_category as $category)
                @if (!empty($row->surrounding[$category->id]))
                    <h6 class="font-weight-bold mb-3"><i class="{{ clean($category->icon_class) }} "></i>
                        {{ $category->location_category_translations->name ?? $category->name }}</h6>
                    @foreach ($row->surrounding[$category->id] as $item)
                        <div class="row ml0 mr0 feat_property">
                            @if (!empty($item['image_id']))
                                <div class="col-lg-3 pl0">
                                    <img src="{{ !empty($item['image_id']) ? get_file_url($item['image_id'], 'full') : '' }}"
                                        alt="{{ $item['name'] }}" class="surrounding-img" />
                                </div>
                            @endif
                            <div
                                class="@if (!empty($item['image_id'])) col-lg-9 pl0
                            @else
                            col-lg-12 pl0 @endif">
                                <div class="row mb-2">
                                    <div class="col-lg-9 surrounding-title"> {{ $item['name'] }}</div>
                                    <div class="col-lg-3">Distance: {{ $item['value'] }} {{ $item['type'] }}</div>
                                </div>
                                {{ $item['content'] }}
                            </div>
                        </div>
                    @endforeach
                @endif
            @endforeach
        </div>
    </div>
    <div class="bravo-hr"></div>
@endif
