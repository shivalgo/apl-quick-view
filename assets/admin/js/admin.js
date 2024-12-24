jQuery(document).ready(function ($) {
    var file_frame;
    $('.awp-wc-color-picker').wpColorPicker();
    $(document).on('click', '.algo-upload-image-button', function (e) {
        e.preventDefault();
        var button = $(this);
        var field = button.siblings('.algo-image-upload-field');

        // If the media frame already exists, reopen it.
        if (file_frame) {
            file_frame.open();
            return;
        }

        // Create a new media frame
        file_frame = wp.media({
            title: 'Select or Upload Image',
            button: {
                text: 'Use this image'
            },
            multiple: false  // Set to true to allow multiple files to be selected
        });

        // When an image is selected in the media frame...
        file_frame.on('select', function () {
            var attachment = file_frame.state().get('selection').first().toJSON();

            // Update the image preview and hidden input field
            button.siblings('img').attr('src', attachment.url);
            field.val(attachment.id);
            button.siblings('.algo-remove-image-button').show();
        });

        // Finally, open the modal
        file_frame.open();
    });

    // Handle remove image button
    $(document).on('click', '.algo-remove-image-button', function (e) {
        e.preventDefault();

        var button = $(this);
        button.siblings('img').attr('src', '');
        button.siblings('.algo-image-upload-field').val('');
        button.hide();
    });

    $(document).on('change', '#algo_wc_quick_view_button_type', function (e) {
        if (e.target.value == "use_button") {
            $('#algo_wc_quick_view_button_icon').parent().parent().hide();
            $('#algo_wc_quick_view_button_label').parent().parent().show();
        } else if (e.target.value == "use_button_icon") {
            $('#algo_wc_quick_view_button_icon').parent().parent().show();
            $('#algo_wc_quick_view_button_label').parent().parent().show();
        } else if (e.target.value == "use_icon") {
            $('#algo_wc_quick_view_button_icon').parent().parent().show();
            $('#algo_wc_quick_view_button_label').parent().parent().hide();
        }
    });

    //Onload
    let algo_wc_quick_view_button_type = $('#algo_wc_quick_view_button_type').val();
    if (algo_wc_quick_view_button_type == "use_button") {
        $('#algo_wc_quick_view_button_icon').parent().parent().hide();
        $('#algo_wc_quick_view_button_label').parent().parent().show();
    } else if (algo_wc_quick_view_button_type == "use_button_icon") {
        $('#algo_wc_quick_view_button_icon').parent().parent().show();
        $('#algo_wc_quick_view_button_label').parent().parent().show();
    } else if (algo_wc_quick_view_button_type == "use_icon") {
        $('#algo_wc_quick_view_button_icon').parent().parent().show();
        $('#algo_wc_quick_view_button_label').parent().parent().hide();
    }
});
