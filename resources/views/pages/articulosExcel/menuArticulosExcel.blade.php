<?php
    include(app_path().'\functions\config.php');
    include(app_path().'\functions\functions.php');
    include(app_path().'\functions\querys_mysql.php');
    include(app_path().'\functions\querys_sqlserver.php');
?>

@extends('layouts.model')

@section('title')
  Reporte
@endsection

@section('scriptsHead')
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
    background-color: DodgerBlue !important;
    color: #ffffff;
  }
  </style>
@endsection

@section('content')
	<h1 class="h5 text-info">
		<i class="fas fa-file-excel"></i>
		Articulos Excel
	</h1>
	<hr class="row align-items-start col-12">


    <form id="myFormulario" autocomplete="off" action="">

        <table style="width:100%;">
        <tr>
            <td align="center" colspan="7">
                <h3 class="text-center text-danger" id="mensaje"></h3>
                <br>
            </td>
        </tr>

        <tr>
            <td align="center">
                Condicion atributo:
            </td>
            <td align="right" style="width:20%;">
                <select id="condicionAtributo" name="condicionAtributo" class="form-control" required="required">
                    <option value="">Seleccione...</option>
                    <option value="PWEB">Pagina Web</option>
                    <option value="TODOS">Todos</option>
                </select>
            </td>

            <td align="center">
                Condicion excel:
            </td>
            <td align="right" style="width:20%;">
                <select id="condicionExcel" name="condicionExcel" class="form-control" required="required">
                    <option value="">Seleccione...</option>
                    <option value="WEB">Excel Web</option>
                    <option value="APP">Excel App</option>
                </select>
            </td>

            <td align="center">
                Condicion articulo:
            </td>
            <td align="right" style="width:20%;">
                <select id="condicionArticulo"  name="condicionArticulo" class="form-control" required="required">
                    <option value="">Seleccione...</option>
                    <option value="TODOS">Todos</option>
                    <option value="DOLARIZADO">Dolarizado</option>
                    <option value="NODOLARIZADO">No dolarizado</option>
                </select>
            </td>

        </tr>
        <tr><td><br></td></tr>
        <tr>

            <td align="center">
                Condicion existencia:
            </td>
            <td align="right" style="width:20%;">
                <input type="number" id="condicionExistencia" min="0" step="1" name="condicionExistencia" class="form-control" required="required" placeholder="15"/>
            </td>

            <td align="center">
                Condicion utilidad(%):
            </td>
            <td align="right" style="width:20%;">
                <input type="number" id="condicionUtilidad" min="0" step="1" name="condicionUtilidad" class="form-control" required="required" placeholder="30%"/>
            </td>

            <td align="right">
                <span class="btn btn-outline-success" onclick="submitForm();">Generar</span>
            </td>
        </tr>
        </table>
    </form>

    <script>
        function submitForm(){
            var condicionExcel =  $("#condicionExcel").val();
            var condicionArticulo =  $("#condicionArticulo").val();
            var condicionExistencia =  $("#condicionExistencia").val();
            var condicionUtilidad =  $("#condicionUtilidad").val();
            var condicionAtributo =  $("#condicionAtributo").val();

            var mensaje = "Complete los campos: ";
            var error = 0;
            if(condicionExcel==""){ mensaje = mensaje+" Condicion excel,"; error = 1; }
            if(condicionArticulo==""){ mensaje = mensaje+" Condicion articulo,"; error = 1; }
            if(condicionExistencia==""){ mensaje = mensaje+" Condicion existencia."; error = 1; }
            //if(condicionUtilidad==""){ mensaje = mensaje+" Condicion utilidad."; error = 1; }
            if(condicionAtributo==""){ mensaje = mensaje+" Condicion atributo."; error = 1; }

            if(error==1){
                $("#mensaje").html(mensaje);
            }else{

                var urlExcel = "";
                if(condicionExcel=="WEB"){
                    urlExcel = "/ArticulosExcel";
                }else if(condicionExcel=="APP"){
                    urlExcel = "/ArticulosAPP";
                }

                $("#myFormulario").attr("action",urlExcel);
                $("#myFormulario").submit();
            }
        }
    </script>
@endsection
