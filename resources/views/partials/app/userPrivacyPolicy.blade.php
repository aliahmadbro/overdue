{{-- Author Umar A --}}
<!DOCTYPE html>
<html lang="en">
    <!-- adminlte-->
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
    
    <link rel="stylesheet" href="{{ asset('plugins/icheck-bootstrap/icheck-bootstrap.min.css') }}">

<body>
    
    <div class="container-fluid">
            <!-- Content Header (Page header) -->
            <section class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>{{ !empty($title) ? $title : '' }}</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                @yield('breadcrumb')
                            </ol>
                        </div>
                    </div>
                </div>
                <!-- /.container-fluid -->
            </section>

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <!-- Default box -->
                            
                            <div class="form-group">
                                <div>{!! $privacyPolicy->description!!}</div>
                            </div>
                            <!-- /.card -->
                        </div>
                    </div>
                </div>

            </section>
    </div>
    <!-- jQuery -->
<script src="{{asset('plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('plugins/bootstrap/bootstrap.bundle.min.js')}}"></script>
</body>

</html>
