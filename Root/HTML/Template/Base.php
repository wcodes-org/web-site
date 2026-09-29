<!DOCTYPE html>
<html xmlns='http://www.w3.org/1999/xhtml' lang='en' itemscope='' itemtype='http://schema.org/WebPage'>
<head>
	<meta http-equiv='X-UA-Compatible' content='IE=edge' >
	<meta http-equiv='Content-Type' content="text/html; charset=UTF-8" >
	<meta name='title' content="<?php echo $desc.' - '.$config['project_title'] ?>" >
	<meta name='author' content="<?php echo $config['author'] ?>" >
	<meta name='viewport' content="width=device-width, initial-scale=1.0, viewport-fit=cover" >
	<meta name='format-detection' content='telephone=no'>
	<?php require '../HTML/Fragment/Google_Plus_Meta.php' ?>
	<?php require '../HTML/Fragment/OG_Meta.php' ?>
	<?php require '../HTML/Fragment/FB_Meta.php' ?>
	<?php require '../HTML/Fragment/Twitter_Meta.php' ?>
	<link rel="shortcut icon" type='image/x-icon' href='/favicon.ico' >
	<link rel='apple-touch-icon' type='image/png' href='/apple-touch-icon.png' >
	<link rel='manifest' href='/manifest.json' >
	<link href="<?php echo $config['base_url']; if($id != 'root') echo '/'.$id ?>" rel='canonical' >
	<title><?php echo $desc.' - '.$config['project_title']; ?></title>
<?php if($bPublish) {
		if(!empty($config['google_tag_id']))
			require '../JS/Fragment/GA_HeadScript.php';
		require '../JS/Fragment/GA_track.js'; ?>
		<script <?php require '../JS/Fragment/Sentry_version.php' ?>></script>
		<script>
			if (window.Sentry && typeof window.Sentry.init === 'function') {
				try {
					<?php require '../JS/Fragment/Sentry_exec.php' ?>
				} catch (error) {
					console.warn('Sentry initialization failed', error);
				}
			}
		</script>
		<script src='//apis.google.com/js/platform.js' async defer></script>
		<script src='//platform.twitter.com/widgets.js' async></script>
<?php	}
	require '../JS/Fragment/JS.php';
	require '../JS/Fragment/GTranslate.php';
	require '../JS/Fragment/GCSE.php';
	require '../JS/Fragment/Project_title.php';
	require '../CSS/Fragment/CSS.php';
?>
</head>
<body>
	<?php	if($bPublish) { require '../JS/Fragment/BodyBegin_FB.php'; } ?>
	<div id='wait_loader' class='hide'></div>
	<div id='main-wrapper' class="<?php echo $menu_active_class; echo ($id == 'root'? ' '.'hide_path_title_updated' : ' ') ?>">
		<div id='content-wrapper'>
		<?php require '../../HTML/Fragment/Header.php'; ?>
		<div id='wrapper'>
			<div id='main'>
				<div class='container'>
					<div id='content-wrapper-inside'>
						<div class='shadow-scroll-top'></div>
						<div id='translation-controls' class='hide_display'>
							<div id='google_translate_element'></div>
						</div>
						<?php require '../HTML/Fragment/GCSE.php' ?>
						<div id='canvas-wrapper'>
							<div id='path-container' class="<?php echo ($id == 'root'? 'hide_scale' : '') ?>"><div id=path><?php require '../HTML/Fragment/Path.php' ?></div></div>
							<div id='title-container' class="<?php echo ($id == 'root'? 'hide_scale' : '') ?>"><h1 id='title'><?php echo ($id == 'root'? '&nbsp;' : $label) ?></h1></div>
							<div id='canvas-wrapper-inner-container'>
								<?php require '../../HTML/Fragment/Menu.php'; ?>
								<div id='canvas-main'>
									<div id='content'>
										<?php require (getComponentPath($id)) ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</div>
		<?php require '../../HTML/Fragment/Footer.php'; ?>
	</div>
</body>
</html>
