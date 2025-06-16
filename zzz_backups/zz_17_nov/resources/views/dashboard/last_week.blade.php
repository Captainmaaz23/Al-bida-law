<?php 
$states = $states_array['week'];
?>

<div class="row state_option_div hide" id="state_div_last_week">

    <div class="col-xl-8 col-xxl-9 d-flex flex-column">
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h3 class="block-title">Last Week Summary</h3>
            </div>
            <div class="block-content block-content-full align-items-center">
            
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            			<div id="last_weekSalesChart" style="width:100%"></div>
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
function last_weekSalesChart() 
{
	var options = 
	{
		series: [
			{
				name: 'Orders',
				type: 'column',
				data: [{{ $states['day0_orders'] }},{{ $states['day1_orders'] }},{{ $states['day2_orders'] }},{{ $states['day3_orders'] }},{{ $states['day4_orders'] }},{{ $states['day5_orders'] }},{{ $states['day6_orders'] }}]
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
	
		labels: ['{{ $states['day0_name'] }}','{{ $states['day1_name'] }}','{{ $states['day2_name'] }}','{{ $states['day3_name'] }}','{{ $states['day4_name'] }}','{{ $states['day5_name'] }}','{{ $states['day6_name'] }}'],	
	
	};
	
	var chart = new ApexCharts(document.querySelector("#last_weekSalesChart"), options);

	chart.render();
}
</script>