<?php
require __DIR__."/fonctions.php";
header("Content-Type: text/html; charset=UTF-8");
header('Content-Type: application/xml');
$aFreq = array(1 => 'daily','weekly','monthly');
$aPriority = array(1 => '1','0.6','0.2');

$aLien = array();
$aLien[] = array('Lien' => '','Freq' => 1,'Priority' => 1);
$aLien[] = array('Lien' => 'forum.php','Freq' => 1,'Priority' => 1);
###################

foreach($aForumCategories as $k => $v)
	$aLien[] = array('Lien' => 'surleforum/'.$v['NomUrl'].'/','Freq' => 1,'Priority' => 1);

$rR = "SELECT F.* FROM ".S_FORUM." F WHERE F.Etat <= 2";
$sRow = $oSql->GetAll($rR);
foreach($sRow as $k => $v) {
	$aLien[] = array('Lien' =>  'surleforum/'.$aForumCategories[$v['idCategorie']]['NomUrl'].'/'.hs($v['TitreUrl']).'/','Freq' => 1,'Priority' => 1);
}

$rR = "SELECT F.* FROM ".S_LOGIN." F WHERE F.Etat = 1";
$sRow = $oSql->GetAll($rR);
foreach($sRow as $k => $v) {
	$aLien[] = array('Lien' => 'profil/'.hs($v['idMbr']).'/','Freq' => 1,'Priority' => 1);
}

###################
$sDate = date("Y-m-d",$_SERVER['REQUEST_TIME']);
$sSiteMap = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
$sSiteMap .= "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
foreach($aLien as $k => $v) {
	$sSiteMap .= "<url>\n";
	$sSiteMap .= "<loc>".URLSITE.$v['Lien']."</loc>\n";
	$sSiteMap .= "<lastmod>".$sDate."</lastmod>\n";
	$sSiteMap .= "<changefreq>".$aFreq[$v['Freq']]."</changefreq>\n";
	$sSiteMap .= "<priority>".$aPriority[$v['Priority']]."</priority>\n";
	$sSiteMap .= "</url>\n";
}
$sSiteMap .= "</urlset>\n";
echo $sSiteMap;
?>