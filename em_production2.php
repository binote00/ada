<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	$Vehs=false;
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	include_once('./menu_em.php');
	if($OfficierEMID ==$Commandant or $OfficierEMID ==$Adjoint_Terre or $OfficierEMID ==$Officier_Rens or $OfficierEMID ==$Officier_Log or $GHQ or $Admin)
	{
        //$Type=Insec($_POST['type']);
        $con=dbconnecti();
        if($Type ==100)
            $query="SELECT ID,Pays,`Date`,Nom,Production,Stock,Type,Categorie,Repare,Retrait,Usine1,Usine2,Usine3,Reput,Lease,Capture,DATEDIFF(Retrait,`Date`) as Chaine FROM Cible WHERE Pays='$country' AND Unit_ok=1 AND Categorie=5 AND `Type`=0 ORDER BY `Date`,Retrait ASC";
        elseif($Type ==101)
            $query="SELECT ID,Pays,`Date`,Nom,Production,Stock,Type,Categorie,Repare,Retrait,Usine1,Usine2,Usine3,Reput,Lease,Capture,DATEDIFF(Retrait,`Date`) as Chaine FROM Cible WHERE Pays='$country' AND Unit_ok=1 AND Categorie=6 ORDER BY `Date`,Retrait ASC";
        else
            $query="SELECT ID,Pays,`Date`,Nom,Production,Stock,Type,Categorie,Repare,Retrait,Usine1,Usine2,Usine3,Reput,Lease,Capture,DATEDIFF(Retrait,`Date`) as Chaine FROM Cible WHERE Pays='$country' AND Unit_ok=1 AND `Type`='$Type' ORDER BY `Date`,Retrait ASC";
        $Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
        $resultt=mysqli_query($con,"SELECT ID,`Type` FROM Veh_Type WHERE ID NOT IN (13,14,38) ORDER BY `Type` ASC");
        $result=mysqli_query($con,$query);
        if($resultt){
            while($datat=mysqli_fetch_array($resultt,MYSQLI_ASSOC)){
                $cibleok=false;
                if($datat['ID'] <100) {
                    $cibleok = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Cible WHERE Pays='$country' AND `Type`='".$datat['ID']."'"), 0);
                }
                elseif($datat['ID'] ==100) {
                    $cibleok = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Cible WHERE Pays='$country' AND Categorie=5 AND `Type`=0"), 0);
                }
                elseif($datat['ID'] ==101) {
                    $cibleok = mysqli_result(mysqli_query($con, "SELECT COUNT(*) FROM Cible WHERE Pays='$country' AND  Categorie=6"), 0);
                }
                if($cibleok){
                    if($Type ==$datat['ID'])
                        echo "<a class='btn btn-primary' href='index.php?view=em_production2_".$datat['ID']."'>".$datat['Type']."</a>";
                    else
                        echo "<a class='btn btn-default' href='index.php?view=em_production2_".$datat['ID']."'>".$datat['Type']."</a>";
                }
            }
        }
        //Coût et quantité de réparations
        $Repa_Nbr=1;
		if($GHQ or $Admin)
		{
			$CT_Repa_Min=4;
			$Repa_Nbr=4;
			$Repa_ok=true;
		}
		elseif($OfficierEMID ==$Officier_Log)
		{
			$CT_Repa_Min=8;
			$Repa_ok=true;
		}
		else
            $CT_Repa_Min=99;
		if($Trait ==14)
            $CT_Repa_Min-=2;
		//Véhicules
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Service=0;
				$Perdus=0;
				$Perdus2=0;
				$Perdus3=0;
				$Usine1='';
				$Usine2='';
				$Usine3='';
				$Transfer='';
				$Industrie2=100;
				$Industrie3=100;
				$Taux_rate=1;
				$Flags=false;
				$ID=$data['ID'];
				$Pays=$data['Pays'];
				$Avion_img=GetVehiculeIcon($ID,$Pays);
				if($data['Usine1'])
				{
					$Industrie1=GetData("Lieu","ID",$data['Usine1'],"Industrie");
					$Usine1=GetData("Lieu","ID",$data['Usine1'],"Nom")."<br>".$Industrie1."%";
					$Taux=$Industrie1;
				}
				if($data['Usine2'])
				{
					$Industrie2=GetData("Lieu","ID",$data['Usine2'],"Industrie");
					$Usine2=GetData("Lieu","ID",$data['Usine2'],"Nom")."<br>".$Industrie2."%";
					$Taux+=$Industrie2;
					$Taux_rate++;
				}
				if($data['Usine3'])
				{
					$Industrie3=GetData("Lieu","ID",$data['Usine3'],"Industrie");
					$Usine3=GetData("Lieu","ID",$data['Usine3'],"Nom")."<br>".$Industrie3."%";
					$Taux+=$Industrie3;
					$Taux_rate++;
				}
				//$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment WHERE Vehicule_ID='$ID'"),0);
				$Service=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Vehicule_ID='$ID'"),0);
                $Garnisons=mysqli_result(mysqli_query($con,"SELECT SUM(Garnison) FROM Lieu WHERE Flag='$country'"),0);
				if($data['Categorie'] ==20 or $data['Categorie'] ==21 or $data['Categorie'] ==24)
				{
					$Perdus=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Vehicule_ID='$ID' AND Vehicule_Nbr=0"),0);
					$Perdus2=0;
				}
				else
				{
					$con4=dbconnecti(4);
					$Perdus=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground_Stats WHERE Event_Type IN (400,401,404,405,415,420,605,615) AND Avion='$ID'"),0);
					$Perdus2=mysqli_result(mysqli_query($con4,"SELECT COUNT(*) FROM Events_Ground_Stats WHERE Event_Type IN (402,403) AND Pilote_eni='$ID'"),0); //54
					if($data['Categorie'] ==5 or $data['Categorie'] ==6)
						$Perdus3=mysqli_result(mysqli_query($con4,"SELECT SUM(Avion_Nbr) FROM Events_Ground WHERE Event_Type IN (602,702) AND Pilote_eni='$ID'"),0);
					mysqli_close($con4);
				}
                if($data['Categorie'] ==5 and $Type==100)
    				$Service+=$Garnisons;
				$Perdustotal=$Perdus+$Perdus2+$Perdus3;
				if($data['Repare'] >$Perdustotal)$data['Repare']=$Perdustotal;
				$Repa=$Perdustotal-$data['Repare'];
				$Reste=floor($data['Stock']-$Service-$Perdustotal+$data['Repare']);
				if($Reste+$Service >$data['Stock'])$Reste=floor($data['Stock']-$Service);
				if($Reste<1)$Reste="<span class='text-danger'>0</span>";
				$CT_Repa=$data['Reput'];
				if($CT_Repa <$CT_Repa_Min)$CT_Repa=$CT_Repa_Min;
				if($Repa_ok and $Repa>=$Repa_Nbr and $Credits >=$CT_Repa and $data['Reput']<$CT_MAX)
					$Repa_txt="<form action='em_prod_repa2.php' method='post'><input type='hidden' name='veh' value='".$ID."'><input type='hidden' name='CT' value='".$CT_Repa."'><input type='hidden' name='Type' value='".$Type."'><img src='/images/CT".$CT_Repa.".png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='Réparer' class='btn btn-warning btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
				else
					$Repa_txt="";
				$Rate="<span class='text-danger'>0</span>";
				if($data['Lease'])$Flags.="<img src='images/lendlease.png' title='Lend-Lease' alt='Lend-Lease'>";
				if($data['Capture'])$Flags.="<img src='images/capture_factory.png' title='Usine ennemie capturée' alt='Usine ennemie capturée'>";
				if($data['Retrait'] <$Date_Campagne)
				{
					$Vehs.="<tr><td>".$Avion_img."</td><td class='hidden-lg-down'><img src='".$Pays."20.gif'>".$Flags."</td><td>".$data['Date']."</td><td><span class='text-danger'>".$data['Retrait']."</span></td><td>".$data['Production']."</td><td>".$Rate."</td><th>".floor($data['Stock'])."</th>
					<td>".$Perdustotal."</td><td>".$data['Repare']."/".$Repa.$Repa_txt."</td><th>".$Service."</th><td>".$Reste."</td><td>".$Usine1."</td><td>".$Usine2."</td><td>".$Usine3."</td><td align='left' class='hidden-lg-down'>".$Transfer."</td></tr>";
				}
				elseif($data['Date'] >$Date_Campagne)
				{
					$Vehs.="<tr style='color: grey;'><td>".$Avion_img."</td><td class='hidden-lg-down'><img src='".$Pays."20.gif'>".$Flags."</td><td><span class='text-danger'>".$data['Date']."</span></td><td>".$data['Retrait']."</td><td>".$data['Production']."</td><td>".$Rate."</td><th>".floor($data['Stock'])."</th>
					<td>".$Perdustotal."</td><td>".$data['Repare']."/".$Repa.$Repa_txt."</td><th>".$Service."</th><td>".$Reste."</td><td>".$Usine1."</td><td>".$Usine2."</td><td>".$Usine3."</td><td align='left' class='hidden-lg-down'>".$Transfer."</td></tr>";
				}
				else
				{
					if($Industrie1 ==0 or $Industrie2 ==0 or $Industrie3 ==0)
						$Reste="<span class='text-danger'>0</span>";
					else
						$Rate=round($data['Production']/$data['Chaine']*($Taux/$Taux_rate)/100,2);
					$Vehs.="<tr><td>".$Avion_img."</td><td class='hidden-lg-down'><img src='".$Pays."20.gif'>".$Flags."</td><td>".$data['Date']."</td><td>".$data['Retrait']."</td><td>".$data['Production']."</td><td>".$Rate."</td><th>".floor($data['Stock'])."</th>
					<td>".$Perdustotal."</td><td>".$data['Repare']."/".$Repa.$Repa_txt."</td><th>".$Service."</th><td>".$Reste."</td><td>".$Usine1."</td><td>".$Usine2."</td><td>".$Usine3."</td><td align='left' class='hidden-lg-down'>".$Transfer."</td></tr>";
				}
			}
			mysqli_free_result($result);
			unset($data);
		}
		else
			echo "<b>Désolé, aucun véhicule</b>";
		//echo "<a class='btn btn-default' title='Retour au menu' href='index.php?view=em_production20'>Retour au menu</a>";
        echo "<h2>Production des véhicules en service</h2><div class='alert alert-warning'><a href='#' class='close' data-dismiss='alert' aria-label='close'>&times;</a>Les véhicules apparaissant en grisé dans cette liste ont déjà tous été produits par nos usines</div>
		<div style='overflow:auto;'><table class='table table-striped table-condensed table-responsive'>
		<thead><tr>
			<th>Véhicule</th>
			<th class='hidden-lg-down'>Pays</th>
			<th>Mise en service</th>
			<th>Fin de production</th>
			<th title='Production totale'>Total</th>
			<th title='Production quotidienne'>Prod</th>
			<th title='Stock actuel'>Stock</th>
			<th title='Nombre de pertes'>Perdus</th>
			<th title='Véhicules réparés'>Reparés</th>
			<th title='Véhicules en service'>En Service</th>
			<th title='Production - Perdus - En Service'>Réserve</th>
			<th>Usine 1</th>
			<th>Usine 2</th>
			<th>Usine 3</th>
			<th class='hidden-lg-down'>Infos</th></tr></thead>".$Vehs."</table></div>";
	}
	else
		PrintNoAccess($country,1,4,6);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';