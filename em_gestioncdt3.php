<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
$Unitet=Insec($_POST['unitet']);
if(isset($_SESSION['AccountID']) AND isset($Unitet))
{	
	$OfficierEMID=$_SESSION['Officier_em'];
	if($OfficierEMID >0)
	{
		$country=$_SESSION['country'];
		$Transfer_esc=Insec($_POST['Transfer_esc']);
		$Transfer_val=Insec($_POST['Transfer_val']);
		$Credits_r=Insec($_POST['cr']);
		include_once('./jfv_txt.inc.php');
		include_once('./jfv_inc_em.php');
		if(!$GHQ)
			include_once('./menu_em.php');
		else
			$titre="Planificateur stratégique";
		if($OfficierEMID ==$Commandant or $OfficierEMID ==$Officier_Adjoint or $GHQ or $Armee) // or $OfficierEMID ==$Officier_Adjoint or $OfficierEMID ==$Cdt_Chasse or $OfficierEMID ==$Cdt_Bomb or $OfficierEMID ==$Cdt_Reco or $OfficierEMID ==$Cdt_Atk))
		{
			if($Credits_r >0 and $Credits >=$Credits_r)
			{
				$Base=GetData("Unit","ID",$Unitet,"Base");
				if($Transfer_val and $Transfer_esc >0 and $Transfer_esc !=$Base)
				{
					//UpdateCarac($PlayerID,"Note",3);
					$Front_dest=GetFrontByCoord($Transfer_esc);
					$Unit_train=GetTraining($country);
					$con=dbconnecti();
					$reset1=mysqli_query($con,"UPDATE Lieu SET Recce=0,Recce_PlayerID=0 WHERE ID='$Transfer_esc'");
					$reset2=mysqli_query($con,"UPDATE Flak SET Lieu='$Transfer_esc' WHERE Unit='$Unitet'");
					$reset3=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0 WHERE Unit='$Unitet'");
					$reset=mysqli_query($con,"UPDATE Unit SET Base='$Transfer_esc',Mission_Lieu=0,Mission_Type=0,Mission_IA=1,Recce=0,Garnison=50 WHERE ID='$Unitet'");
					/*$reset4=mysqli_query($con,"UPDATE Joueur as j,Officier as o,Pilote as p SET p.Front=0,p.Mutation=0,p.Ailier=0,p.Unit='$Unit_train'
					WHERE j.Officier=o.ID AND j.Pilote_id=p.ID AND Unit='$Unitet' AND o.Front='$Front_dest'");
					if(mysqli_affected_rows($con) >0)
					{
						$resetc=mysqli_query($con,"UPDATE Unit as u,Pilote as p SET u.Commandant=NULL WHERE u.Commandant=p.ID AND p.Unit<>'$Unitet'");
						$reseta=mysqli_query($con,"UPDATE Unit as u,Pilote as p SET u.Officier_Adjoint=NULL WHERE u.Officier_Adjoint=p.ID AND p.Unit<>'$Unitet'");
						$resett=mysqli_query($con,"UPDATE Unit as u,Pilote as p SET u.Officier_Technique=NULL WHERE u.Officier_Technique=p.ID AND p.Unit<>'$Unitet'");
					}*/
					$reset_tr1=mysqli_query($con,"UPDATE Flak,Armes SET Flak.Unit=0 WHERE Flak.Unit='$Unitet' AND Flak.DCA_ID=Armes.ID AND Armes.Transport=0");
					$reset_tr2=mysqli_query($con,"DELETE FROM Flak WHERE Unit=0");
					mysqli_close($con);
					AddEvent("Avion",41,2,$OfficierEMID,$Unitet,$Transfer_esc);
					if(!$Admin)UpdateData("Officier_em","Credits",-$Credits_r,"ID",$OfficierEMID);
                    $_SESSION['esc'] = $Unitet;
                    $_SESSION['msg_esc'] = 'Vos ordres ont été exécutés. L\'unité a été déplacée avec succès!';
                    if($Armee){
                        header( 'Location : index.php?view=em_missions');
                    }
                    else{
                        header( 'Location : index.php?view=em_unites');
                    }
				}
				else
				{
					$mes="<p>Vos ordres n'ont pas été exécutés!</p>";
					$Credits_r=-1;
				}
			}
			else
			{
				$mes="<p>Vous ne disposez pas de suffisamment de temps!</p>";
				$Credits_r=-1;
			}
			if($OfficierEMID)
			{
				UpdateCarac($OfficierEMID,"Avancement",$Credits_r,"Officier_em");
				UpdateCarac($OfficierEMID,"Reputation",$Credits_r,"Officier_em");
			}
			$img="<img src='images/transfer_yes".$country.".jpg'>";
			if($GHQ)
				$menu="<a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour</a>";
		}
		else
			PrintNoAccess($country,1,2);
		include_once('./index.php');
	}
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>