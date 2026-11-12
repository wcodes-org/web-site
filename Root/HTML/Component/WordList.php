<?php
	require_once(__DIR__ . '/../Fragment/Wordlist_rules.php');
	$lines = file('../../File/Wordlist.tsv', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
	$rankColumn = array_search('Rank', explode("\t", array_shift($lines)), true);
	$groups = array();
	$liveWordSet = array();
	$ranks = array();
	$primary = $secondary = '';
	foreach($lines as $line) {
		$row = explode("\t", $line);
		if($row[1] !== '') {
			$primary = $row[1];
			$secondary = '';
		}
		if($row[2] !== '')
			$secondary = ucfirst($row[2]);
		foreach(array_slice($row, 3, 3) as $word) {
			if($word === '')
				continue;
			$groups[$primary][$secondary][] = $word;
			$liveWordSet[$word] = true;
			if($rankColumn !== false && !empty($row[$rankColumn]))
				$ranks[$word] = (int) $row[$rankColumn];
		}
	}
	$emojiMap = json_decode(file_get_contents('../../File/Wordlist-emoji.json'), true);
	$greylistFile = wordlistDocPath('GREYLIST.md');
	$blacklistFile = wordlistDocPath('BLACKLIST.md');
	$greylist = array_intersect_key(wordlistListedReasons($greylistFile), $liveWordSet);
	$blacklistSet = wordlistListedReasons($blacklistFile);
	$totalCount = count($liveWordSet);
?>
<div id='message'>
	<div id='wordlist_search_wrap'>
		<div id='wordlist_search'>
			<label for='word_search_box' id='search-button_icon_wordlist' data-placeholder='search' aria-hidden='true'><?php includeSVG('', 'Search_wordList'); ?></label>
			<input id='word_search_box' type='text' placeholder='search' autocomplete='off' spellcheck='false' aria-label='Search the wordlist' aria-describedby='wordlist_search_hint'>
			<span class='wordlist-search-side hide' id='search_input_clear'><span class='image'><?php includeSVG('', 'Cross'); ?></span></span>
			<span id='wordlist_search_hint' hidden>space clears</span>
		</div>
		<div id='wordlist-message'></div>
	</div>
	<div id='wordlist_lists'>
		<div id='wordlist_tabs_wrap'>
			<div id='wordlist_tabs' role='group' aria-label='Wordlist'>
				<button id='wordlist_white_tab' type='button' aria-pressed='false' aria-controls='wordlist_white_panel' data-search-section='wordlist_search_white'>Whitelist</button>
				<span aria-hidden='true'>|</span>
				<button id='wordlist_grey_tab' type='button' aria-pressed='false' aria-controls='wordlist_grey_panel' data-search-section='wordlist_search_grey'>Greylist</button>
				<span aria-hidden='true'>|</span>
				<button id='wordlist_black_tab' type='button' aria-pressed='false' aria-controls='wordlist_black_panel' data-search-section='wordlist_search_black'>Blacklist</button>
			</div>
			<div id='wordlist_view_controls' role='group' aria-label='Wordlist display options' hidden>
				<button type='button' aria-pressed='true' aria-label='Group by category' title='Group by category' data-group-words>
					<svg viewBox='0 0 20 20' aria-hidden='true' focusable='false'><rect x='2.5' y='3' width='15' height='5' rx='1'/><rect x='2.5' y='12' width='15' height='5' rx='1'/></svg>
				</button>
				<button type='button' aria-pressed='false' aria-label='Sort A–Z' title='Sort A–Z' data-word-order='alphabet'>
					<svg viewBox='0 0 20 20' aria-hidden='true' focusable='false'><path d='m2.5 14 3.5-8 3.5 8m-6-2.5h5M15 4v12m-3-3 3 3 3-3'/></svg>
				</button>
				<button type='button' aria-pressed='false' aria-label='Sort by rank' title='Sort by rank' data-word-order='rank'>
					<svg viewBox='0 0 20 20' aria-hidden='true' focusable='false'><path d='M2.5 16.5h15M4 16.5V10h4v6.5M8 16.5V4h4v12.5M12 16.5V12h4v4.5'/></svg>
				</button>
			</div>
		</div>

		<div class='wordlist_tab_panels'>
			<div id='wordlist_white_panel'>
				<div class='indent-c'>
					<p>The 1,024 words currently used in WCode. Suggestions are welcome — the list is dedicated to the public domain.</p>
				</div>
				<div class='wordlist-table indent-c'>
					<div class='wordlist-live-grid'>
						<?php echo renderWordlistGrid($groups, $emojiMap, $greylist, $blacklistSet, $ranks); ?>
					</div>
				</div>
			<?php if($totalCount != 1024) { ?>
				<div id='words-remaining'>* <?php echo 1024-$totalCount ?> remaining</div>
			<?php } ?>
				<div class='top-bottom-gap'>
					<div id='wordlist-links'>
						<span id='wordlist-downloads'>
							<a class='content-link' href='https://wordlist.wcodes.org/Wordlist.tsv' target='_blank'>.tsv</a><a class='content-link' href='https://wordlist.wcodes.org/wordlist.json' target='_blank'>.json</a><a class='content-link' href='https://wordlist.wcodes.org/wordlist.txt' target='_blank'>.txt</a><a class='content-link' href='https://wordlist.wcodes.org/wordlist.js' target='_blank'>.js</a>
						</span>
						<span id='wordlist-release'>
							<span id='wordlist-version'>v 5</span>
							-
							<span id='wordlist-date'>Nov 12, 2026</span>
							<span aria-hidden='true'>|</span>
							<a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/VERSIONS.md' target='_blank'>versions</a>
						</span>
						<span id='wordlist-github-row'>
							<a id='wordlist-github' class='content-link' href='https://github.com/wcodes-org/wordlist'>
								<?php // GitHub mark from Primer Octicons, MIT: https://github.com/primer/octicons/blob/main/LICENSE ?>
								<svg aria-hidden="true" focusable="false" fill="currentColor" xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path d="M6.766 11.328c-2.063-.25-3.516-1.734-3.516-3.656 0-.781.281-1.625.75-2.188-.203-.515-.172-1.609.063-2.062.625-.078 1.468.25 1.968.703.594-.187 1.219-.281 1.985-.281.765 0 1.39.094 1.953.265.484-.437 1.344-.765 1.969-.687.218.422.25 1.515.046 2.047.5.593.766 1.39.766 2.203 0 1.922-1.453 3.375-3.547 3.64.531.344.89 1.094.89 1.954v1.625c0 .468.391.734.86.547C13.781 14.359 16 11.53 16 8.03 16 3.61 12.406 0 7.984 0 3.563 0 0 3.61 0 8.031a7.88 7.88 0 0 0 5.172 7.422c.422.156.828-.125.828-.547v-1.25c-.219.094-.5.156-.75.156-1.031 0-1.64-.562-2.078-1.609-.172-.422-.36-.672-.719-.719-.187-.015-.25-.093-.25-.187 0-.188.313-.328.625-.328.453 0 .844.281 1.25.86.313.452.64.655 1.031.655s.641-.14 1-.5c.266-.265.47-.5.657-.656"/></svg>
								Wordlist on GitHub
							</a>
						</span>
					</div>
				</div>
			</div>

			<div id='wordlist_grey_panel' hidden>
				<?php
					if($greylistFile)
						echo renderWordlistMarkdown($greylistFile, false, $emojiMap, $greylist, $blacklistSet, $liveWordSet, $ranks);
					else
						echo "<p class='indent-c'>Greylist: <a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/GREYLIST.md' target='_blank'>GREYLIST.md</a></p>";
				?>
			</div>

			<div id='wordlist_black_panel' hidden>
				<?php
					if($blacklistFile)
						echo renderWordlistMarkdown($blacklistFile, false, $emojiMap, $greylist, $blacklistSet);
					else
						echo "<p class='indent-c'>Blacklist: <a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/BLACKLIST.md' target='_blank'>BLACKLIST.md</a></p>";
					$blackTsvFile = wordlistBlackTsvPath();
					$blackTsvRows = wordlistReadBlackTsv($blackTsvFile);
					if($blackTsvRows)
						echo renderWordlistBlackTsvWords($blackTsvRows, $emojiMap);
				?>
			</div>
		</div>
		<div id='wordlist_search_results' hidden>
			<p id='wordlist_search_empty' class='indent-c' hidden>No matches.</p>
			<section id='wordlist_search_white' class='wordlist-search-section' hidden>
				<h2>Whitelist</h2>
				<div class='wordlist-search-body indent-c'></div>
			</section>
			<section id='wordlist_search_grey' class='wordlist-search-section' hidden>
				<h2>Greylist</h2>
				<div class='wordlist-search-body'></div>
			</section>
			<section id='wordlist_search_black' class='wordlist-search-section' hidden>
				<h2>Blacklist</h2>
				<div class='wordlist-search-body'></div>
			</section>
		</div>
	</div>
	<script type='application/json' id='wordlist-meta'><?php echo json_encode(array('greylist' => (object) $greylist, 'blacklist' => array_keys($blacklistSet), 'ranks' => $ranks), JSON_HEX_TAG); ?></script>

	<section class='indent-c' aria-labelledby='wordlist-uses'>
		<h2 id='wordlist-uses'>Wordlist uses</h2>
		<ul>
			<li><a class='content-link' href='https://wolo.codes/faq'>Wolo Code</a> uses the wordlist to encode locations as a city name and three words.</li>
			<li><a class='content-link' href='https://pkg.go.dev/github.com/wttw/words#readme'>words</a> is a Go package that uses the wordlist to encode integers as short sequences of words.</li>
			<li><a class='content-link' href='https://github.com/bandrews/verbal-id#whats-the-methodology-you-used-to-create-the-word-list'>verbal-id</a> adapts the WCode wordlist for spoken identifiers, filtering out similar-sounding words and adding replacements.</li>
		</ul>
	</section>

	<section class='indent-c' aria-labelledby='wordlist-references'>
		<h2 id='wordlist-references'>Wordlist references</h2>
		<ul>
			<li>UX <a class='content-link' href='https://ux.stackexchange.com/questions/21076/are-there-numbers-and-letters-to-avoid-for-activation-codes-via-sms#21084'>Stack Exchange</a> discussion mentions WCode as an option for generating memorable activation codes from words.</li>
		</ul>
	</section>

	<hr class='wordlist-docs-rule' aria-hidden='true'>
	<div id='wordlist_docs'>
		<?php
			$wordlistRulesFile = wordlistRulesPath();
			if($wordlistRulesFile)
				echo renderWordlistMarkdown($wordlistRulesFile, true, null);
			else
				echo "<p class='indent-c'>Rules: <a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/RULES.md' target='_blank'>RULES.md</a></p>";
		?>
	</div>
</div>
<div class='indent-c'>
	<div class='fb-comments' data-href='https://wcodes.org/wordlist' data-numposts='50' data-order_by='reverse_time'></div>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
