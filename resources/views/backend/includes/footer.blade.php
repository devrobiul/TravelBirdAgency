<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.slimscroll.js') }}"></script>
<script src="https://malsup.github.io/jquery.form.js"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.13.2/jquery-ui.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="{{ asset('backend/assets/js/boxicons.js') }}"></script>
<script src="{{ asset('backend/assets/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/select2.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/izitoast/1.4.0/js/iziToast.min.js"></script>
<script src="{{ asset('backend/assets/js/ajax.js') }}"></script>
<script src="{{ asset('backend/assets/js/main.js') }}"></script>

@stack('scripts')

<script>

    @foreach (['success', 'error', 'info', 'warning'] as $msg)
        @if (session($msg))
            iziToast.{{ $msg }}({
                title: '{{ ucfirst($msg) }}',
                message: '{{ session($msg) }}',
                position: 'topRight',
                timeout: 5000,
                progressBar: true,
            });
        @endif
    @endforeach
</script>


<script>
    function confirmDelete(event) {
        event.preventDefault();

        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                event.target.submit();
            }
        });
    }

    $('.select2').select2();

const timePickerConfig = {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true
};

document.querySelectorAll('.timepicker').forEach(input => {
    flatpickr(input, timePickerConfig);
});
</script>




<script>
    $(document).ready(function() {
        $('.dropdown-submenu a.dropdown-toggle').on('click', function(e) {
            if (!$(this).next().hasClass('show')) {
                $(this).parents('.dropdown-menu').first().find('.show').removeClass('show');
            }
            var $subMenu = $(this).next('.dropdown-menu');
            $subMenu.toggleClass('show');
            $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
                $('.dropdown-submenu .show').removeClass('show');
            });
            return false;
        });
    });
</script>



<script>
    $(document).ready(function() {
        $('.formSubmit').on('submit', function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            $('#loadingPopup').show();
            $('.error-message').text('');

            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loadingPopup').hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: response.message,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = response.route;
                    });
                },
                error: function(xhr) {
                    $('#loadingPopup').hide();
                    let errors = xhr.responseJSON.errors || {};

                    if (xhr.responseJSON.error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON.error
                        });
                    } else {
                        $.each(errors, function(key, value) {
                            $(`#error-${key}`).text(value[0]);
                        });
                    }
                }
            });
        });
    });

    $(document).ready(function() {

    // // Disable right-click
    // $(document).on("contextmenu", function(e) {
    //     e.preventDefault();
    // });

    // // Disable specific key combinations
    // $(document).on("keydown", function(e) {
    //     // F12
    //     if (e.keyCode === 123) return false;

    //     // Ctrl+Shift+I, Ctrl+Shift+J, Ctrl+Shift+C
    //     if (e.ctrlKey && e.shiftKey && (e.keyCode === 73 || e.keyCode === 74 || e.keyCode === 67)) return false;

    //     // Ctrl+U
    //     if (e.ctrlKey && e.keyCode === 85) return false;
    // });

    // // Disable text selection
    // $("body").css({
    //     "-webkit-user-select": "none",
    //     "-moz-user-select": "none",
    //     "-ms-user-select": "none",
    //     "user-select": "none"
    // });

    // // Disable dragging images
    // $("img").on("dragstart", function(e) {
    //     e.preventDefault();
    // });

});
</script>
