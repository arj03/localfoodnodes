<div class="row clearfix">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/node.information') }}</div>
            <div class="card-block">
                <div class="form-group">
                    <label class="form-control-label" for="name">
                        {{ trans('admin/node.name_node') }}
                        @include('admin.field-error', ['field' => 'name'])
                    </label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/node.name_node') }}" value="{{ $node->name or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="email">
                        {{ trans('admin/node.email') }}
                        @include('admin.field-error', ['field' => 'email'])
                    </label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('admin/node.email') }}" value="{{ $node->email or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="info">
                        {{ trans('admin/node.information') }}
                        @include('admin.field-error', ['field' => 'info'])
                    </label>
                    <textarea name="info" class="form-control wysiwyg" id="info" placeholder="{{ trans('admin/node.information_placeholder') }}" rows="5">{{ $node->info or '' }}</textarea>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="address">
                            {{ trans('admin/node.address') }}
                            @include('admin.field-error', ['field' => 'address'])
                        </label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="{{ trans('admin/node.address') }}" value="{{ $node->address }}">
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="zip">
                            {{ trans('admin/node.zip') }}
                            @include('admin.field-error', ['field' => 'zip'])
                        </label>
                        <input type="text" name="zip" class="form-control" id="zip" placeholder="{{ trans('admin/node.zip') }}" value="{{ $node->zip }}">
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="city">
                            {{ trans('admin/node.city') }}
                            @include('admin.field-error', ['field' => 'city'])
                        </label>
                        <input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{ $node->city }}">
                    </div>
                </div>

                @if ($node->address)
                    <a href="http://maps.google.com/?q={{ $node->address }} {{ $node->zip }} {{ $node->city }}" target="_blank">{{ trans('admin/node.show_address_on_map') }}</a>
                @endif

            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ trans('admin/node.deliveries') }}</div>
            <div class="card-block">
                <div class="form-group">
                    <label for="delivery_weekday">{{ trans('admin/node.weekday') }} @include('admin.field-error', ['field' => 'delivery_weekday'])</label>
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
                        @include('admin.field-error', ['field' => 'delivery_interval'])
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
                        @include('admin.field-error', ['field' => 'delivery_startdate'])
                    </label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                        <input type="text" name="delivery_startdate" class="form-control picker date" id="delivery_startdate" placeholder="{{ trans('admin/node.delivery_startdate') }}" value="{{ $node->delivery_startdate ? $node->delivery_startdate->format('Y-m-d') : '' }}">
                    </div>
                </div>

                @if ($node->id)
                    <div class="alert alert-warning mt-3">
                        <i class="fa fa-warning"></i> {{ trans('admin/node.change_weekday_warning') }}
                    </div>
                @endif

                <div class="form-group">
                    <label for="delivery_time">{{ trans('admin/node.time') }} @include('admin.field-error', ['field' => 'delivery_time'])</label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-clock-o"></i></span>
                        <input type="text" name="delivery_time" class="form-control picker time" id="delivery_time" placeholder="{{ trans('admin/node.time_placeholder') }}" value="{{ $node->delivery_time or '' }}">
                    </div>
                </div>
            </div>
        </div>

        @include('admin.image-card', [
            'images' => $node->images(),
            'deleteUrl' => '/account/image/{imageId}/delete',
            'limit' => 4,
        ])

        <div class="card">
            <div class="card-header">Facebook</div>
            <div class="card-block">
                <div class="form-group">
                    <label for="link_facebook">{{ trans('admin/node.facebook_page') }}</label>
                    <input type="text" name="link_facebook" class="form-control" id="address" placeholder="{{ trans('admin/node.facebook_page') }}" value="{{ $node->link_facebook }}">
                </div>
                <div class="form-group">
                    <label for="link_facebook_producers">{{ trans('admin/node.facebook_page_producers') }}</label>
                    <input type="text" name="link_facebook_producers" class="form-control" id="link_facebook_producers" placeholder="{{ trans('admin/node.facebook_page_producers') }}" value="{{ $node->link_facebook_producers }}">
                </div>
            </div>
        </div>
    </div>

    @include('admin.node.hdiw')

</div>
