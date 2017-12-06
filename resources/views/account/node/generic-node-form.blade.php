<div class="row clearfix">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/node.information') }}</div>
            <div class="card-body">
                <div class="form-group">
                    <label class="form-control-label" for="name">
                        {{ trans('admin/node.name_node') }}
                        @include('account.field-error', ['field' => 'name'])
                    </label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/node.name_node') }}" value="{{ $node->name or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="email">
                        {{ trans('admin/node.email') }}
                        @include('account.field-error', ['field' => 'email'])
                    </label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('admin/node.email') }}" value="{{ $node->email or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="info">
                        {{ trans('admin/node.information') }}
                        @include('account.field-error', ['field' => 'info'])
                    </label>
                    <textarea name="info" class="form-control wysiwyg" id="info" placeholder="{{ trans('admin/node.information_placeholder') }}" rows="5">{{ $node->info or '' }}</textarea>
                </div>

                <div class="row">
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="address">
                            {{ trans('admin/node.address') }}
                            @include('account.field-error', ['field' => 'address'])
                        </label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="{{ trans('admin/node.address') }}" value="{{ $node->address }}">
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="zip">
                            {{ trans('admin/node.zip') }}
                            @include('account.field-error', ['field' => 'zip'])
                        </label>
                        <input type="text" name="zip" class="form-control" id="zip" placeholder="{{ trans('admin/node.zip') }}" value="{{ $node->zip }}">
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="city">
                            {{ trans('admin/node.city') }}
                            @include('account.field-error', ['field' => 'city'])
                        </label>
                        <input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{ $node->city }}">
                    </div>
                </div>

                @if ($node->address)
                    <a href="http://maps.google.com/?q={{ $node->address }} {{ $node->zip }} {{ $node->city }}" target="_blank">{{ trans('admin/node.show_address_on_map') }}</a>
                @endif

            </div>
        </div>

        @include('account.node.delivery-settings-form')

        @include('account.image-card', [
            'images' => $node->images(),
            'deleteUrl' => '/account/image/{imageId}/delete',
            'limit' => 4,
        ])

        <div class="card">
            <div class="card-header">{{ trans('admin/node.other_settings') }}</div>
            <div class="card-body">
                <div class="form-check">
                    <fieldset class="form-group">
                        <legend class="col-form-legend">{{ trans('admin/node.visibility') }}</legend>
                        <label class="form-check-label">
                            <input class="form-check-input" name="is_hidden" type="hidden" value="0" />
                            <input class="form-check-input" name="is_hidden" type="checkbox" value="1" {{ $node->is_hidden == 1 ? 'checked' : '' }} />
                            {{ trans('admin/node.hide_from_map') }}
                        </label>
                    </fieldset>
                </div>
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

    @include('account.node.hdiw')

</div>
