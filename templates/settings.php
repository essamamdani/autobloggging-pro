<div class="wrap autoblogging-pro-wrap">
    <div class="abp-settings-container">

        <!-- Header -->
        <header class="abp-header">
            <div class="abp-logo">
                <div class="abp-logo-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                </div>
                <div class="abp-logo-text">
                    <h1>AutoBlogging Pro</h1>
                    <span>Automated Content Publishing</span>
                </div>
            </div>
        </header>

        <?php if ( empty( $api_key ) ) : ?>
            <!-- Not Connected State -->
            <div class="abp-card abp-card-connect">
                <div class="abp-connect-content">
                    <div class="abp-connect-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.19 8.688a4.5 4.5 0 011.242 7.244l-4.5 4.5a4.5 4.5 0 01-6.364-6.364l1.757-1.757m13.35-.622l1.757-1.757a4.5 4.5 0 00-6.364-6.364l-4.5 4.5a4.5 4.5 0 001.242 7.244"></path>
                        </svg>
                    </div>
                    <h2>Connect Your Account</h2>
                    <p>Link your AutoBlogging Pro account to start publishing automated content directly to your WordPress site.</p>

                    <a href="<?php echo esc_url( $connect_api ) . '?redirect_to=' . base64_encode( admin_url( 'options-general.php?page=autoblogging-pro' ) ); ?>" class="abp-btn abp-btn-primary abp-btn-lg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                        </svg>
                        Connect Account
                    </a>
                </div>

                <div class="abp-connect-footer">
                    <span>Don't have an account?</span>
                    <a href="https://www.autoblogging.pro" target="_blank" rel="noopener">
                        Create one at autoblogging.pro
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                        </svg>
                    </a>
                </div>
            </div>
        <?php else : ?>

            <!-- Connected Status -->
            <div class="abp-status-bar abp-status-connected">
                <div class="abp-status-info">
                    <div class="abp-status-icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div class="abp-status-text">
                        <strong>Connected</strong>
                        <span>Your account is linked and syncing</span>
                    </div>
                </div>
                <div class="abp-status-actions">
                    <button type="button" class="abp-btn abp-btn-secondary btn_action" data-action="autoblogging_pro_fetch_now">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Sync Now
                    </button>
                    <button type="button" class="abp-btn abp-btn-danger-outline btn_action" data-action="autoblogging_pro_disconnect_api_key">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        Disconnect
                    </button>
                </div>
            </div>

            <!-- Settings Form -->
            <form method="post" action="options.php" class="abp-settings-form">
                <?php settings_fields( 'autoblogging_pro_settings_group' ); ?>

                <div class="abp-card">
                    <div class="abp-card-header">
                        <div class="abp-card-icon">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                        </div>
                        <div class="abp-card-title">
                            <h3>Publishing Settings</h3>
                            <p>Configure how articles are published to your site</p>
                        </div>
                    </div>

                    <div class="abp-card-body">
                        <div class="abp-field-group">
                            <label class="abp-label">Default Publishing Action</label>
                            <div class="abp-radio-group">
                                <label class="abp-radio-card <?php echo get_option( 'autoblogging_pro_action', 'draft' ) === 'draft' ? 'selected' : ''; ?>">
                                    <input type="radio" name="autoblogging_pro_action" value="draft" <?php checked( get_option( 'autoblogging_pro_action', 'draft' ), 'draft' ); ?>>
                                    <div class="abp-radio-content">
                                        <div class="abp-radio-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div class="abp-radio-text">
                                            <strong>Save as Draft</strong>
                                            <span>Review before publishing</span>
                                        </div>
                                    </div>
                                    <div class="abp-radio-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </label>

                                <label class="abp-radio-card <?php echo get_option( 'autoblogging_pro_action', 'draft' ) === 'publish' ? 'selected' : ''; ?>">
                                    <input type="radio" name="autoblogging_pro_action" value="publish" <?php checked( get_option( 'autoblogging_pro_action', 'draft' ), 'publish' ); ?>>
                                    <div class="abp-radio-content">
                                        <div class="abp-radio-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="abp-radio-text">
                                            <strong>Publish Immediately</strong>
                                            <span>Go live right away</span>
                                        </div>
                                    </div>
                                    <div class="abp-radio-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </label>

                                <label class="abp-radio-card <?php echo get_option( 'autoblogging_pro_action', 'draft' ) === 'schedule' ? 'selected' : ''; ?>">
                                    <input type="radio" name="autoblogging_pro_action" value="schedule" <?php checked( get_option( 'autoblogging_pro_action', 'draft' ), 'schedule' ); ?>>
                                    <div class="abp-radio-content">
                                        <div class="abp-radio-icon">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <div class="abp-radio-text">
                                            <strong>Schedule</strong>
                                            <span>Publish at a set time</span>
                                        </div>
                                    </div>
                                    <div class="abp-radio-check">
                                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="abp-schedule-settings" style="<?php echo $action !== 'schedule' ? 'display:none;' : ''; ?>">
                            <div class="abp-field-group">
                                <label class="abp-label" for="abp-publish-time">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Daily Schedule Time
                                </label>
                                <input type="time"
                                       id="abp-publish-time"
                                       name="autoblogging_pro_publish_time"
                                       value="<?php echo esc_attr( get_option( 'autoblogging_pro_publish_time' ) ); ?>"
                                       class="abp-input abp-input-time">
                                <p class="abp-field-hint">Articles will be scheduled to publish at this time each day.</p>
                            </div>
                        </div>
                    </div>

                    <div class="abp-card-footer">
                        <button type="submit" class="abp-btn abp-btn-primary">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Settings
                        </button>
                    </div>
                </div>
            </form>

            <script>
                jQuery(document).ready(function($) {
                    var $scheduleSettings = $('.abp-schedule-settings');
                    var $radioCards = $('.abp-radio-card');

                    // Handle radio card selection
                    $('input[name="autoblogging_pro_action"]').change(function() {
                        $radioCards.removeClass('selected');
                        $(this).closest('.abp-radio-card').addClass('selected');

                        if ($(this).val() === 'schedule') {
                            $scheduleSettings.slideDown(200);
                        } else {
                            $scheduleSettings.slideUp(200);
                        }
                    });

                    // Ajax Action Handler
                    $('.btn_action').click(function(e) {
                        e.preventDefault();
                        var $btn = $(this);
                        var action = $btn.data('action');
                        var $svg = $btn.find('svg').first();
                        var originalSvg = $svg.prop('outerHTML');

                        $btn.prop('disabled', true).addClass('loading');
                        $svg.replaceWith('<svg class="abp-spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10" stroke-dasharray="32" stroke-dashoffset="32"><animateTransform attributeName="transform" type="rotate" from="0 12 12" to="360 12 12" dur="1s" repeatCount="indefinite"/></circle></svg>');

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
                                        showNotification('success', response.data || 'Action completed successfully');
                                    }
                                } else {
                                    showNotification('error', response.data || 'An error occurred');
                                }
                            },
                            error: function() {
                                showNotification('error', 'Network error occurred');
                            },
                            complete: function() {
                                $btn.prop('disabled', false).removeClass('loading');
                                $btn.find('svg').first().replaceWith(originalSvg);
                            }
                        });
                    });

                    function showNotification(type, message) {
                        var $notification = $('<div class="abp-notification abp-notification-' + type + '">' +
                            '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">' +
                            (type === 'success'
                                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                                : '<path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>'
                            ) +
                            '</svg>' +
                            '<span>' + message + '</span>' +
                            '</div>');

                        $('.abp-settings-container').prepend($notification);

                        setTimeout(function() {
                            $notification.addClass('show');
                        }, 10);

                        setTimeout(function() {
                            $notification.removeClass('show');
                            setTimeout(function() {
                                $notification.remove();
                            }, 300);
                        }, 4000);
                    }
                });
            </script>
        <?php endif; ?>
    </div>
</div>

<style>
/* AutoBlogging Pro Settings Styles */
.abp-settings-container {
    max-width: 720px;
    margin: 40px auto;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
}

/* Header */
.abp-header {
    margin-bottom: 32px;
}

.abp-logo {
    display: flex;
    align-items: center;
    gap: 16px;
}

.abp-logo-icon {
    width: 56px;
    height: 56px;
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    border-radius: 14px;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 14px rgba(99, 102, 241, 0.35);
}

.abp-logo-icon svg {
    width: 28px;
    height: 28px;
    color: white;
}

.abp-logo-text h1 {
    font-size: 24px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 2px 0;
    padding: 0;
}

.abp-logo-text span {
    font-size: 14px;
    color: #64748b;
}

/* Cards */
.abp-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08), 0 4px 12px rgba(0, 0, 0, 0.04);
    border: 1px solid #e2e8f0;
    overflow: hidden;
}

.abp-card-connect {
    text-align: center;
    padding: 48px 32px;
}

.abp-connect-icon {
    width: 80px;
    height: 80px;
    background: linear-gradient(135deg, #ede9fe 0%, #e0e7ff 100%);
    border-radius: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 24px;
}

.abp-connect-icon svg {
    width: 40px;
    height: 40px;
    color: #6366f1;
}

.abp-connect-content h2 {
    font-size: 22px;
    font-weight: 700;
    color: #1e293b;
    margin: 0 0 12px 0;
}

.abp-connect-content p {
    font-size: 15px;
    color: #64748b;
    max-width: 400px;
    margin: 0 auto 28px;
    line-height: 1.6;
}

.abp-connect-footer {
    margin-top: 32px;
    padding-top: 24px;
    border-top: 1px solid #e2e8f0;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.abp-connect-footer span {
    color: #94a3b8;
    font-size: 14px;
}

.abp-connect-footer a {
    color: #6366f1;
    font-weight: 500;
    font-size: 14px;
    text-decoration: none;
    display: inline-flex;
    align-items: center;
    gap: 4px;
    transition: color 0.2s;
}

.abp-connect-footer a:hover {
    color: #4f46e5;
}

.abp-connect-footer svg {
    width: 14px;
    height: 14px;
}

/* Status Bar */
.abp-status-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 24px;
}

.abp-status-connected {
    background: linear-gradient(135deg, #ecfdf5 0%, #d1fae5 100%);
    border: 1px solid #a7f3d0;
}

.abp-status-info {
    display: flex;
    align-items: center;
    gap: 12px;
}

.abp-status-icon {
    width: 40px;
    height: 40px;
    background: #10b981;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.abp-status-icon svg {
    width: 20px;
    height: 20px;
    color: white;
}

.abp-status-text strong {
    display: block;
    font-size: 15px;
    font-weight: 600;
    color: #065f46;
}

.abp-status-text span {
    font-size: 13px;
    color: #047857;
}

.abp-status-actions {
    display: flex;
    gap: 10px;
}

/* Buttons */
.abp-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    padding: 10px 18px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
    text-decoration: none;
}

.abp-btn svg {
    width: 18px;
    height: 18px;
}

.abp-btn-lg {
    font-size: 15px;
    padding: 14px 28px;
    border-radius: 12px;
}

.abp-btn-lg svg {
    width: 20px;
    height: 20px;
}

.abp-btn-primary {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    box-shadow: 0 2px 8px rgba(99, 102, 241, 0.35);
}

.abp-btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.45);
}

.abp-btn-secondary {
    background: #fff;
    color: #4f46e5;
    border: 1px solid #c7d2fe;
}

.abp-btn-secondary:hover {
    background: #eef2ff;
    border-color: #a5b4fc;
}

.abp-btn-danger-outline {
    background: transparent;
    color: #dc2626;
    border: 1px solid #fecaca;
}

.abp-btn-danger-outline:hover {
    background: #fef2f2;
    border-color: #fca5a5;
}

.abp-btn:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none !important;
}

.abp-btn.loading {
    pointer-events: none;
}

/* Card Header */
.abp-card-header {
    display: flex;
    align-items: flex-start;
    gap: 16px;
    padding: 24px;
    border-bottom: 1px solid #f1f5f9;
}

.abp-card-icon {
    width: 44px;
    height: 44px;
    background: #f1f5f9;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.abp-card-icon svg {
    width: 22px;
    height: 22px;
    color: #64748b;
}

.abp-card-title h3 {
    font-size: 17px;
    font-weight: 600;
    color: #1e293b;
    margin: 0 0 4px 0;
}

.abp-card-title p {
    font-size: 14px;
    color: #64748b;
    margin: 0;
}

/* Card Body */
.abp-card-body {
    padding: 24px;
}

/* Card Footer */
.abp-card-footer {
    padding: 20px 24px;
    background: #f8fafc;
    border-top: 1px solid #f1f5f9;
}

/* Form Fields */
.abp-field-group {
    margin-bottom: 24px;
}

.abp-field-group:last-child {
    margin-bottom: 0;
}

.abp-label {
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 14px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 12px;
}

.abp-label svg {
    width: 16px;
    height: 16px;
    color: #6366f1;
}

.abp-field-hint {
    font-size: 13px;
    color: #94a3b8;
    margin: 8px 0 0 0;
}

/* Radio Cards */
.abp-radio-group {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.abp-radio-card {
    display: flex;
    align-items: center;
    padding: 16px;
    background: #fff;
    border: 2px solid #e2e8f0;
    border-radius: 12px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
}

.abp-radio-card:hover {
    border-color: #c7d2fe;
    background: #fafafa;
}

.abp-radio-card.selected {
    border-color: #6366f1;
    background: #f5f3ff;
}

.abp-radio-card input[type="radio"] {
    position: absolute;
    opacity: 0;
    pointer-events: none;
}

.abp-radio-content {
    display: flex;
    align-items: center;
    gap: 14px;
    flex: 1;
}

.abp-radio-icon {
    width: 40px;
    height: 40px;
    background: #f1f5f9;
    border-radius: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.abp-radio-card.selected .abp-radio-icon {
    background: #6366f1;
}

.abp-radio-icon svg {
    width: 20px;
    height: 20px;
    color: #64748b;
    transition: color 0.2s;
}

.abp-radio-card.selected .abp-radio-icon svg {
    color: white;
}

.abp-radio-text strong {
    display: block;
    font-size: 14px;
    font-weight: 600;
    color: #1e293b;
    margin-bottom: 2px;
}

.abp-radio-text span {
    font-size: 13px;
    color: #64748b;
}

.abp-radio-check {
    width: 24px;
    height: 24px;
    border: 2px solid #e2e8f0;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}

.abp-radio-card.selected .abp-radio-check {
    background: #6366f1;
    border-color: #6366f1;
}

.abp-radio-check svg {
    width: 14px;
    height: 14px;
    color: white;
    opacity: 0;
    transition: opacity 0.2s;
}

.abp-radio-card.selected .abp-radio-check svg {
    opacity: 1;
}

/* Schedule Settings */
.abp-schedule-settings {
    margin-top: 20px;
    padding: 20px;
    background: #f8fafc;
    border-radius: 12px;
    border-left: 3px solid #6366f1;
}

/* Inputs */
.abp-input {
    display: block;
    width: 100%;
    max-width: 200px;
    padding: 12px 14px;
    font-size: 14px;
    border: 2px solid #e2e8f0;
    border-radius: 10px;
    background: #fff;
    transition: all 0.2s;
    color: #1e293b;
}

.abp-input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15);
}

/* Notifications */
.abp-notification {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 14px 18px;
    border-radius: 12px;
    margin-bottom: 20px;
    opacity: 0;
    transform: translateY(-10px);
    transition: all 0.3s ease;
}

.abp-notification.show {
    opacity: 1;
    transform: translateY(0);
}

.abp-notification svg {
    width: 20px;
    height: 20px;
    flex-shrink: 0;
}

.abp-notification-success {
    background: #ecfdf5;
    color: #065f46;
    border: 1px solid #a7f3d0;
}

.abp-notification-error {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
}

/* Spinner */
.abp-spinner {
    animation: abp-spin 1s linear infinite;
}

@keyframes abp-spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 640px) {
    .abp-settings-container {
        margin: 20px;
    }

    .abp-status-bar {
        flex-direction: column;
        gap: 16px;
        text-align: center;
    }

    .abp-status-info {
        flex-direction: column;
    }

    .abp-status-actions {
        width: 100%;
        justify-content: center;
    }

    .abp-radio-card {
        flex-direction: column;
        text-align: center;
        gap: 12px;
    }

    .abp-radio-content {
        flex-direction: column;
    }

    .abp-radio-check {
        position: absolute;
        top: 12px;
        right: 12px;
    }
}
</style>
