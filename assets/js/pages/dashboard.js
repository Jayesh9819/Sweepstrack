//[Dashboard Javascript]

//Project:	CRMi - Responsive Admin Template
//Primary use:   Used only for the main dashboard (index.html)


$(function () {

  'use strict';
	
		 
	var options = {
          series: [70, 38],
          chart: {
          type: 'donut',
			 height: 140,
			  width: 140,
        },
		legend: {
      		show: false,
		},
		dataLabels: {
			enabled: false,
		  },
		plotOptions: {
			pie: {
			  customScale: 0.90,
			  donut: {
				size: '80%',
				  labels: {
					show: true,
					total: {
					  showAlways: true,
					  show: true,
					}
				  }
			  },
			  offsetY: 0,
			},
			stroke: {
			  colors: undefined
			}
		  },
		colors:['#7047ee', '#e8e1ff'],
        };

        var chart = new ApexCharts(document.querySelector("#chart41"), options);
        chart.render();
	
		
		 
	var options = {
          series: [32, 80],
          chart: {
          type: 'donut',
			 height: 140,
			  width: 140,
        },
		legend: {
      		show: false,
		},
		dataLabels: {
			enabled: false,
		  },
		plotOptions: {
			pie: {
			  customScale: 0.90,
			  donut: {
				size: '80%',
				  labels: {
					show: true,
					total: {
					  showAlways: true,
					  show: true,
					}
				  }
			  },
			  offsetY: 0,
			},
			stroke: {
			  colors: undefined
			}
		  },
		colors:['#3596f7', '#cce5ff'],
        };

        var chart = new ApexCharts(document.querySelector("#chart42"), options);
        chart.render();
		
		 
	var options = {
          series: [102, 12],
          chart: {
          type: 'donut',
			 height: 140,
			  width: 140,
        },
		legend: {
      		show: false,
		},
		dataLabels: {
			enabled: false,
		  },
		plotOptions: {
			pie: {
			  customScale: 0.90,
			  donut: {
				size: '80%',
				  labels: {
					show: true,
					total: {
					  showAlways: true,
					  show: true,
					}
				  }
			  },
			  offsetY: 0,
			},
			stroke: {
			  colors: undefined
			}
		  },
		colors:['#05825f', '#ebf9f5'],
        };

        var chart = new ApexCharts(document.querySelector("#chart43"), options);
        chart.render();
	
		
		var options = {
          series: [{
          name: 'Visitors',
          data: [440, 642, 414, 671, 443, 822, 901, 352, 752, 320, 757, 260]
        }, {
          name: 'Sales',
          data: [323, 505, 335, 527, 227, 513, 717, 231, 622, 222, 612, 116]
        }],
          chart: {
          height: 234,
          type: 'area',
          toolbar: {
            show: false
          }
        },
        stroke: {
          width: [3, 3],
			curve: 'smooth',
        },
        legend: {
          show: false,
        },
		colors:['#ffa800', '#7047ee'],
        dataLabels: {
          enabled: false,
          enabledOnSeries: [1]
        },
		fill: {
		  colors:['#ffa800', '#7047ee'],
		  opacity: 0.05,
  			type: 'solid',
		},
		markers: {
			size: 7,
			colors: undefined,
			strokeColors: '#fff',
			strokeWidth: 2,
			strokeOpacity: 1,
			strokeDashArray: 0,
			fillOpacity: 1,
			discrete: [],
			shape: "square",
			radius: 0,
			offsetX: 0,
			offsetY: 0,
			onClick: undefined,
			onDblClick: undefined,
			showNullDataPoints: true,
			hover: {
			  size: undefined,
			  sizeOffset: 3
			}
		},
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        };

        var chart = new ApexCharts(document.querySelector("#chart44"), options);
        chart.render();
	
	
	var options = {
        series: [17, 22, 19],
        chart: {
          type: 'donut',
			width: '100%',
      		height: 312
        },
		colors:['#7047ee', '#3596f7', '#ffa800'],
		labels: ["On line", "in Store", "Marketing"],
		legend: {
		  show: true,
		  position: 'bottom',
      	  horizontalAlign: 'center', 
		},
		dataLabels: {
			enabled: false,
		  },
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 200
            },
          }
        }]
      };

      var chart = new ApexCharts(document.querySelector("#sales-chart"), options);
      chart.render();
	
	$('.activity-div').slimScroll({
		height: '250px'
	});
	
	
	
		var options = {
          series: [{
			  name: 'Earning',
			  data: [44, 55, 41, 67, 22, 43, 21, 33, 54]
			}],
          chart: {
		  foreColor:"#bac0c7",
          type: 'bar',
          height: 220,
          toolbar: {
            show: false
          },
          zoom: {
            enabled: true
          }
        },
        responsive: [{
          breakpoint: 480,
          options: {
            legend: {
              position: 'bottom',
              offsetX: -10,
              offsetY: 0
            }
          }
        }],		
		grid: {
			show: true,
			borderColor: '#f7f7f7',      
		},
		colors:['#7047ee'],
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: '30%',
            borderRadius: 3
          },
        },
        dataLabels: {
          enabled: false
        },
 
        xaxis: {
          type: 'datetime',
          categories: ['08/01/2021 GMT', '08/02/2021 GMT', '08/03/2021 GMT', '08/04/2021 GMT','08/05/2021 GMT', '08/06/2021 GMT', '08/07/2021 GMT', '08/08/2021 GMT', '08/09/2021 GMT'
          ],
        },
        legend: {
          show: false,
        },
        fill: {
          opacity: 1
        }
        };

        var chart = new ApexCharts(document.querySelector("#charts_widget_1_chart"), options);
        chart.render();
	
	
		var options = {
          series: [44, 55, 13, 43, 22],
          chart: {
          width: 370,
          type: 'pie',
        },
			
		legend: {
		  position: 'bottom'
		},
		colors:['#7047ee', '#3596f7', '#ffa800', '#ee3158', '#05825f'],
        labels: ['Trade show', 'Referral', 'Web', 'Ads', 'Emails'],
        responsive: [{
          breakpoint: 480,
          options: {
            chart: {
              width: 280
            },
          }
        }]
        };

        var chart = new ApexCharts(document.querySelector("#chartdiv46"), options);
        chart.render();
		
	
}); // End of use strict


	
		am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		var chart = am4core.create("chartdiv45", am4charts.SlicedChart);
		chart.data = [{
		  "name": "$",
		  "value1": 400
		}, {
		  "name": "$",
		  "value1": 550
		}, {
		  "name": "$",
		  "value1": 400
		}, {
		  "name": "$",
		  "value1": 350
		}, {
		  "name": "$",
		  "value1": 250
		}];


		var series2 = chart.series.push(new am4charts.PyramidSeries());
		series2.dataFields.value = "value1";
		series2.dataFields.category = "name";
		series2.labels.template.disabled  = false;

		}); // end am4core.ready()
	
