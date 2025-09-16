<div class="modal-dialog modal-md">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="staticBackdropLabel">Clinet/Customer</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>


<form action="{{ $customer ? route('admin.customer.update', $customer->id) : route('admin.customer.store') }}"
      method="post"
      class="customersubmit"
      enctype="multipart/form-data">
    @csrf
    @if ($customer)
        @method('PUT')
    @endif

            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Customer name</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $customer->name ?? '') }}"
                                class="form-control  @error('name')is-invalid @enderror" placeholder="Customer name"
                                aria-describedby="helpId">
                            <span class="text-danger error-text name_error"></span>

                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="phone">Customer phone</label>
                            <input type="text" name="phone" id="phone"value="{{ old('name', $customer->phone ?? '') }}"
                                class="form-control  @error('phone')is-invalid @enderror" placeholder="Customer phone"
                                aria-describedby="helpId">

                            <span class="text-danger error-text phone_error"></span>

                        </div>
                    </div>



                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="address">Customer Adress</label>
                            <input type="text" name="address" id="address"value="{{ old('name', $customer->address ?? '') }}"
                                class="form-control " placeholder="Customer Adress" aria-describedby="helpId">

                        </div>
                    </div>

                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success  btn-sm"><i class="fas fa-sync"></i> {{ $customer ? 'Update' : 'Submit' }}</button>
            </div>
        </form>

    </div>
</div>
<style>
    .form-group {
        margin-bottom: 10px !important
    }

    label {
        margin-bottom: 0px !important
    }
</style>

<script src="{{ asset('backend/assets/js/ajax.js') }}"></script>
