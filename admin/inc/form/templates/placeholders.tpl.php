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
        'type' => $placeholder_field['type'] ?? '',
        'label' => !empty($placeholder_field['label']) ? $placeholder_field['label'] : $placeholder_name,
        // A choice stores one thing and shows another, the rest are the same.
        'has_label' => \in_array($placeholder_field['type'] ?? '', ['choice', 'radio_group', 'checkbox_group', 'country', 'posts', 'terms', 'term_posts', 'rating'], true),
    ];
}
?>
<div class="af-placeholders">
    <h3>
        <?php esc_html_e('Placeholders for this form', 'ajaxy-forms'); ?>
        <button type="button" class="button-link af-placeholders-toggle" aria-expanded="false"><?php esc_html_e('show', 'ajaxy-forms'); ?></button>
    </h3>
    <div class="af-placeholders-body" hidden>
        <?php if (empty($placeholder_rows)) : ?>
            <p class="description"><?php esc_html_e('This form has no fields yet. Add some on the Edit Form tab and they will be listed here.', 'ajaxy-forms'); ?></p>
        <?php else : ?>
            <p class="description"><?php esc_html_e('Click one to copy it, then paste it into the To, Subject or Message of any action below.', 'ajaxy-forms'); ?></p>
            <table class="widefat striped af-placeholders-table">
                <thead>
                    <tr>
                        <th><?php esc_html_e('Field', 'ajaxy-forms'); ?></th>
                        <th><?php esc_html_e('What was submitted', 'ajaxy-forms'); ?></th>
                        <th><?php esc_html_e('How it reads', 'ajaxy-forms'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($placeholder_rows as $placeholder_row) : ?>
                        <tr>
                            <td>
                                <strong><?php echo esc_html($placeholder_row['label']); ?></strong>
                                <span class="af-placeholder-type"><?php echo esc_html($placeholder_row['type']); ?></span>
                            </td>
                            <td><code class="af-copy" tabindex="0"><?php echo esc_html(sprintf("{{data['%s'].value}}", $placeholder_row['name'])); ?></code></td>
                            <td>
                                <?php if ($placeholder_row['has_label']) : ?>
                                    <code class="af-copy" tabindex="0"><?php echo esc_html(sprintf("{{data['%s'].value_label}}", $placeholder_row['name'])); ?></code>
                                <?php else : ?>
                                    <span class="af-placeholder-same"><?php esc_html_e('same as submitted', 'ajaxy-forms'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td><strong><?php esc_html_e('Everything at once', 'ajaxy-forms'); ?></strong></td>
                        <td colspan="2"><code class="af-copy" tabindex="0">{{table}}</code> <span class="af-placeholder-same"><?php esc_html_e('a table of every submitted field, for the Message', 'ajaxy-forms'); ?></span></td>
                    </tr>
                </tbody>
            </table>
            <p class="description">
                <?php esc_html_e('The field label and type are available too, as .label and .type, and Twig tags such as {% if %} work in any of these boxes.', 'ajaxy-forms'); ?>
            </p>
        <?php endif; ?>
    </div>
</div>
