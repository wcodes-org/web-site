var wordListWhite;
var wordListBlack;
var wordEmojiMap = {};

function wordlist() {
	if(typeof wordListWhite == 'undefined')
		wordListWhite = [];
	if(typeof wordListBlack == 'undefined')
		wordListBlack = [];
	if(wordListWhite.length == 0) {
		wordListWhite = loadWordlistWhite();
		initloadWordlistBlack();
		word_search_box.addEventListener('input', execSearch);
		search_input_clear.addEventListener('click', clearWordListSearch);
	}
}

function clearWordListSearch() {
	word_search_box.value = '';
	setWordList(null, true);
	document.getElementById('wordlist-table-black').innerText = '';
	document.getElementById('wordlist-table-separator').classList.add('hide');
	document.getElementById('search_input_clear').classList.add('hide');
}

function loadWordlistWhite() {
	var x = [];
	var parents = document.getElementById('wordlist-table').getElementsByClassName('primary');
	for(var i = 0; i < parents.length; i++) {
		x[parents[i].firstChild.innerText] = getSecondary(parents[i]);
	};
	return x;
}

function loadWordlistBlack(blackList) {
	var lines = blackList.split("\n");
	var obj = [];
	for(var i = 1; i < lines.length; i++) {
		if(lines[i].length) {
			var currentline = lines[i].split("\t");
				if(currentline[3].length > 0)
					obj.push(currentline[3]);
		}
	}
	wordListBlack['Discarded'] = [];
	wordListBlack['Discarded']['*'] = obj;
}

function initloadWordlistBlack() {
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
	var x = [];
	var words = child.getElementsByClassName('word-item');
	for(var i = 0; i < words.length; i++) {
		var textEl = words[i].getElementsByClassName('word-text')[0];
		var word = textEl.innerText;
		x[i] = word;
		var emojiEl = words[i].getElementsByClassName('word-emoji')[0];
		if(emojiEl)
			wordEmojiMap[word] = emojiEl.innerText;
	}
	return x;
}

function createWordItem(word) {
	var span = document.createElement('span');
	span.setAttribute('class', 'word-item');
	var emoji = document.createElement('span');
	emoji.setAttribute('class', 'word-emoji');
	emoji.innerText = wordEmojiMap[word] || '💬';
	var text = document.createElement('span');
	text.setAttribute('class', 'word-text');
	text.innerText = word;
	span.appendChild(emoji);
	span.appendChild(text);
	return span;
}

function setWordList(matchString, white__black) {
	var id;
	var wordList;
	if(white__black) {
		id = 'wordlist-table-white';
		wordList = wordListWhite;
	}
	else {
		id = 'wordlist-table-black';
		wordList = wordListBlack;
	}
	var x = document.createElement('div');
	for(var p in wordList) {
		var primary = document.createElement('div');
		primary.setAttribute('class', 'primary');
		var primary_word = document.createElement('div');
		primary_word.innerText = p;
		primary.appendChild(primary_word);
		var bAny = false;
		for(var s in wordList[p]) {
			var secondary = document.createElement('div');
			secondary.setAttribute('class', 'secondary');
			var secondary_word = document.createElement('div');
			secondary_word.innerText = s;
			secondary.appendChild(secondary_word);
			var div = document.createElement('div');
			var subList = [];
			if(matchString != null)
				subList = matchWord(wordList[p][s], matchString);
			else
				subList = wordList[p][s];
			for(var i in subList) {
				div.appendChild(createWordItem(subList[i]));
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
	x.setAttribute('id', id);
	document.getElementById(id).replaceWith(x);
}
