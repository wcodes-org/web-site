<div id='message'>
	<h3>Type table</h3>
	<table id='data_set-table'>
		<thead>
			<tr>
				<th class='R1'>Type</th>
				<th class='R2'>ID</th>
				<th>Set</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Symbol-Set</td>
				<td>BASE_2</td>
				<td>0, 1</td>
			</tr>
			<tr>
				<td>Symbol-Set</td>
				<td>BASE_8</td>
				<td>0, 1, 2, 3, 4, 5, 6, 7</td>
			</tr>
			<tr>
				<td>Symbol-Set</td>
				<td>BASE_10</td>
				<td>0, 1, 2, 3, 4, 5, 6, 7, 8, 9</td>
			</tr>
			<tr>
				<td>Symbol-Set</td>
				<td>BASE_16</td>
				<td>0, 1, 2, 3, 4, 5, 6, 7, 8, 9, A | a, B | b, C | c, D | d, E | e, F | f</td>
			</tr>
			<tr>
				<td>Symbol-Set</td>
				<td>BASE_32</td>
				<td>A | a, B | b, C | c, D | d, E | e, F | f, G | g, H | h, I | i, J | j, K | k, L | l, M | m, N | n, O | o, P | p, Q | q, R | r, S | s, T | t, U | u, V | v, W | w, X | x, Y | y, Z | z, 2, 3, 4, 5, 6, 7</td>
			</tr>
			<tr>
				<td>Symbol-Set</td>
				<td>BASE_64</td>
				<td>a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, A, B, C, D, E, F, G, H, I, J, K, L, M, N, O, P, Q, R, S, T, U, V, W, X, Y, Z, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, /, +</td>
			</tr>
			<tr>
				<td>:Symbol-Set</td>
				<td>BASE_2#TF</td>
				<td>F | f, T | t</td>
			</tr>
			<tr>
				<td>:Symbol-Set</td>
				<td>BASE_64#URL</td>
				<td>: / &gt; _, + &gt; -</td>
			</tr>
			<tr>
				<td>:Symbol-Set</td>
				<td>BASE_32#ENG_ABT</td>
				<td>
					a, b, c, d, e, f, g, h, i, j, k, l, m, n, o, p, q, r, s, t, u, v, w, x, y, z, ^, SPACE, ., -, _, @<br>
					<table>
						<tbody>
							<tr>
								<td>^</td>
								<td>Make Cap</td>
								<td>^x</td>
								<td>X</td>
							</tr>
							<tr>
								<td></td>
								<td>Make small</td>
								<td>^^xyz^qabc</td>
								<td>XYZqAB</td>
							</tr>
							<tr>
								<td>^^</td>
								<td>Caps begin</td>
								<td>^^xyz...</td>
								<td>XYZ...</td>
							</tr>
							<tr>
								<td></td>
								<td>Caps end</td>
								<td>^^xyz^^abc</td>
								<td>XYZabc</td>
							</tr>
							<tr>
								<td>^.</td>
								<td>Toggle Case Fixed</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>^-</td>
								<td>Fixed Len Number</td>
								<td></td>
								<td></td>
							</tr>
							<tr>
								<td>^_</td>
								<td>Symbol</td>
								<td>^_a</td>
								<td></td>
							</tr>
							<tr>
								<td>^@</td>
								<td>Number</td>
								<td>^@abc</td>
								<td>924</td>
							</tr>
							<tr>
								<td>^SPACE</td>
								<td>Dictionary</td>
								<td></td>
								<td></td>
							</tr>
						</tbody>
					</table>
					<span>Extended: 0, 1, 3, 4, 5, 6, 7, 8, 9, ,, &lsquo;, &ldquo;, ?, !, #, &lt;, &gt;, *, &amp;, |, \, /, (, ), {, }, [, ], &amp;, +, =</span>
				</td>
			</tr>
			<tr>
				<td>String-Set</td>
				<td>WEB-URL</td>
				<td>http, https, ftp, com, org</td>
			</tr>
		</tbody>
	</table>
	<p>
		<strong>Operators</strong>: List-Separator [ , ], Alternate [ | ], Carry-over [ : ], Replace [ &gt; ]
	</p>
	<p>
		<strong>Custom Data Sets</strong>:
		<span class='content-low'>( draft )</span>
	</p>
	<table id='data_set_custom-table'>
		<caption>
			Example: University Serial Number: 1DB05EE057
		</caption>
		<thead>
			<tr>
				<th>ID</th>
				<th>Range</th>
				<th>Type</th>
				<th>Title</th>
				<th>Params</th>
				<th>N</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>Decimal</td>
				<td>[1, 50]</td>
				<td>Index</td>
				<td>College Code</td>
				<td>Lead-0:N</td>
				<td>50</td>
			</tr>
			<tr>
				<td>Str-Set</td>
				<td>{DB, RV, ...}</td>
				<td>Code</td>
				<td>College</td>
				<td></td>
				<td>200</td>
			</tr>
			<tr>
				<td>Decimal</td>
				<td>[0, 99]</td>
				<td>Year</td>
				<td>Admission</td>
				<td>Lead-0:Y</td>
				<td>100</td>
			</tr>
			<tr>
				<td>Str-Set</td>
				<td>{EE, EC, CS, IS, ME}</td>
				<td>Code</td>
				<td>Branch</td>
				<td></td>
				<td>5</td>
			</tr>
			<tr>
				<td>Decimal
				<td>[1, 999]</td>
				<td>Index</td>
				<td>Roll</td>
				<td>Lead-0:Y</td>
				<td>999</td>
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td colspan='6'>Total n = 50*200*100*5*999 = 4995000000 | 33 Bits</td>
			</tr>
		</tfoot>
	</table>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
