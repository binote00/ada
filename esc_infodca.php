<?php
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Unite=Insec($_GET['Unite']);
if(isset($_SESSION['AccountID']) AND $Unite >0)
{
	$PlayerID=$_SESSION['PlayerID'];
	$OfficierEMID=$_SESSION['Officier_em'];
	if($PlayerID >0 xor $OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		if($PlayerID >0)
			$Front=GetData("Pilote","ID",$PlayerID,"Front");
		elseif($OfficierEMID >0)
			$Front=GetData("Officier_em","ID",$OfficierEMID,"Front");
		$con=dbconnecti();
		$Unite=mysqli_real_escape_string($con,$Unite);
		$Admin=mysqli_result(mysqli_query($con,"SELECT Admin FROM Joueur WHERE ID='".$_SESSION['AccountID']."'"),0);
		$result=mysqli_query($con,"SELECT Base,Commandant,Officier_Adjoint,Officier_Technique,Porte_avions FROM Unit WHERE ID='$Unite'");
		$result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Officier_Rens FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
		mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Base=$data['Base'];
				$Commandant=$data['Commandant'];
				$Officier_Adjoint=$data['Officier_Adjoint'];
				$Officier_Technique=$data['Officier_Technique'];
				$Porte_avions=$data['Porte_avions'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($result2)
		{
			while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
			{
				$Cdt_EM=$data2['Commandant'];
				$Adjoint_EM=$data2['Adjoint_EM'];
				$Officier_EM=$data2['Officier_EM'];
				$Officier_Rens=$data2['Officier_Rens'];
			}
			mysqli_free_result($result2);
		}
		if($Admin or ($PlayerID >0 and ($PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)) 
		or ($OfficierEMID >0 and ($OfficierEMID ==$Cdt_EM or $OfficierEMID ==$Adjoint_EM or $OfficierEMID ==$Officier_EM or $OfficierEMID ==$Officier_Rens)))
		{
			if($Porte_avions >0)
			{
				$con=dbconnecti();
				$result=mysqli_query($con,"SELECT a.ID,a.Nom,a.Calibre,a.Degats,a.Multi,a.Portee 
				FROM Armes as a,Cible as r WHERE r.ID='$Porte_avions' AND (a.ID=r.Arme_AA OR a.ID=r.Arme_AA2 OR a.ID=r.Arme_AA3)");
				mysqli_close($con);
				if($result)
				{
					while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
					{
						$dg_max=round($data['Degats']*$data['Multi']);
						$dca_pieces.="<tr><td><img src='images/aa".$data['ID'].".png'><br>".$data['Nom']."</td><td>".round($data['Calibre'])."mm</td>
						<td>".$data['Degats']."-".$dg_max."</td><td>".$data['Portee']."m</td></tr>";
					}
				}
				$dca_pieces="<h2>Porte-avions ".GetData("Cible","ID",$Porte_avions,"Nom")."</h2><p><img src='images/vehicules/vehicule".$Porte_avions.".gif'></p>
				<table class='table'><thead><tr><th>Nom</th><th>Calibre</th><th>Dégats Max</th><th>Plafond</th></tr></thead>".$dca_pieces."</table>";
			}
			else
			{
				$dca_pieces="<h2>Composition de la défense anti-aérienne de l'unité</h2><table class='table table-striped'>
					<thead><tr><th>Type</th><th>Nombre</th><th>Altitude</th><th>Expérience</th></tr></thead>";
				$dca_pieces_others="<h2>Défense anti-aérienne des autres unités occupant l'aérodrome</h2><table class='table table-striped'>
					<thead><tr><th>Type</th><th>Nombre</th><th>Altitude</th><th>Expérience</th></tr></thead>";
				$con=dbconnecti();
				//$dca_res=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr,DCA_Exp,Alt FROM Flak WHERE Unit='$Unite' AND Lieu='$Base'");
				$dca_res=mysqli_query($con,"SELECT DCA_ID,DCA_Nbr,DCA_Exp,Alt,Unit FROM Flak WHERE Lieu='$Base'");
				mysqli_close($con);
				if($dca_res)
				{
					while($data_flak=mysqli_fetch_array($dca_res,MYSQLI_ASSOC))
					{
						$DCA_ID=$data_flak['DCA_ID'];
						$DCA_Nbr=$data_flak['DCA_Nbr'];
						$DCA_Exp=floor($data_flak['DCA_Exp']);
						$DCA_Alt=$data_flak['Alt'];
						$DCA_Nom=GetData("Armes","ID",$DCA_ID,"Nom");
						if($data_flak['Unit'] == $Unite)
							$dca_pieces.="<tr><td><img src='images/aa".$DCA_ID.".png' title='".$DCA_Nom."'><td>".$DCA_Nbr."</td><td>".$DCA_Alt."m</td><td>".$DCA_Exp."</td></tr>";
						else
							$dca_pieces_others.="<tr><td><img src='images/aa".$DCA_ID.".png' title='".$DCA_Nom."'><td>".$DCA_Nbr."</td><td>".$DCA_Alt."m</td><td>".$DCA_Exp."</td></tr>";
					}
				}
				$dca_pieces.="</table>";
				$dca_pieces_others.="</table>";
			}
		}
		else
		{
			$dca_pieces="<table class='table'>
					<tr><td><img src='images/top_secret.gif'></td></tr>
					<tr><td>Ces données sont classifiées.</td></tr>
					<tr><td>Votre fonction ne vous permet pas d'accéder à ces informations.</td></tr>
				</table>";
		}
		$mes="<h1>".Afficher_Icone($Unite,$country)."</h1>".$dca_pieces.$dca_pieces_others;
		include_once('./default_blank.php');
	}
}
?>