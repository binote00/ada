<?
require_once('./jfv_inc_sessions.php');
//$OfficierID=$_SESSION['Officier'];
if($OfficierID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$Type_s=Insec($_POST['Type_Stock']);
	$Cie_ori=Insec($_POST['Cie_ori']);
	$Cie_dest=Insec($_POST['Cie_dest']);
	$Credits=GetData("Officier","ID",$OfficierID,"Credits");
	if($Type_s >0 and $Cie_ori >0 and $Cie_dest >0 and $Credits >=2)
	{
		if($Type_s >1000)
		{
			$Type_s-=1000;
			$Stock="Stock_Essence_".$Type_s;
		}
		else
			$Stock="Stock_Munitions_".$Type_s;
		$Stock_ori=GetData("Regiment","ID",$Cie_ori,$Stock);
		if($Stock_ori >0)
		{
			$Stock_tr=floor($Stock_ori/2);
			UpdateData("Regiment",$Stock,-$Stock_tr,"ID",$Cie_ori);
			UpdateData("Regiment",$Stock,$Stock_tr,"ID",$Cie_dest);
			UpdateData("Officier","Credits",-2,"ID",$OfficierID);
			$mes="Le stock de ".$Stock_tr." a été transféré de la ".$Cie_ori."e Cie à la ".$Cie_dest."e Cie !";
		}
		else
			$mes="Le stock est vide!";
		$titre="<h1>Gestion des stocks</h1>";
		$img=Afficher_Image('images/logistics.jpg', "images/image.png", "");
		$menu="<a href='index.php?view=ground_menu' class='btn btn-default' title='Retour'>Retour au menu Ordres</a>";
		include_once('./default.php');
	}
	else
		echo "Tsss!";
}?>