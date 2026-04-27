# SmartFIT Website Structure

This structure follows the SmartFIT working guideline:
- Structured architecture
- Business logic separated (backend service layer, frontend store/actions)
- Descriptive naming

## Frontend Entry
- Blade entry page: `resources/views/smartfit.blade.php`
- JS entry: `resources/js/app.js`
- Styles: `resources/css/app.css`

## Frontend Tech Stack Alignment
- Vue 3 Composition API: component `setup()` pattern in `resources/js/components/*`
- Pinia: centralized state/action flow in `resources/js/stores/useSmartfitStore.js`
- Tailwind CSS pipeline: configured via Vite and loaded in `resources/css/app.css`

## Frontend Modules
- API client: `resources/js/services/smartfitApi.js`
- Morphotype metadata: `resources/js/data/morphotypeMeta.js`
- Store: `resources/js/stores/useSmartfitStore.js`
- Components:
   - `resources/js/components/SmartfitApp.js`
   - `resources/js/components/MeasurementForm.js`
   - `resources/js/components/ResultPanel.js`
   - `resources/js/components/Avatar2DPreview.js`

## Backend Endpoints Used by UI
- `POST /api/morphotype/classify`
- `POST /api/recommendations`

## SmartFit Wizard Flow (Web)
- `GET /smartfit/start`
- `GET /smartfit/check-body-type`
- `GET /smartfit/input-measurements`
- `POST /smartfit/calculate`
- `GET /smartfit/result`
- `GET /smartfit/recommendation`
- `POST /smartfit/get-recommendation`
- `POST /smartfit/result/style-preference`
- `GET /smartfit/body-measurements`

Known-body-type flow routes:
- `GET /known/select-body-type`
- `POST /known/get-recommendation`
- `GET /known/result`

Result page interaction update:
- `resources/views/smartfit/result.blade.php` now includes selectable Style Preference cards.
- User can re-pick style directly on result page and submit `Get recommendation` while staying in `smartfit/*` flow.
- `POST /smartfit/get-recommendation` updates focus, tops, bottoms, and avoid recommendation blocks based on selected style.
- After submit, user is redirected to `GET /smartfit/recommendation` for a dedicated recommendation page (not merged into `smartfit/result`).
- If session token expires during recommendation submit, app now redirects user back to `smartfit/result` with a warning banner instead of raw 419 page.
- `resources/views/smartfit/recommendation.blade.php` displays the personalized recommendation showcase (main product, alternatives, and styling tips).
- SmartFit pages use normalized Font Awesome icon classes with a shared CDN update to avoid missing icons in result/recommendation screens.
- Footer CTA on `smartfit/result` now submits the selected style preference form directly; if no style is selected, it guides users back to the style panel.
- Known-body-type flow remains separate in `known/*` routes (`known.select`, `known.process`, `known.result`).

Core files for anthropometric input standardization:
- `resources/views/smartfit/input-measurements.blade.php`
- `public/css/smartfit-measure.css`
- `app/Http/Requests/ClassifyMorphotypeRequest.php`
- `app/Http/Controllers/SmartFitController.php`
- `app/Models/BodyMeasurement.php`
- `database/migrations/2026_04_14_000003_create_body_measurements_table.php`
- `database/migrations/2026_04_14_000004_create_body_measurement_attempts_table.php`
- `app/Http/Controllers/BodyMeasurementController.php`
- `app/Models/BodyMeasurementAttempt.php`
- `resources/views/smartfit/result.blade.php`
- `public/css/smartfit-result.css`
- `resources/views/smartfit/body-measurements.blade.php`
- `public/css/smartfit-body-measurements.css`

## Threshold Source of Truth
- Classification thresholds are centralized in `config/smartfit.php`
- Validate threshold updates with feature tests:
   - `tests/Feature/MorphotypeClassificationTest.php`
   - `tests/Feature/RecommendationTest.php`

## Route
- Web page: `GET /`

## Run Locally
1. Install PHP dependencies
   - `composer install`
2. Install JS dependencies
   - `npm install`
3. Build frontend (production assets)
   - `npm run build`
4. Serve Laravel
   - `php artisan serve`
5. Open browser
   - `http://127.0.0.1:8000`

## Dev Mode (optional)
- Terminal 1: `php artisan serve`
- Terminal 2: `npm run dev`