@push('custom_head')

<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
@endpush
<div class="card">
    <div class="card-header container-fluid">
        <div class="row">
            <div class="col-md-10 text-center">
                <h4>{{!empty($privacyPolicy) ? 'Update' : 'Add'}} Privacy Policy</h4>
            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    @component('components.admin.privacyPolicy.userPrivacyPolicy_form', ['privacyPolicy' => (isset($privacyPolicy) ? $privacyPolicy : '')]) @endcomponent
                </div>
            </div>
        </div>
    </div>
</div>


@push('custom_scripts')
    <script>
        $(document).ready(function() {
            $('#summernote').summernote();
        });
    
      </script>
      <script src="{{ asset('public/ckeditor/ckeditor.js') }}"></script>
      <script>
            CKEDITOR.replace('summernote');
      </script>
@endpush