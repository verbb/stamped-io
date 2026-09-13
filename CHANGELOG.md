# Changelog

## Unreleased

### Changed
- Updated documentation for clearer, more consistent guidance.

## 3.0.2 - 2026-09-13

### Changed
- Normalize plugin settings.

## 3.0.1 - 2025-07-18

### Changed
- Update English translations.
- Craft 5/Commerce 5 compatibility.

## 3.0.0 - 2024-05-27

### Changed
- Now requires PHP `8.2.0+`.
- Now requires Craft `5.0.0+`.
- Now requires Craft Commerce `5.0.0+`.

## 2.0.0 - 2022-05-05

### Changed
- Now requires PHP `8.0.2+`.
- Now requires Craft `4.0.0+`.
- Now requires Craft Commerce `4.0.0+`.

## 1.0.2 - 2022-07-15

### Added
- Add `productImageUrl` to payload sent to Stamped.io. (thanks @smockensturm).
- Product image fields can now be configured through plugin settings. (thanks @smockensturm).
- Add conditionals around product image fields, only if one is configured.
- Allow all settings to be set as `.env` variables.

### Changed
- Change `productImageFieldTransformation` to `productImageFieldTransform` for consistent language inline with Craft image transforms.
- Change Product Image fields in settings to use selects for pre-defined options, preventing unwanted side-effects.

### Fixed
- Fix some required plugin settings not being set as required.

## 1.0.1 - 2021-08-22

### Fixed
- Fix ISO output in payload for currency.

## 1.0.0 - 2020-09-07

- Initial release.
