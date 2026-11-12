<div id='message'>
	<div id='wordlist_search_wrap'>
		<div class='center' id='wordlist_search'>
			<span id='search-button_icon_wordlist'><span class='image'><?php includeSVG('', 'Search_wordList'); ?></span></span>
			<input id='word_search_box' type='text' placeholder="&nbsp;&nbsp;search ( 'space' clears )" autocomplete='off' spellcheck='false'>
			<span class='hide' id='search_input_clear'><span class='image'><?php includeSVG('', 'Cross'); ?></span></span>
		</div>
		<div id='wordlist-message'></div>
	</div>
	<div id='wordlist_tabs_wrap'>
		<div id='wordlist_tabs' role='tablist' aria-label='Wordlist'>
			<button id='wordlist_white_tab' type='button' role='tab' aria-selected='true' aria-controls='wordlist_white_panel' data-tab-label='Whitelist' data-search-section='wordlist_search_white'>Whitelist</button>
			<span aria-hidden='true'>|</span>
			<button id='wordlist_grey_tab' type='button' role='tab' aria-selected='false' aria-controls='wordlist_grey_panel' tabindex='-1' data-tab-label='Greylist' data-search-section='wordlist_search_grey'>Greylist</button>
			<span aria-hidden='true'>|</span>
			<button id='wordlist_black_tab' type='button' role='tab' aria-selected='false' aria-controls='wordlist_black_panel' tabindex='-1' data-tab-label='Blacklist' data-search-section='wordlist_search_black'>Blacklist</button>
		</div>
	</div>

	<div class='wordlist_tab_panels'>
		<div id='wordlist_white_panel' role='tabpanel' aria-labelledby='wordlist_white_tab' tabindex='0'>
			<div class='indent-c'>
				<p>The 1,024 words currently used in WCode. Suggestions are welcome — the list is dedicated to the public domain.</p>
			</div>
			<div id='wordlist-table' class='indent-c'>
				<div id='wordlist-table-white'>
				<?php
					require_once(__DIR__ . '/../Fragment/Wordlist_rules.php');
					$lines = file('../../File/Wordlist.tsv', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
					$arWord = array_map(function($v) {
						return (explode("\t", $v));
					}, $lines);
					$emojiMap = json_decode(file_get_contents('../../File/Wordlist-emoji.json'), true);
					$greylistFile = wordlistDocPath('GREYLIST.md');
					$blacklistFile = wordlistDocPath('BLACKLIST.md');
					$greylistSet = array_fill_keys(wordlistListedWords($greylistFile), true);
					$blacklistSet = array_fill_keys(wordlistListedWords($blacklistFile), true);
					function renderWordItem($word, $emojiMap, $greylistSet, $blacklistSet) {
						$class = wordlistWordClass($word, $greylistSet, $blacklistSet);
						$html = '<span class="' . $class . '">';
						if(isset($emojiMap[$word]))
							$html .= '<span class="word-emoji">' . $emojiMap[$word] . '</span>';
						$html .= '<span class="word-text">' . $word . '</span></span>';
						echo $html;
					}
					$bPrimaryCat = false;
					$bSecondaryCat = false;
					$totalCount = 0;
					for($i = 1; $i < sizeOf($arWord); $i++) {
						echo $arWord[$i][0];
						if($arWord[$i][1] != NULL) {
							if($bPrimaryCat) { ?>
								</div></div>
							<?php }
							else
								$bPrimaryCat = true;
							if($bSecondaryCat) { ?>
								</div>
								<?php $bSecondaryCat = false;
							}
							$primaryName = $arWord[$i][1];
							$primaryClass = (strcasecmp(trim($primaryName), 'Greylist') === 0) ? "primary greylist" : 'primary';
							?>
								<div class='<?php echo $primaryClass ?>'><div class='primary-word'><?php echo $primaryName ?></div>
						<?php }
						if($arWord[$i][2] != NULL) {
							if($bSecondaryCat) { ?>
							</div></div>
							<?php
							} else
								$bSecondaryCat = true;
							?>
							<div class='secondary'><div class='secondary-word'><?php echo $arWord[$i][2] ?></div><div>
						<?php }
						if($arWord[$i][3] != NULL) {
								$totalCount++;
								renderWordItem($arWord[$i][3], $emojiMap, $greylistSet, $blacklistSet);
						}
						if($arWord[$i][4] != NULL) {
								$totalCount++;
								renderWordItem($arWord[$i][4], $emojiMap, $greylistSet, $blacklistSet);
						}
						if($arWord[$i][5] != NULL) {
								$totalCount++;
								renderWordItem($arWord[$i][5], $emojiMap, $greylistSet, $blacklistSet);
						}
					}
				?>
					</div></div></div>
				</div>
			</div>
		<?php if($totalCount != 1024) { ?>
			<div id='words-remaining'>* <?php echo 1024-$totalCount ?> remaining</div>
		<?php } ?>
			<div class='top-bottom-gap'>
				<div id='wordlist-links'>
					<span id='wordlist-version'> v 4</span>
					-
					<span id='wordlist-date'> Nov 9, 2026
					<strong>
						<a class='content-link' href='https://wordlist.wcodes.org/Wordlist.tsv' target='_blank'>[ .tsv ]</a>
						<a class='content-link' href='https://wordlist.wcodes.org/wordlist.json' target='_blank'>[ .json ]</a>
						<a class='content-link' href='https://wordlist.wcodes.org/wordlist.txt' target='_blank'>[ .txt ]</a>
						<a class='content-link' href='https://wordlist.wcodes.org/wordlist.js' target='_blank'>[ .js ]</a>
						<a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/VERSIONS.md' target='_blank'>[ versions ]</a>
					</strong>
				</div>
			</div>
		</div>

		<div id='wordlist_grey_panel' role='tabpanel' aria-labelledby='wordlist_grey_tab' tabindex='0' hidden>
			<?php
				if($greylistFile)
					echo renderWordlistMarkdown($greylistFile, false, $emojiMap, $greylistSet, $blacklistSet);
				else
					echo "<p class='indent-c'>Greylist: <a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/GREYLIST.md' target='_blank'>GREYLIST.md</a></p>";
			?>
		</div>

		<div id='wordlist_black_panel' role='tabpanel' aria-labelledby='wordlist_black_tab' tabindex='0' hidden>
			<?php
				if($blacklistFile)
					echo renderWordlistMarkdown($blacklistFile, false, $emojiMap, $greylistSet, $blacklistSet);
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
	<script type='application/json' id='wordlist-meta'><?php echo json_encode(array('greylist' => array_keys($greylistSet), 'blacklist' => array_keys($blacklistSet))); ?></script>

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
