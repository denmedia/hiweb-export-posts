<?php

	if(isset($_GET['remove'])){
		$removePath = HW_EXPORT_POSTS_DIR_UPLOAD.'/'.$_GET['remove'];
		if(file_exists($removePath)) @unlink($removePath);
	}

	$files_json = array();
	foreach( scandir( HW_EXPORT_POSTS_DIR_UPLOAD ) as $filename ){
		if( preg_match( '/(.json)$/i', $filename ) > 0 ){
			$files_json[ $filename ] = HW_EXPORT_POSTS_DIR_UPLOAD . '/' . $filename;
		}
	}

?>
<div class="wrap">
	<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Import from History</h1>
	<p></p>
	<?php if( count( $files_json ) == 0 ): ?>
		<h3>Files not found...</h3>
		<p>You mus <a href="<?php echo admin_url('tools.php?page='.HW_EXPORT_POSTS_SLUG_PAGE.'&mod=import'); ?>" class="button">first upload json file</a>, after that can see some hier...</p>
	<?php else: ?>
		<table class="wp-list-table widefat fixed striped pages">
			<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"></td>
				<th>Post Type</th>
				<th width="30%"></th>
			</tr>
			</thead>

			<tbody id="the-list"><?php foreach($files_json as $file){
				$baseName = basename($file);
				?>
				<tr>
					<th>

					</th>
					<td>
						<a href="<?php echo admin_url('tools.php?page='.HW_EXPORT_POSTS_SLUG_PAGE.'&mod=import&file_data='.$baseName) ?>" class="row-title"><?php echo $baseName ?></a>
						<div><?php echo date('Y.m.d - H:i:s', filemtime($file)).', '.round( filesize($file) / 1024 / 1024, 2 ).' Mb' ?></div>
					</td>
					<td style="text-align: right;">
						<a class="button button-primary" href="<?php echo admin_url('tools.php?page='.HW_EXPORT_POSTS_SLUG_PAGE.'&mod=import&file_data='.$baseName) ?>">Import Again...</a>
						<a class="button button-primary" href="<?php echo admin_url('admin-ajax.php?action=hw_export_posts&mod=import&file_data='.$baseName) ?>" target="_blank" title="Open JSON file"><i class="dashicons dashicons-share-alt2"></i></a>
						<a href="<?php echo admin_url('tools.php?page='.HW_EXPORT_POSTS_SLUG_PAGE.'&mod=history&remove='.$baseName) ?>" class="button button-primary" title="Remove File..."><i class="dashicons dashicons-trash"></i></a>
					</td>
				</tr>
				<?php

			} ?></tbody>

			<tfoot>
			<tr>
				<td></td>
				<th></th>
				<th></th>
			</tr>
			</tfoot>

		</table>
	<?php endif; ?>

</div>
