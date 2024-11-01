<div class="wp-stripe-cart-icon">
    <a href="javascript:void(0);" class="wp-stripe-cart-icon__btn wsc-open-cart">
        <i class="icon-wsc-cart"></i>
        <span class="wp-stripe-cart-icon__bubble empty wsc-cart-bubble"></span>
    </a>
</div>

<div class="wp-stripe-cart-notice wsc-cart-notice">
    <div class="wp-stripe-cart-notice__inner">
        <div class="wp-stripe-cart-notice__thumb wsc-cart-notice-image"></div>
        <div class="wp-stripe-cart-notice__message"><i class="icon-wsc-ok"></i><?php _e('Added to cart.', self::PLUGIN_ID); ?></div>
        <button class="wp-stripe-cart-notice__close wsc-close-notice">&times;</button>
        <button class="wp-stripe-cart-notice__btn_checkout wsc-open-cart"><?php _e('VIEW CART ( <span class="wsc-cart-bubble"></span> Items.)', self::PLUGIN_ID); ?></button>
    </div>
</div>