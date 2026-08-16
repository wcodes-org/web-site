<div id='message'>
	<ul class='content-list'>
		<li>
			<span id='download-windows' class='download'>
				<a href='/wcodex86.msi' download='WCodex86.msi' class='content-link'><span class='image'><?php includeSVG('', 'windows'); ?></span></a>
			</span>
			<span>Windows ( desktop )</span>
			<div>
				<span> - version</span> <span id='download_version'>1.2</span>
			</div>
			<ul class='download-content-list-2'>
				<li>
					<span>x86 <span class='gray'>- ( download this if you are not sure )</span></span>
						<ul>
							<li>
								<a class='content-link' href='/wcodex86.msi' download='WCodex86.msi'>Installer</a>
								<span class='download-hash'>
									<span>SHA-256: <span class='download-hash-value'>4984EC7F2ADFA426D685D642D93AF8ADBFF3CFA5BE3795E26BDF6618B98B30A8</span></span>
								</span>
							</li>
							<!-- li>
								<span>Zip</span>
							</li -->
						</ul>
				</li>
				<li>
					<span>x64</span>
						<ul>
							<li>
								<a class='content-link' href='/wcodex64.msi' download='WCodex64.msi'>Installer</a>
								<span class='download-hash'>
									<span>SHA-256: <span class='download-hash-value'>C4582365F5BE2992289BC43FDEBD28062FEB25860F988A37593C79A023BDBD32</span></span>
								</span>
							</li>
							<!-- li>
								<span>Zip</span>
							</li -->
						</ul>
				</li>
			</ul>
		</li>
		<li>
			<span class='download download-android'>
				<a href='https://play.google.com/store/apps/details?id=codes.wolo.droid' target='_blank' rel='noopener' class='content-link' onclick="trackOutboundLink('wcodes-play', 'https://play.google.com/store/apps/details?id=codes.wolo.droid'); return false;"><span class='image'><?php includeSVG('', 'Google-Play-badge'); ?></span></a>
			</span>
		</li>
		<li>
			<span class='download coming-soon'>
				<span>to be announced</span>
				<a class='content-link'><span class='image'><?php includeSVG('', 'Apple-App-Store-badge'); ?></span></a>
			</span>
		</li>
	</ul>
</div>
<?php require('../HTML/Fragment/Component_bottom_nav.php') ?>
