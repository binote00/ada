<?php
require_once('./jfv_inc_sessions.php');
if($_SESSION['AccountID'])
{
	include_once('./jfv_include.inc.php');
	$Premium=GetData("Joueur","ID",$_SESSION['AccountID'],"Premium");
	if($Premium)
	{
		$PlayerID=$_SESSION['PlayerID'];
		if($_FILES['fichier']['tmp_name'] and $_FILES['fichier']['name'])
		{
			$ok=false;
			$target_path="uploads/Pilote/";
			$nom_fichier=Insec($PlayerID."_".$_FILES['fichier']['name']);
			$target_path.=basename($nom_fichier); 
			$fileinfo=pathinfo($nom_fichier);
			if($fileinfo['extension'] =="jpg")
			{
				if(!$_FILES['fichier']['size'])
					$mes.="<div class='alert alert-danger'>Vous n'avez sélectionné aucun fichier ou votre fichier est trop grand!<br>Votre fichier n'a pas été téléchargé!</div>";
				elseif($_FILES['fichier']['size'] <50000)
				{
					if(move_uploaded_file($_FILES['fichier']['tmp_name'],$target_path)) 
					{
						$imagedata=getimagesize($target_path);
						$width=$imagedata[0];
						$height=$imagedata[1];
						//$type=$imagedata[2];2=jpg
						if($width >225 or $height >300)
							$mes.="<div class='alert alert-danger'>L'image est trop grande!
							<br>La taille maximum autorisée est de 225 pixels de large et 300 pixels de haut.
							<br>Votre image a une taille de ".$width." pixels de large et ".$height." pixels de haut. 
							<br>Votre fichier n'a pas été téléchargé!</div>";
						else
						{
							$mes.="<div class='alert alert-success'>L'image a été téléchargée avec succès!</div>";
							rename($target_path,"uploads/Pilote/".$PlayerID."_photo.jpg");
							SetData("Pilote","Photo_Premium",1,"ID",$PlayerID);
							$mes.="<p><img src='uploads/Pilote/".$PlayerID."_photo.jpg'></p>";
						}
					}
					else
						$mes.="<div class='alert alert-danger'>Une erreur est survenue.<br>Votre fichier n'a pas été téléchargé!</div>";
				}
				else
					$mes.="<div class='alert alert-danger'>Le fichier est trop volumineux! La taille maximum autorisée est de 50Ko<br>Votre fichier n'a pas été téléchargé!</div>";
			}
			else
				$mes.="<div class='alert alert-danger'>Le fichier n'est pas une image jpg!<br>Votre fichier n'a pas été téléchargé!</div>";
			$mes.="<p><a class='btn btn-default' title='Retour au profil' href='index.php?view=user'>Retour au profil</a>";
			include_once('./index.php');
		}
		else
			echo "Tsss!";
	}
	else
		echo "Tsss!";
}
?>