@extends('backend.layout.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <span style="font-size: 20px ">All Users</span>

        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('admin.users.store') }}" method="POST">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="name">Name</label>
                                    <input type="text" name="name" id="name" class="form-control"
                                        placeholder="Name" aria-describedby="helpName">
                                    @error('name')
                                        <span id="helpName" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="phone">Phone</label>
                                    <input type="text" name="phone" id="phone" class="form-control"
                                        placeholder="Phone" aria-describedby="helpPhone">
                                    @error('phone')
                                        <span id="helpPhone" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <label for="address">Address</label>
                                    <input type="text" name="address" id="address" class="form-control"
                                        placeholder="Address" aria-describedby="helpAddress">
                                    @error('address')
                                        <span id="helpAddress" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="form-group mb-3">
                                    <label for="password">Password</label>
                                    <input type="text" name="password" id="password" class="form-control"
                                        placeholder="password" aria-describedby="helpAddress">
                                    @error('password')
                                        <span id="helpAddress" class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <!-- Roles -->
                                <div class="form-group mb-3">
                                    <label class="d-block">Role</label>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="admin"
                                            id="roleAdmin">
                                        <label class="form-check-label" for="roleAdmin">Admin</label>
                                    </div>

                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="accountent"
                                            id="accountent">
                                        <label class="form-check-label" for="accountent">Accountent</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="checkbox" name="roles[]" value="staff"
                                            id="roleStaff">
                                        <label class="form-check-label" for="roleStaff">Staff</label>
                                    </div>


                                    @error('roles')
                                        <span class="text-danger d-block">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="text-center">
                                    <button type="submit" class="btn btn-secondary btn-sm btn mt-2">Create User</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="table-responsive card-body">
                            <table id="example" class="table table-bordered table-responsive-sm">
                                <thead>
                                    <tr>

                                        <th>
                                            Name
                                        </th>

                                        <th>
                                            Phone
                                        </th>

                                        <th>
                                            Role
                                        </th>

                                        <th>
                                            Activity
                                        <th>
                                            Status
                                        </th>

                                        <th>
                                            Action
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->phone }}</td>
                                            <td>{{ $user->getRoleNames()->join(', ') }}</td>

                                            <td> @php
                                                $isOnline =
                                                    $user->last_seen && $user->last_seen->gt(now()->subMinutes(5));
                                            @endphp
                                                @if ($isOnline)
                                                    <span class="badge badge-pill badge-success">Online</span>
                                                @else
                                                    <span class="badge bg-danger">Offline</span>
                                                    <small
                                                        class="d-block">{{ $user->last_seen ? $user->last_seen->diffForHumans() : 'Never Active' }}</small>
                                                @endif
                                            </td>

                                            <td>
                                                @if ($user->status == 1)
                                                    Active
                                                @else
                                                    Banned
                                                @endif
                                            </td>

                                            <td class="text-center">
                                                <div class="">
                                                    <!-- Log using ID -->

                                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                                        class="btn btn-info btn-sm">
                                                        <i class="bi bi-pencil-fill"></i>
                                                    </a>
                                                    @if (!$user->hasRole('admin'))
                                                        <a href="{{ route('admin.users.logUsingId', $user->id) }}"
                                                            class="btn btn-info btn-sm">
                                                            <i class="bi bi-person-lines-fill"></i>
                                                        </a>
                                                        <!-- Status toggle -->
                                                        <form action="{{ route('admin.users.status.update', $user->id) }}"
                                                            method="POST" style="display: inline;">
                                                            @csrf
                                                            <button type="submit"
                                                                class="btn btn-sm 
                                                                    @if ($user->status == 1) btn-success @else btn-danger @endif">
                                                                @if ($user->status == 1)
                                                                    <i class="bi bi-unlock-fill"></i> <!-- Unlock icon -->
                                                                @else
                                                                    <i class="bi bi-lock-fill"></i> <!-- Lock icon -->
                                                                @endif
                                                            </button>
                                                        </form>

                                                        <!-- Delete -->
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
                                                            method="POST" style="display:inline;"
                                                            onsubmit="return confirmDelete(event);">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger">
                                                                <i class="bi bi-trash-fill"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">


    <script>
        $('#example').DataTable({
            paging: true,
            searching: true,
            ordering: true,
            info: true,
            responsive: true,
            columnDefs: [{
                orderable: false,
                targets: [0, 5]
            }],


        });
    </script>
@endpush
