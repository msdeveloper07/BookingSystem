$(document).ready(function(){
Circles.create({
    id:         'circles-1',
    percentage: 42,
    radius:     20,
    width:      5,
    text:       '$',
    colors:     ['#f6f6f6', '#dfd5e6'],
    duration:   400
});
     
Circles.create({
    id:         'circles-2',
    percentage: 42,
    radius:     20,
    width:      5,
    text:       '$',
    colors:     ['#f6f6f6', '#f0d2c5'],
    duration:   400
});
 
});


$(function () {
  var tax_data = [
       {"Year": "2018 Year", "sale": 50},/*, "percent": "2"*/
       {"Year": "2017 Year", "sale": 40},
       {"Year": "2016 Year", "sale": 30},
       {"Year": "2015 Year", "sale": 20},
       {"Year": "2014 Year", "sale": 0}
  ];
  Morris.Line({
    element: 'year-sale-chart',
    data: tax_data,
    gridTextColor: '#fff',
      gridLineColor: '#7bac46',
    xkey: 'Year',
    ykeys: ['sale'],
    labels: ['sale']
  });
 /*==============================================*/   
    
  var tax_data = [
       {"Year": "2018 Year", "sale": 0},/*, "percent": "2"*/
       {"Year": "2017 Year", "sale": 70},
       {"Year": "2016 Year", "sale": 5},
       {"Year": "2015 Year", "sale": 80},
       {"Year": "2014 Year", "sale": 0}
  ];
  Morris.Line({
    element: 'earing-today',
    data: tax_data,
    gridTextColor: '#fff',
    gridLineColor: '#9972b5',
    pointFillColors: ['#fff'],
      axes: false,
      grid: false,
    xkey: 'Year',
    ykeys: ['sale'],
    labels: ['sale']
  });
     /*==============================================*/   
    
  var tax_data = [
       {"Year": "2018 Year", "sale": 0},/*, "percent": "2"*/
       {"Year": "2017 Year", "sale": 70},
       {"Year": "2016 Year", "sale": 5},
       {"Year": "2015 Year", "sale": 80},
       {"Year": "2014 Year", "sale": 0}
  ];
  Morris.Line({
    element: 'earing-monthly',
    data: tax_data,
    gridTextColor: '#fff',
    gridLineColor: '#9972b5',
    pointFillColors: ['#fff'],
      axes: false,
      grid: false,
    xkey: 'Year',
    ykeys: ['sale'],
    labels: ['sale']
  });
     /*==============================================*/ 

    Morris.Bar({
  element: 'statement-chart',
  data: [
    { y: '2014', a: 35, b: 18 },
    { y: '2015', a: 15, b: 25},
    { y: '2016', a: 20, b: 32 },
    { y: '2016', a: 28, b: 15},
    { y: '2017', a: 10 , b: 18},
    { y: '2018', a: 21 , b: 23}
  ],
  gridTextColor: '#999696',
  gridLineColor: '#fff',
  gridTextSize: 13,
  gridTextFamily: 'ProximaNova-Regular',
  xkey: 'y',
  ykeys: ['a', 'b'],
  labels: ['VISITORS A', 'VISITORS B']
});
    
/*==========================================================*/
      Morris.Donut({
    element: 'donut-blue',
    data: [
      {label: 'TOTAL VISITORS', value: 25 },
      {label: 'TOTAL VISITORS', value: 40 },
      {label: 'TOTAL VISITORS', value: 25 },
      {label: 'TOTAL VISITORS', value: 10 }
    ],
      colors: ['#00b0d9', '#82e3ff', '#53cef4', '#2dc0e8', '#B0CCE1', '#095791', '#095085', '#083E67', '#052C48', '#042135'],
  gridTextColor: '#7b8da0',
  gridTextSize: 13,
  gridTextFamily: 'OpenSans',
//formatter: function (y) { return y + "%" }
  });
    
/*==========================================================*/
      Morris.Donut({
    element: 'donut-orange',
    data: [
      {label: 'VISITORS TODAY', value: 25 },
      {label: 'VISITORS TODAY', value: 10 },
      {label: 'VISITORS TODAY', value: 25 },
      {label: 'VISITORS TODAY', value: 40 }
    ],
      colors: ['#e16a36', '#cd440a', '#de581e', '#dd5d25', '#B0CCE1', '#095791', '#095085', '#083E67', '#052C48', '#042135'],
  gridTextColor: '#7b8da0',
  gridTextSize: 13,
  gridTextFamily: 'OpenSans',
//formatter: function (y) { return y + "%" }
  });
    
});



$(function()
	{
    $('#redactor').redactor();
});