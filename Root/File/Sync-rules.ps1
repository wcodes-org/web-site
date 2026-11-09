# Refreshes the committed copy of the canonical wordlist rules (wcodes-org/wordlist RULES.md).
# Run from this folder (root/File) after RULES.md changes in the wordlist repo.
$source = Join-Path $PSScriptRoot '..\..\..\..\..\..\Wordlist\Project\RULES.md'
if (Test-Path $source) {
	Copy-Item $source (Join-Path $PSScriptRoot 'Wordlist-rules.md') -Force
} else {
	Invoke-WebRequest 'https://raw.githubusercontent.com/wcodes-org/wordlist/main/RULES.md' -OutFile (Join-Path $PSScriptRoot 'Wordlist-rules.md')
}
