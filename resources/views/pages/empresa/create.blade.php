@extends('pages.empresa.layout')

@section('title')
    Empresa
@endsection

@section('sub-content')
	<h1 class="h5 text-info">
		<i class="fas fa-plus"></i>
        Agregar empresa
	</h1>
	<hr class="row align-items-start col-12">

	<div class="card border-success" style="max-width: 40rem;">
        <div class="card-body">
                <form class="form-group" method="POST" action="/empresa">
                	@csrf
                    <fieldset>
                        <div class="form-group">                            
                            <div class="col-md-10">
                                <label>Nombre:</label>
                                <input id="nombre" name="nombre" type="text" placeholder="Farmacia Tierra Negra C.A." class="form-control" autofocus>
                            </div>
                        </div>
                        <div class="form-group">                            
                            <div class="col-md-10">
                                <label>RIF:</label>
                                <input id="rif" name="rif" type="text" placeholder="J-400145717" class="form-control">
                            </div>
                        </div>
                
                        <div class="form-group">                            
                            <div class="col-md-10">
                                <label>Telefono:</label>
                                <input id="telefono" name="telefono" type="text" placeholder="0261-7988326" class="form-control">
                            </div>
                        </div>

                        <div class="form-group">                            
                            <div class="col-md-10">
                                <label>Direccion:</label>
                                <textarea class="form-control" id="direccion" name="direccion" placeholder="CALLE 72 ESQUINA AV 14A LOCAL NRO 13A-99 SECTOR TIERRA NEGRA MARACAIBO ZULIA ZONA POSTAL 4002" rows="2"></textarea>
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
@endsection