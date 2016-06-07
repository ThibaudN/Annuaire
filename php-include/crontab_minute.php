<?php
ini_set('memory_limit', '512M');
set_time_limit(150);
$nTimeCronJeuDebut = microtime(true);
$sTimeCronRapport = '0.00000 :: Debut script<br />';
if(empty($sVerifCRONTAB)) {

	ini_set('session.use_cookies','1');
	ini_set('session.use_only_cookies','1');
	ini_set('url_rewriter.tags','');
	ini_set('session.use_trans_sid','0');
	ini_set('date.timezone', 'Europe/Paris');
	ini_set('display_errors','1');
	error_reporting(E_ALL);
	include __DIR__."/f_base.php";
	include __DIR__."/d_base.php";//identifisant sql
	include __DIR__."/d_sql.php";//identifisant sql
	include __DIR__."/d_sqltables.php";//nom des tables sql
	include __DIR__."/c_pdo.php";//class pdo
	include __DIR__."/c_email.php";//class pdo
	include __DIR__."/f_keygen.php";//class pdo
	include __DIR__."/v_date.php";//class pdo
	include __DIR__."/f_google.php";//class pdo
	include __DIR__."/f_photos.php";//class pdo

	$entete  = "MIME-Version: 1.0\n";
	$entete .= "Content-type: text/html; charset=UTF-8\n";
	$entete .= "Content-Transfer-Encoding: 8bit\n";
	$entete .= "X-Priority: 3\n";
	$entete .= "X-MSMail-Priority: Normal\n";
	$entete .= "X-Mailer: php\n";
	$entete .= "From: \"".NOMSITE."\" <thibaud@passetoncode.fr>\n";
	$entete .= "Return-path: thibaud@passetoncode.fr\n";

	$oSql = mypdo::GetInstance(DB_HOST,DB_LOGIN,DB_PASS,DB_BASE);
	#mail("thibaud@passetoncode.fr", "CRONTAB MINUTE :: Lancement effectué à ".$nHour.'h'.$nMinute.'m'.$nSecond,"CRONTAB MINUTE :: Lancement effectué à ".$nHour.'h'.$nMinute.'m'.$nSecond, $entete);
	#
	#
	header("Content-Type: text/html; charset=UTF-8");
}

$rR = "SELECT * FROM ".S_CATEGORIES." order by Ordre asc";
foreach($oSql->GetAll($rR) as $k => $v) {
	$aChainesCategories[$v['id']] = array('id' => $v['id'],'Titre' => hs($v['Titre']),'TitreUrl' => hs($v['TitreUrl']),'Chaines' => hs($v['Chaines']),'ChainesPrenium' => hs($v['ChainesPrenium']));
	$aChainesCategoriesInversees[$v['TitreUrl']] = array('id' => $v['id'],'Titre' => hs($v['Titre']),'TitreUrl' => hs($v['TitreUrl']),'Chaines' => hs($v['Chaines']),'ChainesPrenium' => hs($v['ChainesPrenium']));
}

$rR = "SELECT * FROM ".S_LOGIN." WHERE Etat = 2 and DateModeration < now() order by idMbr asc";
foreach($oSql->GetAll($rR) as $k => $v) {
	$rR = "UPDATE ".S_LOGIN." set Priorite = 1 where idMbr = ?";
	$aArg = array($v['idMbr']);
	$oSql->Query($rR,$aArg);
}


$rR = "SELECT * FROM ".S_LOGIN." WHERE Etat = 2 and Priorite = 1 order by idMbr asc";
$sRow = $oSql->GetAll($rR);
foreach($sRow as $k => $v)
	MajYoutubeAccount($v,1);

if($nHour == 5 or $nHour == 11 or $nHour == 17 or $nHour == 23) {
	$rR = "SELECT * FROM ".S_LOGIN." WHERE Etat = 1 and Prenium = 1 order by idMbr asc";
	$sRow = $oSql->GetAll($rR);
	foreach($sRow as $k => $v)
		MajYoutubeVideo($v,1);
}


if($nMinute == 1 or $nMinute == 01) {
	
	$rR = "SELECT * FROM 0_Home where id = 1";
	$sRHome = $oSql->GetLine($rR);

	function GetMyNewHome() {
		global $sRHome,$aChainesCategories;
		for($i=1;$i<=10;$i++) {
			if($i<=3) {
				$aRetour[6][$i] = 0;
				$aRetour[4][$i] = 0;
				${'nLastCatVoulu6_'.$i} = $sRHome['CatVoulu6_'.$i];
				${'nLastCatVoulu4_'.$i} = $sRHome['CatVoulu4_'.$i];
			}
			$aRetour[2][$i] = 0;
			${'nLastCatVoulu2_'.$i} = $sRHome['CatVoulu2_'.$i];
		}
		$aCatsUses = array();
		$aCats = $aChainesCategories;
		shuffle($aCats);
		$nMin = 6;
		$nI = 1;
		foreach($aCats as $k => $v) {
			if($v['ChainesPrenium'] > $nMin and !isset($aCatsUses[$v['id']]) and ${'nLastCatVoulu'.$nMin.'_'.$nI} != $v['id']) {
				$aCatsUses[$v['id']] = 1;
				$aRetour[$nMin][$nI] = $v['id'];
				$nI++;
				if($nI == 4)
					break;
			}
		}
		$aCats = $aChainesCategories;
		shuffle($aCats);
		$nMin = 4;
		$nI = 1;
		foreach($aCats as $k => $v) {
			if($v['ChainesPrenium'] > $nMin and !isset($aCatsUses[$v['id']]) and ${'nLastCatVoulu'.$nMin.'_'.$nI} != $v['id']) {
				$aCatsUses[$v['id']] = 1;
				$aRetour[$nMin][$nI] = $v['id'];
				$nI++;
				if($nI == 4)
					break;
			}
		}
		$aCats = $aChainesCategories;
		shuffle($aCats);
		$nMin = 2;
		$nI = 1;
		foreach($aCats as $k => $v) {
			if($v['ChainesPrenium'] > $nMin and !isset($aCatsUses[$v['id']]) and ${'nLastCatVoulu'.$nMin.'_'.$nI} != $v['id']) {
				$aCatsUses[$v['id']] = 1;
				$aRetour[$nMin][$nI] = $v['id'];
				$nI++;
				if($nI == 11)
					break;
			}
		}
		for($i=1;$i<=10;$i++) {
			if($i<=3) {
				if(empty($aRetour[6][$i]) or empty($aRetour[4][$i]))
					return false;
			}
			if(empty($aRetour[2][$i]))
				return false;
		}
		return $aRetour;
	}
	
	$aRetour = false;
	while($aRetour == false) {
		$aRetour = GetMyNewHome();
	}
	$aArg = array();
	$rR = "UPDATE 0_Home SET ";
	foreach($aRetour as $k => $v) {
		foreach($v as $kk => $vv) {
			if(!empty($aArg))
				$rR .= ',';
			$rR .= "CatVoulu".$k."_".$kk." = ?";
			$aArg[] = $vv;
		}
	}
	$rR .= " WHERE id = 1";
	$oSql->Query($rR,$aArg);
}
?>