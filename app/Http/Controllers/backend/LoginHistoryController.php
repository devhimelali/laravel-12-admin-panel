<?php

namespace App\Http\Controllers\backend;

use App\DataTables\LoginHistoryDataTable;
use App\Http\Controllers\Controller;
use App\Models\LoginHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class LoginHistoryController extends Controller
{
    public function index(LoginHistoryDataTable $dataTable)
    {
        return $dataTable->render('backend.login-histories.index');
    }

    /**
     * @return array<string, string>
     */
    private function formatDetailsPayload(LoginHistory $loginHistory): array
    {
        $loginHistory->loadMissing('user');
        $d = is_array($loginHistory->details) ? $loginHistory->details : [];
        $device = (string) data_get($d, 'device_type', '');

        $label = $device !== '' ? Str::ucfirst($device) : '—';

        return [
            'user_name' => (string) ($loginHistory->user?->name ?? '—'),
            'email' => (string) ($loginHistory->user?->email ?? '—'),
            'role' => (string) ($loginHistory->type ?? '—'),
            'login_time' => $loginHistory->created_at?->format('n/j/Y, g:i:s A') ?? '—',
            'ip' => (string) ($loginHistory->ip ?? '—'),
            'country' => (string) (data_get($d, 'country') ?: '—'),
            'region' => (string) (data_get($d, 'regionName') ?: '—'),
            'city' => (string) (data_get($d, 'city') ?: '—'),
            'browser' => (string) (data_get($d, 'browser_name') ?: '—'),
            'os' => (string) (data_get($d, 'os_name') ?: '—'),
            'device_type' => $label,
            'timezone' => (string) (data_get($d, 'timezone') ?: '—'),
            'isp' => (string) (data_get($d, 'isp') ?: '—'),
            'organization' => (string) (data_get($d, 'org') ?: '—'),
            'referrer_host' => (string) (data_get($d, 'referrer_host') ?: '—'),
            'referrer_path' => (string) (data_get($d, 'referrer_path') ?: '—'),
        ];
    }

    public function details(LoginHistory $loginHistory): JsonResponse
    {
        return response()->json([
            'data' => $this->formatDetailsPayload($loginHistory),
        ]);
    }
}
