<?php
require_once('./jfv_inc_sessions.php');
$OfficierEMID=$_SESSION['Officier_em'];
if(isset($_SESSION['AccountID']) AND $OfficierEMID >0)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_inc_em.php');
	if($GHQ or $Admin)
	{
		$Regiment=Insec($_POST['Reg']);
		$Front_Ori=Insec($_POST['Front']);
		$Lieu_Ori=Insec($_POST['Transit']);
		if($Regiment and $Lieu_Ori){
            if($Lieu_Ori ==2 or $Lieu_Ori ==603)
            {
                if($Front_Ori ==4)
                    $Front_Dest=10;
                else
                    $Front_Dest=4;
            }
            elseif($Lieu_Ori ==189 or $Lieu_Ori ==198 or $Lieu_Ori ==201 or $Lieu_Ori ==344 or $Lieu_Ori ==586)
            {
                if($Front_Ori ==2)
                    $Front_Dest=10;
                else
                    $Front_Dest=2;
            }
            elseif($Lieu_Ori ==199 or $Lieu_Ori ==218 or $Lieu_Ori ==1600 or $Lieu_Ori ==2732)
            {
                if($Front_Ori ==1)
                    $Front_Dest=10;
                else
                    $Front_Dest=1;
            }
            elseif($Lieu_Ori ==614)
            {
                if($Front_Ori ==4)
                    $Front_Dest=5;
                else
                    $Front_Dest=4;
            }
            elseif($Lieu_Ori ==615 or $Lieu_Ori ==619 or $Lieu_Ori ==621 or $Lieu_Ori ==967 or $Lieu_Ori ==1280)
            {
                if($Front_Ori ==4)
                    $Front_Dest=1;
                else
                    $Front_Dest=4;
            }
            elseif($Lieu_Ori ==704 or $Lieu_Ori ==898 or $Lieu_Ori ==2079)
            {
                if($Front_Ori ==5)
                    $Front_Dest=10;
                else
                    $Front_Dest=5;
            }
            elseif($Lieu_Ori ==709)
            {
                if($Front_Ori ==2)
                    $Front_Dest=1;
                else
                    $Front_Dest=2;
            }
            elseif($Lieu_Ori ==1896)
            {
                if($Front_Ori ==2)
                    $Front_Dest=3;
                else
                    $Front_Dest=2;
            }
            elseif($Lieu_Ori ==1567 or $Lieu_Ori ==1577)
                $Front_Dest=10;
            elseif($Lieu_Ori ==2079 or $Lieu_Ori ==2149)
                $Front_Dest=3;
            if($Front_Dest >0)
            {
                if($country ==7 or $country ==2)
                {
                    if($Lieu_Ori ==2149)
                        SetData("Regiment_IA","Lieu_ID",1567,"ID",$Regiment);
                    elseif($Lieu_Ori ==1567 or $Lieu_Ori ==1577)
                        SetData("Regiment_IA","Lieu_ID",2149,"ID",$Regiment);
                }
                if($Front_Dest ==10)$Front_Dest=0;
                $con=dbconnecti();
                $reset2=mysqli_query($con,"UPDATE Regiment_IA SET Front='$Front_Dest',Division=NULL,Bataillon=NULL,Move=1,Position=4,Mission_Type_D=0,Mission_Lieu_D=0,Visible=0,Dem_Front=0 WHERE ID='$Regiment'");
                mysqli_close($con);
                $_SESSION['msg']='L\'unité a changé de front avec succès!';
            }
            else
                $_SESSION['msg_red']='Erreur!';
            $_SESSION['reg'] = $Regiment;
            header( 'Location : index.php?view=ground_em_ia');
        }
        else
            header( 'Location : index.php');
    }
	else
		PrintNoAccess($country,1);
}