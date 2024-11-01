<?php
class WpStripeCartSetting extends WpStripeCart{

	static $error_message = [];
	static $message = [];

	static function show_about_plugin() {
		$environment = get_option(self::PLUGIN_DB_PREFIX . "environment");
		$test_publishable_key = get_option(self::PLUGIN_DB_PREFIX . "test_publishable_key");
		$test_secret_key = get_option(self::PLUGIN_DB_PREFIX . "test_secret_key");
		$publishable_key = get_option(self::PLUGIN_DB_PREFIX . "publishable_key");
		$secret_key = get_option(self::PLUGIN_DB_PREFIX . "secret_key");
		$filter_countries = get_option(self::PLUGIN_DB_PREFIX . "filter_countries");
		$default_country = get_option(self::PLUGIN_DB_PREFIX . "default_country");
		$payment_description = get_option(self::PLUGIN_DB_PREFIX . "payment_description");
		$tax_rate = get_option(self::PLUGIN_DB_PREFIX . "tax_rate");
		$charge_shipping = get_option(self::PLUGIN_DB_PREFIX . "charge_shipping");
		$fixed_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "fixed_shipping_fee");
		$varied_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "varied_shipping_fee");
		$success_header = get_option(self::PLUGIN_DB_PREFIX . "success_header");
		$success_message = get_option(self::PLUGIN_DB_PREFIX . "success_message");
		$error_header = get_option(self::PLUGIN_DB_PREFIX . "error_header");
		$error_message = get_option(self::PLUGIN_DB_PREFIX . "error_message");
		?>
		<div class="wrap">
			<h1><?php _e('Setting - WP Stripe Cart', self::PLUGIN_ID); ?></h1>

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
			<?php if((bool)self::$error_message): ?>
				<div class="error notice">
					<p class="wp-strpe-cart_error_text"><?php echo nl2br(implode("\n",self::$error_message)); ?></p>
				</div>
			<?php endif; ?>
			<?php if((bool)self::$message): ?>
				<div class="updated notice">
					<p class="wp-strpe-cart_success_text"><?php echo nl2br(implode("\n",self::$message)); ?></p>
				</div>
			<?php endif; ?>
			<form action="" method='post' id="wp-strpe-cart-setting-form">
				<?php wp_nonce_field(self::CREDENTIAL_ACTION, self::CREDENTIAL_NAME) ?>

				<input id="tab-1" type="radio" name="tabs" checked="checked"/>
				<input id="tab-2" type="radio" name="tabs"/>
				<input id="tab-3" type="radio" name="tabs"/>
				
				<div class="tabs">
					<label for="tab-1"><?php _e('Environment',self::PLUGIN_ID); ?></label>
					<label for="tab-2"><?php _e('Shipping & Tax',self::PLUGIN_ID); ?></label>
					<label for="tab-3"><?php _e('Messages',self::PLUGIN_ID); ?></label>
				</div>

				<div class="tab-content">
					
					<div class="tab">
						<div class="wp-strpe-cart_setting-block border">
							<h2 class="title"><?php _e('Cart mode',self::PLUGIN_ID); ?></h2>
							<p><?php _e('Please make sure set to <strong>Live</strong> before releasing your site to public.',self::PLUGIN_ID); ?></p>
							<label class="wp-strpe-cart_setting-block__radio">
								<input name="environment" type="radio" value="test" class="tog" <?php if($environment != 'live'): ?> checked="checked"<?php endif; ?>>
								<?php _e('Test',self::PLUGIN_ID); ?>
							</label>
							<label class="wp-strpe-cart_setting-block__radio">
								<input name="environment" type="radio" value="live" class="tog" <?php if($environment == 'live'): ?> checked="checked"<?php endif; ?>>
								<?php _e('Live',self::PLUGIN_ID); ?>
							</label>
						</div>
						<div class="wp-strpe-cart_setting-block border">
							<h2 class="title"><?php _e('API keys',self::PLUGIN_ID); ?></h2>
							<p><?php echo sprintf(__("Get %s from STRIPE dashboard.",self::PLUGIN_ID),__('API keys',self::PLUGIN_ID)); ?></p>
							<h3 class="wp-strpe-cart_setting-block__sub"><?php _e('Test Keys',self::PLUGIN_ID); ?></h3>
							<div class="wp-strpe-cart_fieldgroup <?php if(array_key_exists('test',self::$error_message)):?> has_error<?php endif; ?>">
								<label for="test-publishable-key" class="wp-strpe-cart_fieldgroup__label"><?php _e('Publishable key',self::PLUGIN_ID); ?></label>
								<input id="test-publishable-key" name="test-publishable-key" type="text" class="wp-strpe-cart_fieldgroup__input" value="<?php echo $test_publishable_key; ?>">
							</div>
							<div class="wp-strpe-cart_fieldgroup <?php if(array_key_exists('test',self::$error_message)):?> has_error<?php endif; ?>">
								<label for="test-secret-key" class="wp-strpe-cart_fieldgroup__label"><?php _e('Secret key',self::PLUGIN_ID); ?></label>
								<input id="test-secret-key" name="test-secret-key" type="text" class="wp-strpe-cart_fieldgroup__input" value="<?php echo $test_secret_key; ?>">
							</div>
							<h3 class="wp-strpe-cart_setting-block__sub"><?php _e('Live Keys',self::PLUGIN_ID); ?></h3>
							<div class="wp-strpe-cart_fieldgroup <?php if(array_key_exists('live',self::$error_message)):?> has_error<?php endif; ?>">
								<label for="publishable-key" class="wp-strpe-cart_fieldgroup__label"><?php _e('Publishable key',self::PLUGIN_ID); ?></label>
								<input id="publishable-key" name="publishable-key" type="text" class="wp-strpe-cart_fieldgroup__input" value="<?php echo $publishable_key; ?>">
							</div>
							<div class="wp-strpe-cart_fieldgroup <?php if(array_key_exists('live',self::$error_message)):?> has_error<?php endif; ?>">
								<label for="secret-key" class="wp-strpe-cart_fieldgroup__label"><?php _e('Secret key',self::PLUGIN_ID); ?></label>
								<input id="secret-key" name="secret-key" type="text" class="wp-strpe-cart_fieldgroup__input" value="<?php echo $secret_key; ?>">
							</div>
						</div>

						<div class="wp-strpe-cart_setting-block border">
							<h2 class="title"><?php _e('Payment Description',self::PLUGIN_ID); ?></h2>
							<p><?php echo nl2br(__("Payment description will appear in receipt email and user's mobile payment screen. 
							And also displayed in Stripe Payment dashboard.
							It is advised to reflects your doing business name. i.e. Shop Name.
							If you leave this field blank your domain name will be used.",  self::PLUGIN_ID)); ?></p>
							<div class="wp-strpe-cart_fieldgroup">
								<input id="payment_description" type="text" name="payment_description" class="wp-strpe-cart_fieldgroup__input standalone" value="<?php echo nl2br($payment_description); ?>">
							</div>
						</div>

						<div class="wp-strpe-cart_setting-block">
							<h2 class="title"><?php _e('Filter Countries',self::PLUGIN_ID); ?></h2>
							<p><?php echo nl2br(__('Please list Two-letter country code (<a href="https://en.wikipedia.org/wiki/ISO_3166-1_alpha-2" target="_blank">ISO 3166-1 alpha-2</a>). in comma separated format, if you need to filter country.
							i.e. JP,US,GB
							Please note that any country not listed in default will be ignored.
							
							Default list of country is:
							IS, IE, AZ, AF, US, AE, DZ, AR, AW, AL, AM, AI, AO, AG, AD, YE, GB, IL, IT, IQ, IN, ID, WF, UG, UA, UZ, UY, EC, EG, EE, SZ, ET, ER, SV, AU, AT, AX, OM, NL, BQ, GH, CV, GG, GY, KZ, QA, CA, GA, CM, GM, KH, GN, GW, CY, CW, GR, KI, KG, GT, GP, GU, KW, CK, GL, GD, HR, KY, KE, CI, CR, XK, KM, CO, CG, CD, SA, GS, WS, BL, MF, ST, ZM, PM, SM, SL, DJ, GI, JE, JM, GE, SG, SX, ZW, CH, SE, SJ, ES, SR, LK, SK, SI, SC, SN, RS, KN, VC, SH, LC, SO, SB, TC, TH, TJ, TZ, CZ, TD, TN, CL, TV, DK, DE, TG, TK, DO, DM, TA, TT, TM, TR, TO, NG, NR, NA, NU, NI, NE, NC, NZ, NP, NO, BH, HT, PK, VA, PA, VU, BS, PG, BM, PY, BB, PS, HU, BD, PN, FJ, PH, FI, BT, BV, PR, FO, FK, BR, FR, BG, BF, BN, BI, VN, BJ, VE, BY, BZ, PE, BE, PL, BA, BW, BO, PT, HN, MG, YT, MW, ML, MT, MQ, MY, IM, MM, MX, MU, MR, MZ, MC, MV, MD, MA, MN, ME, MS, JO, LA, LV, LT, LY, LI, LR, RO, LU, RW, LS, LB, RE, RU, IO, VG, KR, EH, GQ, TW, CF, MO, HK, CN, TL, ZA, SS, AQ, JP, GF, PF, TF, MK
							',  self::PLUGIN_ID)); ?></p>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="filter_countries" class="wp-strpe-cart_fieldgroup__label"><?php _e('Country list',  self::PLUGIN_ID); ?></label>
								<textarea id="filter_countries" name="filter_countries" class="wp-strpe-cart_fieldgroup__input textarea" rows="5"><?php echo nl2br($filter_countries); ?></textarea>
							</div>
						</div>

						<div class="wp-strpe-cart_setting-block">
							<h2 class="title"><?php _e('Default Country', self::PLUGIN_ID); ?></h2>
							<p><?php echo nl2br(__('Please select default country to be checked in checkout form.', self::PLUGIN_ID)); ?></p>
							<div class="wp-strpe-cart_fieldgroup alone">
								<select id="default_country" name="default_country" class="wp-strpe-cart_fieldgroup__input">
									<option value=""><?php _e('Please select', self::PLUGIN_ID); ?></option>
									<option value="AF" <?php if($default_country == 'AF'):?> selected<?php endif; ?>><?php _e('Afghanistan', self::PLUGIN_ID);?></option>
									<option value="AX" <?php if($default_country == 'AX'):?> selected<?php endif; ?>><?php _e('Åland Islands', self::PLUGIN_ID);?></option>
									<option value="AL" <?php if($default_country == 'AL'):?> selected<?php endif; ?>><?php _e('Albania', self::PLUGIN_ID);?></option>
									<option value="DZ" <?php if($default_country == 'DZ'):?> selected<?php endif; ?>><?php _e('Algeria', self::PLUGIN_ID);?></option>
									<option value="AD" <?php if($default_country == 'AD'):?> selected<?php endif; ?>><?php _e('Andorra', self::PLUGIN_ID);?></option>
									<option value="AO" <?php if($default_country == 'AO'):?> selected<?php endif; ?>><?php _e('Angola', self::PLUGIN_ID);?></option>
									<option value="AI" <?php if($default_country == 'AI'):?> selected<?php endif; ?>><?php _e('Anguilla', self::PLUGIN_ID);?></option>
									<option value="AQ" <?php if($default_country == 'AQ'):?> selected<?php endif; ?>><?php _e('Antarctica', self::PLUGIN_ID);?></option>
									<option value="AG" <?php if($default_country == 'AG'):?> selected<?php endif; ?>><?php _e('Antigua &amp; Barbuda', self::PLUGIN_ID);?></option>
									<option value="AR" <?php if($default_country == 'AR'):?> selected<?php endif; ?>><?php _e('Argentina', self::PLUGIN_ID);?></option>
									<option value="AM" <?php if($default_country == 'AM'):?> selected<?php endif; ?>><?php _e('Armenia', self::PLUGIN_ID);?></option>
									<option value="AW" <?php if($default_country == 'AW'):?> selected<?php endif; ?>><?php _e('Aruba', self::PLUGIN_ID);?></option>
									<option value="AU" <?php if($default_country == 'AU'):?> selected<?php endif; ?>><?php _e('Australia', self::PLUGIN_ID);?></option>
									<option value="AT" <?php if($default_country == 'AT'):?> selected<?php endif; ?>><?php _e('Austria', self::PLUGIN_ID);?></option>
									<option value="AZ" <?php if($default_country == 'AZ'):?> selected<?php endif; ?>><?php _e('Azerbaijan',self::PLUGIN_ID);?></option>
									<option value="BS" <?php if($default_country == 'BS'):?> selected<?php endif; ?>><?php _e('Bahamas',self::PLUGIN_ID);?></option>
									<option value="BH" <?php if($default_country == 'BH'):?> selected<?php endif; ?>><?php _e('Bahrain',self::PLUGIN_ID);?></option>
									<option value="BD" <?php if($default_country == 'BD'):?> selected<?php endif; ?>><?php _e('Bangladesh',self::PLUGIN_ID);?></option>
									<option value="BB" <?php if($default_country == 'BB'):?> selected<?php endif; ?>><?php _e('Barbados',self::PLUGIN_ID);?></option>
									<option value="BY" <?php if($default_country == 'BY'):?> selected<?php endif; ?>><?php _e('Belarus',self::PLUGIN_ID);?></option>
									<option value="BE" <?php if($default_country == 'BE'):?> selected<?php endif; ?>><?php _e('Belgium',self::PLUGIN_ID);?></option>
									<option value="BZ" <?php if($default_country == 'BZ'):?> selected<?php endif; ?>><?php _e('Belize',self::PLUGIN_ID);?></option>
									<option value="BJ" <?php if($default_country == 'BJ'):?> selected<?php endif; ?>><?php _e('Benin',self::PLUGIN_ID);?></option>
									<option value="BM" <?php if($default_country == 'BM'):?> selected<?php endif; ?>><?php _e('Bermuda',self::PLUGIN_ID);?></option>
									<option value="BT" <?php if($default_country == 'BT'):?> selected<?php endif; ?>><?php _e('Bhutan',self::PLUGIN_ID);?></option>
									<option value="BO" <?php if($default_country == 'BO'):?> selected<?php endif; ?>><?php _e('Bolivia',self::PLUGIN_ID);?></option>
									<option value="BA" <?php if($default_country == 'BA'):?> selected<?php endif; ?>><?php _e('Bosnia &amp; Herzegovina',self::PLUGIN_ID);?></option>
									<option value="BW" <?php if($default_country == 'BW'):?> selected<?php endif; ?>><?php _e('Botswana',self::PLUGIN_ID);?></option>
									<option value="BV" <?php if($default_country == 'BV'):?> selected<?php endif; ?>><?php _e('Bouvet Island',self::PLUGIN_ID);?></option>
									<option value="BR" <?php if($default_country == 'BR'):?> selected<?php endif; ?>><?php _e('Brazil',self::PLUGIN_ID);?></option>
									<option value="IO" <?php if($default_country == 'IO'):?> selected<?php endif; ?>><?php _e('British Indian Ocean Territory',self::PLUGIN_ID);?></option>
									<option value="VG" <?php if($default_country == 'VG'):?> selected<?php endif; ?>><?php _e('British Virgin Islands',self::PLUGIN_ID);?></option>
									<option value="BN" <?php if($default_country == 'BN'):?> selected<?php endif; ?>><?php _e('Brunei',self::PLUGIN_ID);?></option>
									<option value="BG" <?php if($default_country == 'BG'):?> selected<?php endif; ?>><?php _e('Bulgaria',self::PLUGIN_ID);?></option>
									<option value="BF" <?php if($default_country == 'BF'):?> selected<?php endif; ?>><?php _e('Burkina Faso',self::PLUGIN_ID);?></option>
									<option value="BI" <?php if($default_country == 'BI'):?> selected<?php endif; ?>><?php _e('Burundi',self::PLUGIN_ID);?></option>
									<option value="KH" <?php if($default_country == 'KH'):?> selected<?php endif; ?>><?php _e('Cambodia',self::PLUGIN_ID);?></option>
									<option value="CM" <?php if($default_country == 'CM'):?> selected<?php endif; ?>><?php _e('Cameroon',self::PLUGIN_ID);?></option>
									<option value="CA" <?php if($default_country == 'CA'):?> selected<?php endif; ?>><?php _e('Canada',self::PLUGIN_ID);?></option>
									<option value="CV" <?php if($default_country == 'CV'):?> selected<?php endif; ?>><?php _e('Cape Verde',self::PLUGIN_ID);?></option>
									<option value="BQ" <?php if($default_country == 'BQ'):?> selected<?php endif; ?>><?php _e('Caribbean Netherlands',self::PLUGIN_ID);?></option>
									<option value="KY" <?php if($default_country == 'KY'):?> selected<?php endif; ?>><?php _e('Cayman Islands',self::PLUGIN_ID);?></option>
									<option value="CF" <?php if($default_country == 'CF'):?> selected<?php endif; ?>><?php _e('Central African Republic',self::PLUGIN_ID);?></option>
									<option value="TD" <?php if($default_country == 'TD'):?> selected<?php endif; ?>><?php _e('Chad',self::PLUGIN_ID);?></option>
									<option value="CL" <?php if($default_country == 'CL'):?> selected<?php endif; ?>><?php _e('Chile',self::PLUGIN_ID);?></option>
									<option value="CN" <?php if($default_country == 'CN'):?> selected<?php endif; ?>><?php _e('China',self::PLUGIN_ID);?></option>
									<option value="CO" <?php if($default_country == 'CO'):?> selected<?php endif; ?>><?php _e('Colombia',self::PLUGIN_ID);?></option>
									<option value="KM" <?php if($default_country == 'KM'):?> selected<?php endif; ?>><?php _e('Comoros',self::PLUGIN_ID);?></option>
									<option value="CG" <?php if($default_country == 'CG'):?> selected<?php endif; ?>><?php _e('Congo - Brazzaville',self::PLUGIN_ID);?></option>
									<option value="CD" <?php if($default_country == 'CD'):?> selected<?php endif; ?>><?php _e('Congo - Kinshasa',self::PLUGIN_ID);?></option>
									<option value="CK" <?php if($default_country == 'CK'):?> selected<?php endif; ?>><?php _e('Cook Islands',self::PLUGIN_ID);?></option>
									<option value="CR" <?php if($default_country == 'CR'):?> selected<?php endif; ?>><?php _e('Costa Rica',self::PLUGIN_ID);?></option>
									<option value="CI" <?php if($default_country == 'CI'):?> selected<?php endif; ?>><?php _e('Côte d’Ivoire',self::PLUGIN_ID);?></option>
									<option value="HR" <?php if($default_country == 'HR'):?> selected<?php endif; ?>><?php _e('Croatia',self::PLUGIN_ID);?></option>
									<option value="CW" <?php if($default_country == 'CW'):?> selected<?php endif; ?>><?php _e('Curaçao',self::PLUGIN_ID);?></option>
									<option value="CY" <?php if($default_country == 'CY'):?> selected<?php endif; ?>><?php _e('Cyprus',self::PLUGIN_ID);?></option>
									<option value="CZ" <?php if($default_country == 'CZ'):?> selected<?php endif; ?>><?php _e('Czechia',self::PLUGIN_ID);?></option>
									<option value="DK" <?php if($default_country == 'DK'):?> selected<?php endif; ?>><?php _e('Denmark',self::PLUGIN_ID);?></option>
									<option value="DJ" <?php if($default_country == 'DJ'):?> selected<?php endif; ?>><?php _e('Djibouti',self::PLUGIN_ID);?></option>
									<option value="DM" <?php if($default_country == 'DM'):?> selected<?php endif; ?>><?php _e('Dominica',self::PLUGIN_ID);?></option>
									<option value="DO" <?php if($default_country == 'DO'):?> selected<?php endif; ?>><?php _e('Dominican Republic',self::PLUGIN_ID);?></option>
									<option value="EC" <?php if($default_country == 'EC'):?> selected<?php endif; ?>><?php _e('Ecuador',self::PLUGIN_ID);?></option>
									<option value="EG" <?php if($default_country == 'EG'):?> selected<?php endif; ?>><?php _e('Egypt',self::PLUGIN_ID);?></option>
									<option value="SV" <?php if($default_country == 'SV'):?> selected<?php endif; ?>><?php _e('El Salvador',self::PLUGIN_ID);?></option>
									<option value="GQ" <?php if($default_country == 'GQ'):?> selected<?php endif; ?>><?php _e('Equatorial Guinea',self::PLUGIN_ID);?></option>
									<option value="ER" <?php if($default_country == 'ER'):?> selected<?php endif; ?>><?php _e('Eritrea',self::PLUGIN_ID);?></option>
									<option value="EE" <?php if($default_country == 'EE'):?> selected<?php endif; ?>><?php _e('Estonia',self::PLUGIN_ID);?></option>
									<option value="SZ" <?php if($default_country == 'SZ'):?> selected<?php endif; ?>><?php _e('Eswatini',self::PLUGIN_ID);?></option>
									<option value="ET" <?php if($default_country == 'ET'):?> selected<?php endif; ?>><?php _e('Ethiopia',self::PLUGIN_ID);?></option>
									<option value="FK" <?php if($default_country == 'FK'):?> selected<?php endif; ?>><?php _e('Falkland Islands',self::PLUGIN_ID);?></option>
									<option value="FO" <?php if($default_country == 'FO'):?> selected<?php endif; ?>><?php _e('Faroe Islands',self::PLUGIN_ID);?></option>
									<option value="FJ" <?php if($default_country == 'FJ'):?> selected<?php endif; ?>><?php _e('Fiji',self::PLUGIN_ID);?></option>
									<option value="FI" <?php if($default_country == 'FI'):?> selected<?php endif; ?>><?php _e('Finland',self::PLUGIN_ID);?></option>
									<option value="FR" <?php if($default_country == 'FR'):?> selected<?php endif; ?>><?php _e('France',self::PLUGIN_ID);?></option>
									<option value="GF" <?php if($default_country == 'GF'):?> selected<?php endif; ?>><?php _e('French Guiana',self::PLUGIN_ID);?></option>
									<option value="PF" <?php if($default_country == 'PF'):?> selected<?php endif; ?>><?php _e('French Polynesia',self::PLUGIN_ID);?></option>
									<option value="TF" <?php if($default_country == 'TF'):?> selected<?php endif; ?>><?php _e('French Southern Territories',self::PLUGIN_ID);?></option>
									<option value="GA" <?php if($default_country == 'GA'):?> selected<?php endif; ?>><?php _e('Gabon',self::PLUGIN_ID);?></option>
									<option value="GM" <?php if($default_country == 'GM'):?> selected<?php endif; ?>><?php _e('Gambia',self::PLUGIN_ID);?></option>
									<option value="GE" <?php if($default_country == 'GE'):?> selected<?php endif; ?>><?php _e('Georgia',self::PLUGIN_ID);?></option>
									<option value="DE" <?php if($default_country == 'DE'):?> selected<?php endif; ?>><?php _e('Germany',self::PLUGIN_ID);?></option>
									<option value="GH" <?php if($default_country == 'GH'):?> selected<?php endif; ?>><?php _e('Ghana',self::PLUGIN_ID);?></option>
									<option value="GI" <?php if($default_country == 'GI'):?> selected<?php endif; ?>><?php _e('Gibraltar',self::PLUGIN_ID);?></option>
									<option value="GR" <?php if($default_country == 'GR'):?> selected<?php endif; ?>><?php _e('Greece',self::PLUGIN_ID);?></option>
									<option value="GL" <?php if($default_country == 'GL'):?> selected<?php endif; ?>><?php _e('Greenland',self::PLUGIN_ID);?></option>
									<option value="GD" <?php if($default_country == 'GD'):?> selected<?php endif; ?>><?php _e('Grenada',self::PLUGIN_ID);?></option>
									<option value="GP" <?php if($default_country == 'GP'):?> selected<?php endif; ?>><?php _e('Guadeloupe',self::PLUGIN_ID);?></option>
									<option value="GU" <?php if($default_country == 'GU'):?> selected<?php endif; ?>><?php _e('Guam',self::PLUGIN_ID);?></option>
									<option value="GT" <?php if($default_country == 'GT'):?> selected<?php endif; ?>><?php _e('Guatemala',self::PLUGIN_ID);?></option>
									<option value="GG" <?php if($default_country == 'GG'):?> selected<?php endif; ?>><?php _e('Guernsey',self::PLUGIN_ID);?></option>
									<option value="GN" <?php if($default_country == 'GN'):?> selected<?php endif; ?>><?php _e('Guinea',self::PLUGIN_ID);?></option>
									<option value="GW" <?php if($default_country == 'GW'):?> selected<?php endif; ?>><?php _e('Guinea-Bissau',self::PLUGIN_ID);?></option>
									<option value="GY" <?php if($default_country == 'GY'):?> selected<?php endif; ?>><?php _e('Guyana',self::PLUGIN_ID);?></option>
									<option value="HT" <?php if($default_country == 'HT'):?> selected<?php endif; ?>><?php _e('Haiti',self::PLUGIN_ID);?></option>
									<option value="HN" <?php if($default_country == 'HN'):?> selected<?php endif; ?>><?php _e('Honduras',self::PLUGIN_ID);?></option>
									<option value="HK" <?php if($default_country == 'HK'):?> selected<?php endif; ?>><?php _e('Hong Kong SAR China',self::PLUGIN_ID);?></option>
									<option value="HU" <?php if($default_country == 'HU'):?> selected<?php endif; ?>><?php _e('Hungary',self::PLUGIN_ID);?></option>
									<option value="IS" <?php if($default_country == 'IS'):?> selected<?php endif; ?>><?php _e('Iceland',self::PLUGIN_ID);?></option>
									<option value="IN" <?php if($default_country == 'IN'):?> selected<?php endif; ?>><?php _e('India',self::PLUGIN_ID);?></option>
									<option value="ID" <?php if($default_country == 'ID'):?> selected<?php endif; ?>><?php _e('Indonesia',self::PLUGIN_ID);?></option>
									<option value="IQ" <?php if($default_country == 'IQ'):?> selected<?php endif; ?>><?php _e('Iraq',self::PLUGIN_ID);?></option>
									<option value="IE" <?php if($default_country == 'IE'):?> selected<?php endif; ?>><?php _e('Ireland',self::PLUGIN_ID);?></option>
									<option value="IM" <?php if($default_country == 'IM'):?> selected<?php endif; ?>><?php _e('Isle of Man',self::PLUGIN_ID);?></option>
									<option value="IL" <?php if($default_country == 'IL'):?> selected<?php endif; ?>><?php _e('Israel',self::PLUGIN_ID);?></option>
									<option value="IT" <?php if($default_country == 'IT'):?> selected<?php endif; ?>><?php _e('Italy',self::PLUGIN_ID);?></option>
									<option value="JM" <?php if($default_country == 'JM'):?> selected<?php endif; ?>><?php _e('Jamaica',self::PLUGIN_ID);?></option>
									<option value="JP" <?php if($default_country == 'JP'):?> selected<?php endif; ?>><?php _e('Japan',self::PLUGIN_ID);?></option>
									<option value="JE" <?php if($default_country == 'JE'):?> selected<?php endif; ?>><?php _e('Jersey',self::PLUGIN_ID);?></option>
									<option value="JO" <?php if($default_country == 'JO'):?> selected<?php endif; ?>><?php _e('Jordan',self::PLUGIN_ID);?></option>
									<option value="KZ" <?php if($default_country == 'KZ'):?> selected<?php endif; ?>><?php _e('Kazakhstan',self::PLUGIN_ID);?></option>
									<option value="KE" <?php if($default_country == 'KE'):?> selected<?php endif; ?>><?php _e('Kenya',self::PLUGIN_ID);?></option>
									<option value="KI" <?php if($default_country == 'KI'):?> selected<?php endif; ?>><?php _e('Kiribati',self::PLUGIN_ID);?></option>
									<option value="XK" <?php if($default_country == 'XK'):?> selected<?php endif; ?>><?php _e('Kosovo',self::PLUGIN_ID);?></option>
									<option value="KW" <?php if($default_country == 'KW'):?> selected<?php endif; ?>><?php _e('Kuwait',self::PLUGIN_ID);?></option>
									<option value="KG" <?php if($default_country == 'KG'):?> selected<?php endif; ?>><?php _e('Kyrgyzstan',self::PLUGIN_ID);?></option>
									<option value="LA" <?php if($default_country == 'LA'):?> selected<?php endif; ?>><?php _e('Laos',self::PLUGIN_ID);?></option>
									<option value="LV" <?php if($default_country == 'LV'):?> selected<?php endif; ?>><?php _e('Latvia',self::PLUGIN_ID);?></option>
									<option value="LB" <?php if($default_country == 'LB'):?> selected<?php endif; ?>><?php _e('Lebanon',self::PLUGIN_ID);?></option>
									<option value="LS" <?php if($default_country == 'LS'):?> selected<?php endif; ?>><?php _e('Lesotho',self::PLUGIN_ID);?></option>
									<option value="LR" <?php if($default_country == 'LR'):?> selected<?php endif; ?>><?php _e('Liberia',self::PLUGIN_ID);?></option>
									<option value="LY" <?php if($default_country == 'LY'):?> selected<?php endif; ?>><?php _e('Libya',self::PLUGIN_ID);?></option>
									<option value="LI" <?php if($default_country == 'LI'):?> selected<?php endif; ?>><?php _e('Liechtenstein',self::PLUGIN_ID);?></option>
									<option value="LT" <?php if($default_country == 'LT'):?> selected<?php endif; ?>><?php _e('Lithuania',self::PLUGIN_ID);?></option>
									<option value="LU" <?php if($default_country == 'LU'):?> selected<?php endif; ?>><?php _e('Luxembourg',self::PLUGIN_ID);?></option>
									<option value="MO" <?php if($default_country == 'MO'):?> selected<?php endif; ?>><?php _e('Macao SAR China',self::PLUGIN_ID);?></option>
									<option value="MG" <?php if($default_country == 'MG'):?> selected<?php endif; ?>><?php _e('Madagascar',self::PLUGIN_ID);?></option>
									<option value="MW" <?php if($default_country == 'MW'):?> selected<?php endif; ?>><?php _e('Malawi',self::PLUGIN_ID);?></option>
									<option value="MY" <?php if($default_country == 'MY'):?> selected<?php endif; ?>><?php _e('Malaysia',self::PLUGIN_ID);?></option>
									<option value="MV" <?php if($default_country == 'MV'):?> selected<?php endif; ?>><?php _e('Maldives',self::PLUGIN_ID);?></option>
									<option value="ML" <?php if($default_country == 'ML'):?> selected<?php endif; ?>><?php _e('Mali',self::PLUGIN_ID);?></option>
									<option value="MT" <?php if($default_country == 'MT'):?> selected<?php endif; ?>><?php _e('Malta',self::PLUGIN_ID);?></option>
									<option value="MQ" <?php if($default_country == 'MQ'):?> selected<?php endif; ?>><?php _e('Martinique',self::PLUGIN_ID);?></option>
									<option value="MR" <?php if($default_country == 'MR'):?> selected<?php endif; ?>><?php _e('Mauritania',self::PLUGIN_ID);?></option>
									<option value="MU" <?php if($default_country == 'MU'):?> selected<?php endif; ?>><?php _e('Mauritius',self::PLUGIN_ID);?></option>
									<option value="YT" <?php if($default_country == 'YT'):?> selected<?php endif; ?>><?php _e('Mayotte',self::PLUGIN_ID);?></option>
									<option value="MX" <?php if($default_country == 'MX'):?> selected<?php endif; ?>><?php _e('Mexico',self::PLUGIN_ID);?></option>
									<option value="MD" <?php if($default_country == 'MD'):?> selected<?php endif; ?>><?php _e('Moldova',self::PLUGIN_ID);?></option>
									<option value="MC" <?php if($default_country == 'MC'):?> selected<?php endif; ?>><?php _e('Monaco',self::PLUGIN_ID);?></option>
									<option value="MN" <?php if($default_country == 'MN'):?> selected<?php endif; ?>><?php _e('Mongolia',self::PLUGIN_ID);?></option>
									<option value="ME" <?php if($default_country == 'ME'):?> selected<?php endif; ?>><?php _e('Montenegro',self::PLUGIN_ID);?></option>
									<option value="MS" <?php if($default_country == 'MS'):?> selected<?php endif; ?>><?php _e('Montserrat',self::PLUGIN_ID);?></option>
									<option value="MA" <?php if($default_country == 'MA'):?> selected<?php endif; ?>><?php _e('Morocco',self::PLUGIN_ID);?></option>
									<option value="MZ" <?php if($default_country == 'MZ'):?> selected<?php endif; ?>><?php _e('Mozambique',self::PLUGIN_ID);?></option>
									<option value="MM" <?php if($default_country == 'MM'):?> selected<?php endif; ?>><?php _e('Myanmar (Burma)',self::PLUGIN_ID);?></option>
									<option value="NA" <?php if($default_country == 'NA'):?> selected<?php endif; ?>><?php _e('Namibia',self::PLUGIN_ID);?></option>
									<option value="NR" <?php if($default_country == 'NR'):?> selected<?php endif; ?>><?php _e('Nauru',self::PLUGIN_ID);?></option>
									<option value="NP" <?php if($default_country == 'NP'):?> selected<?php endif; ?>><?php _e('Nepal',self::PLUGIN_ID);?></option>
									<option value="NL" <?php if($default_country == 'NL'):?> selected<?php endif; ?>><?php _e('Netherlands',self::PLUGIN_ID);?></option>
									<option value="NC" <?php if($default_country == 'NC'):?> selected<?php endif; ?>><?php _e('New Caledonia',self::PLUGIN_ID);?></option>
									<option value="NZ" <?php if($default_country == 'NZ'):?> selected<?php endif; ?>><?php _e('New Zealand',self::PLUGIN_ID);?></option>
									<option value="NI" <?php if($default_country == 'NI'):?> selected<?php endif; ?>><?php _e('Nicaragua',self::PLUGIN_ID);?></option>
									<option value="NE" <?php if($default_country == 'NE'):?> selected<?php endif; ?>><?php _e('Niger',self::PLUGIN_ID);?></option>
									<option value="NG" <?php if($default_country == 'NG'):?> selected<?php endif; ?>><?php _e('Nigeria',self::PLUGIN_ID);?></option>
									<option value="NU" <?php if($default_country == 'NU'):?> selected<?php endif; ?>><?php _e('Niue',self::PLUGIN_ID);?></option>
									<option value="MK" <?php if($default_country == 'MK'):?> selected<?php endif; ?>><?php _e('North Macedonia',self::PLUGIN_ID);?></option>
									<option value="NO" <?php if($default_country == 'NO'):?> selected<?php endif; ?>><?php _e('Norway',self::PLUGIN_ID);?></option>
									<option value="OM" <?php if($default_country == 'OM'):?> selected<?php endif; ?>><?php _e('Oman',self::PLUGIN_ID);?></option>
									<option value="PK" <?php if($default_country == 'PK'):?> selected<?php endif; ?>><?php _e('Pakistan',self::PLUGIN_ID);?></option>
									<option value="PS" <?php if($default_country == 'PS'):?> selected<?php endif; ?>><?php _e('Palestinian Territories',self::PLUGIN_ID);?></option>
									<option value="PA" <?php if($default_country == 'PA'):?> selected<?php endif; ?>><?php _e('Panama',self::PLUGIN_ID);?></option>
									<option value="PG" <?php if($default_country == 'PG'):?> selected<?php endif; ?>><?php _e('Papua New Guinea',self::PLUGIN_ID);?></option>
									<option value="PY" <?php if($default_country == 'PY'):?> selected<?php endif; ?>><?php _e('Paraguay',self::PLUGIN_ID);?></option>
									<option value="PE" <?php if($default_country == 'PE'):?> selected<?php endif; ?>><?php _e('Peru',self::PLUGIN_ID);?></option>
									<option value="PH" <?php if($default_country == 'PH'):?> selected<?php endif; ?>><?php _e('Philippines',self::PLUGIN_ID);?></option>
									<option value="PN" <?php if($default_country == 'PN'):?> selected<?php endif; ?>><?php _e('Pitcairn Islands',self::PLUGIN_ID);?></option>
									<option value="PL" <?php if($default_country == 'PL'):?> selected<?php endif; ?>><?php _e('Poland',self::PLUGIN_ID);?></option>
									<option value="PT" <?php if($default_country == 'PT'):?> selected<?php endif; ?>><?php _e('Portugal',self::PLUGIN_ID);?></option>
									<option value="PR" <?php if($default_country == 'PR'):?> selected<?php endif; ?>><?php _e('Puerto Rico',self::PLUGIN_ID);?></option>
									<option value="QA" <?php if($default_country == 'QA'):?> selected<?php endif; ?>><?php _e('Qatar',self::PLUGIN_ID);?></option>
									<option value="KR" <?php if($default_country == 'KR'):?> selected<?php endif; ?>><?php _e('Republic of Korea',self::PLUGIN_ID);?></option>
									<option value="RE" <?php if($default_country == 'RE'):?> selected<?php endif; ?>><?php _e('Réunion',self::PLUGIN_ID);?></option>
									<option value="RO" <?php if($default_country == 'RO'):?> selected<?php endif; ?>><?php _e('Romania',self::PLUGIN_ID);?></option>
									<option value="RU" <?php if($default_country == 'RU'):?> selected<?php endif; ?>><?php _e('Russia',self::PLUGIN_ID);?></option>
									<option value="RW" <?php if($default_country == 'RW'):?> selected<?php endif; ?>><?php _e('Rwanda',self::PLUGIN_ID);?></option>
									<option value="WS" <?php if($default_country == 'WS'):?> selected<?php endif; ?>><?php _e('Samoa',self::PLUGIN_ID);?></option>
									<option value="SM" <?php if($default_country == 'SM'):?> selected<?php endif; ?>><?php _e('San Marino',self::PLUGIN_ID);?></option>
									<option value="ST" <?php if($default_country == 'ST'):?> selected<?php endif; ?>><?php _e('São Tomé &amp; Príncipe',self::PLUGIN_ID);?></option>
									<option value="SA" <?php if($default_country == 'SA'):?> selected<?php endif; ?>><?php _e('Saudi Arabia',self::PLUGIN_ID);?></option>
									<option value="SN" <?php if($default_country == 'SN'):?> selected<?php endif; ?>><?php _e('Senegal',self::PLUGIN_ID);?></option>
									<option value="RS" <?php if($default_country == 'RS'):?> selected<?php endif; ?>><?php _e('Serbia',self::PLUGIN_ID);?></option>
									<option value="SC" <?php if($default_country == 'SC'):?> selected<?php endif; ?>><?php _e('Seychelles',self::PLUGIN_ID);?></option>
									<option value="SL" <?php if($default_country == 'SL'):?> selected<?php endif; ?>><?php _e('Sierra Leone',self::PLUGIN_ID);?></option>
									<option value="SG" <?php if($default_country == 'SG'):?> selected<?php endif; ?>><?php _e('Singapore',self::PLUGIN_ID);?></option>
									<option value="SX" <?php if($default_country == 'SX'):?> selected<?php endif; ?>><?php _e('Sint Maarten',self::PLUGIN_ID);?></option>
									<option value="SK" <?php if($default_country == 'SK'):?> selected<?php endif; ?>><?php _e('Slovakia',self::PLUGIN_ID);?></option>
									<option value="SI" <?php if($default_country == 'SI'):?> selected<?php endif; ?>><?php _e('Slovenia',self::PLUGIN_ID);?></option>
									<option value="SB" <?php if($default_country == 'SB'):?> selected<?php endif; ?>><?php _e('Solomon Islands',self::PLUGIN_ID);?></option>
									<option value="SO" <?php if($default_country == 'SO'):?> selected<?php endif; ?>><?php _e('Somalia',self::PLUGIN_ID);?></option>
									<option value="ZA" <?php if($default_country == 'ZA'):?> selected<?php endif; ?>><?php _e('South Africa',self::PLUGIN_ID);?></option>
									<option value="GS" <?php if($default_country == 'GS'):?> selected<?php endif; ?>><?php _e('South Georgia &amp; South Sandwich Islands',self::PLUGIN_ID);?></option>
									<option value="SS" <?php if($default_country == 'SS'):?> selected<?php endif; ?>><?php _e('South Sudan',self::PLUGIN_ID);?></option>
									<option value="ES" <?php if($default_country == 'ES'):?> selected<?php endif; ?>><?php _e('Spain',self::PLUGIN_ID);?></option>
									<option value="LK" <?php if($default_country == 'LK'):?> selected<?php endif; ?>><?php _e('Sri Lanka',self::PLUGIN_ID);?></option>
									<option value="BL" <?php if($default_country == 'BL'):?> selected<?php endif; ?>><?php _e('St. Barthélemy',self::PLUGIN_ID);?></option>
									<option value="SH" <?php if($default_country == 'SH'):?> selected<?php endif; ?>><?php _e('St. Helena',self::PLUGIN_ID);?></option>
									<option value="KN" <?php if($default_country == 'KN'):?> selected<?php endif; ?>><?php _e('St. Kitts &amp; Nevis',self::PLUGIN_ID);?></option>
									<option value="LC" <?php if($default_country == 'LC'):?> selected<?php endif; ?>><?php _e('St. Lucia',self::PLUGIN_ID);?></option>
									<option value="MF" <?php if($default_country == 'MF'):?> selected<?php endif; ?>><?php _e('St. Martin',self::PLUGIN_ID);?></option>
									<option value="PM" <?php if($default_country == 'PM'):?> selected<?php endif; ?>><?php _e('St. Pierre &amp; Miquelon',self::PLUGIN_ID);?></option>
									<option value="VC" <?php if($default_country == 'VC'):?> selected<?php endif; ?>><?php _e('St. Vincent &amp; Grenadines',self::PLUGIN_ID);?></option>
									<option value="SR" <?php if($default_country == 'SR'):?> selected<?php endif; ?>><?php _e('Suriname',self::PLUGIN_ID);?></option>
									<option value="SJ" <?php if($default_country == 'SJ'):?> selected<?php endif; ?>><?php _e('Svalbard &amp; Jan Mayen',self::PLUGIN_ID);?></option>
									<option value="SE" <?php if($default_country == 'SE'):?> selected<?php endif; ?>><?php _e('Sweden',self::PLUGIN_ID);?></option>
									<option value="CH" <?php if($default_country == 'CH'):?> selected<?php endif; ?>><?php _e('Switzerland',self::PLUGIN_ID);?></option>
									<option value="TW" <?php if($default_country == 'TW'):?> selected<?php endif; ?>><?php _e('Taiwan',self::PLUGIN_ID);?></option>
									<option value="TJ" <?php if($default_country == 'TJ'):?> selected<?php endif; ?>><?php _e('Tajikistan',self::PLUGIN_ID);?></option>
									<option value="TZ" <?php if($default_country == 'TZ'):?> selected<?php endif; ?>><?php _e('Tanzania',self::PLUGIN_ID);?></option>
									<option value="TH" <?php if($default_country == 'TH'):?> selected<?php endif; ?>><?php _e('Thailand',self::PLUGIN_ID);?></option>
									<option value="TL" <?php if($default_country == 'TL'):?> selected<?php endif; ?>><?php _e('Timor-Leste',self::PLUGIN_ID);?></option>
									<option value="TG" <?php if($default_country == 'TG'):?> selected<?php endif; ?>><?php _e('Togo',self::PLUGIN_ID);?></option>
									<option value="TK" <?php if($default_country == 'TK'):?> selected<?php endif; ?>><?php _e('Tokelau',self::PLUGIN_ID);?></option>
									<option value="TO" <?php if($default_country == 'TO'):?> selected<?php endif; ?>><?php _e('Tonga',self::PLUGIN_ID);?></option>
									<option value="TT" <?php if($default_country == 'TT'):?> selected<?php endif; ?>><?php _e('Trinidad &amp; Tobago',self::PLUGIN_ID);?></option>
									<option value="TA" <?php if($default_country == 'TA'):?> selected<?php endif; ?>><?php _e('Tristan da Cunha',self::PLUGIN_ID);?></option>
									<option value="TN" <?php if($default_country == 'TN'):?> selected<?php endif; ?>><?php _e('Tunisia',self::PLUGIN_ID);?></option>
									<option value="TR" <?php if($default_country == 'TR'):?> selected<?php endif; ?>><?php _e('Turkey',self::PLUGIN_ID);?></option>
									<option value="TM" <?php if($default_country == 'TM'):?> selected<?php endif; ?>><?php _e('Turkmenistan',self::PLUGIN_ID);?></option>
									<option value="TC" <?php if($default_country == 'TC'):?> selected<?php endif; ?>><?php _e('Turks &amp; Caicos Islands',self::PLUGIN_ID);?></option>
									<option value="TV" <?php if($default_country == 'TV'):?> selected<?php endif; ?>><?php _e('Tuvalu',self::PLUGIN_ID);?></option>
									<option value="UG" <?php if($default_country == 'UG'):?> selected<?php endif; ?>><?php _e('Uganda',self::PLUGIN_ID);?></option>
									<option value="UA" <?php if($default_country == 'UA'):?> selected<?php endif; ?>><?php _e('Ukraine',self::PLUGIN_ID);?></option>
									<option value="AE" <?php if($default_country == 'AE'):?> selected<?php endif; ?>><?php _e('United Arab Emirates',self::PLUGIN_ID);?></option>
									<option value="GB" <?php if($default_country == 'GB'):?> selected<?php endif; ?>><?php _e('United Kingdom',self::PLUGIN_ID);?></option>
									<option value="US" <?php if($default_country == 'US'):?> selected<?php endif; ?>><?php _e('United States',self::PLUGIN_ID);?></option>
									<option value="UY" <?php if($default_country == 'UY'):?> selected<?php endif; ?>><?php _e('Uruguay',self::PLUGIN_ID);?></option>
									<option value="UZ" <?php if($default_country == 'UZ'):?> selected<?php endif; ?>><?php _e('Uzbekistan',self::PLUGIN_ID);?></option>
									<option value="VU" <?php if($default_country == 'VU'):?> selected<?php endif; ?>><?php _e('Vanuatu',self::PLUGIN_ID);?></option>
									<option value="VA" <?php if($default_country == 'VA'):?> selected<?php endif; ?>><?php _e('Vatican City',self::PLUGIN_ID);?></option>
									<option value="VE" <?php if($default_country == 'VE'):?> selected<?php endif; ?>><?php _e('Venezuela',self::PLUGIN_ID);?></option>
									<option value="VN" <?php if($default_country == 'VN'):?> selected<?php endif; ?>><?php _e('Vietnam',self::PLUGIN_ID);?></option>
									<option value="WF" <?php if($default_country == 'WF'):?> selected<?php endif; ?>><?php _e('Wallis &amp; Futuna',self::PLUGIN_ID);?></option>
									<option value="EH" <?php if($default_country == 'EH'):?> selected<?php endif; ?>><?php _e('Western Sahara',self::PLUGIN_ID);?></option>
									<option value="YE" <?php if($default_country == 'YE'):?> selected<?php endif; ?>><?php _e('Yemen',self::PLUGIN_ID);?></option>
									<option value="ZM" <?php if($default_country == 'ZM'):?> selected<?php endif; ?>><?php _e('Zambia',self::PLUGIN_ID);?></option>
									<option value="ZW" <?php if($default_country == 'ZW'):?> selected<?php endif; ?>><?php _e('Zimbabwe',self::PLUGIN_ID);?></option>
								</select>
							</div>
						</div>

					</div>

					<div class="tab">
						<div class="wp-strpe-cart_setting-block border">
							<h2 class="title"><?php _e('Tax',self::PLUGIN_ID); ?></h2>
							<p><?php _e('Please set Tax rate. If tax rate has set to 0 or balnk, No tax will be added on checkout.',self::PLUGIN_ID); ?></p>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="tax_rate" class="wp-strpe-cart_fieldgroup__label"><?php _e('Tax rate',self::PLUGIN_ID); ?></label>
								<input id="tax_rate" name="tax_rate" type="number" step="0.01" class="wp-strpe-cart_fieldgroup__input" value="<?php echo $tax_rate; ?>">
							</div>
						</div>
						<div class="wp-strpe-cart_setting-block">
							<h2 class="title"><?php _e('Shipping',self::PLUGIN_ID); ?></h2>
							<p><?php _e('If set to HIDE, shipping fee will not be charged regardless of shipping fee settings.',self::PLUGIN_ID); ?></p>
							<label class="wp-strpe-cart_setting-block__radio">
								<input name="charge_shipping" type="radio" value="1" class="tog" <?php if($charge_shipping == 1): ?> checked="checked"<?php endif; ?>>
								<?php _e('Display shipping fee',self::PLUGIN_ID); ?>
							</label>
							<label class="wp-strpe-cart_setting-block__radio">
								<input name="charge_shipping" type="radio" value="0" class="tog" <?php if($charge_shipping != 1): ?> checked="checked"<?php endif; ?>>
								<?php _e('Hide shipping fee',self::PLUGIN_ID); ?>
							</label>
						</div>

						<div class="wp-strpe-cart_setting-block">
							<h2 class="title"><?php _e('Shipping fee',self::PLUGIN_ID); ?></h2>
							<p><?php _e('If fixed fee has set, fixed shipping fee will be charged regardless of varied shipping fee setting.',self::PLUGIN_ID); ?></p>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="fixed_shipping_fee" class="wp-strpe-cart_fieldgroup__label"><?php _e('Fixed fee',self::PLUGIN_ID); ?></label>
								<input id="fixed_shipping_fee" name="fixed_shipping_fee" type="number" step="0.01" class="wp-strpe-cart_fieldgroup__input" value="<?php echo $fixed_shipping_fee; ?>">
							</div>
						</div>

						<div class="wp-strpe-cart_setting-block">
							<p><pre><?php _e('If you need charge shipping fee depend on country or state, please set them in JSON format.
example:
{
	"JP": {
		"北海道": 1200,
		"青森県": 1000,
		"岩手県": 1000
	},
	"GB": 1200,
	"US": 1800
}
							', self::PLUGIN_ID); ?></pre></p>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="varied_shipping_fee" class="wp-strpe-cart_fieldgroup__label"><?php _e('Varied Fee', self::PLUGIN_ID); ?></label>
								<textarea id="varied_shipping_fee" name="varied_shipping_fee" class="wp-strpe-cart_fieldgroup__input textarea" rows="5"><?php echo str_replace('\"','"',$varied_shipping_fee); ?></textarea>
							</div>
							<p><?php echo nl2br(__('Varied shipping fee setting is only availble to the country that provide state/province field as selectbox.
							Currently available countries are:
							United Arab Emirates, Armenia, Argentina, Australia, Barbados, Brazil, Bahamas, Belarus, Canada, Chile, China, Colombia, Costa Rica, Cape Verde, Egypt, Spain, Hong Kong SAR China, Honduras, Indonesia, Ireland, India, Iraq, Italy, Jamaica, Japan, Kiribati, St. Kitts &amp; Nevis, Republic of Korea, Cayman Islands, Kazakhstan, Mongolia, Mexico, Malaysia, Mozambique, Nigeria, Nicaragua, Nauru, Panama, Peru, French Polynesia, Papua New Guinea, Philippines, Russia, Somalia, Suriname, El Salvador, Thailand, Turkey, Tuvalu, Taiwan, Ukraine, United States, Uruguay, Uzbekistan, Venezuela, Vietnam
							', self::PLUGIN_ID)); ?></p>
						</div>
					</div>

					<div class="tab">
						<div class="wp-strpe-cart_setting-block border">
							<h2 class="title"><?php _e('Success & Error messages',self::PLUGIN_ID); ?></h2>
							<p><?php _e('You can set custom message for Success / Error checkout. If no custom message have set, default message will be displayed.',self::PLUGIN_ID); ?></p>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="success_header" class="wp-strpe-cart_fieldgroup__label"><?php _e('Success header',self::PLUGIN_ID); ?></label>
								<input id="success_header" name="success_header" class="wp-strpe-cart_fieldgroup__input" type="text" value="<?php echo nl2br($success_header); ?>">
								<p class="wp-strpe-cart_fieldgroup__default">
									<em><?php _e('Default',self::PLUGIN_ID); ?>：</em>
									<?php _e('Thanks for your order!',self::PLUGIN_ID); ?>
								</p>
							</div>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="success_message" class="wp-strpe-cart_fieldgroup__label"><?php _e('Success message',self::PLUGIN_ID); ?></label>
								<textarea id="success_message" name="success_message" class="wp-strpe-cart_fieldgroup__input textarea" rows="5"><?php echo nl2br($success_message); ?></textarea>
								<p class="wp-strpe-cart_fieldgroup__default">
									<em><?php _e('Default',self::PLUGIN_ID); ?>：</em>
									<?php _e('We just sent your receipt to your email address, and your items will be on their way shortly.',self::PLUGIN_ID); ?>
								</p>
							</div>

							<div class="wp-strpe-cart_fieldgroup">
								<label for="error_header" class="wp-strpe-cart_fieldgroup__label"><?php _e('Error header',self::PLUGIN_ID); ?></label>
								<input id="error_header" name="error_header" class="wp-strpe-cart_fieldgroup__input" type="text" value="<?php echo nl2br($error_header); ?>">
								<p class="wp-strpe-cart_fieldgroup__default">
									<em><?php _e('Default',self::PLUGIN_ID); ?>：</em>
									<?php _e('Oops, payment failed.',self::PLUGIN_ID); ?>
								</p>
							</div>
							<div class="wp-strpe-cart_fieldgroup">
								<label for="error_message" class="wp-strpe-cart_fieldgroup__label"><?php _e('Error message',self::PLUGIN_ID); ?></label>
								<textarea id="error_message" name="error_message" class="wp-strpe-cart_fieldgroup__input textarea" rows="5"><?php echo nl2br($error_message); ?></textarea>
								<p class="wp-strpe-cart_fieldgroup__default">
									<em><?php _e('Default',self::PLUGIN_ID); ?>：<?php _e('Please note that error message from STRIPE will be inserted below this message.',self::PLUGIN_ID); ?></em>
									<?php _e('It looks like your order could not be paid at this time. Please try again or try a different card.',self::PLUGIN_ID); ?>
								</p>
							</div>
						</div>
					</div>

				</div>
				<p><input type='submit' value='<?php _e('Save', self::PLUGIN_ID); ?>' class='button button-primary button-large'></p>
			</form>	
		</div>
<?php
	}

	static function wp_stripe_cart_admin() {
		wp_register_style( self::PLUGIN_ID.'-backend', plugin_dir_url(__FILE__).'asset/css/wp-stripe-cart-backend.css' );
		wp_enqueue_style( self::PLUGIN_ID.'-backend');
		wp_enqueue_script( self::PLUGIN_ID.'-backend', plugin_dir_url(__FILE__).'asset/js/wp-stripe-cart-backend.bundle.js',[],false,true);
	}

	static function save_settings(){

        // check credential 
        if (isset($_POST[self::CREDENTIAL_NAME]) && $_POST[self::CREDENTIAL_NAME]) {
            if (check_admin_referer(self::CREDENTIAL_ACTION, self::CREDENTIAL_NAME)) {

				//reset error.
				self::$error_message = [];
				self::$message = [];

                //Save
				$environment = filter_input(INPUT_POST, 'environment', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;

				$test_publishable_key = filter_input(INPUT_POST, 'test-publishable-key', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
				$test_secret_key = filter_input(INPUT_POST, 'test-secret-key', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
				
				$publishable_key = filter_input(INPUT_POST, 'publishable-key', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
				$secret_key = filter_input(INPUT_POST, 'secret-key', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;

				$payment_description = filter_input(INPUT_POST, 'payment_description', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;

				$filter_countries = filter_input(INPUT_POST, 'filter_countries', FILTER_SANITIZE_MAGIC_QUOTES, FILTER_SANITIZE_STRING) ?: NULL;
				$default_country = filter_input(INPUT_POST, 'default_country', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;

				// Tax
				$tax_rate = filter_input(INPUT_POST, 'tax_rate', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) ?: NULL;
				$charge_shipping = filter_input(INPUT_POST, 'charge_shipping', FILTER_VALIDATE_REGEXP, ['options'=> ['regexp'=>'/^[0-1]$/']]) ?: 0;
				$fixed_shipping_fee = filter_input(INPUT_POST, 'fixed_shipping_fee', FILTER_SANITIZE_NUMBER_FLOAT,FILTER_FLAG_ALLOW_FRACTION) ?: NULL;
				$varied_shipping_fee = filter_input(INPUT_POST, 'varied_shipping_fee', FILTER_SANITIZE_MAGIC_QUOTES,FILTER_SANITIZE_STRING) ?: NULL;
				
				//Messages
				$success_header = filter_input(INPUT_POST, 'success_header', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
				$success_message = filter_input(INPUT_POST, 'success_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
				$error_header = filter_input(INPUT_POST, 'error_header', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;
				$error_message = filter_input(INPUT_POST, 'error_message', FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?: NULL;

				if(trim($environment) == 'test'){
					if(!(bool)$test_publishable_key || !(bool)$test_secret_key){
						self::$error_message['test'] = sprintf(__('If you set to Test environment, %s are must be set.',self::PLUGIN_ID),__('Test Keys',self::PLUGIN_ID));
					}
				}

				if(trim($environment) == 'live'){
					if(!(bool)$publishable_key || !(bool)$secret_key){
						self::$error_message['live'] = sprintf(__('If you set to Live environment, %s are must be set.',self::PLUGIN_ID),__('Live Keys',self::PLUGIN_ID));
					}
				}

				if((bool)self::$error_message === false){
					update_option(self::PLUGIN_DB_PREFIX . 'environment', $environment);
					if((bool)$test_publishable_key && (bool)$test_secret_key){
						update_option(self::PLUGIN_DB_PREFIX . 'test_publishable_key', $test_publishable_key);
						update_option(self::PLUGIN_DB_PREFIX . 'test_secret_key', $test_secret_key);
					}
					if((bool)$publishable_key && (bool)$secret_key){
						update_option(self::PLUGIN_DB_PREFIX . 'publishable_key', $publishable_key);
						update_option(self::PLUGIN_DB_PREFIX . 'secret_key', $secret_key);
					}

					update_option(self::PLUGIN_DB_PREFIX . 'payment_description', $payment_description);

					if((bool)$default_country){
						update_option(self::PLUGIN_DB_PREFIX . 'default_country', $default_country);
					}


					if((bool)$filter_countries){
						update_option(self::PLUGIN_DB_PREFIX . 'filter_countries', $filter_countries);
					}


					if((bool)$tax_rate){
						update_option(self::PLUGIN_DB_PREFIX . 'tax_rate', $tax_rate);
					}

					update_option(self::PLUGIN_DB_PREFIX . 'charge_shipping', $charge_shipping);

					update_option(self::PLUGIN_DB_PREFIX . 'fixed_shipping_fee', $fixed_shipping_fee);

					if((bool)$varied_shipping_fee){
						update_option(self::PLUGIN_DB_PREFIX . 'varied_shipping_fee', $varied_shipping_fee);
					}

					if((bool)$success_header){
						update_option(self::PLUGIN_DB_PREFIX . 'success_header', $success_header);
					}

					if((bool)$success_message){
						update_option(self::PLUGIN_DB_PREFIX . 'success_message', $success_message);
					}

					if((bool)$error_header){
						update_option(self::PLUGIN_DB_PREFIX . 'error_header', $error_header);
					}

					if((bool)$error_message){
						update_option(self::PLUGIN_DB_PREFIX . 'error_message', $error_message);
					}
					self::$message['saved'] = __('WP Stripe Cart settings are successfully saved !',self::PLUGIN_ID);			
				} 
            }
        }
	}
	
	static function load_settings(){
		$tax_rate = get_option(self::PLUGIN_DB_PREFIX . "tax_rate");
		$default_country = get_option(self::PLUGIN_DB_PREFIX . "default_country");
		$filter_countries = get_option(self::PLUGIN_DB_PREFIX . "filter_countries");
		$charge_shipping = get_option(self::PLUGIN_DB_PREFIX . "charge_shipping");
		$fixed_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "fixed_shipping_fee");
		$varied_shipping_fee = get_option(self::PLUGIN_DB_PREFIX . "varied_shipping_fee");
		$payment_description = get_option(self::PLUGIN_DB_PREFIX . "payment_description");

		$json = [
			"default_country" => $default_country,
			"charge_shipping" => $charge_shipping,
			"filter_countries" => explode(",", $filter_countries),
			"fixed_shipping_fee" => $fixed_shipping_fee,
			"payment_description" => $payment_description,
			"labels" => [
				"update" => __('Update', self::PLUGIN_ID),
				"remove" => __('Remove', self::PLUGIN_ID),
				"quantity" => __('Quantity', self::PLUGIN_ID),
			]
		];

		//Output json.
		header("Content-Type: application/json; charset=utf-8");
		echo json_encode($json);
		exit();
	}
}
?>