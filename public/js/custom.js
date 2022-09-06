$(document).ready(function () {

    // UPLOAD SINGLE IMAGE
    $("#upload-image").on('change', function (e) {
        $("#image-display").html("");
        for (var i = 0; i < e.target.files.length; i++) {
            if (e.target.files[i].type.includes('image') && e.target.files[i].size >= 5000000) {
                alert('Image Size should upto 5Mb');
                return false;
            }

            if (e.target.files[i].type.includes('image')) {
                $("#image-display").append(`<img class="img-fluid" src="` + URL.createObjectURL(e.target.files[i]) + `" alt="img" style="max-height:200px; max-width="100px">`);
            }

        }
        $("#image-display").removeClass("d-none");
    });

    // DELETE CLONE
    $(document).on('click', '.delete-clone', function () {
        $("." + $(this).data('delete-clone')).remove();
    });
});