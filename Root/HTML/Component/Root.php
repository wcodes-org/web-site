<div id='message' class='alt_div_shade'>
	
	<div id='basis'>
		<span id='basis_image' class='image' title='WCode basis'><?php includeSVG('', 'Basis'); ?></span>
	</div>
	
	<div id='what'>
	<h2><span class='bullet'>&#8226;</span><span class='key'>What</span> <span class='minor'>is WCode?</span></h2>
	<div class='indent-f'>
		<span>A system to communicate digital information using only simple words.</span>
	</div>
	<div id='codes-example'>
		<div class='indent-f' id='codes-table-label'>Below, each represent the same data; The bottom one: words enclosed by slashes, is the equivalent WCode</div>
		<div id='codes-table'>
			<div id='codes-table-top'>one two three four five six</div>
			<div id='codes-table-mid'>
				<div><div class='code'><span id='bar-code' class='image' title='Bar Code : Rectangle with black bars'><?php includeSVG('', 'Bar_code'); ?></span></div></div>
				<div><div class=''>1 2 3 4 5 6</div></div>
				<div><div><span id='qr-code' class='image' title='QR Code : Square with black blocks'><?php includeSVG('', 'QR_code'); ?></span></div></div>
			</div>
			<div id='codes-table-bottom' class='code_value'>\ <span>cat apple mango tomato</span> /</div>
		</div>
	</div>
	<div class='indent-f'>
		Unlike barcodes, instead of geometrical figures like bars and blocks, WCode is made up of literal words.
	</div>
	</div>
	
	<div id='where'>
	<h2><span class='bullet'>&#8226;</span><span class='key'>Where</span> <span class='minor'>can it be used?</span></h2>
		<div class='indent-f'>
			WCode is to be used when a human needs to relay very short data from one computer to another <strong><span class='blue'>manually</span></strong>.
			<div>
				E.g. when exchanging email address, mobile number or GPS location by speech or writing
			</div>
		</div>
		<p class='center'>
			<img src='/resource/general-use_case.png' alt="WCode general use-case">
		</p>
	</div>
	
	<div id='why'>
	<h2><span class='bullet'>&#8226;</span><span class='key'>Why</span> <span class='minor'>should it be used?</span></h2>
	<div class='indent-f'>
		Comparatively, WCodes are:
		<ul class='list-dash'>
			<li>
				<details>
					<summary><em><strong>shorter</strong></em> – meaning lesser number of words. Since <span class='blue'>1</span> when spoken, is again a word: <span class='blue'>&lsquo;ONE&rsquo;</span></summary>
					<div class='indent-32'>
						As in the example above:
						<div id='comparison-table'>
							<div class='tbl-col'>
								<div class='cell'><em>Original</em></div>
								<div class='cell'><em>WCode</em></div>
							</div>
							<div class='tbl-col'>
								<div class='cell'><span> – </span><span class='t-size'>6</span><span class='t-type'> digits </span></div>
								<div class='cell'><span> – </span><span class='t-size'>4</span><span class='t-type'> nouns </span></div>
							</div>
							<div class='tbl-col indent-a'>
								<div class='blue'><span class='blue t-data'>1</span><span class='blue t-data'>2</span><span class='blue t-data'>3</span><span class='blue t-data'>4</span><span class='blue t-data'>5</span><span class='blue t-data'>6</span></div>
								<div class='blue'><span class='blue t-data'>Cat</span><span class='blue t-data'>Apple</span><span class='blue t-data'>Mango</span><span class='blue t-data'> Tomato </span></div>
							</div>
						</div>
					</div>
				</details>
			</li>
			<li>
				<details>
					<summary><em><strong>simpler</strong></em> – thus unambiguous and easier to communicate and memorize</summary>
					<div class='indent-32'>
						Digits and symbols like : <span class='blue'>1</span>, <span class='blue'>@</span>, <span class='blue'>_</span> are replaced with common words.<br>
						Even complex datasets like : <span class='blue'>27°59'17'N 86°55'31'E</span> get transformed to a bunch of simple words.<br>
					</div>
				</details>
			</li>
			<li>
				<details>
					<summary><em><strong>safer</strong></em> – ensures accuracy and privacy</summary>
					<div class='indent-32'>
						The chances of making a mistake are reduced.<br>
						Additionally checksum and encryption are supported.<br>
					</div>
				</details>
			</li>
		</ul>
	</div>
	</div>
	
	<div id='example'>
	<h2><span class='bullet'>&#8226;</span><span class='key'>Example</span> <span class='minor'>:</span></h2>
	<div class='indent-f'>
		<span>Sender:</span>
	</div>
	<div class='center'>
		<div class='example'>
			<span class="example_title">Data:</span><span class='example_data'>9812345670</span><span class='example_connector'>&#x25BA;</span><span class='example_wcode'>cat apple mango</span><span class="example_title">:Code</span>
		</div>
	</div>
	<div class='indent-f'>
		<span>Receiver:</span>
	</div>
	<div class='center'>
		<div class='example'>
			<span class="example_title">Code:</span><span class='example_wcode'>cat apple mango</span><span class='example_connector'>&#x25BA;</span><span class='example_data'>9812345670</span><span class="example_title">:Data</span>
		</div>
	</div>
	<div id='example_message' class='blue'>
		<span>&ndash; Voilà ! your mobile number is back.</span>
	</div>
	</div>
	
	<div id='apps'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>Apps</span></h2>
		<?php group_image("page-list center", 1, ['app/location', 'wolo.codes', 'https://wolo.codes']) ?>
	</div>
	
	<div id='more'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>More</span></h2>
		<div class='sidebar-nav-li sidebar-sub'>
<?php
			$SIDEBAR_NAV_GROUP = 'sidebar-nav-group page-list';
			$MENU_MAX_ITEM_COUNT = -5;
			group_text($SIDEBAR_NAV_GROUP, $MENU_MAX_ITEM_COUNT, ['location', 'Location'], ['video', 'Video'], ['downloads', 'Downloads'], ['faq', 'FAQ'], ['in_pictures', 'In pictures'], ['pitch', 'Pitch'], ['use_cases', 'Use cases'], ['applications', 'Applications'], ['about', 'About'], ['patents', 'Patents']);
			group_text($SIDEBAR_NAV_GROUP, $MENU_MAX_ITEM_COUNT, ['adoption', 'Adoption'], ['bug_report', 'Bug report'], ['changelog', 'Changelog'], ['comparison', 'Comparison'], ['data_set', 'Data set'], ['features', 'Features'], ['feedback', 'Feedback'], ['guidelines', 'Guidelines'], ['implementations', 'Implementations'], ['license', 'License'], ['logo', 'Logo'], ['presentation', 'Presentation'], ['roadmap', 'Roadmap'], ['source_code', 'Source code'], ['technology', 'Technology'], ['timeline', 'Timeline'], ['user_guide', 'User guide'], ['wordlist', 'WordList']);
			group_text($SIDEBAR_NAV_GROUP, $MENU_MAX_ITEM_COUNT, ['about_project', 'About project'], ['about_site', 'About site'], ['about_me', 'About me'], ['clientele', 'Clientele'], ['media', 'Media']);
?>
		</div>
	</div>
	
</div>
