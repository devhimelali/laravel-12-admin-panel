@extends('layouts.app')
@section('title', 'Role Permissions')
@section('content')
    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item"><a href="{{ route('roles.index') }}">Roles</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $role->display_name ?? $role->name }} — Permissions</li>
        </ol>
    </nav>

    <div class="page-header">
        <div class="add-item d-flex align-items-center">
            <div class="page-title">
                <h4 class="fw-bold mb-1">Manage Permissions</h4>
                <h6 class="text-muted mb-0">Assign access rights for <span
                        class="badge bg-primary-subtle text-primary px-2 py-1">{{ $role->display_name ?? $role->name }}</span>
                </h6>
            </div>
        </div>
        <div class="page-btn d-flex align-items-center gap-2">
            <div class="d-none d-md-flex align-items-center text-muted me-2">
                <span class="badge bg-success-subtle text-success" id="selected-count">0 selected</span>
            </div>
            <a href="{{ route('roles.index') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-left me-1"></i>
                Back
            </a>
            <button type="submit" form="permissions-form" class="btn btn-primary" id="save-permissions-btn">
                <i class="ti ti-device-floppy me-1"></i>
                Save Permissions
            </button>
        </div>
    </div>

    {{-- Quick Actions --}}
    <div class="card mb-4">
        <div class="card-body py-3">
            <div class="d-flex flex-wrap align-items-center gap-2">
                <span class="text-muted small me-2">Quick actions:</span>
                <button type="button" class="btn btn-sm btn-outline-primary" id="select-all-btn">
                    <i class="ti ti-checkbox me-1"></i>Select All
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" id="deselect-all-btn">
                    <i class="ti ti-square me-1"></i>Deselect All
                </button>
            </div>
        </div>
    </div>

    <form action="{{ route('roles.update-permissions', $role) }}" method="POST" id="permissions-form">
        @csrf
        @forelse($permissions as $groupName => $groupPermissions)
            @php
                $groupId = $groupName ? Str::slug($groupName) : 'general';
                $groupLabel = $groupName ? ucwords(str_replace(['-', '_'], ' ', $groupName)) : 'General';
                $groupIcons = [
                    'dashboard' => 'ti-layout-dashboard',
                    'users' => 'ti-users',
                    'roles' => 'ti-shield-check',
                    'settings' => 'ti-settings',
                ];
                $groupIcon = $groupIcons[$groupName] ?? 'ti-key';
            @endphp
            <div class="card permission-group mb-3 shadow-sm" id="group-card-{{ $groupId }}">
                <div class="card-header bg-transparent border-bottom py-3 d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center flex-grow-1 cursor-pointer collapse-trigger"
                        data-bs-toggle="collapse" data-bs-target="#collapse-{{ $groupId }}" aria-expanded="true">
                        <div class="rounded-circle bg-primary-subtle p-2 me-3">
                            <i class="ti {{ $groupIcon }} text-primary fs-5"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-semibold">{{ $groupLabel }}</h6>
                            <small class="text-muted">{{ $groupPermissions->count() }}
                                permission{{ $groupPermissions->count() !== 1 ? 's' : '' }}</small>
                        </div>
                        <i class="ti ti-chevron-down collapse-icon ms-auto"></i>
                    </div>
                    <div class="d-flex align-items-center gap-2 ms-3">
                        <span class="badge bg-light text-dark group-checked-count"
                            id="group-count-{{ $groupId }}">0/{{ $groupPermissions->count() }}</span>
                        <div class="form-check mb-0">
                            <input class="form-check-input group-select-all" type="checkbox" id="group-{{ $groupId }}"
                                data-group="{{ $groupId }}">
                            <label class="form-check-label small fw-medium" for="group-{{ $groupId }}">Select all</label>
                        </div>
                    </div>
                </div>
                <div class="collapse show" id="collapse-{{ $groupId }}">
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach ($groupPermissions as $permission)
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-check permission-item p-3 rounded border border-hover">
                                        <input class="form-check-input permission-checkbox" type="checkbox"
                                            name="permissions[]" value="{{ $permission->id }}"
                                            id="permission-{{ $permission->id }}" data-group="{{ $groupId }}"
                                            {{ $role->hasPermissionTo($permission) ? 'checked' : '' }}>
                                        <label class="form-check-label w-100 cursor-pointer"
                                            for="permission-{{ $permission->id }}">
                                            {{ $permission->display_name ?? ucwords(str_replace(['-', '.', '_'], ' ', $permission->name)) }}
                                        </label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="rounded-circle bg-primary-subtle d-inline-flex p-4 mb-3">
                        <i class="ti ti-key-off fs-1 text-primary"></i>
                    </div>
                    <h5 class="mb-2">No Permissions Found</h5>
                    <p class="text-muted mb-4">Run the permission seeder to populate permissions.</p>
                    <code class="d-block bg-light p-3 rounded text-start">php artisan db:seed
                        --class=PermissionSeeder</code>
                </div>
            </div>
        @endforelse
    </form>

    {{-- Sticky Save Bar (mobile) --}}
    @if ($permissions->isNotEmpty())
        <div class="d-md-none fixed-bottom bg-white border-top p-3 shadow-sm">
            <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-success-subtle text-success" id="selected-count-mobile">0 selected</span>
                <button type="submit" form="permissions-form" class="btn btn-primary" id="save-permissions-btn-mobile">
                    <i class="ti ti-device-floppy me-1"></i>Save
                </button>
            </div>
        </div>
        <div class="d-md-none" style="height: 70px;"></div>
    @endif
@endsection

@push('styles')
    <style>
        .permission-item.border-hover:hover {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), 0.04);
        }

        .permission-item:has(.permission-checkbox:checked) {
            border-color: var(--bs-primary) !important;
            background-color: rgba(var(--bs-primary-rgb), 0.08);
        }

        .cursor-pointer {
            cursor: pointer;
        }

        .collapse.show .collapse-icon {
            transform: rotate(180deg);
        }

        .collapse-icon {
            transition: transform 0.2s ease;
        }
    </style>
@endpush

@push('scripts')
    <script>
        $(document).ready(function() {
            function updateSelectedCount() {
                const total = $('.permission-checkbox').length;
                const checked = $('.permission-checkbox:checked').length;
                $('#selected-count, #selected-count-mobile').text(checked + ' of ' + total + ' selected');
            }

            function updateGroupCount(groupId) {
                const total = $(`.permission-checkbox[data-group="${groupId}"]`).length;
                const checked = $(`.permission-checkbox[data-group="${groupId}"]:checked`).length;
                $(`#group-count-${groupId}`).text(checked + '/' + total);
            }

            function updateGroupCheckbox(groupId) {
                const total = $(`.permission-checkbox[data-group="${groupId}"]`).length;
                const checked = $(`.permission-checkbox[data-group="${groupId}"]:checked`).length;
                const groupCheckbox = $(`#group-${groupId}`);
                groupCheckbox.prop('checked', total === checked);
                groupCheckbox.prop('indeterminate', checked > 0 && checked < total);
            }

            function updateAllStates() {
                updateSelectedCount();
                $('.group-select-all').each(function() {
                    const groupId = $(this).data('group');
                    updateGroupCount(groupId);
                    updateGroupCheckbox(groupId);
                });
            }

            // Select/deselect all in group
            $('.group-select-all').on('change', function(e) {
                e.stopPropagation();
                const groupId = $(this).data('group');
                $(`.permission-checkbox[data-group="${groupId}"]`).prop('checked', $(this).prop('checked'));
                updateAllStates();
            });

            // Individual permission change
            $('.permission-checkbox').on('change', function() {
                const groupId = $(this).data('group');
                updateGroupCount(groupId);
                updateGroupCheckbox(groupId);
                updateSelectedCount();
            });

            // Select all / Deselect all
            $('#select-all-btn').on('click', function() {
                $('.permission-checkbox').prop('checked', true);
                $('.group-select-all').prop('checked', true).prop('indeterminate', false);
                updateAllStates();
            });
            $('#deselect-all-btn').on('click', function() {
                $('.permission-checkbox').prop('checked', false);
                $('.group-select-all').prop('checked', false).prop('indeterminate', false);
                updateAllStates();
            });

            // Initial state
            updateAllStates();

            // Form submit
            $('#permissions-form').on('submit', function(e) {
                e.preventDefault();
                const form = this;
                const formData = new FormData(form);

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        ajaxBeforeSend('#permissions-form', '#save-permissions-btn');
                        ajaxBeforeSend('#permissions-form', '#save-permissions-btn-mobile');
                    },
                    success: function(response) {
                        notify(response.status === 'success' ? 'success' : 'error', response
                            .message);
                    },
                    error: handleAjaxErrors,
                    complete: function() {
                        ajaxComplete('#save-permissions-btn',
                            '<i class="ti ti-device-floppy me-1"></i> Save Permissions');
                        ajaxComplete('#save-permissions-btn-mobile',
                            '<i class="ti ti-device-floppy me-1"></i> Save');
                    }
                });
            });
        });
    </script>
@endpush
