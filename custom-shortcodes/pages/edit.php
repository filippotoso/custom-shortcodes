<div class="wrap">

    <h1>Edit Custom Shortcodes</h1>

    <div style="margin-top: 20px; padding: 0em 2em 2em; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04); box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff;">

        <form method="POST" action="<?= admin_url('admin.php'); ?>">

            <input type="hidden" name="action" value="ftcs_manage_shortcode" />
            <input type="hidden" name="ftcs-action" value="update" />

            <input type="hidden" name="original-name" value="<?= $shortcode['name']; ?>" />

            <?php include(__DIR__ . '/partials/form.php'); ?>

            <input type="submit" value="Update Shortcode" class="button button-primary" />

        </form>

    </div>

</div>
