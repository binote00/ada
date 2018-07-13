<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 04-02-18
 * Time: 13:50
 */

if(isset($_SESSION['AccountID'])){
dbconnect();
$result = $dbh->query("SELECT msg,titre,color FROM Msg WHERE id=1");
$data = $result->fetchObject();
$msg_titre = $data->titre;
$msg_txt = $data->msg;
$msg_color = $data->color;
if($msg_color ==1){
    $sel_color = '<option value="1" selected>Vert</option>';
    $color_class = 'success';
}elseif($msg_color ==2){
    $sel_color = '<option value="2" selected>Bleu</option>';
    $color_class = 'primary';
}elseif($msg_color ==3){
    $sel_color = '<option value="3" selected>Orange</option>';
    $color_class = 'warning';
}elseif($msg_color ==4){
    $sel_color = '<option value="4" selected>Rouge</option>';
    $color_class = 'danger';
}
$msg_output = '<div class="alert alert-'.$color_class.'"><h4>'.$msg_titre.'</h4>'.$msg_txt.'</div>';
?>
<h1>Animation</h1>
<div class="row">
    <div class="col-xs-1 col-md-1">
        <?php PrintOnlinePlayers($_SESSION['AccountID']);?>
    </div>
    <div class="col-xs-11 col-md-11">
        <div class="col-md-5 col-xs-11">
            <h2>Modifier le message d'accueil du jeu</h2>
            <form action="../anim_mod_msg.php" method="post">
                <label for="titre">Titre</label>
                <input class="form-control" type="text" name="titre" id="titre" value="<?=$msg_titre?>">
                <label for="text">Texte</label>
                <textarea class="form-control" id="text" name="text" rows='5' cols='50'><?=$msg_txt?></textarea>
                <label for="color">Couleur</label>
                <select class="form-control" name="color" id="color">
                    <?=$sel_color?>
                    <option value="2">Bleu</option>
                    <option value="3">Orange</option>
                    <option value="4">Rouge</option>
                    <option value="1">Vert</option>
                </select>
                <p><input type="submit" value="modifier" class="btn btn-danger"></p>
            </form>
        </div>
    </div>
</div>
<h2>Aperçu</h2><div><?=$msg_output?></div>
<?} ?>