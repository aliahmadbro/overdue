@push('custom_head')

<link rel="stylesheet" href="//cdn.datatables.net/1.12.1/css/jquery.dataTables.min.css">
@endpush
<div class="card card-outline card-maroon">
    <div class="card-header">
        <h3 class="card-title">Listing</h3>

        <div class="card-tools">
            {{-- <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                <i class="fas fa-minus"></i>
            </button> --}}
            <a href="{{ route('admin.providerSupport.create') }}">
                <button type="button" class="btn btn-primary font-weight-bold">
                    <i class="fas fa-plus-square"></i> Add Provider Support
                </button>
            </a>
        </div>
    </div>
    @component('components.admin.providerSupport.listing', ['provider' => isset($provider) ? $provider : ''])
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
