<?php
$actions = \Ajaxy\Forms\Inc\Actions::getInstance()->get_actions();
$values = [];
try {
    $values = $form && isset($form['actions']) ? json_decode($form['actions'], true) : [];
} catch (\Exception $e) {
    $values = [];
}

?>
<div class="wp-clearfix af-actions-frame">
    <div class="af-management-liquid">
        <div class="af-management">
            <h2><?php esc_html_e('Form Actions', 'ajaxy-forms'); ?></h2>
            <div id="post-body">
                <div id="post-body-content" class="wp-clearfix">
                    <?php
                    $starter_copy = __('Use the actions below to send notifications or to execute a specific command');
                    ?>
                    <div class="drag-instructions post-body-plain">
                        <p><?php echo esc_html($starter_copy); ?></p>
                    </div>
                    <?php include(AJAXY_FORMS_PLUGIN_DIR . '/admin/inc/form/templates/placeholders.tpl.php'); ?>
                    <ul class="ui-sortable af-actions-list">
                        <?php foreach ($actions as $action_name => $action) : ?>
                            <?php include(AJAXY_FORMS_PLUGIN_DIR . '/admin/inc/form/templates/action.tpl.php'); ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    jQuery(document).ready(function($) {
        var timeout = null;
        var toast = $('.af-toast');
        $('.af-toast').on('click', function() {
            toast.fadeOut();
        });

        $('.af-placeholders-toggle').on('click', function() {
            var body = $(this).closest('.af-placeholders').find('.af-placeholders-body');
            var open = body.is('[hidden]');
            body.prop('hidden', !open);
            $(this).attr('aria-expanded', open ? 'true' : 'false').text(open ? '<?php echo esc_js(__('hide', 'ajaxy-forms')); ?>' : '<?php echo esc_js(__('show', 'ajaxy-forms')); ?>');
        });

        $('.af-placeholders').on('click keydown', 'code.af-copy', function(e) {
            if (e.type === 'keydown' && e.key !== 'Enter' && e.key !== ' ') return;
            e.preventDefault();
            var code = this;
            var text = $(code).text();
            var done = function() {
                $(code).addClass('af-copied');
                setTimeout(function() { $(code).removeClass('af-copied'); }, 1200);
            };
            if (navigator.clipboard && navigator.clipboard.writeText) {
                navigator.clipboard.writeText(text).then(done, function() {});
                return;
            }
            var temp = $('<textarea>').val(text).appendTo('body').select();
            try { document.execCommand('copy'); done(); } catch (err) {}
            temp.remove();
        });

        $('.af-actions-list .item-edit').on('click', function(e) {
            e.preventDefault();
            $(this).closest('li').find('.wrap-settings').toggleClass('active');
        });

        $('.af-actions-list li .af-toggle-enabled').on('change', function() {
            var checked = $(this).is(':checked');
            $(this).closest('li.af-form-item').toggleClass('is-enabled', checked);
            $(this).closest('form').find('button[type="submit"]').click();
        });
        $('.af-actions-list li form').submit(function(event) {
            event.preventDefault(); // Prevent default form submission

            var formData = $(this).serialize(); // Get form data

            toast.fadeOut(10);
            toast.removeClass(['af-success', 'af-error', 'af-info', 'af-warning']);
            var button = $('button[type="submit"]', this);
            button.prop('disabled', true).addClass('af-loading');
            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    button.prop('disabled', false).removeClass('af-loading');
                    if (response.status == 'success') {
                        toast.addClass('af-success');
                        toast.html('<span class="dashicons dashicons-info"></span>' + response.message);
                    } else {
                        toast.addClass('af-error');
                        toast.html('<span class="dashicons dashicons-warning"></span>' + response.message);
                    }
                    toast.fadeIn();

                    timeout && clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        toast.fadeOut();
                    }, 5000);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    button.prop('disabled', false).removeClass('af-loading');
                    toast.addClass('af-error');
                    toast.html('<span class="dashicons dashicons-warning"></span>' + jqXHR.responseText);
                    toast.fadeIn();

                    timeout && clearTimeout(timeout);
                    timeout = setTimeout(function() {
                        toast.fadeOut();
                    }, 5000);
                }
            });
        });
    });
</script>