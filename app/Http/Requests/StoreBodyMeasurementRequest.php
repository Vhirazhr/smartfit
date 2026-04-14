<?php

namespace App\Http\Requests;

use App\Models\BodyMeasurementAttempt;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Throwable;

class StoreBodyMeasurementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $ranges = config('smartfit.measurement_ranges', []);

        $bustMin = (float) ($ranges['bust']['min'] ?? 60);
        $bustMax = (float) ($ranges['bust']['max'] ?? 170);
        $waistMin = (float) ($ranges['waist']['min'] ?? 50);
        $waistMax = (float) ($ranges['waist']['max'] ?? 160);
        $hipMin = (float) ($ranges['hip']['min'] ?? 70);
        $hipMax = (float) ($ranges['hip']['max'] ?? 180);

        return [
            'bust' => ['required', 'numeric', 'between:'.$bustMin.','.$bustMax],
            'waist' => ['required', 'numeric', 'between:'.$waistMin.','.$waistMax],
            'hip' => ['required', 'numeric', 'between:'.$hipMin.','.$hipMax],
        ];
    }

    public function withValidator(\Illuminate\Validation\Validator $validator): void
    {
        $validator->after(function (\Illuminate\Validation\Validator $validator): void {
            $bust = (float) $this->input('bust', 0);
            $waist = (float) $this->input('waist', 0);
            $hip = (float) $this->input('hip', 0);

            if ($bust <= 0 || $waist <= 0 || $hip <= 0) {
                return;
            }

            $rules = config('smartfit.consistency_rules', []);
            $waistVsHipFactor = (float) ($rules['waist_to_hip_max_factor'] ?? 1.14);
            $waistVsBustFactor = (float) ($rules['waist_to_bust_max_factor'] ?? 1.15);
            $maxBustHipGap = (float) ($rules['max_bust_hip_gap_cm'] ?? 70);

            if ($waist > ($hip * $waistVsHipFactor)) {
                $validator->errors()->add('waist', 'Waist looks unusually larger than hip. Please re-measure with tape level and relaxed posture.');
            }

            if ($waist > ($bust * $waistVsBustFactor)) {
                $validator->errors()->add('waist', 'Waist looks unusually larger than bust. Please re-measure at your natural waistline.');
            }

            if (abs($bust - $hip) > $maxBustHipGap) {
                $validator->errors()->add('hip', 'Bust and hip difference appears extreme. Please re-check both measurements in centimeters.');
            }
        });
    }

    protected function failedValidation(Validator $validator): void
    {
        $errorMessages = $validator->errors()->all();
        $isConsistencyIssue = collect($errorMessages)->contains(function (string $message): bool {
            return str_contains(strtolower($message), 're-measure') || str_contains(strtolower($message), 'difference appears extreme');
        });

        try {
            BodyMeasurementAttempt::create([
                'bust' => $this->toNullableFloat($this->input('bust')),
                'waist' => $this->toNullableFloat($this->input('waist')),
                'hip' => $this->toNullableFloat($this->input('hip')),
                'status' => 'rejected',
                'rejection_reasons' => $errorMessages,
                'is_consistency_issue' => $isConsistencyIssue,
                'measurement_standard' => 'ISO 8559-1',
                'ip_address' => $this->ip(),
                'user_agent' => substr((string) $this->userAgent(), 0, 512),
            ]);
        } catch (Throwable) {
            // Ignore logging failure so validation error response is still returned.
        }

        parent::failedValidation($validator);
    }

    public function messages(): array
    {
        return [
            'bust.required' => 'Bust measurement is required.',
            'bust.numeric' => 'Bust must be a numeric value in centimeters (cm).',
            'bust.between' => 'Bust must be within the expected human range.',
            'waist.required' => 'Waist measurement is required.',
            'waist.numeric' => 'Waist must be a numeric value in centimeters (cm).',
            'waist.between' => 'Waist must be within the expected human range.',
            'hip.required' => 'Hip measurement is required.',
            'hip.numeric' => 'Hip must be a numeric value in centimeters (cm).',
            'hip.between' => 'Hip must be within the expected human range.',
        ];
    }

    private function toNullableFloat(mixed $value): ?float
    {
        if (!is_numeric($value)) {
            return null;
        }

        return (float) $value;
    }

}
