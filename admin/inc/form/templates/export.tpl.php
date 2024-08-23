<?php

use Ajaxy\Forms\Inc\Data;
use Ajaxy\Forms\Inc\Helper;

$form_id = sanitize_text_field($_GET['form']);
$form = Data::get_form($form_id);


$metadata = $form['metadata'] ?? '';
$struct = json_decode($metadata, true);
// $json = json_encode($metadata, JSON_PRETTY_PRINT);

$json = Helper::create_tree_list($struct);

?>
<div class="wp-clearfix af-actions-frame">
    <div class="af-management-liquid">
        <div class="af-management">
            <h2><?php esc_html_e('Export', 'ajaxy-forms'); ?></h2>
            <div>
                <div class="wp-clearfix">
                    <?php $starter_copy = __('Copy the below json to import it to another form'); ?>
                    <div class="drag-instructions post-body-plain">
                        <p><?php echo esc_html($starter_copy); ?></p>
                    </div>
                    <?php echo wp_kses($json, ['ul' => ['class' => []], 'li'=> ['class' => []], 'span'=> ['class' => []], 'a'=> ['class' => []]]); ?>
                    <textarea id="af-export-json" style="display:none" readonly class="widefat form-table af-export" rows="10"><?php echo esc_html($metadata); ?></textarea>

                    <div class="form-actions" style="margin-top: 30px">
                        <a class="button" onclick="copyExport(event)" href="#"><?php esc_html_e('Copy Export Fields', 'ajaxy-forms'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <hr style="margin: 30px 0" />
        <div class="af-management">
            <h2><?php esc_html_e('Import', 'ajaxy-forms'); ?></h2>
            <form method="post">
                <div class="wp-clearfix">
                    <?php
                    $starter_copy = __('Paste a json string from an export and click import to import it to this form');
                    ?>
                    <div class="drag-instructions post-body-plain">
                        <p><?php echo esc_html($starter_copy); ?></p>
                    </div>
                    <textarea name="json" class="widefat form-table af-export" rows="10"></textarea>
                    <hr />
                    <div class="form-actions">
                        <button name="af-import" type="submit" class="button"><?php esc_html_e('Import Fields', 'ajaxy-forms'); ?></button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript">
    function copyExport(e) {
        e.preventDefault();
        var copyText = document.getElementById("af-export-json");
        copyText.select();
        copyText.setSelectionRange(0, 99999); // For mobile devices
        navigator.clipboard.writeText(copyText.value);
        var oldText = e.target.innerText;

        e.target.innerText = "<?php esc_html_e('Copied!', 'ajaxy-forms');?>";
        setTimeout(function() {
            e.target.innerText = oldText;
        }, 2000);
    }
    jQuery(function() {
        jQuery(".af-handle").click(function() {
            jQuery(this).closest('li').toggleClass('af-active');
        });
    });
</script>