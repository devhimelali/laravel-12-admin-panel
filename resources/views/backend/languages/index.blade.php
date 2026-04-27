@extends('layouts.app')
@section('title', 'Manage Languages')
@section('content')
    @php
        $selectedLanguage = collect($availableLanguages)->firstWhere('code', $selectedLang);
    @endphp
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Manage Languages</h4>
                <h6>Manage your application languages</h6>
            </div>
        </div>
        <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-primary" id="add-language-btn">
                <i class="ti ti-circle-plus me-1"></i>
                Add Language
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-2">
            <div class="card">
                <div class="card-body">
                    <div class="sidebar-inner slimscroll">
                        <div id="language-sidebar-menu" class="sidebar-menu language-manager-sidebar">
                            <ul class="list-unstyled mb-0">
                                <li class="submenu-open">
                                    <h6 class="submenu-hdr">Languages</h6>
                                    <ul class="list-unstyled mb-0">
                                        @foreach ($availableLanguages as $language)
                                            @php $isActive = $selectedLang === $language['code']; @endphp
                                            <li @class(['active' => $isActive])>
                                                <a href="{{ route('language.index', $language['code']) }}"
                                                    @if ($isActive) aria-current="true" @endif>
                                                    <span
                                                        class="language-nav__label d-inline-flex align-items-center gap-2 flex-wrap">
                                                        <img src="{{ asset('vendor/flags/' . strtolower($language['countryCode']) . '.svg') }}"
                                                            width="20" height="15" alt=""
                                                            class="language-nav__flag flex-shrink-0" loading="lazy"
                                                            decoding="async">
                                                        <span>{{ $language['name'] }}</span>
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-10">
            <div class="card">
                <div class="card-body">
                    <form action="{{ route('language.update', $selectedLang) }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <h5 class="fw-bold mb-3">Edit Labels for
                                    {{ $selectedLanguage['name'] ?? $selectedLang }}</h5>
                            </div>
                            @forelse ($defaultData as $key => $value)
                                <div class="col-md-4 mb-3">
                                    <label for="label-{{ Str::slug($key) }}" class="form-label">{{ $key }}</label>
                                    <input type="text" class="form-control" id="label-{{ Str::slug($key) }}"
                                        name="{{ $key }}" value="{{ e(old($key, $value ?? '')) }}">
                                </div>
                            @empty
                                <div class="col-12">
                                    <div class="text-center text-muted py-4 rounded border border-dashed">
                                        <i class="ti ti-world fs-1 d-block mb-2 opacity-50"></i>
                                        <p class="mb-1">No labels in this language file yet.</p>
                                        <p class="small mb-0">Add a <code>resources/lang/{{ $selectedLang }}.json</code>
                                            file with string keys and values, or copy from <code>en.json</code> and
                                            translate.</p>
                                    </div>
                                </div>
                            @endforelse
                            @if (count($defaultData) > 0)
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ti ti-device-floppy me-1"></i>
                                        Save Changes
                                    </button>
                                </div>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('modals')
    <div class="modal fade" id="add-language-modal" tabindex="-1" aria-labelledby="add-language-modal-title"
        aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="add-language-modal-title">Add Language</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('language.store') }}" method="POST" id="add-language-form">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="language-name" class="form-label">Language Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                    id="language-name" name="name" value="{{ old('name') }}"
                                    placeholder="e.g., French, German, Spanish" required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="language-code" class="form-label">Language Code</label>
                                <input type="text" class="form-control @error('code') is-invalid @enderror"
                                    id="language-code" name="code" value="{{ old('code') }}"
                                    placeholder="e.g., fr, de, es-bd" required maxlength="30"
                                    pattern="[a-zA-Z0-9\-]+" title="Letters, numbers, and hyphen only">
                                @error('code')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Lowercase recommended (saved lowercase). Used for the JSON file name.</div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label for="language-country-code" class="form-label">Country Code</label>
                                <input type="text"
                                    class="form-control text-uppercase @error('country_code') is-invalid @enderror"
                                    id="language-country-code" name="country_code"
                                    value="{{ old('country_code') }}" placeholder="e.g., FR, DE, BD"
                                    required maxlength="2" minlength="2"
                                    pattern="[a-zA-Z]{2}" title="ISO 3166-1 alpha-2 (two letters)">
                                @error('country_code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">Two-letter ISO code for the flag image (<code>vendor/flags/xx.svg</code>).</div>
                            </div>
                            <div class="col-md-12 mb-3">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-label mb-0" for="language-enabled">Enabled</label>
                                    <div class="form-check form-switch">
                                        <input type="hidden" name="enabled" value="0">
                                        <input class="form-check-input" type="checkbox" name="enabled"
                                            id="language-enabled" value="1"
                                            @checked(old('enabled', '1') === '1')>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="submit-add-language-btn">
                            <i class="ti ti-circle-plus me-1"></i>
                            Add Language
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script>
        $(document).ready(function() {
            @if (session('success'))
                notify('success', @json(session('success')));
            @endif
            @if (session('error'))
                notify('error', @json(session('error')));
            @endif

            $('#add-language-btn').on('click', function() {
                $('#add-language-modal').modal('show');
            });
            @if ($errors->hasAny(['name', 'code', 'country_code']))
                $('#add-language-modal').modal('show');
            @endif
        });
    </script>
@endpush
@push('styles')
    <style>
        .language-manager-sidebar .submenu-hdr {
            font-weight: 700;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.02em;
            color: #092c4c;
            margin: 0 0 0.5rem;
        }

        .language-manager-sidebar .submenu-open>ul {
            border-bottom: 1px solid #e6eaed;
            padding-bottom: 1rem;
        }

        .language-manager-sidebar .submenu-open>ul>li+li {
            margin-top: 0.25rem;
        }

        .language-manager-sidebar .submenu-open ul li a {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 0.5rem 0.75rem;
            font-weight: 500;
            font-size: 0.875rem;
            line-height: 1.3;
            color: #212b36;
            text-decoration: none;
            border-radius: 0.35rem;
            transition: background-color 0.15s ease, color 0.15s ease;
        }

        .language-manager-sidebar .submenu-open ul li a .language-nav__label,
        .language-manager-sidebar .submenu-open ul li a span {
            color: inherit;
        }

        .language-manager-sidebar .submenu-open ul li:not(.active) a:hover,
        .language-manager-sidebar .submenu-open ul li:not(.active) a:focus-visible {
            background-color: #f2f2f2;
            color: #212b36;
            outline: none;
        }

        .language-manager-sidebar .submenu-open ul li:not(.active) a:focus-visible {
            box-shadow: 0 0 0 0.2rem rgba(254, 159, 67, 0.35);
        }

        .language-manager-sidebar .submenu-open ul li.active>a {
            background: rgba(254, 159, 67, 0.12);
            color: #fe9f43;
        }

        .language-manager-sidebar .submenu-open ul li.active>a:hover,
        .language-manager-sidebar .submenu-open ul li.active>a:focus-visible {
            background: rgba(254, 159, 67, 0.18);
            color: #fe9f43;
            box-shadow: none;
        }

        .language-manager-sidebar .submenu-open ul li.active a .language-nav__label,
        .language-manager-sidebar .submenu-open ul li.active a span {
            color: #fe9f43;
            font-weight: 600;
        }
    </style>
@endpush
