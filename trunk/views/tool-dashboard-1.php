<div class="wrap">
	<h1><?php _e('hiWeb Export / Import Posts Tool','hw_export_posts') ?></h1>
	<p class="describe"><?php _e( 'Choose what you want to do...', 'hw_export_posts' ) ?></p>
	<a href="?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=export" class="button button-hero button-primary"><i class="dashicons dashicons-migrate" style="font-size: 45px; width: 50px;height: 50px;"></i> <?php _e('Export to JSON file','hw_export_posts') ?></a>
	<a href="?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=import" class="button button-hero button-primary"><i class="dashicons dashicons-media-spreadsheet" style="font-size: 45px;width: 50px;height: 50px;""></i> <?php _e('Import from JSON file','hw_export_posts') ?></a>
	<!--<a href="?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=url" class="button button-hero button-primary"><i class="dashicons dashicons-admin-site" style="font-size: 45px;width: 50px;height: 50px;""></i> IMPORT POSTS DATA from URL</a>-->
	<a href="?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=history" class="button button-hero button-primary"><i class="dashicons dashicons-clock" style="font-size: 45px;width: 50px;height: 50px;""></i> <?php _e('Upload previously files','hw_export_posts') ?></a>
</div>