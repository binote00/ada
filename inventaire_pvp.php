<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	$Pilote_pvp=$_SESSION['Pilote_pvp'];
	if($Pilote_pvp >0)
	{
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Reputation,Credits,MIA,Pays,Slot1,Slot2,Slot3,Slot4,Slot5,Slot6,Slot7,Slot8,Slot9,Slot10,Slot11 FROM Pilote_PVP WHERE ID='$Pilote_pvp'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$country=$data['Pays'];
				$Reput=$data['Reputation'];
				$Credits=40; //$data['Credits'];
				$MIA=$data['MIA'];
				$Slot1=$data['Slot1'];
				$Slot2=$data['Slot2'];
				$Slot3=$data['Slot3'];
				$Slot4=$data['Slot4'];
				$Slot5=$data['Slot5'];
				$Slot6=$data['Slot6'];
				$Slot7=$data['Slot7'];
				$Slot8=$data['Slot8'];
				$Slot9=$data['Slot9'];
				$Slot10=$data['Slot10'];
				$Slot11=$data['Slot11'];
			}
			mysqli_free_result($result);
		}
		if($Credits >0 and !$MIA)
		{		
			function PrintMatos($Slot)
			{
				global $Reput;
				global $country;
				$txt='';
				$con=dbconnecti(1);
				$result=mysqli_query($con,"SELECT DISTINCT ID,Nom,Cout,Reput_mini FROM Matos WHERE Slot='$Slot' AND Reput_mini <='$Reput' AND Cout <98 ORDER BY Cout,Nom ASC");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC)) 
					{
						$txt.="<option value='".$data['ID']."'>".$data['Nom']." (Gratuit!)</option>";
					}
					mysqli_free_result($result);
				}
				unset($data);
				return $txt;
			}
?>
<h1>Equipement de votre pilote <a href='aide_matos.php' target='_blank' title='Aide à propos du matériel'><img src='/images/help.png'></a></h1>
<form action="inventaire1_pvp.php" method="post">
	<table cellspacing="1" cellpadding="5">
	<tr><th class="TableauProfil">Visage</th><td></td><th class="TableauProfil">Tête</th></tr>
	<tr><td style='width: 200px'><img src="images/matos<?echo $Slot2;?>.gif" title="<?if($Slot2){echo GetData("Matos","ID",$Slot2,"Nom");}?>"></td><td rowspan="11" style='width: 200px'><img src="images/silhouette.gif"></td><td style='width: 200px'><img src="images/matos<?echo $Slot1;?>.gif" title="<?if($Slot1){echo GetData("Matos","ID",$Slot1,"Nom");}?>"></td></tr>
	<tr><td><select name="2" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(2);?></select></td><td><select name="1" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(1);?></select></td></tr>
	<tr><th>Torse</th><th>Dos</th></tr>
	<tr><td><img src="images/matos<?echo $Slot4;?>.gif" title="<?if($Slot4){echo GetData("Matos","ID",$Slot4,"Nom");}?>"></td><td><img src="images/matos<?echo $Slot3;?>.gif" title="<?if($Slot3){echo GetData("Matos","ID",$Slot3,"Nom");}?>"></td></tr>
	<tr><td><select name="4" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(4);?></select></td><td><select name="3" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(3);?></select></td></tr>
	<tr><th>Ceinture</th><th>Poignet</th></tr>
	<tr><td><img src="images/matos<?echo $Slot5;?>.gif" title="<?if($Slot5){echo GetData("Matos","ID",$Slot5,"Nom");}?>"></td><td><img src="images/matos<?echo $Slot6;?>.gif" title="<?if($Slot6){echo GetData("Matos","ID",$Slot6,"Nom");}?>"></td></tr>
	<tr><td><select name="5" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(5);?></select></td><td><select name="6" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(6);?></select></td></tr>
	<tr><th>Arme</th><th>Mains</th></tr>
	<tr><td><img src="images/matos<?echo $Slot8;?>.gif" title="<?if($Slot8){echo GetData("Matos","ID",$Slot8,"Nom");}?>"></td><td><img src="images/matos<?echo $Slot7;?>.gif" title="<?if($Slot7){echo GetData("Matos","ID",$Slot7,"Nom");}?>"></td></tr>
	<tr><td><select name="8" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(8);?></select></td><td><select name="7" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(7);?></select></td></tr>
	<tr><th>Poche</th><th>Besace</th><th>Pieds</th></tr>
	<tr><td><img src="images/matos<?echo $Slot10;?>.gif" title="<?if($Slot10){echo GetData("Matos","ID",$Slot10,"Nom");}?>"></td><td><img src="images/matos<?echo $Slot11;?>.gif" title="<?if($Slot11){echo GetData("Matos","ID",$Slot11,"Nom");}?>"></td><td><img src="images/matos<?echo $Slot9;?>.gif" title="<?if($Slot9){echo GetData("Matos","ID",$Slot9,"Nom");}?>"></td></tr>
	<tr><td><select name="10" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(10);?></select></td><td><select name="11" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(11);?></select></td><td><select name="9" class="form-control"><option value="0" selected>Ne pas changer</option><? echo PrintMatos(9);?></select></td></tr>
	</table>
	<input type='Submit' class="btn btn-default" value='VALIDER' onclick='this.disabled=true;this.form.submit();'>
</form>
<?
		}
		else
			echo "<h1>Inventaire</h1>Vous n'avez pas le temps pour ça!";
	}
	else
		echo 'Revenez plus tard!';
}
else
	header("Location: ./tsss.php");