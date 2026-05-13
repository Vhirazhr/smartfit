<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Throwable;

class IpGeolocationService
{
    public function resolve(Request $request): array
    {
        $ipAddress = $request->ip();
        $cloudflareCountryCode = $this->normalizeCountryCode($request->headers->get('CF-IPCountry'));

        if ($cloudflareCountryCode !== null) {
            return [
                'ip_address' => $ipAddress,
                'country_code' => $cloudflareCountryCode,
                'country_name' => $this->countryNameFromCode($cloudflareCountryCode),
                'region' => $request->headers->get('CF-Region'),
                'city' => $request->headers->get('CF-IPCity'),
            ];
        }

        if (! $this->isPublicIp((string) $ipAddress) || ! (bool) config('smartfit.analytics.geolocation.enabled', false)) {
            return $this->emptyResult($ipAddress);
        }

        return Cache::remember(
            'smartfit:ip-geolocation:'.$ipAddress,
            (int) config('smartfit.analytics.geolocation.cache_ttl', 86400),
            fn (): array => $this->lookupExternal($ipAddress)
        );
    }

    private function lookupExternal(?string $ipAddress): array
    {
        $endpoint = (string) config('smartfit.analytics.geolocation.endpoint', '');

        if ($ipAddress === null || $endpoint === '') {
            return $this->emptyResult($ipAddress);
        }

        try {
            $url = str_replace('{ip}', rawurlencode($ipAddress), $endpoint);
            $response = Http::timeout((int) config('smartfit.analytics.geolocation.timeout', 2))
                ->acceptJson()
                ->get($url);

            if (! $response->ok()) {
                return $this->emptyResult($ipAddress);
            }

            $payload = $response->json();

            if (! is_array($payload)) {
                return $this->emptyResult($ipAddress);
            }

            $countryCode = $this->normalizeCountryCode($payload['country_code'] ?? $payload['countryCode'] ?? null);

            return [
                'ip_address' => $ipAddress,
                'country_code' => $countryCode,
                'country_name' => $payload['country_name'] ?? $payload['country'] ?? $this->countryNameFromCode($countryCode),
                'region' => $payload['region'] ?? $payload['regionName'] ?? null,
                'city' => $payload['city'] ?? null,
            ];
        } catch (Throwable) {
            return $this->emptyResult($ipAddress);
        }
    }

    private function emptyResult(?string $ipAddress): array
    {
        return [
            'ip_address' => $ipAddress,
            'country_code' => null,
            'country_name' => null,
            'region' => null,
            'city' => null,
        ];
    }

    private function isPublicIp(string $ipAddress): bool
    {
        return (bool) filter_var(
            $ipAddress,
            FILTER_VALIDATE_IP,
            FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE
        );
    }

    private function normalizeCountryCode(mixed $countryCode): ?string
    {
        $normalized = strtoupper(trim((string) $countryCode));

        if (strlen($normalized) !== 2 || $normalized === 'XX') {
            return null;
        }

        return $normalized;
    }

    private function countryNameFromCode(?string $countryCode): ?string
    {
        if ($countryCode === null) {
            return null;
        }

        $countries = [
            'AU' => 'Australia',
            'BN' => 'Brunei',
            'BR' => 'Brazil',
            'CA' => 'Canada',
            'CN' => 'China',
            'DE' => 'Germany',
            'FR' => 'France',
            'GB' => 'United Kingdom',
            'ID' => 'Indonesia',
            'IN' => 'India',
            'JP' => 'Japan',
            'KR' => 'South Korea',
            'MY' => 'Malaysia',
            'NL' => 'Netherlands',
            'PH' => 'Philippines',
            'SG' => 'Singapore',
            'TH' => 'Thailand',
            'US' => 'United States',
            'VN' => 'Vietnam',
        ];

        return $countries[$countryCode] ?? $countryCode;
    }
}
