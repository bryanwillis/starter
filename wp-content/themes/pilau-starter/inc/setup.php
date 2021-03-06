<?php

/**
 * Initial theme setup
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Set up theme
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'after_setup_theme', 'pilau_setup', 10 );
function pilau_setup() {

	/* Enable shortcodes in widgets */
	add_filter( 'widget_text', 'do_shortcode' );

	/*
	 * Override core automatic feed links
	 * @see inc/feeds.php
	 */
	remove_theme_support( 'automatic-feed-links' );

	/* Featured image */
	add_theme_support( 'post-thumbnails' );
	//set_post_thumbnail_size( 203, 161 ); // default Post Thumbnail dimensions

	/* Set custom image sizes */
	//add_image_size( 'image-banner', 250, 0, false );

	/*
	 * Post formats - may be useful for some blog-heavy projects
	 * @link http://codex.wordpress.org/Post_Formats
	 */
	//add_theme_support( 'post-formats', array( 'aside', 'gallery', 'link', 'image', 'quote', 'status', 'video', 'audio', 'chat' ) );

	/*
	 * Register nav menus
	 */
	/*
	register_nav_menus( array(
		'nav_main'		=> __( 'Main navigation' )
	) );
	*/

}


/**
 * Cookie notice handling
 *
 * @since	Pilau_Starter 0.1
 * @todo	Implement more sophisticated cookie handling (JS?) to hide notice for users who have disabled cookies
 */
if ( PILAU_USE_COOKIE_NOTICE )
	add_action( 'init', 'pilau_cookie_notice' );
function pilau_cookie_notice() {

	// Check for this domain in referrer
	if ( parse_url( $_SERVER['HTTP_REFERER'], PHP_URL_HOST ) == $_SERVER['SERVER_NAME'] ) {

		// Set cookie showing (implied) consent
		// Expires in 10 years
		setcookie( 'pilau_cookie_notice', 1, time() + ( 10 * 365 * 24 * 60 * 60 ), '/' );

	}

}


/**
 * Manage core taxonomies
 *
 * @since	Pilau_Starter 0.1
 * @link	http://w4dev.com/wp/remove-taxonomy/
 */
add_action( 'init', 'pilau_core_taxonomies' );
function pilau_core_taxonomies() {
	global $wp_taxonomies;

	/* Disable categories? */
	if ( taxonomy_exists( 'category' ) && ! PILAU_USE_CATEGORIES )
		unset( $wp_taxonomies['category'] );

	/* Disable tags? */
	if ( taxonomy_exists( 'post_tag' ) && ! PILAU_USE_TAGS )
		unset( $wp_taxonomies['post_tag'] );

}


/**
 * Set up that needs to happen when $post object is ready
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'template_redirect', 'pilau_setup_after_post' );
function pilau_setup_after_post() {
	global $pilau_custom_fields, $post;
	$pilau_custom_fields = array();

	/*
	 * Determine current page ID, if we're on a page
	 *
	 * This may not be $post->ID, if we're on the blog home page.
	 * Set to false if the current view isn't related to a singular post or page.
	 */
	$current_page_id = false;
	if ( is_home() && ! is_front_page() )
		$current_page_id = get_option( 'page_for_posts' );
	else if ( is_singular() )
		$current_page_id = $post->ID;
	define( 'PILAU_CURRENT_PAGE_ID', $current_page_id );

	/*
	 * Get all custom fields for current post
	 */
	//if ( PILAU_CURRENT_PAGE_ID && function_exists( 'slt_cf_all_field_values' ) )
	//	$pilau_custom_fields = slt_cf_all_field_values( 'post', $current_page_id );

	// De-activate removal of menu item IDs from Pilau Base
	//remove_filter( 'nav_menu_item_id', '__return_empty_array', 10000 );

}


/**
 * Move scripts to the footer for better performance
 * @link	http://developer.yahoo.com/performance/rules.html#js_bottom
 *
 * You may want to disable this if you have jQuery you want to run within the page,
 * as it loads.
 *
 * Code from @link http://www.prelovac.com/vladimir/wordpress-plugins/footer-javascript
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'after_theme_setup', 'pilau_scripts_to_footer' );
function pilau_scripts_to_footer() {
	remove_action( 'wp_head', 'wp_print_scripts' );
	remove_action( 'wp_head', 'wp_print_head_scripts', 9 );
	remove_action( 'wp_head', 'wp_enqueue_scripts', 1 );
	add_action( 'wp_footer', 'wp_print_scripts', 5 );
	add_action( 'wp_footer', 'wp_enqueue_scripts', 5 );
	add_action( 'wp_footer', 'wp_print_head_scripts', 5 );
}


/**
 * Manage scripts for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * The Modernizr script has to be included in the header, so in case pilau_scripts_to_footer()
 * is used to move scripts to the footer, Modernizr is hard-coded into header.php
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'wp_enqueue_scripts', 'pilau_enqueue_scripts', 10 );
function pilau_enqueue_scripts() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {

		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'pilau-global', get_stylesheet_directory_uri() . '/js/global.js', array( 'jquery' ), '1.0' );

		/*
		 * Comment reply script - adjust the conditional if you need comments on post types other than 'post'
		 */
		if ( defined( 'PILAU_USE_COMMENTS' ) && PILAU_USE_COMMENTS && is_single() && comments_open() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		/*
		 * Use this to pass the AJAX URL to the client when using AJAX
		 * @link	http://wp.smashingmagazine.com/2011/10/18/how-to-use-ajax-in-wordpress/
		 */
		wp_localize_script( 'pilau-global', 'pilau_global', array( 'ajaxurl' => admin_url( 'admin-ajax.php', PILAU_REQUEST_PROTOCOL ) ) );

	}
}


/**
 * Manage styles for the front-end
 *
 * Always use the $ver parameter when registering or enqueuing styles or scripts, and
 * update it when deploying a new version - this helps prevent browser caching issues.
 * (Actually this is made redundant by using Better WordPress Minify, with its
 * appended parameter - but this is a good habit to get into ;-)
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'wp_enqueue_scripts', 'pilau_enqueue_styles', 10 );
function pilau_enqueue_styles() {
	// This test is done here because applying the test to the hook breaks due to pilau_is_login_page() not being defined yet...
	if ( ! is_admin() && ! pilau_is_login_page() ) {
		global $wp_styles; // In case we need IE-only styles with conditional wrapper

		wp_enqueue_style( 'pilau-main', get_stylesheet_directory_uri() . '/styles/main.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );
		wp_enqueue_style( 'pilau-print', get_stylesheet_directory_uri() . '/styles/print.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );

		// IE-only styles
		// BEWARE: When using Better WordPress Minify plugin, these appear before the other CSS files in the header
		//wp_enqueue_style( 'pilau-ie', get_stylesheet_directory_uri() . '/styles/ie.css', array( 'html5-reset', 'wp-core', 'pilau-classes' ), '1.0' );
		//$wp_styles->add_data( 'pilau-ie', 'conditional', 'lt IE 9' );

	}
}


/**
 * Login styles and scripts
 *
 * @since	Pilau_Starter 0.1
 */
//add_action( 'login_head', 'pilau_login_styles_scripts', 10000 );
function pilau_login_styles_scripts() { ?>
	<link rel="stylesheet" href="<?php echo get_stylesheet_directory_uri() . '/styles/wp-login.css'; ?>">
<?php }
