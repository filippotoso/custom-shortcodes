    <table class="form-table">

        <tr>
            <th scope="row"><label for="name">Shortcode name</label></th>
            <td>
                <input name="name" type="text" value="<?= htmlentities($shortcode['name'], ENT_QUOTES); ?>" class="regular-text" />
                <p class="description">The name of the shortcode without square brackets (it must be a valid filename).</p>
            </td>
        </tr>

        <tr>
            <th scope="row"><label for="type">Shortcode status</label></th>
            <td>
                <p><input name="type" id="type-active" type="radio" value="active" <?= ($shortcode['type'] == 'active') ? 'checked' : ''; ?> /> <label for="type-active">Active</label> </p>
                <p><input name="type"  id="type-inactive" type="radio" value="inactive" <?= ($shortcode['type'] == 'inactive') ? 'checked' : ''; ?> /> <label for="type-inactive">Inactive</label> </p>
            </td>
        </tr>


        <tr>
            <th scope="row"><label for="content">Shortcode content</label></th>
            <td>
                <textarea name="content" class="regular-text" rows="12" style="width: 100%;"><?= htmlentities($shortcode['content'], ENT_QUOTES); ?></textarea>
                <p class="description">You can access the attributes and the content of the shortcode using the $atts and $content variables.</p>
            </td>
        </tr>

    </table>
