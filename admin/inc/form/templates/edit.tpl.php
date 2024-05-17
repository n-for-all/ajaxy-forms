<?php $add_new_screen = $action == 'edit' ? false : true; ?>
<div class="wp-clearfix af-frame">
    <div class="settings-column">
        <div class="clear"></div>
        <h2><?php _e('Add Fields'); ?></h2>
        <?php do_accordion_sections('form-fields', 'side', null); ?>
    </div>
    <div class="af-management-liquid">
        <div class="af-management">
            <h2><?php _e('Form Structure'); ?></h2>
            <form method="post" class="af-edit">
                <?php wp_nonce_field($nonce); ?>
                <?php
                ?>
                <div class="af-header">
                    <div class="major-publishing-actions wp-clearfix">
                        <label for="form-name"><?php _e('Form Name'); ?></label>
                        <input name="name" id="form-name" type="text" class="regular-text form-required" required="required" value="<?php echo $form ? \esc_attr($form['name']) : ''; ?>" />
                    </div>
                </div>
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

                        <div class="af-settings">
                            <h3><?php _e('Form Settings'); ?></h3>
                            <fieldset class="af-settings-group">
                                <legend class="af-settings-group-name howto"><?php _e('Extra fields'); ?></legend>
                                <div class="af-settings-input checkbox-input">
                                    <input name="options[allow_extra_fields]" type="checkbox" value="1" <?php \checked($form && isset($form['metadata']['options']['allow_extra_fields'])); ?> /> <label for="auto-add-pages"><?php _e('Allow extra fields'); ?></label>
                                </div>
                            </fieldset>
                            <fieldset class="af-settings-group">
                                <legend class="af-settings-group-name howto"><?php _e('No Validate'); ?></legend>
                                <div class="af-settings-input checkbox-input">
                                    <input name="options[attr][novalidate]" type="checkbox" value="1" <?php \checked($form && isset($form['metadata']['options']['attr']['novalidate'])); ?> /> <label for="auto-add-pages"><?php _e('Disable client side validation'); ?></label>
                                </div>
                            </fieldset>
                            <fieldset class="af-settings-group">
                                <legend class="af-settings-group-name howto"><?php _e('Class Name'); ?></legend>
                                <div class="af-settings-input text-input">
                                    <input name="options[attr][class]" type="text" value="<?php echo isset($form['metadata']['options']['attr']['class']) ? $form['metadata']['options']['attr']['class'] : ''; ?>" />
                                </div>
                            </fieldset>
                            <fieldset class="af-settings-group">
                                <legend class="af-settings-group-name howto"><?php _e('Method'); ?></legend>
                                <div class="af-settings-input text-input">
                                    <select name="options[method]">
                                        <option value="post" <?php \selected($form && isset($form['metadata']['options']['method']) && $form['metadata']['options']['method'] == 'post'); ?>>POST</option>
                                        <option value="get" <?php \selected($form && isset($form['metadata']['options']['method']) && $form['metadata']['options']['method'] == 'get'); ?>>GET</option>
                                        <option value="put" <?php \selected($form && isset($form['metadata']['options']['method']) && $form['metadata']['options']['method'] == 'put'); ?>>PUT</option>
                                        <option value="delete" <?php \selected($form && isset($form['metadata']['options']['method']) && $form['metadata']['options']['method'] == 'delete'); ?>>DELETE</option>
                                        <option value="patch" <?php \selected($form && isset($form['metadata']['options']['method']) && $form['metadata']['options']['method'] == 'patch'); ?>>PATCH</option>
                                    </select>
                                </div>
                            </fieldset>
                            <fieldset class="af-settings-group">
                                <legend class="af-settings-group-name howto"><?php _e('Submission'); ?></legend>
                                <div class="af-settings-input text-input">
                                    <select name="options[submission]">
                                        <option value="1" <?php \selected($form && isset($form['metadata']['options']['submission']) && $form['metadata']['options']['submission'] == '1'); ?>>Ajax</option>
                                        <option value="0" <?php \selected($form && isset($form['metadata']['options']['submission']) && $form['metadata']['options']['submission'] == '0'); ?>>Normal</option>
                                    </select>
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </div>
                <div class="af-footer">
                    <div class="major-publishing-actions">
                        <div class="publishing-action">
                            <?php submit_button(empty($select_id) ? __('Create Form') : __('Save Form'), 'primary large form-save', 'save_form', false); ?>
                        </div>
                        <?php if (!$add_new_screen) : ?>
                            <span class="delete-action">
                                <a class="submitdelete deletion af-delete" href="
									<?php
                                    echo esc_url(
                                        wp_nonce_url(
                                            add_query_arg(
                                                array(
                                                    'action' => 'delete',
                                                    'form' => $select_id,
                                                ),
                                                admin_url('admin.php?page=ajaxy-form')
                                            ),
                                            'delete-form-' . $select_id
                                        )
                                    );
                                    ?>
									"><?php _e('Delete Form'); ?></a>
                            </span>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>