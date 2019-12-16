@extends('layouts.model')

@section('title', 'Examenes Médicos')

@section('content')
  <h1 class="h5 text-info">
    <i class="far fa-eye"></i>
    Detalles de los Examenes Médicos
  </h1>
  <hr class="row align-items-start col-12">

  <form action="/examenesm/" method="POST" style="display: inline;">  
    @csrf
    <button type="submit" name="Regresar" role="button" class="btn btn-outline-info btn-sm"data-placement="top">
      <i class="fa fa-reply">&nbsp;Regresar</i>
    </button>
  </form>

  <br/><br/>

  <table class="table table-borderless table-striped">
    <thead class="thead-dark">
      <tr>
        <th scope="row"></th>
        <th scope="row"></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th scope="row">Nombre de la Empresa</th>
        <td>{{$examenesm->empresa}}</td>
      </tr>

      <tr>
        <th scope="row">Representante de Empresa</th>
        <td>
          {{
            compras\RHI_Examen_Laboratorio::where('rh_examenes_id', $examenesm->id)
            ->value('representante')
          }}
        </td>
      </tr>

      <tr>
        <th scope="row">Estado</th>
        <td>{{$examenesm->estado}}</td>
      </tr>
     <tr>
        <th scope="row">Observaciones</th>
        <td>{{$examenesm->observaciones}}</td>
      </tr>

      <tr>
        <th scope="row">Estatus</th>
        <td>{{$examenesm->estatus}}</td>
      </tr>

      <tr>
        <th scope="row">Creado</th>
        <td>{{$examenesm->created_at}}</td>
      </tr>

      <tr>
        <th scope="row">Ultima Actualización</th>
        <td>{{$examenesm->updated_at}}</td>
      </tr>

      <tr>
        <th scope="row">Actualizado por</th>
        <td>{{$examenesm->user}}</td>
      </tr>
    </tbody>
  </table>
@endsection