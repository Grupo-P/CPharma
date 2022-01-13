<?php
	use compras\Categoria;
	use compras\Subcategoria;

	$categorias =  Categoria::where('estatus','ACTIVO')->get();
	$subcategorias =  Subcategoria::all();

	include(app_path().'\functions\config.php');
  include(app_path().'\functions\functions.php');
  include(app_path().'\functions\querys_mysql.php');
  include(app_path().'\functions\querys_sqlserver.php');

	$RutaUrl = FG_Mi_Ubicacion();
?>

@extends('layouts.model')

@section('title')
    Categorizacion
@endsection

@section('content')

	<!-- Modal Guardar -->
	@if (session('Saved'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Saved') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Categorizacion almacenada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Editar -->
	@if (session('Updated'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Updated') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Categorizacion modificada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<!-- Modal Eliminar -->
	@if (session('Deleted'))
		<div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
		  <div class="modal-dialog modal-dialog-centered" role="document">
		    <div class="modal-content">
		      <div class="modal-header">
		        <h5 class="modal-title text-info" id="exampleModalCenterTitle"><i class="fas fa-info text-info"></i>{{ session('Deleted') }}</h5>
		        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
		          <span aria-hidden="true">&times;</span>
		        </button>
		      </div>
		      <div class="modal-body">
		        <h4 class="h6">Categorizacion actualizada con exito</h4>
		      </div>
		      <div class="modal-footer">
		        <button type="button" class="btn btn-outline-success" data-dismiss="modal">Aceptar</button>
		      </div>
		    </div>
		  </div>
		</div>
	@endif

	<h1 class="h5 text-info">
		<i class="fas fa-tag"></i>
		Categorizacion
	</h1>

	<hr class="row align-items-start col-12">
	<table style="width:100%;" class="CP-stickyBar">
		<tr>
			<td style="width:7%;" align="center">
				<form action="/categorizacion?Tipo=0" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-dark btn-sm" value="0">Por Caregorizar</button>
				</form>
	    </td>
	    <td style="width:7%;" align="center">
				<form action="/categorizacion?Tipo=1" method="GET" style="display: inline;">
			    <button type="submit" name="Tipo" role="button" class="btn btn-outline-success btn-sm" value="1">Caregorizado</button>
				</form>
	    </td>
      <td style="width:85%;">
        <form action="/categorizacion" method="GET">
            <select required style="margin-left: 2.5%; width: 95%" class="form-control" name="clave_busqueda">
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == '') ? 'selected' : '' }} value="">Seleccione metodo de busqueda</option>
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == 'Codigo interno') ? 'selected' : '' }} value="Codigo interno">Codigo interno</option>
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == 'Codigo de barra') ? 'selected' : '' }} value="Codigo de barra">Codigo de barra</option>
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == 'Descripcion') ? 'selected' : '' }} value="Descripcion">Descripcion</option>
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == 'Marca') ? 'selected' : '' }} value="Marca">Marca</option>
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == 'Categoria') ? 'selected' : '' }} value="Categoria">Categoria</option>
              <option {{ (isset($_GET['clave_busqueda']) && $_GET['clave_busqueda'] == 'Subcategoria') ? 'selected' : '' }} value="Subcategoria">Subcategoria</option>
            </select>

            <div style="margin-left: 2.5%; width: 95%" class="input-group md-form form-sm form-1 mt-2 pl-0">
              <input required class="form-control my-0 py-1" name="valor_busqueda" type="text" placeholder="Buscar..." value="{{ isset($_GET['valor_busqueda']) ? $_GET['valor_busqueda'] : '' }}" aria-label="Search" autofocus="autofocus">

              <input type="hidden" name="Tipo" value="{{ isset($_GET['Tipo']) ? $_GET['Tipo'] : 0 }}">

              <div class="input-group-append">
                <button type="submit" class="btn btn-outline-primary"><i class="fa fa-search"></i> Buscar</button>
              </div>
            </div>
        </form>
      </td>
    </tr>
  </table>
  <br/>
	<br/>
	{!! Form::open(['route' => 'categorizacion.store', 'method' => 'POST']) !!}
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Codigo Interno</th>
		      	<th scope="col" class="CP-sticky">Codigo Barra </th>
		      	<th scope="col" class="CP-sticky">Descripcion</th>
		      	<th scope="col" class="CP-sticky">Marca</th>
		      	<?php
		      		if($tipo==1){
		      	?>
		      		<th scope="col" class="CP-sticky">Categoria Actual</th>
		      		<th scope="col" class="CP-sticky">Subcategoria Actual</th>
		      	<?php
		      		}
		      	?>
		      	<th scope="col" class="CP-sticky">Nueva Categoria</th>
		      	<th scope="col" class="CP-sticky">Nueva Subcategoria</th>
		      	<th scope="col" class="CP-sticky">Marcar</th>
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($categorizaciones as $categorizacion)
		    <tr>
		    	<input type="hidden" name="<?php echo"articulo".$categorizacion->id; ?>" id="<?php echo"articulo".$categorizacion->id; ?>" value="<?php echo $categorizacion->id; ?>">

		      <th>{{$categorizacion->id}}</th>
		      <td>{{$categorizacion->codigo_interno}}</td>
		      <td>{{$categorizacion->codigo_barra}}</td>
		      <td>{{$categorizacion->descripcion}}</td>
		      <td>{{$categorizacion->marca}}</td>

		      <?php
		      		if($tipo==1){
		      			$categoria_actual = Categoria::where('codigo',$categorizacion->codigo_categoria)->get();

		      			$subcategoria_actual = Subcategoria::where('codigo',$categorizacion->codigo_subcategoria)->get();
		      	?>
		      		<td>{{$categoria_actual[0]->nombre}}</td>
		      		<td>{{$subcategoria_actual[0]->nombre}}</td>
		      	<?php
		      		}
		      	?>

		      <td>
            <select name="<?php echo"categoria".$categorizacion->id; ?>" id="<?php echo"categoria".$categorizacion->id; ?>" class="form-control" onchange="eligioCategoria(<?php echo $categorizacion->id; ?>);">
                <?php
                	$cont = count($categorias);
                	for($i=0;$i<$cont;$i++){
                ?>
                	<option value="<?php echo $categorias[$i]->codigo; ?>">
                		<?php echo $categorias[$i]->nombre; ?></option>
                <?php
	                }
                ?>
            </select>
        	</td>

        	<td>
            <select name="<?php echo"subcategoria".$categorizacion->id; ?>" id="<?php echo"subcategoria".$categorizacion->id; ?>" class="form-control" disabled="disabled">
                <?php
                	$cont = count($subcategorias);
                	for($i=0;$i<$cont;$i++){
                ?>
                	<option value="<?php echo $subcategorias[$i]->codigo; ?>">
                		<?php echo $subcategorias[$i]->nombre; ?></option>
                <?php
	                }
                ?>
            </select>
        	</td>

		    	<td>
		    		<input type="checkbox" name="articulosCategorizar[]" id="<?php echo"guardar".$categorizacion->id; ?>" value="" onchange="marcaGuardar(<?php echo $categorizacion->id; ?>)" style="width:100%; height:calc(1em + 0.20rem + 2px); margin-top: 8px;">
        	</td>

		    </tr>
		@endforeach
		</tbody>
	</table>


    <div class="d-flex justify-content-center">
        {{ $categorizaciones->appends($_GET)->links() }}
    </div>

	<div class="text-center">
		{!! Form::submit('Guardar', ['class' => 'btn btn-outline-success btn-md']) !!}
	</div>
	{!! Form::close()!!}

	<script>
		const SedeConnectionJs = '<?php echo $RutaUrl;?>';

		function dominio(SedeConnectionJs){
        var dominio = '';
        switch(SedeConnectionJs) {
            case 'FTN':
                dominio = 'http://cpharmaftn.com/';
                return dominio;
            break;
            case 'FLL':
                dominio = 'http://cpharmafll.com/';
                return dominio;
            break;
            case 'FAU':
                dominio = 'http://cpharmafau.com/';
                return dominio;
            break;
            case 'GP':
                dominio = 'http://cpharmatest.com/';
                return dominio;
            break;
            case 'ARG':
                dominio = 'http://cpharmade.com/';
                return dominio;
            break;
            case 'DBs':
                dominio = 'http://cpharmade.com/';
                return dominio;
            break;
            case 'KDI':
                dominio = 'http://cpharmakdi.com/';
                return dominio;
            break;
            case 'FSM':
                $dominio = 'http://cpharmafsm.com/';
                return $dominio;
            break;
        }
    }

		var dominio = dominio(SedeConnectionJs);
    const URLConsultaCategoria = ''+dominio+'assets/functions/ConsultaSubcategorias.php';

		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();
		});
		$('#exampleModalCenter').modal('show')

		function eligioCategoria(id){

			let categoria = $('#categoria'+id).val();
			let subcategoria = $('#subcategoria'+id).val();

			if(categoria == 1){
				$('#subcategoria'+id+' option').remove();
				$('#subcategoria'+id).append('<option value="1.1" selected>SIN SUBCATEGORIA</option>');
				$('#subcategoria'+id).attr("disabled","disabled");
			}
			else{

				var parametro = {"categoria":categoria};

				$.ajax({
	        data: parametro,
	        url: URLConsultaCategoria,
	        type: "POST",
	        success: function(data) {
	          if(JSON.parse(data)!="SIN SUBCATEGORIA"){
	          	var respuesta = JSON.parse(data);
            	var limite = respuesta.length;

              $('#subcategoria'+id+' option').remove();
              $('#subcategoria'+id).append('<option value="1.1" selected>SIN SUBCATEGORIA</option>');

              var i = 0;
              while (i<limite){
                var codigo = respuesta[i]['codigo'];
                var nombre = respuesta[i]['nombre'];

                $('#subcategoria'+id).append('<option value="'+codigo+'">'+nombre+'</option>');
              	$('#subcategoria'+id).removeAttr("disabled");
              	i++;
              }
            }
            else{
              $('#subcategoria'+id+' option').remove();
							$('#subcategoria'+id).append('<option value="1.1" selected>SIN SUBCATEGORIA</option>');
							$('#subcategoria'+id).attr("disabled","disabled");
            }
        	}
      	});
			}
		}

		function marcaGuardar(id){
			let articulo = $('#articulo'+id).val();
			let categoria = $('#categoria'+id).val();
			let subcategoria = $('#subcategoria'+id).val();

			$('#guardar'+id).val(articulo+"/"+categoria+"/"+subcategoria);
			let seleccion = $('#guardar'+id).val();
		}

	</script>

@endsection
