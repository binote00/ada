<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$country=$_SESSION['country'];
	$Unite=Insec($_POST['unit']);
	if(!$MIA and $_SESSION['Distance'] ==0 and $Unite >0)
	{		
		$con=dbconnecti();
		$resultu=mysqli_query($con,"SELECT Nom,Pays,Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unite'");
		mysqli_close($con);
		if($resultu)
		{
			while($datau=mysqli_fetch_array($resultu,MYSQLI_ASSOC))
			{
				$Nom_u=$datau['Nom'];
				$Pays_u=$datau['Pays'];
				$Commandant=$datau['Commandant'];
				$Officier_Adjoint=$datau['Officier_Adjoint'];
				$Officier_Technique=$datau['Officier_Technique'];
			}
			mysqli_free_result($resultu);
			unset($datau);
		}
		if($Avancement >24999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
		{
			$con=dbconnecti(2);
			$resultg=mysqli_query($con,"SELECT Gain,Mode,DATE_FORMAT(Date,'%d-%m-%Y:%Hh%i') as Jour FROM gains_unit WHERE UnitID='$Unite' ORDER BY ID DESC");
			mysqli_close($con);
			if($resultg)
			{
				while($data=mysqli_fetch_array($resultg,MYSQLI_ASSOC))
				{
					if($data['Mode'] ==1)
						$Mode_txt="Mission";
					elseif($data['Mode'] ==2)
						$Mode_txt="Echec de mission";
					elseif($data['Mode'] ==3)
						$Mode_txt="Avion crashé au décollage";
					elseif($data['Mode'] ==4)
						$Mode_txt="Avion abattu";
					elseif($data['Mode'] ==5)
						$Mode_txt="Avion abattu par la DCA";
					elseif($data['Mode'] ==6)
						$Mode_txt="Mission EM";
					elseif($data['Mode'] ==7)
						$Mode_txt="Mission EM réussie";
					elseif($data['Mode'] ==8 or $data['Mode'] ==13)
						$Mode_txt="Demande de mission effectuée";
					elseif($data['Mode'] ==9)
						$Mode_txt="Escorte";
					elseif($data['Mode'] ==10)
						$Mode_txt="Pilote MIA";
					elseif($data['Mode'] ==11 or $data['Mode'] ==14)
						$Mode_txt="Mission de reco";
					elseif($data['Mode'] ==12)
						$Mode_txt="Mission navale";
					elseif($data['Mode'] ==13)
						$Mode_txt="Demande de mission effectuée";
					elseif($data['Mode'] ==15)
						$Mode_txt="Mission de ravitaillement";
					elseif($data['Mode'] ==110)
						$Mode_txt="Bombardement stratégique IA";
					elseif($data['Mode'] ==112)
						$Mode_txt="Bombardement tactique IA";
					elseif($data['Mode'] ==113)
						$Mode_txt="Reco tactique IA";
					elseif($data['Mode'] ==114)
						$Mode_txt="Reco stratégique IA";
					elseif($data['Mode'] ==115)
						$Mode_txt="Ravitaillement IA";
					elseif($data['Mode'] ==116)
						$Mode_txt="Parachutage IA";
					elseif($data['Mode'] ==129)
						$Mode_txt="Patrouille ASM IA";
					$output.="<tr><td>".$data['Jour']."</td><td>".$Mode_txt."</td><td>".$data['Gain']."</td></tr>";
				}
				mysqli_free_result($resultg);
			}
			include_once('./menu_escadrille.php');
			echo "<h1>".$Nom_u."</h1><h2>Gain de réputation</h2><table class='table table-striped'><thead><tr><th>Date</th><th>Catégorie</th><th>Gain</th></tr></thead>".$output."</table>";
		}
		else
		{
			include_once('./menu_escadrille.php');
			PrintNoAccessPil($country,1,2,3);
		}
	}
	else
	{
		$titre="MIA";
		$mes="<h6>Peut-être la reverrez-vous un jour votre escadrille...</h6>";
		$img="<img src='images/unites".$country.".jpg'>";
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>