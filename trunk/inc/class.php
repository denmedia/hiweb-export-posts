<?php


	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:26
	 */
	class hw_export{


		public function hooks(){
			static $class;
			if( !$class instanceof hw_export_hooks )
				$class = new hw_export_hooks();
			return $class;
		}


	}


	class hw_export_hooks{

		public function admin_menu(){
			add_submenu_page( 'tools.php', 'hiWeb Export Post', 'hiWeb Export Posts', 'publish_posts', HW_EXPORT_POSTS_SLUG_PAGE, array( $this, 'dashboard' ) );
		}


		public function dashboard(){
			if( !isset( $_GET['mod'] ) ){
				include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-1.php';
				return;
			}
			switch( $_GET['mod'] ){
				case 'export':
					include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-export-1.php';
					break;
				case 'import':
					include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-import-1.php';
					break;
				default:
					include_once HW_EXPORT_POSTS_DIR_TEMPLATE . '/tool-dashboard-1.php';
			}
		}

	}