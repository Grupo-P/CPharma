/*
  Nombre: FilterTable
  Funcion: Filtrado de tabla por todos los campos
 */
function FilterAllTable() {
  // Declare variables 
  var input, filter, table, tr, td, i, j, visible;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  
  for (i = 1; i < tr.length; i++) {
    visible = false;
    td = tr[i].getElementsByTagName("td");
    /*Filtrado Por Todos los Campos*/
    
    for (j = 0; j < td.length; j++) {
      if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
        visible = true;
      }
    }
    
     /*Filtrado Por Un Campo Especifico*/
     /*
    if (td[0] && td[0].innerHTML.toUpperCase().indexOf(filter) > -1) {
         visible = true;
    }
    */
    /*Mantener el encabezado de la tabla*/
    if (!tr[0]) {
      tr[0].style.display = "";
    }

    if (visible === true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}

function FilterFirsTable() {
  // Declare variables 
  var input, filter, table, tr, td, i, j, visible;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  
  for (i = 1; i < tr.length; i++) {
    visible = false;
    td = tr[i].getElementsByTagName("td");
    /*Filtrado Por Todos los Campos*/
    /*
    for (j = 0; j < td.length; j++) {
      if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
        visible = true;
      }
    }
    */
     /*Filtrado Por Un Campo Especifico*/
    if (td[0] && td[0].innerHTML.toUpperCase().indexOf(filter) > -1) {
         visible = true;
    }

    /*Mantener el encabezado de la tabla*/
    if (!tr[0]) {
      tr[0].style.display = "";
    }

    if (visible === true) {
      tr[i].style.display = "";
    } else {
      tr[i].style.display = "none";
    }
  }
}