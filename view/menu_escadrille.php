<?php
/*if($Avancement >9999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
	$Acces_officier=true;
if($Avancement >24999 or $PlayerID ==$Commandant or $PlayerID ==$Officier_Adjoint or $PlayerID ==$Officier_Technique)
{
	$Acces_Staff=true;
	$Stocks_Staff="<a href='index.php?view=esc_gestion' class='btn btn-default' title='Gérer les stocks'>Stocks</a>";
	$Stocks_DCA="<a href='index.php?view=esc_dca' class='btn btn-default' title='Gérer la DCA'>DCA</a>			         
				<a class='btn btn-default' title='Gestion du parc avion' href='index.php?view=esc_gestioncdt0_a'>Avions</a>
				<a class='btn btn-default' title='Gestion armement des avions' href='index.php?view=esc_gestioncdt0_r'>Armement</a>
				<a class='btn btn-default' href='index.php?view=esc_atelier'>Atelier</a>
				<a class='btn btn-default' title='Gestion de la base' href='index.php?view=esc_gestioncdt0_b'>Base</a>";
	$Gestion_Cdt="<a class='btn btn-warning' title='Urgences' href='index.php?view=esc_gestioncdt0_c'>Urgence</a>";
	//$Gestion_Cdt="<a href='index.php?view=esc_gestioncdt' class='btn btn-warning' title='Gérer les stocks'>Gestion</a>";
}
if($PlayerID == $Commandant or $PlayerID == $Officier_Adjoint)
	$Acces_Cdt=true;
*/
echo '<h1>'.$Unite_Nom.'</h1>';
echo "<a href='index.php?view=esc_gestion' class='btn btn-default' title='Gérer les stocks'>Stocks</a>
		<a href='index.php?view=esc_dca' class='btn btn-default' title='Gérer la DCA'>DCA</a>			         
		<a class='btn btn-default' title='Gestion du parc avion' href='index.php?view=esc_gestioncdt0_a'>Avions</a>
		<a class='btn btn-default' title='Gestion armement des avions' href='index.php?view=esc_gestioncdt0_r'>Armement</a>
		<a class='btn btn-default' href='index.php?view=esc_atelier'>Atelier</a>
		<a class='btn btn-default' title='Gestion de la base' href='index.php?view=esc_gestioncdt0_b'>Base</a>
		<a href='index.php?view=esc_gestionpers' class='btn btn-default' title='Gérer le personnel spécialisé'>Personnel</a>
		<a class='btn btn-warning' title='Urgences' href='index.php?view=esc_gestioncdt0_c'>Urgence</a>";
//<a href='index.php?view=esc_archives' class='btn btn-default' title='Consulter les archives'>Archives</a>
