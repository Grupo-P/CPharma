/*
  Nombre: FilterTable
  Funcion: Filtrado de tabla por todos los campos
 */
function FilterAllTables() {
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

  // Declare variables
  var input, filter, table, tr, td, i, j, visible;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable2");
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

function FilterAllTableWithoutFooter() {
  // Declare variables
  var input, filter, table, tr, td, i, j, visible;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");


  for (i = 1; i < tr.length - 1; i++) {
    visible = false;
    td = tr[i].getElementsByTagName("td");
    /*Filtrado Por Todos los Campos*/

    for (j = 0; j < td.length; j++) {
      if (td[j] && td[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
        visible = true;
      }
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

/*
  Nombre: FilterTable
  Funcion: Filtrado de tabla con doble encabezado por todos los campos
 */
function FilterAllTableDoubleHeader() {
  // Declare variables 
  var input, filter, table, tr, td, i, j, visible;
  input = document.getElementById("myInput");
  filter = input.value.toUpperCase();
  table = document.getElementById("myTable");
  tr = table.getElementsByTagName("tr");

  
  for (i = 2; i < tr.length; i++) {
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

    if (!tr[1]) {
      tr[1].style.display = "";
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

/*
  Nombre: FilterTable
  Funcion: Filtrado de tabla por todos los campos
 */
function FilterAllTableConflicto() {
  // Declare variables 
  var input, filter, table, tr, td, i, j, visible;
  input = document.getElementById("myFilter");
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
