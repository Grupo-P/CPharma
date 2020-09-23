<?php	
	use compras\Categoria;
	use compras\Subcategoria;

	$categorias =  Categoria::all();
	$subcategorias =  Subcategoria::all();
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
      <td style="width:100%;">
        	<div class="input-group md-form form-sm form-1 pl-0">
			  <div class="input-group-prepend">
			    <span class="input-group-text purple lighten-3" id="basic-text1"><i class="fas fa-search text-white"
			        aria-hidden="true"></i></span>
			  </div>
			  <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()" autofocus="autofocus">
				</div>
      </td>
    </tr>
  </table>
  <br/>
	<br/>
	<table class="table table-striped table-borderless col-12 sortable" id="myTable">
	  	<thead class="thead-dark">
		    <tr>
		      	<th scope="col" class="CP-sticky">#</th>
		      	<th scope="col" class="CP-sticky">Codigo Interno</th>
		      	<th scope="col" class="CP-sticky">Codigo Barra </th>
		      	<th scope="col" class="CP-sticky">Descripcion</th>
		      	<th scope="col" class="CP-sticky">Marca</th>	
		      	<th scope="col" class="CP-sticky">Categoria</th>
		      	<th scope="col" class="CP-sticky">Subcategoria</th>	
		      	<th scope="col" class="CP-sticky">Marcar</th>      			      			      	
		    </tr>
	  	</thead>
	  	<tbody>
		@foreach($categorizaciones as $categorizacion)
		    <tr>
		      <th>{{$categorizacion->id}}</th>
		      <td>{{$categorizacion->codigo_interno}}</td>
		      <td>{{$categorizacion->codigo_barra}}</td>
		      <td>{{$categorizacion->descripcion}}</td>
		      <td>{{$categorizacion->marca}}</td>
		      
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
            <select name="<?php echo"subcategoria".$categorizacion->id; ?>" id="<?php echo"subcategoria".$categorizacion->id; ?>" class="form-control" disabled>            		
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

	<script>
		$(document).ready(function(){
		    $('[data-toggle="tooltip"]').tooltip();   
		});
		$('#exampleModalCenter').modal('show')

		function eligioCategoria(id){			
			let categoria = $('#categoria'+id).val();		
			console.log("Categoria: "+categoria);
		}

		function marcaGuardar(id){			
			let categoria = $('#categoria'+id).val();		
			console.log("CategoriaGuardar: "+categoria);

			$('#guardar'+id).val($('#categoria'+id).val());
			let seleccion = $('#guardar'+id).val();
			console.log("Hola pues:"+seleccion);
		}

	</script>

@endsection