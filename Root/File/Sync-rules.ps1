# Refreshes the committed copies of the canonical wordlist files (wcodes-org/wordlist):
#   RULES.md -> Wordlist-rules.md, Wordlist.tsv -> Wordlist.tsv (categorized list shown on /wordlist)
# Run after RULES.md or Wordlist.tsv changes in the wordlist repo. Uses the sibling checkout
# (D:\WCode\Wordlist\Project) when present, otherwise GitHub main.
$repo = Join-Path $PSScriptRoot '..\..\..\..\..\..\Wordlist\Project'
$files = @{ 'RULES.md' = 'Wordlist-rules.md'; 'Wordlist.tsv' = 'Wordlist.tsv' }
foreach ($name in $files.Keys) {
	$target = Join-Path $PSScriptRoot $files[$name]
	$source = Join-Path $repo $name
	if (Test-Path $source) {
		Copy-Item $source $target -Force
	} else {
		Invoke-WebRequest "https://raw.githubusercontent.com/wcodes-org/wordlist/main/$name" -OutFile $target
	}
}
