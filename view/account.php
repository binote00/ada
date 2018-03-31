<?php
/**
 * Created by PhpStorm.
 * User: JF
 * Date: 10-10-17
 * Time: 14:42
 */
if($Pilote_id){
    $pilot_profil='
    <div class="panel panel-war">
        <div class="panel-heading">Pilote</div>
        <div class="panel-body">
            <img class="d-block img-fluid mx-auto" src="'.$img_pilote.'" alt="Photo du profil de votre pilote">
            <p><img src="images/flag'.$Pays_pilote.'p.jpg"></p>
            <h5>'.$Nom.'</h5>
            <div class="panel-footer">
                <form action="../account_update.php" method="post">
                    <input type="hidden" name="reset" value="2">
                    <input class="btn btn-danger" type="submit" value="Désactiver">
                </form>
            </div>
        </div>
    </div>';
}
if($Officier_em){
    $off_profil='
    <div class="panel panel-war">
        <div class="panel-heading">Officier</div>
        <div class="panel-body">
            <img class="d-block img-fluid mx-auto" src="'.$img_off.'" alt="Photo du profil de votre pilote">
            <p><img src="images/flag'.$Pays_off_em.'p.jpg"></p>
            <h5>'.$Nom_off_em.'</h5>
        </div>
    </div>';
}
if($Officier_bonus){
    $off_profil_bonus='
    <div class="panel panel-war">
        <div class="panel-heading">Officier Premium</div>
        <div class="panel-body">
            <img class="d-block img-fluid mx-auto" src="'.$img_off_bonus.'" alt="Photo du profil de votre pilote">
            <p><img src="images/flag'.$Pays_off_bonus.'p.jpg"></p>
            <h5>'.$Nom_off_bonus.'</h5>
            <div class="panel-footer">
                <form action="../account_update.php" method="post">
                    <input type="hidden" name="reset" value="4">
                    <input class="btn btn-danger" type="submit" value="Désactiver">
                </form>
            </div>
        </div>
    </div>';
}
echo '<h1>Informations du Compte</h1>
    <div class="panel panel-war">
        <div class="panel-heading text-center"><a class="popup" href="#"><img src="images/flag'.$Pays.'p.jpg">
        <span>Changer de nation n\'est possible qu\'en désactivant votre compte
        <br>Cette action est irréversible et tous vos personnages seront définitivement perdus!
        </span></a></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6 col-xs-12">
                     <fieldset>
                         <label for="engage">Inscription</label>
                         <input class="form-control c-300" type="text" id="engage" value="'.$Engagement.'" disabled>
                         <label for="premium">Premium</label>
                         <input class="form-control c-300" type="text" id="premium" value="'.$Prem_date.'" disabled>
                         <label for="coop">Points de Coopération</label>
                         <input class="form-control c-300" type="text" id="coop" value="'.$Note.'" disabled>
                         <form action="../account_update.php" method="post">
                            <label for="login">Identifiant</label>
                            <input class="form-control c-300" type="text" id="login" name="login" value="'.$login.'" disabled>
                            <label for="email">Email</label>
                            <input class="form-control c-300" type="text" id="email" name="email" value="'.$Email.'">
                            <label for="password">Mot de passe</label>
                            <input class="form-control c-300" type="password" id="password" name="password" required>
                            <p><input class="btn btn-warning" type="submit" value="Modifier"></p>
                        </form>
                    </fieldset>                
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="panel panel-war">
                        <div class="panel-heading">Désactivation du compte</div>
                        <div class="panel-body">
                            <div class="alert alert-danger">Cette action rendra ce compte inaccessible, ainsi que tous les personnages de ce compte.<br><b>Attention !</b> cette action est définitive et irréversible!</div>
                            <form action="../account_update.php" method="post">
                                <input type="hidden" name="reset" value="5">
                                <input class="btn btn-danger" type="submit" value="Désactiver" onsubmit="return confirm(\'Etes vous certain de vouloir désactiver votre compte?\');">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-4 col-md-6 col-sm-12 text-center">'.$off_profil.'</div>
                <div class="col-lg-4 col-md-6 col-sm-12 text-center">'.$pilot_profil.'</div>
                <div class="col-lg-4 col-md-6 col-sm-12 text-center">'.$off_profil_bonus.'</div>            
            </div>
        </div>
    </div>
';