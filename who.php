<?
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'] ==1)
{
	include_once('./jfv_include.inc.php');
	$Joueur=Insec($_GET['id']);
	$con=dbconnecti();
	$result=mysqli_query($con,"SELECT j.*,p.Nom as Pilote_nom,o.Nom as Officier_nom,
	(SELECT GROUP_CONCAT(DISTINCT mo.Sujet,' (',mo.`Date`,') = ',mo.Message ORDER BY mo.ID DESC SEPARATOR '<br>') FROM gnmh_aubedesaiglesnet3.Ada_Messages as mo WHERE mo.Expediteur=j.Officier_em AND mo.Exp_em=1 GROUP BY mo.Expediteur) as Sujet_o,
	(SELECT GROUP_CONCAT(DISTINCT mp.Sujet,' (',mp.`Date`,') = ',mp.Message ORDER BY mp.ID DESC SEPARATOR '<br>') FROM gnmh_aubedesaiglesnet3.Ada_Messages as mp WHERE mp.Expediteur=j.Pilote_id AND mp.Exp_em=3 GROUP BY mp.Expediteur) as Sujet_p
	FROM Joueur as j
	LEFT JOIN Pilote as p ON j.Pilote_id=p.ID
	LEFT JOIN Officier_em as o ON j.Officier_em=o.ID
	WHERE j.ID='$Joueur'");
	if($result)
	{
		$data=mysqli_fetch_array($result,MYSQLI_ASSOC);
        echo 	"<br>Login = ".$data['login'].
                "<br>Pwd = ".$data['Mdp'].
                "<br>Email = ".$data['adresse'].
                "<br>IP = ".$data['IP'].
                "<br>Dernière connexion = ".$data['Con_date'].
                "<br>Inscription = ".$data['Engagement'].
                "<br>Parrain = ".$data['Parrain'].
                "<br>Pilote = ".$data['Pilote_id']." ".$data['Pilote_nom'].
                "<br>Officier = ".$data['Officier'].
                "<br>Officier EM = ".$data['Officier_em']." ".$data['Officier_nom'].
                "<h3>Messages Off</h3>".$data['Sujet_o'].
                "<h3>Messages Pil</h3>".$data['Sujet_p'].
                "";
        if($data['Admin'] > 0)
            $Spec.="<br><b>Compte Admin</b>";
        if($data['Anim'] > 0)
            $Spec.="<br><b>Compte Animateur</b>";
        if($data['Encodage'] > 0)
            $Spec.="<br><b>Compte Encodeur</b>";
        if($data['Actif'] > 0)
            $Spec.="<br><b>Compte Désactivé</b>";

        $result_ip=mysqli_query($con,"SELECT COUNT(ID),PlayerID FROM gnmh_aubedesaiglesnet2.Connectes WHERE IP='".$data['IP']."' AND PlayerID !='".$data['ID']."' GROUP BY PlayerID");
        $result_proxy=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM gnmh_aubedesaiglesnet2.Connectes WHERE Proxy='".$data['IP']."'"),0);
        $result_pm=mysqli_query($con,"SELECT Date,Credits,Action FROM gnmh_aubedesaiglesnet2.Porte_Monnaie WHERE PlayerID='".$data['Pilote_id']."' ORDER BY Date DESC LIMIT 20");
        if($result_proxy)
            $Spec.='<br><b>Proxy</b> '.$result_proxy;
        if($result_ip)
        {
            while($dataip = mysqli_fetch_array($result_ip))
            {
                $Spec.='<br><b>Doublon IP '.$dataip[1].' => '.$dataip[0].'x</b>';
            }
            mysqli_free_result($result_ip);
        }
        if($result_pm)
        {
            while($datapm = mysqli_fetch_array($result_pm))
            {
                switch($datapm['Action'])
                {
                    //Action : 1=Mission,2=Temps Libre,3=Gestion,4=Décoration,5=Repos,6=Training,7=Mission Histo,
                    //8=Mission annulée,9=crash,10=garage,11=Mutation,12=Avion perso
                    case 1:
                        $Event_Type_txt="Mission";
                        break;
                    case 2:
                        $Event_Type_txt="Temps libre";
                        break;
                    case 3:
                        $Event_Type_txt="Action de gestion";
                        break;
                    case 4:
                        $Event_Type_txt="Décoration";
                        break;
                    case 5:
                        $Event_Type_txt="Repos";
                        break;
                    case 6:
                        $Event_Type_txt="Training";
                        break;
                    case 7:
                        $Event_Type_txt="Bombardement strat";
                        break;
                    case 8:
                        $Event_Type_txt="Mission annulée";
                        break;
                    case 9:
                        $Event_Type_txt="Crash";
                        break;
                    case 10:
                        $Event_Type_txt="Avion perso";
                        break;
                    case 11:
                        $Event_Type_txt="Demande de mutation";
                        break;
                    case 12:
                        $Event_Type_txt="Avion perso";
                        break;
                    case 13:
                        $Event_Type_txt="Equipement";
                        break;
                    case 14:
                        $Event_Type_txt="Postuler à un état-major";
                        break;
                    case 15:
                        $Event_Type_txt="Espionnage";
                        break;
                    case 90:
                        $Event_Type_txt="Avancement";
                        if($PlayerID !=1)
                            $afficher=false;
                        break;
                    case 91:
                        $Event_Type_txt="Reput";
                        if($PlayerID !=1)
                            $afficher=false;
                        break;
                    case 99:
                        $Event_Type_txt="CT du jour";
                        break;
                    default:
                        $Event_Type_txt="Debug";
                        break;
                }
                $Porte_Monnaie_txt.=$datapm['Date']." : <b>".$datapm['Credits']."</b> Credits Temps lors d'un(e) ".$Event_Type_txt."<br>";
            }
            mysqli_free_result($result_pm);
        }
        if($Porte_Monnaie_txt)
            $Spec.='<h2>Actions du pilote</h2>'.$Porte_Monnaie_txt;
        echo $Spec;
		mysqli_free_result($result);
		unset($data);
	}
}
else
	echo 'No!';