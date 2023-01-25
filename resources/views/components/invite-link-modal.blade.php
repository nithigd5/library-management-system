<div class="modal fade" id="generate-link-modal" tabindex="-1" style="display: none">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Customer Invitation Link</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <textarea class="form-control text-primary" id="invite-link" rows="4" style="height: auto"
                          onclick="this.select()" readonly></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal" onclick="copyToClipboard()">Copy Link
                </button>
            </div>
        </div>
    </div>
</div>
<script>

    function generateLink() {
        $.get('{{ route('admin.customers.invite') }}', function (response) {
            $('#generate-link-modal').modal('show').find('#invite-link').val(response);
        })
    }

    function copyToClipboard() {
        $('#invite-link').select()
        document.execCommand('copy')
    }
</script>
