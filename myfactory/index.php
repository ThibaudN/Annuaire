<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_SESSION['ADMConnected']['bCon'])) { header('location: login_form.php'); exit; }

elseif(!empty($_GET['actionVue']) and is_numeric($_GET['actionVue'])) {
	$rR = "UPDATE ".S_ADM_ALERTES." SET Etat = 1 where Etat = 0";
	$aArg = array();
	$oSql->Query($rR,$aArg);
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['nEtat']) and is_numeric($_GET['nEtat']) and !empty($_GET['id']) and is_numeric($_GET['id'])) {
	$rR = "SELECT * FROM ".S_CREDITS_PROMOTION." WHERE id = ?";
	$aArg = array($_GET['id']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(empty($sR)) {
		header('location: '.PAGE);
		exit;
	}
	elseif($_GET['nEtat'] == 1) {
		$oTime = new DateTimeFrench($sR['DateExpire']);
		$rR = "UPDATE ".S_CREDITS_PROMOTION." SET Etat = 1 where id = ?";
		$aArg = array($sR['id']);
		$oSql->Query($rR,$aArg);
		#notif user
		NotificationsUser($sR['idMbr'],1,'Votre url pour le gain des '.GAIN_CREDITS_PUBLICITETWITTER.' crédits a été validé. Vous recevrez vos crédits '.$oTime->format('l \à H\hi').' si le lien est toujours présent.');
	}
	elseif($_GET['nEtat'] == 2) {
		$rR = "DELETE FROM ".S_CREDITS_PROMOTION." WHERE id = ?";
		$aArg = array($sR['id']);
		$oSql->Query($rR,$aArg);
		NotificationsUser($sR['idMbr'],1,'Votre url pour le gain des '.GAIN_CREDITS_PUBLICITETWITTER.' crédits a été refusé.');
	}
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['nEtatSponso']) and is_numeric($_GET['nEtatSponso']) and !empty($_GET['id']) and is_numeric($_GET['id'])) {
	$rR = "SELECT * FROM ".S_POSTSPONSOS." WHERE id = ? and Etat = 2";
	$aArg = array($_GET['id']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(empty($sR)) {
		header('location: '.PAGE);
		exit;
	}
	elseif($_GET['nEtatSponso'] == 3) {
		$oTime = new DateTimeFrench($sR['DatePostExpire']);
		$rR = "UPDATE ".S_POSTSPONSOS." SET Etat = 3 where id = ?";
		$aArg = array($sR['id']);
		$oSql->Query($rR,$aArg);
		#notif user
		NotificationsUser($sR['idMbrPost'],1,'Vos urls pour la publicité entre membres ont été validé. Vous recevrez vos crédits '.$oTime->format('l \à H\hi').' si le lien est toujours présent.');
		NotificationsUser($sR['idMbr'],1,'Votre publicité entre membres est en cours de diffusion.');
	}
	elseif($_GET['nEtatSponso'] == 5) {
		$rR = "UPDATE ".S_POSTSPONSOS." SET Facebook = '',Twitter = '',Etat = 0,DatePostExpire = '0000-00-00 00:00:00',idMbrPost = '' where id = ?";
		$aArg = array($sR['id']);
		$oSql->Query($rR,$aArg);
		#
		NotificationsUser($sR['idMbrPost'],1,'Vos urls pour la publicité entre membres ont été refusé...');
		NotificationsUser($sR['idMbr'],1,'L\'utilisateur qui devait diffuser votre pub n\'a pas mis des urls qui respectaient les règles.');

	}
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['deleteActualite']) and is_numeric($_GET['deleteActualite'])) {
	$rR = "DELETE FROM ".S_ACTUALITES." WHERE id = ?";
	$aArg = array($_GET['deleteActualite']);
	$oSql->Query($rR,$aArg);
	header('location: '.PAGE);
	exit;
}
$aHead = array('PageTitre' => 'Accueil');
head($aHead);

echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">';



$nCat = 0;
$sUrlPagination = '?nPage';
$sTitre = 'Les fiches';
$nParPage = 20;
$rR = "SELECT * FROM ".S_FICHES." WHERE 1";
$rR2 = "SELECT count(id) as nbr FROM ".S_FICHES." WHERE 1";
$aArg = array();
if(!empty($_GET['nCat']) and is_numeric($_GET['nCat']) and isset($aCategories[$_GET['nCat']])) {
	$nCat = $_GET['nCat'];
	$sUrlPagination = '?nCat='.$nCat.'&nPage';
	$sTitre = 'Les fiches '.$aCategories[$nCat]['Titre'];
	$rR .= " AND idCategorie = ?";
	$rR2 .= " AND idCategorie = ?";
	$aArg = array($nCat);
}
$rR .= " ORDER BY id DESC";
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



echo '<h2>'.$sTitre.'</h2><table class="table table-striped"><thead><tr><th width="6%">#</th><th>Site</th><th>Categorie</th><th>Etat</th><th width="18%">Date</th></tr></thead><tbody>';
foreach($sRow as $k => $v) {
	$oTime = new DateTimeFrench($v['Date']);
	echo '<tr><td><a href="fiche.php?id='.$v['id'].'">#'.s($v['id']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($v['Url']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($aCategories[$v['idCategorie']]['Titre']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($aEtat[$v['Etat']]).'</a></td><td>'.$oTime->format('l \à H\hi').'</td></tr>';
}
echo '</tbody></table>';
Pagination($nPageActuelle,$nPageMax,PAGE,$sUrlPagination);


$rR = "SELECT count(id) as nbr from ".S_FICHES." where Etat = 6";
$aArg = array();
$sRCountRefuses = $oSql->GetLine($rR,$aArg);

$rR = "SELECT count(id) as nbr from ".S_FICHES." where Etat = 1 or Etat = 2";
$aArg = array();
$sRCountEnLigne = $oSql->GetLine($rR,$aArg);

$rR = "SELECT count(id) as nbr from ".S_FICHES." where Etat = 3";
$aArg = array();
$sRCountModeration = $oSql->GetLine($rR,$aArg);

$rR = "SELECT count(id) as nbr from ".S_FICHES." where Etat = 4 or Etat = 5";
$aArg = array();
$sRCountInscription = $oSql->GetLine($rR,$aArg);

echo '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<h2>Stats</h2><ul><li>'.hs($sRCountModeration['nbr']).' Attente Modération</li><li>'.hs($sRCountEnLigne['nbr']).' Acceptés</li><li>'.hs($sRCountRefuses['nbr']).' Refusés</li><li>'.hs($sRCountInscription['nbr']).' Processus</li></ul><h2>Catégories</h2><ul class="list-group">';
foreach($aCategories as $k => $v) {
	echo '<li class="list-group-item';
	if($nCat == $v['id'])
		echo ' list-group-item-info';
	echo '"><a href="'.PAGE.'?nCat='.$v['id'].'" title="'.s($v['Titre']).'">'.s($v['Titre']).'</a> <span class="badge">'.$v['Sites'].'</span></li>';
}
echo '</ul>

</div></div>';




bot();
?>