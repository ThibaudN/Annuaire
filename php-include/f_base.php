<?php
$nWeek = date('W',$_SERVER["REQUEST_TIME"]);
$nDay = date('d',$_SERVER["REQUEST_TIME"]);
$nMonth = date('m',$_SERVER["REQUEST_TIME"]);
$nYear = date('Y',$_SERVER["REQUEST_TIME"]);
$nHour = date('H',$_SERVER["REQUEST_TIME"]);
$nMinute = date('i',$_SERVER["REQUEST_TIME"]);
$nSecond = date('s',$_SERVER["REQUEST_TIME"]);
$sNow = $nYear.'-'.$nMonth.'-'.$nDay.' '.$nHour.':'.$nMinute.':'.$nSecond;
$sAnnSem = substr($nYear,2,2);
if(strlen($nWeek) == 1)
	$sAnnSem .= '0'.$nWeek;
else
	$sAnnSem .= $nWeek;
$sAnnMois = substr($nYear,2,2);
if(strlen($nMonth) == 1)
	$sAnnMois .= '0'.$nMonth;
else
	$sAnnMois .= $nMonth;

$sAnnSemH = $sAnnSem.$nHour;
$sAnnMoisH = $sAnnMois.$nHour;
$sJourDeLaSemaine = date('l',$_SERVER["REQUEST_TIME"]);

function hs($sChamps) {
 return stripslashes(htmlspecialchars($sChamps));
}

function s($sChamps) {
 return stripslashes($sChamps);
}

function xPre($aP) {
	echo "<pre>";
	print_r($aP);
	echo "</pre>";
}

class DateTimeFrench extends DateTime {
    public function format($format) {
        $english_days = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');
        $french_days = array('Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche');
        $english_months = array('January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December');
        $french_months = array('Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre');
        return str_replace($english_months, $french_months, str_replace($english_days, $french_days, parent::format($format)));
    }
}

function GetIp() {
  if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
    $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
  elseif(isset($_SERVER['HTTP_CLIENT_IP']))
    $ip = $_SERVER['HTTP_CLIENT_IP'];
  else
    $ip = $_SERVER['REMOTE_ADDR'];
  return $ip;
}

function GetHost($ip) {
	$sHost = gethostbyaddr($ip);
	if($sHost === false) {
		error_log('ERREUR SUR GETHOSTBYADDR :: '.$ip);
		return $ip;
	}
	return $sHost;
}

function KeyGen($nLimit = '10') {
	$key_g = "";
	$letter = "abcdefghijklmnpqrstuvwxyz";
	$letter .= "123456789";
	srand((double)microtime()*date("YmdGis")); 
	for($cnt = 0; $cnt < $nLimit; $cnt++) { 
		$key_g .= $letter[rand(0, 33)]; 
	}
	return $key_g; 
}

function clean_url($sWord) {
	$sWord = stripslashes($sWord);
	$sWord = str_replace("à","a",$sWord);
	$sWord = str_replace("â","a",$sWord);
	$sWord = str_replace("ä","a",$sWord);
	$sWord = str_replace("ç","c",$sWord);
	$sWord = str_replace("è","e",$sWord);
	$sWord = str_replace("é","e",$sWord);
	$sWord = str_replace("ê","e",$sWord);
	$sWord = str_replace("ë","e",$sWord);
	$sWord = str_replace("î","i",$sWord);
	$sWord = str_replace("ï","i",$sWord);
	$sWord = str_replace("ô","o",$sWord);
	$sWord = str_replace("ö","o",$sWord);
	$sWord = str_replace("ù","u",$sWord);
	$sWord = str_replace("û","u",$sWord);
	$sWord = str_replace("ü","u",$sWord);
	$sWord = str_replace("À","a",$sWord);
	$sWord = str_replace("Â","a",$sWord);
	$sWord = str_replace("Ä","a",$sWord);
	$sWord = str_replace("Ç","c",$sWord);
	$sWord = str_replace("È","e",$sWord);
	$sWord = str_replace("É","e",$sWord);
	$sWord = str_replace("Ê","e",$sWord);
	$sWord = str_replace("Ë","e",$sWord);
	$sWord = str_replace("Î","i",$sWord);
	$sWord = str_replace("Ï","i",$sWord);
	$sWord = str_replace("Ô","o",$sWord);
	$sWord = str_replace("Ö","o",$sWord);
	$sWord = str_replace("Ù","u",$sWord);
	$sWord = str_replace("Û","u",$sWord);
	$sWord = str_replace("Ü","u",$sWord);
	$sWord = str_replace(" ",'-',$sWord);
	$sWord = str_replace(".",'-',$sWord);
	$sWord = strtolower($sWord);
	$sWord = preg_replace('#([^a-z0-9-])#','',$sWord);
	$sWord = preg_replace('#([-]+)#','-',$sWord);
	$sWord = trim($sWord,'-');
	return $sWord;
}

function verifUrl($sUrl) {
	if(substr($sUrl,0,7) != 'http://' and substr($sUrl,0,8) != 'https://')
		return false;
	$aExplode = explode('.',$sUrl);
	$nCount = count($aExplode);
	if($nCount < 2 or $nCount > 3)
		return false;
	$nKeyLast = 1;
	if($nCount == 3)
		$nKeyLast = 2;
	$aExtensions = array('fr' => 1,'com' => 1,'net' => 1,'org' => 1,'co' => 1,'xyz' => 1,'eu' => 1,'biz' => 1,'info' => 1,'ovh' => 1,'immo' => 1,'paris' => 1,'bzh' => 1,'alsace' => 1,'me' => 1,'link' => 1,'tel' => 1,'pro' => 1,'tv' => 1,'mobi' => 1,'name' => 1,'jobs' => 1,'travel' => 1);
	if(!isset($aExtensions[$aExplode[$nKeyLast]]))
		return false;
	$headers = @get_headers($sUrl);
	if($headers === false)
		return false;
	elseif(substr($headers[0], 9, 3) != 200)
		return false;
	return true;
}

function verifmail($m) {
	$m = strtolower($m);
	$sMot = "#^[a-zA-Z0-9_.+-]+@[a-zA-Z0-9-]+[.]([[:alpha:]]{2,4})$#";
	$blocked_hosts = array('0815.ru0clickemail.com','0wnd.net','0wnd.org','10minutemail.com','20minutemail.com','2prong.com','3d-painting.com','4warding.com','4warding.net','4warding.org','9ox.net','a-bc.net','amilegit.com','anonbox.net','anonymbox.com','antichef.com','antichef.net','antispam.de','baxomale.ht.cx','beefmilk.com','binkmail.com','bio-muesli.net','bobmail.info','bodhi.lawlita.com','bofthew.com','brefmail.com','bsnow.net','bugmenot.com','bumpymail.com','casualdx.com','chogmail.com','cool.fr.nf','correo.blogos.net','cosmorph.com','courriel.fr.nf','courrieltemporaire.com','curryworld.de','cust.in','dacoolest.com','dandikmail.com','deadaddress.com','despam.it','devnullmail.com','dfgh.net','digitalsanctuary.com','discardmail.com','discardmail.de','disposableaddress.com','disposemail.com','dispostable.com','dm.w3internet.co.uk','example.com','dodgeit.com','dodgit.com','dodgit.org','dontreg.com','dontsendmespam.de','dump-email.info','dumpyemail.com','e4ward.com','email60.com','emailias.com','emailinfive.com','emailmiser.com','emailtemporario.com.br','emailwarden.com','ephemail.net','explodemail.com','fakeinbox.com','fakeinformation.com','fastacura.com','filzmail.com','fizmail.com','frapmail.com','fuckmail.me','garliclife.com','get1mail.com','getonemail.com','getonemail.net','girlsundertheinfluence.com','gishpuppy.com','great-host.in','gsrv.co.uk','guerillamail.biz','guerillamail.com','guerillamail.net','guerillamail.org','guerrillamail.com','guerrillamailblock.com','haltospam.com','hotpop.com','ieatspam.eu','ieatspam.info','ihateyoualot.info','imails.info','inboxclean.com','inboxclean.org','incognitomail.com','incognitomail.net','ipoo.org','irish2me.com','jetable.com','jetable.fr.nf','jetable.net','jetable.org','jnxjn.com','junk1e.com','kaspop.com','klzlk.com','kulturbetrieb.info','kurzepost.de','lifebyfood.com','link2mail.net','litedrop.com','lookugly.com','lopl.co.cc','lr78.com','maboard.com','mail.by','mail.mezimages.net','mail4trash.com','mailbidon.com','mailcatch.com','maileater.com','mailexpire.com','mailin8r.com','mailinator.com','mailinator.net','mailinator2.com','mailincubator.com','mailme.lv','mailnator.com','mailnull.com','mail-temporaire.fr','mailzilla.org','mbx.cc','mega.zik.dj','meltmail.com','mierdamail.com','mintemail.com','moncourrier.fr.nf','monemail.fr.nf','monmail.fr.nf','mt2009.com','mx0.wwwnew.eu','mycleaninbox.net','mytrashmail.com','neverbox.com','nobulk.com','noclickemail.com','nogmailspam.info','nomail.xl.cx','nomail2me.com','no-spam.ws','nospam.ze.tc','nospam4.us','nospamfor.us','nowmymail.com','objectmail.com','obobbo.com','onewaymail.com','ordinaryamerican.net','owlpic.com','pookmail.com','proxymail.eu','punkass.com','putthisinyourspamdatabase.com','quickinbox.com','rcpt.at','recode.me','recursor.net','regbypass.comsafe-mail.net','safetymail.info','sandelf.de','saynotospams.com','selfdestructingmail.com','sendspamhere.com','shiftmail.com','skeefmail.com','slopsbox.com','smellfear.com','snakemail.com','sneakemail.com','sofort-mail.de','sogetthis.com','soodonims.com','spam.la','spamavert.com','spambob.net','spambob.org','spambog.com','spambog.de','spambog.ru','spambox.info','spambox.us','spamcannon.com','spamcannon.net','spamcero.com','spamcorptastic.com','spamcowboy.com','spamcowboy.net','spamcowboy.org','spamday.com','spamex.com','spamfree24.com','spamfree24.de','spamfree24.eu','spamfree24.info','spamfree24.net','spamfree24.org','spamgourmet.com','spamgourmet.net','spamgourmet.org','spamherelots.com','spamhereplease.com','spamhole.com','spamify.com','spaminator.de','spamkill.info','spaml.com','spaml.de','spammotel.com','spamobox.com','spamspot.com','spamthis.co.uk','spamthisplease.com','speed.1s.fr','suremail.info','tempalias.com','tempemail.biz','tempemail.com','tempe-mail.com','tempemail.net','tempinbox.co.uk','tempinbox.com','tempomail.fr','temporaryemail.net','temporaryinbox.com','thankyou2010.com','thisisnotmyrealemail.com','throwawayemailaddress.com','tilien.com','tmailinator.com','tradermail.info','trash2009.com','trash-amil.com','trashmail.at','trashmailer.com','trash-mail.at','trashmail.com','trash-mail.com','trash-mail.de','trashmail.me','trashmail.net','trashymail.com','trashymail.net','tyldd.com','uggsrock.com','wegwerfmail.de','wegwerfmail.net','wegwerfmail.org','wh4f.org','whyspam.me','willselfdestruct.com','winemaven.info','wronghead.com','wuzupmail.net','xoxy.net','yogamaven.com','yopmail.com','yopmail.fr','yopmail.net','yuurok.com','zippymail.info');
	if(preg_match($sMot,$m)) {
		$aExplode = explode('@',$m);
		if(in_array($aExplode[1], $blocked_hosts)) {
			return false;
		}
		return true;
	}
	else
		return false;
}

function MailInscription($sCode,$sMail,$nFromAdmin = 0) {
	$sSujet = 'Valider votre adresse email';
	$sBodyHTML = file_get_contents('php-include/emails/inscription_simplifiee.php',FILE_USE_INCLUDE_PATH);
	if(!empty($nFromAdmin))
		$sBodyHTML = file_get_contents('../php-include/emails/inscription_simplifiee.php',FILE_USE_INCLUDE_PATH);
	$sBodyHTML = str_replace("::DOMAINE",NOMDOMAINE,$sBodyHTML);
	$sBodyHTML = str_replace("::OBJET",$sSujet,$sBodyHTML);
	$sBodyHTML = str_replace("::CODE",strtoupper($sCode),$sBodyHTML);
	$sBody = "Bonjour !\n\nVous venez de vous inscrire à ".NOMDOMAINE.".\nAfin de pouvoir continuer, vous devez valider votre adresse e-mail en entrant le code à 5 lettres ci-dessous sur le site\n\n".strtoupper($sCode)."\n\nUne fois votre adresse e-mail validée, vous pourrez continuer votre inscription.";
	$aMail = array('FromMail' => MAILEXPEDITEUR,'FromName' => NOMDOMAINE,'Subject' => $sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sMail);
	myMail($aMail);
}

function Prenium($sR) {
	global $oSql,$sRConfig;
	#update table login
	$rR = "UPDATE ".S_FICHES." SET DatePaiement = now(),Etat = 3 where id = ?";
	$aArg = array($sR['id']);
	$oSql->Query($rR,$aArg);
	
	#envoie email admin
	$sSujet = 'Nouveau site';
	$sBodyHTML = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"><html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en"><head><meta http-equiv="Content-Type" content="text/html;charset=UTF-8"><title>'.NOMDOMAINE.' - '.$sSujet.'</title><style>.button{display:block; margin-top:30px; color:#fff; font-size:14px; line-height:45px; background:#ed1c24; text-transform:uppercase; text-align:center; text-decoration:none;}.button:hover{background-color:#333333;}#footer a{color:#000; text-decoration:underline;}</style></head><body style="padding:0; margin:0; color:#000; font-size:14px; line-height:22px; font-family:arial, sans-serif"><table width="600" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" style="border:1px solid #ccc; border-top:none;"><tr><td style="padding:60px 100px"><h1 style="margin:0 0 30px; padding:0; font-size:18px">'.$sSujet.'</h1><p>Ip : '.hs($sR['Ip']).'<br />Catégorie :: '.hs($sR['NomCat']).'<br />Url : '.hs($sR['Url']).'<br />Titre : '.hs($sR['Titre']).'<br />TitreUrl : '.hs($sR['TitreUrl']).'<br />Mail : '.hs($sR['Mail']).'<br />Description1 : '.nl2br(hs($sR['Description1'])).'<br />Description2 : '.nl2br(hs($sR['Description2'])).'<br /></p></td></tr></table></body></html>';
	$sBody = "Ip : ".hs($sR['Ip'])."\nCatégorie : ".hs($sR['NomCat'])."\nUrl : ".hs($sR['Url'])."\nTitre : ".hs($sR['Titre'])."\nTitreUrl : ".hs($sR['TitreUrl'])."\nMail : ".hs($sR['Mail'])."\nDescription1 : ".nl2br(hs($sR['Description1']))."\nDescription2 : ".nl2br(hs($sR['Description2']))."\n";
	$aMail = array('FromMail' => MAILEXPEDITEUR,'FromName' => NOMDOMAINE,'Subject' => $sSujet,'Text' => $sBody,'TextHTML' => $sBodyHTML,'Email' => $sRConfig['MailDestinataire']);
	if(!empty($sRConfig['MailLorsPaiement']))
		myMail($aMail);
}

function CalculSiteCategorie() {
	global $oSql;
	$rR = "SELECT * FROM ".S_CATEGORIES." order by id asc";
	foreach($oSql->GetAll($rR) as $k => $v) {
		$rR = "SELECT count(id) as nbr FROM ".S_FICHES." WHERE Etat = 1 and idCategorie = ?";
		$aArg = array($v['id']);
		$sRCount = $oSql->GetLine($rR,$aArg);
		$rR = "UPDATE ".S_CATEGORIES." set Sites = ? where id = ?";
		$aArg = array($sRCount['nbr'],$v['id']);
		$oSql->Query($rR,$aArg);
		echo $rR.' :: '.$sRCount['nbr'].' :: '.$v['id'].'<br />';
	}
}

?>