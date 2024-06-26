<div id='message'>
	<ol class="content-list">
		<li>
			<div class="content-li-title">What is WCode?</div>
			An encoding schema to convert digital data, viz. a series of &#39;0&#39;s and &#39;1&#39;s, into literal words.
		</li>
		<li>
			<div class="content-li-title">Is it a replacement to bar-codes and QR codes?</div>
			No, at-least not always.<br>
			It is meant to be used where human beings are in the middle of a 'short' digital transmission.
		</li>
		<li>
			<div class="content-li-title">Advantage over bar-codes?</div>
			Bar-codes can not be read by humans and when the need arises:
			the alternative numeric or alphanumeric code, if present, has to be input manually which is tedious and error-prone.
		</li>
		<li>
			<div class="content-li-title">How is it different from NATO Spelling Alphabet: 'Alfa' 'Bravo' 'Charlie' ...?</div>
			Spelling Alphabet codes encode only a single character (digits included). They do not require a machine to be decoded.<br>
			Whereas a single WCode - Word encodes the binary bits that represent the message. Thus a single Word will represent more than one character.
		</li>
		<li>
			<div class="content-li-title">Output length is longer than input message?</div>
			The length is to be compared in terms of number of elements.<br>
			e.g. if <span class="content-high">9801234567</span> - is converted to - <span class="content-high">Cat Apple Mango Tomato</span><br>
			this equates to: <strong>10</strong> against <strong>4</strong>.<br>
			<br>
			Also proper Encoding format is to be selected for the Conversion.<br>
			e.g. <em>9801234567</em> is a Decimal Number and not a String.<br>
			<br>
			The Number of WCode - Words is much lesser than the number of characters in the original message.<br>
			This makes it more efficient than Spelling Alphabets like <em>'Alpha' 'Bravo' 'Charlie'</em>.<br>
			<br>
			Additionally it can also contain a checksum which will ensure correct delivery.<br>
			This may increase the length by one more word.
		</li>
	</ol>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
