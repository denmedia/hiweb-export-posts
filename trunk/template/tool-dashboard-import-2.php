<?php
	$file = hiweb_export()->file( $_FILES['file_data']['tmp_name'] );
	if( $file->is_exist() ):
		?>
		<div class="wrap">
			<h1><a href="<?php echo self_admin_url( 'tools.php?page=' . HW_EXPORT_POSTS_SLUG_PAGE ) ?>" class="button">←</a> hiWeb Export Tool</h1>
			<div>
				<form>
					<h2>2 / 3 - Select post convert.</h2>
					<p>filesize: <?php echo $file->size(); ?></p>
					<table class="wp-list-table widefat fixed striped pages">
						<thead>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
							<th>Post Type</th>
							<th>Posts Count</th>
							<th>Convert to Post type...</th>
							<th></th>
						</tr>
						</thead>
						<tbody id="the-list">

						<?php foreach( $file->data() as $post_type_name => $posts_data ){
							?>
							<tr>
								<th>
									<input type="checkbox" name="pt[]" id="cb-select-<?php echo $post_type_name ?>" value="<?php echo $post_type_name ?>">
									<div class="locked-indicator"></div>
								</th>
								<td>
									<?php echo $post_type_name ?>
								</td>
								<td>
									<?php echo count( $posts_data ) ?>
								</td>
								<td><select name=""><?php

											foreach( hiweb_export()->post_types() as $to_type => $ty_type_obj ){
												$selected = $post_type_name == $to_type ? 'selected' : '';
												?>
												<option <?php echo $selected ?> value="<?php echo $to_type ?>"><?php echo $ty_type_obj->get()->label . ' (' . $ty_type_obj->type() . ')' ?></option><?php
											}

										?></select></td>
								<td></td>
							</tr>
							<?php
						} ?>
						</tbody>
						<tfoot>
						<tr>
							<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
							<th></th>
							<th></th>
							<th></th>
							<th></th>
						</tr>
						</tfoot>

					</table>
					<p>
						<button type="submit" class="button button-primary">Import Selected Posts</button>
					</p>
				</form>
			</div>
		</div>
	<?php else: ?>
		<div class="wrap"><h1>File Error...</h1></div>
	<?php endif;