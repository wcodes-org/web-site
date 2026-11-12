<?php
/*
 * Renders canonical wordlist docs (RULES.md, GREYLIST.md, BLACKLIST.md from wcodes-org/wordlist).
 * Source order:
 *   1. WCODE_WORDLIST_RULES / WCODE_WORDLIST_GREYLIST / WCODE_WORDLIST_BLACKLIST env vars (full path)
 *   2. Sibling checkout of the wordlist repo (D:\WCode\Wordlist\Project\ locally)
 *   3. Committed copy Root/File/Wordlist-rules.md, Wordlist-greylist.md or Wordlist-blacklist.md
 *      (refresh with File/Sync-rules.ps1)
 * Only the small Markdown subset these docs use is supported:
 * ### / #### headings, "1." ordered items, "-" bullets, follow-on lines (examples),
 * paragraphs, `code`, **bold** and [links](url).
 */
function wordlistDocPath($name) {
	$committed = array(
		'RULES.md' => 'Wordlist-rules.md',
		'GREYLIST.md' => 'Wordlist-greylist.md',
		'BLACKLIST.md' => 'Wordlist-blacklist.md',
		'CANDIDATES.md' => 'Wordlist-greylist.md',
		'Wordlist-black.tsv' => 'Wordlist-black.tsv'
	);
	$envKeys = array(
		'RULES.md' => 'WCODE_WORDLIST_RULES',
		'GREYLIST.md' => 'WCODE_WORDLIST_GREYLIST',
		'BLACKLIST.md' => 'WCODE_WORDLIST_BLACKLIST',
		'CANDIDATES.md' => 'WCODE_WORDLIST_GREYLIST',
		'Wordlist-black.tsv' => 'WCODE_WORDLIST_BLACK_TSV'
	);
	$envKey = isset($envKeys[$name]) ? $envKeys[$name] : 'WCODE_WORDLIST_RULES';
	$committedName = isset($committed[$name]) ? $committed[$name] : $name;
	$candidates = array(
		getenv($envKey),
		__DIR__ . '/../../../../../../../Wordlist/Project/' . $name,
		__DIR__ . '/../../File/' . $committedName
	);
	foreach($candidates as $path)
		if($path && is_file($path))
			return $path;
	return null;
}

function wordlistRulesPath() {
	return wordlistDocPath('RULES.md');
}

function wordlistBlackTsvPath() {
	return wordlistDocPath('Wordlist-black.tsv');
}

function wordlistReadBlackTsv($path) {
	$rows = array();
	if(!$path || !is_file($path))
		return $rows;
	$lines = file($path, FILE_IGNORE_NEW_LINES);
	if($lines === false)
		return $rows;
	for($i = 1; $i < count($lines); $i++) {
		if($lines[$i] === '')
			continue;
		$cols = explode("\t", $lines[$i]);
		$word = isset($cols[3]) ? trim($cols[3]) : '';
		if($word === '')
			continue;
		$remark = isset($cols[4]) ? trim($cols[4]) : '';
		$rows[] = array('word' => $word, 'remark' => $remark);
	}
	return $rows;
}

function renderWordlistBlackTsvWords($rows, $emojiMap = array()) {
	$html = "<div id='wordlist_blacklist_words' class='wordlist-blacklist-words indent-c'>";
	foreach($rows as $row) {
		$word = $row['word'];
		$remark = $row['remark'];
		$html .= '<span class="word-item word-blacklist"';
		if($remark !== '')
			$html .= ' data-reason="' . htmlspecialchars($remark, ENT_QUOTES, 'UTF-8') . '" title="' . htmlspecialchars($remark, ENT_QUOTES, 'UTF-8') . '"';
		$html .= '>';
		if(isset($emojiMap[$word]))
			$html .= '<span class="word-emoji">' . $emojiMap[$word] . '</span>';
		$html .= '<span class="word-text">' . htmlspecialchars($word, ENT_QUOTES, 'UTF-8') . '</span></span>';
		if($remark !== '')
			$html .= '<span class="word-blacklist-reason"> — ' . htmlspecialchars($remark, ENT_QUOTES, 'UTF-8') . '</span>';
	}
	$html .= '</div>';
	return $html;
}



function wordlistRulesInline($text) {
	$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
	$text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
	$text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);
	return preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function($m) {
		$url = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
		if(!preg_match('#^(https?:|mailto:|/)#', $url))
			$url = 'https://github.com/wcodes-org/wordlist/blob/main/' . $url;
		return "<a class='content-link' href='" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "' target='_blank'>" . $m[1] . '</a>';
	}, $text);
}

function wordlistRuleExample($text) {
	$wordBlock = function($word) {
		return '<span class="word-item wordlist-example-word"><span class="word-text">'
			. htmlspecialchars($word, ENT_QUOTES, 'UTF-8') . '</span></span>';
	};
	// The NATO example is an unquoted list, with slash-separated spelling variants.
	if(preg_match('~^(e\.g\.\s+)([A-Z][a-z]+(?:[-/][A-Za-z]+)*(?:,\s+[A-Z][a-z]+(?:[-/][A-Za-z]+)*)+)$~', $text, $m))
		return $m[1] . implode(', ', array_map($wordBlock, preg_split('/,\s+/', $m[2])));

	return preg_replace_callback("/&#039;([a-z]+(?:-[a-z]+)*)&#039;/i", function($m) use ($wordBlock) {
		return $wordBlock($m[1]);
	}, wordlistRulesInline($text));
}

function wordlistWordClass($word, $greylist = array(), $blacklist = array()) {
	if(isset($blacklist[$word]))
		return 'word-item word-blacklist';
	if(isset($greylist[$word]))
		return 'word-item word-greylist';
	return 'word-item word-whitelist';
}

function renderWordlistItem($word, $emojiMap, $greylist, $blacklist, $rank = null) {
	$class = wordlistWordClass($word, $greylist, $blacklist);
	$label = htmlspecialchars($word, ENT_QUOTES, 'UTF-8');
	$rank = (int) $rank;
	$focus = $rank ? ' tabindex="0" aria-label="' . $label . ', rank ' . $rank . '"' : '';
	$rankAttr = $rank ? ' data-rank="' . $rank . '"' : '';
	$html = '<span class="' . $class . '"' . $focus . '>';
	if(isset($emojiMap[$word]))
		$html .= '<span class="word-emoji">' . htmlspecialchars($emojiMap[$word], ENT_QUOTES, 'UTF-8') . '</span>';
	return $html . '<span class="word-text"' . $rankAttr . '>' . $label . '</span></span>';
}

function wordlistCandidateInline($text, $emojiMap, $greylist = array(), $blacklist = array(), $ranks = array()) {
	$text = wordlistRulesInline($text);
	return preg_replace_callback('/<code>([a-z]+)<\/code>/', function($m) use ($emojiMap, $greylist, $blacklist, $ranks) {
		return renderWordlistItem($m[1], $emojiMap, $greylist, $blacklist, $ranks[$m[1]] ?? null);
	}, $text);
}


function wordlistListedWords($path) {
	$words = array();
	if(!$path || !is_file($path))
		return $words;
	foreach(preg_split('/\r\n|\r|\n/', file_get_contents($path)) as $line) {
		if(preg_match('/^-\s+((?:`[a-z]+`(?:,\s*)?)+)/', $line, $m)) {
			if(preg_match_all('/`([a-z]+)`/', $m[1], $wm))
				foreach($wm[1] as $w)
					$words[$w] = true;
		}
	}
	return array_keys($words);
}


function wordlistRetiredListItem($content, $version = '') {
	static $n = 0;
	if(stripos($content, 'Replaced by') === false)
		return '<li>' . $content;
	if(!preg_match('/^(<span class="word-item word-blacklist"[^>]*>)/', $content, $m))
		return '<li>' . $content;
	$depth = 1;
	$pos = strlen($m[1]);
	$len = strlen($content);
	while($pos < $len && $depth > 0) {
		$nextOpen = strpos($content, '<span', $pos);
		$nextClose = strpos($content, '</span>', $pos);
		if($nextClose === false)
			return '<li>' . $content;
		if($nextOpen !== false && $nextOpen < $nextClose) {
			$gt = strpos($content, '>', $nextOpen);
			if($gt === false)
				return '<li>' . $content;
			$depth++;
			$pos = $gt + 1;
		} else {
			$depth--;
			$pos = $nextClose + 7;
		}
	}
	$chip = substr($content, 0, $pos);
	$rest = substr($content, $pos);
	if(!preg_match('/Replaced by\s+(.*)$/is', $rest, $rm))
		return '<li>' . $content;
	$replacement = rtrim($rm[1]);
	$replacement = preg_replace('/\.$/', '', $replacement);
	$reason = '';
	$parts = preg_split('/\s*Replaced by\s*/i', ltrim($rest), 2);
	if(isset($parts[0])) {
		$reason = preg_replace('/^[\s\-\x{2013}\x{2014}]+/u', '', $parts[0]);
		$reason = trim($reason, " \t\n\r.");
	}
	$n++;
	$id = 'word-retired-desc-' . $n;
	$li = '<li class="wordlist-retired" tabindex="0" aria-describedby="' . $id . '"';
	if($version !== '')
		$li .= ' data-version="' . htmlspecialchars($version, ENT_QUOTES, 'UTF-8') . '"';
	if($reason !== '')
		$li .= ' data-reason="' . htmlspecialchars(strip_tags($reason), ENT_QUOTES, 'UTF-8') . '"';
	$li .= '>' . $chip;
	if($reason !== '')
		$li .= '<span class="word-retired-reason">' . $reason . '</span>';
	$li .= '<span class="word-retired-meta" id="' . $id . '">replaced by ' . $replacement . '</span>';
	return $li;
}

function wordlistFoldToggle($label, $id) {
	return '<button type="button" class="wordlist-fold-toggle" aria-expanded="true" aria-controls="'
		. htmlspecialchars($id, ENT_QUOTES, 'UTF-8')
		. '"><span class="wordlist-fold-caret" aria-hidden="true"></span>'
		. '<span class="wordlist-fold-label">' . $label . '</span></button>';
}

function wordlistFoldOpen($tag, $label, $id, $class = '', $extra = '', $dataHeading = '') {
	$attr = '';
	if($class !== '')
		$attr .= ' class="' . $class . '"';
	if($dataHeading !== '')
		$attr .= ' data-heading="' . htmlspecialchars($dataHeading, ENT_QUOTES, 'UTF-8') . '"';
	return '<div class="wordlist-fold"><' . $tag . $attr . '>'
		. wordlistFoldToggle($label, $id)
		. $extra
		. '</' . $tag . '><div class="wordlist-fold-body" id="' . htmlspecialchars($id, ENT_QUOTES, 'UTF-8') . '">';
}

function renderWordlistMarkdown($path, $asDetails = false, $emojiMap = null, $greylist = array(), $blacklist = array(), $liveWords = null, $ranks = array()) {
	$inline = function($text) use ($emojiMap, $greylist, $blacklist, $ranks) {
		return ($emojiMap || $greylist || $blacklist) ? wordlistCandidateInline($text, $emojiMap, $greylist, $blacklist, $ranks) : wordlistRulesInline($text);
	};
	$raw = file_get_contents($path);
	$raw = preg_replace('/<!--.*?-->/s', '', $raw);
	$lines = preg_split('/\r\n|\r|\n/', $raw);
	$out = array();
	$list = null;
	$item = false;
	$started = false;
	$detailsOpen = false;
	$skipNotes = false;
	$retiredVersion = '';
	$currentH3 = '';
	$foldOpen = false;
	$foldStart = 0;
	$foldHasItems = false;
	$foldDropIfEmpty = false;
	$foldN = 0;
	$closeFold = function($forceKeep = false) use (&$foldOpen, &$foldStart, &$foldHasItems, &$foldDropIfEmpty, &$out) {
		if(!$foldOpen)
			return;
		if($foldDropIfEmpty && !$foldHasItems && !$forceKeep) {
			array_splice($out, $foldStart);
		} else {
			$out[] = '</div></div>';
		}
		$foldOpen = false;
		$foldHasItems = false;
		$foldDropIfEmpty = false;
	};
	$openFold = function($tag, $label, $class = '', $extra = '', $dataHeading = '', $dropIfEmpty = false) use (&$foldOpen, &$foldStart, &$foldHasItems, &$foldDropIfEmpty, &$foldN, &$out, $closeFold) {
		$closeFold();
		$foldN++;
		$foldStart = count($out);
		$foldHasItems = false;
		$foldDropIfEmpty = $dropIfEmpty;
		$foldOpen = true;
		$out[] = wordlistFoldOpen($tag, $label, 'wordlist-fold-d-' . $foldN, $class, $extra, $dataHeading);
	};
	$para = array();
	$flushPara = function() use (&$para, &$out, $inline) {
		if($para) {
			$out[] = "<p class='indent-c'>" . implode(' ', $para) . '</p>';
			$para = array();
		}
	};
	$closeItem = function() use (&$item, &$out) {
		if($item) {
			$out[] = '</li>';
			$item = false;
		}
	};
	$closeList = function() use (&$list, &$out, $closeItem) {
		$closeItem();
		if($list) {
			$out[] = "</$list>";
			$list = null;
		}
	};
	$closeDetails = function() use (&$detailsOpen, &$out, $flushPara, $closeList) {
		$flushPara();
		$closeList();
		if($detailsOpen) {
			$out[] = '</div></details>';
			$detailsOpen = false;
		}
	};
	foreach($lines as $line) {
		$line = rtrim($line);
		if($skipNotes) {
			if(preg_match('/^#{1,3}\s+/', $line) && !preg_match('/^#{1,3}\s+Notes for maintainers\b/i', $line))
				$skipNotes = false;
			else
				continue;
		}
		if(preg_match('/^#{1,3}\s+Notes for maintainers\b/i', $line)) {
			$flushPara(); $closeList(); $closeDetails(); $closeFold();
			$skipNotes = true;
			continue;
		}
		if(preg_match('/^####\s+(.*)$/', $line, $m)) {
			$flushPara(); $closeList();
			if(!$started) {
				$started = true;
			}
			$isVersion = preg_match('/^v\d+\b/i', $m[1]);
			$retiredVersion = $isVersion ? preg_replace('/^.*?\b(v\d+).*$/i', '$1', $m[1]) : '';
			$h4label = $inline($m[1]);
			if(!$asDetails && preg_match('/^NATO alphabet$/i', $m[1])) {
				$openFold('h4', $h4label);
			} else {
				if(!$asDetails && $foldOpen && !$isVersion && $currentH3 && preg_match('/^Blacklist$/i', $currentH3))
					$closeFold();
				$class = $isVersion ? ' class="wordlist-version"' : '';
				$out[] = '<h4' . $class . '>' . $h4label . '</h4>';
			}
			continue;
		}
		if(preg_match('/^###\s+(.*)$/', $line, $m)) {
			$flushPara(); $closeList();
			if($asDetails) {
				$closeDetails();
				$started = true;
				$currentH3 = rtrim($m[1], ':');
				$out[] = "<details><summary>" . $inline($m[1]) . "</summary><div class='indent-c'>";
				$detailsOpen = true;
			} else {
				if($started)
					$out[] = '<br>';
				$started = true;
				$currentH3 = $m[1];
				$retiredVersion = '';
				$label = $inline($m[1]);
				if(preg_match('/^Rejected$/i', $m[1])) {
					$openFold('h3', $label, 'wordlist-rejected-heading',
						' <button type="button" class="wordlist-versions-toggle" aria-pressed="false">versions</button>',
						'Rejected');
				} elseif(preg_match('/^Blacklist$/i', $m[1])) {
					$closeFold();
					$out[] = '<h3>' . $label . '</h3>';
				} else {
					$openFold('h3', $label, '', '', '', true);
				}
			}
			continue;
		}
		if(!$started) {
			if($line === '') {
				$flushPara();
				continue;
			}
			if(preg_match('/^#\s+/', $line))
				continue;
			// On the /wordlist page, RULES.md is embedded as collapsed sections.
			// Skip the file title (already handled) and the intro paragraph —
			// it is useful in the repo file, but redundant next to the page heading.
			if($asDetails)
				continue;
			$para[] = $inline(trim($line));
			continue;
		}
		if($line === '') {
			$flushPara(); $closeItem();
			continue;
		}
		if(preg_match('/^(\d+)\.\s+(.*)$/', $line, $m)) {
			$flushPara();
			if($list !== 'ol') {
				$closeList();
				$out[] = "<ol class='indent-e' start='" . intval($m[1]) . "'>";
				$list = 'ol';
			}
			$closeItem();
			$out[] = '<li>' . $inline($m[2]);
			$item = true;
			continue;
		}
		if(preg_match('/^[-*]\s+(.*)$/', $line, $m)) {
			if($liveWords !== null && !$asDetails && preg_match_all('/`([a-z]+)`/', $m[1], $wm)) {
				$anyLive = false;
				foreach($wm[1] as $w) {
					if(isset($liveWords[$w])) {
						$anyLive = true;
						break;
					}
				}
				if(!$anyLive)
					continue;
			}
			$flushPara();
			$foldHasItems = true;
			if($list !== 'ul') {
				$closeList();
				$listClass = 'indent-e';
				if($retiredVersion !== '' || preg_match('/^Rejected$/i', $currentH3))
					$listClass .= ' wordlist-retired-list';
				elseif(!$asDetails && preg_match('/^Blacklist$/i', $currentH3))
					$listClass .= ' wordlist-word-grid';
				$out[] = "<ul class='" . $listClass . "'>";
				$list = 'ul';
			}
			$closeItem();
			if($liveWords !== null && preg_match('/^(`fire` .+); shares \S+ with ((?:`[a-z]+`, )*`[a-z]+`)$/u', $m[1], $shared)) {
				$related = wordlistCandidateInline(str_replace(', ', ' ', $shared[2]), null, $greylist, $blacklist, $ranks);
				$out[] = '<li class="wordlist-shared-emoji">' . $inline($shared[1]) . ' · shares emoji with'
					. '<div class="wordlist-shared-emoji-words">' . $related . '</div>';
			} else {
				$out[] = wordlistRetiredListItem($inline($m[1]), $retiredVersion);
			}
			$item = true;
			continue;
		}
		if($item) {
			$example = trim($line);
			$exampleHtml = $asDetails && strcasecmp($currentH3, 'Word selection criteria') === 0
				? wordlistRuleExample($example)
				: $inline($example);
			$out[] = "<div class='content-li-example'>" . $exampleHtml . '</div>';
			continue;
		}
		if($list)
			$closeList();
		$para[] = $inline(trim($line));
	}
	$flushPara(); $closeList(); $closeDetails(); $closeFold();
	return implode("\n", $out);
}

function renderWordlistRules($path) {
	return renderWordlistMarkdown($path, false, null);
}
