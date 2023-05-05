@extends('layouts.modelUser')

@section('title')
  Etiqueta
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
    .etq table{
        display: inline;
        margin:-2px;
    border-radius: 25px;
    background-color: white;
    }
    .etq thead{
        border-top: 5px solid #dc3545;
        border-right: 5px solid #dc3545;
        border-left: 5px solid #dc3545;
        border-radius: 25px;
    background-color: white;
    }
    .etq tbody{
        border-bottom: 5px solid #dc3545;
        border-right: 5px solid #dc3545;
        border-left: 5px solid #dc3545;
        border-radius: 25px;
    background-color: white;
    }
    .rowCenter{
        width: 25cm;
    }
    .rowIzqA{
        width: 7cm;
    }
    .rowDerA{
        width: 9cm;
    }
    .titulo{
        height: 1cm;
        font-size: 1.1em;
    }
    .descripcion{
        height: 3.5cm;
    }
    .rowDer{
        height: 2.5cm;
    }
    .rowIzq{
        height: 2.5cm;
    }
    .centrado{
        text-align: center;
        text-transform: uppercase;
    }
    .derecha{
        text-align: right;
        text-transform: uppercase;
    }
    .izquierda{
        text-align: left;
        text-transform: uppercase;
    }
    .aumento{
        font-size: 4.5em;
    }

    .aumentoPrecio{
        font-size: 8em;
    }
  .aumento1{
    font-size: 2.5em;
  }
  .preciopromo{
    color: #dc3545;
  }
  .divPromo{
    width: 25cm;
    border: 15px solid #dc3545;
    border-radius: 25px;
    background-color: #dc3545;
  }
  .MensajePromo{
    color: white;
    background-color: #dc3545;
    width: 100%;
    font-size: 4rem;
    text-align: center;
  }
  /* cuando vayamos a imprimir ... */
  @media print{
    /* indicamos el salto de pagina */
    .saltoDePagina{
      display:block;
      page-break-before:always;
    }

    .ocultar {
        display: none !important;
    }
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

        $conn = FG_Conectar_Smartpharma($SedeConnection);

        $concatedado = 'ARTICULOS ESTRELLA PROMOCION';
        FG_Guardar_Auditoria('GENERAR','ETIQUETA',$concatedado);

        $sql = "
            SELECT
            --Id Articulo
            InvArticulo.Id AS IdArticulo,
            --Categoria Articulo
            InvArticulo.InvCategoriaId,
            --Codigo Interno
            InvArticulo.CodigoArticulo AS CodigoInterno,
            --Codigo de Barra
            (SELECT CodigoBarra
            FROM InvCodigoBarra
            WHERE InvCodigoBarra.InvArticuloId = InvArticulo.Id
            AND InvCodigoBarra.EsPrincipal = 1) AS CodigoBarra,
            --Descripcion
            InvArticulo.Descripcion,
            --Impuesto (1 SI aplica impuesto, 0 NO aplica impuesto)
            (ISNULL(InvArticulo.FinConceptoImptoIdCompra,CAST(0 AS INT))) AS Impuesto,
            --Troquelado (0 NO es Troquelado, Id Articulo SI es Troquelado)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Troquelados'
            OR  InvAtributo.Descripcion = 'troquelados')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Troquelado,
            --UtilidadArticulo (Utilidad del articulo, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
            ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
            FROM VenCondicionVenta
            WHERE VenCondicionVenta.Id = (
            SELECT VenCondicionVenta_VenCondicionVentaArticulo.Id
            FROM VenCondicionVenta_VenCondicionVentaArticulo
            WHERE VenCondicionVenta_VenCondicionVentaArticulo.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadArticulo,
            --UtilidadCategoria (Utilidad de la categoria, Utilidad es 1.00 NO considerar la utilidad para el calculo de precio)
            ROUND(CAST(1-((ISNULL(ROUND(CAST((SELECT VenCondicionVenta.PorcentajeUtilidad
            FROM VenCondicionVenta
            WHERE VenCondicionVenta.id = (
            SELECT VenCondicionVenta_VenCondicionVentaCategoria.Id
            FROM VenCondicionVenta_VenCondicionVentaCategoria
            WHERE VenCondicionVenta_VenCondicionVentaCategoria.InvCategoriaId = InvArticulo.InvCategoriaId)) AS DECIMAL(38,4)),2,0),CAST(0 AS INT)))/100)AS DECIMAL(38,2)),2,0) AS UtilidadCategoria,
            --Precio Troquel Almacen 1
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioTroquelado
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE(InvLoteAlmacen.InvAlmacenId = '1')
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,4)),4,0)) AS TroquelAlmacen1,
            --Precio Compra Bruto Almacen 1
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioCompraBruto
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            AND (InvLoteAlmacen.InvAlmacenId = '1')
            ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen1,
            --Precio Troquel Almacen 2
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioTroquelado
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE(InvLoteAlmacen.InvAlmacenId = '2')
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            ORDER BY invlote.M_PrecioTroquelado DESC)AS DECIMAL(38,2)),2,0)) AS TroquelAlmacen2,
            --Precio Compra Bruto Almacen 2
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioCompraBruto
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            AND (InvLoteAlmacen.InvAlmacenId = '2')
            ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBrutoAlmacen2,
            --Precio Compra Bruto
            (ROUND(CAST((SELECT TOP 1
            InvLote.M_PrecioCompraBruto
            FROM InvLoteAlmacen
            INNER JOIN InvLote ON InvLote.Id = InvLoteAlmacen.InvLoteId
            WHERE (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)
            AND (InvLoteAlmacen.Existencia>0)
            ORDER BY invlote.M_PrecioCompraBruto DESC)AS DECIMAL(38,2)),2,0)) AS PrecioCompraBruto,
            --Existencia (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS Existencia,
            --ExistenciaAlmacen1 (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen1,
            --ExistenciaAlmacen2 (Segun el almacen del filtro)
            (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0))  AS ExistenciaAlmacen2,
            --Dolarizado (0 NO es dolarizado, Id Articulo SI es dolarizado)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Dolarizados'
            OR  InvAtributo.Descripcion = 'Giordany'
            OR  InvAtributo.Descripcion = 'giordany')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Dolarizado,
            --Tipo Producto (0 Miscelaneos, Id Articulo Medicinas)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Medicina')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS Tipo,
            --Articulo Estrella (0 NO es Articulo Estrella , Id SI es Articulo Estrella)
            (ISNULL((SELECT
            InvArticuloAtributo.InvArticuloId
            FROM InvArticuloAtributo
            WHERE InvArticuloAtributo.InvAtributoId =
            (SELECT InvAtributo.Id
            FROM InvAtributo
            WHERE
            InvAtributo.Descripcion = 'Articulo Estrella')
            AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT))) AS ArticuloEstrella,
            -- Ultima Venta (Fecha)
            (SELECT TOP 1
            CONVERT(DATE,VenFactura.FechaDocumento)
            FROM VenFactura
            INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
            WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY FechaDocumento DESC) AS UltimaVenta,
            --Tiempo sin Venta (En dias)
            (SELECT TOP 1
            DATEDIFF(DAY,CONVERT(DATE,VenFactura.FechaDocumento),GETDATE())
            FROM VenFactura
            INNER JOIN VenFacturaDetalle ON VenFacturaDetalle.VenFacturaId = VenFactura.Id
            WHERE VenFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY FechaDocumento DESC) AS TiempoSinVenta,
            --Ultimo Lote (Fecha)
            (SELECT TOP 1
            CONVERT(DATE,InvLote.FechaEntrada) AS UltimoLote
            FROM InvLote
            WHERE InvLote.InvArticuloId  = InvArticulo.Id
            ORDER BY UltimoLote DESC) AS UltimoLote,
            --Tiempo Tienda (En dias)
            (SELECT TOP 1
            DATEDIFF(DAY,CONVERT(DATE,InvLote.FechaEntrada),GETDATE())
            FROM InvLoteAlmacen
            INNER JOIN invlote on invlote.id = InvLoteAlmacen.InvLoteId
            WHERE InvLotealmacen.InvArticuloId = InvArticulo.Id
            ORDER BY InvLote.Auditoria_FechaCreacion DESC) AS TiempoTienda,
            --Ultimo Proveedor (Id Proveedor)
            (SELECT TOP 1
            ComProveedor.Id
            FROM ComFacturaDetalle
            INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
            INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
            INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
            WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorID,
            --Ultimo Proveedor (Nombre Proveedor)
            (SELECT TOP 1
            GenPersona.Nombre
            FROM ComFacturaDetalle
            INNER JOIN ComFactura ON ComFactura.Id = ComFacturaDetalle.ComFacturaId
            INNER JOIN ComProveedor ON ComProveedor.Id = ComFactura.ComProveedorId
            INNER JOIN GenPersona ON GenPersona.Id = ComProveedor.GenPersonaId
            WHERE ComFacturaDetalle.InvArticuloId = InvArticulo.Id
            ORDER BY ComFactura.FechaDocumento DESC) AS  UltimoProveedorNombre
            --Tabla principal
            FROM InvArticulo
            --Joins
            LEFT JOIN InvLoteAlmacen ON InvLoteAlmacen.InvArticuloId = InvArticulo.Id
            LEFT JOIN InvArticuloAtributo ON InvArticuloAtributo.InvArticuloId = InvArticulo.Id
            LEFT JOIN InvAtributo ON InvAtributo.Id = InvArticuloAtributo.InvAtributoId
            --Condicionales
            WHERE
                (
                (ISNULL((SELECT
                InvArticuloAtributo.InvArticuloId
                FROM InvArticuloAtributo
                WHERE InvArticuloAtributo.InvAtributoId =
                (SELECT InvAtributo.Id
                FROM InvAtributo
                WHERE
                InvAtributo.Descripcion = 'Articulo Estrella')
                AND InvArticuloAtributo.InvArticuloId = InvArticulo.Id),CAST(0 AS INT)))<>0
                )
            --Agrupamientos
            GROUP BY InvArticulo.Id, InvArticulo.CodigoArticulo, InvArticulo.Descripcion, InvArticulo.FinConceptoImptoIdCompra, InvArticulo.InvCategoriaId
            --Ordanamiento
            ORDER BY (ROUND(CAST((SELECT SUM (InvLoteAlmacen.Existencia) As Existencia
            FROM InvLoteAlmacen
            WHERE(InvLoteAlmacen.InvAlmacenId = 1 OR InvLoteAlmacen.InvAlmacenId = 2)
            AND (InvLoteAlmacen.InvArticuloId = InvArticulo.Id)) AS DECIMAL(38,0)),2,0)) ASC
        ";

        $result = sqlsrv_query($conn,$sql);
    ?>
    <!-- Modal Box -->
    <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header text-white bg-info">
            <h2 class="modal-title" id="exampleModalCenterTitle"><i class="fas fa-tag"></i> Frase de promocion</h2>
            <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="modo_opciones link-opc" id="opc_1">
              <h3><i class="fas fa-tag aum-icon-lup"></i> PRECIO ESPECIAL</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_2">
              <h3><i class="fas fa-tag aum-icon-lup"></i> ARTICULO NUEVO</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_3">
              <h3><i class="fas fa-tag aum-icon-lup"></i> PRECIO OFERTA</h3>
            </div>
            <div class="modo_opciones link-opc" id="opc_4">
              <h3><i class="fas fa-tag aum-icon-lup"></i> ARTICULO EN PROMOCION</h3>
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
            <h1 class="ocultar text-info">ETIQUETAS ARTICULOS ESTRELLA</h1>
          </th>
        </tr>
      </thead>
    </table>

    <table class="table table-borderless col-12" id="tablaError">
      <thead class="center">
        <th class="bg-white text-danger border border-white">
          <h3 id="MsnError"></h3></th>
      </thead>
    </table>

    <div id="DivEtiquetas"></div>
@endsection

@section('scriptsPie')
    <script>
        $(document).ready(function () {
            const SedeConnectionJs = '<?php echo $RutaUrl;?>'

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
                        dominio = 'http://cpharmafsm.com/';
                        return dominio;
                    break;
                    case 'FEC':
                        dominio = 'http://cpharmafec.com/';
                        return dominio;
                    break;
                    case 'KD73':
                        dominio = 'http://cpharmakd73.com/';
                        return dominio;
                    break;
                }
            }

            var dominio = dominio(SedeConnectionJs);

            const URLEtiquetaUnica = ''+dominio+'assets/functions/functionEtiquetaPromocion.php';

            const FrasePromo = 'PRECIO ESPECIAL';
            var contador = 0;

            @while($row = sqlsrv_fetch_array($result, SQLSRV_FETCH_ASSOC))
                @php
                    $connCPharma = FG_Conectar_CPharma();

                    $sqlCPharma = SQL_Etiqueta_Articulo($row['IdArticulo']);
                    $ResultCPharma = mysqli_query($connCPharma,$sqlCPharma);
                    $RowCPharma = mysqli_fetch_assoc($ResultCPharma);
                    $clasificacion = $RowCPharma['clasificacion'];
                    $clasificacion = ($clasificacion!="")?$clasificacion:"NO CLASIFICADO";

                    $tipo = ($row['Dolarizado'] == 0) ? 'NO DOLARIZADO' : 'DOLARIZADO';
                @endphp

                $.ajax({
                    data: {
                        "IdArticulo":'{{ $row['IdArticulo'] }}',
                        "tipo":'{{ $tipo }}',
                        "clasificacion": '{{ $clasificacion }}'
                    },
                    url: URLEtiquetaUnica,
                    type: "POST",
                    success: function(data) {

                        console.log(data);

                        if (data == '' || data == 'EL ARTICULO NO POSEE EXISTENCIA') {
                            return false;
                        }

                      var respuesta = data;
                      var letras = respuesta.substr(0,2);

                      if(letras=='EL'){
                        $("#MsnError").html(respuesta);
                      }
                      else{

                        var nuevoDiv = '<div class="divPromo">';
                        nuevoDiv += '<p class="MensajePromo"><strong>'+FrasePromo+'</strong></p>';
                        nuevoDiv += respuesta
                        nuevoDiv += '</div>';
                        var contenedor = $("#DivEtiquetas").html();
                        $("#DivEtiquetas").html(contenedor+nuevoDiv+'<br>');

                        var contenedor = $("#DivEtiquetas").html();
                        var nuevoDiv = '<div class="saltoDePagina"></div>';
                        $("#DivEtiquetas").html(contenedor+nuevoDiv);
                        contador=0;

                        // if(contador==1){
                        //   var contenedor = $("#DivEtiquetas").html();
                        //   var nuevoDiv = '<div class="saltoDePagina"></div>';
                        //   $("#DivEtiquetas").html(contenedor+nuevoDiv);
                        //   contador=0;
                        // }
                        // else{
                        //   contador++;
                        // }
                      }
                    }
                   });
            @endwhile
        });
    </script>
@endsection
