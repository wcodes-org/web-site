<?php
	require_once 'Fragment/Item.php';
?>
<div id='nav-menu'>
	<div id='nav-menu_container'>
		<div class='sidebar-nav-li sidebar-sub'>
<?php
			$SIDEBAR_NAV_GROUP = 'sidebar-nav-group page-list';
			$MENU_MAX_ITEM_COUNT = -6;
			group_text($SIDEBAR_NAV_GROUP, $MENU_MAX_ITEM_COUNT, ['root', 'App'], ['about', 'About'], ['location', 'Location'], ['video', 'Video'], ['downloads', 'Downloads'], ['faq', 'FAQ'], ['in_pictures', 'In pictures'], ['pitch', 'Pitch'], ['use_cases', 'Use cases'], ['applications', 'Applications'], ['patents', 'Patents']);
			group_text($SIDEBAR_NAV_GROUP, $MENU_MAX_ITEM_COUNT, ['adoption', 'Adoption'], ['bug_report', 'Bug report'], ['changelog', 'Changelog'], ['comparison', 'Comparison'], ['data_set', 'Data set'], ['features', 'Features'], ['feedback', 'Feedback'], ['guidelines', 'Guidelines'], ['implementations', 'Implementations'], ['license', 'License'], ['logo', 'Logo'], ['presentation', 'Presentation'], ['roadmap', 'Roadmap'], ['source_code', 'Source code'], ['technology', 'Technology'], ['timeline', 'Timeline'], ['user_guide', 'User guide'], ['wordlist', 'WordList']);
			group_text($SIDEBAR_NAV_GROUP, $MENU_MAX_ITEM_COUNT, ['about_concept', 'About concept'], ['about_project', 'About project'], ['about_site', 'About site'], ['about_me', 'About me'], ['clientele', 'Clientele'], ['media', 'Media']);
?>
		</div>
	</div>
</div>
