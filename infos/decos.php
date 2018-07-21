<?php
include_once '../jfv_include.inc.php';
include_once '../jfv_txt.inc.php';
include_once '../view/menu_infos.php';
echo "<h2>Les décorations</h2><div style='overflow:auto; width: 100%;'>";
for($country =9; $country >0; $country--)
{
	if($country !=5)
	{
		$med.="<fieldset><legend>".GetPays($country)."</legend><div class='row'>";
		for($i=1;$i<=10;$i++)
		{
			$med.="<div class='col-lg-2 col-md-3 col-sm-4 col-xs-12'><img title='".GetMedal_Name($country,$i)."' src='images/pmedal".$country.$i.".gif'></div>";
		}
		$med.='</div></fieldset>';
	}
}
echo $med.'</div>';