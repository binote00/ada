<? 
require_once('./jfv_inc_sessions.php');
$test=false;
if(!$test)
{
	$PlayerID=$_SESSION['PlayerID'];
	$OfficierID=$_SESSION['Officier'];
	$OfficierEMID=$_SESSION['Officier_em'];
}
if($test or ($_SESSION['AccountID'] and ($PlayerID or $OfficierID or $OfficierEMID)))
{
	include_once('./jfv_include.inc.php');
	include_once('./jfv_map.inc.php');
	include_once('./jfv_nav.inc.php');
	include_once('./jfv_txt.inc.php');
	include_once('./jfv_ground.inc.php');
	function GetMapLabel($MapID)
	{
		if($MapID ==1)return "Europe Sud-Est";
		elseif($MapID ==2)return "Med-Est";
		elseif($MapID ==3)return "Pacifique";
		elseif($MapID ==4)return "Europe Nord-Est";
		elseif($MapID ==5)return "Arctique";
		elseif($MapID ==7)return "USA";
		elseif($MapID ==8)return "Atlantique";
		elseif($MapID ==9)return "Alpes";
		elseif($MapID ==10)return "Asie";
		elseif($MapID ==11)return "URSS";
		elseif($MapID ==12)return "Med";
		elseif($MapID ==13)return "Sicile";
		elseif($MapID ==14)return "Gr�ce";
		elseif($MapID ==17)return "Yougoslavie";
		elseif($MapID ==19)return "Norv�ge";
		elseif($MapID ==20)return "Mer du Nord";
		elseif($MapID ==21)return "Europe Centre";
		elseif($MapID ==22)return "Belgique";
		elseif($MapID ==23)return "Allemagne";
		elseif($MapID ==24)return "Pays-Bas";
		elseif($MapID ==25)return "France";
		elseif($MapID ==26)return "Bretagne";
		elseif($MapID ==27)return "Normandie";
		elseif($MapID ==28)return "Nord";
		elseif($MapID ==29)return "Paris";
		elseif($MapID ==30)return "Angleterre";
		elseif($MapID ==301)return "Philippines";
		elseif($MapID ==302)return "Tha�lande";
		elseif($MapID ==303)return "Birmanie";
		elseif($MapID ==304)return "Malaisie";
		else 
			return "Europe Ouest";
	}
	if($test)
		$country=4;
	else
		$country=$_SESSION['country'];
	if(isset($_GET['map']))
		$Map=Insec($_GET['map']);
	else
		$Map=0;
	if(isset($_GET['mode']))
		$Mode=Insec($_GET['mode']); //99=search,1=g�n�ral,2=air,3=train,4=log,5=missions_air,6=conqu�tes,7=ValStrat,8=terre,9=fleuve,10=move,11=move_air,12/13=range_depots,14=meteo,15=traffic
	else
		$Mode=0;
	$iframe=Insec($_GET['frame']);
	if(!$iframe)$iframe=Insec($_POST['frame']);
	$Lieu_search=Insec($_POST['lieu']);
	//$Cookie=Insec($_POST['cook']);
	$Front_EM=12;
	if($Map ==99)$Map=0;
	if($iframe)$iframe_txt="&frame=1";
	if($Lieu_search >0)
	{
		$Mode=99;
		$Map=Insec($_POST['map']);
	}
	if(!$Lieu_search)$Lieu_search=Insec($_GET['cible']);
	if($Mode ==11)
	{
		$Unite_Type=Insec($_GET['u']);
		$Avion1=Insec($_GET['a1']);
		$Avion2=Insec($_GET['a2']);
		$Avion3=Insec($_GET['a3']);
		$LongPiste_mini=GetLongPisteMin($Unite_Type,$Avion1,$Avion2,$Avion3);
	}
	$h_menu="400px";
	$align_menu="left";
	if($Map ==1) //Sud-Est
	{
		$Decalage_Long=12.76;
		$Mult_Long=90.7;
		$Longitude_min=12;
		$Longitude_max=50;
		$Latitude_min=41.3;
		$Latitude_max=55;	
		$Decalage_Lat=52.35;
		$Mult_Lat=141;
		$map_file="carte_sud_est";
		$menu_maps_ids=array(0,2,4,11,21);
	}
	elseif($Map ==2) //Med-Est
	{
		$Decalage_Long=4.7;
		$Mult_Long=91;
		$Longitude_min=5;
		$Longitude_max=44;
		$Latitude_min=29;
		$Latitude_max=42;	
		$Decalage_Lat=41.55;
		$Mult_Lat=113;
		$map_file="carte_med_est";
		$menu_maps_ids=array(0,21,1,10,12,14,13);
	}
	elseif($Map ==3) //Pacifique
	{
		$Decalage_Long=66.5;
		$Mult_Long=22.4;
		$Longitude_min=67;
		$Longitude_max=225;
		$Latitude_min=-25;
		$Latitude_max=50;	
		$Decalage_Lat=44.8;
		$Mult_Lat=23.7;
		$map_file="carte_pacifique";
		$menu_maps_ids=array(7,10,303,304,301,302);
	}
	elseif($Map ==4) //Nord-Est
	{
		$Decalage_Long=1.7;
		$Mult_Long=90;
		$Longitude_min=4;
		$Longitude_max=38.5;
		$Latitude_min=50.5;
		$Latitude_max=60.5;	
		$Decalage_Lat=60.45;
		$Mult_Lat=161;
		$map_file="carte_nord_est";
		$menu_maps_ids=array(0,21,1,5,20,19,11);
	}
	elseif($Map ==5) //Arctique
	{
		$Decalage_Long=-58;
		$Mult_Long=11.2;
		$Latitude_min=54.5;
		$Latitude_max=80;
		$Decalage_Lat=88.5;
		$Mult_Lat=25;
		$map_file="carte_arctic";
		$menu_maps_ids=array(4,8,19,7,11);
	}
	elseif($Map ==7) //USA
	{
		$Decalage_Long=-140.5;
		$Mult_Long=17.6;
		$Latitude_min=26;
		$Latitude_max=70;
		$Decalage_Lat=58.5;
		$Mult_Lat=26.5;
		$map_file="carte_usa";
		$menu_maps_ids=array(3,8,11);
	}
	elseif($Map ==8) //Atlantique
	{
		$Decalage_Long=-98.5;
		$Mult_Long=18;
		$Latitude_min=30;
		$Latitude_max=64;
		$Mult_Lat=29;
		$map_file="carte_atlantic";
		$menu_maps_ids=array(0,12,20,7,11);
	}
	elseif($Map ==9) //Alpes
	{
		$Decalage_Long=4.6;
		$Mult_Long=125;
		$Longitude_min=4.6;
		$Longitude_max=15.5;
		$Latitude_min=43.15;
		$Latitude_max=48.2;	
		$Decalage_Lat=48.2;
		$Mult_Lat=180;
		$map_file="carte_alpes";
		$align_menu="right";
		$menu_maps_ids=array(0,21,23,25,12);
	}
	elseif($Map ==10) //Asie
	{
		$Decalage_Long=22.1;
		$Mult_Long=22.5;
		$Longitude_min=23;
		$Longitude_max=95;
		$Latitude_min=5;
		$Latitude_max=40;	
		$Decalage_Lat=38.5;
		$Mult_Lat=25;
		$map_file="carte_arab";
		$align_menu="right";
		$menu_maps_ids=array(2,3,11);
	}
	elseif($Map ==11) //URSS
	{
		$Decalage_Long=-10;
		$Mult_Long=15;
		$Longitude_min=13;
		$Longitude_max=70;
		$Latitude_min=40.3;
		$Latitude_max=70;	
		$Decalage_Lat=70;
		$Mult_Lat=20;
		$map_file="carte_rail";
		$menu_maps_ids=array(1,4,5,10);
	}
	elseif($Map ==12) //Med
	{
		$Decalage_Long=-10.2;
		$Mult_Long=91;
		$Longitude_min=-10;
		$Longitude_max=20;
		$Latitude_min=32.4;
		$Latitude_max=45.66;	
		$Decalage_Lat=45.7;
		$Mult_Lat=122;
		$map_file="carte_test_med";
		$menu_maps_ids=array(0,1,9,8,25,2,13);
	}
	elseif($Map ==13) //Sicile
	{
		$Decalage_Long=9.85;
		$Mult_Long=215;
		$Longitude_min=9.85;
		$Longitude_max=16.85;
		$Latitude_min=35.7;
		$Latitude_max=38.7;	
		$Decalage_Lat=38.7;
		$Mult_Lat=275;
		$map_file="carte_sicile";
		$menu_maps_ids=array(2,12);
	}
	elseif($Map ==14) //Gr�ce
	{
		$Decalage_Long=16;
		$Mult_Long=96;
		$Longitude_min=16;
		$Longitude_max=30;
		$Latitude_min=35;
		$Latitude_max=41.85;	
		$Decalage_Lat=41.85;
		$Mult_Lat=126;
		$map_file="carte_grece";
		$align_menu="right";
		$menu_maps_ids=array(1,2,12);
	}
	elseif($Map ==17) //Yougo
	{
		$Decalage_Long=9.8;
		$Mult_Long=111;
		$Longitude_min=9.8;
		$Longitude_max=26;
		$Latitude_min=40.6;
		$Latitude_max=46.7;	
		$Decalage_Lat=46.7;
		$Mult_Lat=156;
		$map_file="carte_yougo";
		$menu_maps_ids=array(1,2,12,14);
	}
	elseif($Map ==19) //Norv�ge
	{
		$Decalage_Long=-1;
		$Mult_Long=64;
		$Longitude_min=-1;
		$Longitude_max=20;
		$Latitude_min=63.4;
		$Latitude_max=70;	
		$Decalage_Lat=70;
		$Mult_Lat=135;
		$map_file="carte_norvege";
		$menu_maps_ids=array(4,5,8,20);
	}
	elseif($Map ==20) //Nord-Ouest
	{
		$Decalage_Long=-17.65;
		$Mult_Long=76.3;
		$Longitude_min=-20;
		$Longitude_max=16;
		$Latitude_min=53.8;
		$Latitude_max=65;	
		$Decalage_Lat=64.65;
		$Mult_Lat=143.45;
		$map_file="carte_nord_ouest_mini";
		$menu_maps_ids=array(0,4,30,5,8,19,24);
	}
	elseif($Map ==21) //Europe Centre
	{
		$Decalage_Long=5.6;
		$Mult_Long=74.5;
		$Longitude_min=5;
		$Longitude_max=30.99;
		$Latitude_min=41.9;
		$Latitude_max=51;	
		$Decalage_Lat=50.5;
		$Mult_Lat=115;
		$map_file="carte_europe_centre";
		$menu_maps_ids=array(0,4,1,9,12,2,23,22,25,24);
	}
	elseif($Map ==22) //Ouest Mai 1940
	{
		$Decalage_Long=-0.3;
		$Mult_Long=171;
		$Longitude_min=-0.4;
		$Longitude_max=10;
		$Latitude_min=48.5;
		$Latitude_max=52;	
		$Decalage_Lat=51.98;
		$Mult_Lat=276;
		$map_file="carte_ouest";
		$align_menu="right";
		$menu_maps_ids=array(0,20,21,22,23,24,25,28);
	}
	elseif($Map ==23) //Ouest Allemagne
	{
		$Decalage_Long=4.4;
		$Mult_Long=60;
		$Longitude_min=4.5;
		$Longitude_max=24.5;
		$Latitude_min=46.3;
		$Latitude_max=55;	
		$Decalage_Lat=55.2;
		$Mult_Lat=100;
		$map_file="carte_all";
		$align_menu="right";
		$menu_maps_ids=array(0,20,21,22,23,24,25);
	}
	elseif($Map ==24) //Holland-Allemagne
	{
		$Decalage_Long=3.3;
		$Mult_Long=175;
		$Longitude_min=3.5;
		$Longitude_max=13.6;
		$Latitude_min=50.6;
		$Latitude_max=54;	
		$Decalage_Lat=53.9;
		$Mult_Lat=275;
		$map_file="carte_holl";
		$menu_maps_ids=array(0,30,20,21,22,23,24,25);
	}
	elseif($Map ==25) //France
	{
		$Decalage_Long=-5.7;
		$Mult_Long=121;
		$Longitude_min=-6;
		$Longitude_max=10;
		$Latitude_min=42.8;
		$Latitude_max=51.1;	
		$Decalage_Lat=51.1;
		$Mult_Lat=181.4;
		$map_file="carte_france";
		$menu_maps_ids=array(0,9,22,23,24,26,27,28,29);
	}
	elseif($Map ==26)
	{
		$Decalage_Long=-5.5;
		$Mult_Long=257;
		$Longitude_min=-5.5;
		$Longitude_max=0.99;
		$Latitude_min=46.7;
		$Latitude_max=49;	
		$Decalage_Lat=49;
		$Mult_Lat=400;
		$map_file="carte_bretagne";
		$menu_maps_ids=array(0,8,25,27);
	}
	elseif($Map ==27)
	{
		$Decalage_Long=-2.8;
		$Mult_Long=370;
		$Longitude_min=-3;
		$Longitude_max=1.6;
		$Latitude_min=48.3;
		$Latitude_max=49.95;	
		$Decalage_Lat=49.95;
		$Mult_Lat=550;
		$map_file="carte_normandie";
		$menu_maps_ids=array(0,8,30,25,26,28,29);
	}
	elseif($Map ==28)
	{
		$Decalage_Long=1.05;
		$Mult_Long=365;
		$Longitude_min=1;
		$Longitude_max=5.6;
		$Latitude_min=49.8;
		$Latitude_max=51.36;	
		$Decalage_Lat=51.36;
		$Mult_Lat=590;
		$map_file="carte_nord";
		$menu_maps_ids=array(0,30,22,25,20,27,29,24);
	}
	elseif($Map ==29)
	{
		$Decalage_Long=0.75;
		$Mult_Long=350;
		$Longitude_min=0.75;
		$Longitude_max=5.5;
		$Latitude_min=47.8;
		$Latitude_max=49.6;	
		$Decalage_Lat=49.6;
		$Mult_Lat=500;
		$map_file="carte_paris";
		$align_menu="right";
		$menu_maps_ids=array(0,25,27,28);
	}
	elseif($Map ==30) //England
	{
		$Decalage_Long=-6.15;
		$Mult_Long=155;
		$Longitude_min=-6.15;
		$Longitude_max=2.4;
		$Latitude_min=49.8;
		$Latitude_max=53.7;	
		$Decalage_Lat=53.7;
		$Mult_Lat=240;
		$map_file="carte_england";
		$align_menu="right";
		$menu_maps_ids=array(8,0,20,25,27,28,24);
	}
	elseif($Map ==301) //Philippines
	{
		$Decalage_Long=113.5;
		$Mult_Long=61;
		$Longitude_min=110;
		$Longitude_max=135;
		$Latitude_min=4;
		$Latitude_max=19.5;	
		$Decalage_Lat=19.5;
		$Mult_Lat=61;
		$map_file="carte_philip";
		$align_menu="right";
		$menu_maps_ids=array(3,304,302);
	}
	elseif($Map ==302) //Tha�lande
	{
		$Decalage_Long=92.45;
		$Mult_Long=70;
		$Longitude_min=92.45;
		$Longitude_max=111;
		$Latitude_min=8;
		$Latitude_max=21.05;	
		$Decalage_Lat=21.05;
		$Mult_Lat=70;
		$map_file="carte_thai";
		$align_menu="right";
		$menu_maps_ids=array(3,303,304,301);
	}
	elseif($Map ==303) //Birmanie
	{
		$Decalage_Long=82.45;
		$Mult_Long=65;
		$Longitude_min=82.45;
		$Longitude_max=102.7;
		$Latitude_min=16.4;
		$Latitude_max=28.4;	
		$Decalage_Lat=28.4;
		$Mult_Lat=76;
		$map_file="carte_birmanie";
		$align_menu="right";
		$menu_maps_ids=array(3,304,302);
	}
	elseif($Map ==304) //Malaisie
	{
		$Decalage_Long=98.9;
		$Mult_Long=97;
		$Longitude_min=98.9;
		$Longitude_max=117;
		$Latitude_min=-0.94;
		$Latitude_max=7.8;	
		$Decalage_Lat=7.8;
		$Mult_Lat=95;
		$map_file="carte_malaisie";
		$align_menu="right";
		$menu_maps_ids=array(3,303,301,302);
	}
	elseif($Map ==0) //Ouest
	{
		$Decalage_Long=-10.75;
		$Mult_Long=121;
		$Longitude_min=-10;
		$Longitude_max=16;
		$Latitude_min=42.9;
		$Latitude_max=54.8;	
		$Decalage_Lat=55;
		$Mult_Lat=182;
		$map_file="carte_test";
		$menu_maps_ids=array(4,8,30,12,20,21,22,23,24,25);
	}
	if(is_array($menu_maps_ids))
	{
		foreach($menu_maps_ids as $menu_maps_id)
		{
			$menu_maps.="<a class='label label-danger' href='carte_ground.php?map=".$menu_maps_id.$iframe_txt."'>".GetMapLabel($menu_maps_id)."</a> ";
		}
		unset($menu_maps_ids);
	}
	if (stristr($_SERVER['HTTP_USER_AGENT'],"Android")
	|| strpos($_SERVER['HTTP_USER_AGENT'],"iPod")
	|| strpos($_SERVER['HTTP_USER_AGENT'],"iPhone"))
		$map_file.="_low";
	/*else
	{
		if($Cookie)setcookie("Map_Legend","hidden",3600,'/','aubedesaigles.net',false,true);
		if(isset($_COOKIE['Map_Legend']))
			$Legend_cook=$_COOKIE['Map_Legend'];
		if($Legend_cook !="hidden")
		{
			$h_menu="90%";
			//$cook_icon="<form action='carte_ground.php' method='post'><input type='hidden' name='map' value='".$Map."'><input type='hidden' name='cook' value='1'><input type='Submit' value='X' class='btn btn-danger btn-sm' onclick='this.disabled=true;this.form.submit();'></form>";
			$legende="<h4>L�gende</h4>".$cook_icon."
			<p class='text-left'><img src='images/map/lieu_depot0.png'> D�p�t<br><img src='images/map/lieu_gare0.png'> Gare<br>
			<img src='images/map/lieu_pont0.png'> Pont<br><img src='images/map/lieu_port0.png'> Port<br>
			<img src='images/map/icone_oil0.gif'> Raffinerie<br><img src='images/map/icone_usine.gif'> Usine<br><img src='images/map/lieu_usined0.png'> Usine/d�p�t<br>
			<img src='images/zone0.jpg'> Plaines<br><img src='images/zone1.jpg'> Collines<br><img src='images/zone2.jpg'> For�ts<br><img src='images/zone3.jpg'> Collines bois�es<br><img src='images/zone4.jpg'> Montagnes<br>	
			<img src='images/zone9.jpg'> Jungle<br><img src='images/zone11.jpg'> Marais<br><img src='images/zone8.jpg'> D�sert<br><img src='images/zone7.jpg'> Urbain<br>
			<img src='images/plage.jpg'> Plage<br><img src='images/strat1.png'> Valeur strat�gique<br><img src='images/map/lieu_fire.png'> Combat<br>	
			<img src='images/map_transit.png'> Ville Transit<br>
			<img src='images/map_arriere.png'> Base arri�re<br>
			<img src='images/map_gare_out.png'> Gare ou Port -50%<br>
			<img src='images/range50r.png'> A�rodrome -50%<br>
			<img src='images/range50b.png'> Escorte<br>
			<img src='images/range50.png'> Couverture<br>
			<img src='images/range50v.png'> Chasse de nuit<br>";
		}
	}*/
?>
	<html><head><title>L'Aube des Aigles : Carte</title>
	    <link rel="stylesheet" href="./css/lib/bootstrap.min.css">
        <link rel="stylesheet" href="./css/bootstrap-theme.css">
        <link rel="stylesheet" href="./css/main.css">
        <link rel="stylesheet" href="./css/map.css">
	<style>
		body {
			background-image:url(images/cartes/<?echo $map_file;?>.jpg);
			background-repeat:no-repeat;
			background-color: #fff;
		}
		.Menu_Pos {
		  top:10;
		  <?=$align_menu?>:10;
		  position: fixed;
		  _position: absolute;
		  z-index: 6;
		  width: 220px;
		  height:<?=$h_menu?>;
		  border : 1px solid black;
		  text-align: center;
		  overflow: auto;
		}
		<?
		$pays_icons=array(1,2,3,4,5,6,7,8,9,10,15,17,18,19,20,35,36);
		foreach($pays_icons as $key => $value)
		{
			echo ".lieu_oil".$value."{background-image: url(../images/map/icone_oil".$value.".png);width: 32px;height: 30px;}";
			echo ".lieu_usine".$value."{background-image: url(../images/map/icone_usine".$value.".png);width: 31px;height: 30px;}";
			echo ".lieu_air".$value."{background-image: url(../images/map/lieu_air".$value.".png);width: 35px;height: 18px;}";
			echo ".lieu_city".$value."{background-image: url(../images/map/lieu_city".$value.".png);width: 27px;height: 35px;}";
			echo ".lieu_depot".$value."{background-image: url(../images/map/lieu_depot".$value.".png);width: 35px;height: 24px;}";
			echo ".lieu_gare".$value."{background-image: url(../images/map/lieu_gare".$value.".png);width: 30px;height: 30px;}";
			echo ".lieu_piste".$value."{background-image: url(../images/map/lieu_piste".$value.".png);width: 30px;height: 16px;}";
			echo ".lieu_pistec".$value."{background-image: url(../images/map/lieu_pistec".$value.".png);width: 15px;height: 16px;}";
			echo ".lieu_pont".$value."{background-image: url(../images/map/lieu_pont".$value.".png);width: 30px;height: 24px;}";
			echo ".lieu_port".$value."{background-image: url(../images/map/lieu_port".$value.".png);width: 28px;height: 28px;}";
			echo ".lieu_portb".$value."{background-image: url(../images/map/lieu_portb".$value.".png);width: 28px;height: 28px;}";
			echo ".lieu_ports".$value."{background-image: url(../images/map/lieu_ports".$value.".png);width: 28px;height: 28px;}";
			echo ".lieu_route".$value."{background-image: url(../images/map/lieu_route".$value.".png);width: 30px;height: 25px;}";
			echo ".lieu_usined".$value."{background-image: url(../images/map/lieu_usined".$value.".png);width: 30px;height: 30px;}";
			echo ".lieu".$value."{background-image: url(../images/map/lieu".$value.".png);width: 25px;height: 25px;}";
		}
		?>
	</style>
	</head><body>
<?
	include_once('./jfv_inc_em.php');
	if($OfficierEMID >0)
	{
		$DB="Officier_em";
		$con=dbconnecti();
		$resulto=mysqli_query($con,"SELECT Front,Trait FROM Officier_em WHERE ID='$OfficierEMID'");
		//mysqli_close($con);
		if($resulto)
		{
			while($datao=mysqli_fetch_array($resulto, MYSQLI_ASSOC)) 
			{
				$Front_EM=$datao['Front'];
				$Trait==$datao['Trait'];
			}
			mysqli_free_result($resulto);
		}
		if($Front_EM !=12 or $Admin)$EM_access=true;
	}
	elseif($OfficierID >0)
	{
		$DB="Officier";
		$con=dbconnecti();
		$resulto=mysqli_query($con,"SELECT Front,Trait FROM Officier WHERE ID='$OfficierID'");
		//mysqli_close($con);
		if($resulto)
		{
			while($datao=mysqli_fetch_array($resulto, MYSQLI_ASSOC)) 
			{
				$Front_EM=$datao['Front'];
				$Trait_o==$datao['Trait'];
			}
			mysqli_free_result($resulto);
		}
		$OfficierEMID=$OfficierID;
	}
	elseif($PlayerID >0)
	{
		$DB="Pilote";
		$OfficierEMID=$PlayerID;
		$con=dbconnecti();
		$Front_EM=mysqli_result(mysqli_query($con,"SELECT Front FROM Pilote WHERE ID='$PlayerID'"),0);
		$results=mysqli_query($con,"SELECT Skill FROM Skills_Pil WHERE PlayerID='$PlayerID' AND actif=0");
		//mysqli_close($con);
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
			if(in_array(89,$Skills_Pil))
				$Chef_Reseau=true;
			if(in_array(113,$Skills_Pil))
				$EM_access=true;
		}
	}
	if($Lieu_search >0)
	{
		//$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		$resultls=mysqli_query($con,"SELECT DISTINCT Longitude,Latitude,Nom,Zone,NoeudR,NoeudF,Pays FROM Lieu WHERE ID='$Lieu_search'");
		//mysqli_close($con);
		if($resultls)
		{
			while($datals=mysqli_fetch_array($resultls, MYSQLI_ASSOC)) 
			{
				$Nom_Sel=$datals['Nom'];
				$Pays_Ori==$datals['Pays'];
				$Zone_calc=$datals['Zone'];
				$longit_base=$datals['Longitude'];
				$latit_base=$datals['Latitude'];
				$NoeudR_calc=$datals['NoeudR'];
				$NoeudF_calc=$datals['NoeudF'];
			}
			mysqli_free_result($resultls);
		}
		$Lat_Sel=($Decalage_Lat-$latit_base)*$Mult_Lat;
		$Long_Sel=($longit_base-$Decalage_Long)*$Mult_Long;
		if($Mode ==11 and $DB =="Officier_em")
		{
			if($Unite_Type ==6 or $Unite_Type ==9 or $Unite_Type ==11)
				$Limite=2000;
			elseif($longit_base >235)
				$Limite=1500;
			elseif($longit_base <-8)
				$Limite=1250;
			else
				$Limite=1000;
			if($longit_base >67)$Limite*=2;
			if($Front_EM ==99)$Limite*=1.5;
		}
		elseif($Mode ==10 and ($OfficierID >0 or $OfficierEMID >0))
		{
			$Reg=Insec($_GET['reg']);
			$Rasputitsa=false;
			$Merzlota=false;
			$Mousson=false;
			$Mois=substr($Date_Campagne,5,2);
			if(($Pays_Ori ==8 or $Pays_Ori ==20) and ($Mois ==11 or $Mois ==3)) //Rasputitsa
				$Rasputitsa=true;
			elseif(($longit_base <=90 and ($Mois ==7 or $Mois ==8)) or ($longit_base >90 and ($Mois ==8 or $Mois ==9)))
				$Mousson=true;
			if(($Pays_Ori ==8 or $Pays_Ori ==20 or $Front_EM ==5) and ($Mois ==12 or $Mois ==1 or $Mois ==2))$Merzlota=true;
			/*if($OfficierID >0)
			{
				//$con=dbconnecti();
				$resultr=mysqli_query($con,"SELECT Vehicule_ID,Placement,Lieu_ID FROM Regiment WHERE Officier_ID='$OfficierID' AND Vehicule_Nbr >0");
				//mysqli_close($con);
				if($resultr)
				{
					while($data=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
					{
						$Vehicule=$data['Vehicule_ID'];
						$Placement=$data['Placement'];
						$Cible=$data['Lieu_ID'];
						//$con=dbconnecti();
						$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
						$result2=mysqli_query($con,"SELECT Fuel,mobile,Type FROM Cible WHERE ID='$Vehicule'");
						//mysqli_close($con);
						if($result2)
						{
							while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								if($NoeudR_calc >0 and $Placement ==2 and !$Rasputitsa and !$Mousson and !$Enis)$Zone_calc=0;
								$data2['Fuel']=Get_LandSpeed($data2['Fuel'],$data2['mobile'],$Zone_calc,0,$data2['Type']);
								if($data2['mobile'] ==3 or $data2['Type'] ==6)$Forcee=true;
								if($Skill4 ==100 and $Zone_base ==8)$data2['Fuel']*=2; //guerre du d�sert									
								$Autonomie[]=$data2['Fuel'];
								$Mobile_t[]=$data2['mobile'];
							}
							mysqli_free_result($result2);
						}
					}
					mysqli_free_result($resultr);
				}
				if($Autonomie)$Autonomie_Min=min($Autonomie);
				if($Mobile_t)$Mobile_Min=min($Mobile_t);
				unset($Autonomie);
				unset($Mobile_t);				
				$Autonomie_Max=150;
				$Range_train=200;
			}
			else*/if($OfficierEMID >0 and $Reg >0)
			{
				$resultr=mysqli_query($con,"SELECT Vehicule_ID,Placement,Lieu_ID,Matos FROM Regiment_IA WHERE ID='$Reg'");
				if($resultr)
				{
					while($data=mysqli_fetch_array($resultr,MYSQLI_ASSOC))
					{
						$Vehicule=$data['Vehicule_ID'];
						$Placement=$data['Placement'];
						$Cible=$data['Lieu_ID'];
						$Matos=$data['Matos'];
						//$con=dbconnecti();
						$Enis=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction<>'$Faction' AND r.Lieu_ID='$Cible' AND r.Placement='$Placement' AND r.Vehicule_Nbr >0"),0);
						$result2=mysqli_query($con,"SELECT Fuel,mobile,Type FROM Cible WHERE ID='$Vehicule'");
						//mysqli_close($con);
						if($result2)
						{
							while($data2=mysqli_fetch_array($result2,MYSQLI_ASSOC))
							{
								if($NoeudR_calc >0 and $Placement ==2 and !$Rasputitsa and !$Mousson and !$Enis)$Zone_calc=0;
								$data2['Fuel']=Get_LandSpeed($data2['Fuel'],$data2['mobile'],$Zone_calc,0,$data2['Type']);
								if($data2['mobile'] ==3 or $data2['Type'] ==6)$Forcee=true;
								$Autonomie_Min=$data2['Fuel'];
								$mobile=$data2['mobile'];
								$Type_Veh=$data2['Type'];
							}
							mysqli_free_result($result2);
						}
					}
					mysqli_free_result($resultr);
				}
			}
		}
	}
	else
	{
		//$con=dbconnecti();
		$Faction=mysqli_result(mysqli_query($con,"SELECT Faction FROM Pays WHERE ID='$country'"),0);
		$Date_Campagne=mysqli_result(mysqli_query($con,"SELECT `Date` FROM Conf_Update WHERE ID=2"),0);
		//mysqli_close($con);
	}
	if($Mode ==1 or $Mode ==5 or $Mode ==8 or $Mode ==10)
		$query1_add="";
	elseif($Mode ==2)
	{
		if($Admin)
			$query1_add=" AND l.BaseAerienne >0";
		else
			$query1_add=" AND l.BaseAerienne >0 AND (p.Faction='$Faction' OR l.Recce=1)";
	}
	elseif($Mode ==11)
		$query1_add=" AND l.BaseAerienne >0 AND p.Faction='$Faction'";
	elseif($Mode ==3)
		$query1_add=" AND (l.NoeudF_Ori >0 OR l.Port_Ori >0)";
	elseif($Mode ==4)
		$query1_add=" AND l.ValeurStrat >3";
	elseif($Mode ==6)
		$query1_add=" AND l.Zone<>6";
	elseif($Mode ==7)
		$query1_add=" AND l.ValeurStrat >0";
	elseif($Mode ==9)
		$query1_add=" AND (l.Pont_Ori >0 OR l.Fleuve >0)";
	elseif($Mode ==12 or $Mode ==13)
		$query1_add=" AND ((l.ValeurStrat >3 AND p.Faction='$Faction') OR l.ID=".$Lieu_search.")";
	if($Map ==7)
		$query1_long="(l.Longitude <-50 OR l.Longitude >200)";
	elseif($Map ==8)
		$query1_long="l.Longitude <3.5 AND (l.Port_Ori>0 OR l.ValeurStrat>6 OR l.Zone=6 OR l.Longitude<-30)";
	elseif($Map ==5)
		$query1_long="l.Longitude <100 AND l.Latitude >54.5 AND (l.Port_Ori>0 OR l.ValeurStrat>3 OR l.Zone=6 OR l.Latitude >67 OR l.Longitude <=-37)";
	elseif($Map ==11)
	{
		$query1_long="(l.Longitude BETWEEN ".$Longitude_min." AND ".$Longitude_max.")";
		$query1_add=" AND (l.ValeurStrat>3 OR (l.Longitude >39 AND l.Latitude >51 AND l.ValeurStrat>1))";
	}
	elseif($Map ==10 or $Map ==23)
	{
		$query1_long="(l.Longitude BETWEEN ".$Longitude_min." AND ".$Longitude_max.")";
		if($Mode !=9)$query1_add=" AND (l.NoeudF_Ori>0 OR l.Port_Ori>0 OR l.ValeurStrat>3 OR l.Zone=6)";
	}
	else
		$query1_long="(l.Longitude BETWEEN ".$Longitude_min." AND ".$Longitude_max.")";
	//$con=dbconnecti();
	if(!$test)
	$resultp=mysqli_query($con,"SELECT Avancement,Admin FROM $DB WHERE ID='$OfficierEMID'");
	$result=mysqli_query($con,"SELECT p.Faction,l.ID,l.Nom,l.Longitude,l.Latitude,l.Pays,l.Meteo,l.Meteo_Hour,l.Industrie,l.NoeudR,l.NoeudF_Ori,l.NoeudF,l.Port_Ori,l.Port,l.Port_level,l.Pont_Ori,l.Last_Attack,l.Oil,l.Zone,l.Recce,l.BaseAerienne,l.QualitePiste,l.LongPiste,l.Tour,l.ValeurStrat,l.Fortification,l.Flag,l.Flag_Route,l.Flag_Gare,l.Flag_Pont,l.Flag_Port,l.Flag_Plage,l.Flag_Radar,l.Flag_Usine,l.Flag_Air,l.Plage,l.Fleuve,l.Detroit,l.Recce_mines_m_ax,l.Recce_mines_m_al
	FROM Lieu as l,Pays as p WHERE l.Flag=p.ID AND ".$query1_long." AND (l.Latitude BETWEEN $Latitude_min AND $Latitude_max)".$query1_add." ORDER BY l.Nom ASC");
	//mysqli_close($con);
	if($resultp)
	{
		while($datap=mysqli_fetch_array($resultp,MYSQLI_ASSOC))
		{
			$Avancement=$datap['Avancement'];
			$Admin=$datap['Admin'];
		}
		mysqli_free_result($resultp);
	}
	if($result)
	{
		while($data=mysqli_fetch_array($result,MYSQLI_ASSOC))
		{
			$Lieux.="<option value='".$data['ID']."'>".$data['Nom']."</option>";
			$NoeudR_txt="";
			$NoeudF_txt="";
			$Industrie_txt="";
			$Pont_txt="";
			$Port_txt="";
			$Icone_txt="";
			$Base_txt="";
			$Flags="";
			$Info_long=60; 
			$icone_lock=false;
			$dest_train_ok=false;
			$dest_ok=false;
			$dest_eni=false;
			$dest_air=false;
			$Pays=GetNation($data['Pays']);
			$Zone=$data['Zone'];
			if($data['Recce'] or $data['Flag'] ==$country or $Admin or $Chef_Reseau)
				$adminl=true;
			else
				$adminl=false;
			$Front=GetFrontByCoord(0,$data['Latitude'],$data['Longitude']);
			$today=getdate();
			if(!$data['Meteo_Hour'] or ($today['hours'] >$data['Meteo_Hour']+2))
			{
				$Saison=GetSaison($Date_Campagne);
				$Previsions_temp=GetMeteo($Saison,$data['Latitude'],$data['Longitude']);
				$Meteo=$Previsions_temp[1];
				//$con=dbconnecti();
				$setmeteo=mysqli_query($con,"UPDATE Lieu SET Meteo='".$Meteo."',Meteo_Hour='".$today['hours']."' WHERE ID='".$data['ID']."'");
				//mysqli_close($con);
				unset($Previsions_temp);
			}
			else
				$Meteo=$data['Meteo'];
			if($Map ==8)
			{
				if($data['Latitude'] <35)
					$Decalage_Lat=59;
				elseif($data['Latitude'] <40)
					$Decalage_Lat=60;
				elseif($data['Latitude'] <45)
					$Decalage_Lat=60.5;
				elseif($data['Latitude'] <52)
					$Decalage_Lat=61.5;
				else
					$Decalage_Lat=61;
			}
			elseif($Map ==7)
				if($data['Longitude'] >200)$data['Longitude']-=360;
			if($Map ==0)
			{
				if($data['Latitude'] >53.23)
					$latit=(54.9-$data['Latitude'])*182;
				else
					$latit=(55.25-$data['Latitude'])*180;
			}
			elseif($Map ==11)
				$latit=($Decalage_Lat-$data['Latitude'])*($Mult_Lat+($data['Latitude']/4.2)+(($data['Latitude']-50)/5));			
			else
				$latit=($Decalage_Lat-$data['Latitude'])*$Mult_Lat;
			$longit=($data['Longitude']-$Decalage_Long)*$Mult_Long;
			if($data['Last_Attack'] ==$Date_Campagne or $data['Recce'])
				$icone_lock=true;
			elseif($data['Flag'] ==$country and $Admin)
			{
				if(($data['Flag'] !=$data['Flag_Route'] and $data['Flag_Route'] >0 and $data['NoeudR'])
					or ($data['Flag'] !=$data['Flag_Air'] and $data['Flag_Air'] >0 and $data['BaseAerienne'])
					or ($data['Flag'] !=$data['Flag_Port'] and $data['Flag_Port'] >0 and $data['Port_Ori'])
					or ($data['Flag'] !=$data['Flag_Pont'] and $data['Flag_Pont'] >0 and $data['Pont_Ori'])
					or ($data['Flag'] !=$data['Flag_Gare'] and $data['Flag_Gare'] >0 and $data['NoeudF_Ori'])
					or ($data['Flag'] !=$data['Flag_Usine'] and $data['Flag_Usine'] >0 and $data['Industrie'])
					or ($data['Flag'] !=$data['Flag_Radar'] and $data['Flag_Radar'] >0 and $data['Radar_Ori'])
					or ($data['Flag'] !=$data['Flag_Plage'] and $data['Flag_Plage'] >0 and $data['Plage']))
				$icone_lock=true;
			}
			if($data['BaseAerienne'] and $adminl)
			{
				if($data['BaseAerienne'] ==3)
					$Base_txt="<br> A�rodrome";
				elseif($data['BaseAerienne'] ==2)
					$Base_txt="<br> A�rodrome avec un bassin pour hydravions";
				else
					$Base_txt="<br> A�rodrome avec piste en dur";
				$Info_long+=20;
				$Base_txt.=' ('.$data['LongPiste'].'m)';
			}
			if($data['NoeudR'])
			{
				$NoeudR_txt="<br> Noeud routier";
				$Info_long+=20;
			}
			if($data['NoeudF_Ori'])
			{
				$NoeudF_txt="<br> Noeud ferroviaire";
				$Info_long+=20;
			}
			if($data['Pont_Ori'])
			{
				$Pont_txt="<br> Pont Strat�gique";
				$Info_long+=20;
			}
			if($data['Industrie'] and $adminl)
			{
				$Industrie_txt="<br> Zone industrielle";
				$Info_long+=20;
			}
			if($data['Oil'] and $adminl)
			{
				$Industrie_txt="<br> Raffinerie";
				$Info_long+=20;
			}
			if($data['Port_Ori'])
			{
				$Port_txt="<br> Infrastructures portuaires";
				$Info_long+=20;
			}
			if($adminl or $Trait ==9)
				$Icone_txt="<img src='images/zone".$Zone.".jpg' title='".GetZone($Zone)."'>";
				//$Icone_txt="<img src=\'images/zone".$Zone.".jpg\' title=\'".GetZone($Zone)."\'>";
			if($data['Plage'])
				$Icone_txt.="<div class='plage'></div>";
				//$Icone_txt.="<img src=\'images/plage.jpg\'>";
			if($data['ValeurStrat'] >0)
				$ValStrat="<img src='images/strat".$data['ValeurStrat'].".png'>";
				//$ValStrat="<img src=\'images/strat".$data['ValeurStrat'].".png\'>";
			else
				$ValStrat="";
			/*if($data['Flag'])
			{
				$Flags=" | Revendiqu� par <img src=\'images/".$data['Flag']."20.gif\' title=\'Nation revendiquant le lieu\'>";
				$Info_long+=25;
			}*/
			if($longit_base and $latit_base)
			{
				$Dist_bat=GetDistance(0,0,$longit_base,$latit_base,$data['Longitude'],$data['Latitude']);
				$Dist_bats="<br>Depuis ".$Nom_Sel." : ".$Dist_bat[0]."km";
				$Info_long+=25;
				if($Mode ==10)
				{
					if($Front ==1 or $Front ==4 or $data['Longitude'] <-50 or $data['Longitude'] >235)
					{
						$Autonomie_Max=250;
						$Range_train=500;
					}
					elseif($Front ==3)
					{
						$Autonomie_Max=300;
						$Range_train=500;
					}
					elseif($Front ==2)
					{
						$Autonomie_Max=100;
						$Range_train=200;
					}
					else
					{
						$Autonomie_Max=50;
						$Range_train=200;
					}
					if($Type_Veh ==97 and ($Zone_calc ==1 or $Zone_calc ==4 or $Zone_calc ==5)) //Montagnards
						$Skill_auto_bonus=true;
					if(($mobile ==1 or $mobile ==2 or $mobile ==6 or $mobile ==7) and $Zone_calc ==0) //Mobile
						$Skill_auto_bonus=true;
					if($Skill_auto_bonus or $Matos ==14 or $Matos ==15 or $Matos ==28)
						$Autonomie_Max*=1.2;
					if($data['NoeudR'] and $NoeudR_calc >0 and $Placement ==2 and !$Rasputitsa and !$Enis)
						$Autonomie_Actu=$Autonomie_Min*2;
					else
						$Autonomie_Actu=$Autonomie_Min;
					if($Autonomie_Actu >$Autonomie_Max)
						$Autonomie_Actu=$Autonomie_Max;
					if($Rasputitsa or ($Merzlota and $mobile !=3))
					{
						if($Rasputitsa and $Zone_calc !=2 and $Zone_calc !=3 and $Zone_calc !=4 and $Zone_calc !=5 and $Zone_calc !=7 and $mobile !=3)
							$Dist_bat[0]=ceil($Dist_bat[0]*1.25);
					}
					if($Faction !=$data['Faction'])
					{
						if($Enis)
							$Dist_bat[0]*=2;
						else
							$Dist_bat[0]=ceil($Dist_bat[0]*1.5);
						$dest_eni=true;
					}
					$Faction_Gare=GetData("Pays","ID",$data['Flag_Gare'],"Faction");
					/*if($Admin)
						$Base_txt.="<br>Auto_Actu = ".$Autonomie_Actu."/ Dist_bat = ".$Dist_bat[0]."/ Range_train = ".$Range_train."/ NoeudF = ".$data['NoeudF']."/ NoeudF_calc".$NoeudF_calc."/ Placement".$Placement."/ Enis".$Enis."/ Faction".$Faction."/ data_Faction".$data['Faction']."/ Faction_Gare".$Faction_Gare;*/
					if($Dist_bat[0] <=$Autonomie_Actu)
						$dest_ok=true;
					elseif($Dist_bat[0] <=$Range_train and $data['NoeudF'] >10 and $NoeudF_calc >10 and $Placement ==3 and !$Enis and $Faction ==$data['Faction'] and $Faction ==$Faction_Gare)
						$dest_train_ok=true;
				}
				elseif($Mode ==11)
				{
					if($Dist_bat[0] <$Limite and $data['LongPiste'] >=$LongPiste_mini and $data['QualitePiste'] >49 and $data['Tour'] >49)
						$dest_air=true;
				}
				elseif($Mode ==12)
					$Autonomie_Log=GetAutoLog($Front);
				elseif($Mode ==13)
					$Autonomie_Log=GetAutoLog($Front,true);
			}
			$Info_txt=$ValStrat." <b>".$data['Nom']."</b> ".$Icone_txt."<br>(".$Pays.")".$Flags.$Dist_bats.$Port_txt.$Industrie_txt.$NoeudR_txt.$NoeudF_txt.$Pont_txt.$Base_txt;
			if($Admin ==1)$Info_txt=$data['ID'].$Info_txt;				
			if($data['Nom'] ==$Nom_Sel)
			{
				$Info_Sel=$Info_txt;
				$Info_Long_Sel=$Info_long;
			}
			if($Mode ==15 and ($OfficierID >0 or $OfficierEMID >0))
			{
				$Embout=0;
				//$Embout2=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID=".$data['ID']." AND r.Placement=0 AND r.Vehicule_Nbr >0"),0);
				$Embout=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND p.Faction='$Faction' AND r.Lieu_ID=".$data['ID']." AND r.Placement=0 AND r.Vehicule_Nbr >0"),0);
				$Embout+=$Embout2;
				if($Embout >=GetEmboutMax($data['ValeurStrat'],0))
				{
					echo '<div style="top:'.($latit-10).'; left:'.($longit-10).'; position: absolute; z-index: 2;"><a href="#" class="popup"><div class="range50r"></div><span>'.$Info_txt.'</span></a></div>';
					/*?><img src="images/range50r.png" style="top:<?=$latit-10?>; left:<?=$longit-10?>; position: absolute; z-index: 2;"><?*/
				}
			}
			elseif($Mode ==9 and $data['Fleuve'])
			{
				/*?><img src="images/map/river<?echo $data['Fleuve'];?>.png" style="top:<?=$latit+2?>; left:<?=$longit+2?>; position: absolute; z-index: 1;"><?*/
			}
			elseif($Mode ==12 and $data['ValeurStrat'] >3 and $Dist_bat[0] <=$Autonomie_Log)
			{
				/*?><img src="images/map/range300.png" style="top:<?=$latit-130?>; left:<?=$longit-130?>; position: absolute; z-index: 2;"><?*/
			}
			elseif($Mode ==13 and $data['ValeurStrat'] >3 and $Dist_bat[0] <=$Autonomie_Log)
			{
				/*?><img src="images/map/range300.png" style="top:<?=$latit-130?>; left:<?=$longit-130?>; position: absolute; z-index: 2;"><?*/
			}
			if($country ==1 and $data['ID'] ==2)
			{
				/*?><img src="images/map_arriere.png" style="top:<?=$latit-15?>; left:<?=$longit-15?>; position: absolute; z-index: 1;"><?*/
			}
			elseif($country ==2 and $data['ID'] ==269)
			{
				/*?><img src="images/map_arriere.png" style="top:<?=$latit-12?>; left:<?=$longit-12?>; position: absolute; z-index: 1;"><?*/
			}
			elseif($country ==4 and $data['ID'] ==201)
			{
				/*?><img src="images/map_arriere.png" style="top:<?=$latit-12?>; left:<?=$longit-12?>; position: absolute; z-index: 1;"><?*/
			}
			if(in_array($data['ID'],$Transit_cities))
			{
				/*?><img src="images/map_transit.png" style="top:<?=$latit-15?>; left:<?=$longit-15?>; position: absolute; z-index: 1;"><?*/
			}
			if($EM_access and ($Front_EM ==$Front or $Front_EM ==99))
			{
				if($Mode ==5)
				{
					//$con=dbconnecti();
					$Couv_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Couverture='".$data['ID']."' AND Pays='$country'"),0);
					$Couv_Nuit_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Couverture_Nuit='".$data['ID']."' AND Pays='$country'"),0);
					$Esc_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Escorte='".$data['ID']."' AND Pays='$country'"),0);
					$Task_IA=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Pilote_IA WHERE Pays='$country' AND Task >'0' AND Cible='".$data['ID']."'"),0);
					//mysqli_close($con);
					if($Couv_IA >0)
					{
						/*?><img src="images/range50.png" style="top:<?=$latit-5?>; left:<?=$longit-5?>; position: absolute; z-index: 2;"><?*/
					}
					if($Couv_Nuit_IA >0)
					{
						/*?><img src="images/range50v.png" style="top:<?=$latit-5?>; left:<?=$longit-5?>; position: absolute; z-index: 2;"><?*/
					}
					if($Esc_IA >0)
					{
						/*?><img src="images/range50b.png" style="top:<?=$latit-5?>; left:<?=$longit-5?>; position: absolute; z-index: 2;"><?*/
					}
					if(!$Couv_IA and !$Couv_Nuit_IA and !$Esc_IA and $Task_IA >0)
					{
						/*?><img src="images/range50p.png" style="top:<?=$latit-5?>; left:<?=$longit-5?>; position: absolute; z-index: 2;"><?*/
					}
				}
				elseif($Mode ==3)
				{
					//$con=dbconnecti();
					$Trains=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE Pays='$country' AND Vehicule_Nbr >0 AND Vehicule_ID=424 AND Lieu_ID='".$data['ID']."'"),0);
					//mysqli_close($con);
					if($Trains >0)
					{
						/*?><img src="images/map/train_ok.png" style="top:<?=$latit-5?>; left:<?=$longit-10?>; position: absolute; z-index: 1;"><?*/
					}
				}
				if($Mode ==2 or $Mode ==5)
				{
					if($data['BaseAerienne'] and $data['QualitePiste'] <50 and $adminl)
					{
						/*?><img src="images/range50r.png" style="top:<?=$latit-10?>; left:<?=$longit-10?>; position: absolute; z-index: 2;"><?*/
					}
				}
				elseif($Mode ==3 or $Mode ==4)
				{
					if($adminl and (($data['NoeudF_Ori'] >0 and $data['NoeudF'] <50) or ($data['Port_Ori'] >0 and $data['Port'] <50)))
					{
						/*?><img src="images/map/map_gare_out.png" style="top:<?=$latit-10?>; left:<?=$longit-10?>; position: absolute; z-index: 2;"><?*/
					}
				}
				elseif($Mode ==8)
				{
					//if(!$Admin)
						$queryt_pays="Pays='$country' AND";
					//$con=dbconnecti();
					$Troupes=mysqli_result(mysqli_query($con,"SELECT COUNT(*) FROM Regiment_IA WHERE ".$queryt_pays." Vehicule_Nbr >0 AND Lieu_ID='".$data['ID']."'"),0);
					//mysqli_close($con);
					if($Troupes >0)
					{
						if($Zone ==6)
						{
							/*?><img src="images/map/ships<?echo $country;?>.png" style="top:<?=$latit-10?>; left:<?=$longit-5?>; position: absolute; z-index: 5;"><?*/
						}
						else
						{
							/*?><img src="images/map/troops<?echo $country;?>.png" style="top:<?=$latit?>; left:<?=$longit-12?>; position: absolute; z-index: 5;"><?*/
						}
					}
				}
			}
			if($iframe)
				$arch_link="<a href='javascript:void(0);' onclick=\"window.parent.location.href='index.php?view=em_city_ground&id=".$data['ID']."&mode=3';\" target='_top'>";
			//data-form=\"{'id':'".$data['ID']."'}\" class='post-data'>";
			else
				$arch_link="<a href='em_city_ground.php?id=".$data['ID']."' target='_blank'>";
			$arch_linkf="</a>";
			$Info_long_pre=$Info_long;				
			//Unites
			if($EM_access and ($Front_EM ==$Front or $Front_EM ==99))
			{
				if($Mode ==2)
				{
					//$con=dbconnecti();
					if($Admin)
						$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' ORDER BY Type DESC LIMIT 1");
					elseif($OfficierEMID >0)
					{
						if($Cdt_Chasse ==$OfficierEMID)
							$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' AND Pays='$country' AND Type IN(1,4,5)");
						elseif($Cdt_Bomb ==$OfficierEMID)
							$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' AND Pays='$country' AND Type IN(2,11)");
						elseif($Cdt_Reco ==$OfficierEMID)
							$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' AND Pays='$country' AND Type IN(3,9)");
						elseif($Cdt_Atk ==$OfficierEMID)
							$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' AND Pays='$country' AND Type IN(7,10,12)");
						else
							$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' AND (Recce=1 OR Pays='$country') ORDER BY Type DESC LIMIT 1");
					}
					else
						$result_unit_air=mysqli_query($con,"SELECT Pays,Type FROM Unit WHERE Base='".$data['ID']."' AND Etat='1' AND (Recce=1 OR Pays='$country') ORDER BY Type DESC LIMIT 1");
					//mysqli_close($con);
					if($result_unit_air)
					{
						while($data_unit=mysqli_fetch_array($result_unit_air,MYSQLI_ASSOC))
						{
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/<?echo $data_unit['Pays'].$data_unit['Type'];?>t.gif" title="<?echo $data['Nom'];?>" style="top:<?=$latit-10?>; left:<?=$longit-10?>; position: absolute; z-index: 10;"><?echo $arch_linkf;?></div><?*/
						}
						mysqli_free_result($result_unit_air);
					}
				}
				$bulle_txt_global="";
				if($OfficierEMID >0)
				{
					$Info_large=0;
					$Info_long+=100;//105
					$esc_nbr=0;
					$Place_ori=99;
					if($Zone ==6)
						$icone="ships";
					else
						$icone="troops";
					$query_unit="(SELECT r.ID,r.Pays,r.Vehicule_ID,r.Placement,r.Visible,p.Faction FROM Regiment as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='".$data['ID']."' AND r.Vehicule_Nbr >0 ORDER BY r.Placement)
					UNION (SELECT r.ID,r.Pays,r.Vehicule_ID,r.Placement,r.Visible,p.Faction FROM Regiment_IA as r,Pays as p WHERE r.Pays=p.ID AND r.Lieu_ID='".$data['ID']."' AND r.Vehicule_Nbr >0 ORDER BY r.Placement) ORDER BY Placement,Pays,Vehicule_ID";
					//$con=dbconnecti();
					$result_unit=mysqli_query($con,$query_unit);
					//mysqli_close($con);
					if($result_unit)
					{
						$Info_long-=20; //compenser la longueur due aux flags
						while($data_unit=mysqli_fetch_array($result_unit,MYSQLI_ASSOC))
						{
							if($data_unit['Pays'] ==$country or $data_unit['Visible'] ==1)
							{
								if($data_unit['Faction'] !=$Faction)$icone_lock=true;
								if($Mode ==8)
								{
									if($Place_ori !=$data_unit['Placement'])
									{
										$Placement="<p><b>-".ucfirst(GetPlace($data_unit['Placement']))."-</b></p>";
										$Info_long+=70;
									}
									else
										$Placement="";
									$esc_nbr+=1;
									//$avion_img='<td><b>'.$data_unit['ID'].'e Cie</b> '.addslashes('<img src=\'images/vehicules/vehicule'.$data_unit['Vehicule_ID'].'.gif\' title=\''.$Vehicule.'\'>').'</td>';
									//$avion_img='<b>'.$data_unit['ID'].'e</b> '.addslashes(GetVehiculeIcon($data_unit['Vehicule_ID'],$data_unit['Pays'],0,0,$Front));
									$avion_img='<b>'.$data_unit['ID'].'e</b> '.GetVehiculeIcon($data_unit['Vehicule_ID'],$data_unit['Pays'],0,0,$Front);
									if($esc_nbr <7)$Info_large+=100; //200									
									$bulle_txt_global.=$Placement.$avion_img;
									if($esc_nbr % 8 ==0)
									{
										$bulle_txt_global.='<br>';
										$Info_long+=70;
									}
									$Place_ori=$data_unit['Placement'];
								}
							}
						}
						mysqli_free_result($result_unit);
						if($Info_large <500)$Info_large =500;
						if($Mode ==8 and $bulle_txt_global !='' and !$icone_lock)
						{
							if($Zone ==6)
							{
								/*?><div onMouseover="ddrivetip('<?echo $ValStrat." <b>".$data['Nom']."</b> ".$Icone_txt."(".$Pays.")".'<hr>'.$bulle_txt_global;?>', '#ECDDC1', '<?echo $Info_large;?>', '<?echo $Info_long;?>')"; onMouseout="hideddrivetip()">
								<?echo $arch_link;?><img src="images/map/ships<?echo $country;?>.png" style="top:<?=$latit-10?>; left:<?=$longit-5?>; position: absolute; z-index: 5;"><?echo $arch_linkf;?></div><?
							}
							else
							{
								/*?><div onMouseover="ddrivetip('<?echo $ValStrat." <b>".$data['Nom']."</b> ".$Icone_txt."(".$Pays.")".'<hr>'.$bulle_txt_global;?>', '#ECDDC1', '<?echo $Info_large;?>', '<?echo $Info_long;?>')"; onMouseout="hideddrivetip()">
								<?echo $arch_link;?><img src="images/map/troops<?echo $country;?>.png" style="top:<?=$latit?>; left:<?=$longit-12?>; position: absolute; z-index: 5;"><?echo $arch_linkf;?></div><?*/
							}
						}
						unset($data_unit);
					}
				}
			}
			if($data['Flag'] ==24)$data['Flag']=6; //Albanie
			if($OfficierEMID >0)
			{
				if($Mode ==6)
				{
					/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
					<?echo $arch_link;?><img src="images/<?echo $data['Flag'];?>20.gif" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
				}
				elseif($Mode ==7)
				{
					/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
					<?echo $arch_link;?><img src="images/strat<?echo $data['ValeurStrat'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
				}
				elseif($Mode ==9 and ($data['Fleuve'] or $data['Pont_Ori']))
				{
					echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_pont'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
					/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
					<?echo $arch_link;?><img src="images/map/lieu_pont<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
				}
				elseif(($Mode ==2 and $Avancement >4999) or $Mode ==11)
				{
					if($data['BaseAerienne'] >0 and ($adminl or $Mode ==11))
					{
						if($data['LongPiste'] >1400)
						{
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_air'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/map/lieu_air<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
						}
						elseif($data['LongPiste'] >=1200)
						{
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_piste'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/map/lieu_piste<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
						}
						elseif($data['LongPiste'] >=1000)
						{
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_pistem'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/map/lieu_pistem<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
						}
						else
						{
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_pistec'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/map/lieu_pistec<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
						}
						if($Mode ==11 and $dest_air)
						{
							/*?><img src="images/map/river2.png" style="top:<?=$latit-2?>; left:<?=$longit-2?>; position: absolute; z-index: 1;"><?*/
						}
					}
				}
				elseif($Mode ==14 and ($Front_EM ==$Front or $Front_EM ==99) and ($adminl or $Trait ==9 or $Trait ==23 or $Trait_o ==23))
				{
					if($data['ValeurStrat'] >1 or $data['Zone'] ==6)
					{
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/meteo<?echo $Meteo;?>.gif" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
				}
				else
				{
					if($icone_lock)
					{
						/*?><img src="images/map/lieu_fire.png" style="top:<?=$latit-5?>; left:<?=$longit+10?>; position: absolute; z-index: 5;"><?*/
					}
					if($dest_ok and $Mode ==10)
					{
						if($dest_eni)
							$dest_icon="move_eni";
						else
							$dest_icon="move_ok2";
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/<?echo $dest_icon;?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit+2?>; left:<?=$longit+2?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['NoeudF_Ori'] >0 and $dest_train_ok and $Mode ==10)
					{
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/move_train2.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($Zone ==6)
					{
						if($data['Detroit'])
						{
							if(IsAxe($country))
								$Recce_field=$data['Recce_mines_m_ax'];
							else
								$Recce_field=$data['Recce_mines_m_al'];
							if($Recce_field >0)
								$icone_mer="icone_mines_m";
							else
								$icone_mer="icone_detroit";
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="detroit"></div><span>'.$Info_txt.'</span></a></div>';
						}
						else
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="mer"></div><span>'.$Info_txt.'</span></a></div>';
					}
					elseif($data['ValeurStrat'] ==10)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_city'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_city<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 4;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['Port_Ori'])
					{
						if($data['Port_level'] ==3)
							$Port_pref='b';
						elseif($data['Port_level'] ==1)
							$Port_pref='s';
						else
							$Port_pref='';
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_port'.$Port_pref.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_port<?echo $Port_pref.$data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 4;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['Oil'] and $data['Industrie'] and $adminl)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_oil'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/icone_oil<?echo $data['Flag'];?>.gif" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['Industrie'] and $adminl)
					{
						if($data['ValeurStrat'] >3)
						{
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_usined'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>', '#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/map/lieu_usined<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
						}
						else
						{
							echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_usine'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
							/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
							<?echo $arch_link;?><img src="images/map/icone_usine<?echo $data['Flag'];?>.gif" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
						}					
					}
					elseif($data['ValeurStrat'] >3)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_depot'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_depot<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['Fortification'] >50)
					{
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/icone_fort<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 4;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['NoeudF_Ori'] >0)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_gare'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_gare<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['Pont_Ori'] >0)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_pont'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_pont<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['NoeudR'] >0)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_route'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_route<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 3;"><?echo $arch_linkf;?></div><?*/
					}
					elseif($data['BaseAerienne'] >0 and $adminl)
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu_pistec'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu_pistec<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
					}
					else
					{
						echo '<div style="top:'.$latit.'; left:'.$longit.'; position: absolute;"><a href="#" class="popup"><div class="lieu'.$data['Flag'].'"></div><span>'.$Info_txt.'</span></a></div>';
						/*?><div onMouseover="ddrivetip('<?echo $Info_txt;?>','#ECDDC1','200','<?echo $Info_long_pre;?>')"; onMouseout="hideddrivetip()">
						<?echo $arch_link;?><img src="images/map/lieu<?echo $data['Flag'];?>.png" title="<?echo $data['Nom'];?>" style="top:<?=$latit?>; left:<?=$longit?>; position: absolute; z-index: 2;"><?echo $arch_linkf;?></div><?*/
					}
				}
			}
		}
	}
	mysqli_close($con);
	if($Lieu_search)
	{
		if($iframe)
		{
			/*?><div onMouseover="ddrivetip('<?echo $Info_Sel;?>','#ECDDC1','200','<?echo $Info_Long_Sel;?>')"; onMouseout="hideddrivetip()">
			<a href="javascript:void(0);" onclick="window.parent.location.href='index.php?view=em_city_ground&id=<?echo $Lieu_search;?>&mode=3';" target='_top'><img src="images/cible3.gif" title="<?echo $Nom_Sel;?>" style="top:<?=$Lat_Sel?>; left:<?=$Long_Sel?>; position: absolute; z-index: 10;"></a></div><?*/
		}
		else
		{
			/*?><div onMouseover="ddrivetip('<?echo $Info_Sel;?>','#ECDDC1','200','<?echo $Info_Long_Sel;?>')"; onMouseout="hideddrivetip()">
			<a href='em_city_ground.php?id=<?echo $Lieu_search;?>' target='_blank'><img src="images/cible3.gif" title="<?echo $Nom_Sel;?>" style="top:<?=$Lat_Sel?>; left:<?=$Long_Sel?>; position: absolute; z-index: 10;"></a></div><?*/
		}
	}?>
	<div class="Menu_Pos" style="background: rgba(236,221,193,0.5);">
	<h4><?echo $Date_Campagne;?></h4>
	<?echo $menu_maps;?>
	<hr><a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=1<?echo $iframe_txt;?>'>G�n�ral</a> <a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=6<?echo $iframe_txt;?>'>Conqu�te</a>
	<a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=2<?echo $iframe_txt;?>'>A�rien</a> <a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=8<?echo $iframe_txt;?>'>Troupes</a>
	<a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=3<?echo $iframe_txt;?>'>Train</a> <a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=4<?echo $iframe_txt;?>'>Logistique</a>
	<a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=9<?echo $iframe_txt;?>'>Fleuves</a> <a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=7<?echo $iframe_txt;?>'>Valeurs</a> <a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=14'>M�t�o</a>
	<a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=5<?echo $iframe_txt;?>'>Missions</a> <a class='label label-primary' href='carte_ground.php?map=<?echo $Map;?>&amp;mode=15<?echo $iframe_txt;?>'>Trafic</a> <hr>
	<?//<form><input type="button" class='btn btn-default btn-sm' value="Calculer une distance" title="Calculer une distance entre deux lieux" onclick="window.open('calc_distance.php','Calculer','width=300,height=300,scrollbars=1')"></form>
	if($Lieux)
		echo "<br><form action='carte_ground.php' method='post'><input type='hidden' name='map' value='".$Map."'><input type='hidden' name='frame' value='1'><select name='lieu' style='width: 100px'>".$Lieux."</select><input type='Submit' class='btn btn-default btn-sm' value='Chercher' onclick='this.disabled=true;this.form.submit();'></form>";
	if($Mode ==10)echo "<br><span class='label label-warning'>Autonomie de votre bataillon ".$Autonomie_Actu."km</span>";
	elseif($Mode ==12 or $Mode ==13)echo "<br><span class='label label-warning'>Autonomie de votre logistique ".$Autonomie_Log."km</span>";
	if($legende){echo $legende;}else{?>
	<form><input type="button" class='btn btn-info btn-sm' value="L�gende" title="L�gende de la carte" onclick="window.open('aide_map.php','L�gende','width=400,height=600,scrollbars=1')"></form>
	<?}?>
	</div>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="./js/lib/jquery-1.10.2.min.js"><\/script>')</script>
    <script src="./js/lib/bootstrap.min.js"></script>
    <script src="./js/lib/jquery.cookie.js"></script>
    <script src="./js/main.js"></script>
</body></html>
<?}?>	