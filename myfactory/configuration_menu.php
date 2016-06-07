<?php
if(!empty($nIncludeMenu)) {
	$sActive1 = '';
	$sActive2 = '';
	$sActive3 = '';
	$sActive4 = '';
	${'sActive'.$nIncludeMenu} = ' active';
	
	echo '<h2>Menu Config</h2><div class="list-group"><a href="configuration.php" title="Configuration" class="list-group-item'.$sActive1.'">Configuration</a><a href="configuration_antirobots.php" title="Anti Robots" class="list-group-item'.$sActive2.'">Anti Robots</a><a href="configuration_categorie.php" title="Catégories" class="list-group-item'.$sActive3.'">Catégories</a><a href="configuration_sidebar.php" title="Sidebar" class="list-group-item'.$sActive4.'">Sidebar</a></div>';
}
?>