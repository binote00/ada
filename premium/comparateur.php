<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
if ($_SESSION['AccountID'] > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    include_once __DIR__ . '/../inc/jfv_nomission.inc.php';
    include_once __DIR__ . '/../view/menu_infos.php';
    include_once __DIR__ . '/../inc/jfv_access.php';
    if ($Premium) {
        ?>
        <form action="index.php?view=premium/comparateur1" method="post">
            <h2>Avions à comparer</h2>
            <img src="images/compare.jpg">
            <table class='table'>
                <thead>
                <tr>
                    <th>Avion 1</th>
                    <th>Avion 2</th>
                </tr>
                </thead>
                <tr>
                    <td><select name="avion1" class='form-control' style="width: 300px">
                            <?
                            if ($Admin) {
                                DoSelect("Avion", "ID", "Nom", "Nom", "Munitions2", 0);
                            } else {
                                DoSelect("Avion", "ID", "Nom", "Nom", "Etat", 1);
                            } ?>
                        </select>
                    <td><select name="avion2" class='form-control' style="width: 300px">
                            <?
                            if ($Admin) {
                                DoSelect("Avion", "ID", "Nom", "Nom", "Munitions2", 0);
                            } else {
                                DoSelect("Avion", "ID", "Nom", "Nom", "Etat", 1);
                            } ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type='submit' value='VALIDER' class='btn btn-default'
                   onclick='this.disabled=true;this.form.submit();'></form>
        <?
    } else
        echo "<img src='images/top_secret.gif'><div class='alert alert-danger'>Ces données sont classifiées.<br>Votre rang ne vous permet pas d'accéder à ces informations.</div>";
}