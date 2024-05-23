<?php $action_values = $values[$action_name] ?? []; ?>
<li class="af-form-item af-form-item-<?php echo $action_name; ?> <?php echo !empty($action_values['enabled'] ?? false) ? 'is-enabled': ''; ?>">
    <form method="post" action="<?php echo admin_url('admin-ajax.php?form_id=' . $select_id); ?>">
        <input type="hidden" name="action" value="ajaxy_forms_action" />
        <?php wp_nonce_field('ajaxy_forms_action_' . $action_name); ?>
        <input type="hidden" name="name" value="<?php echo $action_name; ?>" />
        <div class="af-item-bar">
            <div class="af-item-handle ui-sortable-handle">
                <label class="item-title">
                    <span class="af-item-title"><?php echo $action['label'] ?? $action['title'] ?? __('No Label', AJAXY_FORMS_TEXT_DOMAIN); ?></span>
                </label>
                <div class="item-controls">
                    <?php if (isset($action['docs']) && $action['docs']) : ?>
                        <a target="_blank" href="<?php echo $action['docs']; ?>" class="item-docs">
                            <span class="dashicons dashicons-editor-help"></span>
                        </a>
                    <?php endif; ?>
                    <span class="item-type"><input <?php checked($action_values['enabled'] ?? "0", "1"); ?> type="checkbox" name="form_action[enabled]" value="1" <?php checked($action['enabled'] ?? false); ?> class="af-toggle-enabled" /> Enabled</span>
                    <a class="item-edit" href="#"></a>
                </div>
            </div>
        </div>
        <div class="wrap-settings wp-clearfix">
            <div class="settings-inner">
                <?php $properties = $action['properties'] ?? []; ?>
                <?php foreach ($properties as $property) :
                    $name = \Ajaxy\Forms\Admin\Inc\Helper::convert_input_name($property['name'] ?? '');
                    $basename = $property['name'] ?? '';
                    $type = strtolower($property['type'] ?? 'text');
                    if($type == 'separator') {
                        echo '<hr />';
                        continue;
                    }
                    $attributes = \Ajaxy\Forms\Admin\Inc\Helper::convert_array_to_attributes([
                        'name' => $name,
                        'placeholder' => $property['placeholder'] ?? '',
                        'value' => $action_values[$basename] ?? $property['default'] ?? '',
                        'class' => $property['class'] ?? null,
                        'id' => $property['id'] ?? '',
                        'multiple' => $property['multiple'] ?? '',
                        'type' => $type != 'select' ? $type : null,
                    ]);

                ?>
                    <div class="af-form-field af-form-field-<?php echo $type; ?>">
                        <div class="af-form-field-inner">
                            <?php if (isset($property['label'])) : ?>
                                <label class="af-title"><?php echo $property['label'] ?? __('No Label', AJAXY_FORMS_TEXT_DOMAIN); ?></label>
                            <?php endif; ?>
                            <?php switch ($type):
                                case "textarea": ?>
                                    <textarea <?php echo $attributes; ?> class="large-text" rows="5"><?php echo $action_values[$basename] ?? $property['default'] ?? ''; ?></textarea>
                                <?php break;
                                case "checkbox": ?>
                                    <input <?php echo $attributes; ?> class="regular-text ltr" />
                                <?php break;
                                case "radio": ?>
                                    <input <?php echo $attributes; ?> class="regular-text ltr" />
                                <?php break;
                                case "select": ?>
                                    <select <?php echo $attributes; ?>>
                                        <?php
                                        $options = $property['options'] ?? [];
                                        $default = $property['default'] ?? '';
                                        $multiple = $property['multiple'] ?? false;
                                        foreach ($options as $value => $label) :
                                            $selected = $multiple ? in_array($value, (array)($action_values[$property['name']] ?? $property['default'] ?? [])) : $value == ($action_values[$property['name']] ?? $property['default'] ?? '');
                                        ?>
                                            <option <?php selected($selected, true); ?> value="<?php echo $value; ?>"><?php echo $label; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                <?php break;
                                default: ?>
                                    <input <?php echo $attributes; ?> class="regular-text ltr" />
                                    <?php break; ?>
                            <?php endswitch; ?>
                        </div>
                        <?php if (isset($property['help'])) : ?>
                            <p class="description"><?php echo $property['help']; ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
                <hr />
                <div class="af-item-actions description-wide submitbox">
                    <button type="submit" name="save_actions" id="save_actions" class="button button-primary button-large form-actions-save">
                        <?php echo __('Save Action', AJAXY_FORMS_TEXT_DOMAIN); ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</li>