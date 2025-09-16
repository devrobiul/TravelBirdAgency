$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});


$(document).on('click', '.show-modal', function () {
    let url = $(this).data('url');
    $.ajax({
        url: url,
        dataType: 'html',
        success: function (response) {
            $('.view-modal').html(response).modal('show');
            $('.close').modal('hide');
        }
    });
});

$(document).on('submit', '.submit', function (e) {
    e.preventDefault();

    let url = $(this).attr('action');
    let data = $(this).fieldSerialize();
    let form = $(this);
    $('.submit-button').attr('disabled', true);
    $('.save-button').addClass('d-none');
    $('.loading-button').removeClass('d-none');

    let options = {
        url: url,
        data: data,
        dataType: 'json',
        success: function (response) {
            datatable.ajax.reload();

            get_alert(response);

        }
    };

    $(this).ajaxSubmit(options);

    // !!! Important !!!
    // always return false to prevent standard browser submit and page navigation
    $(this).resetForm();
    return false;
});

// pre-submit callback
function showRequest(formData, jqForm, options) {
    // formData is an array; here we use $.param to convert it to a string to display it
    // but the form plugin does this for you automatically when it submits the data
    //var queryString = $.param(formData);
    var queryString = $.param(formData);

    // jqForm is a jQuery object encapsulating the form element.  To access the
    // DOM element for the form do this:
    // var formElement = jqForm[0];


    // here we could return false to prevent the form from being submitted;
    // returning anything other than false will allow the form submit to continue
    return true;
}

$(document).on('change', '.status-change', function () {
    let name = $(this).data('name');
    let value = $(this).val();
    let url = $(this).data('url');
    let data = {};
    data[name] = value

    $.ajax({
        url: url,
        method: 'GET',
        data: data,
        dataType: 'JSON',
        success: function (response) {
            datatable.ajax.reload();
            get_alert(response);
        },
        error: function (error) {
            console.log(error)
        }
    })
});


// $(document).on('change','.is_paid',function(){
//     let is_default = $(this).val();
//     let url = $(this).data('url');
//     $.ajax({
//         url: url,
//         method: 'GET',
//         data: {is_paid: is_paid},
//         dataType: 'json',
//         success:function(response){
//             get_alert(response);
//         },
//         error: function(error){
//             console.log(error)
//         }
//     })
// });
var Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 5000
});

function get_alert(response) {
    if (response.success == true) {
        Toast.fire({
            icon: 'success',
            title: response.message
        })
        $('.view-modal').modal('hide');
        if (typeof datatable !== 'undefined') {
            datatable.ajax.reload();
        }
    } else {
        Toast.fire({
            icon: 'error',
            title: response.msg
        })

    }

    $('.submit-button').removeAttr('disabled');
    $('.save-button').removeClass('d-none');
    $('.loading-button').addClass('d-none');
}

$(document).on('change', '.submitable', function () {
    datatable.ajax.reload();
});
$(document).on('blur', '.submitable_input', function () {
    datatable.ajax.reload();
});

// ALl Delete checkbox

$(document).on('click', 'input[name="main_checkbox"]', function () {
    if (this.checked) {
        $('input[name="checkbox"]').each(function () {
            this.checked = true;
        });
    } else {
        $('input[name="checkbox"]').each(function () {
            this.checked = false;
        });
    }
    toggledeleteAllbtn();
});

$(document).on('change', 'input[name="checkbox"]', function () {
    if ($('input[name="checkbox"]').length == $('input[name="checkbox"]:checked').length) {
        $('input[name="main_checkbox"]').prop('checked', true);
    } else {
        $('input[name="main_checkbox"]').prop('checked', false);
    }
    toggledeleteAllbtn();
});

function toggledeleteAllbtn() {
    if ($('input[name="checkbox"]:checked').length > 0) {
        $('button.deleteAllbtn').text('Mark Delete (' + $('input[name="checkbox"]:checked').length + ')').removeClass('d-none');
    } else {
        $('button.deleteAllbtn').addClass('d-none');
    }
}
$(".customersubmit").on("submit", function (e) {
    e.preventDefault();
    var form = this;

    $.ajax({
        method: $(form).attr("method"),
        url: $(form).attr("action"),
        data: new FormData(form),
        dataType: "json",
        contentType: false,
        processData: false,
        beforeSend: function () {
            $(form).find("span.error-text").text("");
        },
        success: function (response) {
            if (response.success) {
                get_alert(response);
                 $(form)[0].reset();
                 if(response.route){
                     window.location.href = response.route;
                 }else{
                     window.location.reload();
                 }
             
            }
        },
        error: function (xhr) {
            if (xhr.status === 422) {
                let errors = xhr.responseJSON.errors;
                $.each(errors, function (prefix, val) {
                    $(form)
                        .find("span." + prefix + "_error")
                        .text(val[0])
                    get_alert(response);
                });
            }
        },
    });
});



// Image preview functions
function previewPassport(event) {
    const passportPreview = document.getElementById('passportPreview');
    passportPreview.style.display = 'block';
    passportPreview.src = URL.createObjectURL(event.target.files[0]);
}

function previewImage(event) {
    const imagePreview = document.getElementById('imagePreview');
    imagePreview.style.display = 'block';
    imagePreview.src = URL.createObjectURL(event.target.files[0]);
}


$(document).ready(function () {
    const $noteToggle = $("#noteToggle");
    const $noteDropdown = $("#noteDropdown");
    const $noteMenu = $("#noteMenu");

    // Click to toggle
    $noteToggle.on("click", function (e) {
        e.preventDefault();
        $noteDropdown.toggle(); // toggles display: none/block
    });

    // Close dropdown when clicking outside
    $(document).on("click", function (e) {
        if (!$noteMenu.is(e.target) && $noteMenu.has(e.target).length === 0) {
            $noteDropdown.hide();
        }
    });
});
