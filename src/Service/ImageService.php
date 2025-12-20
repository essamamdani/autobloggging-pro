<?php

namespace AutoBlogging_Pro\Service;

use DOMDocument;

class ImageService {

    /**
     * Process images inside content: download and replace src
     *
     * @param string $content
     * @param int $article_id
     * @return string
     */
    public function process_content_images( $content, $article_id ) {
        // Suppress errors due to malformed HTML
        libxml_use_internal_errors( true );
        
        $dom = new DOMDocument();
        // UTF-8 hack
        $dom->loadHTML( mb_convert_encoding( $content, 'HTML-ENTITIES', 'UTF-8' ), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
        
        libxml_clear_errors();
        
        $images = $dom->getElementsByTagName( 'img' );
        
        if ( $images->length === 0 ) {
            return $content;
        }

        foreach ( $images as $img ) {
            $src = $img->getAttribute( 'src' );
            if ( ! empty( $src ) ) {
                $new_src = $this->sideload_image( $src, 0, 'content' ); // 0 post_id for unattached or attach later if possible, but actually we need post_id to attach. 
                // For now, we might not have post_id creates yet if valid call is inside process_article before insert_post.
                // In the original code, it was doing this before insert_post, so $post_id was unknown?
                // Actually original code was: $img_src = $this->insert_image($img, $article->id,"content"); 
                // Wait, it passed $article->id (the remote ID)? That's wrong for WP attachment parent. 
                // If we pass 0, it's unattached. That's fine for content images.
                
                if ( $new_src ) {
                    $img->setAttribute( 'src', $new_src );
                    // Remove srcset and sizes if they exist to avoid display issues
                    $img->removeAttribute( 'srcset' );
                    $img->removeAttribute( 'sizes' );
                }
            }
        }
        
        return $dom->saveHTML();
    }

    /**
     * Sideload image from URL
     *
     * @param string $url
     * @param int $post_id Parent post ID (0 if none)
     * @param string $type 'featured' or 'content'
     * @param string $alt_text
     * @return string|void URL of the image if content type, void if featured (attached to post)
     */
    public function sideload_image( $url, $post_id = 0, $type = 'featured', $alt_text = '' ) {
        // Validation
        if ( ! filter_var( $url, FILTER_VALIDATE_URL ) ) {
            return;
        }

        require_once ABSPATH . 'wp-admin/includes/media.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';
        require_once ABSPATH . 'wp-admin/includes/image.php';

        // desc as alt text
        $desc = $alt_text;
        
        // sideload handling
        $tmp = download_url( $url );
        
        if ( is_wp_error( $tmp ) ) {
            return;
        }

        $file_array = [
            'name'     => basename( $url ),
            'tmp_name' => $tmp,
        ];

        // Prepare ID for attachment if it's for a post
        // Check for extension
        preg_match( '/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches );
        if ( ! empty( $matches ) ) {
            $file_array['name'] = basename( $matches[0] );
        }

        $id = media_handle_sideload( $file_array, $post_id, $desc );
        
        if ( is_wp_error( $id ) ) {
            @unlink( $file_array['tmp_name'] );
            return;
        }

        if ( ! empty( $alt_text ) ) {
             update_post_meta( $id, '_wp_attachment_image_alt', $alt_text );
        }

        if ( 'featured' === $type && $post_id > 0 ) {
            set_post_thumbnail( $post_id, $id );
        } else {
            return wp_get_attachment_url( $id );
        }
    }
}
