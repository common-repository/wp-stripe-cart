<?php
use Symfony\Component\Intl;

class WPStripeCartOrderTable extends WP_List_Table{
	
	public function __construct(array $args){
		parent::__construct($args);
	}
	
    public function prepare_items(){
		global $avail_post_stati, $wp_query, $per_page, $mode;

		$search_query = filter_input(INPUT_GET, 's', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
		$paged = filter_input(INPUT_GET, 'paged', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: 1;

		$post_type = $this->screen->post_type;
		$per_page  = $this->get_items_per_page( 'edit_' . $post_type . '_per_page' );

		/** This filter is documented in wp-admin/includes/post.php */
		$per_page = apply_filters( 'edit_posts_per_page', $per_page, $post_type );

		$wp_query = new WP_Query([
			'post_type' => WpStripeCart::ORDERS_POST_TYPE,
			'_meta_or_title' => $search_query,
			'posts_per_page' => $per_page,
			'paged' => $paged,
			'meta_query' => [
				'relation' => 'OR', 
				[
					'key' => 'wsc_order_name',
					'value' => $search_query,
					'type' => 'CHAR',
					'compare' => 'LIKE'
				],
				[
					'key' => 'wsc_order_amount',
					'value' => $search_query,
					'type' => 'CHAR',
					'compare' => 'LIKE'
				],
				[
					'key' => 'wsc_order_email',
					'value' => $search_query,
					'type' => 'CHAR',
					'compare' => 'LIKE'
				],
			]
		]);
		
		// Is going to call wp().
		$avail_post_stati = wp_edit_posts_query();

		$this->set_hierarchical_display( is_post_type_hierarchical( $this->screen->post_type ) && 'menu_order title' === $wp_query->query['orderby'] );

		if ( $this->hierarchical_display ) {
			$total_items = $wp_query->post_count;
		} elseif ( $wp_query->found_posts || $this->get_pagenum() === 1 ) {
			$total_items = $wp_query->found_posts;
		} else {
			$post_counts = (array) wp_count_posts( $post_type, 'readable' );

			if ( isset( $_REQUEST['post_status'] ) && in_array( $_REQUEST['post_status'], $avail_post_stati ) ) {
				$total_items = $post_counts[ $_REQUEST['post_status'] ];
			} elseif ( isset( $_REQUEST['show_sticky'] ) && $_REQUEST['show_sticky'] ) {
				$total_items = $this->sticky_posts_count;
			} elseif ( isset( $_GET['author'] ) && get_current_user_id() == $_GET['author'] ) {
				$total_items = $this->user_posts_count;
			} else {
				$total_items = array_sum( $post_counts );

				// Subtract post types that are not included in the admin all list.
				foreach ( get_post_stati( [ 'show_in_admin_all_list' => false ] ) as $state ) {
					$total_items -= $post_counts[ $state ];
				}
			}
		}

		if ( ! empty( $_REQUEST['mode'] ) ) {
			$mode = 'excerpt' === $_REQUEST['mode'] ? 'excerpt' : 'list';
			set_user_setting( 'posts_list_mode', $mode );
		} else {
			$mode = get_user_setting( 'posts_list_mode', 'list' );
		}

		$this->is_trash = isset( $_REQUEST['post_status'] ) && 'trash' === $_REQUEST['post_status'];

		$this->set_pagination_args(
			[
				'total_items' => $total_items,
				'per_page'    => $per_page,
			]
		);
    }

    public function get_columns() {
        return [
            'cb' => '<input type="checkbox" />',
            'id' => 'STRIPE Payment ID',
            'customer_name' => _x( 'Customer Name', 'column name', WpStripeCart::PLUGIN_ID ),
            'customer_email' => _x( 'Customer Email', 'column name', WpStripeCart::PLUGIN_ID ),
            'price' => _x( 'Total Amount', 'column name', WpStripeCart::PLUGIN_ID ),
            'order_date' => _x( 'Order Date', 'column name', WpStripeCart::PLUGIN_ID )
        ];
    }
	
	/**
	 * @return bool
	 */
	public function has_items() {
		global $wp_query;
		return $wp_query->have_posts();
	}

	/**
	 * Displays the table.
	 *
	 * @since 3.1.0
	 */
	public function display() {
		$singular = $this->_args['singular'];

		$this->display_tablenav( 'top' );

		$this->screen->render_screen_reader_content( 'heading_list' );
		?>
<table class="wp-list-table wsc-list-table <?php echo implode( ' ', $this->get_table_classes() ); ?>">
	<thead>
	<tr>
		<?php $this->print_column_headers(); ?>
	</tr>
	</thead>

	<tbody id="the-list"
		<?php
		if ( $singular ) {
			echo " data-wp-lists='list:$singular'";
		}
		?>
		>
		<?php $this->display_rows_or_placeholder(); ?>
	</tbody>

	<tfoot>
	<tr>
		<?php $this->print_column_headers( false ); ?>
	</tr>
	</tfoot>

</table>
		<?php
		$this->display_tablenav( 'bottom' );
	}

	/**
	 * @global WP_Query $wp_query WordPress Query object.
	 * @global int $per_page
	 * @param array $posts
	 * @param int $level
	 */
	public function display_rows( $posts = array(), $level = 0 ) {
		global $wp_query, $per_page;

		if ( empty( $posts ) ) {
			$posts = $wp_query->posts;
		}

		add_filter( 'the_title', 'esc_html' );

		if ( $this->hierarchical_display ) {
			$this->_display_rows_hierarchical( $posts, $this->get_pagenum(), $per_page );
		} else {
			$this->_display_rows( $posts, $level );
		}
	}

	/**
	 * @param array $posts
	 * @param int $level
	 */
	private function _display_rows( $posts, $level = 0 ) {
		$post_type = $this->screen->post_type;

		// Create array of post IDs.
		$post_ids = array();

		foreach ( $posts as $a_post ) {
			$post_ids[] = $a_post->ID;
		}

		foreach ( $posts as $post ) {
			$this->single_row( $post, $level );
		}
	}

	/**
	 * @global WP_Post $post Global post object.
	 *
	 * @param int|WP_Post $post
	 * @param int         $level
	 */
	public function single_row( $post, $level = 0 ) {
		$global_post = get_post();

		$post                = get_post( $post );
		$this->current_level = $level;

		$GLOBALS['post'] = $post;
		setup_postdata( $post );

		$classes = 'iedit author-' . ( get_current_user_id() == $post->post_author ? 'self' : 'other' );

		$lock_holder = wp_check_post_lock( $post->ID );
		if ( $lock_holder ) {
			$classes .= ' wp-locked';
		}

		if ( $post->post_parent ) {
			$count    = count( get_post_ancestors( $post->ID ) );
			$classes .= ' level-' . $count;
		} else {
			$classes .= ' level-0';
		}
		?>
		<tr id="post-<?php echo $post->ID; ?>" class="<?php echo implode( ' ', get_post_class( $classes, $post->ID ) ); ?>">
			<?php $this->single_row_columns( $post ); ?>
		</tr>
		<tr id="post-<?php echo $post->ID; ?>-order-detail" class="type-wsc_orders_detail">
			<td colspan="6" class="type-wsc_orders_detail__td">
				<div class="type-wsc_orders_detail__animate">
					<div class="type-wsc_orders_detail__container">
						<div class="type-wsc_orders_detail__items">
							<?php
							//Order detail
							$order_details = get_post_meta($post->ID,'wsc_order_detail',true);

							//Order summary
							$NumberFormatter = new NumberFormatter('en',2);
							$wsc_order_shipping = get_post_meta($post->ID,'wsc_order_shipping',true);
							$wsc_order_summary = get_post_meta($post->ID,'wsc_order_summary',true);
							if(!isset($wsc_order_summary->shipping) && is_object($wsc_order_summary)) $wsc_order_summary->shipping = 0;

							$order_shipping = isset($wsc_order_summary->shipping) ? $NumberFormatter->formatCurrency($wsc_order_summary->shipping,strtoupper($wsc_order_summary->currency)) : 0;
							$order_subtotal = isset($wsc_order_summary->subtotal) ? $NumberFormatter->formatCurrency($wsc_order_summary->subtotal,strtoupper($wsc_order_summary->currency)) : 0;
							$order_tax = isset($wsc_order_summary->tax) ? $NumberFormatter->formatCurrency($wsc_order_summary->tax,strtoupper($wsc_order_summary->currency)) : 0;
							$order_total = isset($wsc_order_summary->total) ? $NumberFormatter->formatCurrency($wsc_order_summary->total,strtoupper($wsc_order_summary->currency)) : 0;
							?>
							<h2 class="type-wsc_orders_detail__header"><?php _e('Order detail', WpStripeCart::PLUGIN_ID); ?></h2>
							<ul class="type-wsc_orders_detail__itemlist">
								<?php if((bool)$order_details): foreach($order_details as $detail): ?>
									<li class="type-wsc_orders_detail__item">
										<div class="type-wsc_orders_detail__item_thumb"><img src="<?php echo $detail->image; ?>" alt=""></div>
										<strong class="type-wsc_orders_detail__item_name"><?php echo $detail->name; ?></strong>
										
										<p class="type-wsc_orders_detail__item_price"><?php _e('Unit price', WpStripeCart::PLUGIN_ID); ?>: <?php echo $NumberFormatter->formatCurrency($detail->price,strtoupper($detail->currency)); ?></p>
										<p class="type-wsc_orders_detail__item_quantity"><?php _e('Quantity', WpStripeCart::PLUGIN_ID); ?>: <?php echo $detail->quantity; ?></p>
									</li>
								<?php endforeach; endif; ?>
							</ul>
						</div>
						<div class="type-wsc_orders_detail__summary">
							<h2 class="type-wsc_orders_detail__header"><?php _e('Shipping address', WpStripeCart::PLUGIN_ID); ?></h2>
							<div class="type-wsc_orders_detail__shipping <?php echo $wsc_order_shipping->address->country;?>">
								<p class="type-wsc_orders_detail__shipping_line1"><?php echo $wsc_order_shipping->address->line1;?></p>
								<p class="type-wsc_orders_detail__shipping_line2"><?php echo $wsc_order_shipping->address->line2;?></p>
								<p class="type-wsc_orders_detail__shipping_city"><?php echo $wsc_order_shipping->address->city;?></p>
								<p class="type-wsc_orders_detail__shipping_state"><?php echo $wsc_order_shipping->address->state;?></p>
								<p class="type-wsc_orders_detail__shipping_postalcode"><?php echo $wsc_order_shipping->address->postal_code;?></p>
								<p class="type-wsc_orders_detail__shipping_country"><?php echo $wsc_order_shipping->address->country;?></p>
							</div>
							<h2 class="type-wsc_orders_detail__header"><?php _e('Payment info', WpStripeCart::PLUGIN_ID); ?></h2>
							<div class="type-wsc_orders_detail__summary">
								<p class="type-wsc_orders_detail__summary_subtotal"><span class="type-wsc_orders_detail__summary_label"><?php _e('Subtotal', WpStripeCart::PLUGIN_ID); ?></span><?php echo $order_subtotal; ?></p>
								<p class="type-wsc_orders_detail__summary_tax"><span class="type-wsc_orders_detail__summary_label"><?php _e('Tax', WpStripeCart::PLUGIN_ID); ?></span><?php echo $order_tax; ?></p>
								<p class="type-wsc_orders_detail__summary_shipping"><span class="type-wsc_orders_detail__summary_label"><?php _e('Shipping', WpStripeCart::PLUGIN_ID); ?></span><?php echo $order_shipping; ?></p>
								<p class="type-wsc_orders_detail__summary_total"><span class="type-wsc_orders_detail__summary_label"><?php _e('Total', WpStripeCart::PLUGIN_ID); ?></span><?php echo $order_total; ?></p>
							</div>
						</div>
					</div>
				</div>
			</td>
		</tr>
		<?php
		$GLOBALS['post'] = $global_post;
	}

	/**
	 * Generates the columns for a single row of the table
	 *
	 * @since 3.1.0
	 *
	 * @param object $item The current item
	 */
	protected function single_row_columns( $item ) {
		list( $columns, $hidden, $sortable, $primary ) = $this->get_column_info();

		foreach ( $columns as $column_name => $column_display_name ) {
			$classes = "$column_name column-$column_name";
			if ( $primary === $column_name ) {
				$classes .= ' has-row-actions column-primary';
			}

			if ( in_array( $column_name, $hidden ) ) {
				$classes .= ' hidden';
			}

			// Comments column uses HTML in the display name with screen reader text.
			// Instead of using esc_attr(), we strip tags to get closer to a user-friendly string.
			$data = 'data-colname="' . wp_strip_all_tags( $column_display_name ) . '"';

			$attributes = "class='$classes' $data";
			
			if ( 'cb' === $column_name ) {
				echo '<th scope="row" class="check-column">';
				echo $this->column_cb( $item );
				echo '</th>';
			} elseif ( method_exists( $this, '_column_' . $column_name ) ) {
				echo call_user_func(
					[ $this, '_column_' . $column_name ],
					$item,
					$classes,
					$data,
					$primary
				);
			} else {
				echo "<td $attributes>";
				echo $this->column_default( $item, $column_name );
				echo $this->handle_row_actions( $item, $column_name, $primary );
				echo '</td>';
			}
		}
	}

	/**
	 * Handles the checkbox column output.
	 *
	 * @since 4.3.0
	 *
	 * @param WP_Post $post The current WP_Post object.
	 */
	public function column_cb( $post ) {
		if ( current_user_can( 'edit_post', $post->ID ) ) :
			?>
			<label class="screen-reader-text" for="cb-select-<?php the_ID(); ?>">
				<?php
					/* translators: %s: Post title. */
					printf( __( 'Select %s' ), _draft_or_post_title() );
				?>
			</label>
			<input id="cb-select-<?php the_ID(); ?>" type="checkbox" name="post[]" value="<?php the_ID(); ?>" />
			<div class="locked-indicator">
				<span class="locked-indicator-icon" aria-hidden="true"></span>
				<span class="screen-reader-text">
				<?php
				printf(
					/* translators: %s: Post title. */
					__( '&#8220;%s&#8221; is locked' ),
					_draft_or_post_title()
				);
				?>
				</span>
			</div>
			<?php
		endif;
	}

	/**
	 * @since 4.3.0
	 *
	 * @param WP_Post $post
	 * @param string  $classes
	 * @param string  $data
	 * @param string  $primary
	 */
	protected function _column_id( $post, $classes, $data, $primary ) {
		echo '<td class="' . $classes . ' page-title" ', $data, '>';
		echo $post->post_title;
		echo $this->handle_row_actions( $post, 'id', $primary );
		echo '</td>';
	}

	/**
	 * @since 4.3.0
	 *
	 * @param WP_Post $post
	 * @param string  $classes
	 * @param string  $data
	 * @param string  $primary
	 */
	protected function _column_customer_name( $post, $classes, $data, $primary ) {
		$wsc_order_name = get_post_meta($post->ID,'wsc_order_name',true);
		echo '<td class="' . $classes . ' customer_name" ', $data, '>';
		echo $wsc_order_name;
		echo '</td>';
	}

	/**
	 * @since 4.3.0
	 *
	 * @param WP_Post $post
	 * @param string  $classes
	 * @param string  $data
	 * @param string  $primary
	 */
	protected function _column_customer_email( $post, $classes, $data, $primary ) {
		$wsc_order_email = get_post_meta($post->ID,'wsc_order_email',true);
		echo '<td class="' . $classes . ' customer_email" ', $data, '>';
		echo $wsc_order_email;
		echo '</td>';
	}

	/**
	 * @since 4.3.0
	 *
	 * @param WP_Post $post
	 * @param string  $classes
	 * @param string  $data
	 * @param string  $primary
	 */
	protected function _column_price( $post, $classes, $data, $primary ) {
		$NumberFormatter = new NumberFormatter('en',2);
		$wsc_order_amount = get_post_meta($post->ID,'wsc_order_amount',true);
		$wsc_order_currency = get_post_meta($post->ID,'wsc_order_currency',true);
		$currency_with_symbol = $NumberFormatter->formatCurrency($wsc_order_amount,strtoupper($wsc_order_currency));
		echo '<td class="' . $classes . ' ordered_price" ', $data, '>';
		echo $currency_with_symbol;
		echo '</td>';
	}

	/**
	 * @since 4.3.0
	 *
	 * @param WP_Post $post
	 * @param string  $classes
	 * @param string  $data
	 * @param string  $primary
	 */
	protected function _column_order_date( $post, $classes, $data, $primary ) {
		$wsc_order_date = get_the_time('Y-m-d H:i:s', $post->ID);
		echo '<td class="' . $classes . ' ordered_date" ', $data, '>';
		echo $wsc_order_date;
		echo '</td>';
	}

	/**
	 * Generates and displays row action links.
	 *
	 * @since 4.3.0
	 *
	 * @param object $post        Post being acted upon.
	 * @param string $column_name Current column name.
	 * @param string $primary     Primary column name.
	 * @return string Row actions output for posts, or an empty string
	 *                if the current column is not the primary column.
	 */
	protected function handle_row_actions( $post, $column_name, $primary ) {
		if ( $primary !== $column_name ) {
			return '';
		}

		$actions          = [];
		$stripe_live_mode = get_post_meta($post->ID, 'wsc_order_live', true);
		$stripe_paymentIntent = get_the_title($post->ID);
		$stripe_test_url = $stripe_live_mode ? '' : 'test/';
		$stripe_paymentIntent_url = "https://dashboard.stripe.com/{$stripe_test_url}payments/{$stripe_paymentIntent}";

		$actions['detail'] = sprintf(
			'<a href="javascript:void(0);" class="wsc-order-list-view-detail">%s</a>',
			__( 'View/Hide Order Detail', WpStripeCart::PLUGIN_ID )
		);

		$actions['stripe_paymentIntent'] = sprintf(
			'<a href="%s" target="_blank" rel="noopener">%s</a>',
			$stripe_paymentIntent_url,
			__( 'Stripe Payment Detail', WpStripeCart::PLUGIN_ID )
		);

		return $this->row_actions( $actions );
	}

	/**
	 * Get a list of CSS classes for the WP_List_Table table tag.
	 *
	 * @since 3.1.0
	 *
	 * @return string[] Array of CSS classes for the table tag.
	 */
	protected function get_table_classes() {
		return array( 'widefat', 'striped', $this->_args['plural'] );
	}

}