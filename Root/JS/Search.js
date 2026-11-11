var MAX_WORD_LENGTH = 10;
var bSearch_selected = false;

function execSearch(event) {
	var input = word_search_box.value.toLowerCase();
	if(input.length == 0) {
		document.getElementById('wordlist-message').innerText = '';
		document.getElementById('search_input_clear').classList.add('hide');
		clearWordListSearch();
	}
	else if(input[input.length-1] == ' ') {
		if(!bSearch_selected) {
			word_search_box.value = input.substring(0, input.length-1);
			word_search_box.select();
			bSearch_selected = true;
		}
		else {
			bSearch_selected = false;
			clearWordListSearch();
		}
		return;
	}
	else if(input.length > MAX_WORD_LENGTH) {
		document.getElementById('wordlist-message').innerText = "Word's length exceeds the maximum limit of " + MAX_WORD_LENGTH + " characters";
		document.getElementById('search_input_clear').classList.remove('hide');
	}
	else {
		document.getElementById('wordlist-message').innerText = '';
		document.getElementById('search_input_clear').classList.remove('hide');
		bSearch_selected = false;
		applyWordlistSearch(input);
	}
};

function matchWord(list, input) {
	var q = String(input || '').toLowerCase();
	if (!q) return [];
	return list.filter(function(word) {
		if (!word) return false;
		var w = String(word).toLowerCase();
		if (w.startsWith(q)) return true;
		if (q.length >= 2 && w.indexOf(q) !== -1) return true;
		return false;
	});
}
