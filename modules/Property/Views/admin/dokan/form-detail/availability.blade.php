@if (is_default_lang())
    <div class="panel" id="Availability">
        <div class="panel-title"><strong>{{ __('Store Availability') }}</strong></div>
        <div class="panel-body">
            <h3 class="panel-body-title">{{ __('Open Hours') }}</h3>
            <div class="form-group">
                <label>
                    <input type="checkbox" name="enable_open_hours" id="enable_open_hours" @if (!empty($row->enable_open_hours)) checked @endif value="1"> {{ __('Enable Open Hours') }}
                </label>
            </div>
            <?php $old = $row->open_hours ?? []; ?>
            <div class="table-responsive form-group view_enable_open_hours" data-condition="enable_open_hours:is(1)">
                <table class="table">
                    <thead>
                    <tr>
                        <th>{{ __('Enable?') }}( <input type="checkbox" name="" id="sameSchedule" /> Same Time )</th>
                        <th>{{ __('Day of Week') }}</th>
                        <th>{{ __('Open') }}</th>
                        <th>{{ __('Close') }}</th>
                    </tr>
                    </thead>
                    @for ($i = 1; $i <= 7; $i++)
                        <tr>
                            <td>
                                @if ($old[$i]['enable'] ?? false)
                                    <input style="display: inline-block" type="radio" checked name="open_hours[{{ $i }}][enable]" id="dayEnable_{{ $i }}"  class="dayEnable" value="1"> Open
                                    <input style="display: inline-block" type="radio" name="open_hours[{{ $i }}][enable]" id="dayDisable_{{ $i }}" class="dayEnable" value="0"> Close
                                @else
                                    <input style="display: inline-block" type="radio"  name="open_hours[{{ $i }}][enable]" id="dayEnable_{{ $i }}"  class="dayEnable" value="1"> Open
                                    <input style="display: inline-block" type="radio" checked  name="open_hours[{{ $i }}][enable]" id="dayDisable_{{ $i }}" class="dayEnable" value="0"> Close
                                @endif
                            </td>
                            <td><strong>
                                    @switch($i)
                                        @case(1)
                                        {{ __('Monday') }}
                                        @break

                                        @case(2)
                                        {{ __('Tuesday') }}
                                        @break

                                        @case (3)
                                        {{ __('Wednesday') }}
                                        @break

                                        @case (4)
                                        {{ __('Thursday') }}
                                        @break

                                        @case (5)
                                        {{ __('Friday') }}
                                        @break

                                        @case (6)
                                        {{ __('Saturday') }}
                                        @break

                                        @case (7)
                                        {{ __('Sunday') }}
                                        @break
                                    @endswitch
                                </strong></td>
                            <td>
                                <select class="form-control open_hours" name="open_hours[{{ $i }}][from]">
                                    <?php
                                    $time = strtotime('2019-01-01 00:00:00');
                                    for($k = 0; $k <= 47; $k++):

                                    $val = date('H:i', $time + 60 * 30 * $k);
                                    ?>
                                    <option @if (isset($old[$i]) and $old[$i]['from'] == $val) selected @endif
                                    value="{{ $val }}">{{ $val }}</option>

                                    <?php endfor;?>
                                </select>
                            </td>
                            <td>
                                <select class="form-control close_hours" name="open_hours[{{ $i }}][to]">
                                    <?php
                                    $time = strtotime('2019-01-01 00:00:00');
                                    for($k = 0; $k <= 47; $k++):

                                    $val = date('H:i', $time + 60 * 30 * $k);
                                    ?>
                                    <option @if (isset($old[$i]) and $old[$i]['to'] == $val) selected @endif
                                    value="{{ $val }}">{{ $val }}</option>

                                    <?php endfor;?>
                                </select>
                            </td>
                        </tr>
                    @endfor
                </table>
            </div>
        </div>
    </div>
@endif
