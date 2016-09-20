<?php
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 20.09.2016
	 * Time: 10:01
	 */

	if( !isset( $_GET['file_data'] ) || !isset( $_POST['pt'] ) ){
		header( "HTTP/1.0 400 Bad Request" );
		//echo json_encode( 'Error: dosen\'t see require params...' );
		echo json_encode( $_POST );
	}else{

		function hw_wp_basedir(){
			$full_path = getcwd();
			$ar = explode( "wp-", $full_path );
			return rtrim( $ar[0], '\\/' );
		}

		//define( WP_PLUGIN_DIR, '' );
		define( 'WP_DISABLE_PLUGINS', true );

		define( 'WP_USE_THEMES', false );

		set_include_path( hw_wp_basedir() );
		require( 'wp-blog-header.php' );

		///
		require 'hiweb-export.php';
		///File
		$file = hiweb_export()->file( $_GET['file_data'] );
		if( !$file->is_exist() ){
			header( "HTTP/1.0 404 Not found" );
			echo json_encode( 'Error: File [' . $_GET['file_data'] . '] no found!' );
		}else{
			echo json_encode( $file->process( $_POST ) );
		}
	}

