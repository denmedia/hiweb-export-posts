<?php
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:26
	 */

	///OPTIONS
	add_action('init', array(hiweb_export()->hooks(),'load_textdomain'));
	add_action( 'admin_menu', array( hiweb_export()->hooks(), 'admin_menu' ) );
	add_filter('plugin_action_links', array(hiweb_export()->hooks(),'plugin_action_links'), 10, 4);
	///AJAX
	add_action( 'wp_ajax_hw_export_posts', array( hiweb_export()->hooks(), 'posts' ) );
	add_action( 'wp_ajax_hw_export_posts_html', array( hiweb_export()->hooks(), 'html' ) );
	add_action( 'wp_ajax_hw_export_posts_download', array( hiweb_export()->hooks(), 'posts_download' ) );
	add_action( 'wp_ajax_hw_export_posts_import', array( hiweb_export()->hooks(), 'posts_download' ) );