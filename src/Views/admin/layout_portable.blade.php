<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <link rel="icon" href="{{ vncore_file(vncore_store_info('icon')) }}" type="image/png" sizes="16x16">
  <title>{{vncore_config_admin('ADMIN_TITLE')}} | {{ $title??'' }}</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/jqvmap/jqvmap.min.css')}}">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/overlayScrollbars/css/OverlayScrollbars.min.css')}}">
  <!-- summernote -->
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/summernote/summernote-bs4.css')}}">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

  <!-- iCheck -->
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/iCheck/square/blue.css')}}">
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  
  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/plugins/jquery-ui/jquery-ui.min.css')}}">

  <link rel="stylesheet" href="{{ vncore_file('Vncore/Admin/LTE/dist/css/adminlte.min.css')}}">

  @stack('styles')

</head>

<body class="hold-transition sidebar-mini layout-navbar-fixed layout-fixed accent-lightblue">

<div class="wrapper">
    @yield('main')
</div>
<!-- ./wrapper -->

<!-- jQuery -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/jquery/jquery.min.js')}}"></script>
<!-- jQuery UI 1.11.4 -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/jquery-ui/jquery-ui.min.js')}}"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
{{-- <script>
  $.widget.bridge('uibutton', $.ui.button)
</script> --}}
<!-- Bootstrap 4 -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
{{-- <!-- ChartJS -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/chart.js/Chart.min.js')}}"></script> --}}
<!-- JQVMap -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/jqvmap/jquery.vmap.min.js')}}"></script>
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/jqvmap/maps/jquery.vmap.usa.js')}}"></script>
<!-- daterangepicker -->
{{-- <script src="{{ vncore_file('Vncore/Admin/LTE/plugins/moment/moment.min.js')}}"></script>
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/daterangepicker/daterangepicker.js')}}"></script> --}}
<!-- Tempusdominus Bootstrap 4 -->
{{-- <script src="{{ vncore_file('Vncore/Admin/LTE/plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js')}}"></script> --}}
<!-- Summernote -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/summernote/summernote-bs4.min.js')}}"></script>
<!-- overlayScrollbars -->
<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js')}}"></script>

<script src="{{ vncore_file('Vncore/Admin/LTE/plugins/iCheck/icheck.min.js')}}"></script>

@stack('scripts')


<script>
  $(function () {
      $(".date_time").datepicker({
          dateFormat: "yy-mm-dd"
      });
  });
</script>

</body>
</html>