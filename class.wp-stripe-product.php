<?php


class WpStripeCartProduct extends WpStripeCart{


	static function set_product_shortcode($atts = []){
		extract(shortcode_atts(['product_id' => NULL], $atts));

		if(!(bool)$product_id) return NULL;

		$environment = get_option(self::PLUGIN_DB_PREFIX . "environment");

		if($environment == 'live'){
			$secret_key = get_option(self::PLUGIN_DB_PREFIX . "secret_key");
		}else{
			$secret_key = get_option(self::PLUGIN_DB_PREFIX . "test_secret_key");
		}

		$stripe = new \Stripe\StripeClient(
			$secret_key
		);

		try{
			$product = $stripe->products->retrieve(
				$product_id,
				[]
			);
		}catch( Exception $e ){
			return $e->getMessage();
		}

		try{
			$price = $stripe->prices->all(['product' => $product_id, 'active' => true]);
		}catch( Exception $e ){
			return $e->getMessage();
		}

		if((bool)$price){
			$price = $price->data[0];
		}

		$NumberFormatter = new NumberFormatter('en',2);
		$currency_with_symbol = $NumberFormatter->formatCurrency($price->unit_amount,strtoupper($price->currency));

		$product_html = '';

		//If no image is present, set noimage.png
		if((bool)$product->images === false){
			$product->images[0] = plugins_url() .'/'. self::PLUGIN_ID .'/asset/images/noimage.png';
		}

		$product_template = self::$PRODUCT_TEMPLATE ?: WP_STRIPE_CART_PLUGIN_DIR .'template/product.php';
		if(!file_exists($product_template)) $product_template = WP_STRIPE_CART_PLUGIN_DIR .'template/product.php';
		
		ob_start();
		include $product_template;
		$product_html = ob_get_clean();
		return $product_html;
	}

}
?>