<div class="wp-stripe-cart-product">
    <?php if($product->images): ?>
        <div class="wp-stripe-cart-product__image">
            <img class="wp-stripe-cart-js-image" src="<?php echo $product->images[0]; ?>" alt="">
        </div>
    <?php endif; ?>
    <div class="wp-stripe-cart-product__info">
        <strong class="wp-stripe-cart-product__name"><?php echo $product->name; ?></strong>
        <p class="wp-stripe-cart-product__desc"><?php echo $product->description; ?></p>
        <p class="wp-stripe-cart-product__price" data-amount="<?php echo $price->unit_amount; ?>" data-currency="<?php echo strtoupper($price->currency); ?>"><?php echo $currency_with_symbol; ?></p>
        <p class="wp-stripe-cart-product__quantity"><input class="wp-stripe-cart-js-quantity" type="number" min="1" value="1"></p>
        <button class="wp-stripe-cart-product__add_to_cart wsc-add-to-cart" data-product-id="<?php echo $product->id; ?>"><?php _e('Add to cart',self::PLUGIN_ID); ?></button>
    </div>
</div>