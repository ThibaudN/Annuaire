<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_SESSION['ADMConnected']['bCon'])) { header('location: login_form.php'); exit; }
elseif(!empty($_GET['action']) and is_numeric($_GET['action'])) {
	function GetData() {
		$_SESSION['ADMConnected']['Configuration']['NomSite'] = (!isset($_POST['NomSite']) ? '' : s($_POST['NomSite']));
		$_SESSION['ADMConnected']['Configuration']['TagLine'] = (!isset($_POST['TagLine']) ? '' : s($_POST['TagLine']));
		$_SESSION['ADMConnected']['Configuration']['ThemeColor'] = (!isset($_POST['ThemeColor']) ? '' : s($_POST['ThemeColor']));
		$_SESSION['ADMConnected']['Configuration']['MailDestinataire'] = (!isset($_POST['MailDestinataire']) ? '' : s($_POST['MailDestinataire']));
		$_SESSION['ADMConnected']['Configuration']['MailLorsPaiement'] = (!isset($_POST['MailLorsPaiement']) ? '' : s($_POST['MailLorsPaiement']));
		$_SESSION['ADMConnected']['Configuration']['GoogleAnalytics'] = (!isset($_POST['GoogleAnalytics']) ? '' : s($_POST['GoogleAnalytics']));
		$_SESSION['ADMConnected']['Configuration']['MentionsLegales'] = (!isset($_POST['MentionsLegales']) ? '' : s($_POST['MentionsLegales']));
		$_SESSION['ADMConnected']['Configuration']['Regles'] = (!isset($_POST['Regles']) ? '' : s($_POST['Regles']));
		$_SESSION['ADMConnected']['Configuration']['HomeMetaTitle'] = (!isset($_POST['HomeMetaTitle']) ? '' : s($_POST['HomeMetaTitle']));
		$_SESSION['ADMConnected']['Configuration']['HomeMetaDescription'] = (!isset($_POST['HomeMetaDescription']) ? '' : s($_POST['HomeMetaDescription']));
		$_SESSION['ADMConnected']['Configuration']['HomeDescriptionH2'] = (!isset($_POST['HomeDescriptionH2']) ? '' : s($_POST['HomeDescriptionH2']));
		$_SESSION['ADMConnected']['Configuration']['HomeDescription'] = (!isset($_POST['HomeDescription']) ? '' : s($_POST['HomeDescription']));
		$_SESSION['ADMConnected']['Configuration']['HomeSitesH2'] = (!isset($_POST['HomeSitesH2']) ? '' : s($_POST['HomeSitesH2']));
		$_SESSION['ADMConnected']['Configuration']['HomeSitesAffiches'] = (!isset($_POST['HomeSitesAffiches']) ? '' : s($_POST['HomeSitesAffiches']));
		$_SESSION['ADMConnected']['Configuration']['SidebarCategorieH2'] = (!isset($_POST['SidebarCategorieH2']) ? '' : s($_POST['SidebarCategorieH2']));
		$_SESSION['ADMConnected']['Configuration']['SidebarSitesH2'] = (!isset($_POST['SidebarSitesH2']) ? '' : s($_POST['SidebarSitesH2']));
		$_SESSION['ADMConnected']['Configuration']['SidebarNavigationH2'] = (!isset($_POST['SidebarNavigationH2']) ? '' : s($_POST['SidebarNavigationH2']));
		$_SESSION['ADMConnected']['Configuration']['CaracteresMinDescription1'] = (!isset($_POST['CaracteresMinDescription1']) ? '' : s($_POST['CaracteresMinDescription1']));
		$_SESSION['ADMConnected']['Configuration']['CaracteresMaxDescription1'] = (!isset($_POST['CaracteresMaxDescription1']) ? '' : s($_POST['CaracteresMaxDescription1']));
		$_SESSION['ADMConnected']['Configuration']['CaracteresMinDescription2'] = (!isset($_POST['CaracteresMinDescription2']) ? '' : s($_POST['CaracteresMinDescription2']));
		$_SESSION['ADMConnected']['Configuration']['CaracteresMaxDescription2'] = (!isset($_POST['CaracteresMaxDescription2']) ? '' : s($_POST['CaracteresMaxDescription2']));
		$_SESSION['ADMConnected']['Configuration']['FicheMetaTitle'] = (!isset($_POST['FicheMetaTitle']) ? '' : s($_POST['FicheMetaTitle']));
		$_SESSION['ADMConnected']['Configuration']['FicheMetaDescription'] = (!isset($_POST['FicheMetaDescription']) ? '' : s($_POST['FicheMetaDescription']));
		$_SESSION['ADMConnected']['Configuration']['PaiementDescription'] = (!isset($_POST['PaiementDescription']) ? '' : s($_POST['PaiementDescription']));
		$_SESSION['ADMConnected']['Configuration']['PaypalPrix'] = (!isset($_POST['PaypalPrix']) ? '' : s($_POST['PaypalPrix']));
		$_SESSION['ADMConnected']['Configuration']['PaypalMonnaie'] = (!isset($_POST['PaypalMonnaie']) ? '' : s($_POST['PaypalMonnaie']));
		$_SESSION['ADMConnected']['Configuration']['PaypalMail'] = (!isset($_POST['PaypalMail']) ? '' : s($_POST['PaypalMail']));
		$_SESSION['ADMConnected']['Configuration']['PaypalBoutonId'] = (!isset($_POST['PaypalBoutonId']) ? '' : s($_POST['PaypalBoutonId']));
	}
	if(empty($_POST['NomSite'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de nom pour le site.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['TagLine'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de tagline pour le site.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['ThemeColor'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de couleur pour le theme.';
		header('location: '.PAGE.'');
		exit;
	}	
	elseif(empty($_POST['MailDestinataire'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de mail pour recevoir les emails.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(!is_numeric($_POST['MailLorsPaiement']) or $_POST['MailLorsPaiement'] < 0 or $_POST['MailLorsPaiement'] > 1) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous voulez recevoir les emails ou pas ?!.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['HomeMetaTitle'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de balise title pour la page d\'accueil.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['HomeMetaDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de meta description pour la page d\'accueil.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['HomeDescriptionH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi le H2 pour la description sur la page d\'accueil.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['HomeDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de description sur la page d\'accueil.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['HomeSitesH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi le H2 pour le listing des sites sur la page d\'accueil.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(!is_numeric($_POST['HomeSitesAffiches']) or $_POST['HomeSitesAffiches'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Saisissez un nombre de sites qui sera affichés sur la page d\'accueil.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['SidebarCategorieH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi le H2 pour le listing des catégories dans la sidebar.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['SidebarSitesH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi le H2 pour le listing des sites dans la sidebar.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['SidebarNavigationH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi le H2 pour la navigation dans la sidebar.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['Regles'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de règles pour proposer son site.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(!is_numeric($_POST['CaracteresMinDescription1']) or $_POST['CaracteresMinDescription1'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Erreur avec le nombre de caractères minimum pour la description courte.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(!is_numeric($_POST['CaracteresMaxDescription1']) or $_POST['CaracteresMaxDescription1'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Erreur avec le nombre de caractères maximum pour la description courte.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(!is_numeric($_POST['CaracteresMinDescription2']) or $_POST['CaracteresMinDescription2'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Erreur avec le nombre de caractères minimum pour la description longue.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(!is_numeric($_POST['CaracteresMaxDescription2']) or $_POST['CaracteresMaxDescription2'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Erreur avec le nombre de caractères maximum pour la description longue.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif($_POST['CaracteresMaxDescription2'] < $_POST['CaracteresMinDescription2']) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Erreur avec le nombre de caractères pour la description longue.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif($_POST['CaracteresMaxDescription1'] < $_POST['CaracteresMinDescription1']) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Erreur avec le nombre de caractères pour la description courte.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['FicheMetaTitle'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de Meta Title pour les fiches.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['FicheMetaDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de Meta Description pour les fiches.';
		header('location: '.PAGE.'');
		exit;
	}	
	elseif(empty($_POST['PaypalPrix'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de prix pour paypal.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['PaypalMonnaie'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi la monnaie pour paypal.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['PaypalMail'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi d\'adresse email pour paypal.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['PaypalBoutonId'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi l\'identifiant du bouton pour paypal.';
		header('location: '.PAGE.'');
		exit;
	}
	elseif(empty($_POST['PaiementDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi de description lors du paiement.';
		header('location: '.PAGE.'');
		exit;
	}
	$sFileCss = __DIR__.'/../static/custom.'.hs($_POST['ThemeColor']).'.css';
	if(!file_exists($sFileCss)) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Le fichier css lié au thème n\'existe pas.';
		header('location: '.PAGE.'');
		exit;
	}	
	$aExplode = explode('::NOMSITE',$_POST['FicheMetaTitle']);
	if(count($aExplode) == 1) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi la variable ::NOMSITE dans la Meta Title pour les fiches.';
		header('location: '.PAGE.'');
		exit;
	}
	$aExplode = explode('::NOMSITE',$_POST['FicheMetaDescription']);
	if(count($aExplode) == 1) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi la variable ::NOMSITE dans la Meta Description pour les fiches.';
		header('location: '.PAGE.'');
		exit;
	}
	$aExplode = explode('::PRIX',$_POST['PaiementDescription']);
	if(count($aExplode) == 1) {
		GetData();
		$_SESSION['ADMConnected']['Configuration']['Erreur'] = 'Vous n\'avez pas saisi la variable ::PRIX dans la description lors du paiement.';
		header('location: '.PAGE.'');
		exit;
	}
	
	$rR = "UPDATE ".S_CONFIG." SET  NomSite = ?,TagLine = ?,ThemeColor = ?,MailDestinataire = ?,MailLorsPaiement = ?,GoogleAnalytics = ?,Regles = ?,HomeMetaTitle = ?,HomeMetaDescription = ?,HomeDescriptionH2 = ?,HomeDescription = ?,HomeSitesH2 = ?,HomeSitesAffiches = ?,SidebarCategorieH2 = ?,SidebarSitesH2 = ?,SidebarNavigationH2 = ?,CaracteresMinDescription1 = ?,CaracteresMaxDescription1 = ?,CaracteresMinDescription2 = ?,CaracteresMaxDescription2 = ?,FicheMetaTitle = ?,FicheMetaDescription = ?,PaiementDescription = ?,PaypalPrix = ?,PaypalMonnaie = ?,PaypalMail = ?,PaypalBoutonId = ?,MentionsLegales = ?";
	if(empty($sRConfig['isConfig']))
		$rR .= ",isConfig = 1";
	$rR .= " WHERE id = 1";
	$aArg = array($_POST['NomSite'],$_POST['TagLine'],$_POST['ThemeColor'],$_POST['MailDestinataire'],$_POST['MailLorsPaiement'],$_POST['GoogleAnalytics'],$_POST['Regles'],$_POST['HomeMetaTitle'],$_POST['HomeMetaDescription'],$_POST['HomeDescriptionH2'],$_POST['HomeDescription'],$_POST['HomeSitesH2'],$_POST['HomeSitesAffiches'],$_POST['SidebarCategorieH2'],$_POST['SidebarSitesH2'],$_POST['SidebarNavigationH2'],$_POST['CaracteresMinDescription1'],$_POST['CaracteresMaxDescription1'],$_POST['CaracteresMinDescription2'],$_POST['CaracteresMaxDescription2'],$_POST['FicheMetaTitle'],$_POST['FicheMetaDescription'],$_POST['PaiementDescription'],$_POST['PaypalPrix'],$_POST['PaypalMonnaie'],$_POST['PaypalMail'],$_POST['PaypalBoutonId'],$_POST['MentionsLegales']);
	$oSql->Query($rR,$aArg);
	if(empty($sRConfig['isConfig'])) {
		$_SESSION['ADMConnected']['Done'] = 'La configuration est faite. Il faut s\'occuper des catégories car les meta title et description sont vides.';
		header('location: configuration_categorie.php');
		exit;
	}
	$_SESSION['ADMConnected']['Done'] = 'La configuration du site a été mis à jour.';
	header('location: '.PAGE);
	exit;
}

$aHead = array('PageTitre' => 'Configuration');
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-9 col-xs-12"><h2>Configuration</h2>';
$sNomSite = s($sRConfig['NomSite']);
$sTagLine = s($sRConfig['TagLine']);
$sThemeColor = s($sRConfig['ThemeColor']);
$sMailDestinataire = s($sRConfig['MailDestinataire']);
$sMailLorsPaiement = s($sRConfig['MailLorsPaiement']);
$sGoogleAnalytics = s($sRConfig['GoogleAnalytics']);
$sRegles = s($sRConfig['Regles']);
$sMentionsLegales = s($sRConfig['MentionsLegales']);
$sHomeMetaTitle = s($sRConfig['HomeMetaTitle']);
$sHomeMetaDescription = s($sRConfig['HomeMetaDescription']);
$sHomeDescriptionH2 = s($sRConfig['HomeDescriptionH2']);
$sHomeDescription = s($sRConfig['HomeDescription']);
$sHomeSitesH2 = s($sRConfig['HomeSitesH2']);
$sHomeSitesAffiches = s($sRConfig['HomeSitesAffiches']);
$sSidebarCategorieH2 = s($sRConfig['SidebarCategorieH2']);
$sSidebarSitesH2 = s($sRConfig['SidebarSitesH2']);
$sSidebarNavigationH2 = s($sRConfig['SidebarNavigationH2']);
$sCaracteresMinDescription1 = s($sRConfig['CaracteresMinDescription1']);
$sCaracteresMaxDescription1 = s($sRConfig['CaracteresMaxDescription1']);
$sCaracteresMinDescription2 = s($sRConfig['CaracteresMinDescription2']);
$sCaracteresMaxDescription2 = s($sRConfig['CaracteresMaxDescription2']);
$sFicheMetaTitle = s($sRConfig['FicheMetaTitle']);
$sFicheMetaDescription = s($sRConfig['FicheMetaDescription']);
$sPaiementDescription = s($sRConfig['PaiementDescription']);
$sPaypalPrix = s($sRConfig['PaypalPrix']);
$sPaypalMonnaie = s($sRConfig['PaypalMonnaie']);
$sPaypalMail = s($sRConfig['PaypalMail']);
$sPaypalBoutonId = s($sRConfig['PaypalBoutonId']);
if(!empty($_SESSION['ADMConnected']['Done'])) {
	echo '<div class="alert alert-success"><p>'.$_SESSION['ADMConnected']['Done'].'</p></div>';
	unset($_SESSION['ADMConnected']['Done']);
}
elseif(!empty($_SESSION['ADMConnected']['Configuration']['Erreur'])) {
	$sNomSite = s($_SESSION['ADMConnected']['Configuration']['NomSite']);
	$sTagLine = s($_SESSION['ADMConnected']['Configuration']['TagLine']);
	$sThemeColor = s($_SESSION['ADMConnected']['Configuration']['ThemeColor']);
	$sMailDestinataire = s($_SESSION['ADMConnected']['Configuration']['MailDestinataire']);
	$sMailLorsPaiement = s($_SESSION['ADMConnected']['Configuration']['MailLorsPaiement']);
	$sGoogleAnalytics = s($_SESSION['ADMConnected']['Configuration']['GoogleAnalytics']);
	$sRegles = s($_SESSION['ADMConnected']['Configuration']['Regles']);
	$sMentionsLegales = s($_SESSION['ADMConnected']['Configuration']['MentionsLegales']);
	$sHomeMetaTitle = s($_SESSION['ADMConnected']['Configuration']['HomeMetaTitle']);
	$sHomeMetaDescription = s($_SESSION['ADMConnected']['Configuration']['HomeMetaDescription']);
	$sHomeDescriptionH2 = s($_SESSION['ADMConnected']['Configuration']['HomeDescriptionH2']);
	$sHomeDescription = s($_SESSION['ADMConnected']['Configuration']['HomeDescription']);
	$sHomeSitesH2 = s($_SESSION['ADMConnected']['Configuration']['HomeSitesH2']);
	$sHomeSitesAffiches = s($_SESSION['ADMConnected']['Configuration']['HomeSitesAffiches']);
	$sSidebarCategorieH2 = s($_SESSION['ADMConnected']['Configuration']['SidebarCategorieH2']);
	$sSidebarSitesH2 = s($_SESSION['ADMConnected']['Configuration']['SidebarSitesH2']);
	$sSidebarNavigationH2 = s($_SESSION['ADMConnected']['Configuration']['SidebarNavigationH2']);
	$sCaracteresMinDescription1 = s($_SESSION['ADMConnected']['Configuration']['CaracteresMinDescription1']);
	$sCaracteresMaxDescription1 = s($_SESSION['ADMConnected']['Configuration']['CaracteresMaxDescription1']);
	$sCaracteresMinDescription2 = s($_SESSION['ADMConnected']['Configuration']['CaracteresMinDescription2']);
	$sCaracteresMaxDescription2 = s($_SESSION['ADMConnected']['Configuration']['CaracteresMaxDescription2']);
	$sFicheMetaTitle = s($_SESSION['ADMConnected']['Configuration']['FicheMetaTitle']);
	$sFicheMetaDescription = s($_SESSION['ADMConnected']['Configuration']['FicheMetaDescription']);
	$sPaiementDescription = s($_SESSION['ADMConnected']['Configuration']['PaiementDescription']);
	$sPaypalPrix = s($_SESSION['ADMConnected']['Configuration']['PaypalPrix']);
	$sPaypalMonnaie = s($_SESSION['ADMConnected']['Configuration']['PaypalMonnaie']);
	$sPaypalMail = s($_SESSION['ADMConnected']['Configuration']['PaypalMail']);
	$sPaypalBoutonId = s($_SESSION['ADMConnected']['Configuration']['PaypalBoutonId']);
	echo '<div class="alert alert-danger">'.$_SESSION['ADMConnected']['Configuration']['Erreur'].'</div>';
	unset($_SESSION['ADMConnected']['Configuration']);
}
echo '<form action="'.PAGE.'?action=1" method="post" class=""><div class="form-group"><label for="NomSite" class="control-label">Nom du site</label><input type="text" name="NomSite" id="NomSite" class="form-control" value="'.$sNomSite.'" placeholder="" required><span class="help-block">H1 sur toutes les pages du site</span></div><div class="form-group"><label for="TagLine" class="control-label">TagLine du site</label><input type="text" name="TagLine" id="TagLine" class="form-control" value="'.$sTagLine.'" placeholder="" required><span class="help-block">TagLine sous le H1 sur toutes les pages du site</span></div><div class="form-group"><label for="ThemeColor" class="control-label">ThemeColor</label><input type="text" name="ThemeColor" id="ThemeColor" class="form-control" value="'.$sThemeColor.'" placeholder="" required><span class="help-block">Couleur du theme (green, blue, orange)</span></div><div class="form-group"><label for="MailDestinataire" class="control-label">Mail destinataire</label><input type="text" name="MailDestinataire" id="MailDestinataire" class="form-control" value="'.$sMailDestinataire.'" placeholder="" required><span class="help-block">Email où sont envoyés les emails de contacts + paiement</span></div><div class="form-group"><label for="MailLorsPaiement" class="control-label">Mail Lors Paiement</label><select class="form-control" id="MailLorsPaiement" name="MailLorsPaiement"><option value="1"'; if($sMailLorsPaiement == 1) echo ' selected="selected"'; echo '>Oui</option><option value="0"'; if($sMailLorsPaiement == 0) echo ' selected="selected"'; echo '>Non</option></select></div><div class="form-group"><label for="GoogleAnalytics" class="control-label">Google Analytics</label><input type="text" name="GoogleAnalytics" id="GoogleAnalytics" class="form-control" value="'.$sGoogleAnalytics.'" placeholder=""><span class="help-block">Entrez le code UA-XXXXX-YY de google analytics</span></div><div class="form-group"><label for="MentionsLegales" class="control-label">Mentions Legales</label><textarea name="MentionsLegales" id="MentionsLegales" class="form-control" placeholder="" cols="40" rows="8" required>'.$sMentionsLegales.'</textarea><span class="help-block">Page Mentions légales</span></div><hr /><h3>Home</h3><div class="form-group"><label for="HomeMetaTitle" class="control-label">Balise Title</label><input type="text" name="HomeMetaTitle" id="HomeMetaTitle" class="form-control" value="'.$sHomeMetaTitle.'" placeholder="" required><span class="help-block">balise title sur la page d\'accueil</span></div><div class="form-group"><label for="HomeMetaDescription" class="control-label">Balise Meta Description</label><input type="text" name="HomeMetaDescription" id="HomeMetaDescription" class="form-control" value="'.$sHomeMetaDescription.'" placeholder="" required><span class="help-block">balise meta description sur la page d\'accueil</span></div><div class="form-group"><label for="HomeDescriptionH2" class="control-label">Description H2</label><input type="text" name="HomeDescriptionH2" id="HomeDescriptionH2" class="form-control" value="'.$sHomeDescriptionH2.'" placeholder="" required><span class="help-block">H2 avant la description sur la page d\'accueil</span></div><div class="form-group"><label for="HomeDescription" class="control-label">Description</label><textarea name="HomeDescription" id="HomeDescription" class="form-control" placeholder="" cols="40" rows="8" required>'.$sHomeDescription.'</textarea><span class="help-block">Texte descriptif / de présentation sur la page d\'acccueil</span></div><div class="form-group"><label for="HomeSitesH2" class="control-label">H2 des sites</label><input type="text" name="HomeSitesH2" id="HomeSitesH2" class="form-control" value="'.$sHomeSitesH2.'" placeholder="" required><span class="help-block">H2 avant la liste des sites sur la page d\'accueil</span></div><div class="form-group"><label for="HomeSitesAffiches" class="control-label">Sites Affichés</label><input type="text" name="HomeSitesAffiches" id="HomeSitesAffiches" class="form-control" value="'.$sHomeSitesAffiches.'" placeholder="" required><span class="help-block">Nombre de sites affichés sur la page d\'accueil</span></div><hr /><h3>Sidebar</h3><div class="form-group"><label for="SidebarCategorieH2" class="control-label">Catégories H2</label><input type="text" name="SidebarCategorieH2" id="SidebarCategorieH2" class="form-control" value="'.$sSidebarCategorieH2.'" placeholder="" required><span class="help-block">H2 dans la sidebar pour les catégories</span></div><div class="form-group"><label for="SidebarSitesH2" class="control-label">Sites H2</label><input type="text" name="SidebarSitesH2" id="SidebarSitesH2" class="form-control" value="'.$sSidebarSitesH2.'" placeholder="" required><span class="help-block">H2 dans la sidebar pour les sites mis en avant</span></div><div class="form-group"><label for="SidebarNavigationH2" class="control-label">Navigation H2</label><input type="text" name="SidebarNavigationH2" id="SidebarNavigationH2" class="form-control" value="'.$sSidebarNavigationH2.'" placeholder="" required><span class="help-block">H2 dans la sidebar pour la navigation</span></div><hr /><h3>Proposer un site</h3><div class="form-group"><label for="Regles" class="control-label">Regles</label><textarea name="Regles" id="Regles" class="form-control" placeholder="" cols="40" rows="8" required>'.$sRegles.'</textarea><span class="help-block">Les règles doivent être acceptées avant d\'accéder au formulaire.</span></div><div class="form-group"><label for="CaracteresMinDescription1" class="control-label">Caractères min description courte</label><input type="text" name="CaracteresMinDescription1" id="CaracteresMinDescription1" class="form-control" value="'.$sCaracteresMinDescription1.'" placeholder="" required><span class="help-block">Nombre de caractères minimum pour la description courte</span></div><div class="form-group"><label for="CaracteresMaxDescription1" class="control-label">Caractères max description courte</label><input type="text" name="CaracteresMaxDescription1" id="CaracteresMaxDescription1" class="form-control" value="'.$sCaracteresMaxDescription1.'" placeholder="" required><span class="help-block">Nombre de caractères maximum pour la description courte</span></div><div class="form-group"><label for="CaracteresMinDescription2" class="control-label">Caractères min description longue</label><input type="text" name="CaracteresMinDescription2" id="CaracteresMinDescription2" class="form-control" value="'.$sCaracteresMinDescription2.'" placeholder="" required><span class="help-block">Nombre de caractères minimum pour la description longue</span></div><div class="form-group"><label for="CaracteresMaxDescription2" class="control-label">Caractères max description longue</label><input type="text" name="CaracteresMaxDescription2" id="CaracteresMaxDescription2" class="form-control" value="'.$sCaracteresMaxDescription2.'" placeholder="" required><span class="help-block">Nombre de caractères maximum pour la description longue</span></div><div class="form-group"><label for="FicheMetaTitle" class="control-label">Meta Title pour les fiches</label><input type="text" name="FicheMetaTitle" id="FicheMetaTitle" class="form-control" value="'.$sFicheMetaTitle.'" placeholder="" required><span class="help-block">la variable ::NOMSITE remplacera l\'adresse du site</span></div><div class="form-group"><label for="FicheMetaDescription" class="control-label">Meta Description pour les fiches</label><input type="text" name="FicheMetaDescription" id="FicheMetaDescription" class="form-control" value="'.$sFicheMetaDescription.'" placeholder="" required><span class="help-block">la variable ::NOMSITE remplacera l\'adresse du site. ::NOMCATEGORIE remplacera le nom de la catégorie</span></div><div class="form-group"><label for="PaiementDescription" class="control-label">Paiement Description</label><textarea name="PaiementDescription" id="PaiementDescription" class="form-control" placeholder="" cols="40" rows="8" required>'.$sPaiementDescription.'</textarea><span class="help-block">Description sur la page paiement. La variable ::PRIX remplacera le prix.</span></div><hr /><h3>Paypal</h3><div class="form-group"><label for="PaypalPrix" class="control-label">Prix</label><input type="text" name="PaypalPrix" id="PaypalPrix" class="form-control" value="'.$sPaypalPrix.'" placeholder="" required><span class="help-block">Prix pour proposer un site</span></div><div class="form-group"><label for="PaypalMonnaie" class="control-label">Monnaie</label><input type="text" name="PaypalMonnaie" id="PaypalMonnaie" class="form-control" value="'.$sPaypalMonnaie.'" placeholder="" required><span class="help-block">Monnaie utilisé pour proposer un site</span></div><div class="form-group"><label for="PaypalMail" class="control-label">Mail</label><input type="email" name="PaypalMail" id="PaypalMail" class="form-control" value="'.$sPaypalMail.'" placeholder="" required><span class="help-block">Adresse email du compte paypal pour la vérification IPN</span></div><div class="form-group"><label for="PaypalBoutonId" class="control-label">ID du bouton</label><input type="text" name="PaypalBoutonId" id="PaypalBoutonId" class="form-control" value="'.$sPaypalBoutonId.'" placeholder="" required><span class="help-block">ID du bouton enregistré chez paypal</span></div><button type="submit" class="btn btn-primary btn-block">Modifier</button></form><br /><br />';
echo '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
$nIncludeMenu = 1;
include __DIR__.'/configuration_menu.php';
echo '</div></div>';
bot();
exit;
?>