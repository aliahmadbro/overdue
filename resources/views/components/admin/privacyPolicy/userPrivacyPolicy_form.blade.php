<form method="post" action="{{ route('admin.app.userSave') }}" enctype="multipart/form-data">
    @csrf
    <div class="form-group">
        <textarea name="description" id="summernote">{!! !empty($privacyPolicy->description) ? $privacyPolicy->description : '' !!}</textarea>
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