<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_GET['id']))
	exit;
elseif(!isset($aCategoriesInversees[hs($_GET['id'])]))
	exit;

$aCat = $aCategoriesInversees[hs($_GET['id'])];

$nCat = $aCat['id'];
$sMetaTitre = s($aCat['MetaTitle']);
$sMetaDescription = s($aCat['MetaDescription']);
$sTitre = s($aCat['Titre']);
$sTitreUrl = s($aCat['TitreUrl']);
$nParPage = s($aCat['ParPage']);

$sDescriptionH2 = s($aCat['DescriptionH2']);
$sDescription = s($aCat['Description']);
$sSitesH2 = s($aCat['SitesH2']);

$rR = "SELECT * FROM ".S_FICHES." WHERE Etat = 1 AND idCategorie = ?";
$rR2 = "SELECT count(id) as nbr FROM ".S_FICHES." WHERE Etat = 1 AND idCategorie = ?";
$aArg = array($nCat);
$rR .= " ORDER BY DateValidation DESC";
$sRo = $oSql->GetLine($rR2,$aArg);
$nTotalEntrees = $sRo['nbr'];
$nPageMax = 0;
if(!empty($nTotalEntrees))
	$nPageMax = ceil($nTotalEntrees/$nParPage);
if(empty($_GET['nPage']) or !is_numeric($_GET['nPage']) or $_GET['nPage'] > $nPageMax or $_GET['nPage'] <= 1) {
	$nPageActuelle = 1;
	$nIrlActuelle = 0;
	$nDebut = 0;
}
else {
	$nPageActuelle = $_GET['nPage'];
	$nIrlActuelle = $_GET['nPage'] - 1;
	$nDebut = $nIrlActuelle * $nParPage;
}
$rR .= " limit ".$nDebut.",".$nParPage;
$sRow = $oSql->GetAll($rR,$aArg);


$sCanonical = URLSITE.$sTitreUrl.'.html';
$sPrev = '';
$sNext = '';
if($nPageActuelle > 1) {
	$nMoinsUn = $nPageActuelle - 1;
	$sPrev = URLSITE.$sTitreUrl.'-page-'.$nMoinsUn.'.html';
	$sMetaTitre .= ' - Page '.$nPageActuelle;
	$sMetaDescription .= ' - Page '.$nPageActuelle;
	$sCanonical = URLSITE.$sTitreUrl.'-page-'.$nPageActuelle.'.html';
	if($nPageActuelle == 2)
		$sPrev = URLSITE.$sTitreUrl.'.html';
}
if($nPageActuelle < $nPageMax) {
	$nPlusUn = $nPageActuelle + 1;
	$sNext = URLSITE.$sTitreUrl.'-page-'.$nPlusUn.'.html';
}

$aHead = array(
	'PageTitre' => $sMetaTitre,
	'MetaRobots' => 'index,follow',
	'MetaKeyword' => '',
	'MetaDescription' => $sMetaDescription,
	'canonical' => $sCanonical,
	'prev' => $sPrev,
	'next' => $sNext,
);
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><div class="myBloc"><h2>'.$sDescriptionH2.'</h2><p>'.nl2br($sDescription).'</p></div><div class="myBloc"><h2>'.$sSitesH2.'</h2></div>';
foreach($sRow as $k => $v) {
	AfficherFiche($v);
}
echo '</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>