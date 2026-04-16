# Body Measurement Dashboard

## Purpose
Provide a monitoring page for SmartFit anthropometric input quality and volume.

## Route
- `GET /smartfit/body-measurements`

## Files
- Controller: `app/Http/Controllers/BodyMeasurementController.php`
- View: `resources/views/smartfit/body-measurements.blade.php`
- Styles: `public/css/smartfit-body-measurements.css`

## Data Sources
- Accepted measurement records:
  - `body_measurements`
- Measurement attempts (accepted and rejected):
  - `body_measurement_attempts`

## Metrics Displayed
- Total measurements
- Today's measurements
- Average B/W and H/W ratios
- Rejected attempt count and rejection rate
- Consistency-rejection count and percentage of rejected attempts
- Morphotype distribution
- Recent rejected attempts with validation reason details

## Filters
- Morphotype filter for measurement table (`morphotype` query param)

## Notes
- Rejected attempts are logged by `StoreBodyMeasurementRequest::failedValidation()`.
- Accepted attempts are logged in `SmartFitController::calculate()` after successful persistence.
