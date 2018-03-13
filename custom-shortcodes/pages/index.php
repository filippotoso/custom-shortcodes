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

    <div style="float: right; margin-top: 1.4em;">
        <form method="post" action="<?= admin_url('admin.php'); ?>">
            <input type="hidden" name="action" value="ftcs_manage_shortcode" />
            <input type="hidden" name="ftcs-action" value="download" />
            <input type="submit" value="Backup Shortcodes" class="button button-primary" />
        </form>
    </div>

</div>

<div id="dialog-confirm" title="Are you really sure?" data-url="">
  <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The shortcode will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>

<script>

jQuery(function($) {

    $('.copy-shortcode').on('click', function() {
        copyTextToClipboard($(this).data('shortcode'));
    });

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

function copyTextToClipboard(text) {
    var textArea = document.createElement("textarea");

    textArea.style.position = 'fixed';
    textArea.style.top = 0;
    textArea.style.left = 0;

    textArea.style.width = '2em';
    textArea.style.height = '2em';

    textArea.style.padding = 0;

    textArea.style.border = 'none';
    textArea.style.outline = 'none';
    textArea.style.boxShadow = 'none';

    textArea.style.background = 'transparent';

    textArea.value = text;
    document.body.appendChild(textArea);

    textArea.select();

    try {
        var successful = document.execCommand('copy');
    } catch (err) {
    }
    document.body.removeChild(textArea);
}
</script>
