<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_SESSION['ADMConnected']['bCon'])) { header('location: login_form.php'); exit; }
$aOuiNon = array('Non','Oui');
if(!empty($_GET['add']) and is_numeric($_GET['add'])) {
	function GetData() {
		$_SESSION['ADMConnected']['Sidebar']['Lien'] = (!isset($_POST['Lien']) ? '' : s($_POST['Lien']));
		$_SESSION['ADMConnected']['Sidebar']['Titre'] = (!isset($_POST['Titre']) ? '' : s($_POST['Titre']));
		$_SESSION['ADMConnected']['Sidebar']['Nofollow'] = (!isset($_POST['Nofollow']) ? '' : s($_POST['Nofollow']));
		$_SESSION['ADMConnected']['Sidebar']['Externe'] = (!isset($_POST['Externe']) ? '' : s($_POST['Externe']));
		$_SESSION['ADMConnected']['Sidebar']['Ordre'] = (!isset($_POST['Ordre']) ? '' : s($_POST['Ordre']));
	}
	if(empty($_POST['Lien'])) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi de Lien.';
		header('location: '.PAGE.'#form');
		exit;
	}
	elseif(empty($_POST['Titre'])) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi de Titre/Ancre.';
		header('location: '.PAGE.'#form');
		exit;
	}
	elseif(!is_numeric($_POST['Nofollow']) or $_POST['Nofollow'] < 0 or $_POST['Nofollow'] > 1) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi le nofollow.';
		header('location: '.PAGE.'#form');
		exit;
	}
	elseif(!is_numeric($_POST['Externe']) or $_POST['Externe'] < 0 or $_POST['Externe'] > 1) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi le lien externe.';
		header('location: '.PAGE.'#form');
		exit;
	}
	elseif(!is_numeric($_POST['Ordre']) or $_POST['Ordre'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi d\'ordre.';
		header('location: '.PAGE.'#form');
		exit;
	}
	$rR = "INSERT INTO ".S_SIDEBAR." (Lien,Titre,Nofollow,Externe,Ordre) values (?,?,?,?,?)";
	$aArg = array($_POST['Lien'],$_POST['Titre'],$_POST['Nofollow'],$_POST['Externe'],$_POST['Ordre']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'Le lien dans la sidebar a été ajouté.';
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['edit']) and is_numeric($_GET['edit'])) {
	function GetData() {
		$_SESSION['ADMConnected']['Sidebar']['Lien'] = (!isset($_POST['Lien']) ? '' : s($_POST['Lien']));
		$_SESSION['ADMConnected']['Sidebar']['Titre'] = (!isset($_POST['Titre']) ? '' : s($_POST['Titre']));
		$_SESSION['ADMConnected']['Sidebar']['Nofollow'] = (!isset($_POST['Nofollow']) ? '' : s($_POST['Nofollow']));
		$_SESSION['ADMConnected']['Sidebar']['Externe'] = (!isset($_POST['Externe']) ? '' : s($_POST['Externe']));
		$_SESSION['ADMConnected']['Sidebar']['Ordre'] = (!isset($_POST['Ordre']) ? '' : s($_POST['Ordre']));
	}
	if(empty($_POST['nId']) or !is_numeric($_POST['nId'])) {
		header('location: '.PAGE);
		exit;
	}
	$rR = "SELECT * FROM ".S_SIDEBAR." WHERE id = ?";
	$aArg = array($_POST['nId']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(empty($sR)) {
		header('location: '.PAGE);
		exit;
	}
	elseif(empty($_POST['Lien'])) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi de Lien.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	elseif(empty($_POST['Titre'])) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi de Titre/Ancre.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	elseif(!is_numeric($_POST['Nofollow']) or $_POST['Nofollow'] < 0 or $_POST['Nofollow'] > 1) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi le nofollow.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	elseif(!is_numeric($_POST['Externe']) or $_POST['Externe'] < 0 or $_POST['Externe'] > 1) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi le lien externe.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	elseif(!is_numeric($_POST['Ordre']) or $_POST['Ordre'] < 0) {
		GetData();
		$_SESSION['ADMConnected']['Sidebar']['Erreur'] = 'Vous n\'avez pas saisi d\'ordre.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	$rR = "UPDATE ".S_SIDEBAR." SET Lien = ?,Titre = ?,Nofollow = ?,Externe = ?,Ordre = ? where id = ?";
	$aArg = array($_POST['Lien'],$_POST['Titre'],$_POST['Nofollow'],$_POST['Externe'],$_POST['Ordre'],$sR['id']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'Le lien a été modifié.';
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['delete']) and is_numeric($_GET['delete'])) {
	$rR = "DELETE FROM ".S_SIDEBAR." where id = ?";
	$aArg = array($_GET['delete']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'Le lien a été supprimé.';
	header('location: '.PAGE);
	exit;
}


$aHead = array('PageTitre' => 'Sidebar');
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-9 col-xs-12"><h2>Sidebar</h2>';

if(!empty($_SESSION['ADMConnected']['Done'])) {
	echo '<div class="alert alert-success"><p>'.$_SESSION['ADMConnected']['Done'].'</p></div>';
	unset($_SESSION['ADMConnected']['Done']);
}

echo '<table class="table table-striped"><thead><tr><th>Lien</th><th>Ancre</th><th>Nofollow</th><th>Externe</th><th>Ordre</th><th width="10%">&nbsp;</th></tr></thead><tbody>';
$rR = "SELECT * FROM ".S_SIDEBAR." WHERE 1 order by Ordre asc";
foreach($oSql->GetAll($rR) as $k => $v) {
	echo '<tr><td>'.s($v['Lien']).'</td><td>'.s($v['Titre']).'</td><td>'.s($aOuiNon[$v['Nofollow']]).'</td><td>'.s($aOuiNon[$v['Externe']]).'</td><td>'.s($v['Ordre']).'</td><td><a href="'.PAGE.'?modif='.$v['id'].'" title="Modifier" class="btn btn-primary btn-xs"><i class="fa fa-edit"></i></a> <a href="'.PAGE.'?delete='.$v['id'].'" title="Effacer" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></a></td></tr>';
}
echo '</tbody></table>';


$sBtn = 'Ajouter';
$sAct = 'add';
$sHidden = '';
$sLien = '';
$sTitre = '';
$sNofollow = 0;
$sExterne = 0;
$sOrdre = '';
if(!empty($_GET['modif']) and is_numeric($_GET['modif'])) {
	$rR = "SELECT * FROM ".S_SIDEBAR." WHERE id = ?";
	$aArg = array($_GET['modif']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(!empty($sR)) {
		$sBtn = 'Modifier';
		$sAct = 'edit';
		$sHidden = '<input type="hidden" name="nId" value="'.$sR['id'].'" readonly="readonly" />';
		$sLien = s($sR['Lien']);
		$sTitre = s($sR['Titre']);
		$sNofollow = s($sR['Nofollow']);
		$sExterne = s($sR['Externe']);
		$sOrdre = s($sR['Ordre']);
	}
}
elseif(!empty($_GET['nFiche']) and is_numeric($_GET['nFiche'])) {
	$rR = "SELECT * FROM ".S_FICHES." WHERE id = ?";
	$aArg = array($_GET['nFiche']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(!empty($sR)) {
		$sLien = URLSITE.hs($aCategories[$sR['idCategorie']]['TitreUrl']).'/'.hs($sR['TitreUrl']).'.html';
	}
}

if(isset($_SESSION['ADMConnected']['Sidebar'])) {
	$sOrdre = s($_SESSION['ADMConnected']['Sidebar']['Ordre']);
	$sExterne = s($_SESSION['ADMConnected']['Sidebar']['Externe']);
	$sNofollow = s($_SESSION['ADMConnected']['Sidebar']['Nofollow']);
	$sTitre = s($_SESSION['ADMConnected']['Sidebar']['Titre']);
	$sLien = s($_SESSION['ADMConnected']['Sidebar']['Lien']);
	echo '<div class="alert alert-danger"><p>'.$_SESSION['ADMConnected']['Sidebar']['Erreur'].'</p></div>';
	unset($_SESSION['ADMConnected']['Sidebar']);
}
echo '<hr /><a name="form"></a><h2>'.$sBtn.' un lien</h2><form method="post" action="'.PAGE.'?'.$sAct.'=1">'.$sHidden.'<div class="form-group"><label for="Lien" class="control-label">Lien</label><input type="text" name="Lien" id="Lien" class="form-control" value="'.$sLien.'" required></div><div class="form-group"><label for="Titre" class="control-label">Titre</label><input type="text" name="Titre" id="Titre" class="form-control" value="'.$sTitre.'" required></div><div class="form-group"><label for="Nofollow" class="control-label">Nofollow</label><select class="form-control" id="Nofollow" name="Nofollow">';
foreach($aOuiNon as $k => $v) {
	echo '<option value="'.$k.'"';
	if($k == $sNofollow)
		echo ' selected="selected"';
	echo '>'.$v.'</option>';
}
echo '</select></div><div class="form-group"><label for="Externe" class="control-label">Externe</label><select class="form-control" id="Externe" name="Externe">';
foreach($aOuiNon as $k => $v) {
	echo '<option value="'.$k.'"';
	if($k == $sExterne)
		echo ' selected="selected"';
	echo '>'.$v.'</option>';
}
echo '</select></div><div class="form-group"><label for="Ordre" class="control-label">Ordre</label><input type="text" name="Ordre" id="Ordre" class="form-control" value="'.$sOrdre.'" required></div><button type="submit" class="btn btn-primary btn-block">'.$sBtn.' le lien</button><br /><br /></form>';



echo '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
$nIncludeMenu = 4;
include __DIR__.'/configuration_menu.php';
echo '</div></div>';
bot();
exit;