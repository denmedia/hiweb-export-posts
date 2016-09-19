<?php
	/**
	 * Created by PhpStorm.
	 * User: hiweb
	 * Date: 19.09.2016
	 * Time: 10:26
	 */

	add_action( 'admin_menu', array( hiweb_export()->hooks(), 'admin_menu' ) );