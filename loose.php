<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['AccountID'];
if($PlayerID > 0)
{
	include_once('./jfv_include.inc.php');
	$country=$_SESSION['country'];
	$ID=Insec($_GET['pilote']);
	if($ID >0 and is_numeric($ID)){
        $mes="<h1>Tableau des défaites</h1>
		<table class='table table-hover'>
			<thead><tr>
			<th>Date</th>
			<th>Unité</th>
			<th>Avion</th>
			<th>Pilote ennemi</th>
			<th>Avion ennemi</th>
			<th>Unité</th>
			<th>Pays</th></tr></thead>";
        $con=dbconnecti();
        $ID=mysqli_real_escape_string($con,$ID);
        $result=mysqli_query($con,"SELECT * FROM Chasse WHERE Pilote_loss='$ID' AND PVP IN (1,2) ORDER BY ID DESC");
        mysqli_close($con);
        if($result)
        {
            $num=mysqli_num_rows($result);
            if($num ==0)
                echo "<h6>A ce jour, ce pilote n'a pas encore été abattu lors d'un combat aérien.</h6>";
            else
            {
                $i=0;
                while($i <$num)
                {
                    $Date = mysqli_result($result,$i,"Date");
                    $Avion_loose = mysqli_result($result,$i,"Avion_loss");
                    $Avion_eni = mysqli_result($result,$i,"Avion_win");
                    $Unite_eni = mysqli_result($result,$i,"Unite_win");
                    $Unite_loose = mysqli_result($result,$i,"Unite_loss");
                    $PVP = mysqli_result($result,$i,"PVP");
                    if($PVP >1)
                        $Pilote_win=GetData("Pilote","ID",mysqli_result($result,$i,"Joueur_win"),"Nom");
                    else
                        $Pilote_win='Inconnu';
                    $Pays_win=GetData("Unit","ID",mysqli_result($result,$i,"Unite_win"),"Pays");
                    $Avion_win=GetData("Avion","ID",$Avion_eni,"Nom");
                    $Unite_win=GetData("Unit","ID",$Unite_eni,"Nom");
                    $Avion_loss=GetData("Avion","ID",$Avion_loose,"Nom");
                    $Unite_loss=GetData("Unit","ID",$Unite_loose,"Nom");
                    $Avion_img_win = 'images/avions/avion'.$Avion_eni.'.gif';
                    $Avion_img_loss = 'images/avions/avion'.$Avion_loose.'.gif';
                    $Avion_unit_win_img = 'images/unit/unit'.$Unite_eni.'p.gif';
                    $Avion_unit_loss_img = 'images/unit/unit'.$Unite_loose.'p.gif';
                    if(is_file($Avion_img_win))
                        $Avion_win="<img src='".$Avion_img_win."' title='".$Avion_win."'>";
                    if(is_file($Avion_img_loss))
                        $Avion_loss="<img src='".$Avion_img_loss."' title='".$Avion_loss."'>";
                    if(is_file($Avion_unit_win_img))
                        $Unite_win="<img src='".$Avion_unit_win_img."' title='".$Unite_win."'>";
                    if(is_file($Avion_unit_loss_img))
                        $Unite_loss="<img src='".$Avion_unit_loss_img."' title='".$Unite_loss."'>";
                    $mes.="<tr>
						<td>".$Date."</td>
						<td>".$Unite_loss."</td>
						<td>".$Avion_loss."</td>
						<td>".$Pilote_win."</td>
						<td>".$Avion_win."</td>
						<td>".$Unite_win."</td>
						<td><img src='".$Pays_win."20.gif'></td>
					</tr>";
                    $i++;
                }
                $mes.='</table>';
            }
        }
        else
            echo "<h6>A ce jour, ce pilote n'a pas encore été abattu lors d'un combat aérien.</h6>";
        include_once('./default_blank.php');
	}
}
