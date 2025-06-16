<?php 
$states = $states_array['today'];
?>

<div class="row state_option_div" id="state_div_today">

    <div class="col-xl-8 col-xxl-9 d-flex flex-column">
        <div class="block block-rounded flex-grow-1 d-flex flex-column">
            <div class="block-header block-header-default">
                <h3 class="block-title">Today Summary</h3>
            </div>
            <div class="block-content block-content-full align-items-center">
            
                <div class="row">
                    <div class="col-lg-12 col-md-12 col-12 col-sm-12">
            			<div id="todaySalesChart" style="width:100%"></div>
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
function todaySalesChart() 
{
	var options = 
	{
		series: [
			{
				name: 'Orders',
				type: 'column',
				data: [{{ $states['yesterday_orders'] }},{{ $states['today_orders'] }}]
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
	
		labels: ['Yesterday','Today'],	
	
	};
	
	var chart = new ApexCharts(document.querySelector("#todaySalesChart"), options);
	
	chart.render();
}
</script>