<?php
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:27
	 */

	if( !defined( 'HW_EXPORT_POSTS_DIR' ) )
		define( 'HW_EXPORT_POSTS_DIR', dirname( dirname( __FILE__ ) ) );
	if( !defined( 'HW_EXPORT_POSTS_URL_AJAX' ) )
		define( 'HW_EXPORT_POSTS_URL_AJAX', plugin_dir_url(dirname(__FILE__)).'ajax.php' );
	if( !defined( 'HW_EXPORT_POSTS_DIR_TEMPLATE' ) )
		define( 'HW_EXPORT_POSTS_DIR_TEMPLATE', HW_EXPORT_POSTS_DIR . '/template' );
	if( !defined( 'HW_EXPORT_POSTS_SLUG_PAGE' ) )
		define( 'HW_EXPORT_POSTS_SLUG_PAGE', 'hiweb-export-posts' );
	if( !defined( 'HW_EXPORT_POSTS_DIR_UPLOAD' ) ){
		$upload_dir = wp_upload_dir();
		define( 'HW_EXPORT_POSTS_DIR_UPLOAD', $upload_dir['basedir'] . '/' . HW_EXPORT_POSTS_SLUG_PAGE );
	}
