<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title" id="staticBackdropLabel">Refund Status</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <form action="{{ route('admin.inventory.refundticket.statusStore', $ticket->id) }}" method="post"
            class="" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="account_name">Refund Status</label>
                            <select name="status" class="form-control select2" id="">
                                <option value="0">Pending</option>
                                <option value="1">Refund</option>
                            </select>
                        </div>
                    </div>


                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary btn-sm">Submit</button>
            </div>
        </form>

    </div>
</div>

<script>
    $('select2').select2();
</script>
