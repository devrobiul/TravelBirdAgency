@extends('backend.layout.app')


@section('content')
    <div class="col-md-12">
        <div class="row">
            <div class="col-md-4 col-sm-12">
                <div class="card mt-3">
                    <div class="card-header">
                        Expense category
                    </div>
                    <div class="card-body">
                        @php
                            if ($category) {
                                $route = route('admin.expense.category.update', $category->id);
                            } else {
                                $route = route('admin.expense.category.store');
                            }
                        @endphp
                        <form action="{{ $route }}" method="POST" id="saleForm">
                            @csrf
                            @if ($category)
                                @method('PUT')
                            @endif
                            <div class="form-group">
                                <label for="category_type">Select type</label>
                        <select name="category_type" id="category_type" class="form-control select2" required>
                                    <option value="">Select type</option>
                                    <option value="operating" @selected($category && $category->category_type == 'operating')>
                                        Operating
                                    </option>
                                    <option value="non_operating" @selected($category && $category->category_type == 'non_operating')>
                                        Non-Operating
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                           
                                <label for="">Enter category name</label>
                                <input type="text" name="name" id="name" class="form-control"
                                    placeholder="Enter category name" required autofocus
                                    value="{{ $category->name ?? null }}">
                            </div>
                   

                            <button type="submit" class="btn btn-secondary btn-sm  float-end">
                                <i class="bi bi-check-circle-fill"></i> Submit
                            </button>
                        </form>

                    </div>
                </div>
            </div>
            <div class="col-md-8 col-sm-12">
                <div class="card mt-3">

                    <div class="card-body">
                        <div class="table-responsive mt-5">
                            <table class="table table-bordered datatable">
                                <thead class="table-light">
                                    <tr>

                                        <th>SL</th>
                                        <th>Main Category</th>
                                        <th>Name</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>

                                            <td>
                                                {{ $loop->index + 1 }}
                                            </td>
                                            <td>
                                                {{ ucfirst($category->category_type )}}
                                            </td>
                                            <td>
                                                {{ $category->name }}
                                            </td>
                                            <td>
                                                <div class="">

                                                    <a href="{{ route('admin.expense.category.edit', $category->id) }}"
                                                        class="btn btn-sm btn-warning me-3" title="Edit">
                                                        <i class="bi bi-pencil-square"></i>
                                                    </a>

                                                    <form
                                                        action="{{ route('admin.expense.category.destroy', $category->id) }}"
                                                        method="POST" onsubmit="return confirmDelete(event);"
                                                        style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>

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

@push('js')
    <script>
        $(document).ready(function() {

            $('.datatable').DataTable({
                responsive: true,
                pageLength: 10,

            });
        });
    </script>
@endpush
