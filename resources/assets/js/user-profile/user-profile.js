'use strict';

$(document).ready(function () {
    if (successMessage !== '') {
        iziToast.success({
            title: '<div class="font-weight-normal">Success</div>',
            message: successMessage,
            iconUrl: iconUrl,
            position: 'topRight',
        });
    }
});

$('#countryId,#stateId,#cityId,#language').select2({
    width: '100%',
});

$('#changeLanguageModal').on('shown.bs.modal', function () {
    $(document).off('focusin.modal');
});

$('.password').on('keypress', function (e) {
    if (e.which == 32) {
        return false;
    }
});

$(document).on('submit', '#changePasswordForm', function (event) {
    event.preventDefault();
    let isValidate = validatePassword();
    if (!isValidate) {
        return false;
    }
    let loadingButton = jQuery(this).find('#btnPrPasswordEditSave');
    loadingButton.button('loading');
    $.ajax({
        url: route('change.password'),
        type: 'post',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                $('#changePasswordModal').modal('hide');
                displaySuccessMessage(result.message);
            }
        },
        error: function (result) {
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

$('#changePasswordModal').on('hidden.bs.modal', function () {
    resetModalForm('#changePasswordForm', '#editValidationErrorsBox');
});

function validatePassword () {
    let currentPassword = $('#pfCurrentPassword').val().trim();
    let password = $('#pfNewPassword').val().trim();
    let confirmPassword = $('#pfNewConfirmPassword').val().trim();

    if (currentPassword == '' || password == '' || confirmPassword == '') {
        $('#editPasswordValidationErrorsBox').
            show().
            html('Please fill all the required fields.');
        return false;
    }
    return true;
}

$(document).on('click', '.edit-profile', function (event) {
    let id = $(event.currentTarget).attr('data-id');
    renderProfileData(id);
});

window.renderProfileData = function (id) {
    $.ajax({
        url: route('edit-profile', id),
        type: 'GET',
        success: function (result) {
            if (result.success) {
                $('#pfName').val(result.data.first_name);
                $('#plName').val(result.data.last_name);
                $('#previewImage').attr('src', result.data.profile_image);
                $('#pfEmail').val(result.data.email);
            }
        },
    });
};

$(document).on('change', '#profileImage', function () {
    if (isValidImage($(this), '#validationErrorsBox')) {
        displayImage(this, '#previewImage');
    }
    $('#validationErrorsBox').delay(3000).slideUp(300);
});

$(document).on('submit', '#editProfileForm', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnPrEditSave');
    loadingButton.button('loading');
    $.ajax({
        url: route('update-profile'),
        type: 'post',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (result) {
            if (result.success) {
                $('#editProfileModal').modal('hide');
                displaySuccessMessage(result.message);
                setTimeout(function () {
                    location.reload();
                }, 1500);
            }
        },
        error: function (result) {
            loadingButton.button('reset');
            displayErrorMessage(result.responseJSON.message);
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

$(document).on('submit', '#changeLanguageForm', function (event) {
    event.preventDefault();
    let loadingButton = jQuery(this).find('#btnLanguageChange');
    loadingButton.button('loading');
    $.ajax({
        url: route('update.language'),
        type: 'post',
        data: new FormData($(this)[0]),
        processData: false,
        contentType: false,
        success: function (result) {
            displaySuccessMessage(result.message);
            setTimeout(function () {
                location.reload();
            }, 3000);
        },
        error: function (result) {
            manageAjaxErrors(result, 'editProfileValidationErrorsBox');
            loadingButton.button('reset');
        },
        complete: function () {
            loadingButton.button('reset');
        },
    });
});

$(document).on('click', '.changeType', function () {
    let inputField = $(this).parent().siblings();
    let oldType = inputField.attr('type');
    if (oldType == 'password') {
        $(this).children().addClass('fa-eye');
        $(this).children().removeClass('fa-eye-slash');
        inputField.attr('type', 'text');
    } else {
        $(this).children().removeClass('fa-eye');
        $(this).children().addClass('fa-eye-slash');
        inputField.attr('type', 'password');
    }
});
