<div class="p-3 mb-2 bg-white text-info footer-copyright text-center py-3">
	<span>CPharma v.5.9 © 2018 - <?php echo''.date('Y'); ?> Copyright Grupo P</span>
	<br>
  Developed by
	<a href="https://www.instagram.com/covacode/" target="blank" class="text-info CP-Links-No-Style">Sergio Cova</a>,
  <a href="https://api.whatsapp.com/send?phone=584246827377&text=&source=&data=" target="blank" class="text-info CP-Links-No-Style">Manuel Henriquez</a>
  and 
  <a href="#" class="text-info CP-Links-No-Style">Rubmary Vielma</a>
</div>

@section('scriptsFoot')
    <script type="text/javascript">
      $(document).keydown(function (e) {
        e = e || event;
        keypressCPharma(e);
      });
    </script>
@endsection