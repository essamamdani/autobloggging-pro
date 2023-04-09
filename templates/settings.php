<div class="container mx-auto p-4">
	<script src="https://cdn.tailwindcss.com"></script>

	<h1 class="text-center text-2xl mb-4">AutoBlogging Pro</h1>
	<p class="text-center mb-4">Connect to your AutoBlogging Pro account to get started.</p>
	<div class="flex justify-center">
		<?php if (empty($api_key)) : ?>
			<a href="<?php echo esc_url($connect_api) . '?redirect_to=' . base64_encode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded">Connect</a>
		<?php else : ?>
			<p class="text-green-500 font-bold">Connected to AutoBlogging Pro</p>
			<div class="wrap">
				<h1><?php esc_html_e('Autoblogging Pro Settings', 'autoblogging-pro'); ?></h1>

				<!-- disconnect api key with form -->
				<form method="post" action="options.php">
					<?php settings_fields('autoblogging_pro'); ?>
					<?php do_settings_sections('autoblogging_pro'); ?>
					<input type="hidden" name="autoblogging_pro_api_key" value="">
					<?php submit_button('Disconnect'); ?>
				</form>

				<!-- fetch now to run sync function in class-autobloggin-pro -->
				<form method="post" action="options.php">
					<?php settings_fields('autoblogging_pro'); ?>
					<?php do_settings_sections('autoblogging_pro'); ?>
					<input type="hidden" name="autoblogging_pro_fetch_now" value="1">
					<?php submit_button('Fetch Now'); ?>
				</form>


				<form method="post" action="options.php">
					<?php settings_fields('autoblogging_pro'); ?>
					<?php do_settings_sections('autoblogging_pro'); ?>
					<table class="form-table">
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Post Limit', 'autoblogging-pro'); ?></th>
							<td>
								<input type="number" name="autoblogging_pro_schedule_limit" min="1" step="1" value="<?php echo esc_attr(get_option('autoblogging_pro_schedule_limit', 1)); ?>" class="regular-text">
								<p class="description"><?php esc_html_e('Enter the maximum number of articles to publish per day', 'autoblogging-pro'); ?></p>
							</td>
						</tr>
						<tr valign="top">
							<th scope="row"><?php esc_html_e('Action', 'autoblogging-pro'); ?></th>
							<td>
								<fieldset>
									<legend class="screen-reader-text"><span><?php esc_html_e('Action', 'autoblogging-pro'); ?></span></legend>
									<label for="autoblogging_pro_action_draft">
										<input name="autoblogging_pro_action" type="radio" id="autoblogging_pro_action_draft" value="draft" <?php checked(get_option('autoblogging_pro_action', 'draft'), 'draft'); ?>>
										<?php esc_html_e('No Action (Draft)', 'autoblogging-pro'); ?>
									</label><br>
									<label for="autoblogging_pro_action_publish">
										<input name="autoblogging_pro_action" type="radio" id="autoblogging_pro_action_publish" value="publish" <?php checked(get_option('autoblogging_pro_action', 'draft'), 'publish'); ?>>
										<?php esc_html_e('Auto Publish', 'autoblogging-pro'); ?>
									</label><br>
									<label for="autoblogging_pro_action_schedule">
										<input name="autoblogging_pro_action" type="radio" id="autoblogging_pro_action_schedule" value="schedule" <?php checked(get_option('autoblogging_pro_action', 'draft'), 'schedule'); ?>>
										<?php esc_html_e('Auto Schedule', 'autoblogging-pro'); ?>
									</label>
								</fieldset>
							</td>
						</tr>
						<tr valign="top" class="autoblogging_pro_schedule_settings">
							<th scope="row"><?php esc_html_e('Daily Publish Time', 'autoblogging-pro'); ?></th>
							<td>
								<input type="time" name="autoblogging_pro_schedule_time" value="<?php echo esc_attr(get_option('autoblogging_pro_schedule_time')); ?>" class="regular-text">
								<p class="description"><?php esc_html_e('Daily time when the scheduled posts should be published', 'autoblogging-pro'); ?></p>
							</td>
						</tr>
					</table>
					<?php submit_button(); ?>




				</form>
				<script>
					jQuery(document).ready(function($) {
						// Hide the Schedule Interval and Schedule Time fields on load

						<?php if ($action !== 'schedule') : ?>
							$('.autoblogging_pro_schedule_settings').hide();
						<?php endif; ?>

						// Show the Schedule Interval and Schedule Time fields when Auto Schedule is selected
						$('#autoblogging_pro_action_schedule').click(function() {
							$('.autoblogging_pro_schedule_settings').show();
						});

						// Hide the Schedule Interval and Schedule Time fields when No Action or Auto Publish is selected
						$('#autoblogging_pro_action_draft, #autoblogging_pro_action_publish').click(function() {
							$('.autoblogging_pro_schedule_settings').hide();
						});
					});
				</script>
			</div>
		<?php endif; ?>
	</div>
</div>