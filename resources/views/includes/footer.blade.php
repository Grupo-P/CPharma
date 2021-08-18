<?php
    include_once(app_path().'\functions\config.php');
    include_once(app_path().'\functions\functions.php');
?>

<div class="p-3 mb-2 bg-white text-info footer-copyright text-center py-3">
	<span><?php echo Version?> © 2018 - <?php echo''.date('Y'); ?> Copyright Grupo P</span>
	<br>
  Developed and designed by
	<a href="https://www.instagram.com/covacode/" target="blank" class="text-info CP-Links-No-Style">Sergio Cova</a>
</div>

@section('scriptsFoot')
    <!-- Page level plugins -->
    <script src="{{asset('assets/chart.js/Chart.min.js')}}"></script>

    <?php
        if(FG_Mi_Ubicacion()=="FAU"){
    ?>
        <!-- Page level custom scripts -->
        <script src="{{asset('assets/js/chart-pie-demo.js')}}"></script>
    <?php
        }
    ?>

    <script type="text/javascript">
      $(document).keydown(function (e) {
        e = e || event;
        keypressCPharma(e);
      });
    </script>
@endsection
