<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
include __DIR__."/php-include/c_pdo.php";#class pdo
session_start();
function head($aHead = array()) {
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT"); 
	header("Cache-Control: no-store, no-cache,must-revalidate"); 
	header("Cache-Control: post-check=0, pre-check=0",false); 
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=UTF-8");
	echo '<!DOCTYPE html><html hreflang="fr"><head><meta charset="UTF-8"><title>INSTALL</title><link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow" /></head><body><div class="container">';
}
function bot($aBot = array()) {
	echo '</div></body></html>';
}

if(!empty($_GET['action']) and is_numeric($_GET['action'])) {
	$aChamps = array('Domaine','Url','MailExpediteur','MotDePasse','SqlHost','SqlBase','SqlUser','SqlMotDePasse','SqlPrefix');
	function GetData() {
		global $aChamps;
		foreach($aChamps as $k => $v)
			$_SESSION['Install'][$v] = (!empty($_POST[$v])) ? $_POST[$v] : '';
	}
	foreach($aChamps as $k => $v) {
		if(empty($_POST[$v])) {
			GetData();
			$_SESSION['Install']['Erreur'] = 'Erreur avec la case '.$v.'.';
			header('location: '.PAGE);
			exit;
		}
	}
	$oSql = mypdo::GetInstance($_POST['SqlHost'],$_POST['SqlUser'],$_POST['SqlMotDePasse'],$_POST['SqlBase']);
	$bVerifSql = $oSql->CheckLogin();
	if(empty($bVerifSql)) {
		GetData();
		$_SESSION['Install']['Erreur'] = 'Erreur avec la connexion mysql.';
		header('location: '.PAGE);
		exit;
	}
	#create fichier
	$sFicherConfig = "<?php\n";
	$sFicherConfig .= '$sNameDomain = \''.$_POST['Domaine'].'\';'."\n";
	$sFicherConfig .= '$sNomDomaine = \''.$_POST['Url'].'\';'."\n";
	$sFicherConfig .= 'define(\'NOMDOMAINE\', $sNomDomaine);'."\n";
	$sFicherConfig .= '#url sites'."\n";
	$sFicherConfig .= 'define(\'URLSITE\', \'http://\'.$sNomDomaine.\'/\');'."\n";
	$sFicherConfig .= 'define(\'URLSITECSS\', \'http://\'.$sNomDomaine.\'/static/\');'."\n";
	$sFicherConfig .= 'define(\'URLSITEADM\', \'http://\'.$sNomDomaine.\'/myfactory/\');'."\n";
	$sFicherConfig .= '#mail'."\n";
	$sFicherConfig .= 'define(\'MAILEXPEDITEUR\', \''.$_POST['MailExpediteur'].'\');'."\n";
	$sFicherConfig .= '#myFactoryMotCléLogin'."\n";
	$sFicherConfig .= 'define(\'TOIMEMETUSAIS\', \''.$_POST['MotDePasse'].'\');'."\n";
	$sFicherConfig .= '#sql'."\n";
	$sFicherConfig .= 'define(\'DB_HOST\', \''.$_POST['SqlHost'].'\');'."\n";
	$sFicherConfig .= 'define(\'DB_LOGIN\', \''.$_POST['SqlUser'].'\');'."\n";
	$sFicherConfig .= 'define(\'DB_PASS\', \''.$_POST['SqlMotDePasse'].'\');'."\n";
	$sFicherConfig .= 'define(\'DB_BASE\', \''.$_POST['SqlBase'].'\');'."\n";
	$sFicherConfig .= '#nom des tables'."\n";
	$sFicherConfig .= 'define(\'S_CONFIG\', \''.$_POST['SqlPrefix'].'Config\');'."\n";
	$sFicherConfig .= 'define(\'S_CATEGORIES\', \''.$_POST['SqlPrefix'].'Categories\');'."\n";
	$sFicherConfig .= 'define(\'S_FICHES\', \''.$_POST['SqlPrefix'].'Fiches\');'."\n";
	$sFicherConfig .= 'define(\'S_SIDEBAR\', \''.$_POST['SqlPrefix'].'Sidebar\');'."\n";
	$sFicherConfig .= 'define(\'S_ANTIROBOTS\', \''.$_POST['SqlPrefix'].'AntiRobots\');'."\n";
	$sFicherConfig .= 'define(\'S_PAYPAL\', \''.$_POST['SqlPrefix'].'Paypal\');'."\n";
	$sFicherConfig .= "?>";
	$rFile = fopen(__DIR__.'/php-include/d_config.php','w');
	fwrite($rFile,$sFicherConfig);
	fclose($rFile);
	#create table sql
	$rSQL = file_get_contents('php-include/annuaire.sql',FILE_USE_INCLUDE_PATH);
	$rSQL = str_replace("Prefix_",$_POST['SqlPrefix'],$rSQL);
	$aAllLignes = explode("\n",$rSQL);
	$rR = '';
	$bFinRequete = false;
	foreach($aAllLignes as $sLigne) {
		$rR .= $sLigne;
		$aVerif = explode(';', $sLigne);
		if(sizeof($aVerif) > 1)
			$bFinRequete = true;
		if($bFinRequete) {
			$oSql->Query($rR);
			$rR = '';
			$bFinRequete = false;
	    }
	}
	$_SESSION['ADMConnected']['bCon'] = 1;
	$_SESSION['ADMConnected']['Done'] = 'Tout s\'est bien passé ! Merci de définir les paramètres ci-dessous pour terminer l\'installation.';
	header('location: /myfactory/configuration.php');
	exit;
}


head();
echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">';
$sDomaine = '';
$sUrl = '';
$sMailExpediteur = '';
$sMotDePasse = '$@lùtçàvà';
if(isset($_SERVER['HTTP_HOST'])) {
	$sUrl = $_SERVER['HTTP_HOST'].'';
	$aDomaine = explode('.',$_SERVER['HTTP_HOST']);
	$nCount = count($aDomaine);
	$sDomaine = $aDomaine[$nCount-2].'.'.$aDomaine[$nCount-1];
	$sMailExpediteur = 'no-reply@'.$sDomaine;
}
$sSqlHost = 'localhost';
$sSqlBase = 'myAnnuaire';
$sSqlUser = 'myAnnuaire';
$sSqlMotDePasse = '';
$sSqlPrefix = 'Annuaire_';
if(!empty($_SESSION['Install']['Erreur'])) {
	$sDomaine = $_SESSION['Install']['Domaine'];
	$sUrl = $_SESSION['Install']['Url'];
	$sMailExpediteur = $_SESSION['Install']['MailExpediteur'];
	$sMotDePasse = $_SESSION['Install']['MotDePasse'];
	$sSqlHost = $_SESSION['Install']['SqlHost'];
	$sSqlBase = $_SESSION['Install']['SqlBase'];
	$sSqlUser = $_SESSION['Install']['SqlUser'];
	$sSqlMotDePasse = $_SESSION['Install']['SqlMotDePasse'];
	$sSqlPrefix = $_SESSION['Install']['SqlPrefix'];
	echo '<div class="alert alert-danger">'.$_SESSION['Install']['Erreur'].'</div>';
	unset($_SESSION['Install']);
}
echo '<form action="'.PAGE.'?action=1" method="post" class="" style="margin-bottom:30px;"><h2>Configuration du site</h2><div class="form-group"><label for="Domaine" class="control-label">Domaine</label><input type="text" name="Domaine" id="Domaine" class="form-control" value="'.$sDomaine.'" placeholder="example.com" required></div><div class="form-group"><label for="Url" class="control-label">Url</label><input type="text" name="Url" id="Url" class="form-control" value="'.$sUrl.'" placeholder="www.example.com" required></div><div class="form-group"><label for="MailExpediteur" class="control-label">Mail Expediteur</label><input type="email" name="MailExpediteur" id="MailExpediteur" class="form-control" value="'.$sMailExpediteur.'" placeholder="adresse email utilisée pour l\'envoie des mails" required></div><div class="form-group"><label for="MotDePasse" class="control-label">Mot de Passe backoffice</label><input type="text" name="MotDePasse" id="MotDePasse" class="form-control" value="'.$sMotDePasse.'" placeholder="Mot de passe du back office" required></div><hr /><h2>SQL</h2><div class="form-group"><label for="SqlHost" class="control-label">Sql Host</label><input type="text" name="SqlHost" id="SqlHost" class="form-control" value="'.$sSqlHost.'" placeholder="" required></div><div class="form-group"><label for="SqlBase" class="control-label">Sql Base</label><input type="text" name="SqlBase" id="SqlBase" class="form-control" value="'.$sSqlBase.'" placeholder="" required></div><div class="form-group"><label for="SqlUser" class="control-label">Sql User</label><input type="text" name="SqlUser" id="SqlUser" class="form-control" value="'.$sSqlUser.'" placeholder="" required></div><div class="form-group"><label for="SqlMotDePasse" class="control-label">Sql Mot de Passe</label><input type="text" name="SqlMotDePasse" id="SqlMotDePasse" class="form-control" value="'.$sSqlMotDePasse.'" placeholder="" required></div><div class="form-group"><label for="SqlMotDePasse" class="control-label">Sql Prefix</label><input type="text" name="SqlPrefix" id="SqlPrefix" class="form-control" value="'.$sSqlPrefix.'" placeholder="" required></div><button type="submit" class="btn btn-primary btn-block">Configurer</button></form></div></div>';
bot();
?>