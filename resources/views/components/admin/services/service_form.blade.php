<form action="{{ !empty($service) ? route('admin.services.update') : route('admin.services.save') }}" method="post"
    enctype="multipart/form-data">
    <div class="card-body">
        @csrf
        @if (!empty($service))
            <input type="hidden" name="id" value="{{ $service->id }}" />
        @endif
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="icheck-primary d-inline">
                        <input type="checkbox" id="sub-service-checkbox" name="sub_service" value="1"
                            {{ isset($service) && !empty($service->parent_id) ? 'checked' : '' }}>
                        <label for="sub-service-checkbox">
                            Sub Service
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group" id="service-name-field">
                    <label for="serviceName">Title</label>
                    <input type="text" name="name" class="form-control" id="serviceName"
                        placeholder="Enter Service Name" value="{{ isset($service->name) ? $service->name : '' }}"
                        required>
                </div>
            </div>
            <div class="col-6 {{ !empty($service) && !empty($service->parent_id) ? '' : 'd-none' }}"
                id="sub-service-field">
                <div class="form-group">
                    <label for="sub-service">Sub Service of</label>
                    <select class="form-control select2" name="parent_id" style="width: 100%;">
                        <option value="">Please Select</option>
                        @foreach ($parentService as $parent)
                            <option value="{{ $parent->id }}"
                                {{ !empty($service->parent_id) && $service->parent_id == $parent->id ? 'selected' : '' }}>
                                {{ $parent->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>
        <div class="col-6" id="sub-category-field">
            <div class="form-group">
                <label for="sub-category">Sub Category</label>
                <select class="form-control select2" name="sub_id" style="width: 100%;">
                    <option value="">Please Select</option>
                    @foreach ($subCategory as $sub)
                        <option value="{{ $sub->id }}"
                            {{ !empty($service) && $service->sub_id == $sub->id ? 'selected' : '' }}>
                            {{ $sub->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="service-description">Description</label>
                    <textarea name="description" class="form-control" id="service-description">{!! !empty($service->description) ? $service->description : '' !!}</textarea>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <div class="btn btn-default btn-file">
                        <i class="fas fa-image"></i> <span class="h5">Image</span>
                        <input type="file" name="service_image" id="serviceImg" accept="image/*">
                    </div>
                    <p class="help-block">Max. 5MB</p>
                </div>
            </div>
            <div class="col-6 {{ !empty($service->image) ? '' : 'd-none' }}" id="image-display">
                @if (!empty($service->image))
                    <img class="img-fluid" src="{{ asset($service->image) }}" alt="img"
                        style="max-height:200px; max-width=" 100px">
                @endif
            </div>
        </div>
    </div>

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
                $('#sub-service-checkbox').on('click', function() {
                    if ($(this).is(':checked')) {
                        $("#sub-service-field").removeClass('d-none');
                    } else {
                        $("#sub-service-field").addClass('d-none');
                    }
                });

                $("#serviceImg").on('change', function(e) {
                    $("#image-display").html("");
                    for (var i = 0; i < e.target.files.length; i++) {
                        if (e.target.files[i].type.includes('image') && e.target.files[i].size >= 5000000) {
                            alert('Image Size should upto 5Mb');
                            return false;
                        }

                        if (e.target.files[i].type.includes('image')) {
                            $("#image-display").append(`<img class="img-fluid" src="` + URL.createObjectURL(e
                                    .target.files[i]) +
                                `" alt="img" style="max-height:200px; max-width="100px">`);
                        }

                    }
                    $("#image-display").removeClass("d-none");
                });
            });
        </script>
    @endpush
@endonce
