<div class="row">
    <div class="col-12 col-xl-8">
        <div class="card">
            <div class="card-header">{{ trans('admin/producer.producer_information') }}</div>
            <div class="card-block">
                <div class="form-group">
                    <label class="form-control-label" for="name">
                        {{ trans('admin/producer.company_name') }}
                        @include('account.field-error', ['field' => 'name'])
                    </label>
                    <input type="text" name="name" class="form-control" id="name" placeholder="{{ trans('admin/producer.name') }}" value="{{ $producer->name or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="email">
                        {{ trans('admin/producer.email') }}
                        @include('account.field-error', ['field' => 'email'])
                    </label>
                    <input type="email" name="email" class="form-control" id="email" placeholder="{{ trans('admin/producer.email') }}" value="{{ $producer->email or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="info">
                        {{ trans('admin/producer.company_description') }}
                        @include('account.field-error', ['field' => 'info'])
                    </label>
                    <textarea name="info" class="form-control wysiwyg" id="info" placeholder="{{ trans('admin/producer.info') }}" rows="5">{{ $producer->info or '' }}</textarea>
                </div>
                <div class="row">
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="address">
                            {{ trans('admin/producer.address') }}
                            @include('account.field-error', ['field' => 'address'])
                        </label>
                        <input type="text" name="address" class="form-control" id="address" placeholder="Address" value="{{ $producer->address or '' }}">
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="zip">
                            {{ trans('admin/producer.zip') }}
                            @include('account.field-error', ['field' => 'zip'])
                        </label>
                        <input type="text" name="zip" class="form-control" id="zip" placeholder="Zip" value="{{ $producer->zip or '' }}">
                    </div>
                    <div class="form-group col-12 col-lg-4">
                        <label class="form-control-label" for="city">
                            {{ trans('admin/producer.city') }}
                            @include('account.field-error', ['field' => 'city'])
                        </label>
                        <input type="text" name="city" class="form-control" id="city" placeholder="City" value="{{ $producer->city or '' }}">
                    </div>
                </div>
                @if ($producer->address)
                    <a href="http://maps.google.com/?q={{ $producer->address }} {{ $producer->zip }} {{ $producer->city }}" target="_blank">Show address on map</a>
                @endif
            </div>
        </div>

        @include('account.image-card', [
            'images' => $producer->images(),
            'deleteUrl' => '/account/image/{imageId}/delete',
            'limit' => 4,
        ])

        <div class="card">
            <div class="card-header">{{ trans('admin/producer.payment_info') }}</div>
            <div class="card-block">
                <div class="row">
                    <div class="form-group col-12 col-lg-6">
                        <label for="currency">{{ trans('admin/producer.currency') }}</label>
                        <select name="currency" id="currency" class="form-control">
                            <option value="">{{ trans('admin/producer.select_currency') }}</option>
                            @foreach (UnitsHelper::getCurrencies() as $currency)
                                <option value="{{ $currency }}" {{ $currency === $producer->currency ? 'selected' : '' }}>{{ $currency }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label for="payment-info">{{ trans('admin/producer.payment_info_email') }}</label>
                    <textarea class="form-control" id="payment-info" name="payment_info" rows="3">{{ $producer->payment_info }}</textarea>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">Social</div>
            <div class="card-block">
                <div class="form-group">
                    <label class="form-control-label" for="link_homepage">Homepage</label>
                    <input type="text" name="link_homepage" class="form-control" id="link_homepage" placeholder="Homepage" value="{{ $producer->link_homepage or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="link_facebook">Facebook</label>
                    <input type="text" name="link_facebook" class="form-control" id="link_facebook" placeholder="Facebook" value="{{ $producer->link_facebook or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="link_twitter">Twitter</label>
                    <input type="text" name="link_twitter" class="form-control" id="link_twitter" placeholder="Twitter" value="{{ $producer->link_twitter or '' }}">
                </div>
                <div class="form-group">
                    <label class="form-control-label" for="link_instagram">Instagram</label>
                    <input type="text" name="link_instagram" class="form-control" id="link_instagram" placeholder="Instagram" value="{{ $producer->link_instagram or '' }}">
                </div>
            </div>
        </div>
    </div>

    @include('account.producer.hdiw')
</div>
