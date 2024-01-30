<?php

class WP_Audit_Tool_Health_Check_Plugins_Known_Issues
{
	public static function results($info)
	{
		$plugins = self::detect_plugins_with_known_issues();

		$new_info = [];
		$new_info['pantheon-problematic-plugins'] = [
			'label' => __('Plugins with Known Issues on Pantheon', 'wp-audit-tool') . ' (' . count($plugins) . ')',
			'description' => __( 'WordPress plugins that are not supported and/or require workarounds on Pantheon. See: ', 'wp-audit-tool' ) . '<a href="https://docs.pantheon.io/plugins-known-issues">docs.pantheon.io/plugins-known-issues</a>',
			'fields' => self::format_plugin_list($plugins),
		];
		 // insert after "WordPress Plugins"
		array_splice( $info, 9, 0, $new_info );
		return $info;
	}

	public static function detect_plugins_with_known_issues()
	{
		$plugins = array_intersect_key(self::get_installed_plugins(), self::plugins_known_issues_data());
		foreach($plugins as $key => $plugin_data) {
			$plugins[$key] = array_merge($plugin_data, self::plugins_known_issues_data()[$key]);
		}

		return $plugins;
	}

	public static function format_plugin_list($plugins = [])
	{
		$fields = [];

		if (empty($plugins)) {
			$fields[] = [
				'label' => '',
				'value' => 'No plugins with known issues detected.',
			];

			return $fields;
		}

		foreach ($plugins as $plugin => $plugin_data) {
			$actives[$plugin] = $plugin_data['active'];
			$severities[$plugin] = $plugin_data['severity'];
		}
		
		// Sort the actives first, then sort by severity
		array_multisort($actives, SORT_DESC, $severities, SORT_ASC, $plugins);
		
		foreach ($plugins as $plugin => $plugin_data) {
			$fields[$plugin] = [
				'label' => $plugin_data['Name'] . ' (' . $plugin_data['Version'] . ')' . ' - ' . ($plugin_data['active'] ? __('Active', 'wp-audit-tool') : __('Inactive', 'wp-audit-tool')),
				'value' => sprintf('%s %s | %s: %s', $plugin_data['Name'], self::formatted_severity_string($plugin_data['severity']), __('More Info', 'wp-audit-tool'), $plugin_data['url']),
				'debug' => [
					'slug' => $plugin,
					'title' => $plugin_data['title'],
					'url' => $plugin_data['url'],
					'severity' => $plugin_data['severity'],
					'active' => $plugin_data['active'],
				],
			];
		}

		return $fields;
	}

	private static function formatted_severity_string($severity = 3) {
		switch ($severity) {
			case 1:
				return __('will not work on Pantheon', 'wp-audit-tool');
			case 2:
				return __('is not recommended on Pantheon but a workaround is possible', 'wp-audit-tool');
			case 3:
			default:
				return __('works on Pantheon but has some caveats and workarounds', 'wp-audit-tool');
		}
	}

	private static function get_installed_plugins()
	{
		$installed_plugins = [];
		$plugin_data = get_plugins();
		$active_plugins = get_option('active_plugins', []);

		foreach ($plugin_data as $key => $plugin_data) {
			$plugin_data['active'] = in_array($key, $active_plugins);
			$installed_plugin_key = str_contains($key, '/') ? dirname($key) : str_replace('.php', '', $key);
			$installed_plugins[$installed_plugin_key] = $plugin_data;
		}

		return $installed_plugins;
	}

	/**
	 * See https://docs.pantheon.io/plugins-known-issues
	 * 
	 * Severity Levels:
	 * 1 - Won't work on Pantheon
	 * 2 - Not recommended on Pantheon but workaround possible
	 * 3 - Works on Pantheon but with some caveats/workarounds
	 */
	public static function plugins_known_issues_data()
	{
		return [
			'accelerated-mobile-pages' => [
				'slug' => 'accelerated-mobile-pages',
				'title' => 'AMP for WP – Accelerated Mobile Pages',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#amp-for-wp--accelerated-mobile-pages',
				'severity' => 3,
			],
			'adthrive-ads' => [
				'slug' => 'adthrive-ads',
				'title' => 'AdThrive Ads',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#adthrive-ads',
				'severity' => 1,
			],
			'all-in-one-wp-migration' => [
				'slug' => 'all-in-one-wp-migration',
				'title' => 'All-in-One WP Migration',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#all-in-one-wp-migration',
				'severity' => 1,
			],
			'autoptimize' => [
				'slug' => 'autoptimize',
				'title' => 'Autoptimize',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#autoptimize',
				'severity' => 3,
			],
			'batcache' => [
				'slug' => 'batcache',
				'title' => 'Batcache',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#caching-plugins',
				'severity' => 1,
			],
			'better-search-replace' => [
				'slug' => 'better-search-replace',
				'title' => 'Better Search And Replace',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#better-search-and-replace',
				'severity' => 2,
			],
			'bookly' => [
				'slug' => 'bookly',
				'title' => 'Bookly',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#bookly',
				'severity' => 1,
			],
			'broken-link-checker' => [
				'slug' => 'broken-link-checker',
				'title' => 'Broken Link Checker',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#broken-link-checker',
				'severity' => 3,
			],
			'coming-soon' => [
				'slug' => 'coming-soon',
				'title' => 'Coming Soon',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#coming-soon',
				'severity' => 2,
			],
			'constant-contact-forms' => [
				'slug' => 'constant-contact-forms',
				'title' => 'Constant Contact Forms',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#constant-contact-forms',
				'severity' => 2,
			],
			'contact-form-7' => [
				'slug' => 'contact-form-7',
				'title' => 'Contact Form 7',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#contact-form-7',
				'severity' => 2,
			],
			'disable-rest-api-and-require-jwt-oauth-authentication' => [
				'slug' => 'disable-rest-api-and-require-jwt-oauth-authentication',
				'title' => 'Disable REST API and Require JWT / OAuth Authentication',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#disable-rest-api-and-require-jwt--oauth-authentication',
				'severity' => 3,
			],
			'divi' => [
				'slug' => 'divi',
				'title' => 'Divi WordPress Theme & Visual Page Builder',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#divi-wordpress-theme--visual-page-builder',
				'severity' => 3,
			],
			'elementor' => [
				'slug' => 'elementor',
				'title' => 'Elementor',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#elementor',
				'severity' => 3,
			],
			'event-espresso' => [
				'slug' => 'event-espresso',
				'title' => 'Event Espresso',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#event-espresso',
				'severity' => 3,
			],
			'official-facebook-pixel' => [
				'slug' => 'official-facebook-pixel',
				'title' => 'Facebook for WordPress (official-facebook-pixel)',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#facebook-for-wordpress-official-facebook-pixel',
				'severity' => 2,
			],
			'facetwp' => [
				'slug' => 'facetwp',
				'title' => 'FacetWP',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#facetwp',
				'severity' => 2,
			],
			'fast-velocity-minify' => [
				'slug' => 'fast-velocity-minify',
				'title' => 'Fast Velocity Minify',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#fast-velocity-minify',
				'severity' => 2,
			],
			'h5p' => [
				'slug' => 'h5p',
				'title' => 'H5P',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#h5p',
				'severity' => 2,
			],
			'hm-require-login' => [
				'slug' => 'hm-require-login',
				'title' => 'HM Require Login',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#hm-require-login',
				'severity' => 1,
			],
			'hummingbird-performance' => [
				'slug' => 'hummingbird-performance',
				'title' => 'Hummingbird',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#hummingbird',
				'severity' => 3,
			],
			'hyperdb' => [
				'slug' => 'hyperdb',
				'title' => 'HyperDB',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#hyperdb',
				'severity' => 1,
			],
			'infinitewp' => [
				'slug' => 'infinitewp',
				'title' => 'InfiniteWP',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#infinitewp',
				'severity' => 1,
			],
			'instashow' => [
				'slug' => 'instashow',
				'title' => 'Instashow',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#instashow',
				'severity' => 1,
			],
			'better-wp-security' => [
				'slug' => 'better-wp-security',
				'title' => 'Solid Security – Password, Two Factor Authentication, and Brute Force Protection (Previously: iThemes Security)',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#ithemes-security',
				'severity' => 2,
			],
			'jetpack' => [
				'slug' => 'jetpack',
				'title' => 'Jetpack',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#jetpack',
				'severity' => 2,
			],
			'live-weather-station' => [
				'slug' => 'live-weather-station',
				'title' => 'Live Weather Station',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#weather-station',
				'severity' => 1,
			],
			'lj-maintenance-mode' => [
				'slug' => 'lj-maintenance-mode',
				'title' => 'lj-maintenance-mode',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#lj-maintenance-mode',
				'severity' => 2,
			],
			'worker' => [
				'slug' => 'worker',
				'title' => 'ManageWP Worker',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#managewp-worker',
				'severity' => 2,
			],
			'monarch' => [
				'slug' => 'monarch',
				'title' => 'Monarch Social Sharing            ',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#monarch-social-sharing',
				'severity' => 2,
			],
			'wp-newrelic' => [
				'slug' => 'wp-newrelic',
				'title' => 'New Relic Reporting for WordPress',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#new-relic-reporting-for-wordpress',
				'severity' => 1,
			],
			'nextgen-gallery' => [
				'slug' => 'nextgen-gallery',
				'title' => 'NextGEN Gallery	',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#assumed-write-access',
				'severity' => 3,
			],
			'nitropack' => [
				'slug' => 'nitropack',
				'title' => 'Nitropack',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#assumed-write-access',
				'severity' => 3,
			],
			'object-sync-for-salesforce' => [
				'slug' => 'object-sync-for-salesforce',
				'title' => 'Object Sync for Salesforce',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#object-sync-for-salesforce',
				'severity' => 3,
			],
			'one-click-demo' => [
				'slug' => 'one-click-demo',
				'title' => 'One Click Demo Import',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#one-click-demo-import',
				'severity' => 3,
			],
			'popup-builder' => [
				'slug' => 'popup-builder',
				'title' => 'Popup Builder – Responsive WordPress Pop up – Subscription & Newsletter',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#popup-builder--responsive-wordpress-pop-up--subscription--newsletter',
				'severity' => 3,
			],
			'polylang' => [
				'slug' => 'polylang',
				'title' => 'PolyLang',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#polylang',
				'severity' => 3,
			],
			'posts-to-posts' => [
				'slug' => 'posts-to-posts',
				'title' => 'Posts 2 Posts',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#posts-2-posts',
				'severity' => 3,
			],
			'query-monitor' => [
				'slug' => 'query-monitor',
				'title' => 'query-monitor',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#query-monitor',
				'severity' => 2,
			],
			'redirection' => [
				'slug' => 'redirection',
				'title' => 'redirection',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#redirection',
				'severity' => 2,
			],
			'sendgrid-email-delivery-simplified' => [
				'slug' => 'sendgrid-email-delivery-simplified',
				'title' => 'SendGrid Subscription Widget',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#sendgrid-email-delivery-simplified',
				'severity' => 1,
			],
			'site24x7-rum' => [
				'slug' => 'site24x7-rum',
				'title' => 'Site24x7',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#site24x7',
				'severity' => 2,
			],
			'slider-revolution' => [
				'slug' => 'slider-revolution',
				'title' => 'Slider Revolution',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#slider-revolution',
				'severity' => 3,
			],
			'smartcrawl-wordpress-seo' => [
				'slug' => 'smartcrawl-wordpress-seo',
				'title' => 'SmartCrawl Pro',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#smartcrawl-pro',
				'severity' => 2,
			],
			'smush' => [
				'slug' => 'smush',
				'title' => 'Smush Pro',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#smush-pro',
				'severity' => 1,
			],
			'timthumb' => [
				'slug' => 'timthumb',
				'title' => 'Timthumb',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#timthumb',
				'severity' => 1,
			],
			'tubepress' => [
				'slug' => 'tubepress',
				'title' => 'TubePress Pro',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#tubepress-pro',
				'severity' => 3,
			],
			'unbounce' => [
				'slug' => 'unbounce',
				'title' => 'Unbounce Landing Pages',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#unbounce-landing-pages',
				'severity' => 3,
			],
			'unloq' => [
				'slug' => 'unloq',
				'title' => 'UNLOQ Two Factor Authentication (2FA)',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#unloq-two-factor-authentication-2fa',
				'severity' => 2,
			],
			'unyson' => [
				'slug' => 'unyson',
				'title' => 'Unyson Theme Framework',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#unyson-theme-framework',
				'severity' => 3,
			],
			'updraftplus' => [
				'slug' => 'updraftplus',
				'title' => 'Updraft / Updraft Plus Backup',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#updraft--updraft-plus-backup',
				'severity' => 1,
			],
			'visualcomposer' => [
				'slug' => 'visualcomposer',
				'title' => 'Visual Composer: Website Builder',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#visual-composer-website-builder',
				'severity' => 3,
			],
			'webp-express' => [
				'slug' => 'webp-express',
				'title' => 'WebP Express',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#webp-express',
				'severity' => 3,
			],
			'w3-total-cache' => [
				'slug' => 'w3-total-cache',
				'title' => 'W3 Total Cache',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#caching-plugins',
				'severity' => 1,
			],
			'woocommerce' => [
				'slug' => 'woocommerce',
				'title' => 'WooCommerce',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#woocommerce',
				'severity' => 3,
			],
			'woozone' => [
				'slug' => 'woozone',
				'title' => 'WooZone',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#woozone',
				'severity' => 3,
			],
			'wordfence' => [
				'slug' => 'wordfence',
				'title' => 'WordFence',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wordfence',
				'severity' => 3,
			],
			'wpdownloadmanager' => [
				'slug' => 'wpdownloadmanager',
				'title' => 'WordPress Download Manager',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wordpress-download-manager',
				'severity' => 2,
			],
			'wordpress-social-login' => [
				'slug' => 'wordpress-social-login',
				'title' => 'WordPress Social Login',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wordpress-social-login',
				'severity' => 2,
			],
			'wp-reset' => [
				'slug' => 'wp-reset',
				'title' => 'WP Reset',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wp-reset',
				'severity' => 2,
			],
			'wp-rocket' => [
				'slug' => 'wp-rocket',
				'title' => 'WP Rocket',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wp-rocket',
				'severity' => 2,
			],
			'wpbakery' => [
				'slug' => 'wpbakery',
				'title' => 'WPBakery: Page Builder',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wpbakery-page-builder',
				'severity' => 3,
			],
			'wpfront-notification-bar' => [
				'slug' => 'wpfront-notification-bar',
				'title' => 'WPFront Notification Bar',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wpfront-notification-bar',
				'severity' => 3,
			],
			'wpallimport' => [
				'slug' => 'wpallimport',
				'title' => 'WP All Import / Export',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wp-all-import--export',
				'severity' => 3,
			],
			'wp-ban' => [
				'slug' => 'wp-ban',
				'title' => 'WP-Ban',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wp-ban',
				'severity' => 2,
			],
			'wp-fastest-cache' => [
				'slug' => 'wp-fastest-cache',
				'title' => 'wp-fastest-cache',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#caching-plugins',
				'severity' => 1,
			],
			'wp-migrate-db' => [
				'slug' => 'wp-migrate-db',
				'title' => 'WP Migrate DB',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wp-migrate-db',
				'severity' => 3,
			],
			'wp-rocket' => [
				'slug' => 'wp-rocket',
				'title' => 'wp-rocket',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#caching-plugins',
				'severity' => 1,
			],
			'wpml' => [
				'slug' => 'wpml',
				'title' => 'WPML - The WordPress Multilingual Plugin',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wpml---the-wordpress-multilingual-plugin',
				'severity' => 3,
			],
			'wp-super-cache' => [
				'slug' => 'wp-super-cache',
				'title' => 'WP Super Cache',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#caching-plugins',
				'severity' => 1,
			],
			'wp-phpmyadmin-extension' => [
				'slug' => 'wp-phpmyadmin-extension',
				'title' => 'WP phpMyAdmin',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#wp-phpmyadmin',
				'severity' => 1,
			],
			'yith-woocommerce-request-a-quote' => [
				'slug' => 'yith-woocommerce-request-a-quote',
				'title' => 'YITH WooCommerce Request a Quote',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#yith-woocommerce-extensions-with-mpdf-library',
				'severity' => 3,
			],
			'yith-woocommerce-pdf-invoice' => [
				'slug' => 'yith-woocommerce-pdf-invoice',
				'title' => 'YITH WooCommerce PDF Invoices & Packing Slips',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#yith-woocommerce-extensions-with-mpdf-library',
				'severity' => 3,
			],
			'yith-woocommerce-gift-cards' => [
				'slug' => 'yith-woocommerce-gift-cards',
				'title' => 'YITH WooCommerce Gift Cards',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#yith-woocommerce-extensions-with-mpdf-library',
				'severity' => 3,
			],
			'wordpress-seo' => [
				'slug' => 'wordpress-seo',
				'title' => 'Yoast SEO',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#yoast-seo',
				'severity' => 3,
			],
			'indexables' => [
				'slug' => 'indexables',
				'title' => 'Yoast Indexables',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#yoast-indexables',
				'severity' => 3,
			],
			'yotuwp-easy-youtube-embed' => [
				'slug' => 'yotuwp-easy-youtube-embed',
				'title' => 'YotuWP Easy YouTube Embed',
				'url' => 'https://docs.pantheon.io/plugins-known-issues#yotuwp-easy-youtube-embed',
				'severity' => 3,
			],
		];
	}
}
