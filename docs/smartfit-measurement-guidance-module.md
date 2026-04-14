# SmartFit Measurement Guidance Module (Anthropometric Input Standardization)

## Objective
This module standardizes anthropometric input for SmartFit so users can measure bust, waist, and hip more accurately before morphotype classification.

The implementation is aligned with practical guidance from ISO 8559-1 style measurement procedures:
- Upright and relaxed posture
- Flexible tape measure
- Tape kept horizontal and snug (not compressing body tissue)
- Light/fitted clothing during measurement
- Input values in centimeters (cm)

## Implemented Scope

### 1. Wizard-Style Measurement Interface
- Route: `GET /smartfit/input-measurements`
- View: `resources/views/smartfit/input-measurements.blade.php`
- Styles: `public/css/smartfit-measure.css`

Features:
- 4-step flow (Bust, Waist, Hip, Review)
- Inline guidance per measurement point
- Tooltip hints for common mistakes (tilted tape, wrong waist position)
- Client-side numeric/range checks before submit
- Review panel with ratio previews (B/W and H/W)

### 2. Server-Side Validation Standardization
- Request class: `app/Http/Requests/ClassifyMorphotypeRequest.php`

Validation now includes:
- Required numeric values for bust, waist, hip
- Human-range thresholds from config
- Cross-measure consistency checks to detect unrealistic values
- Clear re-measurement error messages

Config source of truth:
- `config/smartfit.php`
  - `measurement_ranges`
  - `consistency_rules`

### 3. Persistence and Output Readiness
- New model: `app/Models/BodyMeasurement.php`
- New migration: `database/migrations/2026_04_14_000003_create_body_measurements_table.php`
- New model: `app/Models/BodyMeasurementAttempt.php`
- New migration: `database/migrations/2026_04_14_000004_create_body_measurement_attempts_table.php`

Stored fields include:
- Raw measurements: bust, waist, hip
- Derived ratios: bust_to_waist_ratio, hip_to_waist_ratio
- Morphotype key + label
- Measurement standard metadata (`ISO 8559-1`)
- Source and request metadata (IP/user agent)

Attempt logging includes:
- Accepted submissions (linked to saved measurement record)
- Rejected submissions (validation reasons + consistency issue flag)

### 4. Integration with Classification and Recommendation Modules
- Controller: `app/Http/Controllers/SmartFitController.php`

Changes:
- `calculate()` now uses `MorphotypeService` and `RecommendationService`
- Measurement records are stored before redirect to result page
- Session payload now includes ratio and recommendation-focus fields for consistent output
- Web validation now uses `StoreBodyMeasurementRequest` to log rejected attempts

### 5. Result Presentation
- View: `resources/views/smartfit/result.blade.php`
- Styles: `public/css/smartfit-result.css`

Result screen shows:
- Measurement summary (cm)
- B/W and H/W ratios
- Classified body type
- Focus guidance, recommended tops/bottoms, and avoid list
- Source metadata and measurement standard

### 6. Measurement Analytics Dashboard
- Controller: `app/Http/Controllers/BodyMeasurementController.php`
- Route: `GET /smartfit/body-measurements`
- View: `resources/views/smartfit/body-measurements.blade.php`
- Styles: `public/css/smartfit-body-measurements.css`

Dashboard provides:
- Total and daily measurement volume
- Average B/W and H/W ratio values
- Morphotype distribution from saved records
- Rejection rate and consistency-rejection percentage
- Latest rejected attempts with validation reasons
- Paginated measurement records with morphotype filter

## Validation Rules Summary
Configured defaults in `config/smartfit.php`:
- Bust: 60-170 cm
- Waist: 50-160 cm
- Hip: 70-180 cm

Consistency checks:
- Reject if waist is disproportionately larger than hip
- Reject if waist is disproportionately larger than bust
- Reject if bust and hip gap is excessively large

When rejected, users are prompted to re-measure.

## Data Flow
1. User enters measurements in wizard (`/smartfit/input-measurements`)
2. Server validates range + consistency rules
3. `MorphotypeService` classifies body type using B/W and H/W ratios
4. `RecommendationService` resolves recommendation payload
5. Measurement and derived values are stored in `body_measurements`
6. Accepted/rejected attempt is logged to `body_measurement_attempts`
7. User is redirected to `/smartfit/result` with session-backed output

## Tests
- Existing API tests still pass:
  - `tests/Feature/MorphotypeClassificationTest.php`
  - `tests/Feature/RecommendationTest.php`
- Added web flow test:
  - `tests/Feature/SmartFitMeasurementFlowTest.php`

## Related Documentation
- `docs/body-measurement-dashboard.md`

Note: in environments without SQLite PDO driver, database-backed feature tests requiring migrations may fail to execute until the driver is installed.
