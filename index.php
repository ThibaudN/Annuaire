<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";

#<img src="http://api.thumbsniper.com/v3/thumbnail/300/plain/?url=https://www.thumbsniper.com" alt="ThumbSniper.com" title="ThumbSniper.com" />

/*
if(!empty($_GET['actionRecherche']) and is_numeric($_GET['actionRecherche'])) {
	if(empty($_POST['Rechercher'])) {
		header('location: /');
		exit;
	}
	$_SESSION['Rechercher'] = hs($_POST['Rechercher']);
	header('location: /recherche.php');
	exit;
}*/

$aHead = array(
	'PageTitre' => s($sRConfig['HomeMetaTitle']),
	'MetaRobots' => 'noindex,nofollow',
	'MetaKeyword' => '',
	'MetaDescription' => s($sRConfig['HomeMetaDescription'])
);
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><h2>'.s($sRConfig['HomeDescriptionH2']).'</h2><p>'.nl2br(s($sRConfig['HomeDescription'])).'</p>';


echo '<h2>'.s($sRConfig['HomeSitesH2']).'</h2>';
$rR = "SELECT * FROM ".S_FICHES." WHERE Etat = 1 order by DateValidation DESC LIMIT ".$sRConfig['HomeSitesAffiches'];
$aArg = array();
foreach($oSql->GetAll($rR,$aArg) as $k => $v) {
	AfficherFiche($v);
}
echo '</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>