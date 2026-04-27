<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use DebugPHP\Debug;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    const ALLOWED_LANGUAGES = ['en', 'es', 'fr', 'de', 'it', 'pt', 'ru', 'ja', 'zh', 'ar', 'hi', 'ko', 'th', 'vi', 'tr', 'pl', 'nl', 'da', 'sv', 'no', 'fi', 'cs', 'sk', 'hu', 'ro', 'bg', 'hr', 'sl', 'et', 'lv', 'lt', 'mt', 'ga', 'cy', 'eu', 'ca', 'gl', 'pt-BR', 'zh-CN', 'zh-TW', 'he'];

    public function index(Request $request, $lang = null)
    {
        $langListPath = resource_path('lang/language.json');
        $languages = [];
        if (File::exists($langListPath)) {
            $languages = json_decode(File::get($langListPath), true);
        }

        $defaultLang = 'en';
        $selectedLang = $defaultLang;
        if ($lang && collect($languages)->pluck('code')->contains($lang)) {
            $selectedLang = $lang;
        }
        Debug::send($languages, 'Languages');

        $defaultData = [];
        $langFilePath = resource_path("lang/{$selectedLang}.json");
        if (File::exists($langFilePath)) {
            $decoded = json_decode(File::get($langFilePath), true);
            $defaultData = is_array($decoded) ? $decoded : [];
        }

        Debug::send($defaultData, 'Default Data');

        $availableLanguages = collect($languages)
            ->map(function ($lang) {
                return [
                    'code' => $lang['code'],
                    'name' => $lang['name'],
                    'countryCode' => $lang['countryCode'],
                    'flag' => $this->getCountryFlag($lang['countryCode']),
                    'enabled' => $lang['enabled'] ?? true,
                ];
            })->values()->toArray();

        Debug::send($availableLanguages, 'Available Languages');

        return view('backend.languages.index', compact('availableLanguages', 'selectedLang', 'defaultData'));
    }

    public function update(Request $request, string $lang)
    {
        $langListPath = resource_path('lang/language.json');
        if (! File::exists($langListPath)) {
            abort(404);
        }

        $languages = json_decode(File::get($langListPath), true);
        if (! collect($languages)->pluck('code')->contains($lang)) {
            abort(404);
        }

        $path = resource_path("lang/{$lang}.json");
        if (! File::exists($path)) {
            abort(404);
        }

        $decoded = json_decode(File::get($path), true);
        $existing = is_array($decoded) ? $decoded : [];
        $updated = [];

        foreach (array_keys($existing) as $key) {
            if (! $request->has($key)) {
                $updated[$key] = $existing[$key];

                continue;
            }
            $value = $request->input($key);
            $updated[$key] = is_string($value) ? $value : (string) $value;
        }

        File::put($path, json_encode($updated, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT).PHP_EOL);

        return redirect()
            ->route('language.index', $lang)
            ->with('success', 'Language labels saved successfully.');
    }

    /**
     * Build a flag emoji from an ISO 3166-1 alpha-2 country code (e.g. "GB" → 🇬🇧).
     * Uses regional indicator symbols (Unicode). Display in Blade with `{{ $language['flag'] }}` (UTF-8).
     */
    private function getCountryFlag(string $countryCode): string
    {
        $countryCode = strtoupper(trim($countryCode));

        if (strlen($countryCode) !== 2 || ! ctype_alpha($countryCode)) {
            return '🌐';
        }

        // Regional indicator symbols: U+1F1E6 = 🇦 … U+1F1FF = 🇿 (Unicode TR #51).
        $base = 0x1F1E6;
        $a = ord('A');

        $flag = '';
        for ($i = 0; $i < 2; $i++) {
            $flag .= mb_chr($base + (ord($countryCode[$i]) - $a), 'UTF-8');
        }

        return $flag;
    }
}
