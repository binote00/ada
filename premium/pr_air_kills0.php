<?php
require_once __DIR__ . '/../inc/jfv_inc_sessions.php';
$PlayerID = $_SESSION['PlayerID'];
if ($PlayerID > 0) {
    require_once __DIR__ . '/../jfv_include.inc.php';
    $con = dbconnecti();
    $query = "SELECT DISTINCT ID,Nom FROM Pilote WHERE Actif=0 ORDER BY Nom ASC";
    $result = mysqli_query($con, $query);
    mysqli_close($con);
    if ($result) {
        while ($data = mysqli_fetch_array($result, MYSQLI_NUM)) {
            $pilotes .= "<option value='" . $data[0] . "'>" . $data[1] . "</option>";
        }
        mysqli_free_result($result);
    }
    ?>
    <h1>Kikitoutdur</h1>
    <form action="index.php?view=pr_air_kills" method="post">
        <input type='hidden' name='Off' value='<?= $PlayerID; ?>'>
        <label for="Off_eni">Officier contre lequel vous désirez comparer vos exploits</label>
        <select id="Off_eni" name="Off_eni" class="form-control" style="width: 300px">
            <?= $pilotes; ?>
        </select>
        <input type='submit' value='Valider' class='btn btn-default' onclick='this.disabled=true;this.form.submit();'>
    </form>
    <?}
    ?>