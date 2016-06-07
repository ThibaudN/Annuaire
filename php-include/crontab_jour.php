<?php
$nTimeCronJeuDebut = microtime(true);
$sTimeCronRapport = $nTimeCronJeuDebut.' :: Debut script<br />';
if(empty($sVerifCRONTAB)) {
	$nTimeExc = microtime(true);
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
	#
	header("Content-Type: text/html; charset=UTF-8");
}


$rR = "SELECT * FROM ".S_LOGIN." WHERE Etat = 1 order by idMbr asc";
$sRow = $oSql->GetAll($rR);
echo count($sRow).' users'."\n";
foreach($sRow as $k => $v) {
	MajYoutubeAccount($v,0);
}
?>