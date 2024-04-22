<?php

/**
 * AutoBlogging_Pro class file
 *
 * @package autoblogging-pro
 */

/**
 * Example Plugin
 */
function get_image_sources_from_html($html_content) {
    // Create a new DOMDocument instance
    $dom = new DOMDocument();
    
    // Suppress errors due to malformed HTML
    libxml_use_internal_errors(true);
    
    // Load the HTML content
    $dom->loadHTML($html_content);
    
    // Clear the libxml error buffer
    libxml_clear_errors();
    
    // Get all the image tags
    $images = $dom->getElementsByTagName('img');
    
    // Array to hold all src attributes
    $srcs = [];

    // Loop over image tags and extract the src attribute
    foreach ($images as $img) {
        $src = $img->getAttribute('src');
        if (!empty($src)) {
            $srcs[] = $src;
        }
    }

    return $srcs;
}

class AutoBlogging_Pro
{

	public static $instance;

	/**
	 * Constructor
	 */
	public function __construct()
	{
		// Schedule syncing event
		$this->schedule_sync();
		add_action('admin_menu', [$this, 'add_admin_menu']);

		register_activation_hook(AUTOBLOGGING_PRO_FILE, [$this, 'activate']);
		register_deactivation_hook(AUTOBLOGGING_PRO_FILE, [$this, 'deactivate']);
		register_uninstall_hook(AUTOBLOGGING_PRO_FILE, ['AutoBloggingPro', 'uninstall']); // register_uninstall_hook(__FILE__, [$this, 'uninstall']);
		add_action('admin_init', [$this, 'autoblogging_pro_register_settings']);
		add_action('admin_init', [$this, 'autoblogging_pro_settings_section']);

		// multi site support
		add_action('network_admin_menu', [$this, 'add_admin_menu']);
		add_action('network_admin_edit_autoblogging_pro_settings', [$this, 'save_network_settings']);
		add_action('autoblogging_pro_sync_event', [$this, 'sync']);

		// ajax
		add_action('wp_ajax_autoblogging_pro_disconnect_api_key', [$this, 'disconnect_api_key']);
		add_action('wp_ajax_autoblogging_pro_fetch_now', [$this, 'fetch_now']);
	}
	// disconnect_api_key
	public function disconnect_api_key()
	{
		$api_key = get_site_option('autoblogging_pro_api_key');
		if ($api_key) {
			update_site_option('autoblogging_pro_api_key', '');
			wp_send_json_success();
		}
		wp_send_json_error();
	}


	/**
	 * Get instance
	 */
	public static function instance()
	{
		if (!self::$instance) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	// save_network_settings
	public function save_network_settings()
	{
		if (isset($_POST['autoblogging_pro_publish_time'])) {
			update_site_option('autoblogging_pro_publish_time', $_POST['autoblogging_pro_publish_time']);
		}


		if (isset($_POST['autoblogging_pro_publish_time'])) {
			update_site_option('autoblogging_pro_publish_time', $_POST['autoblogging_pro_publish_time']);
		}
		wp_redirect(
			add_query_arg(
				[
					'page'    => 'autoblogging-pro',
					'updated' => 'true',
				],
				network_admin_url('settings.php')
			)
		);
		exit;
	}




	/**
	 * Activate plugin
	 */
	public function activate()
	{
		// do something
		$default_options = [
			'autoblogging_pro_api_key'      => '',
			'autoblogging_pro_publish_time' => '12:00',
			'autoblogging_pro_post_limit'   => 15,
			'autoblogging_pro_action'       => 'draft',

		];
		foreach ($default_options as $option_key => $option_value) {
			if (!get_option($option_key)) {
				add_option($option_key, $option_value);
			}
		}
	}

	/**
	 * Deactivate plugin
	 */
	public function deactivate()
	{
		// do something
		// Unschedule syncing event
		wp_clear_scheduled_hook('autoblogging_pro_sync_event');
	}


	/**
	 * Uninstall plugin
	 */
	public static function uninstall()
	{
		// do something
		// Unschedule syncing event
		wp_clear_scheduled_hook('autoblogging_pro_sync_event');
		// Delete options
		// Delete options
		$options_to_delete = [
			'autoblogging_pro_api_key',
			'autoblogging_pro_publish_time',
			'autoblogging_pro_post_limit',
			'autoblogging_pro_publish_time',
		];
		foreach ($options_to_delete as $option_key) {
			delete_option($option_key);
		}
	}


	/**
	 * Schedule syncing event hourly cron
	 */
	public function schedule_sync()
	{
		if (!wp_next_scheduled('autoblogging_pro_sync_event')) {
			wp_schedule_event(time(), 'hourly', 'autoblogging_pro_sync_event');
		}
	}

	/**
	 * Fetch Now
	 */
	public function fetch_now()
	{

		$this->sync();
		if (defined('DOING_AJAX') && DOING_AJAX) {

			wp_send_json_success();
		}
		return;
	}





	/**
	 * Add admin menu
	 */
	public function add_admin_menu()
	{
		add_options_page('AutoBlogging Pro', 'AutoBlogging Pro', 'manage_options', 'autoblogging-pro', [$this, 'settings_page']);
	}

	/**
	 * Settings page
	 */
	public function settings_page()
	{
		if (!current_user_can('manage_options')) {
			wp_die(__('You do not have sufficient permissions to access this page.'));
		}

		if (isset($_POST['autoblogging_pro_api_key']) || isset($_GET['api_key'])) {
			$api_key = isset($_POST['autoblogging_pro_api_key']) ? $_POST['autoblogging_pro_api_key'] : $_GET['api_key'];
			$api_key = sanitize_text_field($api_key);
			update_option('autoblogging_pro_api_key', $api_key);
		}



		if (isset($_POST['autoblogging_pro_publish_time'])) {
			update_option('autoblogging_pro_publish_time', $_POST['autoblogging_pro_publish_time']);
		}



		if (isset($_POST['autoblogging_pro_publish_time'])) {
			update_option('autoblogging_pro_publish_time', $_POST['autoblogging_pro_publish_time']);
		}

		if (isset($_REQUEST['autoblogging_pro_fetch_now'])) {
			// if its ajax requset send succeess message
			$this->fetch_now();
		}

		$connect_api = AUTOBLOGGING_PRO_API_URL . 'connect';

		$action     = get_option('autoblogging_pro_action', 'draft');
		$post_limit = get_option('autoblogging_pro_post_limit', 15);

		$autoblogging_pro_publish_time = get_option('autoblogging_pro_publish_time', '');
		$api_key                       = get_option('autoblogging_pro_api_key', '');



		require_once AUTOBLOGGING_PRO_DIR . '/templates/settings.php';
	}

	/**
	 * Register settings
	 */
	public function autoblogging_pro_register_settings()
	{
		register_setting('autoblogging_pro_settings_group', 'autoblogging_pro_post_limit');
		register_setting('autoblogging_pro_settings_group', 'autoblogging_pro_action');
		register_setting('autoblogging_pro_settings_group', 'autoblogging_pro_publish_time');
	}

	/**
	 * Settings section
	 */
	public function autoblogging_pro_settings_section()
	{
		add_settings_section('autoblogging_pro_settings_section', '', [$this, 'autoblogging_pro_settings_section_callback'], 'autoblogging_pro_settings_group');
		//add_settings_field('autoblogging_pro_post_limit', 'Schedule Limit', [$this, 'autoblogging_pro_post_limit_callback'], 'autoblogging_pro_settings_group', 'autoblogging_pro_settings_section');
		add_settings_field('autoblogging_pro_action', 'Action', [$this, 'autoblogging_pro_action_callback'], 'autoblogging_pro_settings_group', 'autoblogging_pro_settings_section');
		add_settings_field('autoblogging_pro_publish_time', 'Schedule Time', [$this, 'autoblogging_pro_publish_time_callback'], 'autoblogging_pro_settings_group', 'autoblogging_pro_settings_section');
	}

	/**
	 * Settings section callback
	 */
	public function autoblogging_pro_settings_section_callback()
	{
		echo '';
	}

	/**
	 * Schedule limit callback
	 */
	public function autoblogging_pro_post_limit_callback()
	{        ?>
		<tr valign="top">

			<td>
				<input type="number" name="f" min="1" step="1" value="<?php echo esc_attr(get_option('autoblogging_pro_post_limit', 1)); ?>" class="regular-text">
				<p class="description"><?php esc_html_e('Enter the maximum number of articles to publish per day', 'autoblogging-pro'); ?></p>
			</td>
		</tr>
	<?php
	}

	/**
	 * Action callback
	 */
	public function autoblogging_pro_action_callback()
	{
	?>
		<tr valign="top">

			<td>
				<fieldset>
					<legend class="screen-reader-text"><span><?php esc_html_e('Action', 'autoblogging-pro'); ?></span></legend>
					<label for="autoblogging_pro_action_draft">
						<input name="autoblogging_pro_action" type="radio" id="autoblogging_pro_action_draft" value="draft" <?php checked(get_option('autoblogging_pro_action', 'draft'), 'draft'); ?>>
						<?php esc_html_e('No Action (Draft)', 'autoblogging-pro'); ?>
					</label><br>
					<label for="autoblogging_pro_action_publish">
						<input name="autoblogging_pro_action" type="radio" id="autoblogging_pro_action_publish" value="publish" <?php checked(get_option('autoblogging_pro_action', 'draft'), 'publish'); ?>>
						<?php esc_html_e('Auto Publish', 'autoblogging-pro'); ?>
					</label><br>
					<label for="autoblogging_pro_action_schedule">
						<input name="autoblogging_pro_action" type="radio" id="autoblogging_pro_action_schedule" value="schedule" <?php checked(get_option('autoblogging_pro_action', 'draft'), 'schedule'); ?>>
						<?php esc_html_e('Auto Schedule', 'autoblogging-pro'); ?>
					</label>
				</fieldset>
			</td>
		</tr>
	<?php
	}

	/**
	 * Schedule time callback
	 */
	public function autoblogging_pro_publish_time_callback()
	{
	?>
		<tr valign="top" class="autoblogging_pro_schedule_settings">

			<td>
				<input type="time" name="autoblogging_pro_publish_time" value="<?php echo esc_attr(get_option('autoblogging_pro_publish_time')); ?>" class="regular-text">
				<p class="description"><?php esc_html_e('Daily time when the scheduled posts should be published', 'autoblogging-pro'); ?></p>
			</td>
		</tr>
<?php
	}

	/**
	 * Sync Function for fetching from autobloggin.pro api
	 */
	public function sync()
	{
		// multisite support 
		$app_url = AUTOBLOGGING_PRO_API_URL . 'api/articles';
		$domain  = get_site_url();
		$api_key = get_option('autoblogging_pro_api_key');
		if (!empty($api_key)) {

			// Perform an HTTP request with the necessary headers
			$response = wp_remote_get(
				$app_url,
				[
					'headers' => [
						'Domain'        => parse_url($domain, PHP_URL_HOST),
						'Authorization' => 'Bearer ' . $api_key,
					],
				]
			);

			if (is_array($response) &&  is_wp_error($response)) {
				// Handle errors
				return;
			}


			// Parse the JSON response and save the articles in the WordPress site

			$articles = json_decode(wp_remote_retrieve_body($response), true);

			if (empty($articles) || isset($articles['error'])) {
				return;
			}
				// var_dump($articles);die;
			$this->insert_post($articles);
		}
	}

	/**
	 * Insert post
	 */
	public function insert_post($articles)
	{
		// Get the action option
		$action = get_option('autoblogging_pro_action', 'draft');

		// Get the schedule time option
		$status = $action == 'schedule' ? 'future' : $action;

		foreach ($articles as $article) {


			global $wpdb;
			$metaKey = 'autoblogging_pro_article_id';
			$metaValue = $article['id'];

			$count = $wpdb->get_var(
				$wpdb->prepare(
					"SELECT COUNT(*) 
					FROM $wpdb->postmeta
					WHERE meta_key = %s AND meta_value = %s",
					$metaKey,
					$metaValue
				)
			);


			if ($count > 0) {
				// Update the existing post if necessary
				continue;
			}

			$article = (object) $article;
			$article->focus_keyphrase = isset($article->focus_keyphrase) ? $article->focus_keyphrase : explode(',', $article->seo_keywords)[0];
			// Create a new post object
			$isfeature = $article->image ? 1 : 0;
			$contentimage = $article->image_count - $isfeature;
			if ($contentimage > 0) {
				$image_sources = get_image_sources_from_html($article->description);

				// Print the sources
				print_r($image_sources);
				foreach ($image_sources as $img){
					$img_src = $this->insert_image($img, $article->id,"content");
					$article->description = str_replace($img,$img_src,$article->description);
				}
			}
			$new_post = [

				'post_title'   => wp_strip_all_tags($article->title),
				'post_content' => $article->description,
				'post_name'    => sanitize_title(preg_replace('/\b(a|an|the)\b/u', '', strtolower($article->title))),
				'post_status'  => $status,
				'post_author'  => 1,
				'post_excerpt' => $article->seo_description,

			];
			if ($action == 'schedule') {
				// Get the current date and time
				$current_datetime = new DateTime();

				// Get the current date
				$current_date          = $current_datetime->format('Y-m-d');
				$schedule_time         = get_option('autoblogging_pro_publish_time', '00:00');
				$new_post['post_date'] = $current_date . ' ' . $schedule_time;
			}
			// Insert the post into the database
			$post_id = wp_insert_post($new_post);

			if ($post_id) {
				// Save the article ID as post meta
				add_post_meta($post_id, 'autoblogging_pro_article_id', $article->id);
				// Set post tags
				// tags are comma separated
				wp_set_post_tags($post_id, $article->tags);

				// Set post categories are comma separated
				$categories   = explode(',', $article->category);
				$category_ids = [];
				$article->categories = [];
				foreach ($categories as $category) {
					$category = trim($category);
					$term     = term_exists($category, 'category');

					if (is_array($term)) {
						$category_id = $term['term_id'];
					} else {
						// wp_insert_term returns an array on success or WP_Error on failure
						$result = wp_insert_term($category, 'category');
						if (is_wp_error($result)) {
							// handle the error in some way
							continue;
						} else {
							$category_id = $result['term_id'];
						}
					}

					$category_ids[] = $category_id;
					$article->categories[] = $category_id;
				}
				wp_set_post_categories($post_id, $category_ids);



				//var_dump($articles->categories,$category_ids);die;
				$this->seo_plugins($post_id, $article);

				// Set featured image
				if ($article->image) {
					$this->insert_image($article, $post_id);
				}
			}
		}
	}
	function download_image($url) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 20);
		$data = curl_exec($ch);
		$error = curl_error($ch);
		curl_close($ch);
		if ($error) {
			error_log('Curl error: ' . $error); // Agar cURL fail ho jaye toh error log karein
			return false;
		}
		return $data;
	}

	public function insert_image($article, $post_id, $type = "featured")
{
    $image_url = is_object($article) ? $article->image : $article;
    // Download the image using the download_image function
    $image = $this->download_image($image_url);
    if (!$image) {
        // Handle error, image not downloaded
        error_log('Failed to download image from ' . $image_url);
        return;
    }

    // Upload the image to the media library
    $upload = wp_upload_bits(basename($image_url), null, $image);

    // Check if the upload was successful
    if (!$upload['error']) {
        $wp_filetype = wp_check_filetype(basename($upload['file']), null);
        $attachment = [
            'post_mime_type' => $wp_filetype['type'],
            'post_title'     => sanitize_file_name(basename($upload['file'])),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attachment_id = wp_insert_attachment($attachment, $upload['file']);

        if (!is_wp_error($attachment_id)) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
            wp_update_attachment_metadata($attachment_id, $attachment_data);

            if ($type == 'featured') {
                update_post_meta($attachment_id, '_wp_attachment_image_alt', $article->focus_keyphrase);
                set_post_thumbnail($post_id, $attachment_id);
            } else {
                return wp_get_attachment_url($attachment_id);
            }
        }
    }
}
	/**
	 * Insert image
	 */
	// public function insert_image($article, $post_id,$type = "featured")
	// {
	// 	$image_url = is_object($article) ? $article->image : $article;
	// 	// Download the image
	// 	$image = file_get_contents($image_url);

	// 	// Upload the image to the media library

	// 	$upload = wp_upload_bits(basename($image_url), null, $image);
	// 	if ($upload['error']) {
	// 		error_log('Upload error: ' . $upload['error']); // Upload errors log karein
	// 		return;
	// 	}

	// 	// Check if the upload was successful

	// 	if (!$upload['error']) {
	// 		$wp_filetype = wp_check_filetype(basename($upload['file']), null);

	// 		$attachment = [
	// 			'post_mime_type' => $wp_filetype['type'],
	// 			'post_title'     => sanitize_file_name(basename($upload['file'])),
	// 			'post_content'   => '',
	// 			'post_status'    => 'inherit',
	// 		];

	// 		$attachment_id = wp_insert_attachment($attachment, $upload['file']);

	// 		if (!is_wp_error($attachment_id)) {
	// 			require_once ABSPATH . 'wp-admin/includes/image.php';

	// 			$attachment_data = wp_generate_attachment_metadata($attachment_id, $upload['file']);
	// 			wp_update_attachment_metadata($attachment_id, $attachment_data);


	// 			if ($type == 'featured') {
	// 				// Assuming your $article object has an 'image_alt' property
	// 				// Otherwise, replace $article->image_alt with the alt text you want
	// 				update_post_meta($attachment_id, '_wp_attachment_image_alt', $article->focus_keyphrase);
	// 				set_post_thumbnail($post_id, $attachment_id);
	// 			} else {
	// 				return	wp_get_attachment_url($attachment_id);
	// 			}
	// 		}
	// 	}
	// }

	/**
	 * SEO plugins
	 */
	public function seo_plugins($post_id, $article)
	{
		// Check for Rank Math
		if (defined('RANK_MATH_FILE')) {
			update_post_meta($post_id, 'rank_math_title', $article->title);
			update_post_meta($post_id, 'rank_math_description', $article->seo_description);
			update_post_meta($post_id, 'rank_math_focus_keyword', $article->seo_keywords);

			// Set the primary category
			$categories = get_the_terms($post_id, 'category');
			if ($categories && !is_wp_error($categories)) :
				// loop through each cat
				foreach ($categories as $category) :
					// get the children (if any) of the current cat
					$children = get_categories(array('taxonomy' => 'category', 'parent' => $category->term_id));

					if (count($children) == 0) {
						update_post_meta($post_id, 'rank_math_primary_' . $category->taxonomy, $category->term_id);
					}
				endforeach;
			endif;
		}

		// Check for Yoast SEO
		if (defined('WPSEO_VERSION')) {
			update_post_meta($post_id, '_yoast_wpseo_title', $article->title);
			update_post_meta($post_id, '_yoast_wpseo_metadesc', $article->seo_description);
			update_post_meta($post_id, '_yoast_wpseo_focuskw', $article->focus_keyphrase);
			update_post_meta($post_id, '_yoast_wpseo_schema_article_type', "BlogPosting");

			// Set the primary category
			$categories = get_the_terms($post_id, 'category');
			if ($categories && !is_wp_error($categories)) :
				// loop through each cat
				foreach ($categories as $category) :
					// get the children (if any) of the current cat
					$children = get_categories(array('taxonomy' => 'category', 'parent' => $category->term_id));

					if (count($children) == 0) {
						update_post_meta($post_id, '_yoast_wpseo_primary_' . $category->taxonomy, $category->term_id);
					}
				endforeach;
			endif;
		}

		// Check for All in One SEO
		if (defined('AIOSEO_PHP_VERSION_DIR')) {
			global $wpdb;

			$table_name = $wpdb->prefix . 'aioseo_posts';

			$json_string = '{
				"keyphraseInTitle": {
					"score": 9,
					"maxScore": 9,
					"error": 0
				},
				"keyphraseInDescription": {
					"score": 9,
					"maxScore": 9,
					"error": 0
				},
				"keyphraseLength": {
					"score": 9,
					"maxScore": 9,
					"error": 0,
					"length": 2
				},
				"keyphraseInURL": {
					"score": 5,
					"maxScore": 5,
					"error": 0
				},
				"keyphraseInIntroduction": {
					"score": 3,
					"maxScore": 9,
					"error": 1
				},
				"keyphraseInSubHeadings": {
					"score": 3,
					"maxScore": 9,
					"error": 1
				},
				"keyphraseInImageAlt": []
			}';

			$keyphrases = [
				"focus" => [
					"keyphrase" => $article->focus_keyphrase,
					"score" => rand(60, 90),
					"analysis" => json_decode($json_string, true)
				],
				"additional" => []
			];

			$keyphrases_json = json_encode($keyphrases);

			$data = array(
				'post_id' => $post_id, // The ID of your post.
				'title' => $article->title,
				'description' => $article->seo_description,
				'keywords' => json_encode(explode(',', $article->seo_keywords)),
				'keyphrases' => $keyphrases_json,
				'images' => json_encode(array($article->image)), // images field expects a JSON-encoded array of image URLs

				// Add other fields here...
			);

			$format = array(
				'%d',  // post_id is a big integer.
				'%s',  // title is text.
				'%s',  // description is text.
				'%s',  // keywords is mediumtext.
				'%s',  // keyphrases is longtext.
				'%s',  // images is longtext.
				// Add formats for other fields here...
			);
			//var_dump($table_name, $data, $format);die;
			$wpdb->insert($table_name, $data, $format);
		}

		// Check for SEOPress
		if (defined('SEOPRESS_VERSION')) {
			update_post_meta($post_id, '_seopress_titles_title', $article->title);
			update_post_meta($post_id, '_seopress_titles_desc', $article->seo_description);
			update_post_meta($post_id, '_seopress_analysis_target_kw', $article->focus_keyphrase);

			// Set the primary category
			$primary_category = $article->categories[0];
			update_post_meta($post_id, '_seopress_robots_primary_cat', $primary_category->term_id);

			// Set the primary category in the SEOPress meta box
			update_post_meta($post_id, '_seopress_robots_primary_' . $primary_category->taxonomy, $primary_category->term_id);
		}

		// Check for The SEO Framework
		if (defined('THE_SEO_FRAMEWORK_VERSION')) {
			update_post_meta($post_id, '_genesis_title', $article->title);
			update_post_meta($post_id, '_genesis_description', $article->seo_description);
			update_post_meta($post_id, '_genesis_keywords', $article->seo_keywords);

			// Set the primary category in the The SEO Framework meta box
			update_post_meta($post_id, '_genesis_primary_' . $primary_category->taxonomy, $primary_category->term_id);

			// Set the canonical URL
			update_post_meta($post_id, '_genesis_canonical_uri', $article->url);

			// Set the noindex option
			update_post_meta($post_id, '_genesis_noindex', '0');

			// Set the nofollow option
			update_post_meta($post_id, '_genesis_nofollow', '0');

			// Set the noarchive option
			update_post_meta($post_id, '_genesis_noarchive', '0');
		}
	}
}
