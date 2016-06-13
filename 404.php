<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
header("HTTP/1.0 404 Not Found");
$aHead = array(
	'PageTitre' => '404',
	'MetaRobots' => 'noindex,nofollow',
	'MetaKeyword' => '',
	'MetaDescription' => ''
);
head($aHead);
echo '<div class="row"><div class="col-md-12"><div class="content"><div class="content-box"><div class="content-box-head"><h3>404</h3></div><div class="error-page content-box-body"><h2>Oops!</h2><h3>La page demandée n\'existe pas.</h3><div class="back-home-section"><i class="fa fa-reply send-comment-btn"></i><a href="/" class="back-home-a"> Retour à l\'annuaire</a></div></div></div></div></div></div>';
bot();
?>