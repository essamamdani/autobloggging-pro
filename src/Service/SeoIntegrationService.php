<?php

namespace AutoBlogging_Pro\Service;

class SeoIntegrationService {

	/**
	 * Update meta for SEO plugins
	 * 
	 * @param int $post_id
	 * @param object $article
	 */
	public function update_meta( $post_id, $article ) {
		$this->handle_rank_math( $post_id, $article );
		$this->handle_yoast( $post_id, $article );
		$this->handle_aioseo( $post_id, $article );
		$this->handle_seopress( $post_id, $article );
		$this->handle_seo_framework( $post_id, $article );
	}

	protected function handle_rank_math( $post_id, $article ) {
		if ( ! defined( 'RANK_MATH_FILE' ) ) {
			return;
		}

		update_post_meta( $post_id, 'rank_math_title', $article->title );
		update_post_meta( $post_id, 'rank_math_description', $article->seo_description );
		update_post_meta( $post_id, 'rank_math_focus_keyword', $article->seo_keywords );

		$this->set_primary_category( $post_id, 'rank_math_primary_' );
	}

	protected function handle_yoast( $post_id, $article ) {
		if ( ! defined( 'WPSEO_VERSION' ) ) {
			return;
		}

		update_post_meta( $post_id, '_yoast_wpseo_title', $article->title );
		update_post_meta( $post_id, '_yoast_wpseo_metadesc', $article->seo_description );
		update_post_meta( $post_id, '_yoast_wpseo_focuskw', $article->focus_keyphrase );
		update_post_meta( $post_id, '_yoast_wpseo_schema_article_type', 'BlogPosting' );

		$this->set_primary_category( $post_id, '_yoast_wpseo_primary_' );
	}

	protected function handle_aioseo( $post_id, $article ) {
		if ( ! defined( 'AIOSEO_PHP_VERSION_DIR' ) ) {
			return;
		}

		global $wpdb;
		$table_name = $wpdb->prefix . 'aioseo_posts';

		// Basic data structure as per original code
		// Optimizing random score generation
		$keyphrases = [
			'focus'      => [
				'keyphrase' => $article->focus_keyphrase,
				'score'     => mt_rand( 60, 90 ),
				'analysis'  => [], // Simplified for now
			],
			'additional' => [],
		];

		$data = [
			'post_id'     => $post_id,
			'title'       => $article->title,
			'description' => $article->seo_description,
			'keywords'    => json_encode( explode( ',', $article->seo_keywords ) ),
			'keyphrases'  => json_encode( $keyphrases ),
			'images'      => json_encode( [ $article->image ] ),
		];

		$wpdb->insert( $table_name, $data );
	}

	protected function handle_seopress( $post_id, $article ) {
		if ( ! defined( 'SEOPRESS_VERSION' ) ) {
			return;
		}

		update_post_meta( $post_id, '_seopress_titles_title', $article->title );
		update_post_meta( $post_id, '_seopress_titles_desc', $article->seo_description );
		update_post_meta( $post_id, '_seopress_analysis_target_kw', $article->focus_keyphrase );

        if ( ! empty( $article->categories ) ) {
            $primary_category = $article->categories[0];
            update_post_meta( $post_id, '_seopress_robots_primary_cat', $primary_category->term_id );
            update_post_meta( $post_id, '_seopress_robots_primary_' . $primary_category->taxonomy, $primary_category->term_id );
        }
	}

	protected function handle_seo_framework( $post_id, $article ) {
		if ( ! defined( 'THE_SEO_FRAMEWORK_VERSION' ) ) {
			return;
		}

		update_post_meta( $post_id, '_genesis_title', $article->title );
		update_post_meta( $post_id, '_genesis_description', $article->seo_description );
		update_post_meta( $post_id, '_genesis_keywords', $article->seo_keywords );
        
        if( ! empty( $article->url ) ) {
		    update_post_meta( $post_id, '_genesis_canonical_uri', $article->url );
        }

        // Defaults
		update_post_meta( $post_id, '_genesis_noindex', '0' );
		update_post_meta( $post_id, '_genesis_nofollow', '0' );
		update_post_meta( $post_id, '_genesis_noarchive', '0' );
        
        if ( ! empty( $article->categories ) ) {
             $primary_category = $article->categories[0];
             update_post_meta( $post_id, '_genesis_primary_' . $primary_category->taxonomy, $primary_category->term_id );
        }
	}

	/**
	 * Helper to set primary category for plugins that support it
	 *
	 * @param int $post_id
	 * @param string $meta_prefix
	 */
	protected function set_primary_category( $post_id, $meta_prefix ) {
		$categories = get_the_terms( $post_id, 'category' );
		if ( $categories && ! is_wp_error( $categories ) ) {
			foreach ( $categories as $category ) {
				// Naive check for "parent" category -> if it has no children, maybe it's primary? 
                // Original code logic: if (count($children) == 0)
				$children = get_categories( [ 
                    'taxonomy' => 'category', 
                    'parent' => $category->term_id,
                    'hide_empty' => false 
                ] );

				if ( empty( $children ) ) {
					update_post_meta( $post_id, $meta_prefix . $category->taxonomy, $category->term_id );
                    // Break after finding one bottom-level category to set as primary
                    break; 
				}
			}
		}
	}
}
