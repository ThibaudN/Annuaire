<?php
if(!isset($nIncludeSidebar))
	exit;
if(!isset($nCat))
	$nCat = 0;
echo '<div class="content-box"><div class="content-box-head"><h2>'.s($sRConfig['SidebarCategorieH2']).'</h2><i class="fa fa-bars content-box-head-icon"></i></div><div class="categories content-box-body"><ul>';
foreach($aCategories as $k => $v) {
	echo '<li><a href="/'.hs($v['TitreUrl']).'.html" title="'.s($v['Titre']).'"><div class="rectangle-list-style';
	if($nCat == $v['id'])
		echo ' rectangle-list-style-active';
	echo '"></div> '.s($v['Titre']).' <span class="badge">'.$v['Sites'].'</span></a></li>';
}
echo '</ul></div></div>';


echo '<div class="search"><form action="search.php?action=1" method="post"><input type="text" class="search-input" placeholder="Rechercher" name="Rechercher"><button style="" type="submit"><div class="search-btn" style=""><i class="fa fa-search"></i></div></button></form></div>';

$rR = "SELECT * FROM ".S_SIDEBAR." ORDER BY Ordre ASC";
$sRowLiens = $oSql->GetAll($rR);
if(!empty($sRowLiens)){
	echo '<div class="content-box"><div class="content-box-head"><h2>'.s($sRConfig['SidebarSitesH2']).'</h2><i class="fa fa-star content-box-head-icon"></i></div><div class="categories content-box-body"><ul>';
	foreach($sRowLiens as $k => $v) {
		echo '<li><a href="'.hs($v['Lien']).'" title="'.s($v['Titre']).'"';
		if(!empty($v['Nofollow']))
			echo ' rel="nofollow"';
		if(!empty($v['Externe']))
			echo ' onclick="window.open(this.href); return false;"';
	
		echo '><div class="rectangle-list-style"></div> '.s($v['Titre']).'</a></li>';
	}
	echo '</ul></div></div>';
}


echo '<div class="content-box"><div class="content-box-head"><h2>'.s($sRConfig['SidebarNavigationH2']).'</h2><i class="fa fa-bars content-box-head-icon"></i></div><div class="categories content-box-body"><ul><li><a href="/" title="'.hs($sRConfig['NomSite']).'"><div class="rectangle-list-style"></div>'.hs($sRConfig['NomSite']).'</a></li><li><a href="/proposerunsite.php" title="Proposer un site" rel="nofollow"><div class="rectangle-list-style"></div>Proposer un site</a></li><li><a href="/contacts.php" title="Contacts" rel="nofollow"><div class="rectangle-list-style"></div>Contacts</a></li><li><a href="/mentionslegales.php" title="Mentions Légales" rel="nofollow"><div class="rectangle-list-style"></div>Mentions Légales</a></li></ul></div></div>';

?>