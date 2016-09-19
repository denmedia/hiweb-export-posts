<?php
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:26
	 */

	///OPTIONS
	add_action( 'admin_menu', array( hiweb_export()->hooks(), 'admin_menu' ) );
	///AJAX
	add_action( 'wp_ajax_hw_export_posts', array( hiweb_export()->hooks(), 'posts' ) );
	add_action( 'wp_ajax_hw_export_posts_download', array( hiweb_export()->hooks(), 'posts_download' ) );