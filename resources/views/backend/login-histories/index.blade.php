@extends('layouts.app')
@section('title', 'Login History')
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Login History</h4>
                <h6>Manage your users login history</h6>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{ $dataTable->table(['class' => 'table table-sm']) }}
        </div>
    </div>
@endsection
@push('modals')
    <div class="modal fade" id="loginDetailsModal" tabindex="-1" aria-labelledby="loginDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title fw-bold" id="loginDetailsModalLabel">Login Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-2">
                    <div class="table-responsive">
                        <table class="table table-sm login-details-modal-table mb-0">
                            <tbody>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">User Name</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-user-name">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Email</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-email">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Role</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-role">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Login Time</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-login-time">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">IP Address</td>
                                    <td class="py-2 px-3 text-start font-monospace" id="login-detail-ip">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Country</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-country">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Region</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-region">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">City</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-city">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Browser</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-browser">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Operating System</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-os">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Device Type</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-device-type">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Timezone</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-timezone">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">ISP</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-isp">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Organization</td>
                                    <td class="py-2 px-3 text-start" id="login-detail-organization">—</td>
                                </tr>
                                <tr class="login-details-row border-bottom">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Referrer Host</td>
                                    <td class="py-2 px-3 text-start font-monospace" id="login-detail-referrer-host">—</td>
                                </tr>
                                <tr class="login-details-row">
                                    <td class="login-details-label bg-light text-secondary py-2 px-3 align-top">Referrer Path</td>
                                    <td class="py-2 px-3 text-start font-monospace" id="login-detail-referrer-path">—</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/js/dataTables.bootstrap5.min.js') }}"></script>
    {{ $dataTable->scripts() }}
    <script>
        (function () {
            const modalEl = document.getElementById('loginDetailsModal');
            if (!modalEl) {
                return;
            }
            const modal = window.bootstrap.Modal.getOrCreateInstance(modalEl);

            const fieldMap = [
                ['login-detail-user-name', 'user_name'],
                ['login-detail-email', 'email'],
                ['login-detail-role', 'role'],
                ['login-detail-login-time', 'login_time'],
                ['login-detail-ip', 'ip'],
                ['login-detail-country', 'country'],
                ['login-detail-region', 'region'],
                ['login-detail-city', 'city'],
                ['login-detail-browser', 'browser'],
                ['login-detail-os', 'os'],
                ['login-detail-device-type', 'device_type'],
                ['login-detail-timezone', 'timezone'],
                ['login-detail-isp', 'isp'],
                ['login-detail-organization', 'organization'],
                ['login-detail-referrer-host', 'referrer_host'],
                ['login-detail-referrer-path', 'referrer_path'],
            ];

            $(document).on('click', '.view-login-details', function (e) {
                e.preventDefault();
                const url = $(this).data('url');
                if (!url) {
                    return;
                }
                const $a = $(this);
                $a.addClass('opacity-50 pe-none');
                $.ajax({
                    url: url,
                    dataType: 'json',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        Accept: 'application/json',
                    },
                })
                    .done(function (res) {
                        if (!res || !res.data) {
                            return;
                        }
                        const d = res.data;
                        fieldMap.forEach(function (pair) {
                            const el = document.getElementById(pair[0]);
                            if (el) {
                                el.textContent = d[pair[1]] ?? '—';
                            }
                        });
                        modal.show();
                    })
                    .fail(function () {
                        alert('Could not load login details.');
                    })
                    .always(function () {
                        $a.removeClass('opacity-50 pe-none');
                    });
            });
        })();
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
    <style>
        .login-details-modal-table .login-details-label {
            width: 38%;
            white-space: nowrap;
        }

        .login-details-modal-table td {
            vertical-align: middle;
        }
    </style>
@endpush
