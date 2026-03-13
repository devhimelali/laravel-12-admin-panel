@extends('layouts.app')
@section('title', 'Manage Users')
@section('content')
    <div class="page-header">
        <div class="add-item d-flex">
            <div class="page-title">
                <h4 class="fw-bold">Users</h4>
                <h6>Manage your users</h6>
            </div>
        </div>
        <ul class="table-top-head">
            <li>
                <select class="form-control" name="role" id="filter-role" class="form-select">
                    <option value="">All</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                    @endforeach
                </select>
            </li>
            {{-- <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Pdf" data-bs-original-title="Pdf"><img
                        src="{{ asset('assets/img/icons/pdf.svg') }}" alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Excel" data-bs-original-title="Excel"><img
                        src="{{ asset('assets/img/icons/excel.svg') }}" alt="img"></a>
            </li> --}}
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh" data-bs-original-title="Refresh"
                    id="refresh-table"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                    data-bs-original-title="Collapse" class=""><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-primary" id="add-user">
                <i class="ti ti-circle-plus me-1"></i>
                Add User
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            {{ $dataTable->table(['class' => 'table table-sm']) }}
        </div>
    </div>
@endsection
@push('modals')
    <!-- Add Or Edit User Modal -->
    <div class="modal fade show" id="add-user-modal" aria-modal="true" role="dialog" data-select2-id="add-user-modal">
        <div class="modal-dialog modal-dialog-centered" data-select2-id="12">
            <div class="modal-content" data-select2-id="11">
                <div class="page-wrapper-new p-0" data-select2-id="10">
                    <div class="content" data-select2-id="9">
                        <div class="modal-header">
                            <div class="page-title">
                                <h4 id="modal-title">Add User</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="{{ route('users.store') }}" id="user-form" data-store-url="{{ route('users.store') }}"
                            data-update-url="{{ route('users.update', ['user' => ':id']) }}">
                            <input type="hidden" name="_method" id="form-method" value="POST">
                            <input type="hidden" name="user_id" id="user-id" value="">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Name<span class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control" name="name" id="name">
                                        </div>
                                    </div>

                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email<span class="text-danger ms-1">*</span></label>
                                            <input type="email" class="form-control" name="email" id="email">
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Password<span
                                                    class="text-danger ms-1">*</span></label>
                                            <div class="pass-group">
                                                <input type="password" class="pass-input form-control" name="password"
                                                    id="password">
                                                <i class="ti ti-eye-off toggle-password"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password<span
                                                    class="text-danger ms-1">*</span></label>
                                            <div class="pass-group">
                                                <input type="password" class="pass-inputa form-control"
                                                    name="confirm_password" id="confirm_password">
                                                <i class="ti ti-eye-off toggle-passworda"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3" data-select2-id="8">
                                            <label class="form-label">Role<span class="text-danger ms-1">*</span></label>
                                            <select class="form-control" name="role" id="role">
                                                <option value="">Select</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->name }}">{{ $role->display_name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel
                                </button>
                                <button type="submit" class="btn btn-primary" id="submit-btn">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
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
            let table = $('#users-table').DataTable();
            // Refresh DataTable
            $('#refresh-table').on('click', function() {
                table.ajax.reload(null, false);
            });

            $('#filter-role').on('change', function() {
                table.ajax.url('{{ route('users.index') }}?role=' + $(this).val()).load();
            });

            $('#add-user').on('click', function() {
                $('#modal-title').text('Add User');
                $('#user-form').attr('action', $('#user-form').data('store-url'));
                $('#form-method').val('POST');
                $('#user-id').val('');
                $('#user-form')[0].reset();
                $('#password, #confirm_password').removeAttr('placeholder');
                $('#submit-btn').text('Add User');
                $('#add-user-modal').modal('show');
            });

            $('#add-user-modal').on('hidden.bs.modal', function() {
                $('#modal-title').text('Add User');
                $('#user-form').attr('action', $('#user-form').data('store-url'));
                $('#form-method').val('POST');
                $('#user-id').val('');
                $('#user-form')[0].reset();
                $('#password, #confirm_password').removeAttr('placeholder');
                $('#submit-btn').text('Add User');
            });

            $('#submit-btn').on('click', function(e) {
                e.preventDefault();
                let form = $(this).closest('form')[0];
                let formData = new FormData(form);
                let isUpdate = $('#form-method').val() === 'PUT';

                $.ajax({
                    url: $(form).attr('action'),
                    type: 'POST',
                    data: formData,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    },
                    processData: false,
                    contentType: false,
                    beforeSend: function() {
                        ajaxBeforeSend('#user-form', '#submit-btn');
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            notify(response.status, response.message);
                            $('#add-user-modal').modal('hide');
                            table.ajax.reload(null, false);
                        } else {
                            notify(response.status, response.message);
                        }
                    },
                    error: function(xhr) {
                        let msg = xhr.responseJSON?.message || (xhr.responseJSON?.errors ?
                            Object.values(xhr.responseJSON.errors).flat().join(' ') :
                            'Something went wrong');
                        notify('error', msg);
                    },
                    complete: function() {
                        ajaxComplete('#submit-btn', isUpdate ? 'Update User' : 'Add User');
                    }
                });
            });

            $(document).on('click', '.edit', function() {
                let id = $(this).data('id');
                $.get("{{ route('users.edit', ['user' => ':id']) }}".replace(':id', id), function(
                    response) {
                    let data = response.data;
                    let roleName = (data.roles && data.roles[0]) ? data.roles[0].name : '';
                    $('#modal-title').text('Edit User');
                    $('#user-form').attr('action', $('#user-form').data('update-url').replace(':id',
                        id));
                    $('#form-method').val('PUT');
                    $('#user-id').val(id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#role').val(roleName).trigger('change');
                    $('#password').val('').attr('placeholder', 'Leave blank to keep current');
                    $('#confirm_password').val('').attr('placeholder',
                        'Leave blank to keep current');
                    $('#submit-btn').text('Update User');
                    $('#add-user-modal').modal('show');
                }).fail(function(xhr) {
                    notify('error', xhr.responseJSON?.message || 'Something went wrong');
                });
            });

            $(document).on('click', '.delete', function() {
                let id = $(this).data('id');
                $('#delete-id').val(id);
                $('#delete-modal').modal('show');
            });

            $('#delete-btn').on('click', function(e) {
                e.preventDefault();
                let id = $('#delete-id').val();
                $.ajax({
                    url: '{{ route('users.destroy', ['user' => ':id']) }}'.replace(':id', id),
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
                        }
                    },
                    error: function(xhr) {
                        notify('error', xhr.responseJSON?.message || 'Something went wrong');
                    },
                    complete: function() {
                        ajaxComplete('#delete-btn', 'Delete User');
                    }
                });
            });

            $('#delete-modal').on('hidden.bs.modal', function() {
                $('#delete-id').val('');
            });
        });
    </script>
@endpush
@push('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dataTables.bootstrap5.min.css') }}">
@endpush
