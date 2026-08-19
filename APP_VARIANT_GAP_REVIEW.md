# App Variant Gap Review

Reviewed 2026-08-19.

## Compared copies

- Primary mobile: `C:\Users\user\Documents\MFM LEKKI\APP\mfmlekkiapp`
- Laragon mobile: `C:\laragon\www\ChurchAppBuild\churchmobileapp`
- Primary backend: `C:\xampp\htdocs\mfmadmin`
- Laragon backend: `C:\laragon\www\churchbackend`

## Findings

- Mobile copies contain the same 246 Dart files, but 45 differ. Key drift includes startup, SQLite, audio players, Bible screens, translations, user models, providers, and routing.
- Backend copies differ in 232 PHP paths. The primary backend has four more PHP files and newer controllers, routes, migrations, landing-page, API, and deployment changes.
- The Laragon mobile repository already contains extensive uncommitted platform, branding, feature, and dependency work. A full directory overwrite would destroy or mix that work.
- The Laragon backend uses different Git remotes and is substantially behind the primary backend architecture.

## Applied consistently

- Backend mobile-app availability setting and migration.
- Backend controls for marketplace, counseling, wellness, and partnership modules.
- Mobile dark-mode preference and app-bar toggle.
- User dashboard hide/show and rename preferences for Quick Access and Grow This Week.
- Mobile handling for backend module availability.

## Recommended consolidation

1. Commit or stash the existing Laragon mobile changes.
2. Select one canonical mobile and backend repository.
3. Reconcile the 45 mobile files feature-by-feature instead of copying directories.
4. Rebase the Laragon backend onto the canonical backend before further releases.
5. Add CI builds and migration tests for both Flutter and CodeIgniter.
