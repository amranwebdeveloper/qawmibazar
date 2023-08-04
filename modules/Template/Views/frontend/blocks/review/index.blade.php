<!-- Our Reviews -->
<section id="our-reviews" class="our-reviews">
    <div class="container ovh max1800">
        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="main-title text-center">
                    @if (!empty($title))
                        <h4>{{ $title }}</h4>
                    @endif
                    @if (!empty($sub_title))
                        <p>{{ $sub_title }}</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="testimonial_slider_home1 testimonial_grid_slider">
                    @foreach ($rows as $row)
                        <?php

                        $users = DB::table('users')
                            ->where('id', $row['create_user'])
                            ->first();
                        if ($row['object_model'] == 'property') {
                            $property = DB::table('bc_properties')
                                ->where('id', $row['object_id'])
                                ->first();
                        } elseif ($row['object_model'] == 'dokan') {
                            $property = DB::table('bc_property_dokans')
                                ->where('id', $row['object_id'])
                                ->first();

                            $properybydokan = DB::table('bc_properties')
                                ->where('id', $property->parent_id)
                                ->first();
                        } else {
                            $property = DB::table('bc_news')
                                ->where('id', $row['object_id'])
                                ->first();
                        }

                        $avatar_url = get_file_url($users->avatar_id, 'full'); ?>
                        <div class="item">
                            <div class="testimonial_post text-center">
                                <div class="thumb">
                                    @if($avatar_url =='')
                                        <img src="{{ asset('images/avatar.png') }}" alt="{{ $users->name }}">
                                    @else
                                        <img src="{{ $avatar_url }}" alt="{{ $users->name }}">
                                    @endif
                                    <div class="title">{{ $users->name }}</div>
                                    <div class="client-postn">{{ $users->job }}</div>
                                </div>
                                <div class="details">

                                    <div class="icon"><span>“</span></div>
                                    <p>“ {!! clean($row['content']) !!} “</p>
                                    @if ($row['object_model'] == 'property')
                                        <strong class="sub_title">Business :
                                            <a href="{{ 'haat/' . $property->slug }}"
                                                @if (!empty($blank)) target="_blank" @endif>
                                                {{ $property->title }}
                                            </a>
                                        </strong>
                                    @elseif ($row['object_model'] == 'dokan')
                                        <strong class="sub_title">{{ ucfirst($row['object_model']) }} :
                                            <a href="{{ 'haat/' . $properybydokan->slug . '/' . $property->slug }}"
                                                @if (!empty($blank)) target="_blank" @endif>
                                                {{ $property->title }}
                                            </a>
                                        </strong>
                                    @else
                                        <strong class="sub_title">{{ ucfirst($row['object_model']) }} :
                                            <a href="{{ 'news/' . $property->slug }}"
                                                @if (!empty($blank)) target="_blank" @endif>
                                                {{ $property->title }}
                                            </a>
                                        </strong>
                                    @endif

                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</section>
