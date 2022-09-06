@push('custom_head')

<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endpush
<div class="card card-outline card-maroon">

    <div class="card-header">
        <h3 class="card-title">Listing</h3>

        <div class="card-tools">
            <a href="{{ route('admin.services.create') }}" class="float-right">
                <button type="button" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-plus-square"></i> Add Service
                </button>
            </a>
        </div>
    </div>
    @component('components.admin.services.listing', ['services' => isset($services) ? $services : ''])
    @endcomponent
</div>

@push('custom_scripts')
<script src="//cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js" defer></script>
<script>
    $(document).ready( function () {
        $('#index').DataTable();
    });
</script>
@endpush