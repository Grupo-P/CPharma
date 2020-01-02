@extends('layouts.modelUser')

@section('title')
  Consultor Compras
@endsection

<style>
	* {
      box-sizing: border-box;
    }
    .autocomplete {
      position: relative;
      display: inline-block;
    }
    input {
      border: 1px solid transparent;
      background-color: #fff;
      border-radius: 5px;
      padding: 10px;
      font-size: 16px;
    }
    input[type=text] {
    	text-align: center;
    	font-size: 1.6em;
      background-color: #fff;
      width: 100%;
      height: 50px;
    }
    input[type=text]:focus {
		 	outline: 0;
    }
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      z-index: 99;
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
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
    }
    .autocomplete-active {
      background-color: #17a2b8 !important; 
      color: #ffffff; 
    }
		.center th {
	    vertical-align: middle;
	    text-align: center;
	  }
	  .aum-icon-lup {
    	text-align: center;
    	font-size: 1em;
    }
    .aum-icon-cod {
      text-align: center;
      font-size: 1.2em;
    }
    .boton-tabla{
      background-color: #fff;
      color: #17a2b8;
    }
    .boton-tabla:hover{
      background-color: #17a2b8;
      color: #fff;
    }
    .link-opc{
      background-color: #fff;
      color: #000;
      padding: 8px;
    }
    .link-opc:hover{
      color: #17a2b8;
    }
</style>

@section('content')
	<?php 
	  include(app_path().'\functions\config.php');
	  include(app_path().'\functions\functions.php');
	  include(app_path().'\functions\querys_mysql.php');
	  include(app_path().'\functions\querys_sqlserver.php');

    $SedeConnection = FG_Mi_Ubicacion();
    $RutaUrl = FG_Mi_Ubicacion();

	  $CodJson = '';
    $ArtJson = '';

		$sql1 = RCCQ_Lista_Articulos_CodBarra();
    $CodJson = FG_Armar_Json($sql1,$SedeConnection);

    $sql1 = RCCQ_Lista_Articulos_Descripcion();
    $ArtJson = FG_Armar_Json($sql1,$SedeConnection);
	?>
    <!-- Modal Box -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header text-white bg-info">
            <h2 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-search"></i> Modo de busqueda</h2>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="modo_opciones link-opc" id="opc_1">
              <h3><i class="fas fa-language aum-icon-lup"></i> Nombre del producto</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_2">
              <h3><i class="fas fa-dna aum-icon-lup"></i> Principio Activo o Componente</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_3">
              <h3><i class="fas fa-barcode aum-icon-lup"></i> Código de barra</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_4">
              <h3><i class="fas fa-pills aum-icon-lup"></i> Uso terapéutico</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- / Modal Box -->

    <table class="table table-borderless col-12">
      <thead class="center">
        <tr>
          <th scope="col" colspan="3">
            <h1 class="text-info">
              <!-- {{FG_Nombre_Sede(FG_Mi_Ubicacion())}} -->
              {{FG_Nombre_Sede('FTN')}}
            </h1>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr class="bg-white" style="border: 4px solid #17a2b8;">
          <td align="center" class="boton-tabla" id="btn_selector">
            <h1><p id="icon-btn"></p></h1>
          </td>
          <td align="center" style="border: 4px solid #17a2b8;">
            <input id="inputCodBar" type="text" name="CodBar" autofocus="autofocus">
          </td>
          <td align="center" class="boton-tabla"><h1><i class="fas fa-search aum-icon-lup"></i></h1></td>
        </tr>
      </tbody>
    </table>
@endsection

@section('scriptsPie')
  <script type="text/javascript">
    function updateModalBox(modo_selec) {
      if (modo_selec == 1) {
        $("#icon-btn").html('');
        $("#icon-btn").html('<i class="fas fa-language aum-icon-cod" ></i>');
        $('#inputCodBar').attr("placeholder", "Ingrese el nombre del producto");
        $('#inputCodBar').attr("onblur", "this.placeholder = 'Ingrese el nombre del producto'");
        $('#inputCodBar').attr("onfocus", "this.placeholder = ''");
      }
      else if (modo_selec == 2) {
        $("#icon-btn").html('');
        $("#icon-btn").html('<i class="fas fa-dna aum-icon-cod" ></i>');
        $('#inputCodBar').attr("placeholder", "Ingrese el principio activo o componente");
        $('#inputCodBar').attr("onblur", "this.placeholder = 'Ingrese el principio activo o componente'");
        $('#inputCodBar').attr("onfocus", "this.placeholder = ''");
      }
      else if (modo_selec == 3) {
        $("#icon-btn").html('');
        $("#icon-btn").html('<i class="fas fa-barcode aum-icon-cod" ></i>');
        $('#inputCodBar').attr("placeholder", "Haga scan del codigo de barra");
        $('#inputCodBar').attr("onblur", "this.placeholder = 'Haga scan del codigo de barra'");
        $('#inputCodBar').attr("onfocus", "this.placeholder = ''");
      }
      else if (modo_selec == 4) {
       $("#icon-btn").html('');
        $("#icon-btn").html('<i class="fas fa-pills aum-icon-cod" ></i>');
        $('#inputCodBar').attr("placeholder", "Ingrese el uso terapéutico");
        $('#inputCodBar').attr("onblur", "this.placeholder = 'Ingrese el uso terapéutico'");
        $('#inputCodBar').attr("onfocus", "this.placeholder = ''");
      }
    }

    const SedeConnectionJs = '<?php echo $RutaUrl;?>';

    var modal = document.getElementById('exampleModalCenter');
    var btn = document.getElementById("btn_selector");
    var span = document.getElementsByClassName("close")[0];
    var opc_selec = 1;
    updateModalBox(opc_selec);

    btn.onclick = function() {
      $('#exampleModalCenter').modal('show');
    }
    span.onclick = function() {
      $('#exampleModalCenter').modal('hide');
    }
    window.onclick = function(event) {
      if (event.target == modal) {
        $('#exampleModalCenter').modal('hide');
      }
    }
    $('#opc_1').click(function(e) {
      if (opc_selec != 1){
        opc_selec = 1;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });
    $('#opc_2').click(function(e) {
      if (opc_selec != 2){
        opc_selec = 2;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });
    $('#opc_3').click(function(e) {
      if (opc_selec != 3){
        opc_selec = 3;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });
    $('#opc_4').click(function(e) {
      if (opc_selec != 4){
        opc_selec = 4;
        $('#exampleModalCenter').modal('hide');
        updateModalBox(opc_selec);
      }
      else {
        $('#exampleModalCenter').modal('hide');
      }
    });

    $(document).ready(function(){
        $('[data-toggle="tooltip"]').tooltip();   
    });
    
	</script>
     
  <?php
    if($CodJson!=""){
  ?>
    <script type="text/javascript">
      const ArrJsCB = eval(<?php echo $CodJson ?>);
    </script> 
  <?php
    }
  ?>  
   <?php
    if($ArtJson!=""){
  ?>
    <script type="text/javascript">
      const ArrJs = eval(<?php echo $ArtJson ?>);
    </script> 
  <?php
    }
  ?>
@endsection

<?php
/**********************************************************************************/
  /*
    TITULO: RCCQ_Lista_Articulos_CodBarra
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function RCCQ_Lista_Articulos_CodBarra() {
    $sql = "
      SELECT CodigoBarra,
      InvArticulo.Id
      FROM InvCodigoBarra
      INNER JOIN InvArticulo ON InvArticulo.Id = InvCodigoBarra.InvArticuloId
    ";
    return $sql;
  }
  /**********************************************************************************/
  /*
    TITULO: RCCQ_Lista_Articulos_Descripcion
    FUNCION: Armar una lista de articulos con descripcion e id
    RETORNO: Lista de articulos con descripcion e id
    DESAROLLADO POR: SERGIO COVA
  */
  function RCCQ_Lista_Articulos_Descripcion() {
    $sql = "
      SELECT
      InvArticulo.Descripcion,
      InvArticulo.Id
      FROM InvArticulo
    ";
    return $sql;
  }
?>