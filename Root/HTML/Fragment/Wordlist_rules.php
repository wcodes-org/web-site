<?php
/*
 * Renders canonical wordlist docs (RULES.md, GREYLIST.md, BLACKLIST.md from wcodes-org/wordlist).
 * Source order:
 *   1. WCODE_WORDLIST_RULES / WCODE_WORDLIST_GREYLIST / WCODE_WORDLIST_BLACKLIST env vars (full path)
 *   2. Sibling checkout of the wordlist repo (D:\WCode\Wordlist\Project\ locally)
 *   3. Committed copy Root/File/Wordlist-rules.md, Wordlist-greylist.md or Wordlist-blacklist.md
 *      (refresh with File/Sync-rules.ps1)
 * Only the small Markdown subset these docs use is supported:
 * ### headings, "1." ordered items, "-" bullets, follow-on lines (examples),
 * paragraphs, `code`, **bold** and [links](url).
 */
function wordlistDocPath($name) {
	$committed = array(
		'RULES.md' => 'Wordlist-rules.md',
		'GREYLIST.md' => 'Wordlist-greylist.md',
		'BLACKLIST.md' => 'Wordlist-blacklist.md',
		'CANDIDATES.md' => 'Wordlist-greylist.md'
	);
	$envKeys = array(
		'RULES.md' => 'WCODE_WORDLIST_RULES',
		'GREYLIST.md' => 'WCODE_WORDLIST_GREYLIST',
		'BLACKLIST.md' => 'WCODE_WORDLIST_BLACKLIST',
		'CANDIDATES.md' => 'WCODE_WORDLIST_GREYLIST'
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

function wordlistWordClass($word, $greylist = array(), $blacklist = array()) {
	if(isset($blacklist[$word]))
		return 'word-item word-blacklist';
	if(isset($greylist[$word]))
		return 'word-item word-greylist';
	return 'word-item word-whitelist';
}

function wordlistCandidateInline($text, $emojiMap, $greylist = array(), $blacklist = array()) {
	$text = wordlistRulesInline($text);
	return preg_replace_callback('/<code>([a-z]+)<\/code>/', function($m) use ($emojiMap, $greylist, $blacklist) {
		$w = $m[1];
		$class = wordlistWordClass($w, $greylist, $blacklist);
		$chip = '<span class="' . $class . '">';
		if($emojiMap && isset($emojiMap[$w]))
			$chip .= '<span class="word-emoji">' . htmlspecialchars($emojiMap[$w], ENT_QUOTES, 'UTF-8') . '</span> ';
		$chip .= '<span class="word-text">' . $w . '</span></span>';
		return $chip;
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

function renderWordlistMarkdown($path, $asDetails = false, $emojiMap = null, $greylist = array(), $blacklist = array()) {
	$inline = function($text) use ($emojiMap, $greylist, $blacklist) {
		return ($emojiMap || $greylist || $blacklist) ? wordlistCandidateInline($text, $emojiMap, $greylist, $blacklist) : wordlistRulesInline($text);
	};
	$lines = preg_split('/\r\n|\r|\n/', file_get_contents($path));
	$out = array();
	$list = null;
	$item = false;
	$started = false;
	$detailsOpen = false;
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
		if(preg_match('/^###\s+(.*)$/', $line, $m)) {
			$flushPara(); $closeList();
			if($asDetails) {
				$closeDetails();
				$started = true;
				$out[] = "<details><summary>" . $inline($m[1]) . "</summary><div class='indent-c'>";
				$detailsOpen = true;
			} else {
				if($started)
					$out[] = '<br>';
				$started = true;
				$out[] = '<h3>' . $inline($m[1]) . '</h3>';
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
			$flushPara();
			if($list !== 'ul') {
				$closeList();
				$out[] = "<ul class='indent-e'>";
				$list = 'ul';
			}
			$closeItem();
			$out[] = '<li>' . $inline($m[1]);
			$item = true;
			continue;
		}
		if($item) {
			$out[] = "<div class='content-li-example'>" . $inline(trim($line)) . '</div>';
			continue;
		}
		if($list)
			$closeList();
		$para[] = $inline(trim($line));
	}
	$flushPara(); $closeList(); $closeDetails();
	return implode("\n", $out);
}

function renderWordlistRules($path) {
	return renderWordlistMarkdown($path, false, null);
}
