<div class="wrap autoblogging-pro-wrap">
    
    <div class="container mx-auto p-4 bg-white max-w-4xl mt-5 shadow-md rounded-lg">
        <h1 class="text-3xl font-bold text-center mb-6 text-gray-800">AutoBlogging Pro</h1>
        
        <?php if ( empty( $api_key ) ) : ?>
            <div class="text-center py-10">
                <p class="text-lg mb-6 text-gray-600">Connect to your AutoBlogging Pro account to get started.</p>
                <a href="<?php echo esc_url( $connect_api ) . '?redirect_to=' . base64_encode( admin_url( 'options-general.php?page=autoblogging-pro' ) ); ?>" 
                   class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg transition duration-200">
                    Connect Account
                </a>
            </div>
        <?php else : ?>
            
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <h2 class="text-xl font-semibold text-gray-700">Settings</h2>
                <div class="flex space-x-4">
                    <button class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded transition duration-200 btn_action" 
                            data-action="autoblogging_pro_fetch_now">
                        Fetch Now
                    </button>
                    <button class="bg-red-50 hover:bg-red-100 text-red-600 font-bold py-2 px-4 rounded transition duration-200 btn_action" 
                            data-action="autoblogging_pro_disconnect_api_key">
                        Disconnect
                    </button>
                </div>
            </div>

            <form method="post" action="options.php" class="space-y-6">
                <?php settings_fields( 'autoblogging_pro_settings_group' ); ?>
                
                <!-- Action Suggestion: We could make this UI nicer than standard valid form-table -->
                <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Publishing Options</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Default Action</label>
                        <div class="space-y-2">
                             <label class="inline-flex items-center">
                                <input type="radio" class="form-radio text-indigo-600" name="autoblogging_pro_action" value="draft" <?php checked( get_option( 'autoblogging_pro_action', 'draft' ), 'draft' ); ?>>
                                <span class="ml-2">Draft (No Publish)</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center">
                                <input type="radio" class="form-radio text-indigo-600" name="autoblogging_pro_action" value="publish" <?php checked( get_option( 'autoblogging_pro_action', 'draft' ), 'publish' ); ?>>
                                <span class="ml-2">Auto Publish</span>
                            </label>
                            <br>
                            <label class="inline-flex items-center" id="autoblogging_pro_action_schedule">
                                <input type="radio" class="form-radio text-indigo-600" name="autoblogging_pro_action" value="schedule" <?php checked( get_option( 'autoblogging_pro_action', 'draft' ), 'schedule' ); ?>>
                                <span class="ml-2">Auto Schedule</span>
                            </label>
                        </div>
                    </div>

                    <div class="autoblogging_pro_schedule_settings mt-4 pl-6 border-l-4 border-indigo-200" 
                         style="<?php echo $action !== 'schedule' ? 'display:none;' : ''; ?>">
                         <label class="block text-sm font-medium text-gray-700 mb-1">Daily Schedule Time</label>
                        <input type="time" name="autoblogging_pro_publish_time" 
                               value="<?php echo esc_attr( get_option( 'autoblogging_pro_publish_time' ) ); ?>" 
                               class="mt-1 block w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                        <p class="text-sm text-gray-500 mt-1">Posts will be scheduled for this time daily.</p>
                    </div>
                </div>

                <div class="pt-5">
                    <?php submit_button( 'Save Settings', 'primary', 'submit', false, ['class' => '!bg-indigo-600 !text-white !border-none !px-6 !py-2 !rounded !shadow-sm hover:!bg-indigo-700'] ); ?>
                </div>
            </form>

            <script>
                jQuery(document).ready(function($) {
                    var $scheduleSettings = $('.autoblogging_pro_schedule_settings');

                    $('input[name="autoblogging_pro_action"]').change(function() {
                        if ($(this).val() === 'schedule') {
                            $scheduleSettings.slideDown();
                        } else {
                            $scheduleSettings.slideUp();
                        }
                    });

                    // Ajax Action Handler
                    $('.btn_action').click(function(e) {
                        e.preventDefault();
                        var $btn = $(this);
                        var action = $btn.data('action');
                        var originalText = $btn.text();

                        $btn.prop('disabled', true).text('Processing...');

                        $.ajax({
                            url: ajaxurl,
                            type: 'POST',
                            data: {
                                action: action,
                                nonce: '<?php echo wp_create_nonce( "autoblogging_pro_ajax" ); ?>'
                            },
                            success: function(response) {
                                if (response.success) {
                                    if (action === 'autoblogging_pro_disconnect_api_key') {
                                        window.location.reload();
                                    } else {
                                        alert('Success: ' + (response.data || 'Action completed'));
                                    }
                                } else {
                                    alert('Error: ' + response.data);
                                }
                            },
                            error: function() {
                                alert('Network error occurred');
                            },
                            complete: function() {
                                $btn.prop('disabled', false).text(originalText);
                            }
                        });
                    });
                });
            </script>
        <?php endif; ?>
    </div>
</div>
