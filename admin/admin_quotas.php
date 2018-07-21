<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
if(!$Admin)$Admin=GetData("Joueur","ID",$_SESSION['AccountID'],"Admin");
if($Admin ==1) {
    include_once('./jfv_ground.inc.php');
    $table = '';
    $countries = [1,6,2,4,7,8,9,3,5,10,17,18,19,20,35];
    $cats = [1,2,3,5,6,8,9,15,17,20,21,22,23,24,100];
    $lands40 = GetAllies('1940-10-01');
    $lands41 = GetAllies('1941-01-02');
    $allies40 = explode(",",$lands40[0]);
    $axe40 = explode(",",$lands40[1]);
    $allies41 = explode(",",$lands41[0]);
    $axe41 = explode(",",$lands41[1]);
    $total_allies_40 = false;
    $total_allies_41 = false;
    $total_axe_40 = false;
    $total_axe_41 = false;
    $total_allie = false;
    $total_axe = false;
    foreach($countries as $country){
        $table.='<tr><td><img src="'.$country.'20.gif"></td>';
        foreach($cats as $categorie){
            $quota40 = GetQuota($country, 0, '1940-01-01', $categorie);
            $quota41 = GetQuota($country, 0, '1941-01-02', $categorie);
            $table.='<td>'.$quota40.'/<b>'.$quota41.'</b></td>';
            if(in_array($country,$allies40)){
                $total_allies_40[$categorie]+=$quota40;
            }
            elseif(in_array($country,$axe40)){
                $total_axe_40[$categorie]+=$quota40;
            }
            if(in_array($country,$allies41)){
                $total_allies_41[$categorie]+=$quota41;
            }
            elseif(in_array($country,$axe41)){
                $total_axe_41[$categorie]+=$quota41;
            }
        }
        $table.='</tr>';
    }
    if($table){
        foreach($cats as $categorie){
            $total_allie.='<th>'.$total_allies_40[$categorie].'/'.$total_allies_41[$categorie].'</th>';
            $total_axe.='<th>'.$total_axe_40[$categorie].'/'.$total_axe_41[$categorie].'</th>';
        }
        $admin_content = '<h2>Quotas par Nation</h2>
            <table class="table table-striped table-dt">
                <thead>
                <tr>
                    <th>Pays</th>
                    <th>Camion</th>
                    <th>Bl.Léger</th>
                    <th>Bl.Moyen</th>
                    <th>Infanterie</th>
                    <th>Mitrailleuse</th>
                    <th>Artillerie</th>
                    <th>Canon AT</th>
                    <th>DCA</th>
                    <th>Sub</th>
                    <th>BB</th>
                    <th>CV</th>
                    <th>DD</th>
                    <th>CL</th>
                    <th>CA</th>
                    <th>Navires</th>
                </tr>
                </thead>'.$table.'
                <tr><th>Total Axe</th>'.$total_axe.'</tr>
                <tr><th>Total Allié</th>'.$total_allie.'</tr>
            </table>
        ';
    }
    require_once '_admin.php';
}
//echo var_dump(get_defined_vars());



