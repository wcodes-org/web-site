var wordListWhite;
var wordListBlack;
var wordEmojiMap = (typeof wordEmojiMap !== 'undefined' && wordEmojiMap) ? wordEmojiMap : {};
var wordGreylistSet = wordGreylistSet || {};
var wordBlacklistSet = wordBlacklistSet || {};
var wordListBlackReasons = wordListBlackReasons || {};
var wordlistSearchMode = false;
var wordlistSavedTabId = 'wordlist_white_tab';

function loadWordlistMeta() {
	var el = document.getElementById('wordlist-meta');
	if (!el || !el.textContent) return;
	try {
		var meta = JSON.parse(el.textContent);
		wordGreylistSet = {};
		wordBlacklistSet = {};
		(meta.greylist || []).forEach(function(w) { wordGreylistSet[w] = true; });
		(meta.blacklist || []).forEach(function(w) { wordBlacklistSet[w] = true; });
	} catch (e) {}
}

function wordItemClass(word, inDiscarded) {
	if (inDiscarded) return 'word-item word-blacklist';
	if (wordBlacklistSet[word]) return 'word-item word-blacklist';
	if (wordGreylistSet[word]) return 'word-item word-greylist';
	return 'word-item word-whitelist';
}

function wordlistTabButtons() {
	return Array.from(document.querySelectorAll('#wordlist_tabs > button'));
}

function wordlist() {
	if(typeof wordListWhite == 'undefined')
		wordListWhite = [];
	if(typeof wordListBlack == 'undefined')
		wordListBlack = [];
	if(typeof wordEmojiMap == 'undefined' || !wordEmojiMap)
		wordEmojiMap = {};
	loadWordlistMeta();
	if(wordListWhite.length == 0) {
		wordListWhite = loadWordlistWhite();
		initloadWordlistBlack();
		word_search_box.addEventListener('input', execSearch);
		search_input_clear.addEventListener('click', clearWordListSearch);
	}
	bindWordlistTabs();
	bindRetiredReveal();
}

function bindRetiredReveal() {
	var root = document.getElementById('message');
	if (!root || root.getAttribute('data-retired-bound') === '1') return;
	root.setAttribute('data-retired-bound', '1');

	function isCoarse() {
		return window.matchMedia && window.matchMedia('(hover: none), (pointer: coarse)').matches;
	}

	function closeAll(except) {
		root.querySelectorAll('.wordlist-retired.is-open').forEach(function(el) {
			if (el !== except) el.classList.remove('is-open');
		});
	}

	root.addEventListener('click', function(event) {
		var item = event.target.closest('.wordlist-retired');
		if (!item || !root.contains(item)) {
			closeAll();
			return;
		}
		if (!isCoarse()) return;
		var open = item.classList.contains('is-open');
		closeAll();
		if (!open) item.classList.add('is-open');
	});

	root.addEventListener('keydown', function(event) {
		var item = event.target.closest('.wordlist-retired');
		if (!item) return;
		if (event.key === 'Escape') {
			item.classList.remove('is-open');
			item.blur();
		}
	});
}

function bindWordlistTabs() {
	const tabList = document.getElementById('wordlist_tabs');
	if (!tabList || tabList.getAttribute('data-bound') === '1') return;
	tabList.setAttribute('data-bound', '1');

	function tabs() {
		return wordlistTabButtons().filter(function(tab) { return !tab.hidden; });
	}

	function selectTab(selectedTab) {
		if (wordlistSearchMode) return;
		wordlistTabButtons().forEach(function(tab) {
			const selected = tab === selectedTab;
			tab.setAttribute('aria-selected', String(selected));
			tab.tabIndex = selected ? 0 : -1;
			const panel = document.getElementById(tab.getAttribute('aria-controls'));
			if (panel) panel.hidden = !selected;
		});
		wordlistSavedTabId = selectedTab.id;
	}

	function activate(tab) {
		if (wordlistSearchMode) jumpToSearchSection(tab);
		else selectTab(tab);
	}

	tabList.addEventListener('click', function(event) {
		const tab = event.target.closest('button');
		if (!tab || !tabList.contains(tab) || tab.hidden) return;
		activate(tab);
	});

	tabList.addEventListener('keydown', function(event) {
		const visible = tabs();
		const current = event.target.closest('button');
		const index = visible.indexOf(current);
		if (index < 0) return;
		if (wordlistSearchMode && (event.key === 'Enter' || event.key === ' ')) {
			event.preventDefault();
			jumpToSearchSection(current);
			return;
		}
		let nextIndex;
		switch (event.key) {
			case 'ArrowLeft': nextIndex = (index + visible.length - 1) % visible.length; break;
			case 'ArrowRight': nextIndex = (index + 1) % visible.length; break;
			case 'Home': nextIndex = 0; break;
			case 'End': nextIndex = visible.length - 1; break;
			default: return;
		}
		event.preventDefault();
		if (wordlistSearchMode) visible[nextIndex].focus();
		else {
			selectTab(visible[nextIndex]);
			visible[nextIndex].focus();
		}
	});
}

function jumpToSearchSection(tab) {
	if (tab.getAttribute('aria-disabled') === 'true') return;
	const id = tab.getAttribute('data-search-section');
	const section = id && document.getElementById(id);
	if (!section || section.hidden) return;
	section.scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function enterSearchMode() {
	wordlistSearchMode = true;
	const tabList = document.getElementById('wordlist_tabs');
	tabList.setAttribute('role', 'navigation');
	tabList.setAttribute('aria-label', 'Search result sections');
	document.getElementById('wordlist_search_results').hidden = false;
	document.querySelector('.wordlist_tab_panels').hidden = true;
	wordlistTabButtons().forEach(function(tab) {
		tab.setAttribute('role', 'link');
		tab.removeAttribute('aria-selected');
		tab.removeAttribute('aria-controls');
		tab.tabIndex = 0;
	});
}

function exitSearchMode() {
	const tabList = document.getElementById('wordlist_tabs');
	const results = document.getElementById('wordlist_search_results');
	const panels = document.querySelector('.wordlist_tab_panels');
	wordlistSearchMode = false;
	if (results) results.hidden = true;
	if (panels) panels.hidden = false;
	document.getElementById('wordlist_search_empty').hidden = true;
	['white', 'grey', 'black'].forEach(function(name) {
		const section = document.getElementById('wordlist_search_' + name);
		if (section) section.hidden = true;
	});
	tabList.setAttribute('role', 'tablist');
	tabList.setAttribute('aria-label', 'Wordlist');
	wordlistTabButtons().forEach(function(tab) {
		const label = tab.getAttribute('data-tab-label') || tab.id;
		tab.textContent = label;
		tab.removeAttribute('aria-disabled');
		tab.setAttribute('role', 'tab');
		tab.setAttribute('aria-controls', tab.id.replace('_tab', '_panel'));
	});
	const saved = document.getElementById(wordlistSavedTabId) || document.getElementById('wordlist_white_tab');
	wordlistTabButtons().forEach(function(tab) {
		const selected = tab === saved;
		tab.setAttribute('aria-selected', String(selected));
		tab.tabIndex = selected ? 0 : -1;
		const panel = document.getElementById(tab.getAttribute('aria-controls'));
		if (panel) panel.hidden = !selected;
	});
}

function setSearchTabLink(tab, count) {
	const label = tab.getAttribute('data-tab-label') || tab.id;
	tab.textContent = label;
	tab.hidden = false;
	if (count > 0) {
		tab.removeAttribute('aria-disabled');
		tab.tabIndex = 0;
	} else {
		tab.setAttribute('aria-disabled', 'true');
		tab.tabIndex = 0;
	}
}

function applyWordlistSearch(input) {
	if (!wordlistSearchMode) enterSearchMode();

	const whiteCount = renderWhiteSearch(input);
	const greyCount = renderDocSearch('wordlist_grey_panel', 'wordlist_search_grey', input);
	const blackDocCount = renderDocSearch('wordlist_black_panel', 'wordlist_search_black', input);
	const tsvCount = renderBlackTsvSearch(input);
	const blackCount = blackDocCount + tsvCount;

	setSearchTabLink(document.getElementById('wordlist_white_tab'), whiteCount);
	setSearchTabLink(document.getElementById('wordlist_grey_tab'), greyCount);
	setSearchTabLink(document.getElementById('wordlist_black_tab'), blackCount);

	const greySection = document.getElementById('wordlist_search_grey');
	if (greySection) greySection.hidden = greyCount === 0;
	const blackSection = document.getElementById('wordlist_search_black');
	if (blackSection) blackSection.hidden = blackCount === 0;

	const total = whiteCount + greyCount + blackCount;
	document.getElementById('wordlist_search_empty').hidden = total > 0;

	document.getElementById('wordlist_search_white').querySelector('h2').textContent = 'Whitelist';
	document.getElementById('wordlist_search_grey').querySelector('h2').textContent = 'Greylist';
	document.getElementById('wordlist_search_black').querySelector('h2').textContent = 'Blacklist';
}

function renderWhiteSearch(input) {
	const section = document.getElementById('wordlist_search_white');
	const body = section.querySelector('.wordlist-search-body');
	const count = fillWordList(body, wordListWhite, input, false, 'wordlist-search-white');
	section.hidden = count === 0;
	return count;
}

function blackTsvEntryMatches(word, reason, input) {
	if (typeof matchWord === 'function' && matchWord([word], input).length) return true;
	if (input.length >= 2 && reason && reason.toLowerCase().indexOf(input.toLowerCase()) !== -1)
		return true;
	return false;
}

function renderBlackTsvSearch(input) {
	const body = document.querySelector('#wordlist_search_black .wordlist-search-body');
	if (!body) return 0;
	const group = wordListBlack && wordListBlack['BlackTsv'];
	const words = group && group['*'] ? group['*'] : [];
	const matches = [];
	for (let i = 0; i < words.length; i++) {
		const word = words[i];
		const reason = (wordListBlackReasons && wordListBlackReasons[word]) || '';
		if (blackTsvEntryMatches(word, reason, input))
			matches.push({ word: word, reason: reason });
	}
	if (!matches.length) return 0;
	var hasBlacklistHeading = false;
	body.querySelectorAll('h3').forEach(function(h) {
		if (h.textContent === 'Blacklist') hasBlacklistHeading = true;
	});
	if (!hasBlacklistHeading) {
		const heading = document.createElement('h3');
		heading.textContent = 'Blacklist';
		body.appendChild(heading);
	}
	const wrap = document.createElement('div');
	wrap.className = 'wordlist-blacklist-words indent-c';
	matches.forEach(function(item) {
		wrap.appendChild(createWordItem(item.word, true, item.reason));
		if (item.reason) {
			const reasonEl = document.createElement('span');
			reasonEl.className = 'word-blacklist-reason';
			reasonEl.textContent = ' — ' + item.reason;
			wrap.appendChild(reasonEl);
		}
	});
	body.appendChild(wrap);
	return matches.length;
}

function docEntryMatches(li, input) {
	const words = [];
	li.querySelectorAll('.word-text').forEach(function(el) { words.push(el.textContent); });
	if (typeof matchWord === 'function' && matchWord(words, input).length) return true;
	if (input.length >= 2) {
		const text = (li.textContent || '').toLowerCase();
		if (text.indexOf(input.toLowerCase()) !== -1) return true;
	}
	return false;
}

function renderDocSearch(panelId, sectionId, input) {
	const panel = document.getElementById(panelId);
	const section = document.getElementById(sectionId);
	const body = section.querySelector('.wordlist-search-body');
	body.innerHTML = '';
	let count = 0;
	let heading = '';
	let currentItems = [];

	function flush() {
		if (!currentItems.length) return;
		if (heading) {
			const h = document.createElement('h3');
			h.textContent = heading;
			body.appendChild(h);
		}
		const ul = document.createElement('ul');
		ul.className = 'indent-e';
		currentItems.forEach(function(li) { ul.appendChild(li.cloneNode(true)); });
		body.appendChild(ul);
		count += currentItems.length;
		currentItems = [];
	}

	Array.prototype.forEach.call(panel.children, function(node) {
		if (node.tagName === 'H3') {
			flush();
			heading = node.textContent;
		} else if (node.tagName === 'UL') {
			Array.prototype.forEach.call(node.children, function(li) {
				if (li.tagName === 'LI' && docEntryMatches(li, input))
					currentItems.push(li);
			});
		}
	});
	flush();
	return count;
}

function clearWordListSearch() {
	word_search_box.value = '';
	document.getElementById('wordlist-message').innerText = '';
	document.getElementById('search_input_clear').classList.add('hide');
	if (wordlistSearchMode) exitSearchMode();
	setWordList(null, true);
}

function loadWordlistWhite() {
	var x = [];
	var parents = document.getElementById('wordlist-table').getElementsByClassName('primary');
	for(var i = 0; i < parents.length; i++) {
		x[parents[i].firstChild.innerText] = getSecondary(parents[i]);
	};
	return x;
}

function adoptBlackTsv(words, reasons) {
	wordListBlack = wordListBlack || [];
	wordListBlack['BlackTsv'] = [];
	wordListBlack['BlackTsv']['*'] = words || [];
	wordListBlackReasons = reasons || {};
	if (wordlistSearchMode && word_search_box && word_search_box.value)
		applyWordlistSearch(word_search_box.value.toLowerCase());
}

function loadBlackTsvFromDom() {
	var root = document.getElementById('wordlist_blacklist_words');
	if (!root) return false;
	var items = root.getElementsByClassName('word-item');
	if (!items.length) return false;
	var words = [];
	var reasons = {};
	for (var i = 0; i < items.length; i++) {
		var textEl = items[i].getElementsByClassName('word-text')[0];
		if (!textEl) continue;
		var word = textEl.textContent;
		words.push(word);
		var reason = items[i].getAttribute('data-reason');
		if (reason) reasons[word] = reason;
	}
	adoptBlackTsv(words, reasons);
	return true;
}

function loadWordlistBlack(blackList) {
	var lines = blackList.split("\n");
	var obj = [];
	var reasons = {};
	for(var i = 1; i < lines.length; i++) {
		if(lines[i].length) {
			var currentline = lines[i].split("\t");
			if(currentline[3] && currentline[3].length > 0) {
				var word = currentline[3].trim();
				obj.push(word);
				if (currentline[4] && currentline[4].trim())
					reasons[word] = currentline[4].trim();
			}
		}
	}
	adoptBlackTsv(obj, reasons);
}

function initloadWordlistBlack() {
	if (loadBlackTsvFromDom()) return;
	var xobj = new XMLHttpRequest();
	xobj.overrideMimeType("text/pain");
	xobj.open('GET', "/wordlist-black.tsv", true);
	xobj.onreadystatechange = function () {
		if (xobj.readyState == 4 && xobj.status == "200") {
			loadWordlistBlack(xobj.responseText);
		}
	};
	xobj.send(null);
}

function getSecondary(parent) {
	var x = [];
	var children = parent.getElementsByClassName('secondary');
	for(var i = 0; i < children.length; i++) {
		x[children[i].firstChild.innerText] = getWords(children[i]);
	}
	return x;
}

function getWords(child) {
	if(typeof wordEmojiMap == 'undefined' || !wordEmojiMap)
		wordEmojiMap = {};
	var x = [];
	var words = child.getElementsByClassName('word-item');
	for(var i = 0; i < words.length; i++) {
		var textEl = words[i].getElementsByClassName('word-text')[0];
		if(!textEl)
			continue;
		var word = textEl.innerText;
		x[i] = word;
		var emojiEl = words[i].getElementsByClassName('word-emoji')[0];
		if(emojiEl)
			wordEmojiMap[word] = emojiEl.innerText;
	}
	return x;
}

function createWordItem(word, inDiscarded, reason) {
	if(typeof wordEmojiMap == 'undefined' || !wordEmojiMap)
		wordEmojiMap = {};
	var span = document.createElement('span');
	span.setAttribute('class', wordItemClass(word, !!inDiscarded));
	if (reason) {
		span.setAttribute('data-reason', reason);
		span.setAttribute('title', reason);
	}
	var emoji = document.createElement('span');
	emoji.setAttribute('class', 'word-emoji');
	emoji.innerText = wordEmojiMap[word] || '';
	if(!wordEmojiMap[word])
		emoji.style.display = 'none';
	var text = document.createElement('span');
	text.setAttribute('class', 'word-text');
	text.innerText = word;
	span.appendChild(emoji);
	span.appendChild(text);
	return span;
}

function fillWordList(target, wordList, matchString, inDiscarded, id) {
	var x = document.createElement('div');
	if (id) x.setAttribute('id', id);
	var count = 0;
	for(var p in wordList) {
		var primary = document.createElement('div');
		primary.setAttribute('class', p === 'Greylist' ? 'primary greylist' : 'primary');
		var primary_word = document.createElement('div');
		primary_word.setAttribute('class', 'primary-word');
		primary_word.innerText = p;
		if (inDiscarded && p === 'Discarded')
			primary_word.classList.add('hide');
		primary.appendChild(primary_word);
		var bAny = false;
		for(var s in wordList[p]) {
			var secondary = document.createElement('div');
			secondary.setAttribute('class', 'secondary');
			var secondary_word = document.createElement('div');
			secondary_word.setAttribute('class', 'secondary-word');
			secondary_word.innerText = s;
			if (inDiscarded && s === '*')
				secondary_word.classList.add('hide');
			secondary.appendChild(secondary_word);
			var div = document.createElement('div');
			var subList = [];
			if(matchString != null)
				subList = matchWord(wordList[p][s], matchString);
			else
				subList = wordList[p][s];
			for(var i in subList) {
				div.appendChild(createWordItem(subList[i], inDiscarded));
				count++;
			}
			secondary.appendChild(div);
			primary.appendChild(secondary);
			if(subList.length == 0)
				secondary.classList.add('hide_display');
			else {
				secondary.classList.remove('hide_display');
				bAny = true;
			}
		}
		if(bAny)
			primary.classList.remove('hide_display');
		else
			primary.classList.add('hide_display');
		x.appendChild(primary);
	}
	if (target.id && target.id === id) {
		target.replaceWith(x);
	} else {
		target.innerHTML = '';
		target.appendChild(x);
	}
	return count;
}

function setWordList(matchString, white__black) {
	var id = white__black ? 'wordlist-table-white' : 'wordlist-table-black';
	var wordList = white__black ? wordListWhite : wordListBlack;
	var el = document.getElementById(id);
	if (!el) return 0;
	return fillWordList(el, wordList, matchString, !white__black, id);
}
