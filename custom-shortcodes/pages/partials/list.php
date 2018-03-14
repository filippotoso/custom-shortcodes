<table class="widefat">
    <thead>
        <tr>
            <th scope="col" id="name" style="width: 20%"><span>Shortcode</span></th>
            <th scope="col" id="date" style="width: 20%"><span>Modified At</span></th>
            <th scope="col" id="actions" style="width: 60%">Actions</th>
        </tr>
    </thead>

    <?php if (empty($shortcodes[$type])) : ?>

    <tbody>
        <tr class="format-standard">
            <td colspan="3" align="center"><h3 style="margin-bottom: 1.2em;">No shortcodes found!</h3></td>
        </tr>
    </tbody>

    <?php else : ?>

        <tbody>

            <?php foreach ($shortcodes[$type] as $shortcode) : ?>

                <tr class="format-standard">
                    <td class="title" data-colname="Shortcode">
                        <strong class="row-title"><?= $shortcode['name']; ?></strong>
                    </td>
                    <td class="date" data-colname="Modified at"><?= date('Y-m-d H:i:s', $shortcode['modified_at']); ?></td>
                    <td class="actions-button">
                        <?php if ($type == 'active') : ?>
                            <a href="<?= $shortcode['actions']['deactivate'] ?>" class="button button-secondary">Deactivate</a>
                        <?php elseif ($type == 'inactive') : ?>
                            <a href="<?= $shortcode['actions']['activate'] ?>" class="button">Activate</a>
                        <?php endif; ?>
                        <a href="<?= $shortcode['actions']['preview'] ?>" class="button button-green" style="">Preview</a>
                        <a href="<?= $shortcode['actions']['edit'] ?>" class="button button-primary">Edit</a>
                        <a href="#" data-url="<?= $shortcode['actions']['delete'] ?>" class="button button-red deleteBtn">Delete</a>
                        <a href="#" class="copy-shortcode button button-secondary" data-shortcode="<?= $shortcode['name']; ?>">Copy</a>
                    </td>
                </tr>

            <?php endforeach; ?>

        </tbody>
        <tfoot>
            <tr>
                <th scope="col" id="name" class="manage-column column-title column-primary"><span>Shortcode</span></th>
                <th scope="col" id="date" class="manage-column column-date column-primary"><span>Modified at</span></th>
                <th scope="col" id="actions" class="manage-column">Actions</th>
            </tr>
        </tfoot>

    <?php endif; ?>

</table>
