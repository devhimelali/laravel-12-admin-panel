Flag SVGs are from the flag-icons project (MIT). See LICENSE-flag-icons.txt.

Usage in Laravel / Blade (ISO 3166-1 alpha-2, lowercase file names):

  <img src="{{ asset('vendor/flags/gb.svg') }}" width="20" height="15" alt="">

  $code = strtolower($countryCode); // e.g. "gb" for United Kingdom
  asset("vendor/flags/{$code}.svg")

This folder also includes non-country codes (e.g. es-ct, eu, un, xk) and
subdivisions (gb-eng, gb-sct, …) where the upstream set provides them.
