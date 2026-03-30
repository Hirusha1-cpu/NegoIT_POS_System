<ul id="<?php print $_GET['action']; ?>">
<?php
	for($i=0;$i<sizeof($data_list);$i++){
		print '<li onClick="'.$fn.'('."'$data_list[$i]'".');">'.$data_list[$i].'</li>';
	}
?>
</ul>
