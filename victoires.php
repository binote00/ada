<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['AccountID'];
if($PlayerID >0)
{
    include_once('./jfv_include.inc.php');
	$country=$_SESSION['country'];
	$ID=Insec($_GET['pilote']);
    if($ID >0 and is_numeric($ID)){
        $messagesParPage=25;
        $con=dbconnecti();
        $query=mysqli_query($con, "SELECT COUNT(*) AS total FROM Chasse WHERE Joueur_win='$ID' AND PVP IN (0,2,4)");
        $data=mysqli_fetch_assoc($query);
        $total=$data['total'];
        $nombreDePages=ceil($total / $messagesParPage);
        $mes="<h1>Tableau de Chasse</h1>
		<table class='table table-hover'>
		<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote Abattu</th>
			<th>Avion Abattu</th>
			<th>Unité</th>
			<th>Pays</th></tr></thead>";
        if (isset($_GET['page'])) {
            $pageActuelle=intval($_GET['page']);
            if ($pageActuelle > $nombreDePages)
                $pageActuelle=$nombreDePages;
        } else
            $pageActuelle=1;
        $premiereEntree=($pageActuelle-1)*$messagesParPage;
        $query2=mysqli_query($con, "SELECT DISTINCT * FROM Chasse WHERE Joueur_win='$ID' AND PVP IN (0,2,4) ORDER BY ID DESC LIMIT ".$premiereEntree.", ".$messagesParPage."");
        mysqli_close($con);
        if($query2){
            while ($data2=mysqli_fetch_assoc($query2)){
                $Date=substr($data2['Date'], 0, 16);
                $Avion_win=GetData("Avion", "ID", $data2['Avion_win'], "Nom");
                $Unite_win=GetData("Unit", "ID", $data2['Unite_win'], "Nom");
                $Avion_loss=GetData("Avion", "ID", $data2['Avion_loss'], "Nom");
                $Unite_loss=GetData("Unit", "ID", $data2['Unite_loss'], "Nom");
                if ($data2['PVP'] ==0 or $data2['PVP'] ==4)
                    $Pilote_loss=GetData("Pilote_IA", "ID", $data2['Pilote_loss'], "Nom");
                else
                    $Pilote_loss='<b>'.GetData("Pilote", "ID", $data2['Pilote_loss'], "Nom").'</b>';
                $Pays_loss=GetData("Unit", "ID", $data2['Unite_loss'], "Pays");
                $Pays_loss="<img src='images/".$Pays_loss."20.gif'>";
                $Avion_img_win="images/avions/avion".$data2['Avion_win'].".gif";
                $Avion_img_loss="images/avions/avion".$data2['Avion_loss'].".gif";
                $Avion_unit_win_img="images/unit/unit".$data2['Unite_win']."p.gif";
                $Avion_unit_loss_img="images/unit/unit".$data2['Unite_loss']."p.gif";
                if (is_file($Avion_img_win))
                    $Avion_win="<img src='".$Avion_img_win."' title='".$Avion_win."'>";
                if (is_file($Avion_img_loss))
                    $Avion_loss="<img src='".$Avion_img_loss."' title='".$Avion_loss."'>";
                if (is_file($Avion_unit_win_img))
                    $Unite_win="<img src='".$Avion_unit_win_img."' title='".$Unite_win."'>";
                if (is_file($Avion_unit_loss_img))
                    $Unite_loss="<img src='".$Avion_unit_loss_img."' title='".$Unite_loss."'>";
                $Pays=GetData("Pilote", "ID", $data2['Joueur_win'], "Pays");
                if ($country == $Pays or $Renseignement > 200 or $PlayerID == 1 or $PlayerID == 2)
                    $read=true;
                else
                    $read=false;
                $mes.="<tr><td>".$Date."</td><td>".$Unite_win."</td><td>".$Avion_win."</td><td>".$Pilote_loss."</td>
				<td>".$Avion_loss."</td><td>".$Unite_loss."</td><td>".$Pays_loss."</td></tr>";
            }
            $mes.="<p align='center'>Page : ";
            for ($i=1; $i <= $nombreDePages; $i++) {
                if ($i == $pageActuelle)
                    $mes.=' [ '.$i.' ] ';
                else
                    $mes.=' <a href="victoires.php?pilote='.$ID.'&page='.$i.'">'.$i.'</a> ';
            }
            $mes.='</p></table>';
            include_once('./default_blank.php');
        } else
            echo "<h6>Désolé, aucune victoire enregistrée à ce jour.</h6>";
    }
}
