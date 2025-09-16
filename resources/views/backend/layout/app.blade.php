<!DOCTYPE html>
<html lang="en">
@include('backend.includes.header')

<body>
    <div class="main-wrapper">
        @include('backend.includes.navbar')
        @include('backend.includes.sidebar')

        <div class="page-wrapper">
            <div class="content container-fluid">
                @yield('content')
            </div>
            <div class="text-center">
                <p>Software developed by <a href="https://raidaitbd.com">Mohammad Robiul Hossain</a> <a
                        href="https://wa.me/+8801882850027"><i class="fab fa-whatsapp"></i> Whatsapp</a>/Call :
                    +8801882850027</p>
            </div>
        </div>
    </div>
    <div class="modal fade view-modal" id="staticBackdrop" data-backdrop="static" data-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
    </div>
    <div id="loadingPopup"
        style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; text-align: center;">
        <div style="position: relative; top: 50%; transform: translateY(-50%);">
            <div class="spinner-border text-light" role="status"></div>
            <p class="text-light mt-2">Processing...</p>
        </div>
    </div>
    @include('backend.includes.footer')
</body>

</html>
