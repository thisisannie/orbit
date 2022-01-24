<?php

/**
 * Helper class for child theme functions.
 *
 * @class FLChildTheme
 */
final class FLChildTheme {
    
    /**
	 * Enqueues scripts and styles.
	 *
     * @return void
     */
    static public function enqueue_scripts()
    {
	    wp_enqueue_style( 'fl-child-theme', FL_CHILD_THEME_URL . '/style.css' );
        wp_enqueue_style( 'slick-theme', FL_CHILD_THEME_URL . '/js/slick/slick-theme.css' );
        wp_enqueue_style( 'slick-style', FL_CHILD_THEME_URL . '/js/slick/slick.css' );
    }
}