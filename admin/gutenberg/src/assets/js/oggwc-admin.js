jQuery(document).ready(function ($) {

    $(document).on('click', '.og-preview_button_generate', function () {
        $('.og-preview_container_text-result').removeClass('show')
        $(this).addClass("loading");

        //  Check for flag modal in localStorage
        if ( !localStorage.modal ) {
            localStorage.modal = true
            $('#openModal').addClass('show')
        }

        $.ajax({
            type: 'POST',
            dataType: 'json',
            url: oggwc_admin.ajax_url,
            data: {
                action: 'oggwc_hand_generate_for_post',
                post_id: $(this).attr('name'),
            },
            success: function (data) {

            },
            /*   AJAX Completed   */
            complete: function (result) {
                let resultObj = result.responseJSON,
                    textResultEl = $('.og-preview_container_text-result');
                $('.og-preview_button').removeClass("loading");

                if (resultObj.post_data) {
                    textResultEl.children('span').text('Successfully! Image will update in 1 minutes')
                } else {
                    resultObj.data ? textResultEl.children('span').text(resultObj.data.text) : textResultEl.children('span').text('Error, please try again later.')
                    textResultEl.children('span').addClass('error')
                }

                textResultEl.addClass('show')
            },

            /* AJAX Error */
            error: function (error) {
                console.log(error)
            }
        });
    });

})