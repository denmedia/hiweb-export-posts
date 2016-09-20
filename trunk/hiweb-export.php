<?php
	/**
	 * Plugin Name: hiWeb Export Posts
	 * Description: Export Selected Posts in to JSON file and Import in to other WordPress Site.
	 * Version: 0.6.0.0
	 * Author: Den Media
	 * Author URI: http://hiweb.moscow
	 */
	
	if( !function_exists( 'hiweb_export' ) ):

		/**
		 * Работа с корневым классом плагина
		 * @return hw_export
		 */
		function hiweb_export(){
			static $class;
			if( !$class instanceof hw_export )
				$class = new hw_export();
			return $class;
		}
		
		include_once 'inc/define.php';
		include_once 'inc/class.php';
		include_once 'inc/hooks.php';

	endif;