<?php 
$states = $states_array['month'];
?>

<div class="row state_option_div hide" id="state_div_last_month">

    <div class="col-xl-8 col-xxl-9 d-flex flex-column">
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h3 class="block-title">Last Month Summary</h3>
            </div>
            <div class="block-content block-content-full align-items-center">
            
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            			<div id="last_monthSalesChart" style="width:100%"></div>
                	</div>
                </div>
                
            </div>
        </div>
    </div>
    
    <div class="col-xl-4 col-xxl-3 d-flex flex-column">
        <div class="row items-push flex-grow-1">
        
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded block-themed d-flex flex-column h-100 mb-0">
                    <div class="block-header bg-primary">
                        <h3 class="block-title">Total Orders</h3>
                    </div>
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $states['total_orders'] }}</dt>
                        </dl>
                    </div>
                </div>
            </div>
            			
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded block-themed d-flex flex-column h-100 mb-0">
                    <div class="block-header bg-info">
                        <h3 class="block-title">Total Sales Value</h3>
                    </div>
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $states['total_sales'] }}</dt>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded block-themed d-flex flex-column h-100 mb-0">
                    <div class="block-header bg-warning">
                        <h3 class="block-title">Total Tax Value</h3>
                    </div>
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $states['total_tax'] }}</dt>
                        </dl>
                    </div>
                </div>
            </div>
            
            <div class="col-md-6 col-xl-12">
                <div class="block block-rounded block-themed d-flex flex-column h-100 mb-0">
                    <div class="block-header bg-success">
                        <h3 class="block-title">Total Earnings</h3>
                    </div>
                    <div class="block-content flex-grow-1 d-flex justify-content-between">
                        <dl class="mb-0">
                            <dt class="fs-3 fw-bold">{{ $states['total_earnings'] }}</dt>
                        </dl>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>


<script>	

function last_monthSalesChart() 
{
	var options = 
	{
		series: [
			{
				name: 'Orders',
				type: 'column',
				data: [{{ $states['day1_orders'] }},{{ $states['day2_orders'] }},{{ $states['day3_orders'] }},{{ $states['day4_orders'] }},{{ $states['day5_orders'] }},{{ $states['day6_orders'] }},{{ $states['day7_orders'] }},{{ $states['day8_orders'] }},{{ $states['day9_orders'] }},{{ $states['day10_orders'] }},{{ $states['day11_orders'] }},{{ $states['day12_orders'] }},{{ $states['day13_orders'] }},{{ $states['day14_orders'] }},{{ $states['day15_orders'] }},{{ $states['day16_orders'] }},{{ $states['day17_orders'] }},{{ $states['day18_orders'] }},{{ $states['day19_orders'] }},{{ $states['day20_orders'] }},{{ $states['day21_orders'] }},{{ $states['day22_orders'] }},{{ $states['day23_orders'] }},{{ $states['day24_orders'] }},{{ $states['day25_orders'] }},{{ $states['day26_orders'] }},{{ $states['day27_orders'] }},{{ $states['day28_orders'] }},{{ $states['day29_orders'] }},{{ $states['day30_orders'] }}]
			}
		],
		chart: {
			width: '100%',		
			type: 'line',
			toolbar : {
				show : false,
			}
		},
		colors: ['#cdb4cf','#9234eb'],
		stroke: {
			width: [0, 4]
		},
		
		dataLabels: {
			enabled: true,
			enabledOnSeries: [1],
			style: {
				colors: ['#968a96','#9234eb']
			}	
		},
	
		labels: ['{{ $states['day1_name'] }}','{{ $states['day2_name'] }}','{{ $states['day3_name'] }}','{{ $states['day4_name'] }}','{{ $states['day5_name'] }}','{{ $states['day6_name'] }}','{{ $states['day7_name'] }}','{{ $states['day8_name'] }}','{{ $states['day9_name'] }}','{{ $states['day10_name'] }}','{{ $states['day11_name'] }}','{{ $states['day12_name'] }}','{{ $states['day13_name'] }}','{{ $states['day14_name'] }}','{{ $states['day15_name'] }}','{{ $states['day16_name'] }}','{{ $states['day17_name'] }}','{{ $states['day18_name'] }}','{{ $states['day19_name'] }}','{{ $states['day20_name'] }}','{{ $states['day21_name'] }}','{{ $states['day22_name'] }}','{{ $states['day23_name'] }}','{{ $states['day24_name'] }}','{{ $states['day25_name'] }}','{{ $states['day26_name'] }}','{{ $states['day27_name'] }}','{{ $states['day28_name'] }}','{{ $states['day29_name'] }}','{{ $states['day30_name'] }}'],	
	
	};
	
	var chart = new ApexCharts(document.querySelector("#last_monthSalesChart"), options);

	chart.render();
}
</script>