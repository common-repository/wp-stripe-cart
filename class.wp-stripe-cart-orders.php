<?php
class WpStripeCartOrders extends WpStripeCart{


	static function create_order_posttype(){

		$labels = [
			'search_items' => __('Search orders', self::PLUGIN_ID)
		];
			
		$args = [
			'label'              => _x( 'Orders', 'post type general name', self::PLUGIN_ID ),
			'labels'			 => $labels,
			'public'             => false,
			'publicly_queryable' => false,
			'show_ui'            => false,
			'show_in_menu'       => false,
			'capability_type'    => 'page',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => ['title']
		];
		register_post_type( self::ORDERS_POST_TYPE, $args );
	}

	static function list_orders() {

		$environment = get_option(self::PLUGIN_DB_PREFIX . "environment");
		$test_publishable_key = get_option(self::PLUGIN_DB_PREFIX . "test_publishable_key");
		$test_secret_key = get_option(self::PLUGIN_DB_PREFIX . "test_secret_key");
		$publishable_key = get_option(self::PLUGIN_DB_PREFIX . "publishable_key");
		$secret_key = get_option(self::PLUGIN_DB_PREFIX . "secret_key");

		$search_query = filter_input(INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;

		global $post_type, $post_type_object;

		$post_type  = self::ORDERS_POST_TYPE;
		$post_type_object = get_post_type_object( $post_type );
		
		$OrderTable = new WPStripeCartOrderTable(['screen' => self::ORDERS_POST_TYPE]);

		$pagenum    = $OrderTable->get_pagenum();
		
	
	?>
	<div class="wrap">
		<h1><?php _e('Order - WP Stripe Cart', self::PLUGIN_ID); ?></h1>
		<?php if(!class_exists('WpStripeCartWhiteLabel')): ?>
				<div class="wp-strpe-cart_recommend_addon">
					<h2 class="wp-strpe-cart_recommend_addon__header"><?php _e('Get a WP Stripe Cart White Label Add-on', self::PLUGIN_ID); ?></h2>
					<ul class="wp-strpe-cart_recommend_addon__point">
						<li class="wp-strpe-cart_recommend_addon__point_item"><i class="icon-wsc-ok"></i><strong><?php _e('Enable your own templates', self::PLUGIN_ID); ?></strong>: <?php _e('Fit to your website design!', self::PLUGIN_ID); ?></li>
						<li class="wp-strpe-cart_recommend_addon__point_item"><i class="icon-wsc-ok"></i><strong><?php _e('Remove "Powered by" attribute', self::PLUGIN_ID); ?></strong>: <?php _e("So doesn't harm your brand!", self::PLUGIN_ID); ?></li>
					</ul>
					<p><a class="wp-strpe-cart_recommend_addon__button" href="<?php _e('https://wp-stripe-cart.metrocode.co/en/', self::PLUGIN_ID); ?>" target="_blank"><?php _e('Get WP Stripe Cart White Label Add-on Now', self::PLUGIN_ID); ?></a></p>
				</div>
			<?php endif; ?>
		<?php	
		$OrderTable->prepare_items();

		get_current_screen()->set_screen_reader_content(
			array(
				'heading_views'      => $post_type_object->labels->filter_items_list,
				'heading_pagination' => $post_type_object->labels->items_list_navigation,
				'heading_list'       => $post_type_object->labels->items_list,
			)
		);
		
		add_screen_option(
			'per_page',
			array(
				'default' => 20,
				'option'  => 'edit_' . $post_type . '_per_page',
			)
		);

		if ((bool)$search_query) {
			/* translators: %s: Search query. */
			printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>', $search_query );
		}
		?>
		<?php $OrderTable->views(); ?>
		<form id="posts-filter" method="get">

			<?php $OrderTable->search_box( $post_type_object->labels->search_items, self::ORDERS_POST_TYPE ); ?>

			<input type="hidden" name="page" value="<?php echo self::PLUGIN_ID; ?>" />
			<input type="hidden" name="post_status" class="post_status_page" value="<?php echo ! empty( $_REQUEST['post_status'] ) ? esc_attr( $_REQUEST['post_status'] ) : 'all'; ?>" />
			<input type="hidden" name="post_type" class="post_type_page" value="<?php echo $post_type; ?>" />
			<?php $OrderTable->display(); ?>
		</form>
	</div>
	<?php
	}

	static function save_order(){
		$paymentIntent = filter_input(INPUT_POST, 'paymentIntent', FILTER_DEFAULT) ?: NULL;
		$order = filter_input(INPUT_POST, 'order', FILTER_DEFAULT) ?: NULL;
		$order_summary = filter_input(INPUT_POST, 'order_summary', FILTER_DEFAULT) ?: NULL;

		if(!(bool)$paymentIntent){
			header('HTTP', true, 403);
			header("Content-Type: application/json; charset=utf-8");
			return json_encode([ 'error' => 'Invalid request.' ]);
			exit();
		}

		$paymentIntent = json_decode($paymentIntent);
		$order = json_decode($order);
		$order_summary = json_decode($order_summary);

		$default_timezone = date_default_timezone_get();
		date_default_timezone_set( wp_timezone_string() );

		// 投稿オブジェクトを作成
		$order_data = [
			'post_title'    => $paymentIntent->id,
			'post_type'		=> self::ORDERS_POST_TYPE,
			'post_date'		=> date('Y-m-d H:i:s',$paymentIntent->created),
			'post_date_gmt' => gmdate('Y-m-d H:i:s',$paymentIntent->created),
			'post_status'   => 'publish',
			'post_author'   => 1,
			'meta_input' => [
				'wsc_order_name' => $paymentIntent->shipping->name,
				'wsc_order_amount' => $paymentIntent->amount,
				'wsc_order_currency' => $paymentIntent->currency,
				'wsc_order_shipping' => $paymentIntent->shipping,
				'wsc_order_live' => $paymentIntent->livemode,
				'wsc_order_email' => $paymentIntent->receipt_email,
				'wsc_order_detail' => $order,
				'wsc_order_summary' => $order_summary,
			]
		];

		date_default_timezone_set( $default_timezone );
		
		// Save Order
		$order = wp_insert_post( $order_data );

		if((bool)$order){
			$json = '{"status":"SUCCESS"}';
		}else{
			$json = '{"status":"FAILED"}';
		}

		header("Content-Type: application/json; charset=utf-8");
		echo $json;
		exit();
	}

	static function alter_search_query($q) {
		if($title = $q->get('_meta_or_title')){
			add_filter( 'get_meta_sql', function( $sql ) use ( $title ) {
				global $wpdb;

				// Only run once:
				static $nr = 0; 
				if( 0 != $nr++ ) return $sql;

				// Modified WHERE
				$sql['where'] = sprintf(
					" AND ( %s OR %s ) ",
					$wpdb->prepare( "{$wpdb->posts}.post_title like '%%%s%%'", $title),
					mb_substr( $sql['where'], 5, mb_strlen( $sql['where'] ) )
				);

				return $sql;
			});
		}
	}
}
?>