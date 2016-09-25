<?php

	if( !isset( $_GET['do'] ) && !isset( $_GET['file_data'] ) ){
		header( "HTTP/1.0 400 Bad Request" );
		//echo json_encode( 'Error: dosen\'t see require params...' );
		echo json_encode( $_POST );
	}else{

		///BASEDIR
		function hw_wp_basedir(){
			$full_path = getcwd();
			$ar = explode( "wp-", $full_path );
			return rtrim( $ar[0], '\\/' );
		}

		///INCLUDE
		set_include_path( hw_wp_basedir() );
		require_once 'wp-load.php';
		require_once 'hiweb-export.php';

		///DO ACTION
		if( isset( $_GET['file_data'] ) ){
			///File
			$file = hiweb_export()->file( $_GET['file_data'] );
			if( !$file->is_exist() ){
				header( "HTTP/1.0 404 Not found" );
				echo json_encode( 'Error: File [' . $_GET['file_data'] . '] no found!' );
			}else{
				echo json_encode( $file->process( $_POST ) );
			}
		}elseif( $_GET['do'] == 'download' && isset( $_GET['pt'] ) ){
			hiweb_export()->hooks()->posts_download();
		}elseif( $_GET['do'] == 'open' && isset( $_GET['pt'] ) ){
			hiweb_export()->hooks()->posts();
		}
	}

