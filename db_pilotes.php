<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['AccountID'];
if($PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_multi.inc.php');
	$Encodage=GetData("Joueur","ID",$PlayerID,"Encodage");
	$Admin=GetData("Joueur","ID",$PlayerID,"Admin");
	if($Encodage >0)
	{
		echo "<h1>Encodage</h1><p class='lead'>Si vous rencontrez un bug, ne continuez pas l'encodage, signalez le sur le forum!</p>";
		echo "<div class='row'><div class='col-md-6'>";
		echo "<h2>Déplacements entre lieux</h2>
		<form action='index.php?view=db_lien_add' method='post'>
			<input type='Submit' value='Ajouter un lien' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
		</form></div>";
		/*echo "<h2>Encodage des pilotes historiques</h2>
		<form action='index.php' method='get'><input type='hidden' name='view' value='db_as'>
		<table class='table'><thead><tr><th>Choix de la nation du pilote</th><th></th></tr></thead>
			<tr><td align='left'><select name='pays'>
			<option value='1'>Allemagne</option><option value='2'>Angleterre</option><option value='3'>Belgique</option><option value='4'>France</option><option value='6'>Italie</option><option value='9'>Japon</option><option value='8'>URSS</option><option value='7'>USA</option>
		</select></td><td><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></td></tr></table></div>";*/
        if($Admin >0)
        {
		echo "<div class='col-md-6'><h2>Ajout d'autres éléments</h2>
		<form action='index.php?view=db_lieu_add' method='post'>
			<input type='Submit' value='Ajouter un lieu' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
		</form>
		<form action='index.php?view=db_event_add' method='post'>
			<input type='Submit' value='Ajouter un évènement historique' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
		</form>
		<a href='index.php?view=admin_lieux' class='btn btn-warning'>Modifier un lieu</a>
		</div></div>";
			echo "<h2>Admin</h2><div class='row'><div class='col-md-6'>
			<form action='index.php?view=admin_cie_ia' method='post'>
				<input type='hidden' name='Tri' value='1'>
				<input type='Submit' value='Liste Cie IA par véhicule (admin)' class='btn btn-info' onclick='this.disabled=true;this.form.submit();'>
			</form>
			<form action='index.php?view=admin_cie_ia' method='post'>
				<input type='hidden' name='Tri' value='0'>
				<input type='Submit' value='Liste Cie IA par lieu (admin)' class='btn btn-info' onclick='this.disabled=true;this.form.submit();'>
			</form>
			<form action='index.php?view=db_event_h_menu' method='post'>
				<input type='Submit' value='Ajouter un évènement historique (admin)' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
			</form>
			<form action='index.php?view=admin_quotas' method='post'>
				<input type='Submit' value='Quotas (admin)' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
			</form>
			<form action='index.php?view=stats2' method='post'>
				<input type='Submit' value='Troupes actives (admin)' class='btn btn-warning' onclick='this.disabled=true;this.form.submit();'>
			</form>
			<form action='index.php?view=admin_live_menu' method='post'>
				<input type='Submit' value='Live (admin)' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
			</form>
			<form action='index.php?view=tools' method='post'>
				<input type='Submit' value='Tools (admin)' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
			</form>
			</div><div class='col-md-6'>
			";
			//Inactifs
			$con=dbconnecti();
			$reset1=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Commandant=NULL,p.Briefing='',p.Lieu_Mission1=0,
			p.Lieu_Mission2=0,p.Lieu_Mission3=0,p.Lieu_Mission4=0,p.Lieu_Mission5=0,p.Lieu_Mission6=0,p.Lieu_Mission7=0,p.Lieu_Mission8=0,p.Lieu_Mission9=0,p.Lieu_Mission10=0,p.Lieu_Mission12=0,
			p.Type_Mission1=0,p.Type_Mission2=0,p.Type_Mission3=0,p.Type_Mission4=0,p.Type_Mission5=0,p.Type_Mission6=0,p.Type_Mission7=0,p.Type_Mission8=0,p.Type_Mission9=0,p.Type_Mission10=0,p.Type_Mission12=0,
			o.Postuler=0 WHERE p.Commandant=o.ID AND p.Commandant >0 AND o.Credits_date <'".$Date_inactif_em."'");
			$reset3=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Adjoint_EM=NULL,p.Briefing='',p.Lieu_Mission1=0,
			p.Lieu_Mission2=0,p.Lieu_Mission3=0,p.Lieu_Mission4=0,p.Lieu_Mission5=0,p.Lieu_Mission6=0,p.Lieu_Mission7=0,p.Lieu_Mission8=0,p.Lieu_Mission9=0,p.Lieu_Mission10=0,p.Lieu_Mission12=0,
			p.Type_Mission1=0,p.Type_Mission2=0,p.Type_Mission3=0,p.Type_Mission4=0,p.Type_Mission5=0,p.Type_Mission6=0,p.Type_Mission7=0,p.Type_Mission8=0,p.Type_Mission9=0,p.Type_Mission10=0,p.Type_Mission12=0,
			o.Postuler=0 WHERE p.Adjoint_EM=o.ID AND p.Adjoint_EM >0 AND o.Credits_date <'".$Date_inactif_em."'");
			$reset4=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Officier_EM=NULL,o.Postuler=0 WHERE p.Officier_EM=o.ID AND p.Officier_EM >0 AND o.Credits_date <'".$Date_inactif_em."'");
			$reset5=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Adjoint_Terre=NULL,o.Postuler=0 WHERE p.Adjoint_Terre=o.ID AND p.Adjoint_Terre >0 AND o.Credits_date <'".$Date_inactif_em."'");
			$reset6=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Officier_Mer=NULL,o.Postuler=0 WHERE p.Officier_Mer=o.ID AND p.Officier_Mer >0 AND o.Credits_date <'".$Date_inactif_em."'");
			$reset7=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Officier_Log=NULL,o.Postuler=0 WHERE p.Officier_Log=o.ID AND p.Officier_Log >0 AND o.Credits_date <'".$Date_inactif_em."'");
			$reset8=mysqli_query($con,"UPDATE Pays as p,Officier_em as o SET p.Officier_Rens=NULL,o.Postuler=0 WHERE p.Officier_Rens=o.ID AND p.Officier_Rens >0 AND o.Credits_date <'".$Date_inactif_em."'");
			mysqli_close($con);
			PrintOnlinePlayers($PlayerID);
			echo "</div></div><hr>";
			for($Land=1;$Land<=9;$Land++)
			{
				if($Land ==1 or $Land ==2 or $Land ==4 or $Land ==6 or $Land ==7 or $Land ==8 or $Land ==9)
				{
					$con=dbconnecti();
					$result=mysqli_query($con,"SELECT Front,Commandant,Adjoint_EM,Officier_EM,Officier_Rens,Adjoint_Terre,Officier_Mer,Officier_Log,Cdt_Chasse,Cdt_Bomb,Cdt_Reco,Cdt_Atk,Co_Lieu_Mission,lieu_atk1,lieu_atk2,lieu_def FROM Pays WHERE Pays_ID='$Land'");
					mysqli_close($con);
					if($result)
					{
						while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
						{
							$Front=$data['Front'];
							$Commandant=$data['Commandant'];
							$Adjoint_EM=$data['Adjoint_EM'];
							$Officier_EM=$data['Officier_EM'];
							$Officier_Rens=$data['Officier_Rens'];
							$Adjoint_Terre=$data['Adjoint_Terre'];
							$Officier_Mer=$data['Officier_Mer'];
							$Officier_Log=$data['Officier_Log'];
							$Cdt_Chasse=$data['Cdt_Chasse'];
							$Cdt_Bomb=$data['Cdt_Bomb'];
							$Cdt_Reco=$data['Cdt_Reco'];
							$Cdt_Atk=$data['Cdt_Atk'];
							$Cdt="Cdt".$Front;
							$Adj="Adj".$Front;
							$Off="Off".$Front;
							$Rens="Rens".$Front;
							$Terre="Terre".$Front;
							$TAdj="TAdj".$Front;
							$Mer="Mer".$Front;
							$Log="Log".$Front;
							$Chasse="Chasse".$Front;
							$Bomb="Bomb".$Front;
							$Reco="Reco".$Front;
							$Atk="Atk".$Front;
							$Co_Lieu="Co_Lieu".$Front;
							$Atk1_Lieu="Atk1_Lieu".$Front;
							$Atk2_Lieu="Atk2_Lieu".$Front;
							$Def_Lieu="Def_Lieu".$Front;
							if($data['Co_Lieu_Mission'])
								$$Co_Lieu=GetData("Lieu","ID",$data['Co_Lieu_Mission'],"Nom");
							else
								$$Co_Lieu="";
							if($data['lieu_atk1'])
								$$Atk1_Lieu=GetData("Lieu","ID",$data['lieu_atk1'],"Nom");
							else
								$$Atk1_Lieu="";
							if($data['lieu_atk2'])
								$$Atk2_Lieu=GetData("Lieu","ID",$data['lieu_atk2'],"Nom");
							else
								$$Atk2_Lieu="";
							if($data['lieu_def'])
								$$Def_Lieu=GetData("Lieu","ID",$data['lieu_def'],"Nom");
							else
								$$Def_Lieu="";
							if($Commandant)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Commandant'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av1=GetAvancement($datao['Avancement'],$Land,0,1);
										$$Cdt=$Av1[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$Cdt="Poste vacant";
							if($Adjoint_EM)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Adjoint_EM'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av2=GetAvancement($datao['Avancement'],$Land);
										$$Adj=$Av2[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$Adj="Poste vacant";
							if($Officier_EM)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Officier_EM'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av3=GetAvancement($datao['Avancement'],$Land,0,1);
										$$Off=$Av3[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$Off="Poste vacant";
							if($Cdt_Chasse)
							{
								$Av9=GetAvancement(GetData("Officier_em","ID",$Cdt_Chasse,"Avancement"),$Land); 
								$$Chasse="".$Av9[0]."<br>".GetData("Officier_em","ID",$Cdt_Chasse,"Nom");
							}
							else
								$$Chasse="Poste vacant";
							if($Cdt_Bomb)
							{
								$Av10=GetAvancement(GetData("Officier_em","ID",$Cdt_Bomb,"Avancement"),$Land); 
								$$Bomb="".$Av10[0]."<br>".GetData("Officier_em","ID",$Cdt_Bomb,"Nom");
							}
							else
								$$Bomb="Poste vacant";
							if($Cdt_Reco)
							{
								$Av11=GetAvancement(GetData("Officier_em","ID",$Cdt_Reco,"Avancement"),$Land); 
								$$Reco="".$Av11[0]."<br>".GetData("Officier_em","ID",$Cdt_Reco,"Nom");
							}
							else
								$$Reco="Poste vacant";
							if($Cdt_Atk)
							{
								$Av12=GetAvancement(GetData("Officier_em","ID",$Cdt_Atk,"Avancement"),$Land); 
								$$Atk="".$Av12[0]."<br>".GetData("Officier_em","ID",$Cdt_Atk,"Nom");
							}
							else
								$$Atk="Poste vacant";
							if($Adjoint_Terre)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Adjoint_Terre'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av7=GetAvancement($datao['Avancement'],$Land,0,1);
										$$TAdj=$Av7[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$TAdj="Poste vacant";
							if($Officier_Mer)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Officier_Mer'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av8=GetAvancement($datao['Avancement'],$Land,0,1);
										$$Mer=$Av8[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$Mer="Poste vacant";
							if($Officier_Rens)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Officier_Rens'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av4=GetAvancement($datao['Avancement'],$Land,0,1);
										$$Rens=$Av4[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$Rens="Poste vacant";
							if($Officier_Log)
							{
								$con=dbconnecti();
								$resulto=mysqli_query($con,"SELECT Nom,Avancement,DATE_FORMAT(Credits_Date,'%d-%m-%Y') as Activite FROM Officier_em WHERE ID='$Officier_Log'");
								mysqli_close($con);
								if($resulto)
								{
									while($datao=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
									{
										$Av6=GetAvancement($datao['Avancement'],$Land,0,1);
										$$Log=$Av6[0]."<br>".$datao['Nom']."<br><i>".$datao['Activite']."</i>";
									}
									mysqli_free_result($resulto);
								}
							}
							else
								$$Log="Poste vacant";
						}
						mysqli_free_result($result);
						unset($result);
					}
					//include_once('./menu_actus.php');
					echo "<h2>Organigramme ".GetPays($Land)."</h2><table class='table table-striped'><thead><tr>
							<th width='9%'>".GetGenStaff($Land,1)."</th>
							<th width='8%'>".GetGenStaff($Land,7)."</th>
							<th width='8%'>".GetGenStaff($Land,8)."</th>
							<th width='8%'>".GetGenStaff($Land,2)."</th>
							<th width='8%'>".GetGenStaff($Land,9)."</th>
							<th width='8%'>".GetGenStaff($Land,10)."</th>
							<th width='8%'>".GetGenStaff($Land,11)."</th>
							<th width='8%'>".GetGenStaff($Land,12)."</th>
							<th width='8%'>".GetGenStaff($Land,4)."</th>
							<th width='8%'>".GetGenStaff($Land,6)."</th>
							<th width='8%'>".GetGenStaff($Land,3)."</th>
						</tr></thead>";
					if($Land ==1 or $Land ==2 or $Land ==4 or $Land ==6 or $Land ==7)
					{
						$con=dbconnecti();
						$Units_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Pays='$Land' AND Vehicule_ID <5000 AND Vehicule_ID<>424 AND Front=0"),0);
						mysqli_close($con);
						echo "<tr><th colspan='3'>Front Ouest (".$Units_IA." Unités EM)</th><td colspan='2'>Air (<b>".$Co_Lieu0."</b>)</td><td colspan='2'>Objectif1 (<b>".$Atk1_Lieu0."</b>)</td><td colspan='2'>Objectif2 (<b>".$Atk2_Lieu0."</b>)</td><td colspan='2'>Défense (<b>".$Def_Lieu0."</b>)</td></tr>
						<tr><td>".$Cdt0."</td><td>".$TAdj0."</td><td>".$Mer0."</td><td>".$Adj0."</td><td>".$Chasse0."</td><td>".$Bomb0."</td><td>".$Reco0."</td><td>".$Atk0."</td>
						<td>".$Rens0."</td><td>".$Log0."</td><td>".$Off0."</td></tr>";
					}
					if($Land ==1 or $Land ==8 or $Land ==20)
					{
						$con=dbconnecti();
						$Units_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Pays='$Land' AND Vehicule_ID <5000 AND Vehicule_ID<>424 AND Front=4"),0);
						mysqli_close($con);
						echo "<tr><th colspan='3'>Front Nord-Est (".$Units_IA." Unités EM)</th><td colspan='2'>Air (<b>".$Co_Lieu0."</b>)</td><td colspan='2'>Objectif1 (<b>".$Atk1_Lieu4."</b>)</td><td colspan='2'>Objectif2 (<b>".$Atk2_Lieu4."</b>)</td><td colspan='2'>Défense (<b>".$Def_Lieu4."</b>)</td></tr>
						<tr><td>".$Cdt4."</td><td>".$TAdj4."</td><td>".$Mer4."</td><td>".$Adj4."</td><td>".$Chasse4."</td><td>".$Bomb4."</td><td>".$Reco4."</td><td>".$Atk4."</td>
						<td>".$Rens4."</td><td>".$Log4."</td><td>".$Off4."</td></tr>";
					}
					if($Land ==1 or $Land ==8)
					{
						$con=dbconnecti();
						$Units_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Pays='$Land' AND Vehicule_ID <5000 AND Vehicule_ID<>424 AND Front=1"),0);
						mysqli_close($con);
						echo "<tr><th colspan='3'>Front Sud-Est (".$Units_IA." Unités EM)</th><td colspan='2'>Air (<b>".$Co_Lieu1."</b>)</td><td colspan='2'>Objectif1 (<b>".$Atk1_Lieu1."</b>)</td><td colspan='2'>Objectif2 (<b>".$Atk2_Lieu1."</b>)</td><td colspan='2'>Défense (<b>".$Def_Lieu1."</b>)</td></tr>
						<tr><td>".$Cdt1."</td><td>".$TAdj1."</td><td>".$Mer1."</td><td>".$Adj1."</td><td>".$Chasse1."</td><td>".$Bomb1."</td><td>".$Reco1."</td><td>".$Atk1."</td>
						<td>".$Rens1."</td><td>".$Log1."</td><td>".$Off1."</td></tr>";
					}
					if($Land ==1 or $Land ==2 or $Land ==4 or $Land ==6 or $Land ==7)
					{
						$con=dbconnecti();
						$Units_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Pays='$Land' AND Vehicule_ID <5000 AND Vehicule_ID<>424 AND Front=2"),0);
						mysqli_close($con);
						echo "<tr><th colspan='3'>Front Méditerranéen (".$Units_IA." Unités EM)</th><td colspan='2'>Air (<b>".$Co_Lieu2."</b>)</td><td colspan='2'>Objectif1 (<b>".$Atk1_Lieu2."</b>)</td><td colspan='2'>Objectif2 (<b>".$Atk2_Lieu2."</b>)</td><td colspan='2'>Défense (<b>".$Def_Lieu2."</b>)</td></tr>
						<tr><td>".$Cdt2."</td><td>".$TAdj2."</td><td>".$Mer2."</td><td>".$Adj2."</td><td>".$Chasse2."</td><td>".$Bomb2."</td><td>".$Reco2."</td><td>".$Atk2."</td>
						<td>".$Rens2."</td><td>".$Log2."</td><td>".$Off2."</td></tr>";
					}
					if($Land ==2 or $Land ==7 or $Land ==9)
					{
						$con=dbconnecti();
						$Units_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Pays='$Land' AND Vehicule_ID <5000 AND Vehicule_ID<>424 AND Front=3"),0);
						mysqli_close($con);
						echo "<tr><th colspan='3'>Front Pacifique (".$Units_IA." Unités EM)</th><td colspan='2'>Air (<b>".$Co_Lieu3."</b>)</td><td colspan='2'>Objectif1 (<b>".$Atk1_Lieu3."</b>)</td><td colspan='2'>Objectif2 (<b>".$Atk2_Lieu3."</b>)</td><td colspan='2'>Défense (<b>".$Def_Lieu3."</b>)</td></tr>
						<tr><td>".$Cdt3."</td><td>".$TAdj3."</td><td>".$Mer3."</td><td>".$Adj3."</td><td>".$Chasse3."</td><td>".$Bomb3."</td><td>".$Reco3."</td><td>".$Atk3."</td>
						<td>".$Rens3."</td><td>".$Log3."</td><td>".$Off3."</td></tr>";
					}
					echo "</table>";
				}
			}
		}
		echo "</div>";
	}
	else
		echo "Vous n'avez pas le droit d'accéder à cette page!";
}
?>