<?php $add_new_screen = $action == 'edit' ? false : true; ?>
<div class="wp-clearfix af-actions-frame">
    <div class="af-management-liquid">
        <div class="af-management">
            <h2><?php _e('Form Actions'); ?></h2>
            <form method="post" class="af-edit">
                <?php wp_nonce_field($nonce); ?>
                <?php
                ?>
                <div id="post-body">
                    <div id="post-body-content" class="wp-clearfix">
                        <?php if ($add_new_screen) : ?>
                            <?php
                            $starter_copy = __('Drag the items into the order you prefer. Click the arrow on the right of the item to reveal additional configuration options.');
                            ?>
                            <div class="drag-instructions post-body-plain">
                                <p><?php echo $starter_copy; ?></p>
                            </div>
                        <?php endif;  ?>
                        <ul class="ui-sortable droppable"></ul>
                    </div>
                </div>
                <div class="af-footer">
                    <div class="major-publishing-actions">
                        <div class="publishing-action">
                            <?php submit_button(__('Save Actions'), 'primary large form-save', 'save_actions', false); ?>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>