<?php
require_once './jfv_inc_sessions.php';
$OfficierEMID = $_SESSION['Officier_em'];
if (isset($_SESSION['AccountID']) AND $OfficierEMID > 0) {
    $country = $_SESSION['country'];
    include_once './jfv_include.inc.php';
    include_once './jfv_txt.inc.php';
    $Pilote = Insec($_POST['id']);
    if ($Pilote > 0) {
        $con = dbconnecti();
        $Premium = mysqli_result(mysqli_query($con, "SELECT Premium FROM Joueur WHERE ID='" . $_SESSION['AccountID'] . "'"), 0);
        $Credits = mysqli_result(mysqli_query($con, "SELECT Credits FROM Officier_em WHERE ID='" . $OfficierEMID . "'"), 0);
        $result = mysqli_query($con, "SELECT * FROM Pilote_IA WHERE ID='$Pilote'");
        if ($result) {
            $Data = mysqli_fetch_array($result, MYSQLI_ASSOC);
            $ID = $Data['ID'];
            $Pilote = $Data['Nom'];
            $Unite = $Data['Unit'];
            $Skill = $Data['Skill'];
            $Avancement = GetAvancement($Data['Avancement'], $country);
            if ($Premium) {
                $pilotes_txt .= "<tr><td>" . $Pilote . "</td><td title='" . $Avancement[0] . "'><img src='images/grades/grades" . $country . $Avancement[1] . ".png'></td><td>" . $Data['Reputation'] . "</td><td>" . $Data['Missions'] . "</td><td>" . $Data['Victoires'] . "</td><td><img src='images/skills/skill" . $Data['Skill'] . "p.png'></td><td>"
                    . floor($Data['Pilotage']) . "</td><td>" . floor($Data['Acrobatie']) . "</td><td>" . floor($Data['Bombardement']) . "</td><td>" . floor($Data['Tir']) . "</td><td>" . floor($Data['Tactique']) . "</td><td>" . floor($Data['Navigation']) . "</td><td>" . floor($Data['Vue']) . "</td><td>"
                    . $Data['Moral'] . "</td><td>" . $Data['Courage'] . "</td><tr>";
            } else {
                $Reputation = GetReputation($Data['Reputation'], $country);
                $pilotes_txt .= "<tr><td>" . $Pilote . "</td><td title='" . $Avancement[0] . "'><img src='images/grades/grades" . $country . $Avancement[1] . ".png'></td><td>" . $Reputation . "</td><td>" . $Data['Missions'] . "</td><td>" . $Data['Victoires'] . "</td><td><img src='images/skills/skill" . $Data['Skill'] . "p.png'></td><td>"
                    . GetSkillTxt($Data['Pilotage']) . "</td><td>" . GetSkillTxt($Data['Acrobatie']) . "</td><td>" . GetSkillTxt($Data['Bombardement']) . "</td><td>" . GetSkillTxt($Data['Tir']) . "</td><td>" . GetSkillTxt($Data['Tactique']) . "</td><td>"
                    . GetSkillTxt($Data['Navigation']) . "</td><td>" . GetSkillTxt($Data['Vue']) . "</td><td>"
                    . GetMoralTxt($Data['Moral']) . "</td><td>" . GetCourageTxt($Data['Courage']) . "</td><tr>";
            }
            mysqli_free_result($result);
        }
        $resultu = mysqli_query($con, "SELECT DISTINCT u.ID,u.Nom,u.Type,u.Pays,
        (SELECT COUNT(*) FROM Pilote_IA WHERE Unit=u.ID AND Skill='$Skill') AS Skill_nbr,
        (SELECT COUNT(*) FROM Pilote_IA WHERE Unit=u.ID AND Actif=1) AS Pil_nbr,
        (SELECT Avancement FROM Pilote_IA WHERE Unit=u.ID AND Actif=1 ORDER BY Avancement DESC LIMIT 1) AS Grade_max
        FROM Unit as u WHERE u.Pays='$country' AND u.Etat=1 AND u.Type<>8 
		AND ((SELECT COUNT(*) FROM Pilote_IA WHERE Unit=u.ID AND Actif=1)<20)
		ORDER BY u.Type DESC,u.Nom ASC");
        mysqli_close($con);
        if ($resultu) {
            while ($datau = mysqli_fetch_array($resultu, MYSQLI_ASSOC)) {
                $Skill_txt = false;
                $Unitem = $datau['ID'];
                $Type = GetAvionType($datau['Type']);
                $Unite_Nom = $datau['Nom'];
                if ($datau['Skill_nbr']) {
                    for ($i = 1; $i <= $datau['Skill_nbr']; $i++) {
                        $Skill_txt .= "<img src='images/skills/skill" . $Skill . "p.png'>";
                    }
                }
                if ($Data['Avancement'] >= $datau['Grade_max']) {
                    $arrow = '<span class="fa fa-arrow-circle-up text-success"></span>';
                } else {
                    $arrow = '<span class="fa fa-arrow-circle-down text-danger"></span>';
                }
                $Avancement_off = GetAvancement($datau['Grade_max'], $country);
                $units .= "<tr>
                            <th>" . Afficher_Icone($Unitem, $datau['Pays'], $Unite_Nom) . "<br>" . $Unite_Nom . "</th>
                            <td>" . $Type . "</td>
                            <td>" . $datau['Pil_nbr'] . "</td>
                            <td>" . $datau['Off_nbr'] . "<img src='images/grades/grades" . $country . $Avancement_off[1] . ".png' title='" . $Avancement_off[0] . "'>" . $arrow . "</td>
                            <td>" . $Skill_txt . "</td>
                            <td><input type='radio' name='unit' value='" . $Unitem . "'></td>
                        </tr>";
            }
            mysqli_free_result($resultu);
            unset($datau);
        }
        $titre = 'Mutation';
        $mes = "<h2>" . Afficher_Icone($Unite, $country) . "</h2><div style='overflow:auto;'><table class='table table-hover'><thead><tr><th>Nom</th><th>Grade</th><th>Reputation</th><th>Missions</th><th>Victoires</th><th>Compétence</th>
		<th>Pilotage</th><th>Acrobatie</th><th>Bombardement</th><th>Tir</th><th>Tactique</th><th>Navigation</th><th>Détection</th>
		<th>Moral</th><th>Courage</th></tr></thead><tbody>" . $pilotes_txt . "</tbody></table></div>";
        if ($Credits >= 1) {
            $mes .= "<h2>Nouvelle unité</h2><form action='index.php?view=em_ia_pil_move1' method='post'><input type='hidden' name='id' value='" . $ID . "'>
                    <div style='overflow:auto; height: 400px;'><table class='table table-striped table-condensed'>
                        <thead>
                        <tr>
                            <th>Unité</th>
                            <th>Type</th>
                            <th>Pilotes</th>
                            <th>Commandant</th>
                            <th>Compétences</th>
                            <th>Sélection</th>
                        </tr>
                        </thead>" . $units . "
                    </table></div>
            <img src='images/CT1.png' title='Montant en Crédits Temps que nécessite cette action'><input type='Submit' value='VALIDER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'></form>";
        } else
            $mes .= 'Vous manquez de temps pour cela!';
        $img = "<img src='images/pilotes" . $country . ".jpg'>";
        $menu = "<ul>
                <li class='inline'><a href='index.php?view=em_personnel_ia' class='btn btn-primary' title='Recherche'>Nouvelle recherche</a></li>
                <li class='inline'><a href='index.php?view=rapports' class='btn btn-default' title='Retour'>Retour menu</a></li>
                <li class='inline'><form action='index.php?view=em_ia' method='post' class='inline'><input type='hidden' name='Unit' value='" . $Unite . "'><input type='Submit' value='Retour unité' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'></form></li>
               </ul>";
        include_once './index.php';
    } else
        $mes = 'Tsss!';
} else
    echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";

