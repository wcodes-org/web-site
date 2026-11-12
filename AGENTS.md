# Agent instructions (wcodes-org/web-site)

The canonical site content repo. Local preview is https://wcodes.local (Apache + Caddy). Pushing `main` does not deploy wcodes.org; that requires a bake and a push to wcodes-org/web-public.

## Public-facing copy

Maintainer and developer notes must never appear in public-facing output (website copy, embedded docs, or UI text). That includes implementation details, filenames kept for compatibility, TODOs, “reasons not recorded”, toolchain commands, and instructions to the developer.

Keep those in repo docs, HTML comments, or a “Notes for maintainers” section. The /wordlist page embeds BLACKLIST.md, GREYLIST.md and RULES.md from the wordlist repo (synced into `Root/File/` with `Root/File/Sync-rules.ps1`). The embed strips `<!-- maintainer -->` comments and any “Notes for maintainers” section. Before shipping, read https://wcodes.local/wordlist and check for this kind of bleed.

The Framework submodule (`Root/Framework`) stays pinned unless it actually moved. Never bake or edit `public/` / `interim/` for source changes.
