<?php


class WpStripeCartCart extends WpStripeCart{


	static function display_cart(){
		$html = '';

		$is_charge_shipping = get_option(self::PLUGIN_DB_PREFIX . "charge_shipping",true);

		$cart_template = self::$CART_TEMPLATE ?: WP_STRIPE_CART_PLUGIN_DIR .'template/cart.php';
		if(!file_exists($cart_template)) $cart_template = WP_STRIPE_CART_PLUGIN_DIR .'template/cart.php';
		
		ob_start();
		include $cart_template;
		$html = ob_get_clean();

		//Output cart.
		echo $html;
	}

	static function display_icon(){
		$html = '';

		$cart_template = self::$CART_ICON_TEMPLATE ?: WP_STRIPE_CART_PLUGIN_DIR .'template/cart-icon.php';
		if(!file_exists($cart_template)) $cart_template = WP_STRIPE_CART_PLUGIN_DIR .'template/cart-icon.php';
		
		ob_start();
		include $cart_template;
		$html = ob_get_clean();

		//Output cart.
		echo $html;
	}

	static function display_checkout(){
		$html = '';

		$is_charge_shipping = get_option(self::PLUGIN_DB_PREFIX . "charge_shipping",true);

		//load setting
		$success_header = get_option(self::PLUGIN_DB_PREFIX . "success_header", __('Thanks for your order!', self::PLUGIN_ID));
		$success_message = get_option(self::PLUGIN_DB_PREFIX . "success_message", __('We just sent your receipt to your email address, and your items will be on their way shortly.', self::PLUGIN_ID));
		$error_header = get_option(self::PLUGIN_DB_PREFIX . "error_header", __('Oops, payment failed.', self::PLUGIN_ID));
		$error_message = get_option(self::PLUGIN_DB_PREFIX . "error_message", __('It looks like your order could not be paid at this time. Please try again or try a different card.', self::PLUGIN_ID));

		$cart_template = self::$CHECKOUT_TEMPLATE ?: WP_STRIPE_CART_PLUGIN_DIR .'template/checkout.php';
		if(!file_exists($cart_template)) $cart_template = WP_STRIPE_CART_PLUGIN_DIR .'template/checkout.php';
		
		ob_start();
		include $cart_template;
		$html = ob_get_clean();

		//Output cart.
		echo $html;
	}

	static function load_stripe_js(){

		$environment = get_option(self::PLUGIN_DB_PREFIX . "environment");

		if($environment == 'live'){
			$publishable_key = get_option(self::PLUGIN_DB_PREFIX . "publishable_key");
		}else{
			$publishable_key = get_option(self::PLUGIN_DB_PREFIX . "test_publishable_key");
		}

		$admin_url = 'admin_url';

		$wsc_noimage = plugins_url() .'/wp-stripe-cart/asset/images/noimage.png';

		//Output Stripe JS.
		$stirpe_js = <<<EOL
<script src="https://js.stripe.com/v3/"></script>
<script>
	const stripe = Stripe('{$publishable_key}');
	if(typeof ajaxurl == 'undefined') ajaxurl = '{$admin_url("admin-ajax.php")}';
	const wsc_noimage = '{$wsc_noimage}';
</script>
EOL;
		echo $stirpe_js;
	}

	static function load_stripe_style(){
		$json = '{}';

		$stripe_style_template = self::$STRIPE_STYLE_JSON ?: WP_STRIPE_CART_PLUGIN_DIR .'asset/json/stripe_style.json';
		if(!file_exists($stripe_style_template)) $stripe_style_template = WP_STRIPE_CART_PLUGIN_DIR .'asset/json/stripe_style.json';

		ob_start();
		include $stripe_style_template;
		$json = ob_get_clean();

		//Output json.
		header("Content-Type: application/json; charset=utf-8");
		echo $json;
		exit();
	}

	static function load_form_label(){
		
		$form_label_json = WP_STRIPE_CART_PLUGIN_DIR ."asset/json/form_label.json";

		$json = '{}';
		ob_start();
		include $form_label_json;
		$json = ob_get_clean();

		//Output json.
		header("Content-Type: application/json; charset=utf-8");
		echo $json;
		exit();
	}

	static function load_state(){
		
		$lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'en';
		$country = filter_input(INPUT_POST, 'country', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: '';
		if($country) $country = strtolower($country);
		$state_json = WP_STRIPE_CART_PLUGIN_DIR ."asset/json/state/{$country}-{$lang}.json";
		if(!file_exists($state_json)) $state_json = WP_STRIPE_CART_PLUGIN_DIR ."asset/json/state/{$country}-en.json";

		$json = '{}';
		ob_start();
		include $state_json;
		$json = ob_get_clean();

		//Output json.
		header("Content-Type: application/json; charset=utf-8");
		echo $json;
		exit();
	}

	static function load_country(){
		
		$lang = filter_input(INPUT_POST, 'lang', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 'en';
		$country_json = WP_STRIPE_CART_PLUGIN_DIR ."asset/json/country/{$lang}.json";
		if(!file_exists($country_json)) $country_json = WP_STRIPE_CART_PLUGIN_DIR ."asset/json/country/en.json";

		$json = '{}';
		ob_start();
		include $country_json;
		$json = ob_get_clean();

		//Output json.
		header("Content-Type: application/json; charset=utf-8");
		echo $json;
		exit();
	}

	static function create_payment_intent(){

		$amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_NUMBER_FLOAT) ?: NULL;
		$currency = filter_input(INPUT_POST, 'currency', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
		$payment_description = filter_input(INPUT_POST, 'payment_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
		$receipt_email = filter_input(INPUT_POST, 'receipt_email', FILTER_SANITIZE_EMAIL) ?: NULL;
		$payment_method_types = filter_input(INPUT_POST, 'payment_method_types', FILTER_SANITIZE_FULL_SPECIAL_CHARS,FILTER_REQUIRE_ARRAY) ?: NULL;

		if(!(bool)$amount || !(bool)$currency){
			header('HTTP', true, 403);
			header("Content-Type: application/json; charset=utf-8");
			return json_encode([ 'error' => 'Invalid request.' ]);
			exit();
		}

		$environment = get_option(self::PLUGIN_DB_PREFIX . "environment");

		if($environment == 'live'){
			$secret_key = get_option(self::PLUGIN_DB_PREFIX . "secret_key");
		}else{
			$secret_key = get_option(self::PLUGIN_DB_PREFIX . "test_secret_key");
		}

		$paymentIntent_args = [
			'amount' => $amount,
			'currency' => $currency,
			'description' => $payment_description,
			// 'statement_descriptor' => 'Statement descriptor.',
			'receipt_email' => $receipt_email,
		];

		if((bool)$payment_method_types) $paymentIntent_args['payment_method_types'] = $payment_method_types;

		\Stripe\Stripe::setApiKey($secret_key);

		try {
			$intent = \Stripe\PaymentIntent::create($paymentIntent_args);

			//Output json.
			header("Content-Type: application/json; charset=utf-8");
			echo json_encode(['client_secret' => $intent->client_secret]);
			exit();
		}catch(\Exception $e) {
			header('HTTP', true, 403);
			header("Content-Type: application/json; charset=utf-8");
			return json_encode([ 'error' => $e->getMessage() ]);
			exit();
		}
	}

	static function calculate_cart_item(){

		$cart_item = filter_input(INPUT_POST, 'cart_item', FILTER_DEFAULT) ?: [];
		
		$tax_rate = get_option(self::PLUGIN_DB_PREFIX . "tax_rate");
		$fixed_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "fixed_shipping_fee");

		$calculated = [
			'currency' => 'JPY',
			'subtotal' => 0,
			'tax' => 0,
			'total' => 0
		];

		if((bool)$fixed_shipping_fee){
			$calculated['shipping'] = $fixed_shipping_fee;
		}
		

		if((bool)$cart_item){
			$cart_item = json_decode($cart_item);
			$_SESSION['wsc_cart_item'] = $cart_item;

			foreach($cart_item as $item){
				$calculated['currency'] = $item->currency;
				$calculated['subtotal'] += ($item->price * $item->quantity);
			}
			$calculated['tax'] = self::ceil_tax($calculated['subtotal'] * $tax_rate / 100);
			$calculated['total'] = $calculated['subtotal'] + $calculated['tax'] + $calculated['shipping'];
		}

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($calculated);
		exit();
	}

	static function restore_cart(){

		$cart_item = $_SESSION['wsc_cart_item'] ?: [];


		$tax_rate = get_option(self::PLUGIN_DB_PREFIX . "tax_rate");
		$fixed_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "fixed_shipping_fee");

		$restore_cart = [
			'calculated' => [
				'currency' => 'JPY',
				'subtotal' => 0,
				'tax' => 0,
				'total' => 0
			],
			'cartItem' => $cart_item
		];

		if((bool)$fixed_shipping_fee){
			$restore_cart['calculated']['shipping'] = (float)$fixed_shipping_fee;
		}

		if((bool)$cart_item){

			foreach($cart_item as $item){
				$restore_cart['calculated']['currency'] = $item->currency;
				$restore_cart['calculated']['subtotal'] += ($item->price * $item->quantity);
			}
			$restore_cart['calculated']['tax'] = self::ceil_tax($restore_cart['calculated']['subtotal'] * $tax_rate / 100);
			$restore_cart['calculated']['total'] = $restore_cart['calculated']['subtotal'] + $restore_cart['calculated']['tax'] + $restore_cart['calculated']['shipping'];
		}

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($restore_cart);
		exit();
	}

	static function get_varied_shipping_fee(){

		$cart_item = $_SESSION['wsc_cart_item'] ?: [];

		$tax_rate = get_option(self::PLUGIN_DB_PREFIX . "tax_rate");
		$fixed_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "fixed_shipping_fee");

		//MARK: 国別送料計算 コメントアウト部分を別ロジックへ移動
		$country = filter_input(INPUT_POST, 'country', FILTER_DEFAULT) ?: NULL;
		$state = filter_input(INPUT_POST, 'state', FILTER_DEFAULT) ?: NULL;

		$varied_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "varied_shipping_fee");
		if((bool)$varied_shipping_fee) $varied_shipping_fee = json_decode(str_replace('\"','"', $varied_shipping_fee),true);

		$calculated = [
			'currency' => 'JPY',
			'subtotal' => 0,
			'tax' => 0,
			'total' => 0
		];

		if((bool)$cart_item){

			foreach($cart_item as $item){
				$calculated['currency'] = $item->currency;
				$calculated['subtotal'] += ($item->price * $item->quantity);
			}

			if((bool)$fixed_shipping_fee){
				$calculated['shipping'] = (float)$fixed_shipping_fee;
			}else{

				if(is_numeric($varied_shipping_fee[$country])){
					$calculated['shipping'] = $varied_shipping_fee[$country];
				}else{
					if(isset($varied_shipping_fee[$country][$state])){
						$calculated['shipping'] = $varied_shipping_fee[$country][$state];
					}
				}
			}

			$calculated['tax'] = self::ceil_tax($calculated['subtotal'] * $tax_rate / 100);
			$calculated['total'] = $calculated['subtotal'] + $calculated['tax'] + $calculated['shipping'];
		}

		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($calculated);
		exit();
	}

	static function ceil_tax($value, $decimals = 2) {
		return (float)number_format($value,$decimals,'.','');
	}
}
?>