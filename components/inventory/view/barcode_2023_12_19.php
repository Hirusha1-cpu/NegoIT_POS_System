<?php
    include_once  'template/header.php';
    $decimal = getDecimalPlaces(1);
?>
<style>
    #code-list{float:left;list-style:none;margin-top:-3px;padding:0;width:200px;position: absolute; box-shadow: 5px 5px 10px #888888;}
    #code-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
    #code-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-code{padding: 10px; border: #a8d4b1 1px solid;border-radius:4px;}
    #desc-list{float:left;list-style:none;margin-top:-3px;padding:0;width:200px;position: absolute; box-shadow: 5px 5px 10px #888888;}
    #desc-list li{padding: 10px; background: #F8F8F8; border-bottom: #bbb9b9 1px solid;}
    #desc-list li:hover{background:#ece3d2;cursor: pointer;}
    #search-desc{padding: 10px;border: #a8d4b1 1px solid;border-radius:4px;}
    .input{
        text-align:right; width: 90%; margin-top: 5px;
    }
</style>
<script src="plugin/jquery/js/jquery-1.8.0.js"></script>
<script>
	$(document).ready(function(){
		$("#search-code").keyup(function(){
			if(document.getElementById('search-code').value.length>1){
				$.ajax({
				type: "POST",
				url: "index.php?components=<?php print $components; ?>&action=code-list&item_type=all&item_filter",
				data:'keyword='+encodeURIComponent($(this).val()),
				beforeSend: function(){
					$("#search-code").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-code").show();
					$("#suggesstion-code").html(data);
					$("#search-code").css("background","#FFF");
                    setTimeout(function() {
							$("#suggesstion-code").hide();
					}, 4000);
				}
				});
			}
		});
		$("#search-desc").keyup(function(){
			if(document.getElementById('search-desc').value.length>1){
				$.ajax({
				type: "POST",
				url: "index.php?components=<?php print $components; ?>&action=desc-list&item_type=all&item_filter",
				data:'keyword='+encodeURIComponent($(this).val()),
				beforeSend: function(){
					$("#search-desc").css("background","#FFF url(images/LoaderIcon.gif) no-repeat 165px");
				},
				success: function(data){
					$("#suggesstion-desc").show();
					$("#suggesstion-desc").html(data);
					$("#search-desc").css("background","#FFF");
                    setTimeout(function() {
							$("#suggesstion-desc").hide();
					}, 4000);
				}
				});
			}
		});
    });

    function selectCode(val) {
		$("#search-code").val(val);
		$("#suggesstion-code").hide();
        getItemMoreData('code',val);
	}

	function selectDesc(val) {
		$("#search-desc").val(val);
		$("#suggesstion-desc").hide();
        getItemMoreData('description', val);
	}

    function getItemMoreData($case, $val){
        $val=encodeURIComponent($val);
        window.location = 'index.php?components=inventory&action=get_more_item_data&'+$case+'='+$val;
    }

    function isValidHexColor(code) {
        const hexRegex = /^#([0-9A-Fa-f]{6}|[0-9A-Fa-f]{3})$/;
        return hexRegex.test(code);
    }

    function generateBarcode(){
        code = document.getElementById('code').value;
        quantity = document.getElementById('numb_of_barcode').value;
        height = document.getElementById('height').value;
        width = document.getElementById('width').value;
        line_color = document.getElementById('line_color').value;
        margin_left = document.getElementById('margin_l').value;
        margin_right = document.getElementById('margin_r').value;
        margin_top = document.getElementById('margin_t').value;
        margin_bottom = document.getElementById('margin_b').value;
        font_size = document.getElementById('font_size').value;
        out=true;
        if((out) && (line_color != '')){
            if(!isValidHexColor(line_color)){
                out=false;
                alert('Line color must be a valid hex color code.');
            }else{
                line_color = encodeURIComponent(line_color);
            }
        }
        if((out) && (height <= 0) || (height == '')){
            out=false;
            alert('Barcode height cannot be less than one');
        }
        if((out) && (width <= 0) || (width == '')){
            out=false;
            alert('Barcode width cannot be less than one');
        }
        if((out) && (quantity <= 0) || (quantity == '')){
            out=false;
            alert('Number of barcode to be print cannot be less than one');
        }
        if((out) && (code == '')){
            out=false;
            alert('Item code cannot be null');
        }
        if((out) && (margin_left != '') && (isNaN(margin_left))){
            out=false;
            alert('Margin left cannot be a text');
        }
        if((out) && (margin_right != '') && (isNaN(margin_right))){
            out=false;
            alert('Margin right cannot be a text');
        }
        if((out) && (margin_top != '') && (isNaN(margin_top))){
            out=false;
            alert('Margin top cannot be a text');
        }
        if((out) && (margin_bottom != '') && (isNaN(margin_bottom))){
            out=false;
            alert('Margin bottom cannot be a text');
        }
        if((out) && (font_size != '') && (isNaN(font_size))){
            out=false;
            alert('Font size cannot be a text');
        }
        if ((out) && (!isNaN(font_size)) && (font_size % 1 !== 0)) {
            out=false;
            alert('Font size cannot be a decimal/float number');
        }
        const checkbox = document.getElementById('print_value').checked;
        print_val = 'false';
        if(checkbox){
            print_val = 'true';
        }
        if(out){
            url = 'index.php?components=inventory&action=barcode_print&code='+code+'&q='+quantity+'&w='+width+'&h='+height+'&color='+line_color+'&print_val='+print_val+'&ml='+margin_left+'&mr='+margin_right+'&mt='+margin_top+'&mb='+margin_bottom+'&fs='+font_size;
            window.open(url, '_blank');
        }
    }
</script>

<!-- Notifications -->
<table align="center" cellspacing="0">
	<tr>
		<td>
			<?php
			if(isset($_REQUEST['message'])){
				if($_REQUEST['re']=='success') $color='green'; else $color='red';
					print '<script type="text/javascript">document.getElementById("notifications").innerHTML='."'".'<span style="color:'.$color.'; font-weight:bold;font-size:12pt;">'.$_REQUEST['message'].'</span>'."'".';</script>';
			}
			?>
		</td>
	</tr>
</table>
<!--// Notifications -->

<div id="loading" style="display:none"><img src="images/loading.gif" style="width:40px" /></div>
<input type="hidden" id="code" value="<?php if((isset($code)) && ($code != '')) print $code; ?>">

<table align="center" style="border-radius: 15px; font-family:Calibri;" border="0" bgcolor="#E5E5E5">
    <thead>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="color: black;" class="td-style"><center><strong>Search Items</strong></center></td>
        </tr>
    <thead>
    <tbody style="font-size:12pt">
        <!--  Item Code -->
        <tr>
            <td width="50px"></td>
            <td>Item Code</td>
            <td style="width: 190px">
                <div class="frmSearch">
                    <input type="text" id="search-code" value="<?php if(isset($code)) print $code; ?>" placeholder="Item Code" autocomplete="off" style="width: 90%;"/>
                    <div id="suggesstion-code"></div>
                </div>
            </td>
            <td width="50px">
                <div id="div_icode"></div>
            </td>
        </tr>
        <!-- Item Description -->
        <tr>
            <td width="50px"></td>
            <td>Item Description</td>
            <td style="width: 190px">
                <div class="frmSearch">
                    <input type="text" id="search-desc" value="<?php if(isset($description)) print $description; ?>" placeholder="Item Description" autocomplete="off" style="width: 90%;"/>
                    <div id="suggesstion-desc"></div>
                </div>
            </td>
            <td width="50px">
                <div id="div_idesc"></div>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
    </tbody>
</table>

<?php if(isset($id) && $id != ''){ ?>
    <p style="text-align:center; font-family:Calibri;">ITEM: <?php print $description; ?></p>
    <table align="center" border="0" style="margin-top:20px; font-family:Calibri;">
        <tr bgcolor="#CCCCCC">
            <td width="120px" align="center" style="">Code</td>
            <td width="120px" align="center" style="">Wholesale Price</td>
            <td width="120px" align="center" style="">Retail Price</td>
            <td width="120px" align="center" style="">Cost</td>
            <td width="120px" align="center" style="">Drawer</td>
        </tr>
        <tr>
            <td align="center" style="" class="shipmentTB4"><?php print $code; ?></td>
            <td align="right" style="" class="shipmentTB4"><?php print number_format($w_price,$decimal); ?></td>
            <td align="right" style="" class="shipmentTB4"><?php print number_format($r_price,$decimal); ?></td>
            <td align="right" style="" class="shipmentTB4"><?php print number_format($c_price,$decimal); ?></td>
            <td align="center" style="" class="shipmentTB4"><?php print $drawer; ?></td>
        </tr>
    </table>
    <table align="center" border="0" style="margin-top:20px; font-family:Calibri;">
        <tr  bgcolor="#CCCCCC">
            <td width="120px" align="center" style="">No of Barcode</td>
            <td width="60px" align="center" style="">Width</td>
            <td width="60px" align="center" style="">Height</td>
            <td width="60px" align="center" style="">Font Size</td>
            <td width="120px" align="center" style="">Line Color (Hex)</td>
            <td width="80px" align="center" style="">Margin L</td>
            <td width="80px" align="center" style="">Margin R</td>
            <td width="80px" align="center" style="">Margin T</td>
            <td width="80px" align="center" style="">Margin B</td>
            <td width="80px" align="center" style="">Print Value</td>
            <td width="120px" align="center" style="">Action</td>
        </tr>
        <tr>
            <td align="right"><input type="number" step="1" value="" id="numb_of_barcode" class="input"/></td>
            <td align="right"><input type="number" value="1" step="0.1" id="width" class="input" placeholder=""/></td>
            <td align="right"><input type="number" value="40" id="height" class="input" placeholder=""/></td>
            <td align="right"><input type="number" id="font_size" class="input" placeholder="10"/></td>
            <td align="right"><input type="text" id="line_color" class="input"/></td>
            <td align="right"><input type="number" id="margin_l" step="0.1" class="input" placeholder="Left"/></td>
            <td align="right"><input type="number" id="margin_r" step="0.1" class="input" placeholder="Right"/></td>
            <td align="right"><input type="number" id="margin_t" step="0.1" class="input" placeholder="Top"/></td>
            <td align="right"><input type="number" id="margin_b" step="0.1" class="input" placeholder="Bottom"/></td>
            <td align="right"><input type="checkbox" id="print_value" class="input" checked/></td>
            <td align="right"><button onclick="generateBarcode();">Print BARCODE</button></td>
        </tr>
    </table>
<?php } ?>
<?php
    include_once  'template/footer.php';
?>