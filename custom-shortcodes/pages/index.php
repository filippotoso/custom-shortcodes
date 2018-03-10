<div class="wrap">

    <h1 class="wp-heading-inline">Custom Shortcodes</h1>

     <a href="<?= admin_url('admin.php?page=' . $page . '&' . $action . '=create'); ?>" class="page-title-action">New Shortcode</a>
    <hr class="wp-header-end">

    <?php include(__DIR__ . '/partials/status.php'); ?>

    <div style="margin-top: 20px; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04); box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff;">

        <h2 style="margin: 1em 0 1em 1em;">Active Shortcodes</h2>
        <?php $type = 'active'; ?>
        <?php include(__DIR__ . '/partials/list.php'); ?>

    </div>

    <div style="margin-top: 20px; border: 1px solid #e5e5e5; -webkit-box-shadow: 0 1px 1px rgba(0,0,0,.04); box-shadow: 0 1px 1px rgba(0,0,0,.04); background: #fff;">

        <h2 style="margin: 1em 0 1em 1em;">Inactive Shortcodes</h2>
        <?php $type = 'inactive'; ?>
        <?php include(__DIR__ . '/partials/list.php'); ?>

    </div>

</div>

<div id="dialog-confirm" title="Are you really sure?" data-url="">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The shortcode will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>

<script>

jQuery(function($) {

    $('.deleteBtn').on('click', function() {
        $('#dialog-confirm').data('url', $(this).data('url'));
        $('#dialog-confirm').dialog('open');
    });

    $('#dialog-confirm').dialog({
        autoOpen: false,
        resizable: false,
        height: 'auto',
        width: 400,
        modal: true,
        buttons: {
            'Delete the shortcode': function() {
                window.location.href = $(this).data('url');
                $(this).dialog('close');
            },
            Cancel: function() {
                $(this).dialog('close');
            }
        }
    });

});


</script>
