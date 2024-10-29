<h2><?php esc_html_e( 'Useful Admin Menu Settings' ); ?></h2>
<p><?php esc_html_e( 'All these settings personal for each user.' ); ?></p>

<form method="post" name="save_settings">
	<?php wp_nonce_field( 'save-settings' ); ?>
	<input type="hidden" name="always_show" value='<?php echo wp_json_encode( $settings['always_show'] ); ?>'>
	<input type="hidden" name="always_hide" value='<?php echo wp_json_encode( $settings['always_hide'] ); ?>'>

	<table class="form-table" role="presentation">
		<tr class="aham_always">
			<th scope="row"><label for="show_search"><?php esc_html_e( 'Show search' ); ?> </label></th>
			<td>
				<input name="show_menu_search" id="show_search" type="checkbox" value="1" <?php checked( 1 === $settings['show_menu_search'] ); ?> /> <?php esc_html_e( 'Show search box for menu items' ); ?>
			</td>
		</tr>

		<tr class="aham_always">
			<th scope="row"><label for="aham_enable"><?php esc_html_e( 'Enable hiding' ); ?> </label></th>
			<td>
				<input name="enable" id="aham_enable" type="checkbox" value="1" <?php checked( 1 === $settings['enable'] ); ?> /> <?php esc_html_e( 'Enable/Disable hiding menu items for your account' ); ?>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="start"><?php esc_html_e( 'Start in' ); ?> </label></th>
			<td>
				<input name="start_in_x_days" id="start" type="number" value="<?php echo esc_attr( $settings['start_in_x_days'] ); ?>" min="0" class="small-text" /> <?php esc_html_e( 'days' ); ?>
				<p class="description">
					<?php esc_html_e( 'How many days should pass when we finish collecting data and start hiding menu items?' ); ?><br>
					<?php esc_html_e(  'Set it to `0` if you want to hide menu items right now ( Note: in this case, all new items will hide by default ).' ); ?>
				</p>
			</td>
		</tr>
	<!--	<tr class="form-field">-->
		<tr>
			<th scope="row"><label for="hide_in"><?php esc_html_e( 'Hide in' ); ?> </label></th>
			<td>
				<input name="hide_without_clicks_in_x_days" id="hide_in" type="number" value="<?php echo esc_attr( $settings['hide_without_clicks_in_x_days'] ); ?>" min="1" class="small-text" /> <?php esc_html_e( 'days' ); ?>
				<p class="description">
					<?php esc_html_e( 'How many days should pass after the last click on a menu item to hide it?' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label for="show_updates"><?php esc_html_e( 'Show updates' ); ?> </label></th>
			<td>
				<input name="show_menu_items_with_updates" id="show_updates" type="checkbox" value="1" <?php checked( 1 === $settings['show_menu_items_with_updates'] ); ?> /> <?php esc_html_e( 'Show menu items with updates' ); ?>
				<p class="description">
					<?php esc_html_e( 'If this option is enabled you won\'t miss new plugins updates or new comments for approving.' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label><?php esc_html_e( 'Always Show' ); ?> </label></th>
			<td>
				<a href="#" class="open-aham-dialog" data-dialog_name="aham-show-dialog"><?php esc_html_e( 'Select Menu Items' ); ?></a>
				<div id="aham-show-dialog" class="aham-dialog hidden">
					<div id="aham-always-show">
					</div>
					<div>
						<?php submit_button( __( 'Save' ), 'primary', 'aham_save', false ); ?>
					</div>
				  </div>
				<p class="description">
					<?php esc_html_e( 'Select Menu items which should be always shown' ); ?>
				</p>
			</td>
		</tr>

		<tr>
			<th scope="row"><label><?php esc_html_e( 'Always Hide' ); ?> </label></th>
			<td>
				<a href="#" class="open-aham-dialog" data-dialog_name="aham-hide-dialog"><?php esc_html_e( 'Select Menu Items' ); ?></a>
				<div id="aham-hide-dialog" class="aham-dialog hidden">
					<div id="aham-always-hide">
					</div>
					<div>
						<?php submit_button( __( 'Save' ), 'primary', 'aham_save', false ); ?>
					</div>
				  </div>
				<p class="description">
					<?php esc_html_e( 'Select Menu items which should be always hidden' ); ?>
				</p>
			</td>
		</tr>

	</table>

	<p class="submit">
		<?php submit_button( __( 'Save settings' ), 'primary', 'save_settings', false ); ?>&nbsp;&nbsp;
		<?php submit_button( __( 'Reset settings' ), '', 'reset_settings', false ); ?>&nbsp;&nbsp;
		<?php submit_button( __( 'Reset clicks' ), '', 'reset_clicks', false ); ?>
	</p>

</form>
