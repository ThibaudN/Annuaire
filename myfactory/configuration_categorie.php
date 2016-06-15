<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_SESSION['ADMConnected']['bCon'])) { header('location: login_form.php'); exit; }

if(!empty($_GET['visible']) and is_numeric($_GET['visible'])) {
	$rR = "SELECT * FROM ".S_CATEGORIES." WHERE id = ?";
	$aArg = array($_GET['visible']);
	$aCat = $oSql->GetLine($rR,$aArg);
	if(empty($aCat)) {
		header('location: '.PAGE);
		exit;
	}
	$nOnline = 1;
	$_SESSION['ADMConnected']['Done'] = 'La catégorie est désormais visible.';
	if(!empty($aCat['Online'])) {
		if(!empty($aCat['Sites'])) {
			$_SESSION['ADMConnected']['Erreur'] = 'Il faut que la catégorie ne contienne aucun site pour la désindexée.';
			header('location: '.PAGE.'?id='.$aCat['id']);
			exit;
		}
		$nOnline = 0;
		$_SESSION['ADMConnected']['Done'] = 'La catégorie est désormais cachée.';
	}
	$rR = "UPDATE ".S_CATEGORIES." SET Online = ? where id = ?";
	$aArg = array($nOnline,$aCat['id']);
	$oSql->Query($rR,$aArg);
	header('location: '.PAGE.'?id='.$aCat['id']);
	exit;
}
elseif(!empty($_GET['add']) and is_numeric($_GET['add'])) {
	function GetData() {
		$_SESSION['ADMConnected']['AddCat']['Titre'] = (!isset($_POST['Titre']) ? '' : s($_POST['Titre']));
		$_SESSION['ADMConnected']['AddCat']['TitreUrl'] = (!isset($_POST['TitreUrl']) ? '' : s($_POST['TitreUrl']));
		$_SESSION['ADMConnected']['AddCat']['DescriptionH2'] = (!isset($_POST['DescriptionH2']) ? '' : s($_POST['DescriptionH2']));
		$_SESSION['ADMConnected']['AddCat']['Description'] = (!isset($_POST['Description']) ? '' : s($_POST['Description']));
		$_SESSION['ADMConnected']['AddCat']['SitesH2'] = (!isset($_POST['SitesH2']) ? '' : s($_POST['SitesH2']));
		$_SESSION['ADMConnected']['AddCat']['MetaTitle'] = (!isset($_POST['MetaTitle']) ? '' : s($_POST['MetaTitle']));
		$_SESSION['ADMConnected']['AddCat']['MetaDescription'] = (!isset($_POST['MetaDescription']) ? '' : s($_POST['MetaDescription']));
		$_SESSION['ADMConnected']['AddCat']['ParPage'] = (!isset($_POST['ParPage']) ? '' : s($_POST['ParPage']));
	}
	if(empty($_POST['Titre'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le titre est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['TitreUrl'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'L\'url interne est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['DescriptionH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le H2 lié à la description de la catégorie est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['Description'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'La description est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['SitesH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le H2 lié aux sites listés est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['MetaTitle'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'La meta title est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['MetaDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'La meta description est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	elseif(empty($_POST['ParPage']) or !is_numeric($_POST['ParPage']) or $_POST['ParPage'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le nombre d\'items par page est vide.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	$rR = "SELECT * FROM ".S_CATEGORIES." where Titre = ?";
	$aArg = array($_POST['Titre']);
	$sRVerif = $oSql->GetLine($rR,$aArg);
	if(!empty($sRVerif)) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Ce titre de catégorie est déjà utilisé.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	$sUrlInterne = hs($_POST['TitreUrl']);
	$rR = "SELECT * FROM ".S_CATEGORIES." where TitreUrl = ?";
	$aArg = array($sUrlInterne);
	$sRVerif = $oSql->GetLine($rR,$aArg);
	if(!empty($sRVerif)) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Cette url interne de catégorie est déjà utilisée.';
		header('location: '.PAGE.'?form=1');
		exit;
	}
	$rR = "INSERT INTO ".S_CATEGORIES." (Titre,TitreUrl,DescriptionH2,Description,SitesH2,MetaTitle,MetaDescription,ParPage,Online) values (?,?,?,?,?,?,?,?,0)";
	$aArg = array(s($_POST['Titre']),$sUrlInterne,$_POST['DescriptionH2'],$_POST['Description'],$_POST['SitesH2'],$_POST['MetaTitle'],$_POST['MetaDescription'],$_POST['ParPage']);
	$nID = $oSql->QueryIns($rR,$aArg);
	
	header('location: '.PAGE.'?id='.$nID);
	exit;
}
elseif(!empty($_GET['edit']) and is_numeric($_GET['edit'])) {
	function GetData() {
		$_SESSION['ADMConnected']['AddCat']['Titre'] = (!isset($_POST['Titre']) ? '' : s($_POST['Titre']));
		$_SESSION['ADMConnected']['AddCat']['TitreUrl'] = (!isset($_POST['TitreUrl']) ? '' : s($_POST['TitreUrl']));
		$_SESSION['ADMConnected']['AddCat']['DescriptionH2'] = (!isset($_POST['DescriptionH2']) ? '' : s($_POST['DescriptionH2']));
		$_SESSION['ADMConnected']['AddCat']['Description'] = (!isset($_POST['Description']) ? '' : s($_POST['Description']));
		$_SESSION['ADMConnected']['AddCat']['SitesH2'] = (!isset($_POST['SitesH2']) ? '' : s($_POST['SitesH2']));
		$_SESSION['ADMConnected']['AddCat']['MetaTitle'] = (!isset($_POST['MetaTitle']) ? '' : s($_POST['MetaTitle']));
		$_SESSION['ADMConnected']['AddCat']['MetaDescription'] = (!isset($_POST['MetaDescription']) ? '' : s($_POST['MetaDescription']));
		$_SESSION['ADMConnected']['AddCat']['ParPage'] = (!isset($_POST['ParPage']) ? '' : s($_POST['ParPage']));
	}
	if(empty($_POST['nId']) or !is_numeric($_POST['nId']) or $_POST['nId'] < 0) {
		header('location: '.PAGE);
		exit;
	}
	$rR = "SELECT * FROM ".S_CATEGORIES." WHERE id = ?";
	$aArg = array($_POST['nId']);
	$aCat = $oSql->GetLine($rR,$aArg);
	if(empty($aCat)) {
		header('location: '.PAGE);
		exit;
	}
	elseif(empty($_POST['Titre'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le titre est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['TitreUrl'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'L\'url interne est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['DescriptionH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le H2 lié à la description de la catégorie est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['Description'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'La description est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['SitesH2'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le H2 lié aux sites listés est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['MetaTitle'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'La meta title est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['MetaDescription'])) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'La meta description est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	elseif(empty($_POST['ParPage']) or !is_numeric($_POST['ParPage']) or $_POST['ParPage'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Le nombre d\'items par page est vide.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	$rR = "SELECT * FROM ".S_CATEGORIES." where Titre = ? and id != ?";
	$aArg = array(hs($_POST['Titre']),$aCat['id']);
	$sRVerif = $oSql->GetLine($rR,$aArg);
	if(!empty($sRVerif)) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Ce titre de catégorie est déjà utilisé.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	$sUrlInterne = hs($_POST['TitreUrl']);
	$rR = "SELECT * FROM ".S_CATEGORIES." where TitreUrl = ? and id != ?";
	$aArg = array($sUrlInterne,$aCat['id']);
	$sRVerif = $oSql->GetLine($rR,$aArg);
	if(!empty($sRVerif)) {
		GetData();
		$_SESSION['ADMConnected']['Erreur'] = 'Cette url interne de catégorie est déjà utilisée.';
		header('location: '.PAGE.'?form=1&modif='.$aCat['id']);
		exit;
	}
	
	$rR = "UPDATE ".S_CATEGORIES." SET Titre = ?,TitreUrl = ?,DescriptionH2 = ?,Description = ?,SitesH2 = ?,MetaTitle = ?,MetaDescription = ?,ParPage = ? where id = ?";
	$aArg = array(s($_POST['Titre']),$sUrlInterne,$_POST['DescriptionH2'],$_POST['Description'],$_POST['SitesH2'],$_POST['MetaTitle'],$_POST['MetaDescription'],$_POST['ParPage'],$aCat['id']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'La catégorie a été modifié.'; 
	header('location: '.PAGE.'?id='.$aCat['id']);
	exit;
}

$aEtatCategories = array(1 => 'Visible',0 => 'Cachée');
$aClassCategories = array(1 => 'success',0 => 'warning');

$aHead = array('PageTitre' => 'Catégories');
head($aHead);

echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-9 col-xs-12">';


if(!empty($_SESSION['ADMConnected']['Done'])) {
	echo '<div class="alert alert-success"><p>'.$_SESSION['ADMConnected']['Done'].'</p></div>';
	unset($_SESSION['ADMConnected']['Done']);
}
elseif(!empty($_SESSION['ADMConnected']['Erreur'])) {
	echo '<div class="alert alert-danger"><p>'.$_SESSION['ADMConnected']['Erreur'].'</p></div>';
	unset($_SESSION['ADMConnected']['Erreur']);
}


if(!empty($_GET['form']) and is_numeric($_GET['form'])) {
	$sBtn = 'Ajouter';
	$sAct = 'add';
	$sHidden = '';
	$sTitre = '';
	$sTitreUrl = '';
	$sDescriptionH2 = '';
	$sDescription = '';
	$sSitesH2 = '';
	$sMetaTitle = '';
	$sMetaDescription = '';
	$sParPage = 20;
	if(!empty($_GET['modif']) and is_numeric($_GET['modif'])) {
		$rR = "SELECT * FROM ".S_CATEGORIES." WHERE id = ?";
		$aArg = array($_GET['modif']);
		$aCat = $oSql->GetLine($rR,$aArg);
		if(!empty($aCat)) {
			$sBtn = 'Modifier';
			$sAct = 'edit';
			$sHidden = '<input type="hidden" name="nId" value="'.$aCat['id'].'" readonly="readonly" />';
			$sTitre = s($aCat['Titre']);
			$sTitreUrl = s($aCat['TitreUrl']);
			$sDescriptionH2 = s($aCat['DescriptionH2']);
			$sDescription = s($aCat['Description']);
			$sSitesH2 = s($aCat['SitesH2']);
			$sMetaTitle = s($aCat['MetaTitle']);
			$sMetaDescription = s($aCat['MetaDescription']);
			$sParPage = s($aCat['ParPage']);
		}
	}
	if(isset($_SESSION['ADMConnected']['AddCat'])) {
		$sTitre = s($_SESSION['ADMConnected']['AddCat']['Titre']);
		$sTitreUrl = s($_SESSION['ADMConnected']['AddCat']['TitreUrl']);
		$sDescriptionH2 = s($_SESSION['ADMConnected']['AddCat']['DescriptionH2']);
		$sDescription = s($_SESSION['ADMConnected']['AddCat']['Description']);
		$sSitesH2 = s($_SESSION['ADMConnected']['AddCat']['SitesH2']);
		$sMetaTitle = s($_SESSION['ADMConnected']['AddCat']['MetaTitle']);
		$sMetaDescription = s($_SESSION['ADMConnected']['AddCat']['MetaDescription']);
		$sParPage = s($_SESSION['ADMConnected']['AddCat']['ParPage']);
		unset($_SESSION['ADMConnected']['AddCat']);
	}
	echo '<h2>'.$sBtn.' une catégorie</h2><form method="post" action="'.PAGE.'?'.$sAct.'=1">'.$sHidden.'<div class="form-group"><label for="Titre" class="control-label">Titre</label><input type="text" name="Titre" id="Titre" class="form-control" value="'.$sTitre.'" required></div><div class="form-group"><label for="TitreUrl" class="control-label">Url interne</label><input type="text" name="TitreUrl" id="TitreUrl" class="form-control" value="'.$sTitreUrl.'" required></div><div class="form-group"><label for="ParPage" class="control-label">Items par Page</label><input type="text" name="ParPage" id="ParPage" class="form-control" value="'.$sParPage.'" required></div><div class="form-group"><label for="DescriptionH2" class="control-label">H2 Description</label><input type="text" name="DescriptionH2" id="DescriptionH2" class="form-control" value="'.$sDescriptionH2.'" placeholder="H2 au dessus de la description" required></div><div class="form-group"><label for="Description" class="control-label">Description</label><textarea name="Description" id="Description" class="form-control" placeholder="Cette description affichée sur sa page de catégorie" cols="40" rows="8" required>'.$sDescription.'</textarea></div><div class="form-group"><label for="SitesH2" class="control-label">H2 Sites</label><input type="text" name="SitesH2" id="SitesH2" class="form-control" value="'.$sSitesH2.'" placeholder="H2 au dessus des sites listés" required></div><div class="form-group"><label for="MetaTitle" class="control-label">Meta Title</label><input type="text" name="MetaTitle" id="MetaTitle" class="form-control" value="'.$sMetaTitle.'" placeholder="balise html title" required></div><div class="form-group"><label for="MetaDescription" class="control-label">Meta Description</label><input type="text" name="MetaDescription" id="MetaDescription" class="form-control" value="'.$sMetaDescription.'" placeholder="balise meta description" required></div><button type="submit" class="btn btn-primary btn-block">'.$sBtn.' la catégorie</button></form><hr />';
}

elseif(!empty($_GET['id']) and is_numeric($_GET['id'])) {
	$rR = "SELECT * FROM ".S_CATEGORIES." WHERE id = ?";
	$aArg = array($_GET['id']);
	$aCat = $oSql->GetLine($rR,$aArg);
	if(!empty($aCat))
		echo '<h2>La catégorie '.$aCat['Titre'].'</h2><table class="table table-striped"><tbody><tr><td width="20%">Titre</td><td>'.hs($aCat['Titre']).'</td></tr><tr><td>Url</td><td>'.hs($aCat['TitreUrl']).'</td></tr><tr><td>Items par page</td><td>'.hs($aCat['ParPage']).'</td></tr><tr><td>Etat</td><td><span class="label label-'.$aClassCategories[$aCat['Online']].'">'.s($aEtatCategories[$aCat['Online']]).'</span></td></tr><tr><td>Sites en ligne</td><td>'.hs($aCat['Sites']).'</td></tr><tr><td>H2 Description</td><td>'.hs($aCat['DescriptionH2']).'</td></tr><tr><td>Description</td><td>'.hs($aCat['Description']).'</td></tr><tr><td>H2 Sites</td><td>'.hs($aCat['SitesH2']).'</td></tr><tr><td>Meta Title</td><td>'.hs($aCat['MetaTitle']).'</td></tr><tr><td>Meta Description</td><td>'.hs($aCat['MetaDescription']).'</td></tr></tbody></table><hr />';
}

echo '<h2>Les catégories</h2>
<table class="table table-striped"><thead><tr><th width="6%">id</th><th>Nom</th><th>NomUrl</th><th>Site</th><th>Etat</th><th width="10%">&nbsp;</th></tr></thead><tbody>';
$rR = "SELECT * FROM ".S_CATEGORIES." WHERE 1 order by Titre asc";
foreach($oSql->GetAll($rR) as $k => $v) {
	echo '<tr><td>#'.s($v['id']).'</td><td>'.s($v['Titre']);
	if(empty($v['MetaTitle']) or empty($v['MetaDescription']))
		echo ' <i class="fa fa-exclamation-triangle" title="Meta title et/ou description vide"></i>';
	
	echo '</td><td>'.s($v['TitreUrl']).'</td><td>'.s($v['Sites']).'</td><td><span class="label label-'.$aClassCategories[$v['Online']].'">'.s($aEtatCategories[$v['Online']]).'</span></td><td><a href="'.PAGE.'?id='.$v['id'].'" title="Voir" class="btn btn-primary btn-xs"><i class="fa fa-eye"></i> Voir</a></td></tr>';
}
echo '</tbody></table>';

echo '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12"><h2>Action</h2>';
if(!empty($_GET['form'])) {
	echo '';
	if(!empty($aCat))
		echo '<a href="'.PAGE.'?id='.$aCat['id'].'" title="Retour" class="btn btn-block btn-default"><i class="fa fa-arrow-left"></i> Retour</a>';
	else
		echo '<a href="'.PAGE.'" title="Retour" class="btn btn-block btn-default"><i class="fa fa-arrow-left"></i> Retour</a>';

}
elseif(!empty($aCat)) {
	echo '<a href="'.PAGE.'" title="Retour" class="btn btn-block btn-default"><i class="fa fa-arrow-left"></i> Retour</a><a href="'.PAGE.'?form=1&modif='.$aCat['id'].'" title="" class="btn btn-block btn-primary"><i class="fa fa-edit"></i> Modifier</a>';
if(empty($aCat['Online']))
	echo '<a href="'.PAGE.'?visible='.$aCat['id'].'" title="Afficher la catégorie" class="btn btn-block btn-success">Afficher la catégorie</a>';
else
	echo '<a href="'.PAGE.'?visible='.$aCat['id'].'" title="Cacher la catégorie" class="btn btn-block btn-danger">Cacher la catégorie</a><a href="'.URLSITE.hs($aCat['TitreUrl']).'.html" title="Voir sur le site" target="_blank" class="btn btn-block btn-success"><i class="fa fa-eye"></i> Voir</a>';
}
else
	echo '<a href="'.PAGE.'?form=1" title="Ajouter une catégorie" class="btn btn-block btn-primary"><i class="fa fa-plus"></i> Ajouter une catégorie</a>';

$nIncludeMenu = 3;
include __DIR__.'/configuration_menu.php';

echo '</div></div>';
bot();
?>