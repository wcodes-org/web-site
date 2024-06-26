<div id='message'>
	<ol class='content-list'>
		<li>
			<div class='content-li-title'>Complex Words:</div>
			A complex word like a Medicine Name will be both difficult to communicate and also will require absolute precision.<br>
			Reference:<br>
			<ul class='indent-c'>
				<li><span class='link-ref'>ISMP's</span> <a class='content-link' href="http://www.ismp.org/tools/confuseddrugnames.pdf">List of confused drug names</a></li>
				<li><span class='link-ref'>BBC:</span> <a class='content-link' href="http://news.bbc.co.uk/2/hi/health/4017509.stm">Complex drug names 'pose risk'</a></li>
			</ul>
			<table class='use-case-table'>
				<caption>Drug Names:</caption>
				<tr>
					<td><strong>Folex</strong></td>
					<td>[ <strong>5 word</strong> ]</td>
				</tr>
				<tr>
					<td><strong>Foltx</strong></td>
					<td>[ <strong>5 word</strong> ]</td>
				</tr>
				<tfoot>
					<tr>
						<td colspan='2'>Notice the close pronunciation.</td>
					</tr>
				</tfoot>
			</table>
			<br>
			Similarly it can be used to communicate even Names without any spelling ambiguity:<br>
			<em>Scenario:</em> You walk in a hotel and the receptionist asks for your name.<br>

			<table class='use-case-table'>
				<caption>Name:</caption>
				<tr>
					<td><strong>Rama Chandrasekharan</strong></td>
					<td>[ <strong>12 word</strong> ]</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Numbers:</div>
			<table class='use-case-table'>
				<caption>Telephone Number</caption>
				<tr>
					<td><strong>9801234567</strong></td>
					<td>[ <strong>4 word</strong> ]</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Substitute Codes:</div>
			<table class='use-case-table'>
				<caption>SKU Code against a bar-code</caption>
				<tr>
					<td><strong>8901088055871</strong></td>
					<td>[ <strong>6 word</strong> ] </td>
				</tr>
			</table>
			<em>Scenario:</em> At a supermarket billing counter - the barcode reader is unable to read the barcode off a particular product. Thanks to WCode instead of having to tediously input the long code adjacent to the barcode. The cashier enters the easy to read "Words" quickly and without worrying of getting it wrong.
		</li>
		<li>
			<div class='content-li-title'>Geographic Coordinates:</div>
			<em>Scenario:</em> Imagine two friends Bob and Alice. Bob is out in the desert and has a satellite phone which has no Internet. Bob is lost and dehydrated. Bob calls Alice who has a computer with internet. Bob then tells him his precise location in a WCode. Alice upon decoding this WCode gets his precise location quickly and without any possibility of error. He then finds the coordinates of a nearby pond; converts this to a WCode and reads it back to Bob. Bob patches in this WCode gets the coordinates and follows his GPS to the pond.
			<table class='use-case-table'>
				<caption>Mt. Everest's Location</caption>
				<tr>
					<td><strong>27°59'17"N 86°55'31"E</strong></td>
					<td>[ <strong>5 word</strong> ]</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Encoded Identifiers:</div>
			<em>Scenario:</em> You need to transfer a small digital identifier e.g. Youtube video ID. This is encoded as a URL variant of Base64. It can easily be done using WCode.
			<table class='use-case-table'>
				<caption>www.youtube.com/watch?v=QxhVvXqBiDc [A Talk for World Peace]</caption>
				<tr>
					<td><strong>QxhVvXqBiDc</strong></td>
					<td>[ <strong>7 word</strong> ]</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Small File Transfer:</div>
			<em>Scenario:</em> Imagine - a small registration file needs to be present to run a software. And there is no internet present. The file can be encoded in WCode and can be easily obtained at the receiving end.
			<table class='use-case-table'>
				<caption>License.reg</caption>
				<tr>
					<td><strong>00 1B DA ...</strong></td>
					<td>
						[ <strong>1 word</strong> / <strong>10 bit</strong> ]<br>
						[ <strong>~820 word</strong> / <strong>KB</strong> ]
					</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Remote Troubleshooting:</div>
			Computer commands and debug information like error codes can be easily communicated between a technical person and a nontechnical user using WCode.

			<table class='use-case-table'>
				<caption>command to get a new IP address</caption>
				<tr>
					<td><strong>ipconfig /renew</strong></td>
					<td>[ ~<strong>10 word</strong> ]</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Shortened URL:</div>
			Shortened URL identifiers can be encoded into an easy to remember WCodes.
			<table class='use-case-table'>
				<caption>URL</caption>
				<tr>
					<td><strong>https://accounts.google.com/b/0/<br>IssuedAuthSubTokens</strong></td>
					<td>
						[ temprorary: ~<strong>1 word</strong>* ]<br>
						[ permanent: <strong>1 word</strong> / <strong>10 bit</strong> ]
					</td>
				</tr>
				<tfoot>
					<tr>
						<td colspan='2'>*Depending on concurrent load.</td>
					</tr>
				</tfoot>
			</table>
			<em>Scenario:</em> Two people on the phone can quickly exchange URLs without requiring a logged-in+connected communication platform.
		</li>
		<li>
			<div class='content-li-title'>Easy Text transfer:</div>
			<em>Scenario:</em> Imagine Bob needs to transfer his bank account details to Alice. Both are far away - and both have computers.
			They are not connected through the Internet. Thus they must send it through a SMS. Also, the phone is not connected to the computers.<br>
			<br>
			Bob places his original message in the WCode Encoder application. He then manually types in the generated WCode into an SMS and sends it over to Alice.
			Upon receiving this message Alice manually transfers it to the WCode Decoder application. As it is only words, it is much simpler and thus quick, also less error prone.
			Since it includes a checksum, correct delivery is guaranteed. Which further saves time by eliminating any need to re-check or verify.
			The original message is now generated.<br>
			<br>
			The same can be performed on only voice - making it instant and without compromising accuracy and without the overhead of recheck and verify.
			<table class='use-case-table'>
				<caption>Account Details</caption>
				<tr>
					<td><strong>Bob the Builder, Bank of Cantonia<br>a/c 1234567</strong></td>
					<td>[ <strong>1 word</strong> / <strong>10 bit</strong> ]</td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Foreign Language Text transfer:</div>
			<em>Scenario:</em> Imagine Bob seeks to transfer a piece of text that is in chinese to Alice. But Bob does not know chinese. Bob can read out the WCode that encoded the chinese text to Alice. Alice upon decoding it receives the original text.

			<table class='use-case-table'>
				<caption>“Earth” in chinese</caption>
				<tr>
					<td><strong>地 球</strong></td>
					<td>[ ~<strong>3 word</strong> ]</td>
				</tr>
			</table>
			*Notice that: this is completely different from transliteration.
		</li>
		<li>
			<div class='content-li-title'>Native Language Codes:</div>
			WCodes in a native language can be used to encode any digital data; as long as an appropriate input method for the native language is available.<br>
			This makes it accessible to non-English speaking populace.
			<table class='use-case-table'>
				<caption>WCode in the nepali language</caption>
				<tr>
					<td rowspan='2'><strong>981234560</strong></td>
					<td><strong>\ Cat Apple Mango Tomato /</strong></td>
				</tr>
				<tr>
					<td><strong>\&nbsp;बिरालो&nbsp;स्याउ&nbsp;आँप&nbsp;टमाटर&nbsp;/</strong></td>
				</tr>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Logographical Codes:</div>
			Logographs can be used against individual words of a WCode. They are more readable and universal (i.e. language independent).
			<table class='use-case-table'>
				<caption>Graphical WCode</caption>
				<tr>
					<td><strong>981234560</strong></td>
					<td><img id='lg_wcode' src='/resource/lg_w_code.png' alt="Cat Apple Mango Tomato"></td>
				</tr>
			</table>
			*Feasibility depends on unique symbol for each word.<br>
			See: <a class="content-link XURL" href='/guidelines' data-target='guidelines' data-title='Guidelines'>Guidelines</a> for details on choosing logographs.
		</li>
		<li>
			<div class='content-li-title'>File Signature verification:</div>
			When a file is downloaded - the download manager can display a WCode of the file hash; this the user can verify more easily against the WCode encoded hash on the source website.
			<table class='use-case-table'>
				<caption>Open Office Win x86 EN-US - SHA1</caption>
				<tr>
					<td><strong>4550B371B749FFE74184C85F<br>5CD3BBE9C44A2F75</strong></td>
					<td>[ <strong>17 word</strong> ]</td>
				</tr>
			</table>
			Also, the resultant WCode can be used to generate a sequence of Logographs.<br>
			A change in hash will result in very different graphics which can be much easily identified.
		</li>
		<li>
			<div class='content-li-title'>Formatted Data Transfer:</div>
			<em>Scenario:</em> Imagine a pathology lab needs to communicate a blood test report to a clinic on a regular basis. Since the format of the fields is uniform and known to both the parties in advance - only the data fields need to be transferred. This makes WCode the ideal solution as it shortens the communication by a significant factor whilst making it guaranteed accurate.
			<table class='use-case-table'>
				<caption>Blood Report:</caption>
				<tbody>
				<tr>
				<td>
					<table>
						<tr>
							<th>Fields</th>
							<th>Value*</th>
							<th>Unit</th>
						</tr>
						<tr>
							<td>ID:</td>
							<td><strong>14731</strong></td>
							<td></td>
						</tr>
						<tr>
							<td>WBC count:</td>
							<td><strong>11200</strong></td>
							<td>/mm3</td>
						</tr>
						<tr>
							<td>Haemoglobin:</td>
							<td><strong>11.0</strong></td>
							<td>gm/dl</td>
						</tr>
						<tr>
							<td>ESR</td>
							<td><strong>13</strong></td>
							<td>mm/hr</td>
						</tr>
					</table>
				</td>
				<td rowspan='6' colspan='3'>
					[ ~<strong>12 Word</strong> ]
				</td>
				</tr>
				</tbody>
				<tfoot>
					<tr>
						<td colspan='2'>*Only the values (viz. in Bold) are encoded.</td>
					</tr>
				</tfoot>
			</table>
			See: <a class="content-link XURL" href='/data_set' data-target='data_set' data-title='Data set'>Data Set</a> for custom data structures support.
		</li>
		<li>
			<div class='content-li-title'>Secure Communication:</div>
			Data can be encrypted by using scrambled or custom word list or by encrypting original data with asymmetric keys and transmitted on print or audio media without compromising the data.
			<table class='use-case-table'>
				<caption>Military Command</caption>
				<tr>
					<td><strong>Attack at 0300 Hrs</strong></td>
					<td>[ ~<strong>10 word</strong>* ]</td>
				</tr>
				<tfoot>
					<tr>
						<td colspan='2'>*Intercepted WCode cannot be decoded by the rival party.</td>
					</tr>
				</tfoot>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Error Resilience:</div>
			WCodes can be made resilient to communication errors by integrating error correction techniques.
			this will result in a longer code though.
			<table class='use-case-table'>
				<caption>Urgent Code</caption>
				<tr>
					<td><strong>0x0110EFAB</strong></td>
					<td>
						[ ~m word ]<br>
						-<strong>nth word</strong> could <strong>not</strong> be read*
					</td>
				</tr>
				<tfoot>
					<tr>
						<td colspan='2'>*The original data could be decoded regardless.</td>
					</tr>
				</tfoot>
			</table>
		</li>
		<li>
			<div class='content-li-title'>Random Password:</div>
			Computer generated &ndash; high entropy, random passwords can be encoded into WCodes which are much easier to remember.
			<table class='use-case-table'>
				<caption>Generated password</caption>
				<tr>
					<td><strong>!4^8*$A</strong></td>
					<td>
						[ <strong>5 word</strong> ]
					</td>
				</tr>
			</table>
		</li>
	</ol>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
