<div id='message'>
	<div class='center'>
		<img src="/resource/screen_guide.png" alt="captioned screen shot of the WCode windows GUI application" />
	</div>
	<table id="manual-table">
		<tr>
			<td class="C1">Input box:</td>
			<td>
				Depending on <strong>Mode</strong>, space for the data on which the software operates.<br>
			</td>
		</tr>
		<tr>
			<td class="C1">Mode:</td>
			<td>
				Based on the complete mode determined by <strong>Type group</strong> & <strong>Data type:</strong>
				<table class="manual-table-s A">
					<tr>
						<td class="C1">Encode</td>
						<td>The input data is converted into a WCode</td>
					</tr>
					<tr>
						<td class="C1">Decode</td>
						<td>The previously encoded data is regenerated from the input</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="C1">Type group:</td>
			<td>
				<table class="manual-table-s A">
					<tr>
						<td class="C1">Raw</td>
						<td>Raw digital data</td>
					</tr>
					<tr>
						<td class="C1">Text</td>
						<td></td>
					</tr>
					<tr>
						<td class="C1">Number</td>
						<td></td>
					</tr>
					<tr>
						<td class="C1">Object</td>
						<td>A defined collection of individual data blocks</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="C1">Data type:</td>
			<td>
				<table class="manual-table-s">
					<caption>Raw</caption>
					<tr>
						<td class="C1">Binary</td>
						<td>e.g. 101000113001</td>
					</tr>
					<tr>
						<td class="C1">Octal</td>
						<td>e.g. 7034221</td>
					</tr>
					<tr>
						<td class="C1">Decimal</td>
						<td>e.g. 9801234567</td>
					</tr>
					<tr>
						<td class="C1">Hex</td>
						<td>e.g. 0F10BAD</td>
					</tr>
					<tr>
						<td class="C1">Base32</td>
						<td>e.g. AB123Z</td>
					</tr>
					<tr>
						<td class="C1">Base64</td>
						<td>e.g. QxhVvXqBiDc</td>
					</tr>
					<tr>
						<td class="C1">File</td>
						<td>e.g. a Software license file C:\Lic.bin</td>
					</tr>
				</table>
				<table class="manual-table-s">
					<caption>Text</caption>
					<tr>
						<td class="C1">ASCII</td>
						<td>07 bit char-set</td>
					</tr>
					<tr>
						<td class="C1">ANSI</td>
						<td>08 bit char-set</td>
					</tr>
					<tr>
						<td class="C1">Unicode</td>
						<td>16 bit char-set</td>
					</tr>
				</table>
				<table class="manual-table-s">
					<caption>Number</caption>
					<tr>
						<td class="C1">Whole</td>
						<td>e.g. 123</td>
					</tr>
					<tr>
						<td class="C1">Float</td>
						<td>e.g. 0.5</td>
					</tr>
				</table>
				<table class="manual-table-s">
					<caption>Object</caption>
					<tr>
						<td class="C1">Lat-Long</td>
						<td>e.g. 27°59'17"N 86°55'31"E</td>
					</tr>
				</table>
			</td>
		</tr>
		<tr>
			<td class="C1">Checksum:</td>
			<td>Ensure data integrity</td>
		</tr>
		<tr>
			<td class="C1">Save Mode:</td>
			<td>Save current <strong>Mode</strong>, <strong>Type group</strong>, <strong>Data type</strong> & <strong>Checksum</strong> state across application sessions</td>
		</tr>
		<tr>
			<td class="C1">Splash screen:</td>
			<td>Show splash screen</td>
		</tr>
		<tr>
			<td class="C1">Execute:</td>
			<td>Perform the conversion</td>
		</tr>
		<tr>
			<td class="C1">
				Output box:<br>
				/ Suggestion list
			</td>
			<td>
				Space for the converted output<br>
				In <strong>Decode</strong> mode - Shows the suggestion list of the likely word being typed.<br>
				Press {Tab} to select the first suggestion.<br>
				Press {1} through {0} to select from first to tenth suggestion.<br>
			</td>
		</tr>
		<tr>
			<td></td>
			<td></td>
		</tr>
	</table>
	<div class="indent-a">
		<span class="note-title"><em>Note:</em></span>
		<span class="note-content">
			When inputting a WCode: <strong>\</strong> and <strong>/</strong> are not to be included and each word should be separated by a single space.<br>
			When inputting an <strong>object</strong>: enter each part into a new line.
		</span>
	</div>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>