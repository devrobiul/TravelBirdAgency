@extends('backend.layout.app')

@push('style')
@endpush
@section('content')
@php
    $route = $note ? route('admin.note.update', $note->id) : route('admin.note.store');
@endphp
    <div class="row">
        <div class="col-md-4">
            <div class="card">

                <div class="card-header">
                    Office Note 
                </div>
                <div class="card-body">
                    <form action="{{ $route }}" method="post">
                        @csrf
                        @if ($note)
                            @method('PUT')
                        @endif
                        <div class="form-group">
                            <label for="note">Note</label>
                            <textarea name="note" id="note" cols="3" rows="3" placeholder="Office note" class="form-control">{{ $note->note ?? null }}</textarea>
                            @error('note')
                                  <small id="helpId" class="text-danger">{{$message}}</small>
                            @enderror
                          
                        </div>
                        <div class="text-center">
                            <button class="btn btn-sm btn-secondary"><i class="fas fa-sync    "></i> Submit</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="example" class="table table-bordered table-responsive-sm">
                            <thead>
                                <tr>
                                    <th>Sl</th>
                                    <th>Creator</th>
                                    <th>Note</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($notes as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->name??'N/A' }}</td>
                                        <td>{{ $item->note }}</td>

                                        <td>
                                            <form action="{{ route('admin.note.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirmDelete(event);" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>

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
@endsection

@push('scripts')


    <script>
   $(document).ready(function() {
    $('#example').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        info: true,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [0, 2] } // Sl & Action not orderable
        ],
        fnRowCallback: function(nRow, aData, iDisplayIndex, iDisplayIndexFull) {
            $('td:eq(0)', nRow).html(iDisplayIndexFull + 1);
        }
    });
});

    </script>
@endpush
