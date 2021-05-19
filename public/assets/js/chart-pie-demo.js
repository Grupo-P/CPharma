// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

var CuentaIngreso = 0;
var CuentaEgreso = 0;
var CuentaActivo = 0;

var URL = 'http://cpharmaftn.com/assets/functions/functionRHDash.php';

//Incio Armado del chart de FTN
  var paramFTN = {
    "sede":'FTN'
  };
  $.ajax({
    data: paramFTN,
    url: URL,
    type: "POST",
    success: function(data) {
      var respuesta = JSON.parse(data);
      CuentaIngreso = respuesta['CuentaIngreso'];
      CuentaEgreso = respuesta['CuentaEgreso'];
      CuentaActivo = respuesta['CuentaActivo'];
      fechaInicio = respuesta['fechaInicio'];
      fechaFin = respuesta['fechaFin'];
      $("#ActFTN").html(CuentaActivo);
      $("#IngFTN").html(CuentaIngreso);
      $("#EgrFTN").html(CuentaEgreso);
      $("#FInicioFTN").html(fechaInicio);
      $("#FFinFTN").html(fechaFin);
      // Pie Chart Example myPieChart
      var ctx = document.getElementById("ChartFTN");
      var ChartFTN = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ["Activos", "Ingresos", "Egresos"],
          datasets: [{
            data: [CuentaActivo,CuentaIngreso,CuentaEgreso],
            backgroundColor: ['#4e73df', '#1cc88a', '#dc3545'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#c62f3e'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 80,
        },
      });
    }
  });
//FIN Armado del chart de FTN

//Incio Armado del chart de FLL
  var paramFLL = {
    "sede":'FLL'
  };
  $.ajax({
    data: paramFLL,
    url: URL,
    type: "POST",
    success: function(data) {
      var respuesta = JSON.parse(data);
      CuentaIngreso = respuesta['CuentaIngreso'];
      CuentaEgreso = respuesta['CuentaEgreso'];
      CuentaActivo = respuesta['CuentaActivo'];
      fechaInicio = respuesta['fechaInicio'];
      fechaFin = respuesta['fechaFin'];
      $("#ActFLL").html(CuentaActivo);
      $("#IngFLL").html(CuentaIngreso);
      $("#EgrFLL").html(CuentaEgreso);
      $("#FInicioFLL").html(fechaInicio);
      $("#FFinFLL").html(fechaFin);
      // Pie Chart Example myPieChart
      var ctx = document.getElementById("ChartFLL");
      var ChartFLL = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ["Activos", "Ingresos", "Egresos"],
          datasets: [{
            data: [CuentaActivo,CuentaIngreso,CuentaEgreso],
            backgroundColor: ['#4e73df', '#1cc88a', '#dc3545'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#c62f3e'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 80,
        },
      });
    }
  });
//FIN Armado del chart de FLL

//Incio Armado del chart de FAU
  var paramFAU = {
    "sede":'FAU'
  };
  $.ajax({
    data: paramFAU,
    url: URL,
    type: "POST",
    success: function(data) {
      var respuesta = JSON.parse(data);
      CuentaIngreso = respuesta['CuentaIngreso'];
      CuentaEgreso = respuesta['CuentaEgreso'];
      CuentaActivo = respuesta['CuentaActivo'];
      fechaInicio = respuesta['fechaInicio'];
      fechaFin = respuesta['fechaFin'];
      $("#ActFAU").html(CuentaActivo);
      $("#IngFAU").html(CuentaIngreso);
      $("#EgrFAU").html(CuentaEgreso);
      $("#FInicioFAU").html(fechaInicio);
      $("#FFinFAU").html(fechaFin);
      // Pie Chart Example myPieChart
      var ctx = document.getElementById("ChartFAU");
      var ChartFAU = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ["Activos", "Ingresos", "Egresos"],
          datasets: [{
            data: [CuentaActivo,CuentaIngreso,CuentaEgreso],
            backgroundColor: ['#4e73df', '#1cc88a', '#dc3545'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#c62f3e'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 80,
        },
      });
    }
  });
//FIN Armado del chart de FAU

//Incio Armado del chart de MC
  var paramMC = {
    "sede":'MC'
  };
  $.ajax({
    data: paramMC,
    url: URL,
    type: "POST",
    success: function(data) {
      var respuesta = JSON.parse(data);
      CuentaIngreso = respuesta['CuentaIngreso'];
      CuentaEgreso = respuesta['CuentaEgreso'];
      CuentaActivo = respuesta['CuentaActivo'];
      fechaInicio = respuesta['fechaInicio'];
      fechaFin = respuesta['fechaFin'];
      $("#ActMC").html(CuentaActivo);
      $("#IngMC").html(CuentaIngreso);
      $("#EgrMC").html(CuentaEgreso);
      $("#FInicioMC").html(fechaInicio);
      $("#FFinMC").html(fechaFin);
      // Pie Chart Example myPieChart
      var ctx = document.getElementById("ChartMC");
      var ChartMC = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ["Activos", "Ingresos", "Egresos"],
          datasets: [{
            data: [CuentaActivo,CuentaIngreso,CuentaEgreso],
            backgroundColor: ['#4e73df', '#1cc88a', '#dc3545'],
            hoverBackgroundColor: ['#2e59d9', '#17a673', '#c62f3e'],
            hoverBorderColor: "rgba(234, 236, 244, 1)",
          }],
        },
        options: {
          maintainAspectRatio: false,
          tooltips: {
            backgroundColor: "rgb(255,255,255)",
            bodyFontColor: "#858796",
            borderColor: '#dddfeb',
            borderWidth: 1,
            xPadding: 15,
            yPadding: 15,
            displayColors: false,
            caretPadding: 10,
          },
          legend: {
            display: false
          },
          cutoutPercentage: 80,
        },
      });
    }
  });
//FIN Armado del chart de MC
