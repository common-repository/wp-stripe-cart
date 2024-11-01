<div class="wp-stripe-cart-loaderbox">
	<div class="wp-stripe-cart-loaderbox__loader">Loading...</div>
</div>
<div class="wp-stripe-cart-checkout">
	<div class="wp-stripe-cart-checkout__inner">
		<a href="javascript:void(0);" class="wp-stripe-cart-checkout__back"><i class="icon-wsc-angle-down"></i><?php _e('Back to cart', self::PLUGIN_ID); ?></a>
		<h2 class="wp-stripe-cart-cart__header"><?php _e('CHECKOUT', self::PLUGIN_ID); ?></h2>
		<div id="checkout">
			<form id="wp-stripe-cart-payment-form" name="stripe_payment_form" method="POST" action="">
				<p class="instruction"><?php _e('Please complete your shipping and payment details below.', self::PLUGIN_ID); ?></p>
				<section>
					<h2><?php _e('Shipping &amp; Billing Information', self::PLUGIN_ID); ?></h2>
					<fieldset class="wp-stripe-cart-fieldset wsc-cart-fieldset">
						<label class="select country">
							<span class="wsc-label-country"></span>
							<div id="country" class="field">
								<select class="wp-stripe-cart-country wsc-set-country" name="country" required></select>
							</div>
						</label>
						<label class="name">
							<span class="wsc-label-name"></span>
							<input name="name" class="field" placeholder="Jenny Rosen" required>
						</label>
						<label class="email">
							<span class="wsc-label-email"></span>
							<input name="email" type="email" class="field" placeholder="jenny@example.com" required>
						</label>
						<label class="address1">
							<span class="wsc-label-line1"></span>
							<input name="address1" class="field" placeholder="185 Berry Street" required>
						</label>
						<label class="address2">
							<span class="wsc-label-line2"></span>
							<input name="address2" class="field" placeholder="Suite 550">
						</label>
						<label class="city">
							<span class="wsc-label-city"></span>
							<input name="city" class="field" placeholder="San Francisco" required>
						</label>
						<label class="state wsc-load-state">
							<span class="wsc-label-state"></span>
							<input name="state" class="field" placeholder="CA" required>
						</label>
						<label class="zip">
							<span class="wsc-label-zip"></span>
							<input name="postal_code" class="field" placeholder="94107" required>
						</label>
					</fieldset>
				</section>
				<section class="stripe_payment_form_footer">
					<h2><?php _e('Order Summary', self::PLUGIN_ID); ?></h2>
					<p class="stripe_payment_form_footer__subtotal">
						<span class="stripe_payment_form_footer__subtotal_label"><?php _e('Subtotal', self::PLUGIN_ID); ?></span>
						<span class="stripe_payment_form_footer__subtotal_amount wsc-subtotal-amount">¥0</span>
					</p>
					<p class="stripe_payment_form_footer__tax">
						<span class="stripe_payment_form_footer__tax_label"><?php _e('Tax', self::PLUGIN_ID); ?></span>
						<span class="stripe_payment_form_footer__tax_amount wsc-tax-amount">¥0</span>
					</p>
					<?php if((bool)$is_charge_shipping): ?>
						<p class="stripe_payment_form_footer__shipping">
							<span class="stripe_payment_form_footer__shipping_label"><?php _e('Shipping', self::PLUGIN_ID); ?></span>
							<span class="stripe_payment_form_footer__shipping_amount wsc-shipping-amount"><?php _e('To be calculated', self::PLUGIN_ID); ?></span>
						</p>
					<?php endif; ?>
					<p class="stripe_payment_form_footer__total">
						<span class="stripe_payment_form_footer__total_label"><?php _e('Total amount to pay', self::PLUGIN_ID); ?></span>
						<span class="stripe_payment_form_footer__total_amount wsc-total-amount">¥0</span>
					</p>
				</section>
				<section>
					<h2><?php _e('Payment Information', self::PLUGIN_ID); ?></h2>
					<div id="payment-request">
						<div id="payment-request-button"></div>
						<div class="payment-request-or">
							<hr>
							<p class="payment-request-or__label"><?php _e('OR', self::PLUGIN_ID); ?></p>
						</div>
					</div>
					<nav id="wp-stripe-cart-payment-methods">
						<ul>
							<li>
								<input type="radio" name="payment" id="payment-card" value="card" checked>
								<label for="payment-card">Card</label>
							</li>
						</ul>
					</nav>
					<div class="payment-info card visible">
						<fieldset>
							<label>
								<span>Card</span>
								<div id="card-element" class="field"></div>
							</label>
						</fieldset>
					</div>
				</section>
				<button class="wp-stripe-cart-payment-button" type="submit" data-processing="<?php _e('Processing…',self::PLUGIN_ID); ?>"><?php _e('Pay',self::PLUGIN_ID); ?></button>
			</form>
			<div id="card-errors" class="element-errors"></div>
		</div>
		<div id="confirmation" class="confirmation">
			<div class="confirmation__inner">
				<div class="status success">
					<h1><?php echo esc_html($success_header); ?></h1>
					<p class="note"><?php echo nl2br(esc_html($success_message)); ?></p>
				</div>
				<div class="status error">
					<h1><?php echo esc_html($error_header); ?></h1>
					<p><?php echo nl2br(esc_html($error_message)); ?></p>
					<p class="error-message"></p>
				</div>

				<button class="wp-stripe-cart-checkout__done" type="button"><?php _e('CLOSE',self::PLUGIN_ID); ?></button>
			</div>
		</div>
		<em class="wp-stripe-cart-cart__powered_by">Powered by <a href="https://wp-stripe-cart.metrocode.co/" target="_blank" rel="noopener">WP Stripe Cart</a>.</em>
	</div>
</div>