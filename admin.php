<?php
if (! defined('ABSPATH')) {
	exit;
}

class Interstitials_Options_Page {

	public function __construct() {

		if (function_exists('acf')) {
			add_action('init', [$this, 'add_acf']);
		}
	}

	public function add_acf() {

		acf_add_options_page([
			'page_title' => 'Interstitial Link Pop-ups',
			'menu_title' => 'Interstitials',
			'menu_slug'  => 'interstitials-link-popups',
			'position'   => 50,
			'icon_url'   => 'dashicons-admin-links',
			'capability' => 'edit_posts',
			'redirect'   => false,
		]);

		acf_add_local_field_group([
			'key'                   => 'group_interstitials',
			'title'                 => 'Interstitials',
			'fields'                => [
				[
					'key'               => 'group_interstitials_field_msg',
					'label'             => '',
					'name'              => '',
					'type'              => 'message',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'message'           => 'These links provide a user with an interstitial pop-up message that they are leaving this website before automatically redirecting them to the destination URL. The pop-up will be automatically applied to every link that matches the interstitial\'s destination URL.',
					'new_lines'         => 'wpautop',
					'esc_html'          => 0,
				],
				[
					'key'               => 'group_interstitials_field_int_links',
					'label'             => 'Interstitials',
					'name'              => 'interstitials_links',
					'type'              => 'repeater',
					'instructions'      => '',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'collapsed'         => '',
					'min'               => 0,
					'max'               => 0,
					'layout'            => 'table',
					'button_label'      => 'Add Interstitial',
					'sub_fields'        => [
						[
							'key'               => 'interstitials_links_destination',
							'label'             => 'Destination URL',
							'name'              => 'destination',
							'type'              => 'url',
							'instructions'      => 'The URL must <strong>exactly</strong> match link URL. Pay careful attention to trailing slashes or spaces.',
							'required'          => 1,
							'conditional_logic' => 0,
							'wrapper'           => [
								'width' => '85',
								'class' => '',
								'id'    => '',
							],
							'default_value'     => '',
							'placeholder'       => 'https://example.com/destination-page/',
						],
						[
							'key'               => 'interstitials_links_status',
							'label'             => 'Status',
							'name'              => 'status',
							'type'              => 'true_false',
							'instructions'      => 'Turn pop-up on/off',
							'required'          => 0,
							'conditional_logic' => 0,
							'wrapper'           => [
								'width' => '15',
								'class' => '',
								'id'    => '',
							],
							'message'           => '',
							'default_value'     => 1,
							'ui'                => 1,
							'ui_on_text'        => 'On',
							'ui_off_text'       => 'Off',
						],
					],
				],
				[
					'key'               => 'group_interstitials_field_redirect_delay',
					'label'             => 'Redirect Delay',
					'name'              => 'interstitials_redirect_delay',
					'type'              => 'number',
					'instructions'      => 'Number of seconds to wait before user is redirected to the destination URL. Minimum of 5 seconds required.',
					'required'          => 1,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'default_value'     => 5,
					'placeholder'       => '',
					'prepend'           => '',
					'append'            => '',
					'min'               => 5,
					'max'               => '',
					'step'              => 1,
				],
				[
					'key'               => 'group_interstitials_field_popup_content',
					'label'             => 'Pop-Up Content',
					'name'              => 'interstitials_popup_content',
					'type'              => 'wysiwyg',
					'instructions'      => 'These tags are available to use within your text:<br><br><code>{destination}</code> Displays the interstitial\'s destination URL<br><br><code>{countdown}</code> Displays the redirect delay countdown in seconds',
					'required'          => 0,
					'conditional_logic' => 0,
					'wrapper'           => [
						'width' => '',
						'class' => '',
						'id'    => '',
					],
					'default_value'     => '',
					'tabs'              => 'all',
					'toolbar'           => 'full',
					'media_upload'      => 1,
					'delay'             => 0,
				],
			],
			'location'              => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => 'interstitials-link-popups',
					],
				],
			],
			'menu_order'            => 0,
			'position'              => 'normal',
			'style'                 => 'seamless',
			'label_placement'       => 'top',
			'instruction_placement' => 'label',
			'hide_on_screen'        => '',
			'active'                => 1,
			'description'           => '',
		]);

	}
}

$plugin_options_page = new Interstitials_Options_Page();
