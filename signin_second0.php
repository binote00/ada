<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');
$PlayerID = $_SESSION['PlayerID'];
$Pays = $_SESSION['country'];
$email = Insec($_POST['email']);
if($PlayerID and $email and $Pays) {
    $Date_Campagne = GetData("Conf_Update", "ID", 2, "Date");
    echo "<h1>Nation du second pilote</h1>
	<form action='index.php?view=signin_second' method='post'>
	<input type='hidden' name='email' value='" . $email . "'>
	<input type='hidden' name='pilote' value='" . $PlayerID . "'>
	<div style='overflow:auto; height: 350px;'><table><tr><td>";
    if (($Pays == 8 or $Pays == 7) and $Date_Campagne > '1941-01-01')
        echo "<Input title='Angleterre' type='Radio' name='country' value='2' style='margin-bottom: 5px; margin-right: 10px'><img src='images/flag2.jpg' title='Angleterre' align='middle'></td><td>";
    else
        echo "<Input title='Angleterre' type='Radio' name='country' value='2' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag2.jpg' title='Angleterre - inacessible' align='middle'></td><td>";
    echo "<Input title='France' type='Radio' name='country' value='4' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag4.jpg' title='France - inacessible' align='middle'></td><td>";
    if (($Pays == 2 or $Pays == 4 or $Pays == 7) and $Date_Campagne > '1941-01-01')
        echo "<Input title='URSS' type='Radio' name='country' value='8' style='margin-bottom: 5px; margin-right: 10px'><img src='images/flag8.jpg' title='URSS' align='middle'></td><td>";
    else
        echo "<Input title='URSS' type='Radio' name='country' value='8' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag8.jpg' title='URSS - inacessible' align='middle'></td><td>";
    if (($Pays == 2 or $Pays == 8) and $Date_Campagne > '1941-01-01')
        echo "<Input title='USA' type='Radio' name='country' value='7' style='margin-bottom: 5px; margin-right: 10px'><img src='images/flag7.jpg' title='USA' align='middle'>";
    else
        echo "<Input title='USA' type='Radio' name='country' value='7' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag7.jpg' title='USA - inacessible' align='middle'>";
    echo "</td></tr><tr><td>";
    if (($Pays == 6 or $Pays == 9 or $Pays == 20) and $Date_Campagne > '1941-01-01')
        echo "<Input title='Allemagne' type='Radio' name='country' value='1' style='margin-bottom: 5px; margin-right: 10px'><img src='images/flag1.jpg' title='Allemagne' align='middle'></td><td>";
    else
        echo "<Input title='Allemagne' type='Radio' name='country' value='1' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag1.jpg' title='Allemagne - inacessible' align='middle'></td><td>";
    echo "<Input title='Finlande - temporairement désactivé' type='Radio' name='country' value='20' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag20.jpg' title='Finlande - temporairement désactivé' align='middle'></td><td>";
    if (($Pays == 1 or $Pays == 9 or $Pays == 20) and $Date_Campagne > '1941-01-01')
        echo "<Input title='Italie' type='Radio' name='country' value='6' style='margin-bottom: 5px; margin-right: 10px'><img src='images/flag6.jpg' title='Italie' align='middle'></td><td>";
    else
        echo "<Input title='Italie' type='Radio' name='country' value='6' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag6.jpg' title='Italie - inacessible' align='middle'></td><td>";
    if (($Pays == 1 or $Pays == 6 or $Pays == 20) and $Date_Campagne > '1941-01-01')
        echo "<Input title='Japon' type='Radio' name='country' value='9' style='margin-bottom: 5px; margin-right: 10px'><img src='images/flag9.jpg' title='Japon' align='middle'>";
    else
        echo "<Input title='Japon' type='Radio' name='country' value='9' style='margin-bottom: 5px; margin-right: 10px' disabled><img src='images/flag9.jpg' title='Japon - inacessible' align='middle'>";
    echo "</td></tr></table></div><input type='Submit' value='VALIDER' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form>";
}