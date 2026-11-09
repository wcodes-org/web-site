<?php
/*
 * Renders the canonical wordlist rules (RULES.md from wcodes-org/wordlist).
 * Source order:
 *   1. WCODE_WORDLIST_RULES env var (path to a RULES.md)
 *   2. Sibling checkout of the wordlist repo (D:\WCode\Wordlist\Project\RULES.md locally)
 *   3. Committed copy root/File/Wordlist-rules.md (refresh with File/Sync-rules.ps1)
 * Only the small Markdown subset RULES.md uses is supported:
 * ### headings, "1." ordered items, "-" bullets, follow-on lines (examples),
 * paragraphs, `code` and [links](url).
 */
function wordlistRulesPath() {
	$candidates = array(
		getenv('WCODE_WORDLIST_RULES'),
		__DIR__ . '/../../../../../../../Wordlist/Project/RULES.md',
		__DIR__ . '/../../File/Wordlist-rules.md'
	);
	foreach($candidates as $path)
		if($path && is_file($path))
			return $path;
	return null;
}

function wordlistRulesInline($text) {
	$text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
	$text = preg_replace('/`([^`]+)`/', '<code>$1</code>', $text);
	return preg_replace_callback('/\[([^\]]+)\]\(([^)\s]+)\)/', function($m) {
		$url = html_entity_decode($m[2], ENT_QUOTES, 'UTF-8');
		if(!preg_match('#^(https?:|mailto:|/)#', $url))
			$url = 'https://github.com/wcodes-org/wordlist/blob/main/' . $url;
		return "<a class='content-link' href='" . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . "' target='_blank'>" . $m[1] . '</a>';
	}, $text);
}

function renderWordlistRules($path) {
	$lines = preg_split('/\r\n|\r|\n/', file_get_contents($path));
	$out = array();
	$list = null;     // 'ol' | 'ul' | null
	$item = false;    // inside <li>
	$started = false; // skip the doc title and intro before the first ### heading
	$para = array();
	$flushPara = function() use (&$para, &$out) {
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
	foreach($lines as $line) {
		$line = rtrim($line);
		if(preg_match('/^###\s+(.*)$/', $line, $m)) {
			$flushPara(); $closeList();
			if($started)
				$out[] = '<br>';
			$started = true;
			$out[] = '<h3>' . wordlistRulesInline($m[1]) . '</h3>';
			continue;
		}
		if(!$started)
			continue;
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
			$out[] = '<li>' . wordlistRulesInline($m[2]);
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
			$out[] = '<li>' . wordlistRulesInline($m[1]);
			$item = true;
			continue;
		}
		if($item) {
			$out[] = "<div class='content-li-example'>" . wordlistRulesInline(trim($line)) . '</div>';
			continue;
		}
		if($list)
			$closeList();
		$para[] = wordlistRulesInline(trim($line));
	}
	$flushPara(); $closeList();
	return implode("\n", $out);
}

$wordlistRulesFile = wordlistRulesPath();
if($wordlistRulesFile)
	echo renderWordlistRules($wordlistRulesFile);
else
	echo "<p class='indent-c'>Rules: <a class='content-link' href='https://github.com/wcodes-org/wordlist/blob/main/RULES.md' target='_blank'>RULES.md</a></p>";
