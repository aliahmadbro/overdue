@push('custom_head')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{asset('plugins/select2/css/select2.min.css')}}">
@endpush

<div class="card card-outline card-maroon">
    <div class="card-header">
        <h3 class="card-title">{{!empty($provider) ? 'Update' : 'Add'}} Provider Support</h3>
    </div>
    @component('components.admin.providerSupport.providerSupport_form', ['provider' => (isset($provider) ? $provider : '')]) @endcomponent
</div>


@push('custom_scripts')
    <!-- Select2 -->
    <script src="{{asset('plugins/select2/js/select2.full.min.js')}}"></script>
@endpush