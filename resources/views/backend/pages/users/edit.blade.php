@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">Edit Users</span>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                        <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
    @csrf
    @method('PUT')

    <!-- Name -->
    <div class="form-group mb-3">
        <label for="name">Name</label>
        <input type="text" name="name" id="name" class="form-control"
            placeholder="Name" value="{{ old('name', $user->name) }}" aria-describedby="helpName">
        @error('name')
            <span id="helpName" class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Phone -->
    <div class="form-group mb-3">
        <label for="phone">Phone</label>
        <input type="text" name="phone" id="phone" class="form-control"
            placeholder="Phone" value="{{ old('phone', $user->phone) }}" aria-describedby="helpPhone">
        @error('phone')
            <span id="helpPhone" class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Address -->
    <div class="form-group mb-3">
        <label for="address">Address</label>
        <input type="text" name="address" id="address" class="form-control"
            placeholder="Address" value="{{ old('address', $user->address) }}" aria-describedby="helpAddress">
        @error('address')
            <span id="helpAddress" class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Password -->
    <div class="form-group mb-3">
        <label for="password">Password</label>
        <input type="text" name="password" id="password" class="form-control"
            placeholder="Leave blank to keep current password" aria-describedby="helpPassword">
        @error('password')
            <span id="helpPassword" class="text-danger">{{ $message }}</span>
        @enderror
    </div>

    <!-- Roles -->
    <div class="form-group mb-3">
        <label class="d-block">Role</label>

        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="roles[]" value="admin"
                id="roleAdmin" {{ $user->hasRole('admin') ? 'checked' : '' }}>
            <label class="form-check-label" for="roleAdmin">Admin</label>
        </div>
        
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="roles[]" value="accountent"
                id="roleaccountent" {{ $user->hasRole('accountent') ? 'checked' : '' }}>
            <label class="form-check-label" for="roleaccountent">Accountent</label>
        </div>
        
        <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="roles[]" value="staff"
                id="roleStaff" {{ $user->hasRole('staff') ? 'checked' : '' }}>
            <label class="form-check-label" for="roleStaff">Staff</label>
        </div>



        @error('roles')
            <span class="text-danger d-block">{{ $message }}</span>
        @enderror
    </div>

    <div class="text-center">
        <button type="submit" class="btn btn-secondary btn-sm mt-2">Update User</button>
    </div>
</form>

                        </div>
                    </div>
                </div>

           
            </div>
        </div>
    </div>
@endsection

@push('scripts')
  
@endpush
