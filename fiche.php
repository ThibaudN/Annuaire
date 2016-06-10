<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";

if(empty($_GET['sFiche']) or empty($_GET['sCat'])) {
	header('location: /404.php');
	exit;
}

$rR = "SELECT * FROM ".S_FICHES." WHERE Etat = 1 and TitreUrl = ?";
$aArg = array($_GET['sFiche']);
$sR = $oSql->GetLine($rR,$aArg);
if(empty($sR)) {
	header('location: /404.php');
	exit;
}
if($aCategories[$sR['idCategorie']]['TitreUrl'] != $_GET['sCat']) {
	header('location: /404.php');
	exit;
}
$aHead = array(
	'PageTitre' => s($sR['MetaTitle']),
	'MetaRobots' => 'noindex,nofollow',
	'MetaKeyword' => '',
	'MetaDescription' => s($sR['MetaDescription'])
);
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><h2>'.hs($sR['Titre']).'</h2><img src="'.$sR['Image'].'" alt="'.hs($sR['Titre']).'" class="img-responsive pull-right" style="max-width:350px;margin-left:20px;" />'.nl2br(hs($sR['Description2'])).'';


#autre fiches meme catégories ?
#$rR = "SELECT * FROM ".S_FICHES." WHERE idCategorie = ? and Etat = 1 and id != ? order by rand() limit 3";
#$aArg = array($sR['idCategorie'],$sR['id']);
$rR = "SELECT * FROM ".S_FICHES." WHERE Etat = 1 order by rand() limit 3";
$aArg = array();
$sRow = $oSql->GetAll($rR,$aArg);
$nCount = count($sRow);
if($nCount >= 2) {
	echo '<br /><h2>Découvrez des sites similaires</h2>';
	$nCol = 4;
	if($nCount == 2)
		$nCol = 6;
	echo '<div class="row">';
	for($i=0;$i<=($nCount-1);$i++)
		echo '<div class="col-lg-'.$nCol.' col-md-'.$nCol.' col-sm-12 col-xs-12"><a href="/'.hs($aCategories[$sRow[$i]['idCategorie']]['TitreUrl']).'/'.hs($sRow[$i]['TitreUrl']).'.html" title="Voir la fiche"><img src="'.hs($sRow[$i]['Image']).'" alt="'.hs($sRow[$i]['Titre']).'" class="img-responsive" /></a><p>'.hs($sRow[$i]['Titre']).'</p></div>';
	echo '</div>';
}


echo '</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>