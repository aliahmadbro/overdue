<form action="{{ !empty($banner) ? route('admin.banner.update') : route('admin.banner.save') }}" method="post"
    enctype="multipart/form-data">
    <div class="card-body">
        @csrf
        @if (!empty($banner))
            <input type="hidden" name="id" value="{{ $banner->id }}" />
        @endif
        <div class="row">
            <div class="col-6">
                <div class="form-group" id="banner-name-field">
                    <label for="bannerName">Name</label>
                    <input type="text" name="name" class="form-control" id="bannerName"
                        placeholder="Enter banner Name" value="{{ !empty($banner->name) ? $banner->name : '' }}"
                        required>
                </div>
            </div>



            <div class="col-6">
                <div class="form-group">
                    <label for="bannerDescription">Description</label>
                    <textarea name="description" class="form-control" id="bannerDescription">{!! !empty($banner->description) ? $banner->description : '' !!}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="btn btn-default btn-file">
                        <i class="fas fa-image"></i> <span class="h5">Image</span>
                        <input type="file" name="banner_image" id="upload-image" accept="image/*">
                    </div>
                    <p class="help-block">Max. 5MB</p>
                </div>
            </div>
            <div class="col-6 {{ !empty($banner->image) ? '' : 'd-none' }}" id="image-display">
                @if (!empty($banner->image))
                    <img class="img-fluid" src="{{ asset($banner->image) }}" alt="img"
                        style="max-height:200px; max-width=" 100px">
                @endif
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
                $('#sub-banner-checkbox').on('click', function() {
                    if ($(this).is(':checked')) {
                        $("#sub-banner-field").removeClass('d-none');
                    } else {
                        $("#sub-banner-field").addClass('d-none');
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
