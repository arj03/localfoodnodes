<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/node.deliveries') }}</div>
            <div class="card-body">
                @if ($node->id)
                    <div class="alert alert-danger">
                        <i class="fa fa-warning"></i> {{ trans('admin/node.change_weekday_warning') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="delivery_weekday">{{ trans('admin/node.weekday') }} @include('account.field-error', ['field' => 'delivery_weekday'])</label>
                    <select name="delivery_weekday" id="delivery_weekday" class="form-control">
                        <option value="">{{ trans('admin/node.select_weekday') }}</option>
                        <option value="monday" {{ $node->delivery_weekday === 'monday' ? ' selected' : '' }}>{{ trans('admin/node.monday') }}</option>
                        <option value="tuesday" {{ $node->delivery_weekday === 'tuesday' ? ' selected' : '' }}>{{ trans('admin/node.tuesday') }}</option>
                        <option value="wednesday" {{ $node->delivery_weekday === 'wednesday' ? ' selected' : '' }}>{{ trans('admin/node.wednesday') }}</option>
                        <option value="thursday" {{ $node->delivery_weekday === 'thursday' ? ' selected' : '' }}>{{ trans('admin/node.thursday') }}</option>
                        <option value="friday" {{ $node->delivery_weekday === 'friday' ? ' selected' : '' }}>{{ trans('admin/node.friday') }}</option>
                        <option value="saturday" {{ $node->delivery_weekday === 'saturday' ? ' selected' : '' }}>{{ trans('admin/node.saturday') }}</option>
                        <option value="sunday" {{ $node->delivery_weekday === 'sunday' ? ' selected' : '' }}>{{ trans('admin/node.sunday') }}</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="delivery_interval">
                        {{ trans('admin/node.delivery_interval') }}
                        @include('account.field-error', ['field' => 'delivery_interval'])
                    </label>
                    <select name="delivery_interval" id="delivery_interval" class="form-control">
                        <option value="">{{ trans('admin/node.select_interval') }}</option>
                        @foreach ($node->intervals as $key => $interval)
                            <option value="{{ $interval }}" {{ $node->delivery_interval === $interval ? ' selected' : '' }}>{{ trans('admin/node.' . $key) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-control-label" for="delivery_startdate">
                        {{ trans('admin/node.delivery_startdate') }}
                        @include('account.field-error', ['field' => 'delivery_startdate'])
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="delivery_startdate" class="form-control picker date" id="delivery_startdate" placeholder="{{ trans('admin/node.delivery_startdate') }}" value="{{ $node->delivery_startdate ? $node->delivery_startdate->format('Y-m-d') : '' }}">
                    </div>
                </div>

                <div class="form-group">
                    <label for="delivery_time">{{ trans('admin/node.time') }} @include('account.field-error', ['field' => 'delivery_time'])</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        <input type="text" name="delivery_time" class="form-control picker time" id="delivery_time" placeholder="{{ trans('admin/node.time_placeholder') }}" value="{{ $node->delivery_time or '' }}">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
