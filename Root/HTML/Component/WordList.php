<div id='message'>
	<h3>Word selection criteria:</h3>
	<ol class='indent-e'>
		<li>
			Nouns
			<div class='content-li-example'>
				e.g. 'Moon' against 'Run'
			</div>
		</li>
		<li>
			Preferabely: tangible, non-absract i.e. concrete &ndash; nouns.
			<div class='content-li-example'>
				e.g. 'Car' against 'Care'
			</div>
		</li>
		<li>
			Base, singular form of the word
			<div class='content-li-example'>
				e.g. 'Car' against 'Cars'
			</div>
		</li>
		<li>
			Popular
			<div class='content-li-example'>
				e.g. 'Car' against 'Tomahawk'
			</div>
		</li>
		<li>
			Simple pronounciation and spelling
			<div class='content-li-example'>
				e.g. 'Cat' against 'Penicillin'
			</div>
		</li>
		<li>
			Avoid words with spelling variantes<br>
			<div class='content-li-example'>
				e.g. 'Color' and 'Colour'
			</div>
		</li>
		<li>
			No compounded words
			<div class='content-li-example'>
				e.g. 'Volley-ball'
			</div>
		</li>
		<li>
			No immoral and objectionable words
			<div class='content-li-example'>
				e.g. 'Bomb' 'Dog' 'Pig'
			</div>
		</li>
		<li>
			Postive words over negative ones
			<div class='content-li-example'>
				e.g. 'Rise' instead of 'Fall'
			</div>
		</li>
		<li>
			No synonyms
			<div class='content-li-example'>
				e.g. 'Man' and 'Person'
			</div>
		</li>
		<li>
			Unique, distinct and no homophones
			<div class='content-li-example'>
				e.g. 'Apple' against 'Rose' and 'Rows'
			</div>
		</li>
	</ol>
	<br>
	<h3>Wordlist</h3>
	<div class='indent-c'>
		<p>Below is the list of 1K (1024) words currently being used in WCode<p>
		<p>You are welcome to suggest improvements to this wordlist down below, under the public domain<p>
	</div>
	<div class='center' id='wordlist_search'>
		<span id='search-button_icon_wordlist'><span class='image'><?php includeSVG('', 'Search_wordList'); ?></span></span>
		<input id='word_search_box' type='text' placeholder="&nbsp;&nbsp;search ( 'space' clears )">
		<span class='hide' id='search_input_clear'><span class='image'><?php includeSVG('', 'Cross'); ?></span></span>
	</div>
	<div id='wordlist-table' class='indent-c'>
		<div id='wordlist-message'></div>
		<div id='wordlist-table-white'>
		<?php
			$lines = file('../../File/Wordlist.tsv', FILE_IGNORE_NEW_LINES|FILE_SKIP_EMPTY_LINES);
			$arWord = array_map(function($v) {
				return (explode("\t", $v));
			}, $lines);
			$emojiMap = json_decode(file_get_contents('../../File/Wordlist-emoji.json'), true);
			function renderWordItem($word, $emojiMap) {
				$emoji = isset($emojiMap[$word]) ? $emojiMap[$word] : '💬';
				echo '<span class="word-item"><span class="word-emoji">' . $emoji . '</span><span class="word-text">' . $word . '</span></span>';
			}
			$bPrimaryCat = false;
			$bSecondaryCat = false;
			$bWordCat = false;
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
					} ?>
						<div class='primary'><div class='primary-word'><?php echo $arWord[$i][1] ?></div>
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
						renderWordItem($arWord[$i][3], $emojiMap);
				}
				if($arWord[$i][4] != NULL) {
						$totalCount++;
						renderWordItem($arWord[$i][4], $emojiMap);
				}
				if($arWord[$i][5] != NULL) {
						$totalCount++;
						renderWordItem($arWord[$i][5], $emojiMap);
				}
			}
		?>
			</div></div></div>
		</div>
		<div id='wordlist-table-separator' class='hide'></div>
		<div id='wordlist-table-black'></div>
	</div>
<?php if($totalCount != 1024) { ?>
	<div id='words-remaining'>* <?php echo 1024-$totalCount ?> remaining</div>
<?php } ?>
	<div class='top-bottom-gap'>
		<div id='wordlist-links'>
			<span id='wordlist-version'> v 3</span>
			-
			<span id='wordlist-date'> May 18, 2019
			<strong>
				<a class='content-link' href='/wordlist.tsv' target='_blank'>[ .tsv ]</a>
			</strong>
		</div>
	</div>
</div>
<div class='indent-c'>
	<div class='fb-comments' data-href='https://wcodes.org/wordlist' data-numposts='50' data-order_by='reverse_time'></div>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
