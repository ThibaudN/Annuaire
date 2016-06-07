<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";

if(!empty($_GET['actionRegles']) and is_numeric($_GET['actionRegles'])) {
	$_SESSION['Deconnected']['Regle'] = 1;
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['action']) and is_numeric($_GET['action'])) {
	function GetData() {
		$_SESSION['Deconnected']['ProposerUnSite']['Categorie'] = (!isset($_POST['Categorie']) ? '' : s($_POST['Categorie']));
		$_SESSION['Deconnected']['ProposerUnSite']['Url'] = (!isset($_POST['Url']) ? '' : s($_POST['Url']));
		$_SESSION['Deconnected']['ProposerUnSite']['Titre'] = (!isset($_POST['Titre']) ? '' : s($_POST['Titre']));
		$_SESSION['Deconnected']['ProposerUnSite']['Description1'] = (!isset($_POST['Description1']) ? '' : s($_POST['Description1']));
		$_SESSION['Deconnected']['ProposerUnSite']['Description2'] = (!isset($_POST['Description2']) ? '' : s($_POST['Description2']));
		$_SESSION['Deconnected']['ProposerUnSite']['Mail'] = (!isset($_POST['Mail']) ? '' : s($_POST['Mail']));
	}
	if(empty($_SESSION['Deconnected']['Regle'])) {
		header('location: '.PAGE);
		exit;
	}
	if(empty($_SESSION['Deconnected']['AntiRobots'])) {
		header('location: '.PAGE);
		exit;
	}
	$rR = "SELECT * FROM ".S_ANTIROBOTS." WHERE id = ?";
	$aArg = array($_SESSION['Deconnected']['AntiRobots']);
	$sRQuestion = $oSql->GetLine($rR,$aArg);
	if(empty($sRQuestion)) {
		header('location: '.PAGE);
		exit;
	}
	elseif(empty($_POST['Categorie']) or !is_numeric($_POST['Categorie']) or !isset($aCategories[hs($_POST['Categorie'])])) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Cette catégorie n\'existe pas.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['Url'])) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi d\'url.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['Titre'])) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de titre.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['Description1'])) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de description courte.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['Description2'])) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Vous n\'avez pas saisi de description longue.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(strlen($_POST['Description1']) < $sRConfig['CaracteresMinDescription1'] or strlen($_POST['Description1']) > $sRConfig['CaracteresMaxDescription1']) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Votre description courte doit faire entre '.$sRConfig['CaracteresMinDescription1'].' et '.$sRConfig['CaracteresMaxDescription1'].' caractères. Elle en fait '.strlen($_POST['Description1']).'.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(strlen($_POST['Description2']) < $sRConfig['CaracteresMinDescription2'] or strlen($_POST['Description2']) > $sRConfig['CaracteresMaxDescription2']) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'Votre description longue doit faire entre '.$sRConfig['CaracteresMinDescription2'].' et '.$sRConfig['CaracteresMaxDescription2'].' caractères. Elle en fait '.strlen($_POST['Description2']).'.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(verifmail($_POST['Mail']) == false) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = "Cette adresse email est erronée.";
		header('location: '.PAGE.'');
		exit;
	}
	elseif(strtolower($sRQuestion['Reponse']) != strtolower($_POST['Question'])) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = 'L\'anti-robots vous a été fatal.';
		header('location: '.PAGE.'');
		exit;
	}
	#question anti robot
	$sUrl = trim($_POST['Url'],'/');
	$rR = "SELECT * FROM ".S_FICHES." WHERE Url = ?";
	$aArg = array($sUrl);
	$sRVerif = $oSql->GetLine($rR,$aArg);
	if(!empty($sRVerif)) {
		GetData();
		if($sRVerif['Etat'] == 1)
			$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = "Ce site est déjà listé sur notre site.";
		elseif($sRVerif['Etat'] == 2)
			$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = "Ce site va être listé dans les heures qui arrivent.";
		elseif($sRVerif['Etat'] == 3)
			$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = "Ce site est en attente de modération.";
		elseif($sRVerif['Etat'] == 4) {
			#Ce site est en attente de paiement.
			unset($_SESSION['Deconnected']);
			$_SESSION['Connected']['idUnique'] = $sRVerif['KeyGen'];
			header('location: /proposerunsite_paiement.php');
			exit;
		}
		elseif($sRVerif['Etat'] == 5) {
			#Ce site est en attente de validation de l\'adresse email.
			unset($_SESSION['Deconnected']);
			$_SESSION['Connected']['idUnique'] = $sRVerif['KeyGen'];
			header('location: proposerunsite_mail.php');
			exit;
		}
		elseif($sRVerif['Etat'] == 6)
			$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = "Ce site a été refusé. Suite à la perte de temps pour la modération, il n\'est plus possible de le soumettre.";
		header('location: '.PAGE.'');
		exit;
	}
	elseif(verifUrl($sUrl) == false) {
		GetData();
		$_SESSION['Deconnected']['ProposerUnSite']['Erreur'] = "L'url que vous avez fournit n\'a pas passé le filtre de notre vérification...";
		header('location: '.PAGE.'');
		exit;
	}
	$sKeyGen = KeyGen(32);
	$sKeyGenMail = KeyGen(5);
	$sUrlDebut = substr($sUrl,0,7);
	if($sUrlDebut == 'http://')
		$sTitreUrl = substr($sUrl,7);
	else
		$sTitreUrl = substr($sUrl,8);
	$sTitreUrl = clean_url($sTitreUrl);
	$sIp = GetIp();
	$sMail = $_POST['Mail'];
	
	$sMetaTitle = $_POST['Titre'];
	$sMetaDescription = 'Nous vous présentons le site internet '.substr($sUrl,8).' référencé dans la catégorie '.s($aCategories[hs($_POST['Categorie'])]['Titre']);
	
	$rR = "INSERT INTO ".S_FICHES." (idCategorie,Url,Titre,TitreUrl,Description1,Description2,MetaTitle,MetaDescription,Mail,KeyGen,KeyGenMail,Ip,Date,Etat) values (?,?,?,?,?,?,?,?,?,?,?,?,now(),5)";
	$aArg = array($_POST['Categorie'],$sUrl,$_POST['Titre'],$sTitreUrl,$_POST['Description1'],$_POST['Description2'],$sMetaTitle,$sMetaDescription,$sMail,$sKeyGen,$sKeyGenMail,$sIp);
	$oSql->Query($rR,$aArg);
	MailInscription($sKeyGenMail,$sMail);
	unset($_SESSION['Deconnected']);
	$_SESSION['Connected']['idUnique'] = $sKeyGenMail;
	
	header('location: proposerunsite_mail.php');
	exit;
}


$aHead = array('PageTitre' => 'Proposer un site','MetaRobots' => 'noindex','MetaKeyword' => '','MetaDescription' => '');
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12">';

#affichage des régles
if(empty($_SESSION['Deconnected']['Regle'])) {
	#afficher les regles
	echo '<h2>Les règles pour proposer son site</h2>'.s($sRConfig['Regles']).'<a href="'.PAGE.'?actionRegles=1" title="Accepter les règles" rel="nofollow" class="btn btn-primary btn-block">Accepter les règles</a>';
}
else {
	$rR = "SELECT * FROM ".S_ANTIROBOTS." ORDER BY RAND() LIMIT 1";
	$sRQuestion = $oSql->GetLine($rR);
	$_SESSION['Deconnected']['AntiRobots'] = $sRQuestion['id'];

	$sCategorie = '';
	$sUrl = '';
	$sTitre = '';
	$sDescription1 = '';
	$sDescription2 = '';
	$sMail = '';
	$sQuestion = '';
	echo '<h2>Proposer un site</h2>';

	echo '<div class="alert alert-warning">Proposer son site est payant.</div>';

	if(!empty($_SESSION['Deconnected']['ProposerUnSite']['Erreur'])) {
		$sCategorie = $_SESSION['Deconnected']['ProposerUnSite']['Categorie'];
		$sUrl = $_SESSION['Deconnected']['ProposerUnSite']['Url'];
		$sTitre = $_SESSION['Deconnected']['ProposerUnSite']['Titre'];
		$sDescription1 = $_SESSION['Deconnected']['ProposerUnSite']['Description1'];
		$sDescription2 = $_SESSION['Deconnected']['ProposerUnSite']['Description2'];
		$sMail = $_SESSION['Deconnected']['ProposerUnSite']['Mail'];
		echo '<div class="alert alert-danger">'.$_SESSION['Deconnected']['ProposerUnSite']['Erreur'].'</div>';
		unset($_SESSION['Deconnected']['ProposerUnSite']);
	}
	$nDescriptionCaracteres1 = strlen($sDescription1);
	$nDescriptionMots1 = count(explode(' ',$sDescription1));
	$nDescriptionCaracteres2 = strlen($sDescription2);
	$nDescriptionMots2 = count(explode(' ',$sDescription2));


	echo '<form action="'.PAGE.'?action=1" method="post" class=""><div class="form-group"><label for="Categorie" class="control-label">Catégorie</label><select class="form-control" id="Categorie" name="Categorie"><option value=""></option>';
	foreach($aCategories as $k => $v) {
		echo '<option value="'.$k.'"';
		if($k == $sCategorie)
			echo ' selected="selected"';
		echo '>'.$v['Titre'].'</option>';
	}
	echo '</select></div><div class="form-group"><label for="Url" class="control-label">Url du site</label><input type="text" name="Url" id="Url" class="form-control" value="'.$sUrl.'" placeholder="http://www.example.com" required></div><div class="form-group"><label for="Titre" class="control-label">Titre</label><input type="text" name="Titre" id="Titre" class="form-control" value="'.$sTitre.'" required></div><div class="form-group"><label for="Description1" class="control-label">Description Courte</label><textarea name="Description1" id="Description1" class="form-control" placeholder="Description de '.$sRConfig['CaracteresMinDescription1'].' caractères minimum. Description affichée sur les pages de catégories" cols="40" rows="8" required>'.$sDescription1.'</textarea><span class="help-block"><span id="CompteurMotDescription1">'.$nDescriptionMots1.' mot</span> - <span id="CompteurCarDescription1">'.$nDescriptionCaracteres1.' caractère</span> / '.$sRConfig['CaracteresMaxDescription1'].' - html et liens interdits</span></div><div class="form-group"><label for="Description2" class="control-label">Description Longue</label><textarea name="Description2" id="Description2" class="form-control" placeholder="Description de '.$sRConfig['CaracteresMinDescription2'].' caractères minimum. Description dédiée à la page de votre site" cols="40" rows="8" required>'.$sDescription2.'</textarea><span class="help-block"><span id="CompteurMotDescription2">'.$nDescriptionMots2.' mot</span> - <span id="CompteurCarDescription2">'.$nDescriptionCaracteres2.' caractère</span> / '.$sRConfig['CaracteresMaxDescription2'].' - html et liens interdits</span></div><div class="form-group"><label for="Mail" class="control-label">Votre adresse email</label><input type="text" name="Mail" id="Mail" class="form-control" value="'.$sMail.'" required></div><div class="form-group"><label for="Question" class="control-label">Question : '.hs($sRQuestion['Question']).'</label><input type="text" name="Question" id="Question" class="form-control" value="" required></div><button type="submit" class="btn btn-primary btn-block">Ajouter le site</button></form>';
}

echo '</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>