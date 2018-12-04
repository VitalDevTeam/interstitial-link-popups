<?php
/*
	Plugin Name: Interstitial Link Pop-ups
	Description: Adds URL links that provide the user with an interstitial pop-up message that they are leaving this website. Requires installation of Advanced Custom Fields.
	Version: 1.0.0
	Author: Vital
	Author URI: https://vtldesign.com
	Text Domain: vital
*/

if (! defined('ABSPATH')) {
	exit;
}

class Interstitial_Link_Popups {

	private $plugin_path;
	private $plugin_url;
	private $plugin_version;

	public function __construct() {

		$this->plugin_path = plugin_dir_path(__FILE__);
		$this->plugin_url  = plugin_dir_url(__FILE__);
		$this->version = '1.0.0';

		require $this->plugin_path . 'admin.php';

		if (function_exists('acf')) {
			add_filter('plugin_action_links_' . plugin_basename(__FILE__), [$this, 'add_action_link']);
			add_action('wp_enqueue_scripts', [$this, 'enqueue_scripts']);
			add_action('wp_enqueue_scripts', [$this, 'enqueue_styles']);
			add_action('wp_footer', [$this, 'add_popup']);
		} else {
			add_action('admin_notices', [$this, 'no_acf_notice']);
		}
	}

	public function no_acf_notice() {
		echo '<div class="error notice"><p><strong>Interstitial Link Pop-ups</strong> requires an active installation of Advanced Custom Fields. Please install ACF or deactivate this plugin.</p></div>';
	}

	public function add_action_link($links) {
		$custom_link = [
			'<a href="' . admin_url('admin.php?page=interstitials-link-popups') . '">Settings</a>',
		];

		return array_merge($custom_link, $links);
	}

	public function enqueue_scripts() {

		wp_enqueue_script(
			'interstitials_js',
			$this->plugin_url . 'assets/js/interstitial-link-popups.min.js',
			false,
			$this->version,
			true
		);

		wp_localize_script('interstitials_js', 'Interstitials', [
			'rows' => get_field('interstitials_links', 'option'),
		]);
	}

	public function enqueue_styles() {
		wp_enqueue_style(
			'interstitials_css',
			$this->plugin_url . 'assets/css/interstitial-link-popups.min.css',
			false,
			$this->version
		);
	}

	public function add_popup() {
		$interstitials = get_field('interstitials_links', 'option');
		$content = get_field('interstitials_popup_content', 'option');

		if ($content && $interstitials) {

			$content = str_replace('{destination}', '<span id="int-popup-destination" class="int-popup-destination"></span>', $content);
			$content = str_replace('{countdown}', '<span id="int-popup-countdown"></span>', $content);
			$close = '<button type="button" class="int-popup-close" aria-label="Cancel and return to ' . get_bloginfo('name') . '"><span>Cancel and return to ' . get_bloginfo('name') . '</span></button>';
			$delay = get_field('interstitials_redirect_delay', 'option');

			echo <<<EOT
<div id="int-popup" class="int-popup" data-redirect="{$delay}">
	<div class="int-popup-outer-wrapper">
		<div class="int-popup-inner-wrapper">
			<div class="int-popup-container">
				<div id="int-popup-content" class="int-popup-content entry" role="dialog" aria-modal="true" aria-hidden="true" aria-label="External Redirect Link Notice">
					{$content}
					<p class="int-popup-content-close">{$close}</p>
				</div>
				<div class="int-popup-modal-close">{$close}</div>
			</div>
		</div>
	</div>
</div>
<div id="int-popup-overlay" class="int-popup-overlay"></div>
EOT;
		}
	}
}

new Interstitial_Link_Popups();
