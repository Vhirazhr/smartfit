# Landing Measure Integration

## Purpose
Integrate body measurement input directly in the landing page and connect it to SmartFIT recommendation API.

## Implemented Files
- resources/views/landing/partials/measure-body.blade.php
- public/css/measure-body.css
- public/js/main.js (section: Measure Body Integration)

## Landing Flow
1. User enters bust, waist, and hip in landing section `#measure-body`.
2. Frontend sends POST request to `/api/recommendations`.
3. Frontend renders:
   - Morphotype label
   - Ratio values (B/W and H/W)
   - Focus, recommended tops, recommended bottoms, avoid list

## Related Entry Points
- resources/views/landing/index.blade.php includes `landing.partials.measure-body`
- resources/views/landing/partials/morphotypes.blade.php CTA links to `#measure-body`
- resources/views/layouts/app.blade.php includes `css/measure-body.css`

## Verification
- GET `/` returns HTTP 200
- POST `/api/recommendations` returns expected data payload
