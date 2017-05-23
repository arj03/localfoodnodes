<input type="hidden" name="owner_type" value="{{ $eventOwner->eventOwnerType() }}">
<input type="hidden" name="owner_id" value="{{ $eventOwner->id }}">

<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/event.information') }}</div>
            <div class="card-block">
                <div class="form-group">
                    <label for="name">
                        {{ trans('admin/event.name') }}
                        @include('admin.field-error', ['field' => 'name'])
                    </label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/event.name') }}" value="{{ $event->name }}">
                </div>
                <div class="form-group">
                    <label for="info">
                        {{ trans('admin/event.information') }}
                        @include('admin.field-error', ['field' => 'info'])
                    </label>
                    <textarea name="info" class="form-control wysiwyg" id="info" placeholder="{{ trans('admin/event.information') }}" rows="5">{{ $event->info }}</textarea>
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-control-label" for="start_datetime">
                                {{ trans('admin/event.start_date_and_time') }}
                                @include('admin.field-error', ['field' => 'start_datetime'])
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="start_datetime" class="form-control picker datetime" id="start_datetime" placeholder="{{ trans('admin/event.start_date_and_time') }}" value="{{ $event->start_datetime ? $event->start_datetime->format('Y-m-d H:i') : '' }}">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label class="form-control-label" for="end_datetime">
                                {{ trans('admin/event.end_date_and_time') }}
                                @include('admin.field-error', ['field' => 'end_datetime'])
                            </label>
                            <div class="input-group">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                <input type="text" name="end_datetime" class="form-control picker datetime" id="end_datetime" placeholder="{{ trans('admin/event.end_date_and_time') }}" value="{{ $event->end_datetime ? $event->end_datetime->format('Y-m-d H:i') : '' }}">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="fee">{{ trans('admin/event.fee') }}</label>
                    <input type="number" min="0" name="fee" class="form-control" id="fee" placeholder="{{ trans('admin/event.fee') }}" value="{{ $event->fee }}">
                </div>
            </div>
        </div>

        @include('admin.image-card', [
            'images' => $event->images(),
            'deleteUrl' => '/account/image/{imageId}/delete',
            'limit' => 4,
        ])

        <div class="card">
            <div class="card-header">{{ trans('admin/event.address') }}</div>
            <div class="card-block">
                <div class="form-group">
                    <label for="address">
                        {{ trans('admin/event.address') }}
                        @include('admin.field-error', ['field' => 'address'])
                    </label>
                    <input type="text" name="address" class="form-control" id="address" placeholder="{{ trans('admin/event.address') }}" value="{{ $event->address }}">
                </div>
                <div class="form-group">
                    <label for="zip">
                        {{ trans('admin/event.zip') }}
                        @include('admin.field-error', ['field' => 'zip'])
                    </label>
                    <input type="text" name="zip" class="form-control" id="zip" placeholder="{{ trans('admin/event.zip') }}" value="{{ $event->zip }}">
                </div>
                <div class="form-group">
                    <label for="city">
                        {{ trans('admin/event.city') }}
                        @include('admin.field-error', ['field' => 'city'])
                    </label>
                    <input type="text" name="city" class="form-control" id="city" placeholder="{{ trans('admin/event.city') }}" value="{{ $event->city }}">
                </div>
                @if ($event->address)
                    <a href="http://maps.google.com/?q={{ $event->address }} {{ $event->zip }} {{ $event->city }}" target="_blank">Show address on map</a>
                @endif
            </div>
        </div>

        <div class="card">
            <div class="card-header">{{ trans('admin/event.visibility') }}</div>
            <div class="card-block">
                <div class="form-check">
                    <fieldset class="form-group">
                        <legend class="col-form-legend">{{ trans('admin/event.visibility') }}</legend>
                        <label class="form-check-label">
                            <input class="form-check-input" name="hidden" type="hidden" value="0" />
                            <input class="form-check-input" name="hidden" type="checkbox" value="1" {{ $event->hidden == 1 ? 'checked' : '' }} />
                            {{ trans('admin/event.hide_from_map') }}
                        </label>
                    </fieldset>
                </div>
            </div>
        </div>
    </div>

    <div class="col-12 col-xl-4">
        <div class="card">
            <div class="card-header">How does it work?</div>
            <div class="card-block">
                {{ trans('admin/event.hdiw_intro') }}
                <ul class="info-list">
                    <li><span class="info-count">1</span><p>{{ trans('admin/event.hdiw_item_1') }}</p></li>
                    <li><span class="info-count">3</span><p>{{ trans('admin/event.hdiw_item_2') }}</p></li>
                    <li><span class="info-count">4</span><p>{{ trans('admin/event.hdiw_item_3') }}</p></li>
                </ul>
            </div>
        </div>
    </div>
</div>
