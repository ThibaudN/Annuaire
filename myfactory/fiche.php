<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_SESSION['ADMConnected']['bCon'])) { header('location: login_form.php'); exit; }
elseif(empty($_GET['id']) or !is_numeric($_GET['id']) and $_GET['id'] < 0) { header('location: index.php'); exit; } 


$rR = "SELECT * FROM ".S_FICHES." where id = ?";
$aArg = array($_GET['id']);
$sR = $oSql->GetLine($rR,$aArg);
if(empty($sR)) { header('location: index.php'); exit; }
elseif(!empty($_GET['email']) and is_numeric($_GET['email'])) {
	if($sR['Etat'] != 5) {
		header('location: '.PAGE.'?id='.$sR['id']);
		exit;
	}
	$sIdUnique = $sR['KeyGenMail'];
	MailInscription($sIdUnique,$sR['Mail'],1);
	$_SESSION['ADMConnected']['Done'] = 'Email d\'activation renvoyé.';
	header('location: '.PAGE.'?id='.$sR['id']);
	exit;
}
elseif(!empty($_GET['emailsaute']) and is_numeric($_GET['emailsaute'])) {
	if($sR['Etat'] != 5) {
		header('location: '.PAGE.'?id='.$sR['id']);
		exit;
	}
	$rR = "UPDATE ".S_FICHES." SET Etat = 4,DateMail = now() where id = ?";
	$aArg = array($sR['id']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'Email validé.';
	header('location: '.PAGE.'?id='.$sR['id']);
	exit;
}
elseif(!empty($_GET['paiement']) and is_numeric($_GET['paiement'])) {
	if($sR['Etat'] != 4) {
		header('location: '.PAGE.'?id='.$sR['id']);
		exit;
	}
	$rR = "SELECT F.*,C.Titre as NomCat FROM ".S_FICHES." F JOIN ".S_CATEGORIES." C on C.id = F.idCategorie where F.id = ?";
	$aArg = array($sR['id']);
	$sR = $oSql->GetLine($rR,$aArg);
	Prenium($sR);
	$_SESSION['ADMConnected']['Done'] = 'Paiement simulé.';
	header('location: '.PAGE.'?id='.$sR['id']);
	exit;
}
elseif(!empty($_GET['accepter']) and is_numeric($_GET['accepter'])) {
	if($sR['Etat'] != 3) {
		header('location: '.PAGE.'?id='.$sR['id']);
		exit;
	}
	/*SCREENSHOT BY THIBAUD*/
	#$rR = "UPDATE ".S_FICHES." SET Etat = 2,DateValidation = now() where id = ?";
	#$aArg = array($sR['id']);
	#$oSql->Query($rR,$aArg);
	#$_SESSION['ADMConnected']['Done'] = 'La fiche a été validée. Elle sera en ligne après génération de la miniature';
	
	/*SCREENSHOT PAR UN TIERS*/
	$sImage = 'http://api.thumbsniper.com/v3/thumbnail/450/plain/?url='.s($sR['Url']);
	$rR = "UPDATE ".S_FICHES." SET Image = ?,Etat = 1,DateValidation = now() where id = ?";
	$aArg = array($sImage,$sR['id']);
	$oSql->Query($rR,$aArg);
	#mise à jour catégorie
	CalculSiteCategorie();
	#envoie email
	$sLien = URLSITE.$aCategories[$sR['idCategorie']]['TitreUrl'].'/'.hs($sR['TitreUrl']).'.html';
	$sSujet = 'site accepté';
	$sBodyHTML = file_get_contents('../php-include/emails/inscription_acceptee.php',FILE_USE_INCLUDE_PATH);
	$sBodyHTML = str_replace("::DOMAINE",NOMDOMAINE,$sBodyHTML);
	$sBodyHTML = str_replace("::OBJET",$sSujet,$sBodyHTML);
	$sBodyHTML = str_replace("::SITE",$sR['Url'],$sBodyHTML);
	$sBodyHTML = str_replace("::LIEN",$sLien,$sBodyHTML);
	$sBody = "Bonjour !\n\nVous avez inscrit votre site ".$sR['Url']." dans notre annuaire ".NOMDOMAINE.".\nVotre site a été accepté. Voici son url personnalisée : ".$sLien."\n\n";
	$aMail = array('FromMail' => MAILEXPEDITEUR,'FromName' => NOMDOMAINE,'Subject' => NOMDOMAINE.' - '.$sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sR['Mail']);
	myMail($aMail);
	$_SESSION['ADMConnected']['Done'] = 'La fiche a été validée. La miniature peut mettre 5 minutes à se générer.';
	header('location: '.PAGE.'?id='.$sR['id']);
	exit;
}
elseif(!empty($_GET['refuser']) and is_numeric($_GET['refuser'])) {
	if($sR['Etat'] != 3 or !isset($aRaisonsRefus[$_GET['refuser']])) {
		header('location: '.PAGE.'?id='.$sR['id']);
		exit;
	}
	$rR = "UPDATE ".S_FICHES." SET Etat = 6,DateValidation = now(),RefusRaison = ? where id = ?";
	$aArg = array($_GET['refuser'],$sR['id']);
	$oSql->Query($rR,$aArg);
	
	$sSujet = 'Refus de votre site';
	$sBodyHTML = file_get_contents('../php-include/emails/inscription_refus.php',FILE_USE_INCLUDE_PATH);
	$sBodyHTML = str_replace("::DOMAINE",NOMDOMAINE,$sBodyHTML);
	$sBodyHTML = str_replace("::OBJET",$sSujet,$sBodyHTML);
	$sBodyHTML = str_replace("::SITE",$sR['Url'],$sBodyHTML);
	$sBodyHTML = str_replace("::RAISON",$aRaisonsRefus[$_GET['refuser']],$sBodyHTML);
	$sBody = "Bonjour !\n\nVous avez inscrit votre site ".$sR['Url']." dans notre annuaire ".NOMDOMAINE.".\nVotre site n\'a pas été accepté pour la raison suivante : ".$aRaisonsRefus[$_GET['refuser']]."\n\nLes règles étaient pourtant simples à respecter.";
	$aMail = array('FromMail' => MAILEXPEDITEUR,'FromName' => NOMDOMAINE,'Subject' => $sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sR['Mail']);
	myMail($aMail);
	
	$_SESSION['ADMConnected']['Done'] = 'La fiche a été refusé.';
	header('location: '.PAGE.'?id='.$sR['id']);
	exit;
}
elseif(!empty($_GET['edit']) and is_numeric($_GET['edit'])) {
	function GetData() {
		$_SESSION['ADMConnected']['ProposerUnSite']['Categorie'] = (!isset($_POST['Categorie']) ? '' : s($_POST['Categorie']));
		$_SESSION['ADMConnected']['ProposerUnSite']['Url'] = (!isset($_POST['Url']) ? '' : s($_POST['Url']));
		$_SESSION['ADMConnected']['ProposerUnSite']['Titre'] = (!isset($_POST['Titre']) ? '' : s($_POST['Titre']));
		$_SESSION['ADMConnected']['ProposerUnSite']['TitreUrl'] = (!isset($_POST['TitreUrl']) ? '' : s($_POST['TitreUrl']));
		$_SESSION['ADMConnected']['ProposerUnSite']['Description1'] = (!isset($_POST['Description1']) ? '' : s($_POST['Description1']));
		$_SESSION['ADMConnected']['ProposerUnSite']['Description2'] = (!isset($_POST['Description2']) ? '' : s($_POST['Description2']));
		$_SESSION['ADMConnected']['ProposerUnSite']['MetaTitle'] = (!isset($_POST['MetaTitle']) ? '' : s($_POST['MetaTitle']));
		$_SESSION['ADMConnected']['ProposerUnSite']['MetaDescription'] = (!isset($_POST['MetaDescription']) ? '' : s($_POST['MetaDescription']));
		$_SESSION['ADMConnected']['ProposerUnSite']['Mail'] = (!isset($_POST['Mail']) ? '' : s($_POST['Mail']));
	}
	if(empty($_POST['Categorie']) or !is_numeric($_POST['Categorie']) or !isset($aCategories[hs($_POST['Categorie'])])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Cette catégorie n\'existe pas.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['Url'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi d\'url.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['Titre'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de titre.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['TitreUrl'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de d\'url interne.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['Description1'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de description courte.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['Description2'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de description longue.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['MetaTitle'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de meta title.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(empty($_POST['MetaDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de meta description.';
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}

	$sUrl = trim($_POST['Url'],'/');
	$rR = "SELECT * FROM ".S_FICHES." WHERE Url = ? and id != ?";
	$aArg = array($sUrl,$sR['id']);
	$sRVerif = $oSql->GetLine($rR,$aArg);
	if(!empty($sRVerif)) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = "Ce site est déjà listé sur notre site.";
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}
	elseif(verifUrl($sUrl) == false) {
		GetData();
		$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'] = "L'url que vous avez fournit n\'a pas passé le filtre de notre vérification...";
		header('location: '.PAGE.'?id='.$sR['id'].'&modif=1#form');
		exit;
	}

	
	$rR = "UPDATE ".S_FICHES." SET idCategorie = ?,Url = ?,Titre = ?,TitreUrl = ?,Description1 = ?,Description2 = ?,MetaTitle = ?,MetaDescription = ?,Mail = ? where id = ?";
	$aArg = array($_POST['Categorie'],$sUrl,$_POST['Titre'],$_POST['TitreUrl'],$_POST['Description1'],$_POST['Description2'],$_POST['MetaTitle'],$_POST['MetaDescription'],$_POST['Mail'],$sR['id']);
	$oSql->Query($rR,$aArg);
	
	#mise à jour catégorie
	CalculSiteCategorie();
	
	$_SESSION['ADMConnected']['Done'] = 'La fiche a été modifié.';
	header('location: '.PAGE.'?id='.$sR['id'].'');
	exit;
}
elseif(!empty($_GET['effacer']) and is_numeric($_GET['effacer'])) {
	$rR = "UPDATE ".S_FICHES." SET Etat = 6,DateValidation = now(),RefusRaison = 6 where id = ?";
	$aArg = array($sR['id']);
	$oSql->Query($rR,$aArg);
	#mise à jour catégorie
	CalculSiteCategorie();
	
	$_SESSION['ADMConnected']['Done'] = 'La fiche a été effacé.';
	header('location: '.PAGE.'?id='.$sR['id']);
	exit;
}

$aHead = array('PageTitre' => 'Fiche #'.$sR['id'].' :: '.hs($sR['Url']));
head($aHead);

echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-9 col-xs-12"><h2>Fiche #'.$sR['id'].' :: '.hs($sR['Url']).'</h2>';
if(!empty($_SESSION['ADMConnected']['Done'])) {
	echo '<div class="alert alert-success"><p>'.$_SESSION['ADMConnected']['Done'].'</p></div>';
	unset($_SESSION['ADMConnected']['Done']);
}
elseif(!empty($_SESSION['ADMConnected']['Erreur'])) {
	echo '<div class="alert alert-danger"><p>'.$_SESSION['ADMConnected']['Erreur'].'</p></div>';
	unset($_SESSION['ADMConnected']['Erreur']);
}

$oTime = new DateTimeFrench($sR['Date']);
$sUrlInterne = URLSITE.$aCategories[$sR['idCategorie']]['TitreUrl'].'/'.hs($sR['TitreUrl']).'.html';
echo '<table class="table table-striped"><tbody>
<tr><td width="20%">Catégorie</td><td>'.hs($aCategories[$sR['idCategorie']]['Titre']).'</td></tr>
<tr><td>Site</td><td>'.hs($sR['Url']).'</td></tr>
<tr><td>Titre</td><td>'.hs($sR['Titre']).'</td></tr>
<tr><td>Url interne</td><td>'.$sUrlInterne.'</td></tr>
<tr><td>Meta Title</td><td>'.hs($sR['MetaTitle']).'</td></tr>
<tr><td>Meta Description</td><td>'.hs($sR['MetaDescription']).'</td></tr>
<tr><td>Mail</td><td>'.hs($sR['Mail']).'</td></tr>
<tr><td>Ip</td><td>'.hs($sR['Ip']).'</td></tr>
<tr><td>Etat</td><td>'.s($aEtat[$sR['Etat']]).'</td></tr>
<tr><td>Date</td><td>'.$oTime->format('l j F Y \à H\hi').'</td></tr>
<tr><td>Description courte</td><td>'.hs($sR['Description1']).'</td></tr>
<tr><td>Description longue</td><td>'.nl2br(hs($sR['Description2'])).'</td></tr>
</tbody></table>';

#form modifier
if(!empty($_GET['modif']) and is_numeric($_GET['modif'])) {
	$sCategorie = s($sR['idCategorie']);
	$sUrl = s($sR['Url']);
	$sTitre = s($sR['Titre']);
	$sTitreUrl = s($sR['TitreUrl']);
	$sDescription1 = s($sR['Description1']);
	$sDescription2 = s($sR['Description2']);
	$sMetaTitle = s($sR['MetaTitle']);
	$sMetaDescription = s($sR['MetaDescription']);
	$sMail = s($sR['Mail']);
	echo '<a name="form"></a><hr /><h2>Modifier la fiche</h2>';
	if(!empty($_SESSION['ADMConnected']['ProposerUnSite']['Erreur'])) {
		$sCategorie = $_SESSION['ADMConnected']['ProposerUnSite']['Categorie'];
		$sUrl = $_SESSION['ADMConnected']['ProposerUnSite']['Url'];
		$sTitre = $_SESSION['ADMConnected']['ProposerUnSite']['Titre'];
		$sTitreUrl = $_SESSION['ADMConnected']['ProposerUnSite']['TitreUrl'];
		$sDescription1 = $_SESSION['ADMConnected']['ProposerUnSite']['Description1'];
		$sDescription2 = $_SESSION['ADMConnected']['ProposerUnSite']['Description2'];
		$sMetaTitle = $_SESSION['ADMConnected']['ProposerUnSite']['MetaTitle'];
		$sMetaDescription = $_SESSION['ADMConnected']['ProposerUnSite']['MetaDescription'];
		$sMail = $_SESSION['ADMConnected']['ProposerUnSite']['Mail'];
		echo '<div class="alert alert-danger">'.$_SESSION['ADMConnected']['ProposerUnSite']['Erreur'].'</div>';
		unset($_SESSION['ADMConnected']['ProposerUnSite']);
	}
	echo '<form action="'.PAGE.'?id='.$sR['id'].'&edit=1" method="post" class=""><div class="form-group"><label for="Categorie" class="control-label">Catégorie</label><select class="form-control" id="Categorie" name="Categorie"><option value=""></option>';
	foreach($aCategories as $k => $v) {
		echo '<option value="'.$k.'"';
		if($k == $sCategorie)
			echo ' selected="selected"';
		echo '>'.$v['Titre'].'</option>';
	}
	echo '</select></div><div class="form-group"><label for="Url" class="control-label">Url du site</label><input type="text" name="Url" id="Url" class="form-control" value="'.$sUrl.'" placeholder="http://www.example.com" required></div><div class="form-group"><label for="Titre" class="control-label">Titre</label><input type="text" name="Titre" id="Titre" class="form-control" value="'.$sTitre.'" required></div><div class="form-group"><label for="TitreUrl" class="control-label">Url interne</label><input type="text" name="TitreUrl" id="TitreUrl" class="form-control" value="'.$sTitreUrl.'" required></div><div class="form-group"><label for="MetaTitle" class="control-label">Meta Title</label><input type="text" name="MetaTitle" id="MetaTitle" class="form-control" value="'.$sMetaTitle.'" required></div><div class="form-group"><label for="MetaDescription" class="control-label">Meta Description</label><input type="text" name="MetaDescription" id="MetaDescription" class="form-control" value="'.$sMetaDescription.'" required></div><div class="form-group"><label for="Description1" class="control-label">Description Courte</label><textarea name="Description1" id="Description1" class="form-control" placeholder="Description de '.$sRConfig['CaracteresMinDescription1'].' caractères minimum. Description affichée sur les pages de catégories" cols="40" rows="8" required>'.$sDescription1.'</textarea></div><div class="form-group"><label for="Description2" class="control-label">Description Longue</label><textarea name="Description2" id="Description2" class="form-control" placeholder="Description de '.$sRConfig['CaracteresMinDescription2'].' caractères minimum. Description dédiée à la page de votre site" cols="40" rows="8" required>'.$sDescription2.'</textarea></div><div class="form-group"><label for="Mail" class="control-label">Adresse email</label><input type="text" name="Mail" id="Mail" class="form-control" value="'.$sMail.'" required></div><button type="submit" class="btn btn-primary btn-block">Modifier la fiche</button></form>';
}

#autre fiches lies à lemail ou l'ip
$rR = "SELECT * FROM ".S_FICHES." where Ip = ? and id != ? order by id desc";
$aArg = array($sR['Ip'],$sR['id']);
$sRow = $oSql->GetAll($rR,$aArg);
if(!empty($sRow)) {
	echo '<hr /><h2>Fiches avec la même ip</h2><table class="table table-striped"><thead><tr><th width="6%">#</th><th>Site</th><th>Categorie</th><th>Etat</th><th width="18%">Date</th></tr></thead><tbody>';
	foreach($sRow as $k => $v) {
		$oTime = new DateTimeFrench($v['Date']);
		echo '<tr><td><a href="fiche.php?id='.$v['id'].'">#'.s($v['id']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($v['Url']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($aCategories[$v['idCategorie']]['Titre']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($aEtat[$v['Etat']]).'</a></td><td>'.$oTime->format('l \à H\hi').'</td></tr>';
	}
	echo '</tbody></table>';
}

$rR = "SELECT * FROM ".S_FICHES." where Mail = ? and id != ? order by id desc";
$aArg = array($sR['Mail'],$sR['id']);
$sRow = $oSql->GetAll($rR,$aArg);
if(!empty($sRow)) {
	echo '<hr /><h2>Fiches avec la même adresse email</h2><table class="table table-striped"><thead><tr><th width="6%">#</th><th>Site</th><th>Categorie</th><th>Etat</th><th width="18%">Date</th></tr></thead><tbody>';
	foreach($sRow as $k => $v) {
		$oTime = new DateTimeFrench($v['Date']);
		echo '<tr><td><a href="fiche.php?id='.$v['id'].'">#'.s($v['id']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($v['Url']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($aCategories[$v['idCategorie']]['Titre']).'</a></td><td><a href="fiche.php?id='.$v['id'].'">'.s($aEtat[$v['Etat']]).'</a></td><td>'.$oTime->format('l \à H\hi').'</td></tr>';
	}
	echo '</tbody></table>';
}

if(!empty($sR['Image']))
	echo '<br /><img src="'.$sR['Image'].'" alt="" class="img-responsive" />';


echo '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">
<h2>Action</h2>
<a href="'.PAGE.'" title="Retour" class="btn btn-block btn-default"><i class="fa fa-arrow-left"></i> Retour</a>';
if($sR['Etat'] >= 1 and $sR['Etat'] <= 3) {
	if($sR['Etat'] == 1) {
		echo '<a href="'.$sUrlInterne.'" title="Voir sur le site" target="_blank" class="btn btn-block btn-success"><i class="fa fa-eye"></i> Voir</a><a href="'.PAGE.'?id='.$sR['id'].'&modif=1#form" title="Modifier" class="btn btn-block btn-primary"><i class="fa fa-edit"></i> Modifier</a>';
	$rR = "SELECT * FROM ".S_SIDEBAR." WHERE Lien = ?";
	$aArg = array($sUrlInterne);
	$sRSidebar = $oSql->GetLine($rR,$aArg);
	if(empty($sRSidebar))
		echo '<a href="configuration_sidebar.php?nFiche='.$sR['id'].'#form" class="btn btn-block btn-warning"><i class="fa fa-star"></i> Mettre en avant</a>';
	}
}
elseif($sR['Etat'] == 5)
	echo '<a href="'.PAGE.'?id='.$sR['id'].'&email=1" title="Renvoyer email" class="btn btn-block btn-warning"><i class="fa fa-envelope"></i> Renvoyer email</a><a href="'.PAGE.'?id='.$sR['id'].'&emailsaute=1" title="Simuler email" class="btn btn-block btn-warning"><i class="fa fa-share"></i> Simuler email</a>';
elseif($sR['Etat'] == 4)
	echo '<a href="'.PAGE.'?id='.$sR['id'].'&paiement=1" title="Simuler le Paiement" class="btn btn-block btn-warning"><i class="fa fa-paypal"></i> Simuler le Paiement</a>';
if($sR['Etat'] != 6)
	echo '<a href="'.PAGE.'?id='.$sR['id'].'&effacer=1" title="Effacer" class="btn btn-block btn-danger"><i class="fa fa-times"></i> Effacer</a>';


if($sR['Etat'] == 3) {
	echo '<h2>Modération</h2><a href="'.PAGE.'?id='.$sR['id'].'&accepter=1" title="Accepter" class="btn btn-block btn-success"><i class="fa fa-check"></i> Accepter</a>';
	foreach($aRaisonsRefus as $k => $v)
		echo '<a href="'.PAGE.'?id='.$sR['id'].'&refuser='.$k.'" title="Refuser" class="btn btn-block btn-danger"><i class="fa fa-times"></i> Refuser ('.$v.')</a>';
}
echo '</div></div>';
bot();
?>