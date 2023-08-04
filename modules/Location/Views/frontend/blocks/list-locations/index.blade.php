<div class="container">
    <div class="bc-list-locations pt70 pb70 @if (!empty($layout)) {{ $layout }} @endif">
        <div class="main-title text-center">
            <div class="title  ">
                <h2>{{ $title }}</h2>
            </div>
            @if (!empty($desc))
                <h3 class="sub-title ">
                    {{ $desc }}
                </h3>
            @endif
        </div>
        @if (!empty($rows))
            <div class="list-item">
                <div class="row ">
                    @foreach ($rows as $key => $row)
                        <?php
                        $argv = [8, 4, 4, 8];
                        if ($layout == 'style_3') {
                            $size_col = 4;
                        } else {
                            $size_col = $argv[$key % 4];
                        }
                        ?>
                        <div class="col-lg-{{ $size_col }} col-md-6">
                            @includeIf('Location::frontend.blocks.list-locations.layouts.' . $layout)
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</div>
