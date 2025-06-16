<!DOCTYPE html>
<html>
<head>
<title>{{ $order_no }}</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php /*?><link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script><?php */?>

<style>
.mt-10 {
	margin-top: 10px;
}

.mt-20 {
	margin-top: 20px;
}

.mt-30 {
	margin-top: 30px;
}

.mt-40 {
	margin-top: 40px;
}

.mt-100 {
	margin-top: 100px;
}

.txt-just {
	text-align: justify;
}

.text-right {
	text-align: right;
	padding-right: 10px;
}

.text-left {
	text-align: left;
	padding-left: 10px;
}

p {
	font-size: 12px;
}

.fl_left {
	float: left;
}

.fl_right {
	float: right;
}

.bold {
	font-weight: bold;
}

table,
tr {
	line-height: 30px;
	height: 30px;
}

th {
	font-weight: bold;
}

.pl-10 {
	padding-left: 10px;
}

.pl-20 {
	padding-left: 20px;
}

.pl-30 {
	padding-left: 30px;
}

.pl-40 {
	padding-left: 40px;
}

.pl-100 {
	padding-left: 100px;
}

@page {
	header: page-header;
	footer: page-footer;
}

@page {
	margin-top: 0.2in !important;
	margin-bottom: 0.1in !important;
}

hr {
	color: black;
	background: black;
}

.header-container {
	margin: 0;
	padding: 0;
	clear: both;
	width: 100%;
}

.hdr_left {
	float: left;
	text: left;
	display: inline-block;
	margin-left: 0;
}

.hdr_left_img {
	float: left;
	padding-top: 10px;
	max-width: 100%;
	height: auto;
}

.hdr_center {
	float: left;
	text: center;
	display: inline-block;
	padding-top: 30px;
}

.hdr_center_img {
	text: center;
	max-width: 100%;
	height: auto;
}

.hdr_right {
	float: right;
	text: right;
	display: inline-block;
	padding-top: 30px;
}

.hdr_right_img {
	float: right;
	max-width: 100%;
	height: auto;
}

.top_1 {
	margin: 0;
	padding: 0;
	clear: both;
}


.top_1_left {
	float: left;
	text: left;
	display: inline-block;
	margin: 0;
	padding: 10px 0px 0px 0px;
	line-height: 20px;
}

.top_1_left p,
.top_1_left p a {
	color: blue;
	font-size: 12px;
}


.top_1_center {
	float: left;
	text: center;
	display: inline-block;
	margin: 0;
	padding: 0;
	line-height: 25px;
}

.top_1_center h1 {
	font-size: 20px;
	color: white;
	background: blue;
	text-align: center;
	margin: 0;
	padding: 0;
	line-height: 25px;
}


.top_1_right {
	float: right;
	text: right;
	display: inline-block;
	margin: 0;
	padding: 10px 0px 0px 0px;
	line-height: 20px;
}

.top_1_right p {
	color: black;
	font-size: 12px;
	float: right;
	text-align: right;
}

.top_1_right p span {
	border: 1px solid black;
	width: 6px;
	height: 20px;
}

/* here */
.box-start {
	border-style: solid;
	border-width: 1px 1px 1px 1px;
	float: left;
	clear: none;
	width: 6px;
	height: 20px;
	margin: 0px auto;
	margin-left: -1xp;
}


.box-end {
	border-style: solid;
	border-width: 1px 1px 1px 1px;
	float: left;
	clear: none;
	width: 6px;
	height: 20px;
}

.line {
	border-style: solid;
	border-width: 1px 1px 1px 1px;
	position: absolute;
	left: 50%;
	left: 50%;
	width: 150px;
	height: 15px;
	line-height: 15px;
	font-size: 10px;
	padding-left:5px;
}

.line-9 {
	border-style: solid;
	border-width: 1px 1px 1px 1px;
	position: absolute;
	left: 50%;
	left: 50%;
	width: 200px;
	height: 15px;
	line-height: 15px;
	font-size: 10px;
	padding-left:5px;
}

.line-4 {
	border-style: solid;
	border-width: 1px 1px 0px 1px;
	position: absolute;
	width: 242px;
	height: 60px;
	/* width:225px;height:50px; */
}

.small-line {
	border-style: solid;
	border-width: 1px 1px 1px 1px;
	position: absolute;
	left: 50%;
	left: 50%;
	width: 70px;
	height: 15px;
	line-height: 15px;
	font-size: 10px;
	padding-left:5px;
}

.top_2 {
	margin: 0;
	padding: 0;
	clear: both;
}

.top_2_left h1 {
	font-size: 10px;
	font-weight: bold;
	color: blue;
	text-align: left;
	margin: 0;
	padding: 10px 0 0 0;
	line-height: 20px;
}


.footer-container {
	margin: 0;
	padding: 0;
	clear: both;
}

.p_footer,
p_footer a {
	color: blue;
}

.cnic-line{
	border-style: solid;
	border-width: 1px 1px 1px 1px;
	position: absolute;
	/* left: 40%;
	left: 40%; */
	width: 5px;
	height: 15px;
	line-height: 15px;
	font-size: 10px;
	padding-left:5px;
}

#text{   
	width: 160px;
	height: 15px;
	background-size: 15px;
	border: none;
	font-family: monospace;
	font-size: 13px;
	padding-left: 5px;
	letter-spacing: 12px;
}


.name-column-margin{
	margin-bottom:-0.1px;
	font-size: 8px;
}




.ft_12{
	font-size:12px;
}

.ft_14{
	font-size:14px;
}

.ft_16{
	font-size:16px;
}

.ft_18{
	font-size:18px;
}

.ft_20{
	font-size:20px;
}

.ft_22{
	font-size:22px;
}

.ft_24{
	font-size:24px;
}

.ft_26{
	font-size:26px;
}

.ft_28{
	font-size:28px;
}

.ft_30{
	font-size:30px;
}

.ft_32{
	font-size:32px;
}




.ft_12b{
	font-size:12px;
	font-weight:bold;
}

.ft_14b{
	font-size:14px;
	font-weight:bold;
}

.ft_16b{
	font-size:16px;
	font-weight:bold;
}

.ft_18b{
	font-size:18px;
	font-weight:bold;
}


.ft_20b{
	font-size:20px;
	font-weight:bold;
}

.ft_22b{
	font-size:22px;
	font-weight:bold;
}

.ft_24b{
	font-size:24px;
	font-weight:bold;
}

.ft_26b{
	font-size:26px;
	font-weight:bold;
}

.ft_28b{
	font-size:28px;
	font-weight:bold;
}

.ft_30b{
	font-size:30px;
	font-weight:bold;
}

.ft_32b{
	font-size:32px;
	font-weight:bold;
}
</style>
</head>
<body>
    <div class="top_1" style="margin-top:5px; clear:both; text-align:center;">
    
        <img src="{{ $rest_image }}" style="width:200px; height:100px;">
        
    </div>
    


	<div class="top_2" style="margin-top:5px; clear:both; height:7px; overflow:hidden;">
        <p>
            ******************************************************************************************************************************************************************************************************
        </p>

	</div>
    


	<div class="top_2" style="margin-top:5px; clear:both; text-align:center;">
        
        <div class="top_1_left" style="width:33%; text-align:left;">
            <div class="ft_20">Table: {{ get_table_name($table_id) }}</div>
            <div class="ft_20">User : {{ $user_name }}</div>
        </div>
        
        <div class="top_1_left" style="width:34%; text-align:center;">
            <h1>{{ $rest_name }}</h1>
        </div>
        
        <div class="top_1_left" style="width:33%; text-align:right;">
            <div class="ft_20">Order No.: {{ $order_no }}</div>
            <div class="ft_16">Dated: {{ $date }}</div>
        </div>
	</div> 


	<div class="top_2" style="margin-top:1px; clear:both; height:7px; overflow:hidden;">
        <p>
            ******************************************************************************************************************************************************************************************************
        </p>

	</div> 
    


	<div class="top_2" style="margin-top:5px; clear:both; text-align:center;">
        <div class="ft_22b">Order Receipt</div>
	</div>

	<?php
	foreach ($items as $item)
	{
		$quantity = $item["quantity"];
		?>
        <div class="top_2" style="margin-top:5px; clear:both; text-align:center;">
        
            <div class="top_1_left" style="width:15%; text-align:left;">
                <div class="ft_20"><?php echo $quantity; ?>x</div>
            </div>
        
            <div class="top_1_left" style="width:50%; text-align:left;">
                <div class="ft_20"><?php echo $item["details"]["name"]; ?></div> 
				<?php
				foreach($item['addons'] as $addon)
				{
					?>
					<div class="ft_16"> + <?php echo $addon["details"]["name"]; ?></div>
					<?php
				}
				?>               

				<?php
				if(!empty($item['notes']))
				{
					?>
					<div class="ft_12"> [<?php echo $item["notes"]; ?>]</div>
					<?php
				}
				?>
            </div>
        
            <div class="top_1_left" style="width:35%; text-align:right;">
                <div class="ft_20"><?php echo get_decimal($item["total_value"]); ?></div> 
				<?php
				foreach($item['addons'] as $addon)
				{
					$price = ($addon["details"]["price"]*$quantity);
					?>
					<div class="ft_16"><?php echo get_decimal($price); ?></div>
					<?php
				}
				?>               
            </div>
        </div>		
		<?php
	}
	?>


	<div class="top_2" style="margin-top:5px; clear:both; height:7px; overflow:hidden;">
        <p>
            ******************************************************************************************************************************************************************************************************
        </p>

	</div>  
    
    <div class="top_2" style="margin-top:2px; clear:both; text-align:center;">
        
        <div class="top_1_left" style="width:20%; text-align:left;">
            <div class="ft_20">&nbsp;</div>
        </div>
    
        <div class="top_1_left" style="width:50%; text-align:right;">
            <div class="ft_20">Order Value</div>
        </div>
    
        <div class="top_1_right" style="width:30%; text-align:right;">
            <div class="ft_20">{{ $total_value }}</div>
        </div>
    </div>   
    
    <div class="top_2" style="margin-top:2px; clear:both; text-align:center;">
        
        <div class="top_1_left" style="width:20%; text-align:left;">
            <div class="ft_20">&nbsp;</div>
        </div>
    
        <div class="top_1_left" style="width:50%; text-align:right;">
            <div class="ft_20">VAT Value</div>
        </div>
    
        <div class="top_1_right" style="width:30%; text-align:right;">
            <div class="ft_20">{{ $vat_value }}</div>
        </div>
    </div>   
    
    <div class="top_2" style="margin-top:2px; clear:both; text-align:center;">
        
        <div class="top_1_left" style="width:20%; text-align:left;">
            <div class="ft_20">&nbsp;</div>
        </div>
    
        <div class="top_1_left" style="width:50%; text-align:right;">
            <div class="ft_20">Service Charges</div>
        </div>
    
        <div class="top_1_right" style="width:30%; text-align:right;">
            <div class="ft_20">{{ $service_charges }}</div>
        </div>
    </div>   
    
    <div class="top_2" style="margin-top:2px; clear:both; text-align:center;">
        
        <div class="top_1_left" style="width:20%; text-align:left;">
            <div class="ft_20">&nbsp;</div>
        </div>
    
        <div class="top_1_left" style="width:50%; text-align:right;">
            <div class="ft_20">Total Value</div>
        </div>
    
        <div class="top_1_right" style="width:30%; text-align:right;">
            <div class="ft_20">{{ $final_value }}</div>
        </div>
    </div> 
</body>
</html>