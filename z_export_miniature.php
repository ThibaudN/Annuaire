<?php
require __DIR__."/fonctions.php";
header("Content-Type: text/html; charset=UTF-8");
#header('Content-Type: application/xml');
if(!empty($_GET['nGet']) and is_numeric($_GET['nGet'])) {
	$aExport = array();
	$rR = "SELECT * FROM ".S_FICHES." WHERE Image = '' and Etat = 2";
	foreach($oSql->GetAll($rR) as $k => $v) {
		$aExport[] = array('i' => $v['id'],'u' => s($v['Url']),'n' => s($v['TitreUrl']));
	}
	echo json_encode($aExport);
	exit;
}
elseif(!empty($_GET['nUp']) and is_numeric($_GET['nUp'])) {
	$rR = "SELECT F.*,C.TitreUrl as NomCatUrl FROM ".S_FICHES." F JOIN ".S_CATEGORIES." C ON C.id = F.idCategorie WHERE F.Image = '' and F.Etat = 2 and F.id = ?";
	$aArg = array($_GET['nUp']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(!empty($sR)) {
		$sImage = 'http://51.254.219.101/screen/'.hs($sR['TitreUrl']).'.jpg';
		$rR = "UPDATE ".S_FICHES." SET Image = ?,Etat = 1 where id = ?";
		$aArg = array($sImage,$sR['id']);
		$oSql->Query($rR,$aArg);
		#mise à jour catégorie
		CalculSiteCategorie();
		#envoie email
		$sLien = URLSITE.hs($sR['NomCatUrl']).'/'.hs($sR['TitreUrl']).'.html';
		$sSujet = 'site accepté';
		$sBodyHTML = file_get_contents('php-include/emails/inscription_acceptee.php',FILE_USE_INCLUDE_PATH);
		$sBodyHTML = str_replace("::DOMAINE",NOMDOMAINE,$sBodyHTML);
		$sBodyHTML = str_replace("::OBJET",$sSujet,$sBodyHTML);
		$sBodyHTML = str_replace("::SITE",$sR['Url'],$sBodyHTML);
		$sBodyHTML = str_replace("::LIEN",$sLien,$sBodyHTML);
		$sBody = "Bonjour !\n\nVous avez inscrit votre site ".$sR['Url']." dans notre annuaire ".NOMDOMAINE.".\nVotre site a été accepté. Voici son url personnalisée : ".$sLien."\n\n";
		$aMail = array('FromMail' => MAILEXPEDITEUR,'FromName' => NOMDOMAINE,'Subject' => NOMDOMAINE.' - '.$sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sR['Mail']);
		myMail($aMail);
	}
	exit;
}
?>