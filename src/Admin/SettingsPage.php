<?php

namespace AutoBlogging_Pro\Admin;

use AutoBlogging_Pro\Service\ArticleService;

class SettingsPage {

	const API_URL = 'https://www.autoblogging.pro/';

	public function init() {
		add_action( 'admin_menu', [ $this, 'add_admin_menu' ] );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ] );
		
		// AJAX Actions
		add_action( 'wp_ajax_autoblogging_pro_disconnect_api_key', [ $this, 'ajax_disconnect' ] );
		add_action( 'wp_ajax_autoblogging_pro_fetch_now', [ $this, 'ajax_fetch_now' ] );
	}

	public function enqueue_assets( $hook ) {
		if ( 'settings_page_autoblogging-pro' !== $hook ) {
			return;
		}

		wp_enqueue_style( 'autoblogging-pro-admin', AUTOBLOGGING_PRO_URL . 'assets/css/admin.min.css', [], AUTOBLOGGING_PRO_VERSION );
	}

	public function add_admin_menu() {
		add_options_page( 
			'AutoBlogging Pro', 
			'AutoBlogging Pro', 
			'manage_options', 
			'autoblogging-pro', 
			[ $this, 'render_page' ] 
		);
	}

	public function register_settings() {
		register_setting( 'autoblogging_pro_settings_group', 'autoblogging_pro_post_limit' );
		register_setting( 'autoblogging_pro_settings_group', 'autoblogging_pro_action' );
		register_setting( 'autoblogging_pro_settings_group', 'autoblogging_pro_publish_time' );
		
		// Handle API Key via POST if manual save
		if ( isset( $_POST['autoblogging_pro_api_key'] ) && current_user_can( 'manage_options' ) ) {
            // Verify nonce if possible, but standard settings API handles some security
			update_option( 'autoblogging_pro_api_key', sanitize_text_field( $_POST['autoblogging_pro_api_key'] ) );
		}
        
        // Handle query param API key (from redirect)
        if ( isset( $_GET['api_key'] ) && current_user_can( 'manage_options' ) ) {
             update_option( 'autoblogging_pro_api_key', sanitize_text_field( $_GET['api_key'] ) );
        }
	}

	public function render_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}

		$api_key = get_option( 'autoblogging_pro_api_key', '' );
		$connect_api = self::API_URL . 'connect';
		$action = get_option( 'autoblogging_pro_action', 'draft' );
        
		require_once AUTOBLOGGING_PRO_DIR . '/templates/settings.php';
	}

	public function ajax_disconnect() {
        check_ajax_referer( 'autoblogging_pro_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		update_option( 'autoblogging_pro_api_key', '' );
		wp_send_json_success();
	}

	public function ajax_fetch_now() {
        check_ajax_referer( 'autoblogging_pro_ajax', 'nonce' );

		if ( ! current_user_can( 'manage_options' ) ) {
			wp_send_json_error( 'Permission denied' );
		}

		try {
			$article_service = new ArticleService();
			$article_service->fetch_and_sync();
			wp_send_json_success( 'Sync completed successfully' );
		} catch ( \Exception $e ) {
			wp_send_json_error( $e->getMessage() );
		} catch ( \Error $e ) {
			wp_send_json_error( $e->getMessage() );
		}
	}
}
