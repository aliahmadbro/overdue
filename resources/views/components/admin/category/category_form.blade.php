<form action="{{ !empty($category) ? route('admin.category.update') : route('admin.category.save') }}" method="post"
    enctype="multipart/form-data">
    <div class="card-body">
        @csrf
        @if (!empty($category))
            <input type="hidden" name="id" value="{{ $category->id }}" />
        @endif
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="sub-category-checkbox" name="sub_category" value="1"
                            {{ !empty($category) && !empty($category->parent_id) ? 'checked' : '' }}>
                        <label for="sub-category-checkbox">
                            Sub Category
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group" id="category-name-field">
                    <label for="categoryName">Name</label>
                    <input type="text" name="name" class="form-control" id="categoryName"
                        placeholder="Enter Category Name" value="{{ !empty($category->name) ? $category->name : '' }}"
                        required>
                </div>
            </div>

            <div class="col-6 {{ !empty($category) && !empty($category->parent_id) ? '' : 'd-none' }}"
                id="sub-category-field">
                <div class="form-group">
                    <label for="sub-category">Parent category</label>
                    <select class="form-control select2" name="parent_id" style="width: 100%;">
                        <option value="">Please Select</option>
                        @foreach ($parentCategory as $parent)
                            <option value="{{ $parent->id }}"
                                {{ !empty($category->parent_id) && $category->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-6">
                <div class="form-group">
                    <label for="categoryDescription">Description</label>
                    <textarea name="description" class="form-control" id="categoryDescription">{!! !empty($category->description) ? $category->description : '' !!}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="btn btn-default btn-file">
                        <i class="fas fa-image"></i> <span class="h5">Image</span>
                        <input type="file" name="category_image" id="upload-image" accept="image/*">
                    </div>
                    <p class="help-block">Max. 5MB</p>
                </div>
            </div>
            <div class="col-6 {{ !empty($category->image) ? '' : 'd-none' }}" id="image-display">
                @if (!empty($category->image))
                    <img class="img-fluid" src="{{ asset($category->image) }}" alt="img"
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
