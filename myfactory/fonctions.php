<?php
$nTimeExc = microtime(true);
ini_set('session.use_cookies','1');
ini_set('session.use_only_cookies','1');
ini_set('url_rewriter.tags','');
ini_set('session.use_trans_sid','0');
ini_set('date.timezone', 'Europe/Paris');
ini_set('display_errors','1');
error_reporting(E_ALL);
#
if(!file_exists(__DIR__."/../php-include/d_config.php")) {
	header('location: z_i.php');
	exit;
}
include __DIR__."/../php-include/f_base.php";#base
include __DIR__."/../php-include/d_config.php";#base
include __DIR__."/../php-include/c_pdo.php";#class pdo
include __DIR__."/../php-include/c_email.php";#email
session_start();
#
header("Content-Type: text/html; charset=UTF-8");
#
$oSql = mypdo::GetInstance(DB_HOST,DB_LOGIN,DB_PASS,DB_BASE);
$rR = "SELECT * FROM ".S_CONFIG." where id = 1";
$sRConfig = $oSql->GetLine($rR);

if(!isset($_SESSION['ADMConnected']['bCon'])) {
	$_SESSION['ADMConnected']['bCon'] = 0;
}
$oUser = '';
$aEtat = array(1 => 'En Ligne','Attente Miniature','Attente Modération','Attente Paiement','Attente mail','Refusé');
$aRaisonsRefus = array(1 => 'Duplicate Content','Html non autorisé','Liens non autorisés','Mauvaise catégorie','non respect des règles','Suppression par l\'admin');
if(!empty($_SESSION['ADMConnected']['bCon'])) {
	#categories
	$rR = "SELECT * FROM ".S_CATEGORIES." WHERE Online = 1 order by Titre asc";
	foreach($oSql->GetAll($rR) as $k => $v) {
		$aTemp = array('id' => $v['id'],'Titre' => hs($v['Titre']),'TitreUrl' => hs($v['TitreUrl']),'DescriptionH2' => s($v['DescriptionH2']),'Description' => s($v['Description']),'SitesH2' => s($v['SitesH2']),'Sites' => hs($v['Sites']),'ParPage' => hs($v['ParPage']),'MetaTitle' => s($v['MetaTitle']),'MetaDescription' => s($v['MetaDescription']));
		$aCategories[$v['id']] = $aTemp;
		$aCategoriesInversees[$v['TitreUrl']] = $aTemp;
	}
}

function head($aHead = array()) {
	global $oUser,$sRConfig;
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT"); 
	header("Cache-Control: no-store, no-cache,must-revalidate"); 
	header("Cache-Control: post-check=0, pre-check=0",false); 
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=UTF-8");
	echo '<!DOCTYPE html><html lang="fr"><head><meta charset="UTF-8"><base href="'.URLSITEADM.'" /><title>'.$sRConfig['NomSite'].' - '.s($aHead['PageTitre']).'</title><link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css" /><link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css" /><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="noindex,nofollow" /></head><body><header>';
	if(!empty($_SESSION['ADMConnected']['bCon'])) {
		echo '<nav class="navbar navbar-inverse navbar-fixed-top"><div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="true"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a href="index.php" title="Accueil" class="navbar-brand">'.$sRConfig['NomSite'].'</a></div><div class="collapse navbar-collapse scrollable-menu" id="bs-example-navbar-collapse-1"><ul class="nav navbar-nav"><li><a href="configuration.php" title="Configuration">Configuration</a></li></ul><ul class="nav navbar-nav navbar-right"><li icon="fa fa-sign-out" class="last"><a href="login.php?logout=1"><i class="fa fa-sign-out"></i> Deconnexion</a></li></ul></div></div></nav>';
	}
	else {
		echo '<nav class="navbar navbar-inverse navbar-fixed-top"><div class="container"><div class="navbar-header"><button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar"><span class="sr-only">Toggle navigation</span><span class="icon-bar"></span><span class="icon-bar"></span><span class="icon-bar"></span></button><a class="navbar-brand" href="#">'.$sRConfig['NomSite'].'</a></div><div id="navbar" class="navbar-collapse collapse">&nbsp;</div></div></nav>';
	}
	echo '</header><div id="main" style="padding-top:80px;"><div class="container">';

}

function bot($aBot = array()) {
	global $oUser,$oSql;
	echo '</div></div><script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.js"></script><script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/js/bootstrap.min.js"></script></body></html>';
}


function Pagination($nPageActuelle,$nNbPage,$sPage,$sArgument = 'nPage') {
	if($nNbPage > 1) {
		echo '<div class="clearfix"></div><div class="text-center"><ul class="pagination">';
		if($nPageActuelle > 1) {
			$nMoinsUn = $nPageActuelle - 1;
			echo '<li><a href="'.$sPage.$sArgument.'='.$nMoinsUn.'" title="Page '.$nMoinsUn.'">&laquo;</a></li>';
		}
		for($i=1;$i<=$nNbPage;$i++) {
			$sClass = '';
			if($i == $nPageActuelle)
				$sClass = ' class="active"';
			echo '<li'.$sClass.'><a href="'.$sPage.$sArgument.'='.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
		}
		if($nPageActuelle < $nNbPage) {
			$nPlusUn = $nPageActuelle + 1;
			echo '<li><a href="'.$sPage.$sArgument.'='.$nPlusUn.'" title="Page '.$nPlusUn.'">&raquo;</a></li>';
		}
		echo '</ul></div>';
	}
}

?>