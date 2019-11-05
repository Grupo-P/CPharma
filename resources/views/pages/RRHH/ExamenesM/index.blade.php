@extends('layouts.model')

@section('title', 'ExamenesMedicos')

@section('scriptsHead')
  <style>
    th, td {text-align: center;}
  </style>
@endsection
@section('content')
 
  <h1 class="h5 text-info">
   <i class="fas fa-user-md"></i>&nbsp;Examenes Médicos
  </h1>
  <hr class="row align-items-start col-12">

  <table style="width:100%;">
    <tr>
      <td style="width:10%;" align="center">
        <a href="/entrevistas/create" role="button" class="btn btn-outline-info btn-sm" style="display: inline; text-align: left;">
          <i class="fa fa-plus"></i>&nbsp;Agregar
        </a>
      </td>

      <td style="width:90%;">
        <div class="input-group md-form form-sm form-1 pl-0">
          <div class="input-group-prepend">
            <span class="input-group-text purple lighten-3" id="basic-text1">
              <i class="fas fa-search text-white" aria-hidden="true"></i>
            </span>
          </div>
          <input class="form-control my-0 py-1" type="text" placeholder="Buscar..." aria-label="Search" id="myInput" onkeyup="FilterAllTable()">
        </div>
      </td>
    </tr>
  </table>

  <br/>

  <table class="table table-striped table-borderless col-12 sortable" id="myTable">
    <thead class="thead-dark">
      <tr>
        <th scope="col" class="CP-sticky">#</th>
        <th scope="col" class="CP-sticky">Fecha</th>
        <th scope="col" class="CP-sticky">Entrevistadores</th>
        <th scope="col" class="CP-sticky">Lugar</th>
        <th scope="col" class="CP-sticky">Estatus</th>
        <th scope="col" class="CP-sticky">Acciones</th>
      </tr>
    </thead> 
  </table>

  <script>
    $(document).ready(function() {
        $('[data-toggle="tooltip"]').tooltip();
    });
    $('#exampleModalCenter').modal('show');
  </script>
@endsection