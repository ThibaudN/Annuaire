<?php
/*LastModif: 2012-01-28 23:00:00*/
$nTimeExc = microtime(true);
ini_set('session.use_cookies','1');
ini_set('session.use_only_cookies','1');
ini_set('url_rewriter.tags','');
ini_set('session.use_trans_sid','0');
ini_set('date.timezone', 'Europe/Paris');
ini_set('display_errors','1');
error_reporting(E_ALL);
#
include __DIR__."/php-include/f_base.php";#base
include __DIR__."/php-include/d_config.php";#base
include __DIR__."/php-include/c_pdo.php";#class pdo
include __DIR__."/php-include/c_email.php";#email
#
$oSql = mypdo::GetInstance(DB_HOST,DB_LOGIN,DB_PASS,DB_BASE);
$rR = "SELECT * FROM ".S_CONFIG." where id = 1";
$sRConfig = $oSql->GetLine($rR);
#
$sSujet = 'Paiement Paypal';
$sBodyHTML = '';
$sBody = '';
$aMail = array('FromMail' => MAILEXPEDITEUR,'FromName' => NOMDOMAINE,'Subject' => $sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sRConfig['MailDestinataire']);
#myMail($aMail);


#Paypal Procedure 1
$sRequete = 'cmd=_notify-validate';
foreach($_POST as $k => $v)
	$sRequete .= '&'.$k.'='.urlencode($v);
#Paypal Procedure 1
$cCon = curl_init('https://www.paypal.com/cgi-bin/webscr');
curl_setopt($cCon, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($cCon, CURLOPT_POST, 1);
curl_setopt($cCon, CURLOPT_RETURNTRANSFER,1);
curl_setopt($cCon, CURLOPT_POSTFIELDS, $sRequete);
curl_setopt($cCon, CURLOPT_SSL_VERIFYPEER, 1);
curl_setopt($cCon, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($cCon, CURLOPT_FORBID_REUSE, 1);
curl_setopt($cCon, CURLOPT_HTTPHEADER, array('Connection: Close'));
if(!($cRetour = curl_exec($cCon))) {
    curl_close($cCon);
    exit;
}
curl_close($cCon);
 

if(strcmp($cRetour, "VERIFIED") == 0) {
    $item_name = $_POST['item_name'];
    $item_number = $_POST['item_number'];
    $payment_status = $_POST['payment_status'];
    $payment_amount = $_POST['mc_gross'];
    $payment_currency = $_POST['mc_currency'];
    $txn_id = $_POST['txn_id'];
    $receiver_email = $_POST['receiver_email'];
    $payer_email = $_POST['payer_email'];
	
	$rR = "SELECT * FROM ".S_PAYPAL." where idTransaction = ?";
	$aArg = array($txn_id);
	$sRTransaction = $oSql->GetLine($rR,$aArg);
	
	$rR = "SELECT F.*,C.Titre as NomCat FROM ".S_FICHES." F JOIN ".S_CATEGORIES." C on C.id = F.idCategorie where F.KeyGen = ?";
	$aArg = array($_POST['custom']);
	$sR = $oSql->GetLine($rR,$aArg);
	
	$bVerif = true;
	if($payment_status != 'Completed') { $bVerif = false; }
	elseif($payment_amount != $sRConfig['PaypalPrix']) { $bVerif = false; }
	elseif($payment_currency != $sRConfig['PaypalMonnaie']) { $bVerif = false; }
	elseif($receiver_email != $sRConfig['PaypalMail']) { $bVerif = false; }
	elseif(!empty($sRTransaction)) { $bVerif = false; }
	elseif(empty($sR)) { $bVerif = false; }
	$nFiche = 0;
	if(!empty($sR))
		$nFiche = $sR['id'];
	$nEtat = 1;
	if(empty($bVerif))
		$nEtat = 0;
	$sData = print_r($_POST,true);
	$rR = "INSERT INTO ".S_PAYPAL." (idFiche,idTransaction,Data,Date,Etat) values (?,?,?,now(),?)";
	$aArg = array($nFiche,$txn_id,$sData,$nEtat);
	$oSql->Query($rR,$aArg);
	if(!empty($bVerif))
		Prenium($sR);
}
elseif(strcmp($res, "INVALID") == 0) {}

exit;
?>