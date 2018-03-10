<div class="wrap">

    <h1 class="wp-heading-inline">Preview Custom Shortcodes</h1>

    <a href="<?= admin_url('admin.php?page=' . $page); ?>" class="page-title-action">Go Back</a>
    <hr class="wp-header-end">

    <div style="margin-top: 20px; padding: 0.5em 2em 2em; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04); box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff;">

        <form method="get" action="<?= admin_url('admin.php'); ?>">
            <h2>Visible Result</h2>
            <textarea name="code" rows="5" style="width: 100%; padding: 0.7em; margin-bottom: 1em;"><?= htmlentities($code, ENT_QUOTES); ?></textarea>
            <input type="submit" value="Preview" class="button button-secondary" />
            <input type="hidden" name="page" value="<?= FTCustomShortcodesAdmin::$page; ?>" />
            <input type="hidden" name="<?= FTCustomShortcodesAdmin::$action; ?>" value="preview" />
            <input type="hidden" name="shortcode" value="<?= $shortcode['name']; ?>" />
        </form>

        <?php $output = do_shortcode($code); ?>
        <h2 style="margin-top: 2em;">Visible Result</h2>
        <div style="border: 1px solid #cccccc; padding: 1em; font-size: 15px;"><?= $output; ?></div>

        <h2 style="margin-top: 2em;">HTML Result</h2>
        <textarea rows="15" style="width: 100%; padding: 0.7em;"><?= htmlentities($output, ENT_QUOTES); ?></textarea>

    </div>

</div>
