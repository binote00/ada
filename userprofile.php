<?
require_once('./jfv_inc_sessions.php');
$Pilote=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $Pilote >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_nomission.inc.php');
	include_once('./jfv_txt.inc.php');
	//$ip=$_SERVER['REMOTE_ADDR'];
	$Distances=$_SESSION['Distance'];
	$country=$_SESSION['country'];
	//RetireCandidat($Pilote);
	$con=dbconnecti();
	$resultj=mysqli_query($con,"SELECT Premium,Beta FROM Joueur WHERE ID='".$_SESSION['AccountID']."'");
	$result=mysqli_query($con,"SELECT *,DATE_FORMAT(`Engagement`,'%d-%m-%Y') AS Engagement FROM Pilote WHERE ID='$Pilote'");
	$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$Pilote' AND actif=0");
	$medals=mysqli_query($con,"SELECT Medal FROM Pil_medals WHERE PlayerID='$Pilote'");
	$Brevet_Pilote=mysqli_result(mysqli_query($con,"SELECT COUNT(ID) FROM Skills_Pil WHERE PlayerID='$Pilote' AND Skill=120"),0);
	mysqli_close($con);
	if($resultj)
	{
		while($dataj=mysqli_fetch_array($resultj,MYSQLI_ASSOC))
		{
			$Premium=$dataj['Premium'];
			$Beta=$dataj['Beta'];
		}
		mysqli_free_result($resultj);
	}
	if($results)
	{
		while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
		{
			$Skills_Pil[]=$data['Skill'];
		}
		mysqli_free_result($results);
	}
	if($medals)
	{
		while($datam=mysqli_fetch_array($medals,MYSQLI_ASSOC))
		{
			$medal_txt=GetMedal_Name($country,$datam['Medal']);
			$medals_txt.="<img title='".$medal_txt."' src='images/pmedal".$country.$datam['Medal'].".gif'>";
		}
		mysqli_free_result($medals);
	}
    if($Beta)
        $medals_txt.="<img src='images/pmedal" . $country . "13.gif'>";
    if($Brevet_Pilote)
        $medals_txt.="<img src='images/pmedal" . $country . "0.gif'>";
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$ID=$data['ID'];
			$Nom=$data['Nom'];
			$Country=$data['Pays'];
			$Unite=$data['Unit'];
			$Engagement=$data['Engagement'];
			$Reputation=$data['Reputation'];
			$Avancement=$data['Avancement'];
			$Courage=$data['Courage'];
			$Moral=$data['Moral'];
			$Commandement=floor($data['Commandement']);
			$Renseignement=floor($data['Renseignement']);
			$Duperie=floor($data['Duperie']);
			$Pilot=floor($data['Pilotage']);
			$Navig=floor($data['Navigation']);
			$Tir=floor($data['Tir']);
			$Endu=$data['Endurance'];
			$Vue=floor($data['Vue']);
			$Acrobat=floor($data['Acrobatie']);
			$Bomb=floor($data['Bombardement']);
			$Tactic=floor($data['Tactique']);
			$Gestion=floor($data['Gestion']);
			$Avion_Perso=$data['Avion_Perso'];
			$Proto=$data['Proto'];
			$Vic=$data['Victoires'];
			$Missions=$data['Missions'];
			$Raids=$data['Raids_Bomb'];
			$Raids_Nuit=$data['Raids_Bomb_Nuit'];
			$Dive=$data['Dive'];
			$kreta=$data['kreta'];
			$Photo=$data['Photo'];
			$Photo_Premium=$data['Photo_Premium'];
			$Actif=$data['Actif'];
			$Hide=$data['Hide'];
			$Dispo_Jour=$data['Dispo_Jour'];
			$Dispo_Sauf=$data['Dispo_Sauf'];
			$Dispo_Debut=$data['Dispo_Debut'];
			$Dispo_Fin=$data['Dispo_Fin'];
			$Credits_date=$data['Credits_date'];
			$Credits=$data['Credits'];
			$Missions_Jour=$data['Missions_Jour'];
			$Missions_Max=6-$data['Missions_Max'];
			$Equipage=$data['Equipage'];
			$Ailier=$data['Ailier'];
			$MIA=$data['MIA'];
			$Skill_Pts=$data['Skill_Pts'];
			$Skill_Cat=$data['Skill_Cat'];
			$Exp_Pts=$data['Exp_Pts'];
		}
		mysqli_free_result($result);
		unset($data);
		$NomUnite=GetData("Unit","ID",$Unite,"Nom");
		if(is_array($Skills_Pil))
		{
			foreach($Skills_Pil as $Skill_P)
			{
				if($Skill_P ==120)
					$Skills_txt.="<img src='images/skills/skill".$Skill_P.$Country."p.png'>";
				else
					$Skills_txt.="<img src='images/skills/skill".$Skill_P."p.png'>";
			}
			unset($Skills_Pil);
		}
		if($Skill_Cat)
		{
			if($Skill_Cat ==1)
				$Spec_txt="Combat";
			elseif($Skill_Cat ==2)
				$Spec_txt="Gestion";
			elseif($Skill_Cat ==3)
				$Spec_txt="Commandement";
			elseif($Skill_Cat ==4)
				$Spec_txt="Discrétion";
			else
				$Spec_txt="Erreur";
		}
		if($Exp_Pts >1000)
		{
			$Promo_Pts=floor($Exp_Pts/1000);
			$Demo_Pts=$Promo_Pts*1000;
			UpdateCarac($PlayerID,"Skill_Pts",$Promo_Pts);
			UpdateCarac($PlayerID,"Exp_Pts",-$Demo_Pts);
		}
		/*if($Ailier)
			$Ailier_Nom=GetData("Pilote_IA","ID",$Ailier,"Nom");*/
		/*if($Avion_Perso)
		{
			if(GetData("Avions_Persos","ID",$Avion_Perso,"Robustesse") <1)
			{
				SetData("Pilote","Avion_Perso",0,"ID",$Pilote);
				$Avion_Perso=0;
			}
			if($Avion_Perso >0)
			{
				if($Credits >0 and !$MIA)
					$Avion_Perso_Nom="<a href='index.php?view=garage' title='Accéder au hangar de votre avion personnel (coûte 1 Crédit Temps)'>".GetData("Avions_Persos","ID",$Avion_Perso,"Nom")."</a>";
				else
					$Avion_Perso_Nom=GetData("Avions_Persos","ID",$Avion_Perso,"Nom");
			}
		}
		else
		{
			$Avion=GetData("Unit","ID",$Unite,"Avion1");
			$Avion_Perso_Nom="<a href='avion.php?avion=$Avion' target='_blank'>".GetData("Avion","ID",$Avion,"Nom")."</a>";
		}*/
		$Grade=GetAvancement($Avancement,$Country);
		$Grade_img='images/grades/grades'.$Country.$Grade[1].'.png';
		if(!is_file($Grade_img))
			$Grade_img='images/grades/grades'.$Country.$Grade[1].'.gif';
		if(!is_file($Grade_img))
			$Grade_img='images/grades/grades'.$Country.$Grade[1].'.jpg';			
		//Horaires
		if($Dispo_Jour =="tous")
			$Dispos="Tous les jours";
		else
		{
			if($Dispo_Jour =="we")
				$Dispos="Week-end";
			elseif($Dispo_Jour == "sem")
				$Dispos="Semaine";
			elseif($Dispo_Jour == "lu")
				$Dispos="Lundi";
			elseif($Dispo_Jour == "ma")
				$Dispos="Mardi";
			elseif($Dispo_Jour == "me")
				$Dispos="Mercredi";
			elseif($Dispo_Jour == "je")
				$Dispos="Jeudi";
			elseif($Dispo_Jour == "ve")
				$Dispos="Vendredi";
			elseif($Dispo_Jour == "sa")
				$Dispos="Samedi";
			elseif($Dispo_Jour == "di")
				$Dispos="Dimanche";
		}
		if($Dispo_Sauf !="aucun")
		{
			if($Dispo_Sauf == "lu")
				$Dispos.= " sauf Lundi";
			elseif($Dispo_Sauf == "ma")
				$Dispos.= " sauf Mardi";
			elseif($Dispo_Sauf == "me")
				$Dispos.= " sauf Mercredi";
			elseif($Dispo_Sauf == "je")
				$Dispos.= " sauf Jeudi";
			elseif($Dispo_Sauf == "ve")
				$Dispos.= " sauf Vendredi";
			elseif($Dispo_Sauf == "sa")
				$Dispos.= " sauf Samedi";
			elseif($Dispo_Sauf == "di")
				$Dispos.= " sauf Dimanche";
		}
		$Dispos.='<br>de '.$Dispo_Debut.'h à '.$Dispo_Fin.'h';		
		if($Premium and $Photo_Premium ==1)
			$Photo="<img class='img-fluid' src='uploads/Pilote/".$PlayerID."_photo.jpg' width='100%'>";
		elseif($Premium)
			$Photo="<a href='upload_img.php'><img class='img-fluid' src='images/persos/pilote".$Country.$Photo.".jpg' title='Changer la photo de profil'></a>";
		else
			$Photo="<img class='img-fluid' src='images/persos/pilote".$Country.$Photo.".jpg' width='100%'>";
		if($Premium)
		{
			if($Avancement >500000)
				$Avancement_Max=1000000;
			elseif($Avancement >200000)
				$Avancement_Max=500000;
			elseif($Avancement >100000)
				$Avancement_Max=200000;
			elseif($Avancement >50000)
				$Avancement_Max=100000;
			elseif($Avancement >25000)
				$Avancement_Max=50000;
			elseif($Avancement >10000)
				$Avancement_Max=25000;
			elseif($Avancement >5000)
				$Avancement_Max=10000;
			elseif($Avancement >3000)
				$Avancement_Max=5000;
			elseif($Avancement >2000)
				$Avancement_Max=3000;
			elseif($Avancement >1500)
				$Avancement_Max=2000;
			elseif($Avancement >1000)
				$Avancement_Max=1500;
			elseif($Avancement >500)
				$Avancement_Max=1000;
			elseif($Avancement >300)
				$Avancement_Max=500;
			elseif($Avancement >200)
				$Avancement_Max=300;
			elseif($Avancement >100)
				$Avancement_Max=200;
			else
				$Avancement_Max=100;
			if($Reputation >100000)
				$Reputation_Max=1000000;
			elseif($Reputation >10000)
				$Reputation_Max=100000;
			elseif($Reputation >1000)
				$Reputation_Max=10000;
			elseif($Reputation >500)
				$Reputation_Max=1000;
			elseif($Reputation >50)
				$Reputation_Max=500;
			else
				$Reputation_Max=50;
			$Skill_Pts_Max=1000;
			$Avancement_Max_Bar=$Avancement_Max/($Avancement_Max/100);
			$Reputation_Max_Bar=$Reputation_Max/($Reputation_Max/100);
			$Skill_Pts_Max_Bar=$Skill_Pts_Max/($Skill_Pts_Max/100);
			$Avancement_Bar=$Avancement/($Avancement_Max/100);
			$Reputation_Bar=$Reputation/($Reputation_Max/100);
			$Skill_Pts_Bar=$Exp_Pts/($Skill_Pts_Max/100);
			/*if($PlayerID ==1 or $PlayerID ==2)
			{
				$PrintRep=$Reputation;
				$PrintAv=$Avancement;
				$PrintSk=$Exp_Pts;
			}
			else
			{
				$PrintRep='';
				$PrintAv='';
				$PrintSk='';
			}*/
		}
?>
<script type="text/javascript">
	/***********************************************
	* Cool DHTML tooltip script- © Dynamic Drive DHTML code library (www.dynamicdrive.com)
	* This notice MUST stay intact for legal use
	* Visit Dynamic Drive at http://www.dynamicdrive.com/ for full source code
	***********************************************/

	var offsetxpoint=-500 //Customize x offset of tooltip
	var offsetypoint=-50 //Customize y offset of tooltip
	var ie=document.all
	var ns6=document.getElementById && !document.all
	var enabletip=false
	if (ie||ns6)
	var tipobj=document.all? document.all["dhtmltooltip"] : document.getElementById? document.getElementById("dhtmltooltip") : ""

	function ietruebody(){
	return (document.compatMode && document.compatMode!="BackCompat")? document.documentElement : document.body
	}

	function ddrivetip(thetext, thecolor, thewidth, theheight, theoffsetx, theoffsety){
	if (ns6||ie){
	if (typeof thewidth!="undefined") tipobj.style.width=thewidth+"px"
	if (typeof theheight!="undefined") tipobj.style.height=theheight+"px"
	if (typeof thecolor!="undefined" && thecolor!="") tipobj.style.backgroundColor=thecolor
	tipobj.innerHTML=thetext
	enabletip=true
	return false
	}
	}

	function positiontip(e){
	if (enabletip){
	var curX=(ns6)?e.pageX : event.clientX+ietruebody().scrollLeft;
	var curY=(ns6)?e.pageY : event.clientY+ietruebody().scrollTop;
	//Find out how close the mouse is to the corner of the window
	var rightedge=ie&&!window.opera? ietruebody().clientWidth-event.clientX-offsetxpoint : window.innerWidth-e.clientX-offsetxpoint-20
	var bottomedge=ie&&!window.opera? ietruebody().clientHeight-event.clientY-offsetypoint : window.innerHeight-e.clientY-offsetypoint-20

	var leftedge=(offsetxpoint<0)? offsetxpoint*(-1) : -1000

	//if the horizontal distance isn't enough to accomodate the width of the context menu
	if (rightedge<tipobj.offsetWidth)
	//move the horizontal position of the menu to the left by it's width
	tipobj.style.left=ie? ietruebody().scrollLeft+event.clientX-tipobj.offsetWidth+"px" : window.pageXOffset+e.clientX-tipobj.offsetWidth+"px"
	else if (curX<leftedge)
	tipobj.style.left="5px"
	else
	//position the horizontal position of the menu where the mouse is positioned
	tipobj.style.left=curX+offsetxpoint+"px"

	//same concept with the vertical position
	if (bottomedge<tipobj.offsetHeight)
	tipobj.style.top=ie? ietruebody().scrollTop+event.clientY-tipobj.offsetHeight-offsetypoint+"px" : window.pageYOffset+e.clientY-tipobj.offsetHeight-offsetypoint+"px"
	else
	tipobj.style.top=curY+offsetypoint+"px"
	tipobj.style.visibility="visible"
	}
	}

	function hideddrivetip(){
	if (ns6||ie){
	enabletip=false
	tipobj.style.visibility="hidden"
	tipobj.style.left="-1000px"
	tipobj.style.backgroundColor=''
	tipobj.style.width=''
	}
	}

	document.onmousemove=positiontip
</script>
<h1>Profil</h1>
        <h2><?=$Nom;?></h2>
        <div class="row">
            <div class="col-sm-12 col-md-8 col-lg-6">
                <div class="row">
                    <div class="col-xs-12 col-sm-4">
                        <?=$Photo;?>
                    </div>
                    <div class="col-xs-12 col-sm-8">
                        <table class="table table-striped">
                            <tr>
                                <th>Engagement</th>
                                <td><?=$Engagement;?></td>
                            </tr>
                            <tr>
                                <th><a href="#" class="popup">Grade
                                        <span><b>Le grade</b>
                                        <ul>
                                            <li>permet d'accéder aux divers postes de commandement.</li>
                                            <li>permet d'accéder à des missions supplémentaires ainsi que plus d'options et de libertés concernant les missions.</li>
                                            <li>influence les actions de gestion.</li>
                                            <li>offre des options supplémentaires de temps libre.</li>
                                            <li>permet d'acquérir un avion personnalisé.</li>
                                        </ul>
                                            Le grade actuel de votre personnage est <b><?=$Grade[0];?></b>
                                        </span>
                                    </a>
                                </th>
                                <td>
                                    <img src="<?=$Grade_img;?>" alt="<?=$Grade[0];?>" title="<?=$Grade[0];?>">
                                </td>
                            </tr>
                            <tr>
                                <th><a href="#" class="popup">Réputation
                                        <span><b>Le Réputation</b>
                                        <ul>
                                            <li>influence en partie l'efficacité des actions de gestion (comme lors de la commande d'avions, de carburant ou de munitions).</li>
                                            <li>influence en partie l'efficacité des formations données à d'autres pilotes.</li>
                                            <li>influence certaines actions de temps libre (comme lors de la réception organisée par le Général ou interroger un prisonnier).</li>
                                            <li>a de l'importance lors de certaines situations de mission (comme lors de l'échange de prisonniers).</li>
                                            <li>a de l'importance lorsque votre pilote postule pour une fonction de staff, ou encore lorsqu'il tente de convaincre un pilote réputé d'être son ailier.</li>
                                            <li>conditionne l'accès à certaines pièces d'équipement.</li><li>permet d'acquérir un avion personnalisé.</li><li>facilite l'obtention des décorations.</li>
                                        </ul>
                                        </span>
                                    </a>
                                </th>
                                <td>
                                    <?echo GetReputation($Reputation,$Country);?>
                                </td>
                            </tr>
                            <tr>
                                <th>Unité</th>
                                <td><a href="#" class="popup"><?echo Afficher_Icone($Unite,$Country,$NomUnite);?><br><?=$NomUnite;?><span>L'unité de votre pilote</span></a></td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row">
                    <div class='col-xs-4'>
                        <h3><a href="#" class="popup">Moral<span>La capacité de votre pilote à croire en ses chances. Le Moral influe sur toutes les actions de vol.</span></a></h3><p class='btn btn-primary'><?=$Moral?></p></div>
                    <div class='col-xs-4'>
                        <h3><a href="#" class="popup">Courage<span>La capacité de votre pilote à faire face au danger et à garder son sang froid lors de situation critiques.</span></a></h3><p class='btn btn-primary'><?=$Courage?></p>
                    </div>
                    <div class='col-xs-4'>
                        <h3><a href="#" class="popup">Endurance<span>La capacité de votre pilote à enchainer les missions. Si la valeur d'Endurance arrive à 0, votre pilote doit se reposer pour récupérer.<br>Si votre pilote est blessé alors que son Endurance est à 0, il est alors blessé mortellement. S'ensuit une perte aléatoire de caractéristiques.</span></a></h3><p class='btn btn-primary'><?=$Endu?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-4 col-lg-6">
                <div class="row">
                    <h3>Compétences</h3>
                    <?if($Skill_Cat){?>
                        <div class="col-xs-12 col-lg-10">
                            <div class="panel panel-warning">
                                <div class="panel-heading">Compétences</div>
                                <div class="panel-body"><?=$Skills_txt;?></div>
                                <div class="panel-footer">Spécialisation <b><?=$Spec_txt;?></b></div>
                            </div>
                            <div class="inline">
                                <div class='btn btn-default btn-sm'><a href='index.php?view=points_skills'>Ajouter une compétence</a></div>
                                <div class='btn btn-primary btn-sm'><a href='index.php?view=aide_skills_p'>Liste des compétences</a></div>
                            </div>
                        </div>
                    <?}else{?>
                    Choix de la spécialisation <a href='#' class='popup'><img src='images/help.png'><span>La spécialisation dans une catégorie apporte une réduction de 50% sur le coût des compétences et permet d'accéder aux compétences exclusives de cette catégorie.
			Attention que le choix de la spécialisation est un choix <b>définitif</b>!<br>Pour plus d'informations sur les compétences, cliquez sur le lien ci-dessous.</span></a>
                    <form action='index.php?view=pil_trait' method='post'><input type='hidden' name='Pil' value='<?=$Pilote?>'>
                        <select name='Spec' class='form-control' style='width: 200px' title='Ce trait sera la spécialisation de votre pilote, pour la durée de sa carrière'>
                            <option value='0' selected>- Aucune -</option>
                            <optgroup label='Spécialisation'>
                                <option value='1'>Combat</option>
                                <option value='3'>Commandement</option>
                                <option value='4'>Discrétion</option>
                                <option value='2'>Gestion</option>
                            </optgroup>
                        </select><input type='Submit' value='VALIDER' class='btn btn-danger' onclick='this.disabled=true;this.form.submit();'>
                    </form>
                    <div class='btn btn-primary'><a href='index.php?view=aide_skills_p'>Liste des compétences</a></div>
                </div>
            <?}if($Premium) {
                $Bar_pc = round($Avancement_Bar, 1, PHP_ROUND_HALF_DOWN);
                $Bar_rep_pc = round($Reputation_Bar, 1, PHP_ROUND_HALF_DOWN);
                $Bar_sk_pc = round($Skill_Pts_Bar, 1, PHP_ROUND_HALF_DOWN); ?>
                <div class="row">
                    <div class="col-xs-8 col-lg-6">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>Premium</th>
                            </tr>
                            </thead>
                            <tr>
                                <th>Avancement</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" aria-valuenow="<?= $Avancement_Bar ?>"
                                             aria-valuemin="0" aria-valuemax="<?= $Avancement_Max_Bar ?>"
                                             style="width:<?= $Bar_pc ?>%;"><?= $Bar_pc ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Réputation</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-danger" role="progressbar"
                                             aria-valuenow="<?= $Reputation_Bar ?>" aria-valuemin="0"
                                             aria-valuemax="<?= $Reputation_Max_Bar ?>"
                                             style="width:<?= $Bar_rep_pc ?>%;"><?= $Bar_rep_pc ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th>Compétence</th>
                            </tr>
                            <tr>
                                <td>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-warning" role="progressbar"
                                             aria-valuenow="<?= $Skill_Pts_Bar ?>" aria-valuemin="0"
                                             aria-valuemax="<?= $Skill_Pts_Max_Bar ?>"
                                             style="width:<?= $Bar_sk_pc ?>%;"><?= $Bar_sk_pc ?>%
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <?}?>
            </div>
        </div>
        <div>
            <h3>Brevets et Décorations</h3><?=$medals_txt;?>
        </div>
<?  }
	else
	{
		echo '<h1>Un problème est survenu lors de la récupération des données de votre profil!</h1>';
		echo '<p>Si le problème persiste, contactez un administrateur via le forum</p>';
	}
}
else
	echo '<h1>Vous devez être connecté pour accéder à cette page!</h1>';
?>