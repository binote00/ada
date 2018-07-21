<?php
/**
 * User: JF
 * Date: 01-07-18
 * Time: 07:33
 */

require_once('jfv_inc_sessions.php');
include_once('jfv_include.inc.php');
include_once('jfv_txt.inc.php');

if ($_SESSION['AccountID'] == 1) {
        dbconnect();
        $result = $dbh->query("SELECT j.ID,o.Nom,o.Pays,o.Front,o.Armee,o.Postuler,o.Actif,
        DATE_FORMAT(o.Credits_Date,'%d-%m-%Y') as Credits_Date,
        DATE_FORMAT(j.Con_date,'%d-%m-%Y') as Con_date,
        DATEDIFF(j.Con_date,CURDATE()) as Activite
        FROM Joueur j
        LEFT JOIN Officier_em o ON j.Officier_em = o.ID
        WHERE Mutation = 9999
        ORDER BY Activite DESC, o.Pays ASC, o.Nom ASC");
        while ($data = $result->fetchObject()) {
            $i++;
            if ($data->Actif == 1) {
                $txt_color = 'danger';
            } else {
                if ($data->Activite > -7) {
                    $txt_color = 'primary';
                } elseif ($data->Activite > -15) {
                    $txt_color = 'warning';
                } else {
                    $txt_color = 'danger';
                }
            }
            $joueurs .= '<tr class="text-' . $txt_color . '">
                           <td>' . $i . '</td>
                           <td><a class="lien" href="who.php?id=' . $data->ID . '" target="_blank">' . $data->Nom . '</a></td>
                           <td>' . GetPosteEM($data->Postuler) . '</td>
                           <td><img src="images/' . $data->Pays . '20.gif"></td>
                           <td>' . GetFront($data->Front) . '</td>
                           <td>' . $data->Armee . '</td>
                           <td class="hidden-md-down">' . $data->Activite . '</td>
                           <td class="hidden-md-down">' . $data->Con_date . '</td>
                           <td class="hidden-md-down">' . $data->Credits_Date . '</td>
                       </tr>';
        }
        if ($joueurs) {
            $table_joueurs = '<table class="table table-striped table-condensed table-dt">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Officier</th>
                                    <th>Poste</th>
                                    <th>Pays</th>
                                    <th>Front</th>
                                    <th>Armee</th>
                                    <th class="hidden-md-down">Actif</th>
                                    <th class="hidden-md-down">Con</th>
                                    <th class="hidden-md-down">Credits</th>
                                </tr>
                                </thead>' . $joueurs . '
                            </table>';
        }
        $admin_content = '<h1>Officiers postulant pour un poste à l\'EM</h1>' . $table_joueurs;
        require_once '_admin.php';
}