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
	'MetaRobots' => 'index,follow',
	'MetaKeyword' => '',
	'MetaDescription' => s($sR['MetaDescription'])
);
head($aHead);
$oDate = new DateTimeFrench($sR['DateValidation']);

echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><div class="content"><article class="post-article"><h2 class="post-title">'.hs($sR['Titre']).'</h2><img src="'.$sR['Image'].'" alt="'.hs($sR['Titre']).'" class="img-responsive pull-right" style="max-width:350px;margin-left:20px;" /><p class="post-text">'.nl2br(hs($sR['Description2'])).'</p><ul class="share-post"><li><i class="fa fa-arrow-up"></i><span>Voir le site : <a href="'.hs($sR['Url']).'" title="'.hs($sR['Url']).'">'.hs($sR['Url']).'</a></span></li></ul><ul class="post-info"><li><i class="fa fa-calendar-o"></i><span><time datetime="'.substr($sR['DateValidation'],0,10).'" itemprop="datePublished">'.$oDate->format('l j F Y').'</time></span></li><li><i class="fa fa-bars"></i><a href="/'.s($aCategories[$sR['idCategorie']]['TitreUrl']).'.html"><span>'.s($aCategories[$sR['idCategorie']]['Titre']).'</span></a></li></ul></article></div>';

#autre fiches meme catégories ?
/*$rR = "SELECT * FROM ".S_FICHES." WHERE idCategorie = ? and Etat = 1 and id != ? order by rand() limit 3";
$aArg = array($sR['idCategorie'],$sR['id']);
$sRow = $oSql->GetAll($rR,$aArg);
$nCount = count($sRow);
if($nCount >= 2) {
	echo '<h2 style="margin-top:25px;">Découvrez des sites similaires</h2><div class="row">';
	for($i=0;$i<=($nCount-1);$i++)
		echo '<div class="col-lg-4 col-md-4 col-sm-12 col-xs-12"><a href="/'.hs($aCategories[$sRow[$i]['idCategorie']]['TitreUrl']).'/'.hs($sRow[$i]['TitreUrl']).'.html" title="Voir la fiche"><img src="'.hs($sRow[$i]['Image']).'" alt="'.hs($sRow[$i]['Titre']).'" class="img-responsive" /></a><p>'.hs($sRow[$i]['Titre']).'</p></div>';
	echo '</div>';
}
*/

echo '</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>