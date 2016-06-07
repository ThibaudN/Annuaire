<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
$aHead = array('PageTitre' => 'Mentions légales','MetaRobots' => 'noindex','MetaKeyword' => '','MetaDescription' => '');
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><h2>Mentions Légales</h2><div class="text-center"><img src="http://placehold.it/500x350" alt="" class="" /></div></div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>