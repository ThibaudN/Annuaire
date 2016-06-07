<?php
if(!isset($nIncludeSidebar))
	exit;
if(!isset($nCat))
	$nCat = 0;
echo '<h2>'.s($sRConfig['SidebarCategorieH2']).'</h2><ul class="list-group">';
foreach($aCategories as $k => $v) {
	echo '<li class="list-group-item';
	if($nCat == $v['id'])
		echo ' list-group-item-info';
	echo '"><a href="/'.hs($v['TitreUrl']).'.html" title="'.s($v['Titre']).'">'.s($v['Titre']).'</a> <span class="badge">'.$v['Sites'].'</span></li>';
}
echo '</ul>';



$rR = "SELECT * FROM ".S_SIDEBAR." ORDER BY Ordre ASC";
$sRowLiens = $oSql->GetAll($rR);
if(!empty($sRowLiens)){
	echo '<h2>'.s($sRConfig['SidebarSitesH2']).'</h2><ul class="list-group">';
	foreach($sRowLiens as $k => $v) {
		echo '<li class="list-group-item"><a href="'.hs($v['Lien']).'" title="'.s($v['Titre']).'"';
		if(!empty($v['Nofollow']))
			echo ' rel="nofollow"';
		if(!empty($v['Externe']))
			echo ' onclick="window.open(this.href); return false;"';
	
		echo '>'.s($v['Titre']).'</a></li>';
	}
	echo '</ul>';
}

echo '<h2>'.s($sRConfig['SidebarNavigationH2']).'</h2><ul class="list-group"><li class="list-group-item"><a href="/" title="'.hs($sRConfig['NomSite']).'">'.hs($sRConfig['NomSite']).'</a></li><li class="list-group-item"><a href="/proposerunsite.php" title="Proposer un site" rel="nofollow">Proposer un site</a></li><li class="list-group-item"><a href="/contacts.php" title="Contacts" rel="nofollow">Contacts</a></li><li class="list-group-item"><a href="/mentionslegales.php" title="Mentions Légales" rel="nofollow">Mentions Légales</a></li></ul>';

?>