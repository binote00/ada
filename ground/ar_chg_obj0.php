<?php
require_once '../jfv_inc_sessions.php';
$OfficierEMID=$_SESSION['Officier_em'];
if($OfficierEMID >0)
{
    $armee=Insec($_POST['armee']);
    $country=$_SESSION['country'];
    include_once '../jfv_include.inc.php';
    $con=dbconnecti();
    $result=mysqli_query($con,"SELECT Front FROM Officier_em WHERE ID='$OfficierEMID'");
    if($result)
    {
        while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
        {
            $Front=$data['Front'];
        }
        mysqli_free_result($result);
    }
    $result2=mysqli_query($con,"SELECT Commandant FROM Pays WHERE Pays_ID='$country' AND Front='$Front'");
    if($result2)
    {
        while($data=mysqli_fetch_array($result2,MYSQLI_ASSOC))
        {
            $Commandant=$data['Commandant'];
        }
        mysqli_free_result($result2);
    }
    if($OfficierEMID ==$Commandant and $armee >0){
        $resulta=mysqli_query($con,"SELECT a.ID,a.Nom,a.Cdt,a.Front,a.Maritime,a.Objectif,a.limite_ouest,a.limite_nord,a.limite_est,a.limite_sud,l.Nom as Ville FROM Armee as a,Lieu as l WHERE a.Base=l.ID AND a.ID=$armee");
        if($resulta) {
            $data = mysqli_fetch_array($resulta);
            $modal_txt = '<table class="table table-striped"><thead><tr><th>Consigne</th><th>Lieu</th></tr></thead>';
            if ($data['Objectif']) {
                //$obj_txt = mysqli_result(mysqli_query($con,"SELECT Nom FROM Lieu WHERE ID=".$data['Objectif']),0);
                $modal_txt .= '<tr><th>Objectif</th><td>' . GetData("Lieu", "ID", $data['Objectif'], "Nom");
            } else
                $modal_txt .= '<tr><th>Objectif</th><td>Aucun';
            $modal_txt .= '<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value=' . $data['ID'] . '>
                            <select name="obj" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>' . $Lieux_obj . '</select>
                            <input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
            if ($data['limite_ouest'])
                $modal_txt .= '<tr><th>Limite Ouest</th><td>' . GetData("Lieu", "ID", $data['limite_ouest'], "Nom");
            else
                $modal_txt .= '<tr><th>Limite Ouest</th><td>Aucune';
            $modal_txt .= '<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value=' . $data['ID'] . '>
                            <select name="lo" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>' . $Lieux_obj . '</select>
                            <input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
            if ($data['limite_nord']) {
                $modal_txt .= '<tr><th>Limite Nord</th><td>' . GetData("Lieu", "ID", $data['limite_nord'], "Nom");
            } else
                $modal_txt .= '<tr><th>Limite Nord</th><td>Aucune';
            $modal_txt .= '<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value=' . $data['ID'] . '>
                            <select name="ln" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>' . $Lieux_obj . '</select>
                            <input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
            if ($data['limite_est'])
                $modal_txt .= '<tr><th>Limite Est</th><td>' . GetData("Lieu", "ID", $data['limite_est'], "Nom");
            else
                $modal_txt .= '<tr><th>Limite Est</th><td>Aucune';
            $modal_txt .= '<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value=' . $data['ID'] . '>
                            <select name="le" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>' . $Lieux_obj . '</select>
                            <input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
            if ($data['limite_sud'])
                $modal_txt .= '<tr><th>Limite Sud</th><td>' . GetData("Lieu", "ID", $data['limite_sud'], "Nom");
            else
                $modal_txt .= '<tr><th>Limite Sud</th><td>Aucune';
            $modal_txt .= '<form action="index.php?view=ar_chg_obj" method="post"><input type="hidden" name="Armee" value=' . $data['ID'] . '>
                            <select name="ls" class="form-control" style="width: 150px"><option value="0">Ne pas changer</option>' . $Lieux_obj . '</select>
                            <input type="submit" value="Changer" class="btn btn-warning btn-sm" onclick="this.disabled=true;this.form.submit();"></form></td></tr>';
            $modal_txt .= '</table>';
            echo '<h2>Objectifs de la ' . $data['Nom'] . '</h2>' . $modal_txt;
        }
        echo '<a href="index.php?view=ground_em" class="btn btn-default">Retour</a>';
    }
}