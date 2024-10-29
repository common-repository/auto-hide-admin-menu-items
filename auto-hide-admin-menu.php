<?php
/*
Plugin Name: Useful Admin Menu
Plugin URI:
Description: The plugin automatically hides unused Admin Menu items and adds search box for menu items
Version: 2.0
Author: ioannup
Author URI: https://www.upwork.com/freelancers/~0165d3dc4b2ffbbd7d
License: GPLv2
Requires at least: 5.0
Tested up to: 6.2
*/

if ( ! class_exists( 'AutoHideAdminMenu' ) ) {
	class AutoHideAdminMenu {
		private static $user_id;

		public function __construct() {
			add_action( 'init', [ $this, 'handle_post' ] );
			add_action( 'admin_menu', [ $this, 'add_menu' ], PHP_INT_MAX );
			add_action( 'network_admin_menu', [ $this, 'add_menu' ], PHP_INT_MAX );
			add_action( 'admin_enqueue_scripts', [ $this, 'include_js_and_css' ] );
			add_filter( 'add_menu_classes', [ $this, 'change_menu_order' ], PHP_INT_MAX );
			add_action( 'wp_ajax_aham_click_on_menu', [ $this, 'click_on_menu' ] );
			add_filter( 'plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 4 );
			add_filter( 'network_admin_plugin_action_links', array( $this, 'add_plugin_action_links' ), 10, 4 );
		}

		public function add_menu() {
			add_menu_page( 'Auto Hide Menu',  __( 'Auto Hide Menu' ), 'read', 'aham_settings', [ $this, 'render_main_page' ], 'dashicons-hidden' );

			$settings = self::get_settings();
			// don't show `Show more` if plugin is disabled
			if ( $settings['enable']  ) {
				add_menu_page( 'show more', __( 'show more' ), 'read', 'aham_show_more', '', 'dashicons-arrow-down', PHP_INT_MAX );
			}
		}

		private static function get_user_id() {
			if ( is_null( self::$user_id ) ) {
				self::$user_id = get_current_user_id();
			}

			return self::$user_id;
		}

		public function include_js_and_css() {
			$settings = self::get_settings();
			$settings['showText'] = esc_html__( 'show more' );
			$settings['hideText'] = esc_html__( 'hide more' );

			wp_enqueue_script( 'aham_admin_script', plugin_dir_url(__FILE__) . 'assets/js/admin-menu.js', array(), '2.0' );
			wp_enqueue_style( 'aham_admin_styles', plugin_dir_url(__FILE__) . 'assets/css/styles.css', array(), '2.0' );
			wp_localize_script( 'aham_admin_script', 'ahamSettings', $settings );

			$page = filter_input( INPUT_GET, 'page' );
			if ( 'aham_settings' === $page ) {
				wp_enqueue_style( 'wp-jquery-ui-dialog' );
				wp_enqueue_script( 'aham_admin_popups', plugin_dir_url(__FILE__) . 'assets/js/admin-popups.js', [ 'jquery-ui-dialog' ], '2.0' );
			}
		}

		/**
		 * Adds custom links on plugin page
		 */
		public function add_plugin_action_links( $actions, $plugin_file, $plugin_data, $context ) {
			if ( 'auto-hide-admin-menu-items/auto-hide-admin-menu.php' === $plugin_file ) {
				$admin_url = admin_url( 'admin.php' );
				$settings_url = add_query_arg( 'page', 'aham_settings', $admin_url );
				$links = [
					'settings' => '<a href="'. $settings_url .'">' . esc_html__( 'Settings' ) . '</a>',
				];

				$actions = array_merge( $links, $actions );
			}

			return $actions;
		}

		/**
		 * Render main page for settings
		 */
		public function render_main_page() {
			$settings = self::get_settings();

			require_once dirname( __FILE__ ) . '/inc/settings.php';
		}

		/**
		 * Save settings
		 */
		public function handle_post() {
			$page = filter_input( INPUT_GET, 'page' );
			if ( 'aham_settings' !== $page ) {
				return;
			}
			$save_settings = filter_input( INPUT_POST, 'save_settings' );
			if ( !is_null( $save_settings ) ) {
				self::save_settings();
			}

			$reset_settings = filter_input( INPUT_POST, 'reset_settings' );
			if ( !is_null( $reset_settings ) ) {
				self::reset_settings();
			}

			$reset_clicks = filter_input( INPUT_POST, 'reset_clicks' );
			if ( !is_null( $reset_clicks ) ) {
				self::reset_clicks();
			}
		}

		private static function reset_clicks() {
			delete_user_meta( self::get_user_id(), 'aham_auto_hidden_menu_items' );
		}

		private static function reset_settings() {
			delete_user_meta( self::get_user_id(), 'aham_settings' );
		}

		private static function save_settings() {
			if ( ! check_admin_referer( 'save-settings' ) ) {
				return;
			}
			$old_settings = get_user_meta( self::get_user_id(), 'aham_settings' );

			$hide_in = filter_input( INPUT_POST, 'hide_without_clicks_in_x_days', FILTER_VALIDATE_INT );
			if ( is_int( $hide_in ) && !empty( $hide_in ) ) {
				$new_settings['hide_without_clicks_in_x_days'] = $hide_in;
			}

			$start_in = filter_input( INPUT_POST, 'start_in_x_days', FILTER_VALIDATE_INT );
			if ( is_int( $start_in ) ) {
				$new_settings['start_in_x_days'] = $start_in;
			}
			$show_updates = filter_input( INPUT_POST, 'show_menu_items_with_updates', FILTER_VALIDATE_INT );
			$show_search = filter_input( INPUT_POST, 'show_menu_search', FILTER_VALIDATE_INT );
			$new_settings['show_menu_items_with_updates'] = $show_updates ? 1 : 0;
			$new_settings['show_menu_search'] = $show_search ? 1 : 0;
			$enable = filter_input( INPUT_POST, 'enable', FILTER_VALIDATE_INT );
			$new_settings['enable'] = $enable ? 1 : 0;
			$always_show = filter_input( INPUT_POST, 'always_show' );
			$always_show = json_decode( $always_show );
			$new_settings['always_show'] = json_last_error() === JSON_ERROR_NONE ? $always_show : [];
			$always_hide = filter_input( INPUT_POST, 'always_hide' );
			$always_hide = json_decode( $always_hide );
			$new_settings['always_hide'] = json_last_error() === JSON_ERROR_NONE ? $always_hide : [];

			if ( ! empty( $new_settings ) ) {
				if ( ! empty( $old_settings ) ) {
					$new_settings = array_merge( $old_settings, $new_settings );
				}
				update_user_meta( self::get_user_id(), 'aham_settings', $new_settings );
			}

		}

		private static function get_default_settings() {
			return apply_filters( 'aham_default_settings', [
				'enable' => 1,
				'start_in_x_days' => 0,
				'hide_without_clicks_in_x_days' => 7,
				'show_menu_items_with_updates' => 1,
				'show_menu_search' => 1,
				'always_show' => [],
				'always_hide' => [],
			] );
		}

		private static function get_settings() {
			$default = self::get_default_settings();

			$saved = get_user_meta( self::get_user_id(), 'aham_settings', true );
			if ( !is_array( $saved ) ) {
				$saved = [];
			}

			$settings = array_merge( $default, $saved );

			return apply_filters( 'aham_settings', $settings );
		}

		//todo: add cron for removing old clicks

		/**
		 * Get admin menu items which never can be hidden
		 *
		 * @return array
		 */
		private static function get_exception() {
			$menus = self::get_shown_menu_item();
			array_push( $menus, 'toplevel_page_aham_show_more' );
			$menus = array_unique( $menus );
			// Never hide `show more` menu
			return apply_filters( 'aham_exceptional_manu_items', $menus );
		}

		private static function set_first_time( $menu ) {
			$auto_hidden = self::get_auto_hidden_menu_option();
			$update      = false;

			foreach ( $menu as $item ) {
				$id = ! empty( $item[5] ) ? $item[5] : '';
				if ( $id && empty( $auto_hidden[ $id ]['first_time'] ) ) {
					$auto_hidden[ $id ]['first_time'] = time();
					$update                           = true;
				}
			}
			if ( $update ) {
				update_user_meta( self::get_user_id(), 'aham_auto_hidden_menu_items', $auto_hidden );
			}
		}

		public function change_menu_order( $menu ) {
			$settings = self::get_settings();

			// do nothing if plugin is disabled
			if ( ! $settings['enable']  ) {
				return $menu;
			}

			self::set_first_time( $menu );

			$hidden = self::get_completely_hidden_menu_item();
			$exceptions = self::get_exception();

//			$new_hidden_item_menu = [];
			$original_menu = $menu;

			$current_page = add_query_arg( [] );

			foreach ( $menu as $key => $item ) {
				$slug = $item[2];
				$id   = ! empty( $item[5] ) ? $item[5] : '';

				//skip exceptions
				if ( in_array( $id, $exceptions, true ) ) {
					continue;
				}
				// skip if we go to the current page directly ( not via menu )
				if ( false !== strpos( $current_page, $slug ) ) {
					$this->add_click( $id );
					continue;
				}
				// skip if there is an update label
				if ( $settings['show_menu_items_with_updates'] && ( strpos( $item[0], 'update-plugins' ) || strpos( $item[0],  'awaiting-mod' ) ) && ! strpos( $item[0], 'count-0' ) ) {
					continue;
				}
				//skip separators
				if ( 0 === strpos( $slug, 'separator' ) ) {
					// remove separator if it's first menu item
					$first_item = array_slice( $menu, 0, 1 );
					if ( ! empty( $is_previous_separator )  || !empty( $first_item[0][2] ) && $first_item[0][2] === $slug ) {
						unset( $menu[ $key ] );
					}
					$is_previous_separator = true;
					continue;
				}

				if ( in_array( $id, $hidden, true ) ) {
					//Add hidden classes
					$item[4] .= ' hidden aham_hidden';
//					$new_hidden_item_menu[] = $item;
//					unset( $menu[ $key ] );
					$menu[ $key ] = $item;
					$hidden_item = true;
				} else {
					$is_previous_separator = false;
				}
			}

			// remove `show more` menu either only this item is available or noone item is hidden and do nothing with menu
			if ( 1 === count( $menu ) || empty( $hidden_item ) ) {
				unset( $original_menu[ PHP_INT_MAX ] );

				return $original_menu;
			}

//			if ( $new_hidden_item_menu ) {
//				$menu = array_merge(
//					$menu,
//					$new_hidden_item_menu
//				);
//			}

			return $menu;
		}

		/**
		 * AJAX handler
		 */
		public function click_on_menu() {
			$id = filter_input( INPUT_POST, 'id' );

			if ( ! $id ) {
				return;
			}

			$this->add_click( $id );

			wp_send_json_success();
		}

		private function add_click( $id ) {
			$saved = self::get_auto_hidden_menu_option();

			$saved[ $id ]['last_time'] = time();
			if ( empty( $saved[ $id ]['first_time'] ) ) {
				$saved[ $id ]['first_time'] = time();
			}

			update_user_meta( self::get_user_id(), 'aham_auto_hidden_menu_items', $saved );
		}

		/**
		 * Get completely hidden menu according settings
		 *
		 * @return array
		 */
		private static function get_completely_hidden_menu_item() {
			$auto_hidden = self::get_auto_hidden_menu_item();
			$hidden = self::get_hidden_menu_item();
			$shown = self::get_shown_menu_item();

			$all_hidden = array_diff( array_merge( $auto_hidden, $hidden ), $shown );

			return $all_hidden;
		}

		/**
		 * Get AUTO hidden admin menu option
		 *
		 * @return array
		 */
		private static function get_auto_hidden_menu_option() {
			$option = get_user_meta( self::get_user_id(), 'aham_auto_hidden_menu_items', true );
			if ( ! $option ) {
				$option = [];
			}

			return $option;
		}

		/**
		 * Get AUTO hidden admin menu slugs
		 *
		 * @return array
		 */
		private static function get_auto_hidden_menu_item() {
			$items = self::get_auto_hidden_menu_option();

			$items = array_filter( $items, [ get_class(), 'filter_by_time' ] );

			$items = array_keys( $items );

			return $items;
		}

		public static function filter_by_time( $var ) {
			$settings = self::get_settings();

			$start_hidding = !empty( $var['first_time'] ) && ( time() - $var['first_time'] > DAY_IN_SECONDS * $settings['start_in_x_days'] );

			$should_hide = empty( $var['last_time'] ) || ( time() - $var['last_time'] > DAY_IN_SECONDS * $settings['hide_without_clicks_in_x_days'] );

			return $start_hidding && $should_hide;
		}

		/**
		 * Get hidden admin menu items from settings
		 *
		 * @return array
		 */
		private static function get_hidden_menu_item() {
			$items = [];
			$settings = self::get_settings();
			if ( ! empty( $settings['always_hide'] ) && is_array( $settings['always_hide'] ) ) {
				$items = $settings['always_hide'];
			}

			return $items;
		}

		/**
		 * Get shown admin menu items from settings
		 *
		 * @return array
		 */
		private static function get_shown_menu_item() {
			$items = [];
			$settings = self::get_settings();
			if ( ! empty( $settings['always_show'] ) && is_array( $settings['always_show'] ) ) {
				$items = $settings['always_show'];
			}

			return $items;
		}
	}

	new AutoHideAdminMenu();
}
