<?
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_msg.inc.php');
	$country=$_SESSION['country'];
	$Front_dest=Insec($_POST['front']);
	$con=dbconnecti();	
	$resulto=mysqli_query($con,"SELECT Front,Credits FROM Officier_em WHERE ID='$OfficierEMID'");
	if($resulto)
	{
		while($data=mysqli_fetch_array($resulto,MYSQLI_ASSOC))
		{
			$Front=$data['Front'];
			$Credits=$data['Credits'];
		}
		mysqli_free_result($resulto);
	}
	if($Front_dest >0 and $Credits >=24)
	{
	    if($Front ==99){
            $reset3=mysqli_query($con,"UPDATE GHQ SET Planificateur=NULL WHERE Pays='$country'");
        }else{
            if($Front_dest ==10)
                $Front_dest=0;
            $result2=mysqli_query($con,"SELECT Commandant,Adjoint_EM,Officier_EM,Adjoint_Terre,Officier_Mer,Officier_Rens,Officier_Log FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
            if($result2)
            {
                while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
                {
                    $Commandant = $data['Commandant'];
                    $Officier_Adjoint = $data['Adjoint_EM'];
                    $Officier_EM = $data['Officier_EM'];
                    $Officier_Rens = $data['Officier_Rens'];
                    $Adjoint_Terre = $data['Adjoint_Terre'];
                    $Officier_Mer = $data['Officier_Mer'];
                    $Officier_Log = $data['Officier_Log'];
                }
                mysqli_free_result($result2);
            }
            if($Commandant ==$OfficierEMID)
                $Poste='Commandant';
            elseif($Officier_Adjoint ==$OfficierEMID)
                $Poste='Adjoint_EM';
            elseif($Officier_EM ==$OfficierEMID)
                $Poste='Officier_EM';
            elseif($Adjoint_Terre ==$OfficierEMID)
                $Poste='Adjoint_Terre';
            elseif($Officier_Mer ==$OfficierEMID)
                $Poste='Officier_Mer';
            elseif($Officier_Rens ==$OfficierEMID)
                $Poste='Officier_Rens';
            elseif($Officier_Log ==$OfficierEMID)
                $Poste='Officier_Log';
            if($Poste)$reset3=mysqli_query($con,"UPDATE Pays SET ".$Poste."=NULL WHERE Pays_ID='$country' AND Front='$Front'");
            $reset2=mysqli_query($con,"UPDATE Armee SET Cdt=NULL WHERE Cdt='$OfficierEMID'");
        }
		$reset=mysqli_query($con,"UPDATE Officier_em SET Front='$Front_dest',Credits=0,Postuler=0,Armee=0,Mutation=0 WHERE ID='$OfficierEMID'");
		if($reset)
		{
			$Corps=GetEM_Name($country);
			$mes='Changement de front effectif!';
		}
		else
			$mes='Une erreur est survenue, veuillez le signaler sur le forum.';
		$titre='Demande de mutation';
		include_once('./default.php');
	}
	else
		echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';