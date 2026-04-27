<?php

namespace App\Utility;

use App\Models\LoginHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class LoginHistoryUtility
{
    public static function logLoginHistory(Request $request)
    {
        $ip = $request->ip();
        $locationData = self::getLocationData($ip);
        $userAgent = $request->userAgent();
        $browserData = parseBrowserData($userAgent);
        $details = array_merge($locationData, $browserData, [
            'status' => 'success',
            'referrer_host' => $request->headers->get('referer') ? parse_url($request->headers->get('referer'), PHP_URL_HOST) : null,
            'referrer_path' => $request->headers->get('referer') ? parse_url($request->headers->get('referer'), PHP_URL_PATH) : null,
        ]);

        $loginHistory = new LoginHistory();
        $loginHistory->user_id = Auth::id();
        $loginHistory->ip = $ip;
        $loginHistory->date = now()->toDateString();
        $loginHistory->details = $details;
        $loginHistory->type = Auth::user()->getRoleNames()->first() ?? 'user';
        $loginHistory->created_by = Auth::id();
        $loginHistory->save();
    }

    private static function getLocationData($ip)
    {
        // For development/local IPs, return mock data
        if (
            in_array($ip, ['127.0.0.1', '::1', 'localhost']) ||
            !filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)
        ) {
            return [
                'country' => 'Local Development',
                'countryCode' => 'DEV',
                'region' => 'DEV',
                'regionName' => 'Development Environment',
                'city' => 'Localhost',
                'zip' => '00000',
                'lat' => 0,
                'lon' => 0,
                'timezone' => 'UTC',
                'isp' => 'Local ISP',
                'org' => 'Development Organization',
                'as' => 'AS0000 Local Network',
                'query' => $ip,
                'status' => 'success'
            ];
        }

        try {
            $response = Http::timeout(10)->get("http://ip-api.com/json/{$ip}?fields=status,message,country,countryCode,region,regionName,city,zip,lat,lon,timezone,isp,org,as,query");

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['status']) && $data['status'] === 'success') {
                    return [
                        'country' => $data['country'] ?? 'Unknown',
                        'countryCode' => $data['countryCode'] ?? null,
                        'region' => $data['region'] ?? null,
                        'regionName' => $data['regionName'] ?? 'Unknown',
                        'city' => $data['city'] ?? 'Unknown',
                        'zip' => $data['zip'] ?? null,
                        'lat' => $data['lat'] ?? null,
                        'lon' => $data['lon'] ?? null,
                        'timezone' => $data['timezone'] ?? null,
                        'isp' => $data['isp'] ?? null,
                        'org' => $data['org'] ?? null,
                        'as' => $data['as'] ?? null,
                        'query' => $data['query'] ?? $ip,
                        'status' => 'success'
                    ];
                }
            }
        } catch (\Exception $e) {
            Log::warning('Failed to get location data for IP: ' . $ip . ' - ' . $e->getMessage());
        }

        return [
            'country' => 'Unknown',
            'countryCode' => null,
            'region' => null,
            'regionName' => 'Unknown',
            'city' => 'Unknown',
            'zip' => null,
            'lat' => null,
            'lon' => null,
            'timezone' => null,
            'isp' => null,
            'org' => null,
            'as' => null,
            'query' => $ip,
            'status' => 'fail'
        ];
    }
}
