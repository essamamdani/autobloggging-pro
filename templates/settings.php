<div class="container mx-auto p-4">
	<script src="https://cdn.tailwindcss.com"></script>
	<h1 class="text-center text-2xl mb-4">AutoBlogging Pro</h1>
	<p class="text-center mb-4">Connect to your AutoBlogging Pro account to get started.</p>
	<div class="flex justify-center">
		<?php if (empty($api_key)) : ?>
			<a href="<?php echo esc_url($connect_api) . '?redirect_to=' . base64_encode('https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']); ?>" class="bg-indigo-600 text-white font-bold py-2 px-4 rounded">Connect</a>
		<?php else : ?>
			<div class="wrap">
				<h1><?php esc_html_e('Autoblogging Pro Settings', 'autoblogging-pro'); ?></h1>

				<div class="flex justify-between">
					<!-- disconnect api key with form -->
					<button class="btn btn-danger text-white font-bold py-2 px-4 rounded btn_action" data-action="autoblogging_pro_disconnect_api_key"><?php esc_html_e("Disconnect") ?></button>

					<button class="btn btn-primary text-white font-bold py-2 px-4 rounded btn_action" data-action="autoblogging_pro_fetch_now"><?php esc_html_e("Fetch now") ?></button>

				</div>
				<form method="post" action="options.php">
					<?php settings_fields('autoblogging_pro_settings_group'); ?>
					<table class="form-table">
						<?php do_settings_sections('autoblogging_pro_settings_group'); ?>
					</table>
					<?php submit_button(); ?>
				</form>
				<script>
					jQuery(document).ready(function($) {

						<?php if ($action !== 'schedule') : ?>
							// Hide the Schedule Interval and Schedule Time fields on load
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

						// button action
						$('.btn_action').click(function() {
							var action = $(this).data('action');
							$.ajax({
								url: '<?php echo admin_url('admin-ajax.php'); ?>',
								type: 'POST',
								data: {
									action: action,
								},
								success: function(response) {
									if (response.success) {
										if (action == 'autoblogging_pro_disconnect_api_key') {
											window.location.reload();
										} else {
											alert('Fetching now');
										}

									}
								}
							});
						});
					});
				</script>
			</div>
		<?php endif; ?>
	</div>
</div>