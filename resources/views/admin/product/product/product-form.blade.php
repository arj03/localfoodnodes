<div class="row">
    <div class="col-12 col-xl-8">
        @include('admin.product.product.basic')

        <!-- Images -->
        @include('admin.image-card', [
            'images' => $product->images(),
            'deleteUrl' => '/account/image/{imageId}/delete',
            'limit' => 4,
        ])

        @include('admin.product.product.other-options')
    </div>

    <div class="col-12 col-xl-4">
        @include('admin.product.product.how-does-it-work')
    </div>
</div>
