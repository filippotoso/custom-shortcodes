<script type="text/javascript" >

jQuery(document).ready(function($) {

    $(':input[name=name]').change(function() {

        var data = {
            'action': 'ftcs_validate_name',
            '<?= FTCustomShortcodesAdmin::$action?>': '<?= $type; ?>',
            'original-name': $(':input[name=original-name]').val(),
            'name': $(':input[name=name]').val()
        };

        jQuery.post(ajaxurl, data, function(response) {
            $('#nameError').html(response.message)
            if (response.valid) {
                $('#nameError').addClass('hide');
                $('#submitButton').removeAttr('disabled');
            } else {
                $('#nameError').removeClass('hide');
                $('#submitButton').attr('disabled', 'disabled');
            }
        }, 'json');

    });

});

</script>
