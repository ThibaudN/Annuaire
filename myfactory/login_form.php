<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";
if(!empty($_SESSION['ADMConnected']['bCon'])) { header('location: index.php'); exit; }
$aHead = array('PageTitre' => 'Se Connecter');
head($aHead);
echo '<div class="row"><div class="col-lg-12 col-md-12 col-sm-12 col-xs-12"><h2>Se Connecter</h2>';
if(!empty($_SESSION['Log_Err'])) {
	echo '<div class="alert alert-danger"><p>'.$_SESSION['Log_Err'].'</p></div>';
	unset($_SESSION['Log_Err']);
}
echo '<form action="login.php" method="post" class=""><div class="form-group"><label for="ToiMemeTuSais">ToiMemeTuSais</label><input type="password" class="form-control" id="ToiMemeTuSais" name="ToiMemeTuSais" placeholder="ToiMemeTuSais">
</div><button type="submit" class="btn btn-block btn-primary"><i class="fa fa-check"></i> Se Connecter</button></form></div></div>';
bot();
exit;
?>