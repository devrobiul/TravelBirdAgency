@extends('backend.layout.app')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Website Logo & Favicon Settings</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.setting.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Website Name -->
                    <div class="row mb-4">
                        <label for="app_name" class="col-sm-2 col-form-label">Website Name</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                                   id="app_name" name="app_name" placeholder="Website Name" value="{{ old('app_name', setting('app_name')) }}">
                            @error('app_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Website Logo -->
                    <div class="row mb-4">
                        <label for="primary_logo" class="col-sm-2 col-form-label">Website Logo</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control @error('primary_logo') is-invalid @enderror"
                                   id="primary_logo" name="primary_logo">
                            @error('primary_logo')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="logoPreview" src="{{ asset(setting('primary_logo','admin/upload/files/default-logo.png')) }}"
                                     alt="Logo Preview" style="max-width:150px; border-radius:8px;">
                            </div>
                        </div>
                    </div>
                   

                    <!-- Authorized Signature -->
                    <div class="row mb-4">
                        <label for="authorized" class="col-sm-2 col-form-label">Authorized Signature</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control @error('authorized') is-invalid @enderror"
                                   id="authorized" name="authorized">
                            @error('authorized')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="authorizedPreview" src="{{ asset(setting('authorized','admin/upload/files/default-signature.png')) }}"
                                     alt="Authorized Preview" style="max-width:150px; border-radius:8px;">
                            </div>
                        </div>
                    </div>

                    <!-- Watermark -->
                    <div class="row mb-4">
                        <label for="watermark" class="col-sm-2 col-form-label">Watermark</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control @error('watermark') is-invalid @enderror"
                                   id="watermark" name="watermark">
                            @error('watermark')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="watermarkPreview" src="{{ asset(setting('watermark','admin/upload/files/default-watermark.png')) }}"
                                     alt="Watermark Preview" style="max-width:150px; border-radius:8px;">
                            </div>
                        </div>
                    </div>

                    <!-- Favicon -->
                    <div class="row mb-4">
                        <label for="favicon" class="col-sm-2 col-form-label">Website Favicon</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control @error('favicon') is-invalid @enderror"
                                   id="favicon" name="favicon">
                            @error('favicon')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="faviconPreview" src="{{ asset(setting('favicon','admin/upload/files/default-favicon.png')) }}"
                                     alt="Favicon Preview" style="max-width:150px; border-radius:8px;">
                            </div>
                        </div>
                    </div>
                    <div class="row mb-4">
                        <label for="login_bg" class="col-sm-2 col-form-label">Website Login Bg</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control @error('login_bg') is-invalid @enderror"
                                   id="login_bg" name="login_bg">
                            @error('login_bg')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="mt-2">
                                <img id="login_bgPrivew" src="{{ asset(setting('login_bg','admin/upload/files/default-favicon.png')) }}"
                                     alt="Login Background Preview" style="width:20%; border-radius:8px;">
                            </div>
                        </div>
                    </div>

                    <!-- Submit -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-secondary btn-sm">
                            <i class="fas fa-sync    "></i> Save Changes
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function(){
    function readPreview(input, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $(previewId).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $('#primary_logo').change(function(){ readPreview(this, '#logoPreview'); });
    $('#authorized').change(function(){ readPreview(this, '#authorizedPreview'); });
    $('#watermark').change(function(){ readPreview(this, '#watermarkPreview'); });
    $('#favicon').change(function(){ readPreview(this, '#faviconPreview'); });
    $('#login_bg').change(function(){ readPreview(this, '#login_bgPrivew'); });

});
</script>
@endpush
