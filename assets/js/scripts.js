jQuery(function ($) {
    jQuery('document').ready(function ($) {
        $('.embed-responsive').children(':first').addClass('embed-responsive-item');

        // Simple AJAX listeners
        $(document).bind("ajaxSend", function () {
            $('.btn-primary').attr('disabled', 'disabled');
        }).bind("ajaxComplete", function () {
            $('.btn-primary').removeAttr('disabled');
        });

        // Email Validation
        if (ResourcePage.mailgun !== undefined && ResourcePage.mailgun !== '') {
            $('#email').mailgun_validator({
                api_key: ResourcePage.mailgun,
                in_progress: function () {
                    $('#email').parent().removeClass('has-warning has-error');
                    $(".mailcheck-suggestion").remove();
                    $("[type=submit]").addClass("disabled").attr("disabled", "disabled");
                },
                success: function (data) {
                    $('#email').after(get_suggestion_str(data['is_valid'], data['did_you_mean']));
                },
                error: function () {
                    $("[type=submit]").removeClass("disabled").removeAttr("disabled");
                }
            });
        }
        // Parse Mailgun Responses
        function get_suggestion_str(is_valid, alternate) {
            if (is_valid) {
                if (alternate) {
                    $('#email').parent().addClass('has-warning');
                    return '<div class="mailcheck-suggestion help-block">Did you mean <a href="#">' + alternate + '</a>?</div>';
                }
                if ($('#form-clicked').length) {
                    $('form').unbind().submit();
                    $("[type=submit]").addClass("disabled").attr("disabled", "disabled");
                } else {
                    $("[type=submit]").removeClass("disabled").removeAttr("disabled");
                }

                return;
            }
            $('#email').parent().addClass('has-error');
            if (alternate) {
                return '<div class="mailcheck-suggestion help-block">This email is invalid. Did you mean <a href="#">' + alternate + '</a>?</div>';
            }
            return '<div class="mailcheck-suggestion help-block">This email is invalid.</div>';
        }

        $(".form-group").on("click", ".mailcheck-suggestion a", function (e) {
            e.preventDefault();
            $("#email").val($(this).text());
            $("[type=submit]").removeClass("disabled").removeAttr("disabled");
            $(".mailcheck-suggestion").remove();
        });
        $('form').submit(function (e) {
            e.preventDefault();
            $(this).after('<input type="hidden" id="form-clicked" value="true">');
            $('#email').trigger('focusout');
        });

        // Submit contact info
        $('#resource-submit').click(function (e) {
            e.preventDefault();

            if (stepVerify() == 0) {
                $.ajax({
                    type: 'POST',
                    url: ResourcePage.ajaxurl,
                    data: $('#resource-form').serialize(),
                    dataType: 'json',
                    async: true,
                    success: function () {
                        $('#resource-access').modal('hide');

                        window.history.pushState({"pageTitle": document.title}, document.title, window.location.href + '?access=true');
                    }
                });
            }
        });
    });

    function stepVerify() {
        $('.help-block').remove();
        $('.form-group').removeClass('has-error');
        var count = 0;
        var inputs = ["first_name", "email"];

        if (inputs !== undefined) {
            jQuery.each(inputs, function (i, id) {
                if ($("#" + id).val() === '') {
                    stepError(id, 'You must enter a value.');
                    count++;
                }
            });
        }

        // Advanced Section Specific Validation
        if (count === 0) {
            var emailregex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

            if (!emailregex.test($('#email').val())) {
                stepError('email', 'Email address is not valid.');
                count++;
            }
        }

        function stepError(id, msg) {
            $("#" + id).parent().addClass('has-error');
            $("#" + id).after('<p class="help-block">' + msg + '</p>');
        }

        return count;
    }
});