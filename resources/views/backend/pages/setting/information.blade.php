@extends('backend.layout.app')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Website Information Settings</h3>
                </div>
                <div class="card-body">
                    <form method="post" action="{{ route('admin.setting.store') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        value="{{ setting('address') }}" placeholder="Enter your address">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        value="{{ setting('phone') }}" placeholder="Enter phone number">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cab_no">CAB No</label>
                                    <input type="text" name="cab_no" id="cab_no" class="form-control"
                                        value="{{ setting('cab_no') }}" placeholder="Enter CAB No">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="whatsapp">WhatsApp</label>
                                    <input type="text" name="whatsapp" id="whatsapp" class="form-control"
                                        value="{{ setting('whatsapp') }}" placeholder="Enter WhatsApp number">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="support_phone">Support Phone</label>
                                    <input type="text" name="support_phone" id="support_phone" class="form-control"
                                        value="{{ setting('support_phone') }}" placeholder="Enter support phone">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email</label>
                                    <input type="email" name="email" id="email" class="form-control"
                                        value="{{ setting('email') }}" placeholder="Enter email address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="booking_email">Hotel Booking Email</label>
                                    <input type="booking_email" name="booking_email" id="booking_email" class="form-control"
                                        value="{{ setting('booking_email') }}" placeholder="Enter booking mail address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="booking_email">Hotel Booking Email 2</label>
                                    <input type="booking_email" name="booking_email2" id="booking_email2" class="form-control"
                                        value="{{ setting('booking_email2') }}" placeholder="Enter booking mail2 address">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="booking_email">PDF Address</label>
                                    <textarea name="pdf_address" class="form-control" id="">{{ setting('pdf_address') }}</textarea>
                                </div>
                            </div>


                        </div>
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
    <script src="{{ asset('backend/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea',
            height: 300,
            plugins: [
                'advlist', 'autolink', 'link', 'image', 'lists', 'charmap', 'prewiew', 'anchor', 'pagebreak',
                'searchreplace', 'wordcount', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media',
                'table', 'emoticons', 'template', 'codesample'
            ],
            toolbar: 'undo redo | styles | bold italic underline | alignleft aligncenter alignright alignjustify |' +
                'bullist numlist outdent indent | link image | print preview media fullscreen | ' +
                'forecolor backcolor emoticons',
            menu: {
                favs: {
                    title: 'Menu',
                    items: 'code visualaid | searchreplace | emoticons'
                }
            },
            menubar: 'favs file edit view insert format tools table',
            content_style: 'body{font-family:Helvetica,Arial,sans-serif; font-size:16px}'
        });
    </script>
@endpush
