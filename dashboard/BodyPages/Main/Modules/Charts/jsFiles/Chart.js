importScripts(GoogleCharts.js)
google.charts.load('current', {'packages':['corechart']});
google.charts.setOnLoadCallback(drawChart);

function drawChart() {

// Set Data
const data = google.visualization.arrayToDataTable([
  ['Contry', 'Mhl'],
  ['Italy', 55],
  ['France', 49],
  ['Spain', 44],
  ['USA', 24],
  ['Argentina', 15]
]);

// Set Options
const options = {
  title: 'World Wide Wine Production'
};

// Draw
const chart = new google.visualization.BarChart(document.getElementById('myChart'));
chart.draw(data, options);

}
// const xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
// const yValues = [55, 49, 44, 24, 15];
// const barColors = ["red", "green","blue","orange","brown"];

// new Chart("myChart", {
//   type: "bar",
//   data: {
//     labels: xValues,
//     datasets: [{
//       backgroundColor: barColors,
//       data: yValues
//     }]
//   },
//   options: {
//     legend: {display: false},
//     title: {
//       display: true,
//       text: "World Wine Production 2018"
//     }
//   }
// });

window.addEventListener("load",drawChart());