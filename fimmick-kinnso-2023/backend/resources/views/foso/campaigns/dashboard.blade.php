@extends('foso.layouts.default')

@section('page_title', 'Campaign Dashborad')

@section('breadcrumb_train')
<li><i class='fa fa-angle-right'></i><a href='{{ route("foso.campaigns.html") }}'>Campaigns</a></li>
<li><i class='fa fa-angle-right'></i> Dashborad</li>
@endsection

@section('content')

<style>
	.highcharts-figure,
	.highcharts-data-table table {
		min-width: 360px;
		max-width: 100%;
		margin: 1em auto;
	}

	.highcharts-data-table table {
		font-family: Verdana, sans-serif;
		border-collapse: collapse;
		border: 1px solid #EBEBEB;
		margin: 10px auto;
		text-align: center;
		width: 90%;
		max-width: 90%;
	}

	.highcharts-data-table caption {
		padding: 1em 0;
		font-size: 1.2em;
		color: #555;
	}

	.highcharts-data-table th {
		font-weight: 600;
		padding: 0.5em;
	}

	.highcharts-data-table td,
	.highcharts-data-table th,
	.highcharts-data-table caption {
		padding: 0.5em;
	}

	.highcharts-data-table thead tr,
	.highcharts-data-table tr:nth-child(even) {
		background: #f8f8f8;
	}

	.highcharts-data-table tr:hover {
		background: #f1f7ff;
	}
</style>

<div class='card'>
	<div class='card-body '>

		<div class="row">
			<div class='col-lg-6 '>
				<label>Main Offer</label> <br>
				<select class='w-100' name="mainOffer" id="mainOffer">
					<option value="Please Select Offer">-- Please Select Offer --</option>
				</select>
			</div>
			<div class='col-lg-6'>
				<label>Offer for Comparison</label> <br>
				<select class='w-100' name="comparison Offer" id="comparison Offer">
					<option value="Please Select Offer">-- Please Select Offer --</option>
				</select>
			</div>
		</div>

		<div class='row'>
			<div class='col' style="min-height:100vh">
				<figure class="highcharts-figure">
					<div class='row'>
						<div class="col-lg-6">
							<div id='timeChartContainer1'></div>
						</div>
						<div class="col-lg-6">
							<div id='timeChartContainer2'></div>
						</div>
					</div>
					</br>
					<div id="container1"></div></br>
					<div id="container2"></div></br>
					<div id="container3"></div></br>
				</figure>
			</div>
		</div>

	</div>
</div>

<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/series-label.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>

<script>
	let data1;
	let data2;
	let title1;
	let title2;

	window.addEventListener('load', fetchOffer());
	async function fetchOffer() {

		// fetch offer
		const offer = await fetch('/foso/campaigns/dashboard/offer.json')
			.then(response => response.json())
			.then(json => json);

		const offerArray = offer.offerArray;

		// add drop menu
		const dropMenu = document.querySelectorAll('select');
		dropMenu.forEach(el => {

			for (key in offerArray) {
				let option = document.createElement('option');
				option.id = key;
				option.value = key;
				option.text = offerArray[key];
				el.appendChild(option);
			}

			el.addEventListener('change', (e) => {
				const offerId = e.target.value;
				if (e.target.id == 'mainOffer') {
					const dataId = 1;
					title1 = offerArray[offerId];
					fetchDataAndDraw(offerId, dataId)
				} else if (e.target.id == 'comparison Offer') {
					const dataId = 2;
					fetchDataAndDraw(offerId, dataId)
					title2 = offerArray[offerId];
				}
			})

		});

		dropMenu[0].value = 1;
		title1 = offerArray[1];
		fetchDataAndDraw(1, 1);
	}

	// fetch data by offer id
	async function fetchDataAndDraw(offerId, dataId) {
		if (dataId == 1) {
			data1 = await fetch(`/foso/campaigns/dashboard/${offerId}`)
				.then(response => response.json())
				.then(json => json);
			data1.record.reverse();
			drawChart();
		}
		if (dataId == 2) {
			data2 = await fetch(`/foso/campaigns/dashboard/${offerId}`)
				.then(response => response.json())
				.then(json => json);
			data2.record.reverse();
			drawChart();
		}

	}
	// draw chart
	const drawChart = () => {
		// const d = new Date(data1.record[0].record_date);

		let xAxisArray = [];
		for (let i = 0; i < 14; i++) {
			xAxisArray.push(`T + ${i}`)
		}
		Highcharts.chart('container1', {

			title: {
				text: 'Number of users'
			},

			subtitle: {
				// text: 'Source: thesolarfoundation.com'
			},

			yAxis: {
				title: {
					text: "Count"
				},
			},

			xAxis: {
				categories: xAxisArray
			},

			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					},
					// pointStart: 2010
				}
			},

			series: [{
				name: title1 != undefined ? title1 : 'title1',
				data: (data1 != undefined) ? data1.record.map(el => el.number_of_users) : []
			}, {
				name: title2 != undefined ? title2 : 'title2',
				data: (data2 != undefined) ? data2.record.map(el => el.number_of_users) : []
			}],

			responsive: {
				rules: [{
					condition: {
						// maxWidth: 500
					},
					chartOptions: {
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						}
					}
				}]
			}

		});

		Highcharts.chart('container2', {

			title: {
				text: 'Number of coupons issued'
			},

			subtitle: {
				// text: 'Source: thesolarfoundation.com'
			},

			yAxis: {
				title: {
					text: "Count"
				}
			},

			xAxis: {
				categories: ['T', ...xAxisArray]
			},

			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					},
					// pointStart: 2010
				}
			},

			series: [{
				name: title1 != undefined ? title1 : 'title1',
				data: (data1 != undefined) ? data1.record.map(el => el.number_of_coupons_issued) : []
			}, {
				name: title2 != undefined ? title2 : 'title2',
				data: (data2 != undefined) ? data2.record.map(el => el.number_of_coupons_issued) : []
			}],

			responsive: {
				rules: [{
					condition: {
						// maxWidth: 500
					},
					chartOptions: {
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						}
					}
				}]
			}

		});

		Highcharts.chart('container3', {

			title: {
				text: 'Number of coupons used'
			},

			subtitle: {
				// text: 'Source: thesolarfoundation.com'
			},

			yAxis: {
				title: {
					text: "Count"
				}
			},

			xAxis: {
				categories: ['T', ...xAxisArray]
			},

			legend: {
				layout: 'vertical',
				align: 'right',
				verticalAlign: 'middle'
			},

			plotOptions: {
				series: {
					label: {
						connectorAllowed: false
					},
					// pointStart: 2010
				}
			},

			series: [{
				name: title1 != undefined ? title1 : 'title1',
				data: (data1 != undefined) ? data1.record.map(el => el.number_of_coupons_used) : []
			}, {
				name: title2 != undefined ? title2 : 'title2',
				data: (data2 != undefined) ? data2.record.map(el => el.number_of_coupons_used) : []
			}],

			responsive: {
				rules: [{
					condition: {
						// maxWidth: 500
					},
					chartOptions: {
						legend: {
							layout: 'horizontal',
							align: 'center',
							verticalAlign: 'bottom'
						}
					}
				}]
			}

		});

		// let data1TimeArray = [];
		// if (data1 != undefined) {
		// 	for (x in data1.timeChart[0]) {
		// 		if (x.includes('time_slot')) {
		// 			let index = parseInt(x.split('_')[2]) - 1;
		// 			data1TimeArray.push([`${ (index < 10) ? "0" + index : index }:30`, data1.timeChart[0][x]]);
		// 		}

		// 	}
		// }
		// let data2TimeArray = [];
		// if (data2 != undefined) {
		// 	for (x in data2.timeChart[0]) {
		// 		if (x.includes('time_slot')) {
		// 			let index = parseInt(x.split('_')[2]) - 1;
		// 			data2TimeArray.push([`${ (index < 10) ? "0" + index : index }:30`, data2.timeChart[0][x]]);
		// 		}

		// 	}
		// }

		// Highcharts.chart('timeChartContainer1', {
		// 	chart: {
		// 		type: 'column'
		// 	},
		// 	title: {
		// 		text: 'Trigger hours'
		// 	},
		// 	subtitle: {
		// 		// text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
		// 	},
		// 	xAxis: {
		// 		type: 'category',
		// 		labels: {
		// 			rotation: -45,
		// 			style: {
		// 				fontSize: '13px',
		// 				fontFamily: 'Verdana, sans-serif'
		// 			}
		// 		}
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: 'Number of users'
		// 		}
		// 	},
		// 	legend: {
		// 		enabled: false
		// 	},
		// 	tooltip: {
		// 		// pointFormat: 'Population in 2017: <b>{point.y:.1f} millions</b>'
		// 	},
		// 	series: [{
		// 		name: 'Users',
		// 		data: data1TimeArray,
		// 		dataLabels: {
		// 			// enabled: true,
		// 			rotation: -90,
		// 			color: '#FFFFFF',
		// 			align: 'right',
		// 			format: '{point.y:.1f}', // one decimal
		// 			y: 10, // 10 pixels down from the top
		// 			style: {
		// 				fontSize: '13px',
		// 				fontFamily: 'Verdana, sans-serif'
		// 			}
		// 		}
		// 	}]
		// });
		// Highcharts.chart('timeChartContainer2', {
		// 	colors: [
		// 		'#000000'
		// 	],
		// 	chart: {
		// 		type: 'column'
		// 	},
		// 	title: {
		// 		text: 'Trigger hours'
		// 	},
		// 	subtitle: {
		// 		// text: 'Source: <a href="http://en.wikipedia.org/wiki/List_of_cities_proper_by_population">Wikipedia</a>'
		// 	},
		// 	xAxis: {
		// 		type: 'category',
		// 		labels: {
		// 			rotation: -45,
		// 			style: {
		// 				fontSize: '13px',
		// 				fontFamily: 'Verdana, sans-serif'
		// 			}
		// 		}
		// 	},
		// 	yAxis: {
		// 		min: 0,
		// 		title: {
		// 			text: 'Number of users'
		// 		}
		// 	},
		// 	legend: {
		// 		enabled: false
		// 	},
		// 	tooltip: {
		// 		// pointFormat: 'Population in 2017: <b>{point.y:.1f} millions</b>'
		// 	},
		// 	series: [{
		// 		name: 'Users',
		// 		data: data2TimeArray,
		// 		dataLabels: {
		// 			// enabled: true,
		// 			rotation: -90,
		// 			color: '#FFFFFF',
		// 			align: 'right',
		// 			format: '{point.y:.1f}', // one decimal
		// 			y: 10, // 10 pixels down from the top
		// 			style: {
		// 				fontSize: '13px',
		// 				fontFamily: 'Verdana, sans-serif'
		// 			}
		// 		}
		// 	}]
		// });
	}
</script>
@endsection
