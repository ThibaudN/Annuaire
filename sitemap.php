<?php
require __DIR__."/fonctions.php";
header("Content-Type: text/html; charset=UTF-8");
header('Content-Type: application/xml');
$aFreq = array(1 => 'daily','weekly','monthly');
$aPriority = array(1 => '1','0.6','0.2');
$sDate = date("Y-m-d",$_SERVER['REQUEST_TIME']);
function Aff($sLien,$nF,$nP) {
	global $aFreq,$sDate,$aPriority;
	echo "<url>
<loc>".URLSITE.$sLien."</loc>
<lastmod>".$sDate."</lastmod>
<changefreq>".$aFreq[$nF]."</changefreq>
<priority>".$aPriority[$nP]."</priority>
</url>
";
}
echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
Aff('',1,1);
foreach($aCategories as $k => $v)
	Aff($v['TitreUrl'].'.html',1,1);

$rR = "SELECT F.* FROM ".S_FICHES." F WHERE F.Etat = 1";
foreach($oSql->GetAll($rR) as $k => $v) {
	Aff(s($aCategories[$v['idCategorie']]['TitreUrl']).'/'.s($v['TitreUrl']).'.html',1,1);
}
echo "</urlset>\n";
?>