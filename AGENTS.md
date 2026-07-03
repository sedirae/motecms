# Agent Rules for MoteCMS

- Preserve the GNU AGPL v3.0 license.
- Keep MoteCMS server-rendered by default.
- Maintain zero public JavaScript unless strictly justified.
- Do not add external resources, CDNs, web fonts or icon libraries.
- Do not add a PHP framework or Composer dependency to the core MVP.
- Keep payload budgets small: public CSS <= 5 KiB and simple public HTML shell <= 6 KiB excluding article content.
- Do not sacrifice accessibility or security for size.
- Keep the core transport-agnostic; do not implement radio or network transports in the CMS core.
- Evaluate every new feature for byte cost and operational complexity.
- Use plain, readable PHP that a beginner can follow.
