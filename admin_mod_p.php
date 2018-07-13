<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_txt.inc.php');

if($_SESSION['AccountID'] ==1)
{
	$Pilote = Insec($_POST['joueur']);
	$Credits = Insec($_POST['ct']);
	$Avancement = Insec($_POST['grade']);
	$Reputation = Insec($_POST['reput']);
	$Ecole = Insec($_POST['ecole']);
	$Pr = Insec($_POST['premium']);
	$Pr_date = Insec($_POST['Premium_date']);
	if($Pilote >0)
	{
	}
	else {
	    dbconnect();
	    $result = $dbh->query("SELECT j.ID,j.login,j.adresse,j.IP,j.Premium,j.Pays,j.Actif,
        DATE_FORMAT(j.Premium_date,'%d-%m-%Y') as Premium_date,DATE_FORMAT(j.Con_date,'%d-%m-%Y') as Con_date,DATE_FORMAT(j.Engagement,'%d-%m-%Y') as Engagement,
        DATEDIFF(j.Con_date,CURDATE()) as Activite,
        p.Nom as Pilote,o.Nom as Officier
        FROM Joueur j
        LEFT JOIN Pilote p ON j.Pilote_id=p.ID
        LEFT JOIN Officier_em o ON j.Officier_em=o.ID
        ORDER BY Activite DESC, j.login ASC");
	    while($data = $result->fetchObject()){
	        $i++;
	        if($data->Actif ==1){
	            $txt_color = 'danger';
            }
            else{
                if($data->Activite > -7){
                    $txt_color = 'primary';
                }elseif($data->Activite > -15){
                    $txt_color = 'warning';
                }else{
                    $txt_color = 'danger';
                }
            }
            if(!$data->Premium){
	            $data->Premium_date = '';
            }
	        $joueurs.='<tr class="text-'.$txt_color.'">
                           <td>'.$i.'</td>
                           <td><a class="lien" href="who.php?id='.$data->ID.'" target="_blank">'.$data->login.'</a></td>
                           <td>'.$data->Activite.'</td>
                           <td><img src="images/'.$data->Pays.'20.gif"></td>
                           <td>'.$data->Pilote.'</td>
                           <td>'.$data->Officier.'</td>
                           <td>'.$data->Con_date.'</td>
                           <td>'.$data->Premium_date.'</td>
                           <td class="hidden-md-down">'.$data->Engagement.'</td>
                           <td class="hidden-md-down">'.$data->IP.'</td>
                           <td class="hidden-md-down">'.$data->adresse.'</td>
                       </tr>';
        }
        if($joueurs){
	        $table_joueurs = '<table class="table table-striped table-condensed table-dt">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Login</th>
                                    <th>Actif</th>
                                    <th>Pays</th>
                                    <th>Pilote</th>
                                    <th>Officier</th>
                                    <th>Con</th>
                                    <th>Premium</th>
                                    <th class="hidden-md-down">Create</th>
                                    <th class="hidden-md-down">IP</th>
                                    <th class="hidden-md-down">Email</th>
                                </tr>
                                </thead>'.$joueurs.'
                            </table>';
        }
        $admin_content='<h1>Comptes joueurs</h1>'.$table_joueurs;
        require_once '_admin.php';
        /*echo '<form action="?" method="post">
            <label class="form-control-label" for="joueur">Joueur</label>
            <select class="form-control" name="joueur" id="joueur">'.$joueurs.'</select>
            <input class="btn btn-danger" type="submit" value="valider" onclick="this.disabled=true;this.form.submit();">
        </form>';*/
	}
}

