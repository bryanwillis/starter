<?php


/**
 * Header modifications
 *
 * Includes dynamic additions to the header, any title manipulation, extensions to default WP classes for body and posts, etc.
 *
 * @package	Pilau_Starter
 * @since	0.1
 */


/**
 * Clean up WP header stuff
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'template_redirect', 'pilau_cleanup_head' );
function pilau_cleanup_head() {

	/* Remove generator meta tags */
	remove_action( 'wp_head', 'wp_generator' );

	/* Remove extra links such as category feeds */
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	/* Remove the link to the Really Simple Discovery service endpoint, EditURI link */
	remove_action( 'wp_head', 'rsd_link' );

	/* Remove the link to the Windows Live Writer manifest file */
	remove_action( 'wp_head', 'wlwmanifest_link' );

	/* Remove rel links unless dealing with posts */
	if ( ! is_home() || ( get_post_type() != 'post' ) || ! is_archive() ) {
		remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
		remove_action( 'wp_head', 'start_post_rel_link' );
		remove_action( 'wp_head', 'index_rel_link' );
		remove_action( 'wp_head', 'adjacent_posts_rel_link' );
	}

}


/**
 * Header stuff
 *
 * @since	Pilau_Starter 0.1
 */
add_action( 'wp_head', 'pilau_head' );
function pilau_head() {
	global $cpage, $post;

	/**
	 * Robots meta
	 *
	 * noindex,nofollow for dev and staging; let Yoast SEO take over on production
	 * TODO No way to override Yoast's robots output selectively?
	 */
	if ( ( defined( 'WP_LOCAL_DEV' ) && WP_LOCAL_DEV ) || ( defined( 'PILAU_REMOTE_ENV' ) && PILAU_REMOTE_ENV == 'staging' ) ) { ?>
		<meta name="robots" content="noindex,nofollow">
		<?php
	}

	/**
	 * Canonical link for paged comments
	 */
	if ( $cpage > 1 ) { ?>
		<link rel="canonical" href="<?php echo esc_url( get_permalink( $post->ID ) ); ?>">
	<?php }

	/**
	 * Site icons
	 *
	 * @link	http://www.jonathantneal.com/blog/understand-the-favicon/

	?>
	<link rel="apple-touch-icon" href="/favicon-144x144.png">
	<link rel="icon" href="/favicon-96x96.png">
	<!--[if IE]>
	<link rel="shortcut icon" href="/favicon.ico">
	<![endif]-->
	<meta name="msapplication-TileColor" content="#99cc33">
	<meta name="msapplication-TileImage" content="/favicon-144x144.png">
	<?php */

}


/**
 * Add custom classes to the array of body classes
 *
 * @since	Pilau_Starter 0.1
 */
//add_filter( 'body_class', 'pilau_body_class' );
function pilau_body_class( $classes ) {
	return $classes;
}
