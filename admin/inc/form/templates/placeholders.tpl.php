<?php

/**
 * Lists the placeholders the actions of this form can use.
 *
 * Expects $form, with its metadata already decoded.
 */

$placeholder_fields = $form['metadata']['fields'] ?? [];
$placeholder_skip = ['submit', 'button', 'html', 'recaptcha', 'turnstile', 'repeater_end', 'repeater_start'];
$placeholder_rows = [];

foreach ($placeholder_fields as $placeholder_field) {
    $placeholder_name = $placeholder_field['name'] ?? '';
    if ('' === $placeholder_name || \in_array($placeholder_field['type'] ?? '', $placeholder_skip, true)) {
        continue;
    }

    $placeholder_rows[] = [
        'name' => $placeholder_name,
        'label' => !empty($placeholder_field['label']) ? $placeholder_field['label'] : $placeholder_name,
        // A choice stores one thing and reads as another, the rest are the same.
        'has_label' => \in_array($placeholder_field['type'] ?? '', ['choice', 'radio_group', 'checkbox_group', 'country', 'posts', 'terms', 'term_posts', 'rating'], true),
    ];
}
?>
<div class="af-placeholders">
    <h3><?php esc_html_e('Placeholders', 'ajaxy-forms'); ?></h3>
    <?php if (empty($placeholder_rows)) : ?>
        <p class="af-placeholders-hint"><?php esc_html_e('No fields yet. Add some on the Edit Form tab.', 'ajaxy-forms'); ?></p>
    <?php else : ?>
        <p class="af-placeholders-hint"><?php esc_html_e('Click to copy', 'ajaxy-forms'); ?></p>
        <ul class="af-placeholders-list">
            <?php foreach ($placeholder_rows as $placeholder_row) : ?>
                <li>
                    <span class="af-placeholder-label"><?php echo esc_html($placeholder_row['label']); ?></span>
                    <code class="af-copy" tabindex="0" title="<?php esc_attr_e('Copy', 'ajaxy-forms'); ?>"><?php echo esc_html(sprintf("{{data['%s'].value}}", $placeholder_row['name'])); ?></code>
                    <?php if ($placeholder_row['has_label']) : ?>
                        <code class="af-copy af-copy-alt" tabindex="0" title="<?php esc_attr_e('Copy the label it reads as', 'ajaxy-forms'); ?>"><?php echo esc_html(sprintf("{{data['%s'].value_label}}", $placeholder_row['name'])); ?></code>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
            <li>
                <span class="af-placeholder-label"><?php esc_html_e('Everything, as a table', 'ajaxy-forms'); ?></span>
                <code class="af-copy" tabindex="0" title="<?php esc_attr_e('Copy', 'ajaxy-forms'); ?>">{{table}}</code>
            </li>
        </ul>
        <p class="af-placeholders-hint"><?php esc_html_e('.label and .type work too, as does Twig.', 'ajaxy-forms'); ?></p>
    <?php endif; ?>
</div>
