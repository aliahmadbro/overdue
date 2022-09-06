<div class="modal fade confirmationModal" id="confirmationModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmation</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @if (isset($description))
                    <p class="text-center custom-confirmation-text">
                        {!! $description !!}
                    </p>
                @else
                    <p class="text-center h3 p-3">
                        <b>Are you sure?</b>
                    </p>
                @endif
            </div>
            <div class="modal-footer justify-content-between">
                <a href="#" class="confirmation-no"><button type="button" class="btn btn-default"
                        data-dismiss="modal">Close</button></a>
                <a href="#" class="confirmation-yes"><button type="button" class="btn btn-primary">Save
                        changes</button></a>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
<!-- /.modal -->

@push('custom_scripts')
    <script>
        $(document).ready(function() {
            $('.confirmation-modal').click(function(e) {
                e.preventDefault();
                $('#confirmationModal').modal('show');
                $('.confirmation-yes').attr("href", $(this).attr('data-url'));
            });

            $('.confirmation-no').click(function(e) {
                e.preventDefault();
                $('#confirmationModal').modal('hide');
                $(this).siblings('.confirmation-yes').removeAttr('href');
            });
        });
    </script>
@endpush
    