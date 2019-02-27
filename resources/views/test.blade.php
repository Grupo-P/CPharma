@extends('layouts.model')

@section('title')
    Reporte
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-invoice"></i>
		TEST
	</h1>
	<hr class="row align-items-start col-12">
	
<?php 
  include(app_path().'\functions\config.php');
  include(app_path().'\functions\Functions.php');

  $ArtJson = "";
  
  if (isset($_GET['Id'])  )
  {
    TableHistoricoArticulos($_GET['Id']);
  } 
  else{
    $sql = QueryArticulosDescripcion();
    $ArtJson = armarJson($sql);

    echo '
    <form autocomplete="off" action="">
        <div class="autocomplete" style="width:90%;">
          <input id="myInput" type="text" name="Descrip" placeholder="Ingrese el nombre del articulo " onkeyup="conteo()">
          <input id="myId" name="Id" type="hidden">
        </div>
        <input type="submit" value="Buscar" class="btn btn-outline-success">
      </form>
      ';
  } 
?>

<script type="text/javascript">
jQuery(document).on('ready',function(){

  var ArtJson = eval(<?php echo $ArtJson ?>);
  llenarAutoComplete();
  
  /*Autocomplete para Reporte Para Pedidos*/
  function llenarAutoComplete() {
    jQuery( "#myInput" ).autocomplete({
      source: ArtJson,
      delay: 150,
      minLength: 3,
      open: function(e, ui) {
        //using the 'open' event to capture the originally typed text
        var self = $(this),
        val = self.val();
        //saving original search term in 'data'.
        self.data('searchTerm', val);
      },
      focus: function(e, ui) {
        return false;
      },
      select: function( e, ui ) {
        var self = $(this),
          keyPressed = e.keyCode,
          keyWasEnter = e.keyCode === 13,
          useSelection = false,
          val = self.data('searchTerm');
        if (keyPressed) {
          if (keyWasEnter) {
            e.preventDefault();
          }
        }
        return useSelection;
      }
    });
    $("#myInput").autocomplete( "enable" );
  }

  //Presionar Enter dentro de la barra de busqueda
  $('#myInput').keyup(function(e) {
        //cuando el modo de busqueda hace referencia al nombre de productos
        // if(e.keyCode == 13) {
        //   if ( $('#input_busq').val() != "" ) {         
        //     consultaAjax( "op="+$('#input_busq').val() , "lib/busquedaM.php", 1 , '.contApp' );
        //   }
        // }
        if(e.keyCode == 13) {
          if ( $('#myInput').val() != "" ) {
            console.log('Hola');
          }   
        }
  });

});
</script>
@endsection

@section('scriptsHead')
    <script type="text/javascript" src="{{ asset('assets/js/sortTable.js') }}">
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/filter.js') }}">	
    </script>
    <script type="text/javascript" src="{{ asset('assets/js/functions.js') }}">	
    </script>
    <script type="text/javascript" src="{{ asset('assets/jquery/jquery-2.2.2.min.js') }}"></script>
  	<script type="text/javascript" src="{{ asset('assets/jquery/jquery-ui.min.js') }}" ></script>

  	<style>
    * {
      box-sizing: border-box;
    }

    /*the container must be positioned relative:*/
    .autocomplete {
      position: relative;
      display: inline-block;
    }

    input {
      border: 1px solid transparent;
      background-color: #f1f1f1;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }

    input[type=text] {
      background-color: #f1f1f1;
      width: 100%;
    }

    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
      /*position the autocomplete items to be the same width as the container:*/
      top: 100%;
      left: 0;
      right: 0;
    }

    .autocomplete-items div {
      padding: 10px;
      cursor: pointer;
      background-color: #fff; 
      border-bottom: 1px solid #d4d4d4; 
    }

    /*when hovering an item:*/
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
    }

    /*when navigating through the items using the arrow keys:*/
    .autocomplete-active {
      background-color: DodgerBlue !important; 
      color: #ffffff; 
    }
    </style>
@endsection