<?php

	$file = hiweb_export()->file( isset( $_GET['file_data'] ) ? $_GET['file_data'] : ( isset( $_FILES['file_data']['tmp_name'] ) ? $_FILES['file_data']['tmp_name'] : false ) );
	if( $file->is_exist() ):
		wp_enqueue_script( 'hiweb-export-posts', plugin_dir_url( dirname( __FILE__ ) ) . '/inc/script.js', array( 'jquery' ), false, true );
		?>
		<div class="wrap">
			<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Import Tool → Select Post Types and Setup
				<small>(step 2 of 3)</small>
			</h1>
			<div>
				<div id="import-settings-process" class="postbox hidden" style="text-align: center; padding: 20px 0 100px 0;">
					<p class="dashicons dashicons-clock" style="font-size: 78px; margin-left: -78px"></p>
					<h1> Import in process</h1>
				</div>
				<div id="import-settings-success" class="postbox hidden" style="text-align: center; padding: 20px 0 100px 0;">
					<p class="dashicons dashicons-admin-post" style="font-size: 78px; margin-left: -78px"></p>
					<h1> Done!</h1>
					<p>Succes added: <b data-result="success"></b></p>
					<p>Errors: <b data-result="error"></b></p>
					<p>
						<a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button button-primary">OK</a>
					</p>
				</div>
				<div id="import-settings-error" class="postbox hidden" style="text-align: center; padding: 20px 0 100px 0;">
					<p class="dashicons dashicons-thumbs-down" style="font-size: 78px; margin-left: -78px"></p>
					<h1> Error :'</h1>
					<p><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ).'&mod=import&file_data='.$file->basename() ?>" class="button button-cancel">Retry...</a>
					</p>
				</div>
				<form id="import-settings-form" action="<?php echo admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE . '&mod=import&file_data=' . $file->basename() ) ?>" method="post">

					<table class="wp-list-table widefat fixed striped pages">
						<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" checked type="checkbox"></td>
							<th width="15%">Post Type</th>
							<th></th>
							<th>Import to Post type...</th>
							<th width="40%">Taxonomies</th>
						</tr>
						</thead>
						<tbody id="the-list">

						<?php foreach( $file->data() as $post_type_name => $posts_data ){
							$post_type = $file->post_type( $post_type_name );
							$posts_count = $file->posts_count( $post_type_name );
							$posts_count = $posts_count !== false ? $posts_count : count( $file->posts( $post_type_name ) );
							?>
							<tr data-post-type="<?php echo $post_type_name ?>">
								<th scope="row" class="check-column">
									<input type="checkbox" name="pt[]" checked id="cb-select-<?php echo $post_type_name ?>" value="<?php echo $post_type_name ?>">
									<div class="locked-indicator"></div>
								</th>
								<td>
									<b class="row-title"><?php echo $post_type['label']; ?></b>
									<div>posts: <?php echo $posts_count ?></div>
								</td>
								<td>
									<label><input type="checkbox" name="pts[<?php echo $post_type_name ?>][remove]"> clear before insert</label>
									<!--<select name="replace_key">
										<option value="">- not update -</option>
										<option value="ID">ID</option>
										<option value="post_title">post_title</option>
										<option value="post_date">post_date</option>
									</select>-->
								</td>
								<td>
									<select data-change="post_type" name="pts[<?php echo $post_type_name ?>][post_type]"><?php

											foreach( hiweb_export()->post_types() as $to_type => $ty_type_obj ){
												$selected = $post_type_name == $to_type ? 'selected' : '';
												?>
												<option <?php echo $selected ?> value="<?php echo $to_type ?>"><?php echo $ty_type_obj->get()->label . ' (' . $ty_type_obj->type() . ')' ?></option><?php
											}

										?></select>
								</td>
								<td>
									<?php foreach( $file->taxonomies( $post_type_name ) as $taxonomy_name => $taxonomy ){
										?>
										<p><?php echo $taxonomy['label'] ?> → <select data-reload="post_type" name="pts[<?php echo $post_type_name ?>][taxonomies][<?php echo $taxonomy_name ?>]"
										                                              id="taxonomy_<?php echo $post_type_name . '_' . $taxonomy_name ?>">
											<?php echo hiweb_export()->html()->select_options_taxonomies(); ?>
										</select></p><?php
									} ?>
								</td>
							</tr>
							<?php
						} ?>
						</tbody>
						<tfoot>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input checked id="cb-select-all-1" type="checkbox"></td>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						</tfoot>

					</table>
					<p>
						<button id="import-process" type="submit" class="button button-primary button-hero">Import Selected Posts</button>
					</p>
				</form>
			</div>
		</div>
		<script>
			window.history.pushState("", "", 'tools.php?page=<?php echo HW_EXPORT_POSTS_SLUG_PAGE ?>&mod=import&file_data=<?php echo $file->basename() ?>');
			var hiweb_export_ajax = "<?php echo plugin_dir_url( dirname( __FILE__ ) ) . 'ajax.php?file_data=' . $file->basename()?>";
		</script>
	<?php else: ?>
		<div class="wrap"><h1>File Error...</h1></div>
	<?php endif;