<?php

namespace AutoBlogging_Pro\Service;

use DateTime;
use  WP_Error;

class ArticleService {

	/**
	 * Insert post from API data
	 *
	 * @param array $articles
	 * @return void
	 */
	public function import_articles( array $articles ) {
		$action = get_option( 'autoblogging_pro_action', 'draft' );
		$status = 'schedule' === $action ? 'future' : $action;

		foreach ( $articles as $article_data ) {
			$this->process_article( $article_data, $status, $action );
		}
	}

	/**
	 * Process a single article
	 *
	 * @param array $article_data
	 * @param string $status
	 * @param string $action
	 * @return void
	 */
	protected function process_article( $article_data, $status, $action ) {
		global $wpdb;

		$article = (object) $article_data;
		$meta_key   = 'autoblogging_pro_article_id';
		$meta_value = $article->id;

		// Check if post exists
		$exists = $wpdb->get_var(
			$wpdb->prepare(
				"SELECT COUNT(*) FROM $wpdb->postmeta WHERE meta_key = %s AND meta_value = %s",
				$meta_key,
				$meta_value
			)
		);

		if ( $exists > 0 ) {
			return;
		}

		$article->focus_keyphrase = isset( $article->focus_keyphrase ) ? $article->focus_keyphrase : explode( ',', $article->seo_keywords )[0];

		// Handle images in content
        $image_service = new ImageService();
		if ( $article->image_count > 0 ) {
			$article->description = $image_service->process_content_images( $article->description, $article->id );
		}

		$new_post = [
			'post_title'   => wp_strip_all_tags( $article->title ),
			'post_content' => $article->description,
			'post_name'    => sanitize_title( preg_replace( '/\b(a|an|the)\b/u', '', strtolower( $article->title ) ) ),
			'post_status'  => $status,
			'post_author'  => 1, // TODO: Make configurable?
			'post_excerpt' => $article->seo_description,
		];

		if ( 'schedule' === $action ) {
			$new_post['post_date'] = $this->calculate_schedule_time();
		}

		$post_id = wp_insert_post( $new_post );

		if ( $post_id && ! is_wp_error( $post_id ) ) {
			add_post_meta( $post_id, 'autoblogging_pro_article_id', $article->id );
			
            if ( ! empty( $article->tags ) ) {
                wp_set_post_tags( $post_id, $article->tags );
            }

			$this->set_categories( $post_id, $article );
			
            // SEO Plugins Integration
            $seo_integration = new SeoIntegrationService();
            $seo_integration->update_meta( $post_id, $article );

			// Featured Image
			if ( ! empty( $article->image ) ) {
				$image_service->sideload_image( $article->image, $post_id, 'featured', $article->focus_keyphrase );
			}
		}
	}

	/**
	 * Calculate schedule time
	 * 
	 * @return string
	 */
	protected function calculate_schedule_time() {
		$current_datetime = new DateTime();
		$current_date     = $current_datetime->format( 'Y-m-d' );
		$schedule_time    = get_option( 'autoblogging_pro_publish_time', '00:00' );
		return $current_date . ' ' . $schedule_time;
	}

	/**
	 * Set categories for the post
	 * 
	 * @param int $post_id
	 * @param object $article
	 */
	protected function set_categories( $post_id, $article ) {
		if ( empty( $article->category ) ) {
			return;
		}

		$categories   = explode( ',', $article->category );
		$category_ids = [];
		$article->categories = []; // Ensure this property exists for SEO integration

		foreach ( $categories as $category_name ) {
			$category_name = trim( $category_name );
			$term          = term_exists( $category_name, 'category' );

			if ( is_array( $term ) ) {
				$category_id = $term['term_id'];
			} else {
				$result = wp_insert_term( $category_name, 'category' );
				if ( is_wp_error( $result ) ) {
					continue;
				}
				$category_id = $result['term_id'];
			}

			$category_ids[]       = $category_id;
			$article->categories[] = get_term( $category_id, 'category' ); // Store full term object or at least an object with term_id
		}

		wp_set_post_categories( $post_id, $category_ids );
	}

    /**
     * Fetch articles from API and sync
     * 
     * @return void
     */
    public function fetch_and_sync() {
        $api_url = 'https://app.autoblogging.pro/'; // Should ideally be injected or constant
        $app_url = $api_url . 'api/articles';
		$domain  = get_site_url();
		$api_key = get_option( 'autoblogging_pro_api_key' );

		if ( empty( $api_key ) ) {
            return;
        }

        $response = wp_remote_get(
            $app_url,
            [
                'headers' => [
                    'Domain'        => parse_url( $domain, PHP_URL_HOST ),
                    'Authorization' => 'Bearer ' . $api_key,
                ],
                'timeout' => 45,
            ]
        );

        if ( is_wp_error( $response ) ) {
            return;
        }

        $body = wp_remote_retrieve_body( $response );
        $articles = json_decode( $body, true );

        if ( empty( $articles ) || isset( $articles['error'] ) ) {
            return;
        }

        $this->import_articles( $articles );
    }
}
