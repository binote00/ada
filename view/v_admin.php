<?php
/**
 * User: Binote
 * Date: 30-11-17
 * Time: 09:52
 */
?>
<h1>Admin</h1>
<div class="row">
    <div class="col-xs-1 col-md-1">
        <?php PrintOnlinePlayers($_SESSION['AccountID']); ?>
    </div>
    <div class="col-xs-11 col-md-11">
        <div class="col-md-5 col-xs-11">
            <h2>Gestion</h2>
            <a href="../index.php?view=admin_mod_p" class="btn btn-primary">Gestion utilisateurs</a>
            <a href="../index.php?view=admin_gestion_em" class="btn btn-primary">Mutations</a>
            <a href="../index.php?view=tools" class="btn btn-primary">Tests</a>
            <a href="../index.php?view=db_pilotes" class="btn btn-primary">Encodage</a>
        </div>
        <div class="col-md-6 col-xs-12">
            <h2>Infos</h2>
            <a href="../index.php?view=admin_quotas" class="btn btn-primary">Quotas de Nations</a>
            <a href="../index.php?view=stats2" class="btn btn-primary">Troupes par Factions</a>
            <a href="../index.php?view=admin_cie_ia" class="btn btn-primary">Liste de Troupes</a>
        </div>
    </div>
</div>
<div><?= $admin_content ?></div>

