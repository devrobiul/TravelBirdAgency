@extends('backend.layout.app')

@section('content')
<div class="col-md-4 m-auto">
    <div class="card">
    <div class="card-header">
        <span style="font-size: 20px">Update Password</span>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.passwordUpdate') }}" method="POST">
            @csrf

            <div class="form-group mb-3">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="form-control" placeholder="Enter new password">
                @error('password')
                    <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>

            <div class="text-center">
                <button type="submit" class="btn btn-secondary btn-sm mt-2">Update Password</button>
            </div>
        </form>
    </div>
</div>
</div>
@endsection

@push('scripts')
@endpush
     