<?php
/**
 * Adds access control filters for WordPress REST API (WP-API)
 *
 * @package   jp-rest-access
 * @author    Josh Pollock <Josh@JoshPress.net>
 * @license   GPL-2.0+
 * @link      
 * @copyright 2014 Josh Pollock
 *
 * @version 0.1.0
 */

if ( ! function_exists( 'jp_rest_access_post_vars') ) :
	/**
	 * Add additional public valid vars for the posts endpoint
	 *
	 * @since 0.1.0
	 *
	 * @uses 'json_query_vars' filter
	 *
	 * @param $valid_vars
	 *
	 * @return array
	 */
	add_filter( 'json_query_vars', 'jp_rest_access_post_vars' );

	function jp_rest_access_post_vars() {
		$valid_vars = array_merge( $this->valid_vars );

		return $valid_vars;
	}
endif;

if ( ! function_exists( 'jp_rest_access_cors_header') ) :
	/**
	 * Set cross-orgin headers
	 *
	 * @since 0.1.0
	 *
	 * @uses 'json_serve_request' filter
	 *
	 * @TODO array of domains/ match to HTTP referrer
	 *
	 * @since 0.1.0
	 */
	add_filter( 'json_serve_request', 'jp_rest_access_cors_header' );
	function jp_rest_access_cors_header() {
		$domain = apply_filters( 'jp_rest_access_cors', '*' );
		if ( $domain && is_string( $domain ) ) {
			header( "Access-Control-Allow-Origin: {$domain}" );
		}

	}
endif;

if ( ! function_exists( 'jp_rest_access_posts_per_page' ) ) :
	/**
	 * Prevent posts_per_page filter from exceeding a specific amount to prevent DDOS attacks via that vector.
	 *
	 * @since 0.1.0
	 *
	 * @uses 'json_query_var-posts_per_page' filter
	 *
	 * @param $posts_per_page
	 *
	 * @return mixed|void
	 */
	add_filter( 'json_query_var-posts_per_page', 'jp_rest_access_posts_per_page' );
	function jp_rest_access_posts_per_page( $posts_per_page ) {
		$max_posts_per_page = apply_filters( 'jp_rest_access_max_posts_per_page', 20 );
		if ( $max_posts_per_page < intval( $posts_per_page )  ) {
			$posts_per_page = $max_posts_per_page ;
		}

		return $posts_per_page;

	}
endif;
