<div class="wp-stripe-cart-cart">
    <div class="wp-stripe-cart-cart__inner">
        <h2 class="wp-stripe-cart-cart__header"><?php _e('SHOPPING CART', self::PLUGIN_ID); ?></h2>
        <a href="javascript:void(0);" class="wp-stripe-cart-cart__close wsc-cart-close">&times;</a>
        <div class="wp-stripe-cart-cart__ajax_products">
            <p class="wp-stripe-cart-cart__empty wp-stripe-cart-js-empty"><?php _e('Cart is empty', self::PLUGIN_ID); ?></p>
            <ul class="wp-stripe-cart-cart__products wsc-cart-items" body-scroll-lock-ignore></ul>
        </div>
        <div class="wp-stripe-cart-cart-footer">
            <div class="wp-stripe-cart-cart-footer__inner">
                <p class="wp-stripe-cart-cart-footer__subtotal">
                    <span class="wp-stripe-cart-cart-footer__subtotal_label"><?php _e('Subtotal', self::PLUGIN_ID); ?></span>
                    <span class="wp-stripe-cart-cart-footer__subtotal_amount wsc-subtotal-amount">¥0</span>
                </p>
                <p class="wp-stripe-cart-cart-footer__tax">
                    <span class="wp-stripe-cart-cart-footer__tax_label"><?php _e('Tax', self::PLUGIN_ID); ?></span>
                    <span class="wp-stripe-cart-cart-footer__tax_amount wsc-tax-amount">¥0</span>
                </p>
                <?php if((bool)$is_charge_shipping): ?>
                    <p class="wp-stripe-cart-cart-footer__shipping">
                        <span class="wp-stripe-cart-cart-footer__shipping_label"><?php _e('Shipping', self::PLUGIN_ID); ?></span>
                        <span class="wp-stripe-cart-cart-footer__shipping_amount wsc-shipping-amount"><?php _e('To be calculated', self::PLUGIN_ID); ?></span>
                    </p>
                <?php endif; ?>
                <p class="wp-stripe-cart-cart-footer__total">
                    <span class="wp-stripe-cart-cart-footer__total_label"><?php _e('Total amount to pay', self::PLUGIN_ID); ?></span>
                    <span class="wp-stripe-cart-cart-footer__total_amount wsc-total-amount">¥0</span>
                </p>
                <button class="wp-stripe-cart-cart-footer__btn_checkout wsc-stripe-checkout"><?php _e('CHECKOUT', self::PLUGIN_ID); ?></button>
                <em class="wp-stripe-cart-cart__powered_by">Powered by <a href="https://wp-stripe-cart.metrocode.co/" target="_blank" rel="noopener">WP Stripe Cart</a>.</em>
            </div>
        </div>
    </div>
    <template id="wsc-cart-item-template">
        <li class="wp-stripe-cart-cart-item wsc-cart-item">
            <div class="wp-stripe-cart-cart-item__image">
                <img class="wsc-cart-item-image" src="" alt="">
            </div>
            <div class="wp-stripe-cart-cart-item__texts">
                <div class="wp-stripe-cart-cart-item__texts_left">
                    <div class="wp-stripe-cart-cart-item__name wsc-cart-item-name"></div>
                    <div class="wp-stripe-cart-cart-item__quantity">
                        <?php _e('Quantity', self::PLUGIN_ID); ?>&nbsp;<input class="wp-stripe-cart-cart-item__quantity_input wsc-quantity-input" type="number" step="1" min="1" value=""><button class="wp-stripe-cart-cart-item__update wsc-cart-update"><?php _e('Update', self::PLUGIN_ID); ?></button>
                    </div>
                </div>
                <div class="wp-stripe-cart-cart-item__texts_right">
                    <div class="wp-stripe-cart-cart-item__price wsc-cart-item-price"></div>
                    <button class="wp-stripe-cart-cart-item__remove wsc-remove-item"><?php _e('Remove', self::PLUGIN_ID); ?></button>
                </div>
            </div>
        </li>
    </template>
</div>

