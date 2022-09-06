@push('custom_head')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endpush

<div class="card card-outline card-maroon">
    <div class="card-header">
        <h3 class="card-title">{{!empty($service) ? 'Update' : 'Add'}} Service</h3>
    </div>
    @component('components.admin.services.service_form', ['service' => (isset($service) ? $service : ''), 'parentService' => (isset($parentService) ? $parentService : ''),'subCategory'=>(isset($subCategory) ? $subCategory : '')]) @endcomponent
</div>


@push('custom_scripts')
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
@endpush
