<div id='message'>
	<div>
		
		<div class='top-bottom-gap'>
			<?php $img_title='index'; $ext='png'; $alt="Wolo demo"; $center='true'; $max_height='true'; require('Fragment/Component_image.php') ?>
		</div>
		
		<p>
			WCode technology is used in <a href="https://wolo.codes" class='content-link'>Wolo</a><br>
			Wolo allows to point to any location within a given city with just three simple words
		</p>
		<p>
			Follow the link below to see more details:
		</p>
		<div class='center'>
			<?php group_image("page-list center", 1, ['app/location', 'wolo.codes', 'https://wolo.codes']) ?>
		</div>
		
	</div>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
