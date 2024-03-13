const xValues = ["Italy", "France", "Spain", "USA", "Argentina"];
const yValues = [55, 49, 44, 24, 15];
const barColors = ["red", "green","blue","orange","brown"];

new Chart("myChart") {
  const cfg = {
    type: 'line',
    data: {
      datasets: [{
        data: [{x: 10, y: 20}, {x: 15, y: null}, {x: 20, y: 10}]
      }]
    }
  },
  options: {
    legend: {display: false},
    title: {
      display: true,
      text: "World Wine Production 2018"
    }
  }
});

// window.addEventListener("load",drawChart());