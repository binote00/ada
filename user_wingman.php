<?
require_once('./jfv_inc_sessions.php');
$PlayerID=$_SESSION['PlayerID'];
if(isset($_SESSION['AccountID']) AND $PlayerID >0)
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_txt.inc.php');
	$con=dbconnecti();
	$resultp=mysqli_query($con,"SELECT Reputation,Avancement FROM Pilote WHERE ID='$PlayerID'");
	mysqli_close($con);
	if($resultp)
	{
		while($data=mysqli_fetch_array($resultp, MYSQLI_ASSOC))
		{
			$Reput=$data['Reputation'];
			$Avancement=$data['Avancement'];
		}
		mysqli_free_result($resultp);
	}
	if($Reput >499 or $Avancement >499)
	{
		$mes=".";
		$query="SELECT * FROM Equipage WHERE ID_ref='$PlayerID'";
		$con=dbconnecti();
		$result=mysqli_query($con,$query);
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
		mysqli_close($con);
		if($results)
		{
			while($data=mysqli_fetch_array($results,MYSQLI_ASSOC))
			{
				$Skills_Pil[]=$data['Skill'];
			}
			mysqli_free_result($results);
		}
		if(is_array($Skills_Pil))
		{
			if(in_array(75,$Skills_Pil))
				$Skill=150;
			elseif(in_array(74,$Skills_Pil))
				$Skill=125;
			elseif(in_array(73,$Skills_Pil))
				$Skill=100;
			elseif(in_array(72,$Skills_Pil))
				$Skill=75;
		}
		if($result)
		{
			while($data=mysqli_fetch_array($result, MYSQLI_ASSOC))
			{
				$ID=$data['ID'];
				$Nom=$data['Nom'];
				$Country=$data['Pays'];
				$Engagement=$data['Engagement'];
				$Courage=$data['Courage'];
				$Moral=$data['Moral'];
				$Reputation=$data['Reputation'];
				$Avancement_e=$data['Avancement'];
				$Endu=$data['Endurance'];
				$Radio=floor($data['Radio']);
				$Navig=floor($data['Navigation']);
				$Tir=floor($data['Tir']);
				$Vue=floor($data['Vue']);
				$Bomb=floor($data['Bombardement']);
				$Radar=floor($data['Radar']);
				$Meca=floor($data['Mecanique']);
				$Aid=floor($data['Premiers_Soins']);
				$Trait_e=$data['Trait'];
				$Vic=$data['Victoires'];
				$Missions=$data['Missions'];
				$Abattu=$data['Abattu'];
				$medal0=$data['medal0'];
				$medal1=$data['medal1'];
				$medal2=$data['medal2'];
				$medal3=$data['medal3'];
				$medal4=$data['medal4'];
				$medal5=$data['medal5'];
				$medal6=$data['medal6'];
				$medal7=$data['medal7'];
				$medal8=$data['medal8'];
				$medal9=$data['medal9'];
				$medal10=$data['medal10'];
				$medal11=$data['medal11'];
			}
			mysqli_free_result($result);
			$Grade=GetAvancement($Avancement_e,$Country);
			switch($Trait_e)
			{
				case 1:
					$Trait_txt="Chanceux";
					$Trait_Title="Léger bonus dans toutes ses actions offensives";
				break;
				case 2:
					$Trait_txt="Nerfs d'acier";
					$Trait_Title="Courage minimum de 100 en toutes circonstances";
				break;
				case 3:
					$Trait_txt="Dur à cuir";
					$Trait_Title="Diminue de moitié ses chances de mourir ou d'être blessé";
				break;
				case 4:
					$Trait_txt="Esprit Vif";
					$Trait_Title="Double les effets de son entrainement";
				break;
				case 5:
					$Trait_txt="Ingénieux";
					$Trait_Title="Autorise des options supplémentaires pour votre avion personnel";
				break;
				case 6:
					$Trait_txt="Loyal";
					$Trait_Title="Obéira toujours aux ordres, même si effrayé ou démoralisé";
				break;
				case 7:
					$Trait_txt="Oeil de Lynx";
					$Trait_Title="Sa capacité de détection n'est pas affectée par la distance de la cible";
				break;
				case 8:
					$Trait_txt="Optimiste";
					$Trait_Title="Moral minimum de 100 en toutes circonstances";
				break;
			}
			if($Missions >2000 and !$medal6)
				SetData("Equipage","medal6",1,"ID",$ID);
			elseif($Missions >1000 and !$medal5)
				SetData("Equipage","medal5",1,"ID",$ID);
			elseif($Missions >500 and !$medal4)
				SetData("Equipage","medal4",1,"ID",$ID);
			elseif($Missions >200 and !$medal3)
				SetData("Equipage","medal3",1,"ID",$ID);
			elseif($Missions >100 and !$medal2)
				SetData("Equipage","medal2",1,"ID",$ID);
			elseif($Missions >50 and !$medal1)
				SetData("Equipage","medal1",1,"ID",$ID);
			elseif($Missions >25 and !$medal0)
				SetData("Equipage","medal0",1,"ID",$ID);
			if($Skill >=75)
			{
				$con=dbconnecti();
				$updateeq=mysqli_query($con,"UPDATE Equipage SET Radio='$Skill',Navigation='$Skill',Tir='$Skill',Vue='$Skill',Bombardement='$Skill',Radar='$Skill',Mecanique='$Skill',Premiers_Soins='$Skill' WHERE ID='$ID'");
				mysqli_close($con);
			}
	?>
	<h1><? echo $Nom; ?></h1>
	<table class='table'>
		<thead><tr><th>Photo</th><th>Grade</th><th>Engagement</th></tr></thead>
		<tr><td><img src="images/wingman<? echo $Country; ?>.jpg"></td><td><img src='images/grades/grades<? echo $Country.$Grade[1]; ?>.png' title='<? echo $Grade[0]; ?>'></td>
		<td><? echo $Engagement; ?></td></tr>
	</table>
	<table class="table">
		<thead><tr><th>Trait</th><th>Endurance <a href='#' class='popup'><img src='images/help.png'><span>Endurance doit être supérieur à 0 pour partir en mission</span></a></th><th>Courage <a href='#' class='popup'><img src='images/help.png'><span>Le Courage doit être supérieur à 0 pour partir en mission</span></a></th><th>Moral <a href='#' class='popup'><img src='images/help.png'><span>Le Moral doit être supérieur à 0 pour partir en mission</span></a></th></tr></thead>
		<tr>
			<td title="<?echo $Trait_Title;?>"><? echo $Trait_txt; ?></td>	
			<td title="La capacité à enchainer les missions"><? echo $Endu; ?></td>
			<td title="La capacité à faire face au danger et à garder son sang froid lors de situation critiques"><? echo $Courage; ?></td>
			<td title="La capacité à croire en ses chances"><? echo $Moral; ?></td>
		</tr>
	</table>
	<h2>Caractéristiques</h2>
	<table class="table">
		<thead><tr><th>Adresse au Tir</th><th>Bombardement</th><th>Détection</th><th>Mécanique</th><th>Navigation</th><th>Radar</th><th>Radio</th><th>Premiers Secours</th></tr></thead>
		<tr>
			<td title="La capacité à ajuster son tir lors des combats aériens et des attaques au sol, que ce soit à l'aide de mitrailleuses ou de canons"><? echo $Tir; ?></td>
			<td title="La capacité à ajuster sa visée lors du largage de bombes"><? echo $Bomb; ?></td>
			<td title="La capacité à détecter visuellement les cibles ennemies, aussi bien dans le ciel qu'au sol"><? echo $Vue; ?></td>
			<td title="Compétence spécifique permettant de réparer votre avion en mission"><? echo $Meca; ?></td>
			<td title="La capacité à se repérer et à diriger l'avion vers l'objectif"><? echo $Navig; ?></td>
			<td title="La capacité de détecter des appareils ennemis à l'aide d'un radar embarqué"><? echo $Radar; ?></td>
			<td title="La capacité de communiquer avec l'escorte (bonus tactique)"><? echo $Radio; ?></td>
			<td title="Compétence spécifique permettant de se soigner pendant une mission"><? echo $Aid; ?></td>	
		</tr>
	</table><?
			echo "<div id='profil_decorations'><h2>Brevets et Décorations</h2>";
			for($i=0;$i<=11;$i++)
			{
				$medal_txt=GetMedal_Name($Country,$i);
				$medal="medal".$i;
				if($$medal >0)
				{
					if($medal =="medal0")
						$mes.="<img title='$medal_txt' src='images/pmedal".$Country."99.gif'>";
					else
						$mes.="<img title='$medal_txt' src='images/pmedal".$Country.$i.".gif'>";
				}
			}
			echo $mes."</div><div class='alert alert-info'>Votre membre d'équipage vous aidera dans vos missions à chaque fois que vous utiliserez un avion multiplaces.<br>
			Effectuez des missions d'entrainement à bord d'un avion multi-places pour faire progresser votre membre d'équipage.</div>
			<p><form action='index.php?view=virer_equipage' method='post'>
			<a href='#' class='popup'><img src='images/help.png'><span>Cette action supprimera définitivement votre membre d'équipage!</span></a>
			<input type='Submit' class='btn btn-danger' value='Renvoyer' onclick='this.disabled=true;this.form.submit();'></form></p>";
		}
		else
		{
			echo "<h1>Un problème est survenu lors de la récupération des données de votre profil</span>";
			echo "<h1>Si le problème persiste, contactez un administrateur via le forum</span>";
		}
	}
	else
		echo "<h1>Votre réputation n'est pas suffisante pour commander à un équipage!</h1>";
}
else
	echo "<h1>Vous devez être connecté pour accéder à cette page!</h1>";
?>