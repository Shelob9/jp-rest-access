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
	 * Add 'offset' as a public valid var for the posts endpoint
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
	function jp_rest_access_post_vars( $valid_vars ) {

		$valid_vars[] = 'offset';

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
	 * @since 0.1.0
	 */
	add_filter( 'json_serve_request', 'jp_rest_access_cors_header' );
	function jp_rest_access_cors_header() {
		/**
		 * Filter CORS domains
		 *
		 * @param bool|string|array $domain Domain(s) to allow. Set a string for one domain, an array for multiple domains, or false for no header.
		 *
		 * @return bool|string|array
		 *
		 * @since 0.1.0
		 */
		$domain = apply_filters( 'jp_rest_access_cors', get_option( 'josie_api_cors', '*' ) );

		if ( $domain && is_string( $domain ) ) {
			$allow = $domain;
		}
		elseif( is_array( $domain ) && isset( $_SERVER['HTTP_ORIGIN'] ) ) {
			if ( $_SERVER['HTTP_ORIGIN'] && in_array( $_SERVER['HTTP_ORIGIN'], $domain ) ){
				$allow = $_SERVER['HTTP_ORIGIN'];
			}
		}
		else {
			$allow = false;
		}

		header( "Access-Control-Allow-Origin: {$allow}" );

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
		/**
		 * Set your max posts per page to allow on posts endpoint
		 *
		 *
		 * @since 0.1.0
		 *
		 * @param int $num Max number
		 *
		 * @return int
		 */
		$max_posts_per_page = apply_filters( 'jp_rest_access_max_posts_per_page', get_option( 'josie_api_max_posts_per_page', 20 ) );
		if ( $max_posts_per_page < intval( $posts_per_page )  ) {
			$posts_per_page = $max_posts_per_page ;
		}

		return $posts_per_page;

	}
endif;

if ( ! function_exists( 'jp_rest_access_set_defaults' ) ) :
	function jp_rest_access_set_defaults( $prefix ) {
		$options = array(
			'cors' => '*',
			'max_posts_per_page' => 20,
		);
		foreach( $options as $name => $default ) {
			$name = $prefix.$name;
			if( ! get_option( $name )){
				update_option( $name, $default );
			}

		}

	}
endif;
