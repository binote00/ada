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
		$Mode=Insec($_POST['mode']);
        $_SESSION['reg']=$Regiment;
        if($Regiment and $Mode)
		{
			if($Mode ==11)
			{
				SetData("Regiment_IA","NoEM",1,"ID",$Regiment);
                $_SESSION['msg'] = 'Vous réservez cette unité pour le GHQ!';
			}
			else
			{
				SetData("Regiment_IA","NoEM",0,"ID",$Regiment);
                $_SESSION['msg'] = 'Vous libérez cette unité pour le commandant de front!';
			}
		}
        header( 'Location : index.php?view=ground_em_ia');
	}
	else
		PrintNoAccess($country,1);
}