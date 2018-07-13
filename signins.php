<?
require_once('./jfv_inc_sessions.php');
session_unset();
session_destroy();

include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');

if(isset($_POST['email']))
{
	$login=Insec($_POST['email']);
}
else
{
	$login = "";
}
if(isset($_POST['password']))
{
	$Pwd=Insec($_POST['password']);
}
else
{
	$Pwd = "";
}
if(isset($_POST['name']))
{
	$Nom=Insec($_POST['name']);
}
else
{
	$Nom = "";
}
if(isset($_POST['country']))
{
	$Pays=Insec($_POST['country']);
}
else
{
	$Pays = "";
}

if(empty($login) OR empty($Pwd) OR empty($Nom) OR empty($Pays))
{
	$mes.="Veuillez remplir tous les champs du formulaire d'inscription!<br><a href='index.php?view=signin'><span>Cliquez ici pour recommencer</span></a>";
	$img = "<p><table align='center' border='1'><tr><td align='center'><img src='/aceofaces/images/tsss.jpg'></td></tr></table></p>";
}
else
{
	$Date=date('Y-m-d');

/*<form action='index.php?view=signinss' method='post'>
<input type='hidden' name='id_q' value="<?echo $id_q;?>">
<table cellspacing="3" cellpadding="2">
	<tr>
		<th>Escadrille</th>
		<td>
			<select name="Unite">
					<? DoSelect2("Unit", "ID", "Nom", "Nom", "Pays", $Pays, "Type", 1);?>
			</select>
		</td>
	</tr>
	<tr class="TitreBleu_bc">
		<th colspan="6">Photo</th>
	</tr>
			<?
			for ($i = 1; $i <= 8; $i++)
			{
				if($i % 2)
				{
			?>
			<tr>
			<?	}?>
			<td>
				<Input type='Radio' name='Photo' value='<? echo $i;?>'><img src="/aceofaces/images/pilote<?echo $Pays; echo $i;?>.jpg"><br>
			</td>
			<?	if(!$i&1)
				{
			?>
			</tr>
			<?
				}	
			}
			?>
	</tr>
	<hr>
	<tr>
		<td colspan="6"><input type='Submit' value='VALIDER'></td>
	</tr>
</table>
</form>
<?
}*/
	switch($Pays)
	{
		case 1 :
			$Unite = 192;
			$Unite_nom="Lehrgeschwader";
		break;
		case 2 :
			$Unite = 193;
			$Unite_nom="Operational Training Unit";
		break;
		case 3 :
			$Unite = 414;
			$Unite_nom="Ecole";
		break;
		case 4 :
			$Unite = 191;
			$Unite_nom="Réserve";
		break;
		case 6 :
			$Unite = 194;
			$Unite_nom="Ristrutturazione";
		break;
	}
	$con = dbconnecti();
	$query="INSERT INTO Joueur (login,Mdp,Nom,Pays,Engagement,Unit)";
	$query.="VALUES ('$login','$Pwd','$Nom','$Pays','$Date','$Unite')";
	$ok=mysqli_query($con, $query);
	mysqli_close($con);
	if($ok)
	{
		$mes.="Personnage créé avec succès!";
		$mes.="<br><br>Vous êtes versé automatiquement dans l'unité <b>".$Unite_nom."</b>";
		$img = "<p><table align='center' border='1'><tr><td align='center'><img src='/aceofaces/images/transfer_yes".$Pays.".jpg'></td></tr></table></p>";
	}
	else
	{
		$mes.="Erreur de création de personnage ".mysqli_error($con);
		//echo "Erreur de création de personnage ".mysqli_error($con);
	}
}
include_once('./index.php');
?>