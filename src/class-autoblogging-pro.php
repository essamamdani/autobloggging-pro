<?php

namespace AutoBlogging_Pro;

use AutoBlogging_Pro\Admin\SettingsPage;
use AutoBlogging_Pro\Service\ArticleService;

class AutoBlogging_Pro {

	public static $instance;

	public function __construct() {
		$this->init_hooks();
        $this->init_admin();
	}

	public static function instance() {
		if ( ! self::$instance ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	private function init_hooks() {
        // Schedule Cron
		add_action( 'autoblogging_pro_sync_event', [ $this, 'execute_sync' ] );
        
        // Activation/Deactivation
        register_activation_hook( AUTOBLOGGING_PRO_FILE, [ $this, 'activate' ] );
		register_deactivation_hook( AUTOBLOGGING_PRO_FILE, [ $this, 'deactivate' ] );
	}
    
    private function init_admin() {
        if ( is_admin() ) {
            $settings_page = new SettingsPage();
            $settings_page->init();
        }
    }

	public function execute_sync() {
		try {
			$article_service = new ArticleService();
			$article_service->fetch_and_sync();
		} catch ( \Exception $e ) {
			error_log( 'AutoBlogging Pro Sync Error: ' . $e->getMessage() );
		} catch ( \Error $e ) {
			error_log( 'AutoBlogging Pro Sync Fatal Error: ' . $e->getMessage() );
		}
	}

	public function activate() {
		if ( ! wp_next_scheduled( 'autoblogging_pro_sync_event' ) ) {
			wp_schedule_event( time(), 'hourly', 'autoblogging_pro_sync_event' );
		}
        
        // Defaults
        add_option( 'autoblogging_pro_publish_time', '12:00' );
        add_option( 'autoblogging_pro_action', 'draft' );
	}

	public function deactivate() {
		wp_clear_scheduled_hook( 'autoblogging_pro_sync_event' );
	}
}
