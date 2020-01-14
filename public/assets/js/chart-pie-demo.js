// Set new default font family and font color to mimic Bootstrap's default styling
Chart.defaults.global.defaultFontFamily = 'Nunito', '-apple-system,system-ui,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif';
Chart.defaults.global.defaultFontColor = '#858796';

var CuentaIngreso = 0;
var CuentaEgreso = 0;
var CuentaActivo = 0;

var URL = 'http://cpharmade.com/assets/functions/functionRHDash.php';

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
    console.log(data);
    CuentaIngreso = respuesta['CuentaIngreso'];
    CuentaEgreso = respuesta['CuentaEgreso'];
    CuentaActivo = respuesta['CuentaActivo'];
  }
 });

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
