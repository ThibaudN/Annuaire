<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
$aNomSujet = array(1 => 'Renseignements','Abus','Partenariat','Bugs');
if(!empty($_GET['action']) and is_numeric($_GET['action'])) {
	function GetData() {
		$_SESSION['Deconnected']['Contacts']['Nom'] = (!isset($_POST['Nom']) ? '' : s($_POST['Nom']));
		$_SESSION['Deconnected']['Contacts']['Prenom'] = (!isset($_POST['Prenom']) ? '' : s($_POST['Prenom']));
		$_SESSION['Deconnected']['Contacts']['Mail'] = (!isset($_POST['Mail']) ? '' : s($_POST['Mail']));
		$_SESSION['Deconnected']['Contacts']['Sujet'] = (!isset($_POST['Sujet']) ? '' : s($_POST['Sujet']));
		$_SESSION['Deconnected']['Contacts']['Contenu'] = (!isset($_POST['Contenu']) ? '' : s($_POST['Contenu']));
	}
	if(empty($_POST['Nom'])) {
		GetData();
		$_SESSION['Contacts']['Erreur'] = "Votre nom n'est pas renseigné";
		header("location: ".PAGE.'');
		exit;
	}
	elseif(empty($_POST['Sujet']) or !is_numeric($_POST['Sujet']) or $_POST['Sujet'] < 1 or $_POST['Sujet'] > count($aNomSujet)) {
		GetData();
		$_SESSION['Deconnected']['Contacts']['Erreur'] = "Le sujet n'a pas été choisi";
		header("location: ".PAGE.'');
		exit;
	}
	elseif(verifmail($_POST['Mail']) == false) {
		GetData();
		$_SESSION['Deconnected']['Contacts']['Erreur'] = "Cette adresse email est erronée";
		header("location: ".PAGE.'');
		exit;
	}
	elseif(empty($_POST['Contenu'])) {
		GetData();
		$_SESSION['Deconnected']['Contacts']['Erreur'] = "Votre message n'est pas renseigné";
		header("location: ".PAGE.'');
		exit;
	}
	$sIp = GetIp();
	$sHost = GetHost($sIp);
	$sSujet = 'Contacts via le site';
	$sBodyHTML = file_get_contents('php-include/emails/contacts.php',FILE_USE_INCLUDE_PATH);

	$sAcceptEncoding = (isset($_SERVER["HTTP_ACCEPT_ENCODING"])) ? $_SERVER["HTTP_ACCEPT_ENCODING"] : '';
	$sAcceptLanguage = (isset($_SERVER["HTTP_ACCEPT_LANGUAGE"])) ? $_SERVER["HTTP_ACCEPT_LANGUAGE"] : '';
	$sAccept = (isset($_SERVER["HTTP_ACCEPT"])) ? $_SERVER["HTTP_ACCEPT"] : '';
	$sUserAgent = (isset($_SERVER["HTTP_USER_AGENT"])) ? $_SERVER["HTTP_USER_AGENT"] : '';
	
	$sBodyHTML = str_replace("::DOMAINE",NOMDOMAINE,$sBodyHTML);
	$sBodyHTML = str_replace("::OBJET",s($sSujet),$sBodyHTML);
	$sBodyHTML = str_replace("::HTTP_USER_AGENT",s($sUserAgent),$sBodyHTML);
	$sBodyHTML = str_replace("::HTTP_ACCEPT",s($sAccept),$sBodyHTML);
	$sBodyHTML = str_replace("::HTTP_ACCEPT_LANGUAGE",s($sAcceptLanguage),$sBodyHTML);
	$sBodyHTML = str_replace("::HTTP_ACCEPT_ENCODING",s($sAcceptEncoding),$sBodyHTML);
	$sBodyHTML = str_replace("::IP",s($sIp),$sBodyHTML);
	$sBodyHTML = str_replace("::HOST",s($sHost),$sBodyHTML);
	$sBodyHTML = str_replace("::PRENOM",hs($_POST['Prenom']),$sBodyHTML);
	$sBodyHTML = str_replace("::NOM",hs($_POST['Nom']),$sBodyHTML);
	$sBodyHTML = str_replace("::EMAIL",s($_POST['Mail']),$sBodyHTML);
	$sBodyHTML = str_replace("::SUJET",$aNomSujet[hs($_POST['Sujet'])],$sBodyHTML);
	$sBodyHTML = str_replace("::MESSAGE",nl2br(hs($_POST['Contenu'])),$sBodyHTML);
	$sBody = "HTTP_USER_AGENT : ".hs($sUserAgent)."\nHTTP_ACCEPT : ".hs($sAccept)."\n
	HTTP_ACCEPT_LANGUAGE : ".hs($sAcceptLanguage)."\nHTTP_ACCEPT_ENCODING : ".hs($sAcceptEncoding)."\nIp : ".hs($sIp)."\nHost : ".hs($sHost)."\n\nPrénom : ".hs($_POST['Prenom'])."\nNom : ".hs($_POST['Nom'])."\nEmail : ".hs($_POST['Mail'])."\nObject : ".$aNomSujet[hs($_POST['Sujet'])]."\nMessage : ".nl2br(hs($_POST['Contenu']));
	$aMail = array('FromMail' => hs($_POST['Mail']),'FromName' => hs($_POST['Nom']).' '.hs($_POST['Prenom']),'Subject' => NOMDOMAINE.' - '.$sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sRConfig['MailDestinataire']);
	myMail($aMail);
	$_SESSION['Deconnected']['Contacts']['Done'] = 'Votre message a bien été transmis. Nous vous engageons à répondre dans les 48 heures.';
	header("location: ".PAGE.'');
	exit;
}


$aHead = array('PageTitre' => 'Contactez Nous','MetaRobots' => 'noindex','MetaKeyword' => '','MetaDescription' => '');
head($aHead);

$sNom = '';
$sPrenom = '';
$sMail = '';
$nSujet = '';
$sContenu = '';
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><div class="content"><div class="content-box"><div class="content-box-body"><h2 class="page">Contacts</h2>';

if(!empty($_SESSION['Deconnected']['Contacts']['Done'])) {
	echo '<div class="alert alert-success mtop4">'.hs($_SESSION['Deconnected']['Contacts']['Done']).'</div>';
	unset($_SESSION['Deconnected']['Contacts']['Done']);
}
else {
	echo '<p>Pour nous contacter, il vous suffit de remplir correctement ce formulaire.</p>
<form action="'.PAGE.'?action=1" method="post" class="">';
if(!empty($_SESSION['Deconnected']['Contacts']['Erreur'])) {
	$sNom = $_SESSION['Deconnected']['Contacts']['Nom'];
	$sPrenom = $_SESSION['Deconnected']['Contacts']['Prenom'];
	$sMail = $_SESSION['Deconnected']['Contacts']['Mail'];
	$nSujet = $_SESSION['Deconnected']['Contacts']['Sujet'];
	$sContenu = $_SESSION['Deconnected']['Contacts']['Contenu'];
	echo '<div class="alert alert-danger mtop4">'.$_SESSION['Deconnected']['Contacts']['Erreur'].'</div>';
	unset($_SESSION['Deconnected']['Contacts']);
}


echo '<div class="form-group"><label for="contact_name" class="control-label">Nom</label><div><input type="text" name="Nom" id="contact_name" class="form-control" value="'.$sNom.'" required></div></div><div class="form-group"><label for="contact_prenom" class="control-label">Prénom</label><div><input type="text" name="Prenom" id="contact_prenom" class="form-control" value="'.$sPrenom.'" required></div></div><div class="form-group"><label for="contact_mail" class="control-label">Adresse e-mail</label><div><input type="email" name="Mail" id="contact_mail" class="form-control" value="'.$sMail.'" required></div></div><div class="form-group"><label for="contact_subject" class="control-label">Sujet</label><div><select class="form-control" id="contact_subject" name="Sujet">';
foreach($aNomSujet as $k => $v) {
	echo '<option value="'.$k.'"';
	if($k == $nSujet)
		echo ' selected="selected"';
	echo '>'.$v.'</option>';
}
echo '</select></div></div><div class="form-group"><label for="contact_message" class="control-label">Message</label><div><textarea name="Contenu" id="contact_message" class="form-control" cols="40" rows="8" required>'.$sContenu.'</textarea></div></div><div class="clearfix"></div><div><button type="submit" class="btn btn-primary btn-block">Envoyer</button></div></form>';
}
echo '</div></div></div></div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>