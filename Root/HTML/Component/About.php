<div class='message'>
	<div class='description'>
		<p class='no-margin'>
		WCodes are <strong>readable by humans</strong>. To decode data though, a computer is still required.<br>
		<br>
		This makes it possible for WCodes to be:
		<ul class='list-dash'>
			<li>
				&ndash; manually entered into a computer via a keyboard.
			</li>
			<li>
				&ndash; relayed to another person over voice, print or, handwritten and electronic text-messages.
			</li>
			<li>
				&ndash; short and simple, compared to the original data.<br>
				<table id='comparision-table'>
					<caption class='indent-16'>
						As in the example above
					</caption>
					<tbody>
						<tr>
							<th>
								<em>Original:</em>
							</th>
							<td class='t-type'>
								Digits
							</td>
							<td class='t-size'>
								<strong>10</strong>
							</td>
							<td>
								&ndash;
							</td>
							<td>
								Nine
								Eight
								Zero
								One
								Two
								Three
								Four
								Five
								Six
								Seven
							</td>
						</tr>
						<tr>
							<th>
								<em>WCode:&nbsp;</em>
							</th>
							<td class='t-type'>
								Nouns
							</td>
							<td class='t-size'>
								<strong>4</strong>
							</td>
							<td>
								&ndash;
							</td>
							<td>
								Cat
								Apple
								Mango
								Tomato
							</td>
						</tr>
					</tbody>
				</table>
			</li>
			<li>
				&ndash; memorizable;<br>
				<span class='indent-32'>at least: small portions of a long code &mdash; which aids manual entry.</span>
			</li>
		</ul>
		</p>
		<p>
		For WCodes to be efficient: it is recommended that a set of 2<sup>n</sup> popular, unique and phonetically distinct nouns be used for the words of a WCode.<br>
		See: <a class="content-link XURL" href='/wordlist' data-target='wordlist' data-title='Wordlist'>WordList</a> &amp; <a class="content-link XURL" href='/guidelines' data-target='guidelines' data-title='Guidelines'>Guidelines</a>
			for details.
		In the present implementation: 1K (i.e. 1024) words are used;	as such,<br>
		<span class='indent-20'>technically, it is a form of Base-1024 (or simply: Base-K) encoding.</span>
		</p>
	</div>
</div>
<?php require('..\HTML\Fragment\Component_bottom_nav.php') ?>