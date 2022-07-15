# Changelog

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
