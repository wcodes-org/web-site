<div id='message'>
	<div class='concept-lead'>
		<p class='lead-statement'>
			<strong>WCodes are readable by humans</strong> &mdash; designed to be spoken, memorized, and manually entered &mdash; while remaining precisely <strong>decodable by computers</strong>.
		</p>
		<p class='lead-subtext'>
			Instead of forcing people to deal with arbitrary numerical sequences, obscure hexadecimal strings, or error-prone alphanumeric hashes, WCode translates digital data into short sequences of common, everyday words.
		</p>
	</div>

	<div id='concept-showcase'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>Concept</span> <span class='minor'>in Action</span></h2>
		<div class='indent-f'>
			Consider relaying a 10-digit number (such as a telephone number, tracking code, or account identifier) over voice, print, or manual entry:
		</div>
		<div class='concept-card'>
			<div class='concept-card-row'>
				<div class='concept-row-meta'>
					<span class='badge-pill badge-raw'>Numeric Data</span>
					<span class='concept-token-count'>10 digits &bull; 10 spoken words</span>
				</div>
				<div class='concept-data-box code-digits'>
					9 8 0 1 2 3 4 5 6 7
				</div>
				<div class='concept-spoken-form'>
					<span class='spoken-label'>Spoken:</span> &ldquo;Nine Eight Zero One Two Three Four Five Six Seven&rdquo;
				</div>
			</div>

			<div class='concept-divider'>
				<div class='concept-divider-line'></div>
				<div class='concept-divider-badge'>encoded as WCode</div>
				<div class='concept-divider-line'></div>
			</div>

			<div class='concept-card-row'>
				<div class='concept-row-meta'>
					<span class='badge-pill badge-wcode'>WCode Representation</span>
					<span class='concept-token-count'>4 words &bull; 4 spoken words</span>
				</div>
				<div class='concept-data-box code-words'>
					\ <span>cat</span> <span>apple</span> <span>mango</span> <span>tomato</span> /
				</div>
				<div class='concept-spoken-form'>
					<span class='spoken-label'>Spoken:</span> &ldquo;Cat Apple Mango Tomato&rdquo;
				</div>
			</div>
		</div>
	</div>

	<div id='concept-comparison'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>Comparison</span> <span class='minor'>: Digits vs. Words</span></h2>
		<div class='indent-f'>
			Comparing transmission characteristics of the example above:
		</div>
		<div class='concept-table-wrap'>
			<table id='comparison-table'>
				<thead>
					<tr>
						<th scope='col'>Format</th>
						<th scope='col'>Length</th>
						<th scope='col'>Spoken Units</th>
						<th scope='col'>Cognitive Load</th>
						<th scope='col'>Error Detection</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><strong>Original Digits</strong></td>
						<td>10 digits</td>
						<td>10 words (each digit spoken individually)</td>
						<td>High &mdash; arbitrary number sequences strain short-term memory</td>
						<td>None &mdash; a single transposed or misheard digit goes undetected</td>
					</tr>
					<tr class='highlight-row'>
						<td><strong>WCode</strong></td>
						<td><strong>4 words</strong></td>
						<td><strong>4 words</strong> (60% fewer spoken words)</td>
						<td>Low &mdash; familiar concrete nouns form memorable mental images</td>
						<td><strong>Immediate</strong> &mdash; 1,024-word dictionary validation + CRC-8 checksum</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>

	<div id='concept-factors'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>Human</span> <span class='minor'>Factors</span></h2>
		<div class='indent-f'>
			The WCode system is built ground-up around human cognitive and communication bandwidth:
			<ul class='list-dash'>
				<li>
					<strong>Voice &amp; Acoustic Clarity</strong> &mdash;
					Spelling out letters (<em>&ldquo;B as in boy&rdquo;</em>, <em>&ldquo;D as in dog&rdquo;</em>, <em>&ldquo;M as in Mary&rdquo;</em>) is slow and clumsy. WCode uses 1,024 curated nouns chosen specifically to be phonetically distinct and easily distinguishable even over noisy phone calls, two-way radio, or speech recognition engines.
				</li>
				<li>
					<strong>Predictive Input &amp; Fast Keyboard Entry</strong> &mdash;
					Because every word belongs to a fixed 1,024-word dictionary, software keyboards and auto-complete engines narrow down matches after just 2 or 3 keystrokes. Entering four short words is often faster and far less stressful than typing random alphanumerics.
				</li>
				<li>
					<strong>Chunked Working Memory</strong> &mdash;
					In psychology, Miller&rsquo;s Law establishes that human working memory holds \(7 \pm 2\) chunks of information. Ten separate digits or characters exceed typical cognitive span, causing fatigue and mistakes. Four concrete nouns fit comfortably within working memory.
				</li>
				<li>
					<strong>Enclosing Delimiters</strong> &mdash;
					Every WCode begins with a backslash <span class='code-sym'>\</span> and terminates with a forward slash <span class='code-sym'>/</span>. This isolates the encoded data from surrounding sentence text, avoiding confusion with adjacent punctuation or grammar.
				</li>
			</ul>
		</div>
	</div>

	<div id='concept-math'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>Mathematics</span> <span class='minor'>of Base-1024</span></h2>
		<div class='indent-f'>
			<p>
				Computers represent information in binary (Base-2). When we group bits:
			</p>
			<ul class='list-bullet'>
				<li>A <strong>Hexadecimal</strong> symbol (Base-16) encodes \(4\) bits (\(2^4 = 16\)).</li>
				<li>A <strong>Base64</strong> character encodes \(6\) bits (\(2^6 = 64\)).</li>
				<li>A <strong>WCode word</strong> (Base-1024) encodes \(10\) bits (\(2^{10} = 1024\)).</li>
			</ul>
			<p>
				Because each word carries \(10\) bits (1.25 bytes), information density is significantly higher than standard alphanumeric characters:
			</p>
			<ul class='list-dash'>
				<li><strong>3 words</strong> = \(30\) bits &rarr; over \(1.07 \times 10^9\) (1 billion) unique values.</li>
				<li><strong>4 words</strong> = \(40\) bits &rarr; over \(1.09 \times 10^{12}\) (1.09 trillion) unique values.</li>
				<li><strong>6 words</strong> = \(60\) bits &rarr; over \(1.15 \times 10^{18}\) (1.15 quintillion) unique values.</li>
			</ul>
			<p>
				This high information density enables compact codes for geographical coordinates, identification numbers, and cryptographic checksums while remaining completely pronounceable.
			</p>
		</div>
	</div>

	<div id='concept-links'>
		<h2><span class='bullet'>&#8226;</span><span class='key'>Explore</span> <span class='minor'>Further</span></h2>
		<div class='indent-f'>
			<p>
				Learn more about how WCode is structured and implemented:
			</p>
			<ul class='list-bullet'>
				<li><a class="content-link XURL" href='/wordlist' data-target='wordlist' data-title='WordList'><strong>WordList</strong></a> &mdash; Browse the canonical list of 1,024 Base-1024 nouns.</li>
				<li><a class="content-link XURL" href='/guidelines' data-target='guidelines' data-title='Guidelines'><strong>Guidelines</strong></a> &mdash; Rules for visual print formatting, capitalization, and voice communication.</li>
				<li><a class="content-link XURL" href='/technology' data-target='technology' data-title='Technology'><strong>Technology</strong></a> &mdash; Technical details on reduction, symbols, and bit mapping.</li>
				<li><a class="content-link XURL" href='/comparison' data-target='comparison' data-title='Comparison'><strong>Comparison</strong></a> &mdash; Detailed contrast with barcodes, QR codes, and NATO phonetic alphabets.</li>
				<li><a class="content-link XURL" href='/features' data-target='features' data-title='Features'><strong>Features</strong></a> &mdash; Native checksums, compression, and structured datasets.</li>
				<li><a class="content-link XURL" href='/about' data-target='about' data-title='About'><strong>About WCode</strong></a> &mdash; Overview presentation, use cases, and applications.</li>
			</ul>
		</div>
	</div>

</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
