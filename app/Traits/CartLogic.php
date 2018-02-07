<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\MessageBag;

use App\Cart\Cart;
use App\Cart\CartDate;
use App\Cart\CartItem;
use App\Cart\CartDateItemLink;

use App\Jobs\SendOrderEmails;

trait CartLogic
{
    /**
     * Validate request to add one item to cart.
     *
     * @param Request $reqest
     * @param Product $product
     * @return Collection $errors
     */
    private function validateAddItemRequest($request, $product)
    {
        $errors = new MessageBag();

        if ($product->variants()->count() > 0 && !$request->has('variant_id')) {
            $errors->add('error', trans('public/product.no_variant'));
        }

        if (!$request->has('delivery_dates') || empty($request->input('delivery_dates'))) {
            $errors->add('error', trans('public/product.no_delivery_dates'));
        }

        if (!$request->has('quantity') || empty($request->input('quantity'))) {
            $errors->add('error', trans('public/product.no_quantity'));
        }

        return $errors;
    }

    /**
     * Add item to cart.
     *
     * @param array $data
     * @param User $user
     * @param Producer $producer
     * @param Product $product
     * @param Variant $variant
     * @param Node $node
     */
    private function add($data, $user, $producer, $product, $variant, $node)
    {
        // Get existing cart dates for node and create the ones missing
        $existingCartDates = $user->cartDates();

        $this->createCartDates($data['delivery_dates'], $existingCartDates, $user, $node);
        $cartDates = $user->cartDates($data['delivery_dates']);

        // Check if item's already in cart
        if ($variant) {
            $cartItem = $user->cartItem($product->id, $node->id, $variant->id);
        } else {
            $cartItem = $user->cartItem($product->id, $node->id);
        }

        if (!$cartItem) {
            $cartItem = $this->createCartItem($user, $producer, $product, $node, $variant, $data['message']);
        } else if ($data['message']) {
            $cartItem->message = $data['message'];
            $cartItem->save();
        }

        return $this->validateAndCreateCartDateItemLink($user, $cartDates, $cartItem, $product, $variant, $node, $data['quantity']);
    }

    /**
     * Validate quantity for selected date and adjust quantity if needed, and then create links.
     *
     * @param User $user
     * @param Collection $cartDates
     * @param CartItem $cartItem
     * @param Product $product
     * @param Variant $variant
     * @param Node $node
     * @param int $quantity
     */
    private function validateAndCreateCartDateItemLink($user, $cartDates, $cartItem, $product, $variant, $node, $quantity)
    {
        $errors = new MessageBag();

        $existingCartDateItemLinks = new Collection();
        $cartItem->cartDateItemLinks()->each(function($cartDateItemLink) use (&$existingCartDateItemLinks) {
            $date = $cartDateItemLink->getDate()->date('Y-m-d');
            $existingCartDateItemLinks->put($date, $cartDateItemLink);
        });

        $cartQuantity = 0;
        foreach ($cartDates as $cartDate) {
            $existingCartDateItemLink = $existingCartDateItemLinks->get($cartDate->date('Y-m-d'));

            // If date item link exists we just update the quantity
            if ($existingCartDateItemLink) {
                $quantity = $existingCartDateItemLink->quantity + $quantity;
            }

            $deliveryLink = $product->deliveryLink($node->id, $cartDate->date('Y-m-d'));
            $availableQuantity = $deliveryLink ? $deliveryLink->getAvailableQuantity($variant, $cartQuantity) : 0;

            if ($availableQuantity < $quantity) {
                if ($product->productionType === 'csa') {
                    $errors->add('quantity_changed_no_date', trans('public/product.quantity_changed_no_date'));
                } else {
                    $errors->add('quantity_changed', trans('public/product.quantity_changed', [
                        'date' => $cartDate->date('Y-m-d')
                    ]));
                }

                $quantity = $availableQuantity;
                $cartQuantity += $quantity;
            }

            if ($existingCartDateItemLink) {
                $existingCartDateItemLink->quantity = $quantity;
                $existingCartDateItemLink->save();
            } else {
                $this->createCartDateItemLink($user, $cartDate, $cartItem, $quantity);
            }
        }

        return $errors;
    }

    /**
     * Validate and update CartDateItemLink.
     *
     * @param CartDateItemLink $cartDateItemLink
     * @param Product $product
     * @param Variant $variant
     * @param Node $node
     * @param int $quantity
     */
    private function validateAndUpdateCartDateItemLink($cartDateItemLink, $product, $variant, $node, $quantity)
    {
        $errors = new MessageBag();

        $deliveryLink = $product->deliveryLink($node->id, $cartDateItemLink->getDate()->date('Y-m-d'));
        $availableQuantity = $deliveryLink->getAvailableQuantity($variant);

        if ($availableQuantity < $quantity) {
            if ($product->productionType === 'csa') {
                $errors->add('error', trans('public/product.quantity_changed_no_date'));
            } else {
                $errors->add('error', trans('public/product.quantity_changed', [
                    'date' => $cartDateItemLink->getDate()->date('Y-m-d')
                ]));
            }

            $quantity = $availableQuantity;
        }

        $cartDateItemLink->quantity = $quantity;
        $cartDateItemLink->save();

        return (object) [
            'errors' => $errors->isEmpty() ? false : $errors,
            'cartDateItemLink' => $cartDateItemLink,
        ];
    }

    /**
     * Create new cart item.
     *
     * @param User $user
     * @param Producer $producer
     * @param Product $product
     * @param Node $node
     * @param Variant $variant
     * @param string $message
     * @return CartItem
     */
    private function createCartItem($user, $producer, $product, $node, $variant = null, $message = null) {
        return CartItem::create([
            'user_id' => $user->id,
            'node_id' => $node->id,
            'node' => $node->getInfoForOrder(),
            'producer_id' => $producer->id,
            'producer' => $producer->getInfoForOrder(),
            'product_id' => $product->id,
            'product' => $product->getInfoForOrder(),
            'variant_id' => $variant ? $variant->id : null,
            'variant' => $variant ? $variant->getInfoForOrder() : null,
            'message' => $this->removeEmojis($message),
        ]);
    }

    /**
     * Create new cart date.
     *
     * @param array $deliveryDates
     * @param Collection $existingCartDates
     * @param User $user
     * @return Collection $createdDates
     */
    private function createCartDates($deliveryDates, $existingCartDates, $user)
    {
        $createdDates = new Collection();

        $newDates = collect(array_diff($deliveryDates, $existingCartDates->map(function($cartDate) {
            return $cartDate->date('Y-m-d');
        })->toArray()));

        // Create new dates
        $newDates->each(function($date) use ($user, &$createdDates) {
            $cartDate = CartDate::create([
                'user_id' => $user->id,
                'date' => $date,
            ]);

            $createdDates->push($cartDate);
        });

        return $createdDates;
    }

    /**
     * Create new cart item date link.
     *
     * @param User $user
     * @param CartDate $cartDate
     * @param CartItem $cartItem
     * @param int $quantity
     * @return CartDateItemLink
     */
    private function createCartDateItemLink($user, $cartDate, $cartItem, $quantity)
    {
        return CartDateItemLink::create([
            'user_id' => $user->id,
            'cart_item_id' => $cartItem->id,
            'cart_date_id' => $cartDate->id,
            'quantity' => $quantity,
            'ref' => 'LFN-' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 4)
        ]);
    }

    /**
     * Remove emojis from string
     *
     * @param string $string String with emojis
     * @return string $string String without emojis
     */
    private function removeEmojis($string){
        return preg_replace('/([0-9|#][\x{20E3}])|[\x{00ae}|\x{00a9}|\x{203C}|\x{2047}|\x{2048}|\x{2049}|\x{3030}|\x{303D}|\x{2139}|\x{2122}|\x{3297}|\x{3299}][\x{FE00}-\x{FEFF}]?|[\x{2190}-\x{21FF}][\x{FE00}-\x{FEFF}]?|[\x{2300}-\x{23FF}][\x{FE00}-\x{FEFF}]?|[\x{2460}-\x{24FF}][\x{FE00}-\x{FEFF}]?|[\x{25A0}-\x{25FF}][\x{FE00}-\x{FEFF}]?|[\x{2600}-\x{27BF}][\x{FE00}-\x{FEFF}]?|[\x{2900}-\x{297F}][\x{FE00}-\x{FEFF}]?|[\x{2B00}-\x{2BF0}][\x{FE00}-\x{FEFF}]?|[\x{1F000}-\x{1F6FF}][\x{FE00}-\x{FEFF}]?/u', '', $string);
    }
}
