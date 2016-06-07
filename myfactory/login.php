<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(!empty($_POST)) {
	if(!empty($_SESSION['ADMConnected']['bCon'])) { header('location: index.php'); exit; }
	elseif(empty($_POST['ToiMemeTuSais'])) {
		$_SESSION['Log_Err'] = 'La case ToiMemeTuSais est vide.';
		header('location: login_form.php');
		exit;
	}
	elseif(TOIMEMETUSAIS != $_POST['ToiMemeTuSais']) {
		$_SESSION['Log_Err'] = '...';
		header('location: login_form.php');
		exit;
	}
	$_SESSION['ADMConnected']['bCon'] = 1;
	header('location: index.php');
	exit;
}
elseif(!empty($_GET['logout']) and is_numeric($_GET['logout']) and strlen($_GET['logout']) == 1) {
	unset($_SESSION['ADMConnected']);
	header('location: index.php');
	exit;
}
else
	header('location: index.php');
?>