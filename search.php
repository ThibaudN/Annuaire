<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(!empty($_GET['action']) and is_numeric($_GET['action'])) {
	if(empty($_POST['Rechercher']) or strlen($_POST['Rechercher']) < 3) {
		header('location: '.URLSITE);
		exit;
	}
	$_SESSION['Search'] = hs($_POST['Rechercher']);
	header('location: '.PAGE);
	exit;
}
elseif(empty($_SESSION['Search'])) {
	header('location: '.URLSITE);
	exit;
}
$aHead = array(
	'PageTitre' => 'Rechercher',
	'MetaRobots' => 'noindex,nofollow',
	'MetaKeyword' => '',
	'MetaDescription' => ''
);
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><div class="myBloc"><h2>Rechercher</h2></div>';
$rR = "SELECT * FROM ".S_FICHES." WHERE Etat = 1 and (Titre LIKE ? or Url LIKE ? or Description1 LIKE ? or Description2 LIKE ?) GROUP BY id order by DateValidation DESC";
$sSearch = '%'.$_SESSION['Search'].'%';
$aArg = array($sSearch,$sSearch,$sSearch,$sSearch);
$sRow = $oSql->GetAll($rR,$aArg);
if(empty($sRow))
	echo '<div class="alert alert-warning"><p>Votre recherche n\'a retourné aucun résultat.</p></div>';
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