@extends('pages.empresa.layout')

@section('title')
    Empresa
@endsection

@section('sub-content')
	<h1 class="h5 text-info">
		<i class="fas fa-industry"></i>
        Empresa > Agregar
	</h1>
	<hr class="row align-items-start col-8">
	<br>

	<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="well well-sm">
                <form class="form-group" method="POST" action="/empresa">
                	@csrf

                    <fieldset>
                        <div class="form-group">                            
                            <div class="col-md-8">
                                <input id="nombre" name="nombre" type="text" placeholder="Nombre" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">                            
                            <div class="col-md-8">
                                <input id="rif" name="rif" type="text" placeholder="RIF" class="form-control">
                            </div>
                        </div>
                
                        <div class="form-group">                            
                            <div class="col-md-8">
                                <input id="telefono" name="telefono" type="text" placeholder="Telefono" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">                            
                            <div class="col-md-8">
                                <textarea class="form-control" id="direccion" name="direccion" placeholder="Direccion" rows="5"></textarea>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-12 text-left">
                                <button type="submit" class="btn btn-success btn-md">Guardar</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection