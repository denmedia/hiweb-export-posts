<?php
	$result = hiweb_export()->file( $_GET['file_data'] )->process( $_POST );
?>
<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Import Tool → Result
		<small>(step 3 of 3)</small>
	</h1>

	<div id="import-settings-success" class="postbox" style="text-align: center; padding: 20px 0 100px 0;">
		<p class="dashicons dashicons-admin-post" style="font-size: 78px; margin-left: -78px"></p>
		<h1> Done!</h1>
		<p>Succes added: <b data-result="success"><?php echo count( $result['success'] ) ?></b></p>
		<p>Errors: <b data-result="error"><?php echo count( $result['error'] ) ?></b></p>
		<p>
			<a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button button-primary">OK</a>
		</p>
	</div>
</div>
