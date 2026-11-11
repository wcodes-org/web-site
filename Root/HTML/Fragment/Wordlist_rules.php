<?php
/*
 * Renders canonical wordlist docs (RULES.md, CANDIDATES.md from wcodes-org/wordlist).
 * Source order:
 *   1. WCODE_WORDLIST_RULES / WCODE_WORDLIST_CANDIDATES env vars (full path)
 *   2. Sibling checkout of the wordlist repo (D:\WCode\Wordlist\Project\ locally)
 *   3. Committed copy Root/File/Wordlist-rules.md or Wordlist-candidates.md
 *      (refresh with File/Sync-rules.ps1)
 * Only the small Markdown subset these docs use is supported:
 * ### headings, "1." ordered items, "-" bullets, follow-on lines (examples),
 * paragraphs, `code`, **bold** and [links](url).
 */
function wordlistDocPath($name) {
	$committed = array(
		'RULES.md' => 'Wordlist-rules.md',
		'CANDIDATES.md' => 'Wordlist-candidates.md'
	);
	$envKey = ($name === 'CANDIDATES.md') ? 'WCODE_WORDLIST_CANDIDATES' : 'WCODE_WORDLIST_RULES';
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

function wordlistCandidateInline($text, $emojiMap) {
	$text = wordlistRulesInline($text);
	if(!$emojiMap)
		return $text;
	return preg_replace_callback('/<code>([a-z]+)<\/code>/', function($m) use ($emojiMap) {
		$w = $m[1];
		if(!isset($emojiMap[$w]))
			return $m[0];
		$e = htmlspecialchars($emojiMap[$w], ENT_QUOTES, 'UTF-8');
		return '<span class="word-item candidate-word"><span class="word-emoji">' . $e . '</span> <span class="word-text">' . $w . '</span></span>';
	}, $text);
}

function renderWordlistMarkdown($path, $asDetails = false, $emojiMap = null) {
	$inline = function($text) use ($emojiMap) {
		return $emojiMap ? wordlistCandidateInline($text, $emojiMap) : wordlistRulesInline($text);
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
