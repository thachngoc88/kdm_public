
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('pageTitle')</title>

    <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />

    <link href="/css/app.css" rel="stylesheet" />

    <!-- ================== BEGIN BASE CSS STYLE ================== -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
    <link href="/assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
    <link href="/assets/css/animate.min.css" rel="stylesheet" />
    <link href="/assets/css/style.min.css" rel="stylesheet" />
    <link href="/assets/css/style-responsive.min.css" rel="stylesheet" />
    <link href="/assets/css/theme/default.css" rel="stylesheet" id="theme" />
    <!-- ================== END BASE CSS STYLE ================== -->

    <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
    <link href="/assets/plugins/select2/dist/css/select2.min.css" rel="stylesheet" />
    <link href="/assets/plugins/dropzone/min/dropzone.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/Buttons/css/buttons.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/AutoFill/css/autoFill.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/ColReorder/css/colReorder.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/KeyTable/css/keyTable.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/RowReorder/css/rowReorder.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="/assets/plugins/switchery/switchery.min.css" rel="stylesheet" />
    <link href="/assets/plugins/parsley/src/parsley.css" rel="stylesheet" />
    <link href="/assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />
    <link href="/assets/plugins/bootstrap-timepicker/css/bootstrap-timepicker.css" rel="stylesheet" />
    <!-- ================== END PAGE LEVEL STYLE ================== -->
    <link href="/css/custom.css" rel="stylesheet" />
    <link href="/css/error.css" rel="stylesheet" />
    @stack('css')
</head>
<body class="@yield('bodyClass')">
<!-- begin #page-loader -->
<div id="page-loader" class="fade in"><span class="spinner"></span></div>
<!-- end #page-loader -->

<!-- begin #page-container -->
<div id="page-container" class="fade page-sidebar-fixed">

@include('header')

@section('sidebar')
@include('sidebar')
@show

    <!-- begin #content -->
    <div id="content" class="content">

        @yield('breadcrumb')

        <!-- begin page-header -->
        <h1 class="page-header">@yield('pageTitle') <small></small></h1>
        <!-- end page-header -->

        <!-- Page Content Here -->
        @yield('content')
    </div>
    <!-- end #content -->


    <!-- begin scroll to top btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-top fadeq" data-click="scroll-top"><i class="fa fa-angle-up"></i></a>
    <!-- end scroll to top btn -->

    <!-- begin scroll to top btn -->
    <a href="javascript:;" class="btn btn-icon btn-circle btn-success btn-scroll-to-bottom fadeq in" data-click="scroll-bottom"><i class="fa fa-angle-down"></i></a>
    <!-- end scroll to top btn -->


</div>





<!-- ================== BEGIN BASE JS ================== -->
<script src="/assets/plugins/jquery/jquery-1.9.1.min.js"></script>
<script src="/assets/plugins/jquery/jquery-migrate-1.1.0.min.js"></script>
<script src="/assets/plugins/jquery-ui/ui/minified/jquery-ui.min.js"></script>
<script src="/assets/plugins/bootstrap/js/bootstrap.min.js"></script>
<!--[if lt IE 9]>
<script src="/assets/crossbrowserjs/html5shiv.js"></script>
<script src="/assets/crossbrowserjs/respond.min.js"></script>
<script src="/assets/crossbrowserjs/excanvas.min.js"></script>
<![endif]-->
<script src="/assets/plugins/slimscroll/jquery.slimscroll.min.js"></script>
<script src="/assets/plugins/jquery-cookie/jquery.cookie.js"></script>
<!-- ================== END BASE JS ================== -->

<!-- ================== BEGIN PAGE LEVEL JS ================== -->
<script src="/assets/plugins/DataTables/media/js/jquery.dataTables.js"></script>
<script src="/assets/plugins/DataTables/media/js/dataTables.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/dataTables.buttons.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/buttons.bootstrap.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/buttons.flash.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/jszip.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/pdfmake.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/vfs_fonts.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/buttons.html5.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Buttons/js/buttons.print.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Responsive/js/dataTables.responsive.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/AutoFill/js/dataTables.autoFill.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/ColReorder/js/dataTables.colReorder.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/KeyTable/js/dataTables.keyTable.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/RowReorder/js/dataTables.rowReorder.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/Select/js/dataTables.select.min.js"></script>
<script src="/assets/plugins/DataTables/extensions/FixedColumns/js/dataTables.fixedColumns.js"></script>
{{--<script src="/assets/plugins/gritter/js/jquery.gritter.js"></script>--}}
<script src="/assets/plugins/dropzone/min/dropzone.min.js"></script>
<script src="/assets/plugins/switchery/switchery.min.js"></script>
<script src="/assets/plugins/powerange/powerange.min.js"></script>
<script src="/assets/plugins/parsley/dist/parsley.js"></script>
<script src="/vendor/datatables/buttons.server-side.js"></script>

<script src="/assets/js/apps.min.js"></script>


<!-- ================== END PAGE LEVEL JS ================== -->
<script src="/js/kdmDatatables.js"></script>
<script src="/js/modal.js"></script>
<script>
    $(document).ready(function() {
        App.init();
        //$.fn.dataTable.ext.errMode = 'none';
    });
</script>
@stack('scripts')
@yield('extraScript')

</body>
</html>