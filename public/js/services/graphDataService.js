'use strict';


angular.module('bungomedia.controllers')
	.factory('GraphDataService', [ '$filter', 'METRICS_FIELDS', function($filter, METRICS_FIELDS) {
		var GraphDataService =  {
			getMetricsGraphData: function(data, filters) {
				var data = {
					data1: GraphDataService.chooseData(data, filters.graph.field1),
					data2: GraphDataService.chooseData(data, filters.graph.field2)
				};

				GraphDataService.fillMissingData(data);

				return {
					dataset: [{
			      data: _.map(data.data1, function(item) { return [item.date, item.value]; }),
			      label: METRICS_FIELDS[filters.graph.field1],
			      points: {
			        show: true,
			        radius: 6
			      },
			      splines: {
			        show: true,
			        tension: 0.45,
			        lineWidth: 5,
			        fill: 0
			      }
			    }, {
			      data: _.map(data.data2, function(item) { return [item.date, item.value]; }),
			      label: METRICS_FIELDS[filters.graph.field2],
			      points: {
			        show: true,
			        radius: 6
			      },
			      splines: {
			        show: true,
			        tension: 0.45,
			        lineWidth: 5,
			        fill: 0
			      }
			    }],
			    options: {
			      colors: ['#a2d200', '#cd97eb'],
			      series: {
			        shadowSize: 0
			      },
			      xaxis:{
			        font: {
			          color: '#ccc'
			        },
			        position: 'bottom',
			        mode: 'time',
			        timeformat: '%m/%d',
			        minTickSize: [1, 'day']
			      },
			      yaxis: {
			        font: {
			          color: '#ccc'
			        }
			      },
			      grid: {
			        hoverable: true,
			        clickable: true,
			        borderWidth: 0,
			        color: '#ccc'
			      },
			      tooltip: true,
			      tooltipOpts: {
			        content: '%s: %y.2',
			        defaultTheme: false,
			        shifts: {
			          x: 0,
			          y: 20
			        }
			      }
		    	}
		    };
			},
			chooseData: function(data, field) {
				return _.map(data, function(item) {
					return {
						date: moment(item.date).unix() * 1000,
						value: item[field]
					};
				});
			},
			fillMissingData: function(dataset) {
				var all_data = _.reduceRight(dataset, function(a, b) { return a.concat(b); }, []);
				var min = _.min(all_data, function(dt) { return dt.date; });
				var max = _.max(all_data, function(dt) { return dt.date; });
				_.each(dataset, function(data, index) {
					var time = min.date;
					while(time <= max.date) {
						var found = _.findWhere(data, {date: time});
						if(typeof found == 'undefined') {
							dataset[index].push({
								date: time,
								value: 0
							});
						}
						time += 24 * 3600 * 1000;
					};
					dataset[index] = _.sortBy(dataset[index], function(dt) { return dt.date; });
				});
			}
		};

		return GraphDataService;
	}]);