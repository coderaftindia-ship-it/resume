'use strict';

$(document).ready(function () {
    $(document).on('submit', '#createUserForm', function (e) {
        e.preventDefault();
        processingBtn('#createUserForm', '#btnSave', 'loading');
        $('#createUserForm')[0].submit();
    });

    $(document).on('submit', '#editUserForm', function (e) {
        e.preventDefault();
        $('#editUsername').val(editUsername);
        processingBtn('#editUserForm', '#btnSave', 'loading');
        $('#editUserForm')[0].submit();
    });

    $(document).on('blur', '#username', function () {
        let data = $(this).val();
        if (data !== '') {
            $.ajax({
                url: route('check.username'),
                type: 'GET',
                data: {
                    'data': data,
                },
                success: function (result) {
                    if (result.success) {
                        $('.username-error').addClass('d-none');
                        return true;
                    }
                },
                error: function (result) {
                    $('.username-error').removeClass('d-none');
                    $('.username-error').text(result.responseJSON.message);
                    $('#username').val('');
                },
            });
        }
    });
});
