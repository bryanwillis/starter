<?php

/**
 * Shortcodes
 *
 * Try to add TinyMCE buttons for shortcodes you create
 * @link https://gist.github.com/3156062
 *
 * @package Pilau_Starter
 * @since	0.1
 */


/**
 * Anchor link
 *
 * @since	Pilau_Starter 0.1
 * @uses	shortcode_atts()
 */
add_shortcode( 'anchor', 'pilau_anchor_link_shortcode' );
function pilau_anchor_link_shortcode( $atts ) {
	extract( shortcode_atts( array( "id" => '' ), $atts ) );
	return '<a name="' . $id . '"></a>';
}


/**
 * Expire content
 *
 * @since	Pilau_Starter 0.1
 * @link	http://crowdfavorite.com/wordpress/plugins/expiring-content-shortcode/
 */
add_shortcode( 'expires', 'pilau_expire_content_shortcode' );
function pilau_expire_content_shortcode( $args = array(), $content = '' ) {
	extract( shortcode_atts( array( 'on' => 'tomorrow' ), $args ) );
	if ( strtotime( $on ) > time() ) {
		return $content;
	}
	return '';
}
