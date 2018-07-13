<?php
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['AccountID'];
if($PlayerID)
{
	$country=$_SESSION['country'];
	include_once('./jfv_include.inc.php');
	$ID=Insec($_GET['pilote']);
    if($ID >0 and is_numeric($ID)) {
        $mes = "<h1>Abattu par la DCA</h1>
	    <table class='table table-hover'>
		<thead><tr>
		<th>Date</th>
		<th>Unité</th>
		<th>Avion</th>
		<th>Lieu</th></tr></thead>";
        $con = dbconnecti();
        $ID = mysqli_real_escape_string($con, $ID);
        $result = mysqli_query($con, "SELECT * FROM DCA WHERE Joueur = '$ID' ORDER BY ID DESC");
        mysqli_close($con);
        if ($result) {
            $num = mysqli_num_rows($result);
            if ($num == 0)
                echo "<b>Heureusement, la DCA ne vous a pas encore abattu à ce jour!</b>";
            else {
                $i = 0;
                while ($i < $num) {
                    $Unit = mysqli_result($result, $i, "Unite");
                    $Avion = mysqli_result($result, $i, "Avion");
                    $Pays = mysqli_result($result, $i, "Pays");
                    $Date = mysqli_result($result, $i, "Date");
                    //$DCA=GetData("Armes","ID",mysqli_result($result,$i,"Arme"),"Nom");
                    $Lieu = GetData("Lieu", "ID", mysqli_result($result, $i, "Lieu"), "Nom");
                    $Avion_Nom = GetData("Avion", "ID", $Avion, "Nom");
                    $Unite = GetData("Unit", "ID", $Unit, "Nom");
                    $Avion_img = "images/avions/avion" . $Avion . ".gif";
                    $Avion_unit_img = "images/unit/unit" . $Unit . "p.gif";
                    if (is_file($Avion_img))
                        $Avion_Nom = "<img src='" . $Avion_img . "' title='" . $Avion_Nom . "'>";
                    if (is_file($Avion_unit_img))
                        $Unite = "<img src='" . $Avion_unit_img . "' title='" . $Unite . "'>";
                    $mes .= "<tr>
						<td>" . $Date . "</td>
						<td>" . $Unite . "</td>
						<td>" . $Avion_Nom . "</td>
						<td>" . $Lieu . "</td>
					</tr>";
                    $i++;
                }
                $mes .= '</table>';
                include_once('./default_blank.php');
            }
        } else
            echo "<h6>Heureusement, la DCA ne vous a pas encore abattu à ce jour!</h6>";
    }
}