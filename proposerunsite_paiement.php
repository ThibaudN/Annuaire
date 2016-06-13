<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(!isset($_SESSION['Connected']['idUnique'])) {
	header('location: /404.php');
	exit;
}
$rR = "SELECT * FROM ".S_FICHES." where KeyGen = ? and (Etat = 4 or Etat = 3)";
$aArg = array($_SESSION['Connected']['idUnique']);
$sR = $oSql->GetLine($rR,$aArg);
if(empty($sR)) {
	header('location: /404.php');
	exit;
}
elseif(!empty($_GET['saute'])) {
	echo 'hm';
	$rR = "SELECT F.*,C.Titre as NomCat FROM ".S_FICHES." F JOIN ".S_CATEGORIES." C on C.id = F.idCategorie where F.KeyGen = ?";
	$aArg = array($sR['KeyGen']);
	$sR = $oSql->GetLine($rR,$aArg);
	Prenium($sR);
	header('location: '.PAGE.'?done=1');
	exit;
}
$aHead = array('PageTitre' => 'Proposer un site','MetaRobots' => 'noindex','MetaKeyword' => '','MetaDescription' => '');
head($aHead);

echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><div class="content"><div class="content-box"><div class="content-box-body"><h2 class="page">Paiement</h2>';
if(!empty($_GET['done']) and is_numeric($_GET['done'])) {
	echo '<div class="alert alert-success"><p>Votre paiement a bien été effectué. Votre site sera validé dans les 24 heures.</p><p>Vous allez être redirigé dans quelques secondes.</p></div><script type="text/javascript">setTimeout(myRedirect, 6000); function myRedirect() {document.location = \''.PAGE.'\';}</script>';
}
elseif(!empty($_GET['erreur']) and is_numeric($_GET['erreur'])) {
	echo '<div class="alert alert-success"><p>Votre paiement a bien été effectué. Votre site sera validé dans les 24 heures.</p><p>Vous allez être redirigé dans quelques secondes.</p></div><script type="text/javascript">setTimeout(myRedirect, 6000); function myRedirect() {document.location = \''.PAGE.'\';}</script>';
}
else {
	if($sR['Etat'] == 4) {
		$sDescriptionPaiement = s($sRConfig['DescriptionPaiement']);
		$sDescriptionPaiement = str_replace("::PRIX",hs($sRConfig['PaypalPrix']).' '.hs($sRConfig['PaypalMonnaie']),$sDescriptionPaiement);
		
		echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">'.$sDescriptionPaiement.'</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12"><form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top"><input type="hidden" name="cmd" value="_s-xclick"><input type="hidden" name="hosted_button_id" value="'.hs($sRConfig['PaypalBoutonId']).'"><input type="hidden" name="custom" value="'.hs($sR['KeyGen']).'" /><input type="image" src="https://www.paypalobjects.com/fr_FR/FR/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal, le réflexe sécurité pour payer en ligne"><img alt="" border="0" src="https://www.paypalobjects.com/fr_FR/i/scr/pixel.gif" width="1" height="1"></form></div></div>';
	}
	else {
		echo '<div class="alert alert-warning"><p>Votre fiche est en attente de modération.</p></div>';
	}

	echo '</div></div><div class="content-box"><div class="content-box-body"><h2>Votre fiche</h2><p><strong>Catégorie</strong> :: '.hs($aCategories[$sR['idCategorie']]['Titre']).'<br /><strong>Url</strong> :: '.hs($sR['Url']).'<br /><strong>Titre</strong> :: '.hs($sR['Titre']).'<br /><br /><strong>Description Courte</strong> :: '.nl2br(hs($sR['Description1'])).'<br /><br /><strong>Description Longue</strong> :: '.nl2br(hs($sR['Description2'])).'<br />';
}
echo '</div></div></div></div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>