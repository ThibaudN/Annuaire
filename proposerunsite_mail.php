<?php
$sPath = pathinfo($_SERVER['PHP_SELF']); 
define('PAGE', $sPath['basename']);
require __DIR__."/fonctions.php";

if(!isset($_SESSION['Connected']['idUnique'])) {
	header('location: /404.php');
	exit;
}
$rR = "SELECT * FROM ".S_FICHES." where KeyGen = ? and Etat = 5";
$aArg = array($_SESSION['Connected']['idUnique']);
$sR = $oSql->GetLine($rR,$aArg);
if(empty($sR)) {
	header('location: /404.php');
	exit;
}
elseif(!empty($_GET['actionEmail']) and is_numeric($_GET['actionEmail'])) {
	if(empty($_POST['Mail'])) {
		$_SESSION['Connected']['Erreur'] = 'L\'adresse email est vide...';
		header('location: '.PAGE);
		exit;
	}
	elseif(verifmail($_POST['Mail']) == false) {
		$_SESSION['Connected']['Erreur'] = 'L\'adresse email est fausse...';
		header('location: '.PAGE);
		exit;
	}
	$rR = "UPDATE ".S_FICHES." SET Mail = ? where id = ?";
	$aArg = array($_POST['Mail'],$sR['id']);
	$oSql->Query($rR,$aArg);
	MailInscription($sR['KeyGenMail'],$_POST['Mail']);
	$_SESSION['Connected']['Done'] = 'L\'adresse email a été changé.<br />Un nouvel email contenant le code vous a été envoyé.';
	header('location: '.PAGE);
	exit;
}
elseif(!empty($_GET['actionCode']) and is_numeric($_GET['actionCode'])) {
	if(empty($_POST['Code'])) {
		$_SESSION['Connected']['Erreur'] = 'Le code est vide...';
		header('location: '.PAGE);
		exit;
	}
	elseif(strlen($_POST['Code']) != 5) {
		$_SESSION['Connected']['Erreur'] = 'Le code est faux.';
		header('location: '.PAGE);
		exit;
	}
	elseif(strtolower($_POST['Code']) != strtolower($sR['KeyGenMail'])) {
		$_SESSION['Connected']['Erreur'] = 'Le code est faux.';
		header('location: '.PAGE);
		exit;
	}
	$rR = "UPDATE ".S_FICHES." SET Etat = 4,DateMail = now() where id = ?";
	$aArg = array($sR['id']);
	$oSql->Query($rR,$aArg);
	
	
	header('location: proposerunsite_paiement.php');
	exit;
}


$aHead = array('PageTitre' => 'Proposer un site','MetaRobots' => 'noindex','MetaKeyword' => '','MetaDescription' => '');
head($aHead);

echo '<div class="row"><div class="col-lg-9 col-md-9 col-sm-12 col-xs-12"><h2>Validation de votre adresse email</h2>';
if(!empty($_SESSION['Connected']['Done'])) {
	echo '<div class="alert alert-success"><p>'.$_SESSION['Connected']['Done'].'</p></div>';
	unset($_SESSION['Connected']['Done']);
}
elseif(!empty($_SESSION['Connected']['Erreur'])) {
	echo '<div class="alert alert-danger"><p>'.$_SESSION['Connected']['Erreur'].'</p></div>';
	unset($_SESSION['Connected']['Erreur']);
}
echo '<div class="alert alert-warning"><p>Un email vous a été envoyé ('.$sR['Mail'].') qui contient un code à 5 lettres.</p></div><form method="post" action="'.PAGE.'?actionCode=1"><div class="form-group"><label>Entrez le code reçu</label><input type="text" name="Code" class="form-control" required="required" placeholder="Entrez le code à 5 lettres envoyé par email"></div><button type="submit" class="btn btn-primary btn-block">Valider</button></form><p class="text-center"><br />L\'email peut mettre jusqu\'à 5 minutes pour arriver et se trouvera <strong>certainement</strong> dans vos courriers indésirables / spams. Si l\'adresse n\'est pas la bonne, vous pouvez la changer dans le formulaire ci-contre.</p><hr /><h2>Changer mon adresse email</h2><div class="alert alert-warning"><p>Si votre adresse email est fausse vous pouvez la changer via le formulaire ci-dessous</p></div><form method="post" action="'.PAGE.'?actionEmail=1"><div class="form-group"><label>Adresse Email</label><input type="email" name="Mail" class="form-control" required="required" placeholder="" value="'.$sR['Mail'].'"></div><button type="submit" class="btn btn-primary btn-block">Changer mon adresse email</button></form>';

echo '</div><div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">';
#sidebar
$nIncludeSidebar = 1;
include __DIR__.'/sidebar.php';
echo '</div></div>';
bot();
?>