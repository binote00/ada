<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{	
	$PlayerID=$_SESSION['PlayerID'];
	$OfficierEMID=$_SESSION['Officier_em'];
	include_once('./jfv_include.inc.php');
	$Unite=Insec($_POST['Unite']);
	if(($PlayerID >0 or $OfficierEMID >0) AND $Unite >0)
	{
		include_once('./jfv_txt.inc.php');
		$Flight=Insec($_POST['flight']);
		$CT_Refit=Insec($_POST['CT']);
		$GHQ=Insec($_POST['ghq']);
		$country=$_SESSION['country'];
        $_SESSION['esc'] = $Unite;
		if($OfficierEMID >0)
			$DB="Officier_em";
		elseif($PlayerID >0)
		{
			$DB="Pilote";
			$OfficierEMID=$PlayerID;
		}
		$con=dbconnecti();
		$result=mysqli_query($con,"SELECT Avancement,Credits,Admin FROM $DB WHERE ID='$OfficierEMID'");
		//mysqli_close($con);
		if($result)
		{
			while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
			{
				$Avancement=$data['Avancement'];
				$Credits=$data['Credits'];
				$Admin=$data['Admin'];
			}
			mysqli_free_result($result);
			unset($data);
		}
		if($Credits >=$CT_Refit and $Flight >0)
		{
			//$con=dbconnecti();
			$result=mysqli_query($con,"SELECT Nom,Type,Reputation,Base,Avion1,Avion2,Avion3,Avion1_Nbr,Avion2_Nbr,Avion3_Nbr FROM Unit WHERE ID='$Unite'");
			//mysqli_close($con);
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Unite_Nom=$data['Nom'];
					$Unite_Type=$data['Type'];
					$Unite_Reput=$data['Reputation'];
					$Base=$data['Base'];
					$Avion1=$data['Avion1'];
					$Avion2=$data['Avion2'];
					$Avion3=$data['Avion3'];
					$Avion1_Nbr=$data['Avion1_Nbr'];
					$Avion2_Nbr=$data['Avion2_Nbr'];
					$Avion3_Nbr=$data['Avion3_Nbr'];
				}
				mysqli_free_result($result);
				unset($data);
			}
			$Avion_Usine=false;
			$Avion="Avion".$Flight;
			$Avion_Nbr="Avion".$Flight."_Nbr";
			$MaxFlight=GetMaxFlight($Unite_Type,$Unite_Reput,0);
			//$con=dbconnecti();
			$result1=mysqli_query($con,"SELECT Nom,Production,Stock,Usine1,Usine2,Usine3,Reserve FROM Avion WHERE ID='".$$Avion."'");
			//mysqli_close($con);
			if($result1)
			{
				while($data=mysqli_fetch_array($result1,MYSQLI_ASSOC))
				{
					$Nom_Avion=$data['Nom'];
					$Production=floor($data['Stock']);
					$Reserve=$data['Reserve'];
					if($Base ==$data['Usine1'] or $Base ==$data['Usine2'] or $Base ==$data['Usine3'])
						$Avion_Usine=true;
				}
				mysqli_free_result($result1);
			}
			if($CT_Refit >2)$Avion_Usine=true;
			if($$Avion_Nbr <$MaxFlight and $Avion_Usine)
			{
				//$con=dbconnecti();
				$Abattu=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Chasse WHERE Avion_loss='".$$Avion."' AND PVP=1"),0);
				$DCA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM DCA WHERE Avion='".$$Avion."'"),0);
				$Service1=mysqli_result(mysqli_query($con,"SELECT SUM(Avion1_Nbr) FROM Unit WHERE Avion1='".$$Avion."' AND Etat=1"),0);
				$Service2=mysqli_result(mysqli_query($con,"SELECT SUM(Avion2_Nbr) FROM Unit WHERE Avion2='".$$Avion."' AND Etat=1"),0);
				$Service3=mysqli_result(mysqli_query($con,"SELECT SUM(Avion3_Nbr) FROM Unit WHERE Avion3='".$$Avion."' AND Etat=1"),0);
				mysqli_close($con);
				$con=dbconnecti(4);
				$Perdu=mysqli_result(mysqli_query($con,"SELECT SUM(Avion_Nbr) FROM Events_Pertes WHERE Event_Type IN (11,12,34,221,222,231) AND Avion='".$$Avion."' AND Avion_Nbr >0"),0);
				mysqli_close($con);
				$Total=$DCA+$Abattu+$Perdu;
				$Service=$Service1+$Service2+$Service3;
				$Reste=$Production-$Total-$Service+$Reserve;
				if($Reste+$Service >$Production)$Reste=$Production-$Service;
				if($Reste >=1)
				{
					$add_avions=1;
					if($GHQ and $CT_Refit==2)
					{
						$add_avions=$Reste;
						if($add_avions>=$MaxFlight)$add_avions=$MaxFlight;
					}
					UpdateData("Unit",$Avion_Nbr,$add_avions,"ID",$Unite,$MaxFlight);
					UpdateCarac($OfficierEMID,"Avancement",$CT_Refit,$DB);
					UpdateCarac($OfficierEMID,"Note",1,$DB);
					if(!$Admin)UpdateData($DB,"Credits",-$CT_Refit,"ID",$OfficierEMID);
                    $_SESSION['msg_esc'] = 'Un <b>'.$Nom_Avion.'</b> '.GetAvionIcon($$Avion,$country,0,$Unite,$Front).' est ajouté au stock du <b>'.$Unite_Nom.'</b>';
				}
				else
                    $_SESSION['msg_esc_red'] = "Il ne reste plus aucun avion de ce type dans le stock disponible!<br>Le ravitaillement est annulé.
				<div class='alert alert-info'>Si ce modèle d'avion a connu des pertes, vous pouvez en réparer dans le <a href='index.php?view=em_production0' class='lien'>menu de production</a> afin de récupérer des avions pour renforcer vos unités.</div>";
			}
			else
                $_SESSION['msg_esc_red'] = 'Ce type d\'avion ne peut être ravitaillé ici.';
		}
        header( 'Location : index.php?view=em_ia');
        /*Old Way
        $titre="Ravitaillement";
		$img="<img src='images/gestion_avions".$country.".jpg'><h2>".$Unite_Nom."</h2>";
		if($GHQ)
			$mes.="<br><form action='index.php?view=em_ia' method='post'><input type='hidden' name='Unit' value='".$Unite."'><input type='Submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>
			<br><a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour menu</a>";
		else
			$mes.="<br><a href='index.php?view=em_missions' class='btn btn-default' title='Retour'>Retour</a>";*/
	}
	else
		echo "<img src='images/top_secret.gif'>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
include_once('./index.php');
?>