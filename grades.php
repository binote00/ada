<?
require_once('./jfv_inc_sessions.php');
include_once('./jfv_include.inc.php');
include_once('./jfv_nomission.inc.php');
include_once('./menu_infos.php');
?>
<h2>Les Grades</h2>
<div style='overflow:auto; width: 100%;'><table class='table table-condensed'>
<thead><tr><th>Luftwaffe</th><th>Royal Air Force</th><th>Armée de l'air</th><th>Regia Aeronautica</th><th>US Army Air Force</th><th>Voienno Vozduchnyie Sily</th><th>Dainippon Teikoku Rikugun Kokutai</th><th>Aéronautique Militaire</th><th>Suomen ilmavoimat</th></tr></thead>
<?
for($level=0;$level<=14;$level++)
{
		echo "<tr>";
		for($i=1; $i<=20;$i++)
		{
			if(($i !=3 and $i !=5 and $i <11) or $i ==20)
			{
				if($i ==10)
					$Pays=3;
				else
					$Pays=$i;
				$Grade = GetAvancement(0,$Pays,$level);
				$Grade_txt=$Grade[0];
				echo "<td><img src='images/grades/grades".$Pays.$level.".png' title='".$Grade_txt."' style='margin-left:5px; margin-top:5px;'></td>";	
			}
		}
		echo "</tr>";
}
echo "</table>";

/*for($i=1;$i<=9;$i++)
{
	if($i !=5)
	{
		echo "<h3>".GetPays($i)."</h3>";
		for($level=0;$level<=14;$level++)
		{
			$Grade = GetAvancement(0,$i,$level);
			$Grade_txt=$Grade[0];
			echo "<img src='images/grades/grades".$i.$level.".png' title='".$Grade_txt."' style='margin-left:5px; margin-top:5px;'>";		
		}
	}
}*/
?>
<!--<tr><td>Aviateur</td><td><img src="images/grades/grades40.jpg"></td><td>Flieger</td><td><img src="images/grades/grades10.gif"></td><td>Aircraftman</td><td><img src="images/grades/grades20.jpg"></td><td>Aviere</td><td><img src="images/grades/grades60.jpg"></td><td>Santo Hei</td><td><img src="images/grades/grades90.jpg"></td><td>Private</td><td><img src="images/grades/grades70.jpg"></td><td>Krasnoarmeyets</td><td><img src="images/grades/grades80.jpg"></td></tr>
<tr><td>1e classe</td><td><img src="images/grades/grades41.jpg"></td><td>Gefreiter</td><td><img src="images/grades/grades11.gif"></td><td>Leading Aircraftman</td><td><img src="images/grades/grades21.jpg"></td><td>Aviere Scelto</td><td><img src="images/grades/grades61.jpg"></td><td>Nito Hei</td><td><img src="images/grades/grades91.jpg"></td><td>Private First Class</td><td><img src="images/grades/grades71.jpg"></td><td>Yefreytor</td><td><img src="images/grades/grades81.jpg"></td></tr>
<tr><td>Caporal</td><td><img src="images/grades/grades42.jpg"></td><td>Obergefreiter</td><td><img src="images/grades/grades12.gif"></td><td>Lance Corporal</td><td><img src="images/grades/grades22.jpg"></td><td>Aviere Capo</td><td><img src="images/grades/grades62.jpg"></td><td>Ito Hei</td><td><img src="images/grades/grades92.jpg"></td><td>Corporal</td><td><img src="images/grades/grades72.jpg"></td><td>Mladshiy serzhant</td><td><img src="images/grades/grades82.jpg"></td></tr>
<tr><td>Caporal-Chef</td><td><img src="images/grades/grades43.jpg"></td><td>Hauptgefreiter</td><td><img src="images/grades/grades13.gif"></td><td>Corporal</td><td><img src="images/grades/grades23.jpg"></td><td>Primo Aviere</td><td><img src="images/grades/grades63.jpg"></td><td>Joto Hei</td><td><img src="images/grades/grades93.jpg"></td><td>Sergeant</td><td><img src="images/grades/grades73.jpg"></td><td>Serzhant</td><td><img src="images/grades/grades83.jpg"></td></tr>
<tr><td>Sergent</td><td><img src="images/grades/grades44.jpg"></td><td>Unteroffizier</td><td><img src="images/grades/grades14.gif"></td><td>Sergeant</td><td><img src="images/grades/grades24.jpg"></td><td>Sergente</td><td><img src="images/grades/grades64.jpg"></td><td>Heicho</td><td><img src="images/grades/grades94.jpg"></td><td>Staff Sergeant</td><td><img src="images/grades/grades74.jpg"></td><td>Starshiy serzhant</td><td><img src="images/grades/grades84.jpg"></td></tr>
<tr><td>Sergent-Chef</td><td><img src="images/grades/grades45.jpg"></td><td>Unterfeldwebel</td><td><img src="images/grades/grades15.gif"></td><td>Chief Technician</td><td><img src="images/grades/grades25.jpg"></td><td>Sergente Maggiore</td><td><img src="images/grades/grades65.jpg"></td><td>Gocho</td><td><img src="images/grades/grades95.jpg"></td><td>Technical Sergeant</td><td><img src="images/grades/grades75.jpg"></td><td>Starshina</td><td><img src="images/grades/grades85.jpg"></td></tr>
<tr><td>Sergent-Major</td><td><img src="images/grades/grades46.jpg"></td><td>Feldwebel</td><td><img src="images/grades/grades16.gif"></td><td>Flight Sergeant</td><td><img src="images/grades/grades26.jpg"></td><td>Maresciallo di terza classe</td><td><img src="images/grades/grades66.jpg"></td><td>Gunso</td><td><img src="images/grades/grades96.jpg"></td><td>Master Sergeant</td><td><img src="images/grades/grades76.jpg"></td><td>Mladshiy voentehnik</td><td><img src="images/grades/grades86.jpg"></td></tr>
<tr><td>Adjudant</td><td><img src="images/grades/grades47.jpg"></td><td>Oberfeldwebel</td><td><img src="images/grades/grades17.gif"></td><td>Warrant Officer 2nd class</td><td><img src="images/grades/grades27.jpg"></td><td>Maresciallo di seconda classe</td><td><img src="images/grades/grades67.jpg"></td><td>Socho</td><td><img src="images/grades/grades97.jpg"></td><td>Junior Warrant Officer</td><td><img src="images/grades/grades77.jpg"></td><td>Voentehnik</td><td><img src="images/grades/grades87.jpg"></td></tr>
<tr><td>Adjudant-Chef</td><td><img src="images/grades/grades48.jpg"></td><td>Hauptfeldwebel</td><td><img src="images/grades/grades18.gif"></td><td>Warrant Officer 1st class</td><td><img src="images/grades/grades28.jpg"></td><td>Maresciallo di prima classe</td><td><img src="images/grades/grades68.jpg"></td><td>Juni</td><td><img src="images/grades/grades98.jpg"></td><td>Chief Warrant Officer</td><td><img src="images/grades/grades78.jpg"></td><td>Mladshiy leytenant</td><td><img src="images/grades/grades88.jpg"></td></tr>
<tr><td>Sous-Lieutenant</td><td><img src="images/grades/grades49.jpg"></td><td>Leutnant</td><td><img src="images/grades/grades19.gif"></td><td>Pilot Officer</td><td><img src="images/grades/grades29.jpg"></td><td>Sottotenente</td><td><img src="images/grades/grades69.jpg"></td><td>Shoi</td><td><img src="images/grades/grades99.jpg"></td><td>Lieutenant</td><td><img src="images/grades/grades79.jpg"></td><td>Leytenant</td><td><img src="images/grades/grades89.jpg"></td></tr>
<tr><td>Lieutenant</td><td><img src="images/grades/grades410.jpg"></td><td>Oberleutnant</td><td><img src="images/grades/grades110.gif"></td><td>Flying Officer</td><td><img src="images/grades/grades210.jpg"></td><td>Tenente</td><td><img src="images/grades/grades610.jpg"></td><td>Chui</td><td><img src="images/grades/grades910.jpg"></td><td>First Lieutenant</td><td><img src="images/grades/grades710.jpg"></td><td>Starshiy leytenant</td><td><img src="images/grades/grades810.jpg"></td></tr>
<tr><td>Capitaine</td><td><img src="images/grades/grades411.jpg"></td><td>Hauptmann</td><td><img src="images/grades/grades111.gif"></td><td>Flight Lieutenant</td><td><img src="images/grades/grades211.jpg"></td><td>Capitano</td><td><img src="images/grades/grades611.jpg"></td><td>Taii</td><td><img src="images/grades/grades911.jpg"></td><td>Captain</td><td><img src="images/grades/grades711.jpg"></td><td>Kapitan</td><td><img src="images/grades/grades811.jpg"></td></tr>
<tr><td>Commandant</td><td><img src="images/grades/grades412.jpg"></td><td>Major</td><td><img src="images/grades/grades112.gif"></td><td>Squadron Leader</td><td><img src="images/grades/grades212.jpg"></td><td>Maggiore</td><td><img src="images/grades/grades612.jpg"></td><td>Shosa</td><td><img src="images/grades/grades912.jpg"></td><td>Major</td><td><img src="images/grades/grades712.jpg"></td><td>Major</td><td><img src="images/grades/grades812.jpg"></td></tr>
<tr><td>Lieutenant-Colonel</td><td><img src="images/grades/grades413.jpg"></td><td>Oberstleutnant</td><td><img src="images/grades/grades113.gif"></td><td>Wing Commander</td><td><img src="images/grades/grades213.jpg"></td><td>Tenente Colonnello</td><td><img src="images/grades/grades613.jpg"></td><td>Chusa</td><td><img src="images/grades/grades913.jpg"></td><td>Lieutenant Colonel</td><td><img src="images/grades/grades713.jpg"></td><td>Podpolkovnik</td><td><img src="images/grades/grades813.jpg"></td></tr>
<tr><td>Colonel</td><td><img src="images/grades/grades414.jpg"></td><td>Oberst</td><td><img src="images/grades/grades114.gif"></td><td>Group Captain</td><td><img src="images/grades/grades214.jpg"></td><td>Colonnello</td><td><img src="images/grades/grades614.jpg"></td><td>Taisa</td><td><img src="images/grades/grades914.jpg"></td><td>Colonel</td><td><img src="images/grades/grades714.jpg"></td><td>Polkovnik</td><td><img src="images/grades/grades814.jpg"></td></tr>
</table></div>-->
