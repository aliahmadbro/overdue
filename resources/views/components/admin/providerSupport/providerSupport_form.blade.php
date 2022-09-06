<form action="{{ !empty($provider) ? route('admin.providerSupport.update') : route('admin.providerSupport.save') }}" method="post"
    enctype="multipart/form-data">
    <div class="card-body">
        @csrf
        @if (!empty($provider))
            <input type="hidden" name="id" value="{{ $provider->id }}" />
        @endif
        <div class="row">
            <div class="col-6">
                <div class="form-group" id="category-name-field">
                    <label for="question">Question</label>
                    <input type="text" name="question" class="form-control" id="question"
                        placeholder="Enter Question" value="{{ !empty($provider->question) ? $provider->question : '' }}"
                        required>
                </div>
            </div>



            <div class="col-6">
                <div class="form-group">
                    <label for="answer">Answer</label>
                    <textarea name="answer" class="form-control" id="categoryDescription">{!! !empty($provider->answer) ? $provider->answer : '' !!}</textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- /.card-body -->
    <div class="card-footer">
        <div class="row">
            <div class="col-12">
                <a href="{{ url()->previous() }}" class="btn btn-default">Back</a>
                <input type="submit" value="Save" class="btn btn-primary float-right">
            </div>
        </div>
    </div>

</form>

@once
    @push('custom_scripts')
        <script>
            $(document).ready(function() {
                $('.select2').select2()
                $('#sub-category-checkbox').on('click', function() {
                    if ($(this).is(':checked')) {
                        $("#sub-category-field").removeClass('d-none');
                    } else {
                        $("#sub-category-field").addClass('d-none');
                    }
                });


                // UPLOAD SINGLE BANNER
                $("#upload-banner").on('change', function(e) {
                    $("#banner-display").html("");
                    if (e.target.files[0].type.includes('image') && e.target.files[0].size >= 5000000) {
                        alert('Banner Size should upto 5Mb');
                        return false;
                    }

                    if (e.target.files[0].type.includes('image')) {
                        $("#banner-display").append(`<img class="img-fluid" src="` + URL.createObjectURL(e
                                .target.files[0]) +
                            `" alt="banner" style="max-height:200px; max-width="100px">`);
                    }
                    $("#banner-display").removeClass("d-none");
                });
            });
        </script>
    @endpush
@endonce
