<div id='message'>
	<span>Based on an encoding schema, where in literal words represent digital data.</span>
	<h2><span class='bullet'>&#8226;</span>Human factor</h2>
	<div class='indent-c'>
		<ol class="content-list list-bullet">
			<li>
				<div>
					It is much easier to deal with legitimate words rather than a collection of random characters<br>
					<table class="human_factor-table">
						<tbody>
							<tr>
								<td>hdkjhg54lhjhd</td>
								<td>Apple Mango Africa</td>
							</tr>
						</tbody>
					</table>
				</div>
			</li>
			<li>
				<div>
					A set of unrelated nouns is simpler than a sequence with words from different parts of speech<br>
					<table class="human_factor-table">
						<tbody>
							<tr>
								<td>Apple Mango Bridge</td>
								<td>apple away an doctor the day keeps a</td>
							</tr>
						</tbody>
					</table>
				</div>
			</li>
		</ol>
	</div>
	<h2><span class='bullet'>&#8226;</span>Reduction</h2>
	<div id='technology-reduction'>
		The greater the number of individual symbols used - the smaller the resultant code.
		<table id="reduction-table" >
			<caption><em>Comparison</em></caption>
			<tbody>
				
				<tr>
					<th>Base</th>
					<td>Dec</td>
					<td>Bin</td>
					<td>Hex</td>
				</tr>
				<tr>
					<th>Digits</th>
					<td>10</td>
					<td>2</td>
					<td>16</td>
				</tr>
				<tr>
					<th>Symbols</th>
					<td>0 1 2 3	4 5 6 7 8 9</td>
					<td>0 1</td>
					<td>0 1 2 3 4 5 6	7 8 9 A B C D E F</td>
				</tr>
				<tr>
					<th></th>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>Ex. 1</th>
					<td>7</td>
					<td>111</td>
					<td>7</td>
				</tr>
				<tr>
					<th>Digits</th>
					<td>1</td>
					<td>3</td>
					<td>1</td>
				</tr>
				<tr>
					<th></th>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
					<td>&nbsp;</td>
				</tr>
				<tr>
					<th>Ex. 2</th>
					<td>15</td>
					<td>1111</td>
					<td>F</td>
				</tr>
				<tr>
					<th>Digits</th>
					<td>2</td>
					<td>4</td>
					<td>1</td>
				</tr>

			</tbody>
		</table>
	</div>
	
	<h2><span class='bullet'>&#8226;</span>Mapping</h2>
	<div id='technology-mapping'>
		<ul class="content-list list-bullet">
			<li><div>A sequence of bits is mapped to individual words ( e.g. <span class="content-high">1011011011 : Apple</span> )</div></li>
			<li><div>Those words are from a list of 1K (1024) carefully chosen nouns</div></li>
			<li><div>Since 2<sup>10</sup> = 1024, the length of these sequences is 10 bits</div></li>
		</ul>
		<table id="illustration-table">
			<caption><em>Illustration</em></caption>
			<tbody>
				<tr class="R1">
					<td class="C1 type">
						Data
					</td>
					<td class="data">
						<span class="blue">A</span><span class="gray">n</span> <span class="gray">Apple a day</span>
					</td>
					<td rowspan="2" class="C3">
						<div class="div1">
							<table class="map">
								<caption>ASCII</caption>
								<tbody>
									<tr>
										<td>
											<span class="blue">A</span>
										</td>
										<td>
											<span class="blue">0x41</span>
										</td>
									</tr>
									<tr>
										<td>
											<span class="gray">n</span>
										</td>
										<td>
											<span class="gray">0x6E</span>
										</td>
									</tr>
									<tr>
										<td>
											...
										</td>
										<td>
											...
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr class="R2">
					<td class="type">
						Hex
					</td>
					<td class="data">
						<span class="blue">41</span> <span class="gray">6E</span> <span class="gray">20 41 70 70 6C 65 20 61 20 64 61 79</span>
					</td>
				</tr>
				<tr class="R3">
					<td class="type">
						Binary
					</td>
					<td class="data">
						<span class="blue">0100 0001 01</span><span class="gray">11 0000 0111</span> <span class="gray">0000 0110 1100 ...</span>
					</td>
					<td rowspan="2" class="C3">
						<div class="div2">
							<table class="map">
								<caption>WCode</caption>
								<tbody>
									<tr>
										<td>
											<span class="blue">Cat</span>
										</td>
										<td>
											<span class="blue">0100000101</span>
										</td>
									</tr>
									<tr>
										<td>
											<span class="gray">Coffee</span>
										</td>
										<td>
											<span class="gray">1100000111</span>
										</td>
									</tr>
									<tr>
										<td>
											...
										</td>
										<td>
											...
										</td>
									</tr>
								</tbody>
							</table>
						</div>
					</td>
				</tr>
				<tr class="R4">
					<td class="type">
						WCode
					</td>
					<td class="data">
						<span class="blue">Cat</span> <span class="gray">Coffee</span> <span class="gray">Mango Apple ...</span>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
	
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>