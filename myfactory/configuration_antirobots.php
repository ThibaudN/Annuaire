<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(empty($_SESSION['ADMConnected']['bCon'])) { header('location: login_form.php'); exit; }

elseif(!empty($_GET['add']) and is_numeric($_GET['add'])) {
	function GetData() {
		$_SESSION['ADMConnected']['AntiRobots']['Question'] = (!isset($_POST['Question']) ? '' : s($_POST['Question']));
		$_SESSION['ADMConnected']['AntiRobots']['Reponse'] = (!isset($_POST['Reponse']) ? '' : s($_POST['Reponse']));
	}
	if(empty($_POST['Question'])) {
		GetData();
		$_SESSION['ADMConnected']['AntiRobots']['Erreur'] = 'Vous n\'avez pas saisi de question.';
		header('location: '.PAGE.'#form');
		exit;
	}
	elseif(empty($_POST['Reponse'])) {
		GetData();
		$_SESSION['ADMConnected']['AntiRobots']['Erreur'] = 'Vous n\'avez pas saisi de réponse.';
		header('location: '.PAGE.'#form');
		exit;
	}
	$rR = "INSERT INTO ".S_ANTIROBOTS." (Question,Reponse) values (?,?)";
	$aArg = array($_POST['Question'],$_POST['Reponse']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'La question anti-robots a été ajouté.';
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['edit']) and is_numeric($_GET['edit'])) {
	function GetData() {
		$_SESSION['ADMConnected']['AntiRobots']['Question'] = (!isset($_POST['Question']) ? '' : s($_POST['Question']));
		$_SESSION['ADMConnected']['AntiRobots']['Reponse'] = (!isset($_POST['Reponse']) ? '' : s($_POST['Reponse']));
	}
	if(empty($_POST['nId']) or !is_numeric($_POST['nId'])) {
		header('location: '.PAGE);
		exit;
	}
	$rR = "SELECT * FROM ".S_ANTIROBOTS." WHERE id = ?";
	$aArg = array($_POST['nId']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(empty($sR)) {
		header('location: '.PAGE);
		exit;
	}
	if(empty($_POST['Question'])) {
		GetData();
		$_SESSION['ADMConnected']['AntiRobots']['Erreur'] = 'Vous n\'avez pas saisi de question.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	elseif(empty($_POST['Reponse'])) {
		GetData();
		$_SESSION['ADMConnected']['AntiRobots']['Erreur'] = 'Vous n\'avez pas saisi de réponse.';
		header('location: '.PAGE.'?modif='.$sR['id'].'#form');
		exit;
	}
	$rR = "UPDATE ".S_ANTIROBOTS." SET Question = ?,Reponse = ? where id = ?";
	$aArg = array($_POST['Question'],$_POST['Reponse'],$sR['id']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'La question anti-robots a été modifié.';
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['delete']) and is_numeric($_GET['delete'])) {
	$rR = "DELETE FROM ".S_ANTIROBOTS." where id = ?";
	$aArg = array($_GET['delete']);
	$oSql->Query($rR,$aArg);
	$_SESSION['ADMConnected']['Done'] = 'La question anti-robots a été supprimé.';
	header('location: '.PAGE);
	exit;
}

$aHead = array('PageTitre' => 'Anti-Robots');
head($aHead);
echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-9 col-xs-12"><h2>Anti-Robots</h2>';


if(!empty($_SESSION['ADMConnected']['Done'])) {
	echo '<div class="alert alert-success"><p>'.$_SESSION['ADMConnected']['Done'].'</p></div>';
	unset($_SESSION['ADMConnected']['Done']);
}


$rR = "SELECT * FROM ".S_ANTIROBOTS." order by Question asc";
$aArg = array();
$sRow = $oSql->GetAll($rR,$aArg);

echo '<table class="table table-striped"><thead><tr><th width="6%">#</th><th>Question</th><th>Reponse</th><th width="10%">&nbsp;</th></tr></thead><tbody>';
foreach($sRow as $k => $v) {
	echo '<tr><td>#'.s($v['id']).'</td><td>'.s($v['Question']).'</td><td>'.s($v['Reponse']).'</td><td>
<a href="'.PAGE.'?modif='.$v['id'].'#form" class="btn btn-xs btn-primary"><i class="fa fa-edit"></i></a> <a href="'.PAGE.'?delete='.$v['id'].'" class="btn btn-xs btn-danger"><i class="fa fa-times"></i></a>
</td></tr>';
}
echo '</tbody></table><hr />';

$sBtn = 'Ajouter';
$sAct = 'add';
$sHidden = '';
$sQuestion = '';
$sReponse = '';
if(!empty($_GET['modif']) and is_numeric($_GET['modif'])) {
	$rR = "SELECT * FROM ".S_ANTIROBOTS." WHERE id = ?";
	$aArg = array($_GET['modif']);
	$sR = $oSql->GetLine($rR,$aArg);
	if(!empty($sR)) {
		$sBtn = 'Modifier';
		$sAct = 'edit';
		$sHidden = '<input type="hidden" readonly="readonly" name="nId" value="'.$sR['id'].'" />';
		$sQuestion = s($sR['Question']);
		$sReponse = s($sR['Reponse']);
	}
}
if(!empty($_SESSION['ADMConnected']['AntiRobots']['Erreur'])) {
	$sQuestion = s($_SESSION['ADMConnected']['AntiRobots']['Question']);
	$sReponse = s($_SESSION['ADMConnected']['AntiRobots']['Reponse']);
	echo '<div class="alert alert-danger"><p>'.$_SESSION['ADMConnected']['AntiRobots']['Erreur'].'</p></div>';
	unset($_SESSION['ADMConnected']['AntiRobots']);
}

echo '<a name="form"></a><h2>'.$sBtn.' une question</h2><form action="'.PAGE.'?'.$sAct.'=1" method="post">'.$sHidden.'
<div class="form-group"><label for="Question" class="control-label">Question</label><input type="text" name="Question" id="Question" class="form-control" value="'.$sQuestion.'" placeholder="" required></div><div class="form-group"><label for="Reponse" class="control-label">Reponse</label><input type="text" name="Reponse" id="Reponse" class="form-control" value="'.$sReponse.'" placeholder="" required></div><button type="submit" class="btn btn-primary btn-block">'.$sBtn.'</button></form><br /><br />';


echo '</div><div class="col-lg-3 col-md-3 col-sm-3 col-xs-12">';
$nIncludeMenu = 2;
include __DIR__.'/configuration_menu.php';
echo '</div></div>';
bot();
exit;