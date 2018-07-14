<?
/*if($OfficierEMID >0 or $PlayerID >0)
{
	if($OfficierEMID >0 and ($OfficierEMID == $Commandant or $OfficierEMID == $Officier_Adjoint or $Admin >0))
	{
		$Acces_Staff=true;
		$Stocks_Staff="<a href='index.php?view=em_gestion' class='btn btn-default' title='Gérer le parc avion et les infrastructures'>Gestion</a>";
		$Mission_Staff="<a href='index.php?view=em_mission' class='btn btn-default' title='Gérer les missions du front'>Missions</a>";
	}
	elseif($OfficierEMID >0 and $OfficierEMID == $Officier_EM)
	{
		$Stocks_Staff="<a href='index.php?view=em_gestion' class='btn btn-default' title='Gérer les infrastructures'>Gestion</a>";
		$Mission_Staff="<span class='btn btn-danger'>Confidentiel</span>";
	}
	else
	{
		$Acces_Staff=false;
		$Stocks_Staff="<span class='btn btn-danger'>Confidentiel</span>";
		$Mission_Staff="<span class='btn btn-danger'>Confidentiel</span>";
	}
	if(($OfficierEMID >0 and ($OfficierEMID == $Commandant or $OfficierEMID == $Officier_Adjoint or $Admin == 1)) 
	or ($PlayerID >0 and ($PlayerID == $Cdt_Chasse or $PlayerID == $Cdt_Bomb or $PlayerID == $Cdt_Reco or $PlayerID == $Cdt_Atk or $Admin == 1)))
	{
		$Acces_Cdt=true;
		$Gestion_Cdt="<a href='index.php?view=em_gestioncdt' class='btn btn-default' title='Gérer le personnel'>Pilotes</a>";
		if($PlayerID == $Cdt_Chasse or $PlayerID == $Cdt_Bomb or $PlayerID == $Cdt_Reco or $PlayerID == $Cdt_Atk)
			$Mission_Staff="<a href='index.php?view=em_missions' class='btn btn-default' title='Gérer les missions IA'>Missions</a>";
	}
	else
	{
		$Acces_Cdt=false;
		$Gestion_Cdt="<span class='btn btn-danger'>Confidentiel</span>";
	}
	if($OfficierEMID == $Commandant or $OfficierEMID == $Officier_Rens or $Admin >0)
		$CI="<a href='index.php?view=em_rens' class='btn btn-default' title='Renseignements et contre-espionnage'>Intelligence</a>";
	else
		$CI="<span class='btn btn-danger'>Confidentiel</span>";
	if($OfficierEMID == $Commandant or $OfficierEMID == $Officier_Adjoint or $OfficierEMID == $Officier_EM or $OfficierEMID == $Officier_Rens or $OfficierEMID == $Adjoint_Terre or $OfficierEMID == $Officier_Mer or $Admin >0)
		$Journal="<a href='index.php?view=em_journal' class='btn btn-default' title='Journal des opérations commando'>Journal</a>";
	else
		$Journal="<span class='btn btn-danger'>Confidentiel</span>";		
	echo "<p>".$Stocks_Staff." ".$Gestion_Cdt." ".$Mission_Staff." ".$CI." ".$Journal."</p>";
}*/
?>