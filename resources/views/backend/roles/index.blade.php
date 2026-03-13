@extends('layouts.app')
@section('title', 'Roles & Permission')
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Roles & Permission</h4>
                <h6>Manage your roles</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Pdf" data-bs-original-title="Pdf"><img
                        src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Excel" data-bs-original-title="Excel"><img
                        src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
            </li>
            <li>
                <a href="javascript:void(0);" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh"
                    data-bs-original-title="Refresh" id="refresh-table">
                    <i class="ti ti-refresh"></i>
                </a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                    data-bs-original-title="Collapse" class=""><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-primary" id="add-role">
                <i class="ti ti-circle-plus me-1"></i>
                Add Role
            </a href="javascript:void(0);">
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{ $dataTable->table(['class' => 'table table-sm']) }}
        </div>
    </div>
@endsection
@push('modals')
    <!-- Add Or Edit Role Modal -->
    <div class="modal fade show" id="add-role-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="page-title">
                        <h4>Create Role</h4>
                    </div>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <form action="{{ route('roles.store') }}" method="POST" id="role-form">
                    @csrf
                    <input type="hidden" name="_method" value="POST">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Role Name</label>
                            <input type="text" class="form-control" name="name" id="name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Display Name</label>
                            <input type="text" class="form-control" name="display_name" id="display_name">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" id="description" class="form-control"></textarea>
                        </div>
                        <div class="d-flex align-items-center justify-content-between">
                            <label class="form-label">Status</label>
                            <label class="switch">
                                <input type="checkbox" checked="" name="is_active" id="status">
                                <span class="slider round"></span>
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary" id="submit-btn">Create Role</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Delete User Modal -->
    <div class="modal fade show" id="delete-modal" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content p-5 px-3 text-center">
                        <input type="hidden" name="id" id="delete-id">
                        <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2"><i
                                class="ti ti-trash fs-24 text-danger"></i></span>
                        <h4 class="fs-20 text-gray-9 fw-bold mb-2 mt-1">Delete User</h4>
                        <p class="text-gray-6 mb-0 fs-16">Are you sure you want to delete user?</p>
                        <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                            <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none"
                                data-bs-dismiss="modal">Cancel
                            </button>
                            <button type="button" class="btn btn-primary fs-13 fw-medium p-2 px-3" id="delete-btn">Yes
                                Delete</button>
                        </div>
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
        $(document).ready(function() {
            let table = $('#role-table').DataTable();
            // Refresh DataTable
            $('#refresh-table').on('click', function() {
                table.ajax.reload(null, false);
            });

            $('#add-role').on('click', function() {
                $('#add-role-modal').modal('show');
            })

            $('#submit-btn').on('click', function(e) {
                e.preventDefault();
                let form = $(this).closest('form')[0];
                let formData = new FormData(form);

                // convert checkbox value to 1 or 0
                formData.set('is_active', $('#status').is(':checked') ? 1 : 0);

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        ajaxBeforeSend('#role-form', '#submit-btn')
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            notify(response.status, response.message);
                            $('#add-role-modal').modal('hide');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: handleAjaxErrors,
                    complete: function() {
                        ajaxComplete('#submit-btn', 'Create Role')
                    }
                });
            })

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');
                $('#delete-id').val(id);
                $('#delete-modal').modal('show');
            });

            $('#delete-btn').on('click', function(e) {
                e.preventDefault();
                let id = $('#delete-id').val();
                $.ajax({
                    url: '{{ route('roles.destroy', ['role' => ':id']) }}'.replace(':id', id),
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    beforeSend: function() {
                        ajaxBeforeSend('#delete-modal', '#delete-btn');
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            notify(response.status, response.message);
                            $('#delete-modal').modal('hide');
                            table.ajax.reload(null, false);
                        } else {
                            notify(response.status, response.message);
                        }
                    },
                    error: handleAjaxErrors,
                    complete: function() {
                        ajaxComplete('#delete-btn', 'Delete Role');
                    }
                });
            });
        })
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
@endpush
