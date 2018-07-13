<?php
	$view=Insec($view);
	if($view =='')
		$view='aubedesaigles';
	elseif(strpos($view, 'passwd') or strpos($view, '999') or strpos($view, '212') or strpos($view, '%2') or strpos($view, 'regist') or strpos($view, '\''))
		$view='dumb';
	switch($view) 
	{
		case 'update':
			$content='game_update.php';
			break;
		case 'aubedesaigles' :
			if($mes =='tableau')
				$content='void.php';
			else
			{
				$content='default.php';
				//$bg='images/bg_papier_full1.jpg';
				//$paper='images/bg_papier.gif';
				if(!$img)
				{
					if($_SERVER['SERVER_NAME'] =='www.cheratte.net' or $_SERVER['SERVER_NAME'] =='cheratte.net')
						$img='<img src=\'fous.jpg\' alt=\'Aube des Aigles\'>';
					elseif(!$mes)
					{
						$titre="L'Aube des Aigles";
						//<img src='images/ada_accueil.png' alt='Aube des Aigles' style='width:100%'>
						$intro='<h4><small><b>Rejoignez une nation au coeur du conflit mondial</b></small></h4>
						<h4><small>Exécutez les missions en tant que simple pilote,
						<br>Commandez un bataillon terrestre ou une flottille de navires,
						<br>Gérez les infrastructures et le ravitaillement,
						<br>Commandez des armées entières et définissez la stratégie globale en tant que membre de l\'état-major...</small></h4>
						<h1><small>Chacun est l\'élément d\'une chaîne sans quoi aucune victoire n\'est possible</small></h1>';
						$img="<img src='images/Logo_ada.png' alt='Aube des Aigles' style='width:50%'>";
						$toolbar='<h1><a href="index.php?view=signin" class="btn btn-default"><span>Créer un compte</span></a> et commencer à jouer !</h1>';
						include_once('./game_stats.php');
					}
				}
				if(!$mes)
				{
					if($_SERVER['SERVER_NAME'] =='www.cheratte.net' or $_SERVER['SERVER_NAME'] =='cheratte.net')
						$menu='';
					$bg='';
					$paper='';
				}
				$pageTitle='Aube des Aigles';
			}
			break;	
		case 'db_as' :
			$content='as.php';				
			break;
		case 'db_as_add' :
			$content='as_add.php';						
			break;			
		case 'db_as_modif' :
			$content='as_modif1.php';						
			break;			
		case 'db_lieu_add' :
			$content='lieu_add.php';						
			break;			
		case 'db_event_add' :
			$content='event_add.php';						
			break;			
		case 'db_event_h_add' :
			$content='event_h_add.php';						
			break;			
		case 'db_event_h_menu' :
			$content='event_h_menu.php';						
			break;			
		case 'as' :
			$content='aces.php';	
			break;			
		case 'as_sandbox' :
			$content='aces_sandbox.php';	
			break;			
		case 'as_des_as_init' :
			$content='as_des_as0.php';	
			break;
		case 'assaut' :
			$content='output_ground.php';
			break;			
		case 'attaques' :
			$content='output_atk.php';
			break;			
		case 'avionperso' :
			$content='choix_avion.php';	
			break;
		case 'avionss' :
			$content='avions1.php';
			break;		
		case 'campagne_scoret' :
			$content='campagne_score2.php';
			break;			
		case 'combats' :
			$content='output_ground.php';
			break;			
		case 'comparateurv' :
			$content='comparateur_v.php';
			break;			
		case 'comparateurv1' :
			$content='comparateur_v1.php';
			break;			
		case 'compte' :
			$content='useraccount.php';
			break;			
		case 'credits' :
			$content='credits.html';	
			break;			
		case 'dca' :
			$content='output_dca.php';					
			break;	
		case 'em_actus' :
			$content='em_actualites.php';					
			break;								
		case 'em_missions_1' :
			$Type=1;
			$content='em_missions.php';
			break;			
		case 'em_missions_2' :
			$Type=2;
			$content='em_missions.php';
			break;
		case 'em_missions_3' :
			$Type=3;
			$content='em_missions.php';
			break;
		case 'em_missions_4' :
			$Type=4;
			$content='em_missions.php';
			break;
		case 'em_missions_6' :
			$Type=6;
			$content='em_missions.php';
			break;
		case 'em_missions_7' :
			$Type=7;
			$content='em_missions.php';
			break;
		case 'em_missions_9' :
			$Type=9;
			$content='em_missions.php';
			break;
		case 'em_missions_10' :
			$Type=10;
			$content='em_missions.php';
			break;
		case 'em_missions_11' :
			$Type=11;
			$content='em_missions.php';
			break;
		case 'em_missions_12' :
			$Type=12;
			$content='em_missions.php';
			break;
		case 'em_missions_99' :
			$Type=99;
			$content='em_missions.php';
			break;
		case 'em_personnel_1' :
			$Tab="1";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_2' :
			$Tab="2";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_3' :
			$Tab="3";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_4' :
			$Tab="4";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_6' :
			$Tab="6";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_7' :
			$Tab="7";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_8' :
			$Tab="8";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_9' :
			$Tab="9";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_10' :
			$Tab="10";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_11' :
			$Tab="11";
			$content='em_personnel.php';
			break;
		case 'em_personnel_12' :
			$Tab="12";
			$content='em_personnel.php';					
			break;
		case 'em_personnel_ia_1' :
			$Tab="1";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_2' :
			$Tab="2";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_3' :
			$Tab="3";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_4' :
			$Tab="4";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_6' :
			$Tab="6";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_7' :
			$Tab="7";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_8' :
			$Tab="8";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_9' :
			$Tab="9";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_10' :
			$Tab="10";
			$content='em_personnel_ia.php';					
			break;
		case 'em_personnel_ia_11' :
			$Tab="11";
			$content='em_personnel_ia.php';
			break;
		case 'em_personnel_ia_12' :
			$Tab="12";
			$content='em_personnel_ia.php';					
			break;
		case 'em_unites_1' :
			$Type=1;
			$content='em_unites.php';
			break;			
		case 'em_unites_2' :
			$Type=2;
			$content='em_unites.php';
			break;
		case 'em_unites_3' :
			$Type=3;
			$content='em_unites.php';
			break;
		case 'em_unites_4' :
			$Type=4;
			$content='em_unites.php';
			break;
		case 'em_unites_6' :
			$Type=6;
			$content='em_unites.php';
			break;
		case 'em_unites_7' :
			$Type=7;
			$content='em_unites.php';
			break;
		case 'em_unites_9' :
			$Type=9;
			$content='em_unites.php';
			break;
		case 'em_unites_10' :
			$Type=10;
			$content='em_unites.php';
			break;
		case 'em_unites_11' :
			$Type=11;
			$content='em_unites.php';
			break;
		case 'em_unites_12' :
			$Type=12;
			$content='em_unites.php';
			break;
		case 'em_unites_95' :
			$Type=95;
			$content='em_unites.php';
			break;
		case 'em_unites_96' :
			$Type=96;
			$content='em_unites.php';
			break;
		case 'em_unites_97' :
			$Type=97;
			$content='em_unites.php';
			break;
		case 'em_unites_98' :
			$Type=98;
			$content='em_unites.php';
			break;
		case 'equipage' :
			$content='user_wingman.php';					
			break;
		case 'escadrille_o' :
			$Tab="forme";
			$content='escadrille.php';					
			break;
		case 'escadrille_s' :
			$Tab="service";
			$content='escadrille.php';					
			break;
		case 'escadrille_f' :
			$Tab="formation";
			$content='escadrille.php';					
			break;
		case 'escadrille_r' :
			$Tab="renseignement";
			$content='escadrille.php';					
			break;
		case 'escadrille_p' :
			$Tab="reputation";
			$content='escadrille.php';					
			break;			
		case 'esc_archives' :
			$content='archives_unite.php';				
			break;
		case 'esc_gestioncdt0_a' :
			$Tab="Avions";
			$content='esc_gestioncdt0.php';					
			break;
		case 'esc_gestioncdt0_r' :
			$Tab="Armement";
			$content='esc_gestioncdt0.php';					
			break;
		case 'esc_gestioncdt0_p' :
			$Tab="Pilotes";
			$content='esc_gestioncdt0.php';					
			break;
		case 'esc_gestioncdt0_b' :
			$Tab="Base";
			$content='esc_gestioncdt0.php';					
			break;
		case 'esc_gestioncdt0_c' :
			$Tab="Cdt";
			$content='esc_gestioncdt0.php';					
			break;				
		case 'esc_gestion_confirm' :
			$content='esc_gestion1.php';					
			break;
		case 'esc_infos' :
			$content='escinfos.php';					
			break;
		case 'esc_journala' :
			$Tab="attaques";
			$content='esc_journal.php';					
			break;			
		case 'esc_journalm' :
			$Tab="missions";
			$content='esc_journal.php';					
			break;			
		case 'esc_journalp' :
			$Tab="pilotes";
			$content='esc_journal.php';					
			break;			
		case 'esc_journalr' :
			$Tab="ravit";
			$content='esc_journal.php';					
			break;
		case 'esc_pilotes' :
			$content='escadrille1.php';					
			break;
		case 'esc_missions' :
			$content='escmissions.php';					
			break;
		case 'escorte' :
			$content='output_escorte.php';					
			break;		
		case 'sauvetage' :
			$content='output_sauvetage.php';					
			break;
		case 'hardcore' :
			$content='hardcore_aces.php';					
			break;			
		case 'historique' :
			$content='historique_events.php';					
			break;			
		case 'events_histo' :
			$content='em_historique.php';					
			break;
		case 'ident_recover' :
			$content='login_recover.html';					
			break;
		case 'intercept' :
			$content='output_intercept.php';					
			break;
		case 'login' :
			$content='login.html';						
			break;
		case 'mission_start' :
			$content='start.php';					
			break;			
		case 'naval' :
			$content='output_naval.php';					
			break;		
		case 'navires' :
			$content='output_navires.php';					
			break;			
		case 'officiers' :
			$content='output_off.php';
			break;			
		case 'officiers_em' :
			$content='output_off_em.php';
			break;			
		case 'paras' :
			$content='output_paras.php';				
			break;			
		case 'patrouille' :
			$content='output_patrouille.php';					
			break;								
		case 'pass_recover' :
			$content='password_recover.html';
			break;
		case 'presse' :
			$content='event.php';					
			break;
		case 'ravit' :
			$content='output_ravit.php';					
			break;		
		case 'recce' :
			$content='output_recce.php';					
			break;
		case 'signin' :
			$content='signin.html';					
			break;
		case 'signin_second' :
			$content='signin_second.php';					
			break;
		case 'signin_seconds' :
			$content='signin_second0.php';					
			break;
		case 'signins' :
			$content='signin.php';					
			break;
		case 'tableau' :
			$content='output.php';					
			break;			
		case 'tableau_chasse' :
			$content='output_sandbox.php';					
			break;			
		case 'terre' :
			$content='output_terre.php';					
			break;
		case 'adispo' :
			$content='admin_dispos.php';					
			break;			
		case 'aguns' :
			$content='admin_guns.php';					
			break;			
		case 'anav' :
			$content='admin_nav.php';					
			break;			
		case 'av0' :
			$content='admin_avions0.php';					
			break;			
		case 'aveh' :
			$content='admin_veh.php';					
			break;			
		case 'aveh0' :
			$content='admin_veh0.php';					
			break;			
		case 'tableau_pvp' :
			$content='output_pvp.php';					
			break;
		case 'testcombat' :
			$content='test_combat.php';					
			break;			
		case 'transfer' :
			$content='choix_unite.php';					
			break;
		case 'unitess' :
			$content='unites1.php';					
			break;			
		case 'user' :
			$content='userprofile.php';					
			break;			
		case 'usertest' :
			$content='userprofile.php';					
			break;
		case 'vehiculess' :
			$content='vehicules1.php';					
			break;
		//GROUND		
		case 'ground_div':
			$content='ground_em.php'; 					
			break;
		case 'ground_em':
			$content='ground_em.php';		
			$Tab="EM";
			break;
		case 'ground_em_ia_list_1' :
			$Type=1;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_2' :
			$Type=2;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_3' :
			$Type=3;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_4' :
			$Type=4;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_5' :
			$Type=5;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_6' :
			$Type=6;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_7' :
			$Type=7;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_8' :
			$Type=8;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_9' :
			$Type=9;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_10' :
			$Type=10;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_13' :
			$Type=13;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_14' :
			$Type=14;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_15' :
			$Type=15;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_17' :
			$Type=17;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_20' :
			$Type=20;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_21' :
			$Type=21;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_22' :
			$Type=22;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_23' :
			$Type=23;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_24' :
			$Type=24;
			$content='ground_em_ia_list.php';
			break;
        case 'ground_em_ia_list_88' :
            $Type=88;
            $content='ground_em_ia_list.php';
            break;
		case 'ground_em_ia_list_89' :
			$Type=89;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_90' :
			$Type=90;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_91' :
			$Type=91;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_92' :
			$Type=92;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_93' :
			$Type=93;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_94' :
			$Type=94;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_95' :
			$Type=95;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_96' :
			$Type=96;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_97' :
			$Type=97;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_98' :
			$Type=98;
			$content='ground_em_ia_list.php';
			break;
		case 'ground_em_ia_list_100' :
			$Type=100;
			$content='ground_em_ia_list.php';
			break;
		case 'em_city_combats_1' :
			$Type=1;
			$content='em_city_combats.php';
			break;
		case 'em_city_combats_2' :
			$Type=2;
			$content='em_city_combats.php';
			break;
		case 'em_city_combats_3' :
			$Type=3;
			$content='em_city_combats.php';
			break;
		case 'em_city_combats_6' :
			$Type=6;
			$content='em_city_combats.php';
			break;
		case 'em_city_combats_8' :
			$Type=8;
			$content='em_city_combats.php';
			break;
		case 'profil_pvp' :
			$content='profil_pilote_pvp.php';
			break;
		case 'em_production_1':
		case 'em_production_2':
		case 'em_production_3':
		case 'em_production_4':
		case 'em_production_5':
		case 'em_production_6':
		case 'em_production_7':
		case 'em_production_9':
		case 'em_production_10':
		case 'em_production_11':
		case 'em_production_12':
			$Type=substr($view,strpos($view,'_',3)+1);
			$content='em_production.php';
			break;
        case 'em_production2_1':
        case 'em_production2_2':
        case 'em_production2_3':
        case 'em_production2_4':
        case 'em_production2_5':
        case 'em_production2_6':
        case 'em_production2_7':
        case 'em_production2_8':
        case 'em_production2_9':
        case 'em_production2_10':
        case 'em_production2_11':
        case 'em_production2_12':
        case 'em_production2_15':
        case 'em_production2_16':
        case 'em_production2_17':
        case 'em_production2_18':
        case 'em_production2_19':
        case 'em_production2_20':
        case 'em_production2_21':
        case 'em_production2_37':
        case 'em_production2_90':
        case 'em_production2_91':
        case 'em_production2_92':
        case 'em_production2_93':
        case 'em_production2_94':
        case 'em_production2_95':
        case 'em_production2_96':
        case 'em_production2_97':
        case 'em_production2_98':
        case 'em_production2_99':
        case 'em_production2_100':
        case 'em_production2_101':
            $Type=substr($view,strpos($view,'_',3)+1);
            $content='em_production2.php';
            break;
		//DUMB
		case 'dumb':
			$content='dumb.php';
			break;
		default :
			$content=$view.'.php';
	}