<?php
/*LastModif: 2012-01-28 23:00:00*/
$nTimeExc = microtime(true);
ini_set('session.use_cookies','1');
ini_set('session.use_only_cookies','1');
ini_set('url_rewriter.tags','');
ini_set('session.use_trans_sid','0');
ini_set('date.timezone', 'Europe/Paris');
ini_set('display_errors','1');
error_reporting(E_ALL);
#
include __DIR__."/php-include/d_base.php";#constantes de base
include __DIR__."/php-include/f_base.php";#base
include __DIR__."/php-include/d_sql.php";#identifisant sql
include __DIR__."/php-include/c_pdo.php";#class pdo
include __DIR__."/php-include/c_email.php";#class email
session_start();
#
header("Content-Type: text/html; charset=UTF-8");
#
$oSql = mypdo::GetInstance(DB_HOST,DB_LOGIN,DB_PASS,DB_BASE);
$rR = "SELECT * FROM ".S_CONFIG." where id = 1";
$sRConfig = $oSql->GetLine($rR);



#categories
$rR = "SELECT * FROM ".S_CATEGORIES." WHERE Online = 1 order by Titre asc";
foreach($oSql->GetAll($rR) as $k => $v) {
	$aTemp = array('id' => $v['id'],'Titre' => hs($v['Titre']),'TitreUrl' => hs($v['TitreUrl']),'DescriptionH2' => s($v['DescriptionH2']),'Description' => s($v['Description']),'SitesH2' => s($v['SitesH2']),'Sites' => hs($v['Sites']),'ParPage' => hs($v['ParPage']),'MetaTitle' => s($v['MetaTitle']),'MetaDescription' => s($v['MetaDescription']));
	$aCategories[$v['id']] = $aTemp;
	$aCategoriesInversees[$v['TitreUrl']] = $aTemp;
}


function head($aHead = array()) {
	global $sRConfig,$oSql,$nHour,$nMinute,$nSecond,$nDay,$nMonth,$nYear,$aCategories;
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") ." GMT"); 
	header("Cache-Control: no-store, no-cache,must-revalidate"); 
	header("Cache-Control: post-check=0, pre-check=0",false); 
	header("Pragma: no-cache");
	header("Content-Type: text/html; charset=UTF-8");
	$sRobots = 'noindex,nofollow';
	if(!empty($aHead['MetaRobots']))
		$sRobots = $aHead['MetaRobots'];
	$sUrl = URLSITE;
	if(!empty($aHead['canonical']))
		$sUrl = $aHead['canonical'];
	
	$sImage = URLSITECSS.'images/logo_reseauxsociaux.jpg';
	if(!empty($aHead['Image']))
		$sImage = $aHead['Image'];
	
	
	echo '<!DOCTYPE html><html hreflang="fr"><head><meta charset="UTF-8"><base href="'.URLSITE.'" /><title>'.s($aHead['PageTitre']).'</title><link type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" rel="stylesheet"><link rel="shortcut icon" href="'.URLSITE.'favicon.ico"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="robots" content="'.$sRobots.'" /><meta name="description" content="'.s($aHead['MetaDescription']).'" /><meta name="keywords" content="'.s($aHead['MetaKeyword']).'" />';
	
	if(isset($aHead['canonical']) and !empty($aHead['canonical']))
		echo '<link rel="canonical" href="'.$aHead['canonical'].'" />';
	if(isset($aHead['prev']) and !empty($aHead['prev']))
		echo '<link rel="prev" href="'.$aHead['prev'].'" />';
	if(isset($aHead['next']) and !empty($aHead['next']))
		echo '<link rel="next" href="'.$aHead['next'].'" />';

	#facebook
	echo '<meta property="og:type" content="website" /><meta property="og:title" content="'.$aHead['PageTitre'].'" /><meta property="og:url" content="'.$sUrl.'" /><meta property="og:description" content="'.$aHead['MetaDescription'].'" /><meta property="og:image" content="'.$sImage.'" />';
	#twitter
	echo '<meta name="twitter:card" content="summary_large_image"><meta name="twitter:site" content="'.$sUrl.'"><meta name="twitter:title" content="'.$aHead['PageTitre'].'"><meta name="twitter:description" content="'.$aHead['MetaDescription'].'"><meta name="twitter:image" content="'.$sImage.'">';
	echo '</head><body>';
	
	echo '<div class="container"><div class="blog-header"><h1 class="blog-title"><a href="/" title="">'.hs($sRConfig['NomSite']).'</a></h1><p class="lead blog-description">'.hs($sRConfig['TagLine']).'</p></div>';
	

}

function bot($aBot = array()) {
	global $sRConfig;
	echo '</div>';
	GoogleAnalytics();
	if(PAGE == 'proposerunsite.php') {
		echo '<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script><script type="text/javascript">$(document).ready(function() {$(\'#Description1\').keyup(function() {var nombreCaracteres = $(this).val().length;var nombreMots = jQuery.trim($(this).val()).split(\' \').length;if($(this).val() === \'\') { nombreMots = 0; }var msgCaracteres = \' \'+nombreCaracteres+\' caractère\';if(nombreCaracteres > 1) { var msgCaracteres = \' \'+nombreCaracteres+\' caractères\'; }var msgMots = \' \'+nombreMots+\' mot\';if(nombreMots > 1) { var msgMots = \' \'+nombreMots+\' mots\'; }$(\'#CompteurMotDescription1\').text(msgMots);$(\'#CompteurCarDescription1\').text(msgCaracteres);if(nombreCaracteres < '.$sRConfig['CaracteresMinDescription1'].' || nombreCaracteres > '.$sRConfig['CaracteresMaxDescription1'].') { $("#CompteurMotDescription1").attr(\'style\', \'color:red;\');$("#CompteurCarDescription1").attr(\'style\', \'color:red;\'); }else { $("#CompteurMotDescription1").attr(\'style\', \'color:green;\');$("#CompteurCarDescription1").attr(\'style\', \'color:green;\'); }});$(\'#Description2\').keyup(function() {var nombreCaracteres = $(this).val().length;var nombreMots = jQuery.trim($(this).val()).split(\' \').length;if($(this).val() === \'\') { nombreMots = 0; }var msgCaracteres = \' \'+nombreCaracteres+\' caractère\';if(nombreCaracteres > 1) { var msgCaracteres = \' \'+nombreCaracteres+\' caractères\'; }var msgMots = \' \'+nombreMots+\' mot\';if(nombreMots > 1) { var msgMots = \' \'+nombreMots+\' mots\'; }$(\'#CompteurMotDescription2\').text(msgMots);$(\'#CompteurCarDescription2\').text(msgCaracteres);if(nombreCaracteres < '.$sRConfig['CaracteresMinDescription2'].' || nombreCaracteres > '.$sRConfig['CaracteresMaxDescription2'].') { $("#CompteurMotDescription2").attr(\'style\', \'color:red;\');$("#CompteurCarDescription2").attr(\'style\', \'color:red;\'); }else { $("#CompteurMotDescription2").attr(\'style\', \'color:green;\');$("#CompteurCarDescription2").attr(\'style\', \'color:green;\'); }});});</script>';
	}
	
	echo '</body></html>';
}

function AfficherFiche($v) {
	global $aCategories;
	$sUrl = '/'.hs($aCategories[$v['idCategorie']]['TitreUrl']).'/'.hs($v['TitreUrl']).'.html';
	$sImage = hs($v['Image']);
	$sTitre = hs($v['Titre']);
	$sDescription = hs($v['Description1']);
	echo '<div class="media"><div class="media-left"><img class="media-object" src="'.$sImage.'" alt="'.$sTitre.'" style="width:200px;" /></div><div class="media-body"><h3 class="media-heading">'.$sTitre.'</h3><p>'.$sDescription.'</p><p><span class="pull-right"><a href="'.$sUrl.'" title="'.$sTitre.'">Voir la fiche</a></span></p></div></div>';
}


function GoogleAnalytics() {
	global $sRConfig;
	if(!empty($sRConfig['GoogleAnalytics']))
		echo '<script>(function(i,s,o,g,r,a,m){i[\'GoogleAnalyticsObject\']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)})(window,document,\'script\',\'//www.google-analytics.com/analytics.js\',\'ga\');ga(\'create\', \''.$sRConfig['GoogleAnalytics'].'\', \'auto\');ga(\'send\', \'pageview\');</script>';
}

function Pagination($nPageActuelle,$nNbPage,$sPage,$sArgument = 'nPage') {
	if($nNbPage > 1) {
		echo '<div class="pagination-wrapper"><ul class="pagination">';
		if($nPageActuelle > 1) {
			$nMoinsUn = $nPageActuelle - 1;
			echo '<li><a href="'.$sPage.$sArgument.'='.$nMoinsUn.'" title="Page '.$nMoinsUn.'" aria-label="Previous"><span aria-hidden="true"><i class="fa fa-long-arrow-left"></i> </span></a></li>';
		}
		else
			echo '<li><a href="#">&nbsp;</a></li>';
		if($nNbPage <= 10) {
			for($i=1;$i<=$nNbPage;$i++) {
				$sClass = '';
				if($i == $nPageActuelle)
					$sClass = ' class="active"';
				echo '<li'.$sClass.'><a href="'.$sPage.$sArgument.'='.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
			}
		}
		else {
			$nDebut = 1;
			$nFin = 10;
			if($nPageActuelle > 5) {
				$nProvisoire = $nPageActuelle + 5;
				if($nProvisoire > $nNbPage) {
					$nDebut = $nNbPage - 9;
					$nFin = $nNbPage;
				}
				else {
					$nDebut = $nPageActuelle - 4;
					$nFin = $nPageActuelle + 5;
				}
			}
			for($i=$nDebut;$i<=$nFin;$i++) {
				$sClass = '';
				if($i == $nPageActuelle)
					$sClass = ' class="active"';
				echo '<li'.$sClass.'><a href="'.$sPage.$sArgument.'='.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
			}
		}
		if($nPageActuelle < $nNbPage) {
			$nPlusUn = $nPageActuelle + 1;
			echo '<li><a href="'.$sPage.$sArgument.'='.$nPlusUn.'" title="Page '.$nPlusUn.'" aria-label="Next"><span aria-hidden="true"> <i class="fa fa-long-arrow-right"></i></span></a></li>';
		}
		else
			echo '<li><a href="#">&nbsp;</a></li>';
		echo '</ul></div>';
	}
}


?>