<?
require_once('./jfv_inc_sessions.php');
if(isset($_SESSION['AccountID']))
{
	include_once('./jfv_include.inc.php');
	$country=$_SESSION['country'];
	if($_SESSION['Distance'] ==0)
	{
		$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
		if($Admin)
		{
		    function GetCat($Cat){
                /*                24	Croiseur lourd
                23	Croiseur léger
                22	Corvette/Destroyer
                21	Porte-avions
                20	Cuirassé
                19	Démineur (dragueur de mines)
                18	Navire de débarquement
                17	Sous-marin
                16	Génie motorisé
                15	DCA
                14	Refueler
                13	Locomotive
                12	Wagon troupes
                11	Wagon blindé
                10	Wagon fret
                9	Canon AT
                8	Artillerie (+ artillerie mob)
                7	Cavalerie
                6	MG
                5	Infanterie
                4	Véhicule Cdt
                3	Blindé moyen/lourd + chasseurs de chars + canon d'assaut
                2	Blindé léger + véhicule blindé + half-track
                1	Camion*/
                switch($Cat){
                    case 1:
                        return 'Camion';
                    case 2:
                        return 'Blindé léger';
                    case 3:
                        return 'Blindé';
                    case 4:
                        return 'Véhicule Cdt';
                    case 5:
                        return 'Infanterie';
                    case 6:
                        return 'Mitrailleuse';
                    case 7:
                        return 'Cavalerie';
                    case 8:
                        return 'Artillerie';
                    case 9:
                        return 'Canon AT';
                    case 13:
                        return 'Train';
                    case 15:
                        return 'DCA';
                    case 17:
                        return 'Sous-marins';
                    case 19:
                        return 'Petit navire';
                    case 20:
                        return 'Cuirassé';
                    case 21:
                        return 'Porte-avions';
                    case 22:
                        return 'Corvette';
                    case 23:
                        return 'Croiseur léger';
                    case 24:
                        return 'Croiseur lourd';
                    case 25:
                        return 'Petit navire';
                    case 26:
                        return 'Cargo';
                    case 30:
                        return 'Patrouilleur';
                    case 38:
                        return 'GHQ';
                    default:
                        return $Cat;
                }

            }
			$con=dbconnecti();
            /*$Troupes_axe=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Pays IN(1,6) AND c.Categorie="),0);
            $Troupes_allies=mysqli_result(mysqli_query($con,"SELECT SUM(Vehicule_Nbr) FROM Regiment_IA WHERE Pays IN(2,3,4,5,35)"),0);*/
            $Troupes=mysqli_query($con,"SELECT r.Vehicule_Nbr,r.Pays,c.Categorie FROM Regiment_IA as r,Cible as c WHERE r.Vehicule_ID=c.ID AND r.Vehicule_Nbr >0");
            mysqli_close($con);
            if($Troupes){
                while($data=mysqli_fetch_array($Troupes)){
                    if($data['Pays'] ==2 or $data['Pays'] ==3 or $data['Pays'] ==4 or $data['Pays'] ==5 or $data['Pays'] ==7 or $data['Pays'] ==8 or $data['Pays'] ==10 or $data['Pays'] ==17 or $data['Pays'] ==35){
                        $Allies[$data['Categorie']]+=$data['Vehicule_Nbr'];
                        $Allies_units[$data['Categorie']]+=1;
                    }
                    elseif($data['Pays'] ==1 or $data['Pays'] ==6 or $data['Pays'] ==9 or $data['Pays'] ==15 or $data['Pays'] ==18 or $data['Pays'] ==19 or $data['Pays'] ==20){
                        $Axe[$data['Categorie']]+=$data['Vehicule_Nbr'];
                        $Axe_units[$data['Categorie']]+=1;
                    }
                }
                mysqli_free_result($Troupes);
            }
            $units_allies_nbr=array_sum($Allies_units);
            $units_axe_nbr=array_sum($Axe_units);
            if(is_array($Allies))
            {
                $total_allies=array_sum($Allies);
                ksort($Allies);
                foreach($Allies as $Cat => $Nbr)
                {
                    $Stocks_allies.='<tr><td>'.GetCat($Cat).'</td><td>'.$Allies_units[$Cat].'</td><td>'.$Nbr.'</td></tr>';
                }
                unset($Allies);
            }
            if(is_array($Axe))
            {
                $total_axe=array_sum($Axe);
                ksort($Axe);
                foreach($Axe as $Cat => $Nbr)
                {
                    $Stocks_axe.='<tr><td>'.GetCat($Cat).'</td><td>'.$Axe_units[$Cat].'</td><td>'.$Nbr.'</td></tr>';
                }
                unset($Axe);
            }
			echo "<h1>Statistiques</h1><div class='row'><div class='col-md-6'><h2>Alliés</h2><table class='table'><thead><tr><th>Catégorie</th><th>Unités</th><th>Troupes</th></tr></thead>".$Stocks_allies."
            <tr><th>Total</th><th>".$units_allies_nbr."</th><th>".$total_allies."</th></tr></table></div>
			<div class='col-md-6'><h2>Axe</h2><table class='table'><thead><tr><th>Catégorie</th><th>Unités</th><th>Troupes</th></tr></thead>".$Stocks_axe."
			<tr><th>Total</th><th>".$units_axe_nbr."</th><th>".$total_axe."</th></tr></table></div></div>";
		}
		else
			echo "<h1>Statistiques</h1><h2>Information Premium</h2><img src='images/premium.png' title='Information Premium'>";
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';