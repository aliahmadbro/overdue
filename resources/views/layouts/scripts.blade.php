<!-- jQuery -->
<script src="{{asset('/public/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('/public/plugins/bootstrap/bootstrap.bundle.min.js')}}"></script>
<!-- pace-progress -->
<script src="{{asset('/public/plugins/pace-progress/pace.min.js')}}"></script>
<!-- Toastr -->
<script src="{{asset('/public/plugins/toastr/toastr.min.js')}}"></script>
<!-- Select2 -->
<script src="{{asset('/public/plugins/select2/js/select2.full.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('/public/js/adminlte.min.js')}}"></script>

<script>
    $(document).ready(function() {
        $(".select2").select2();
    });
</script>

{{-- CUSTOM GENERIC JS --}}
<script src="{{asset('public/js/custom.js')}}"></script>