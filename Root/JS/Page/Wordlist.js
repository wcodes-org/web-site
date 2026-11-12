var wordListWhite;
var wordListBlack;
var wordEmojiMap = (typeof wordEmojiMap !== 'undefined' && wordEmojiMap) ? wordEmojiMap : {};
var wordGreylistReasons = {};
var wordBlacklistSet = wordBlacklistSet || {};
var wordRanks = {};
var wordListBlackReasons = wordListBlackReasons || {};
var wordlistSearchMode = false;
var wordlistSavedTabId = 'wordlist_white_tab';

function loadWordlistMeta() {
	var el = document.getElementById('wordlist-meta');
	if (!el || !el.textContent) return;
	try {
		var meta = JSON.parse(el.textContent);
		wordRanks = meta.ranks || {};
		wordGreylistReasons = meta.greylist || {};
		wordBlacklistSet = {};
		(meta.blacklist || []).forEach(function(w) { wordBlacklistSet[w] = true; });
	} catch (e) {}
}

function wordItemClass(word, inDiscarded) {
	if (inDiscarded) return 'word-item word-blacklist';
	if (wordBlacklistSet[word]) return 'word-item word-blacklist';
	if (Object.prototype.hasOwnProperty.call(wordGreylistReasons, word)) return 'word-item word-greylist';
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
	}
	word_search_box.addEventListener('input', execSearch);
	word_search_box.addEventListener('focus', updateWordlistSearchPlaceholder);
	word_search_box.addEventListener('blur', updateWordlistSearchPlaceholder);
	search_input_clear.addEventListener('click', clearWordListSearch);
	bindWordlistTabs();
	bindWordlistViewControls();
	bindRetiredReveal();
	bindVersionsToggle();
	bindWordlistFolds();
	bindWordlistTabPeak();
	updateWordlistTabPeak();
}

function bindWordlistViewControls() {
	const controls = document.getElementById('wordlist_view_controls');
	if (!controls || !controls.hidden) return;
	controls.hidden = false;
	const group = controls.querySelector('[data-group-words]');
	const sorts = controls.querySelectorAll('[data-word-order]');
	controls.addEventListener('click', function(event) {
		const button = event.target.closest('button');
		if (!button) return;
		if (button === group) {
			const grouped = group.getAttribute('aria-pressed') !== 'true';
			group.setAttribute('aria-pressed', String(grouped));
		} else {
			sorts.forEach(function(sort) {
				sort.setAttribute('aria-pressed', String(sort === button));
			});
		}
		updateWordlistViews(false);
		if (wordlistSearchMode) updateWordlistViews(true);
	});
}

function cloneWordlistEntry(node, prefix) {
	const clone = node.cloneNode(true);
	const description = clone.querySelector('.word-retired-meta[id]');
	if (description) {
		description.id = prefix + description.id;
		clone.setAttribute('aria-describedby', description.id);
	}
	return clone;
}

function updateWordlistViews(searchResults) {
	const controls = document.getElementById('wordlist_view_controls');
	if (!controls) return;
	const grouped = controls.querySelector('[data-group-words]').getAttribute('aria-pressed') === 'true';
	const sort = controls.querySelector('[data-word-order][aria-pressed="true"]');
	const order = sort && sort.dataset.wordOrder;
	const roots = searchResults
		? '#wordlist-search-white, #wordlist_search_grey .wordlist-search-body, #wordlist_search_black .wordlist-search-body'
		: '#wordlist-table-white, #wordlist_grey_panel, #wordlist_black_panel';

	function compare(a, b) {
		if (order === 'rank' && a.rank !== b.rank) return a.rank - b.rank;
		return a.word.localeCompare(b.word, 'en');
	}

	document.querySelectorAll(roots).forEach(function(root) {
		const previousFlat = root.querySelector(':scope > .wordlist-flat');
		if (previousFlat) previousFlat.remove();
		root.classList.toggle('wordlist-is-flat', !grouped);
		if (grouped && !order) return;

		const entries = [];
		root.querySelectorAll('.secondary > .wordlist-fold-body, ul.indent-e, .wordlist-blacklist-words').forEach(function(group) {
			const groupEntries = [];
			Array.from(group.children).forEach(function(node) {
				const text = node.querySelector('.word-text');
				if (!text) return;
				const next = node.nextElementSibling;
				const reason = next && next.classList.contains('word-blacklist-reason') ? next : null;
				groupEntries.push({ node: node, reason: reason, word: text.textContent, rank: Number(text.dataset.rank) || Infinity });
			});
			if (grouped) {
				groupEntries.sort(compare).forEach(function(entry) {
					group.appendChild(entry.node);
					if (entry.reason) group.appendChild(entry.reason);
				});
			} else {
				entries.push(...groupEntries);
			}
		});
		if (grouped || !entries.length) return;
		if (order) entries.sort(compare);

		const flat = document.createElement('ul');
		flat.className = 'wordlist-flat indent-e';
		if (root.id === 'wordlist-table-white' || root.id === 'wordlist-search-white')
			flat.classList.add('wordlist-word-grid');
		entries.forEach(function(entry) {
			const clone = cloneWordlistEntry(entry.node, 'flat-');
			const item = clone.tagName === 'LI' ? clone : document.createElement('li');
			if (item !== clone) item.appendChild(clone);
			if (entry.reason) item.appendChild(entry.reason.cloneNode(true));
			flat.appendChild(item);
		});
		root.appendChild(flat);
	});
}

function updateWordlistSearchPlaceholder(event) {
	const placeholder = event.type === 'focus'
		? document.getElementById('wordlist_search_hint').textContent
		: 'search';
	event.currentTarget.placeholder = placeholder;
	document.getElementById('search-button_icon_wordlist').dataset.placeholder = placeholder;
}

function setWordlistVersions(on) {
	document.querySelectorAll('.wordlist-versions-toggle').forEach(function(btn) {
		btn.setAttribute('aria-pressed', String(!!on));
	});
	['wordlist_black_panel', 'wordlist_search_black'].forEach(function(id) {
		var el = document.getElementById(id);
		if (el) el.classList.toggle('show-versions', !!on);
	});
}

function bindVersionsToggle() {
	var root = document.getElementById('message');
	if (!root || root.getAttribute('data-versions-bound') === '1') return;
	root.setAttribute('data-versions-bound', '1');
	root.addEventListener('click', function(event) {
		var btn = event.target.closest('.wordlist-versions-toggle');
		if (!btn || !root.contains(btn)) return;
		event.preventDefault();
		setWordlistVersions(btn.getAttribute('aria-pressed') !== 'true');
	});
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

function updateWordlistTabPeak() {
	var tabs = document.getElementById('wordlist_tabs');
	if (!tabs) return;
	var peak = 50;
	if (!wordlistSearchMode) {
		var active = tabs.querySelector("button[aria-selected='true']");
		if (active) {
			var tabsRect = tabs.getBoundingClientRect();
			var lineWidth = parseFloat(getComputedStyle(tabs, '::before').width) || Math.min(tabsRect.width, 384);
			var lineLeft = tabsRect.left + (tabsRect.width - lineWidth) / 2;
			var ar = active.getBoundingClientRect();
			var center = ar.left + ar.width / 2;
			if (lineWidth > 0)
				peak = ((center - lineLeft) / lineWidth) * 100;
		}
	}
	if (peak < 0) peak = 0;
	if (peak > 100) peak = 100;
	tabs.style.setProperty('--wordlist-tab-peak', peak.toFixed(2) + '%');
}

function bindWordlistTabPeak() {
	if (bindWordlistTabPeak.bound) return;
	bindWordlistTabPeak.bound = true;
	var scheduled = false;
	function schedule() {
		if (scheduled) return;
		scheduled = true;
		requestAnimationFrame(function() {
			scheduled = false;
			updateWordlistTabPeak();
		});
	}
	window.addEventListener('resize', schedule);
	if (typeof ResizeObserver !== 'undefined') {
		var tabs = document.getElementById('wordlist_tabs');
		if (tabs) new ResizeObserver(schedule).observe(tabs);
	}
}

function wordlistFoldButton(label, id) {
	var btn = document.createElement('button');
	btn.type = 'button';
	btn.className = 'wordlist-fold-toggle';
	btn.setAttribute('aria-expanded', 'true');
	btn.setAttribute('aria-controls', id);
	var caret = document.createElement('span');
	caret.className = 'wordlist-fold-caret';
	caret.setAttribute('aria-hidden', 'true');
	var text = document.createElement('span');
	text.className = 'wordlist-fold-label';
	text.textContent = label;
	btn.appendChild(caret);
	btn.appendChild(text);
	return btn;
}

function bindWordlistFolds() {
	var root = document.getElementById('message');
	if (!root || root.getAttribute('data-folds-bound') === '1') return;
	root.setAttribute('data-folds-bound', '1');
	root.addEventListener('click', function(event) {
		var btn = event.target.closest('.wordlist-fold-toggle');
		if (!btn || !root.contains(btn)) return;
		event.preventDefault();
		var id = btn.getAttribute('aria-controls');
		var body = id && document.getElementById(id);
		if (!body) return;
		var open = btn.getAttribute('aria-expanded') !== 'false';
		btn.setAttribute('aria-expanded', open ? 'false' : 'true');
		if (open) body.setAttribute('hidden', '');
		else body.removeAttribute('hidden');
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
		updateWordlistTabPeak();
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
	updateWordlistTabPeak();
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
	updateWordlistTabPeak();
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
	updateWordlistViews(true);

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
	const count = fillWordList(body, wordListWhite, input, 'wordlist-search-white');
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
	let headingDone = false;
	let subheading = null;
	let currentItems = [];
	const versionsOn = !!(document.getElementById('wordlist_black_panel') &&
		document.getElementById('wordlist_black_panel').classList.contains('show-versions'));

	var searchFoldN = 0;
	function emitFoldHeading(tag, label, headingClass, extra) {
		searchFoldN++;
		const foldId = sectionId + '-fold-' + searchFoldN;
		const wrap = document.createElement('div');
		wrap.className = 'wordlist-fold';
		const headingEl = document.createElement(tag);
		if (headingClass) headingEl.className = headingClass;
		if (label === 'Rejected') headingEl.setAttribute('data-heading', 'Rejected');
		headingEl.appendChild(wordlistFoldButton(label, foldId));
		if (extra) headingEl.appendChild(extra);
		const foldBody = document.createElement('div');
		foldBody.className = 'wordlist-fold-body';
		foldBody.id = foldId;
		wrap.appendChild(headingEl);
		wrap.appendChild(foldBody);
		body.appendChild(wrap);
		return foldBody;
	}

	var currentFoldBody = body;

	function emitHeading() {
		if (headingDone || !heading) return;
		var extra = null;
		if (heading === 'Rejected') {
			extra = document.createElement('button');
			extra.type = 'button';
			extra.className = 'wordlist-versions-toggle';
			extra.setAttribute('aria-pressed', versionsOn ? 'true' : 'false');
			extra.textContent = 'versions';
			currentFoldBody = emitFoldHeading('h3', 'Rejected', 'wordlist-rejected-heading', extra);
		} else if (heading === 'Blacklist') {
			const h = document.createElement('h3');
			h.textContent = heading;
			body.appendChild(h);
			currentFoldBody = body;
		} else {
			currentFoldBody = emitFoldHeading('h3', heading, '', null);
		}
		headingDone = true;
	}

	function flush() {
		if (!currentItems.length) return;
		emitHeading();
		var target = currentFoldBody || body;
		if (subheading) {
			var subText = (subheading.getAttribute('data-heading') || subheading.textContent || '').trim();
			if (/^NATO alphabet$/i.test(subText)) {
				target = emitFoldHeading('h4', subText, subheading.className, null);
			} else {
				target.appendChild(subheading.cloneNode(true));
			}
		}
		const ul = document.createElement('ul');
		const retired = currentItems.some(function(li) { return li.classList.contains('wordlist-retired'); });
		ul.className = retired ? 'indent-e wordlist-retired-list' : 'indent-e wordlist-word-grid';
		currentItems.forEach(function(li) { ul.appendChild(cloneWordlistEntry(li, sectionId + '-')); });
		target.appendChild(ul);
		count += currentItems.length;
		currentItems = [];
	}

	function walkDocNodes(nodes) {
		Array.prototype.forEach.call(nodes, function(node) {
			if (node.nodeType !== 1) return;
			if (node.classList && node.classList.contains('wordlist-fold')) {
				walkDocNodes(node.children);
				return;
			}
			if (node.tagName === 'H3') {
				flush();
				var labelEl = node.querySelector('.wordlist-fold-label');
				heading = node.getAttribute('data-heading') || (labelEl ? labelEl.textContent : node.textContent).trim();
				if (heading.indexOf('versions') !== -1)
					heading = heading.replace(/\s*versions\s*$/, '').trim();
				headingDone = false;
				subheading = null;
			} else if (node.tagName === 'H4') {
				flush();
				subheading = node;
			} else if (node.tagName === 'UL' && !node.classList.contains('wordlist-flat')) {
				Array.prototype.forEach.call(node.children, function(li) {
					if (li.tagName === 'LI' && docEntryMatches(li, input))
						currentItems.push(li);
				});
			} else if (node.classList && node.classList.contains('wordlist-fold-body')) {
				walkDocNodes(node.children);
			}
		});
	}
	walkDocNodes(panel.children);
	flush();
	return count;
}

function clearWordListSearch() {
	word_search_box.value = '';
	document.getElementById('wordlist-message').innerText = '';
	document.getElementById('search_input_clear').classList.add('hide');
	if (wordlistSearchMode) exitSearchMode();
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
	if (!inDiscarded) {
		var rank = Object.prototype.hasOwnProperty.call(wordRanks, word) ? wordRanks[word] : null;
		var greylistReason = Object.prototype.hasOwnProperty.call(wordGreylistReasons, word) ? wordGreylistReasons[word] : '';
		var details = [rank ? 'Rank ' + rank : '', greylistReason].filter(Boolean).join(', ');
		if (rank) text.setAttribute('data-rank', rank);
		if (greylistReason) span.setAttribute('data-greylist-tooltip', greylistReason);
		if (details) {
			span.tabIndex = 0;
			span.setAttribute('aria-label', word + ', ' + details);
		}
	}
	span.appendChild(emoji);
	span.appendChild(text);
	return span;
}

function fillWordList(target, wordList, matchString, id) {
	var x = document.createElement('div');
	x.id = id;
	var count = 0;
	for(var p in wordList) {
		var primary = document.createElement('div');
		primary.setAttribute('class', p === 'Greylist' ? 'primary greylist' : 'primary');
		var primary_word = document.createElement('div');
		primary_word.setAttribute('class', 'primary-word');
		var primaryFoldId = id + '-p-' + Object.keys(wordList).indexOf(p);
		primary_word.appendChild(wordlistFoldButton(p, primaryFoldId));
		primary.appendChild(primary_word);
		var primaryBody = document.createElement('div');
		primaryBody.className = 'wordlist-fold-body';
		primaryBody.id = primaryFoldId;
		var bAny = false;
		var sIndex = 0;
		for(var s in wordList[p]) {
			var secondary = document.createElement('div');
			secondary.setAttribute('class', 'secondary');
			var secondary_word = document.createElement('div');
			secondary_word.setAttribute('class', 'secondary-word');
			var secondaryFoldId = primaryFoldId + '-s-' + (sIndex++);
			secondary_word.appendChild(wordlistFoldButton(s, secondaryFoldId));
			secondary.appendChild(secondary_word);
			var div = document.createElement('div');
			div.className = 'wordlist-fold-body';
			div.id = secondaryFoldId;
			var subList = matchWord(wordList[p][s], matchString);
			for(var i in subList) {
				div.appendChild(createWordItem(subList[i], false));
				count++;
			}
			secondary.appendChild(div);
			primaryBody.appendChild(secondary);
			if(subList.length == 0)
				secondary.classList.add('hide_display');
			else {
				secondary.classList.remove('hide_display');
				bAny = true;
			}
		}
		primary.appendChild(primaryBody);
		if(bAny)
			primary.classList.remove('hide_display');
		else
			primary.classList.add('hide_display');
		x.appendChild(primary);
	}
	target.replaceChildren(x);
	return count;
}
