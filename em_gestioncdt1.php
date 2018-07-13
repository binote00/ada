<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{	
	include_once('./jfv_include.inc.php');
	include_once('./jfv_msg.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	$Mutation_Cdt=Insec($_POST['Mutation_Cdt']);
	/*$Pilote_Note=Insec($_POST['Pilote_Note']);
	$Note_Pilote=Insec($_POST['Note_Pilote']);*/
	$country=$_SESSION['country'];
	$con=dbconnecti();	
	$result=mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'");
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Front=$data['Front'];
		}
		mysqli_free_result($result);
	}
	$Commandant=mysqli_result(mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'"),0);
	//GetDoubleData("Pays","Pays_ID",$country,"Front",$Front,"Commandant");
	if($OfficierEMID ==$Commandant or $Admin)
	{		
		$Credits=false;
		if($Mutation_Cdt >0)
		{
			$Unit_mut=GetTraining($country);
			$result=mysqli_query($con,"SELECT Nom,Unit FROM Pilote WHERE ID='$Mutation_Cdt'");
			if($result)
			{
				while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
				{
					$Nom_pil=$data['Nom'];
					$Unit_ori=$data['Unit'];
				}
				mysqli_free_result($result);
			}
			$result_ori=mysqli_query($con,"SELECT Nom,Commandant,Officier_Adjoint,Officier_Technique FROM Unit WHERE ID='$Unit_ori'");
			if($result_ori)
			{
				while($data=mysqli_fetch_array($result_ori,MYSQLI_ASSOC))
				{
					$Nom_ori=$data['Nom'];
					$Cdt_ori=$data['Commandant'];
					$Adjoint_ori=$data['Officier_Adjoint'];
					$Tech_ori=$data['Officier_Technique'];
				}
				mysqli_free_result($result_ori);
			}
			if($Cdt_ori ==$Mutation_Cdt)
			{
				$Role_field="Commandant";
				$Role=1;
			}
			elseif($Adjoint_ori ==$Mutation_Cdt)
			{
				$Role_field="Officier_Adjoint";
				$Role=2;
			}
			elseif($Tech_ori ==$Mutation_Cdt)
			{
				$Role_field="Officier_Technique";
				$Role=3;
			}
            if($Role_field){
                $reset1=mysqli_query($con,"UPDATE Unit SET ".$Role_field."=NULL WHERE ID='$Unit_ori'");
                $reset3=mysqli_query($con,"UPDATE Pilote_IA SET Avion=0,Cible=0,Couverture=0,Couverture_Nuit=0,Escorte=0,Alt=0,Task=0 WHERE Unit='$Unit_ori'");
            }
			$reset2=mysqli_query($con,"UPDATE Pilote SET Unit='$Unit_mut',Ailier=0,Couverture=0,Couverture_Nuit=0,Escorte=0 WHERE ID='$Mutation_Cdt'");
			mysqli_close($con);
			SendMsgOff($Mutation_Cdt,$PlayerID,"Suite à une réorganisation des effectifs, vous êtes muté dans une unité de réserve. Dès que vous serez prêt pour une nouvelle mutation, prenez contact avec votre état-major.","Mutation",1,3);
			if(!$Admin)$Credits=1;
			$mes="Le pilote <b>".$Nom_pil."</b> a été muté vers l'unité école. Le poste de <b>".GetStaff($country,$Role)."</b> de l'unité <b>".$Nom_ori."</b> est à présent disponible.";
		}
		/*if($Note_Pilote and $Pilote_Note)
		{
			UpdateCarac($Pilote_Note,"Note",$Note_Pilote);
			UpdateCarac($Pilote_Note,"Avancement",$Note_Pilote);
			UpdateCarac($Pilote_Note,"Reputation",$Note_Pilote);
			SetData("Pilote","Do_Note",1,"ID",$PlayerID);
		}*/	
		if($Credits !=0)
		{
			UpdateData("Officier_em","Credits",-$Credits,"ID",$OfficierEMID);
			UpdateCarac($OfficierEMID,"Avancement",-$Credits,"Officier_em");
		}
        if(!$mes)$mes.='Vos ordres ont été exécutés!';
        $_SESSION['msg_esc'] = $mes;
		header('Location: index.php?view=em_unites');
		/*$img="<img src='images/transfer_yes".$country.".jpg'>";
		$menu="<a class='btn btn-default' title='Retour à l Etat-Major' href='index.php?view=em_gestioncdt'>Retour à l'Etat-Major</a>";*/
	}
	else
		PrintNoAccess($country,1);
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
include_once('./index.php');