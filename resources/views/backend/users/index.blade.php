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
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Pdf" data-bs-original-title="Pdf"><img
                        src="{{asset('assets/img/icons/pdf.svg')}}" alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Excel"
                   data-bs-original-title="Excel"><img src="{{asset('assets/img/icons/excel.svg')}}" alt="img"></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Refresh"
                   data-bs-original-title="Refresh"><i class="ti ti-refresh"></i></a>
            </li>
            <li>
                <a data-bs-toggle="tooltip" data-bs-placement="top" id="collapse-header" aria-label="Collapse"
                   data-bs-original-title="Collapse" class=""><i class="ti ti-chevron-up"></i></a>
            </li>
        </ul>
        <div class="page-btn">
            <a href="javascript:void(0);" class="btn btn-primary" id="addUser">
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
    <div class="modal fade show" id="add-user" aria-modal="true" role="dialog" style="display: block;"
         data-select2-id="add-user">
        <div class="modal-dialog modal-dialog-centered" data-select2-id="12">
            <div class="modal-content" data-select2-id="11">
                <div class="page-wrapper-new p-0" data-select2-id="10">
                    <div class="content" data-select2-id="9">
                        <div class="modal-header">
                            <div class="page-title">
                                <h4>Add User</h4>
                            </div>
                            <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">×</span>
                            </button>
                        </div>
                        <form action="users.html">
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <div class="new-employee-field">

                                            <div class="profile-pic-upload mb-2">
                                                <div class="profile-pic">
                                                    <span><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                               viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                               stroke-width="2" stroke-linecap="round"
                                                               stroke-linejoin="round"
                                                               class="feather feather-plus-circle plus-down-add"><circle
                                                                cx="12" cy="12" r="10"></circle><line x1="12" y1="8"
                                                                                                      x2="12"
                                                                                                      y2="16"></line><line
                                                                x1="8" y1="12" x2="16"
                                                                y2="12"></line></svg>Add Image</span>
                                                </div>
                                                <div class="mb-0">
                                                    <div class="image-upload mb-0">
                                                        <input type="file">
                                                        <div class="image-uploads">
                                                            <h4>Upload Image</h4>
                                                        </div>
                                                    </div>
                                                    <p class="fs-13 mt-2">JPEG, PNG up to 2 MB</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">User<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3" data-select2-id="8">
                                            <label class="form-label">Role<span
                                                    class="text-danger ms-1">*</span></label>
                                            <select class="select select2-hidden-accessible" data-select2-id="1"
                                                    tabindex="-1" aria-hidden="true">
                                                <option data-select2-id="3">Select</option>
                                                <option data-select2-id="14">Admin</option>
                                                <option data-select2-id="15">Manager</option>
                                                <option data-select2-id="16">Salesman</option>
                                            </select><span
                                                class="select2 select2-container select2-container--default select2-container--below"
                                                dir="ltr" data-select2-id="2" style="width: 100%;"><span
                                                    class="selection"><span
                                                        class="select2-selection select2-selection--single"
                                                        role="combobox" aria-haspopup="true" aria-expanded="false"
                                                        tabindex="0" aria-disabled="false"
                                                        aria-labelledby="select2-3reo-container"><span
                                                            class="select2-selection__rendered"
                                                            id="select2-3reo-container" role="textbox"
                                                            aria-readonly="true" title="Select">Select</span><span
                                                            class="select2-selection__arrow" role="presentation"><b
                                                                role="presentation"></b></span></span></span><span
                                                    class="dropdown-wrapper" aria-hidden="true"></span></span>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Email<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="mb-3">
                                            <label class="form-label">Phone<span
                                                    class="text-danger ms-1">*</span></label>
                                            <input type="tel" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Password<span
                                                    class="text-danger ms-1">*</span></label>
                                            <div class="pass-group">
                                                <input type="password" class="pass-input form-control">
                                                <i class="ti ti-eye-off toggle-password"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="mb-3">
                                            <label class="form-label">Confirm Password<span
                                                    class="text-danger ms-1">*</span></label>
                                            <div class="pass-group">
                                                <input type="password" class="pass-inputa form-control">
                                                <i class="ti ti-eye-off toggle-passworda"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div
                                            class="status-toggle modal-status d-flex justify-content-between align-items-center">
                                            <span class="status-label">Status</span>
                                            <input type="checkbox" id="user1" class="check" checked="">
                                            <label for="user1" class="checktoggle"> </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn me-2 btn-secondary" data-bs-dismiss="modal">Cancel
                                </button>
                                <button type="submit" class="btn btn-primary">Add User</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Delete User Modal -->
    <div class="modal fade show" id="delete-modal" aria-modal="true" role="dialog" style="display: block;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="page-wrapper-new p-0">
                    <div class="content p-5 px-3 text-center">
                        <span class="rounded-circle d-inline-flex p-2 bg-danger-transparent mb-2"><i
                                class="ti ti-trash fs-24 text-danger"></i></span>
                        <h4 class="fs-20 text-gray-9 fw-bold mb-2 mt-1">Delete User</h4>
                        <p class="text-gray-6 mb-0 fs-16">Are you sure you want to delete user?</p>
                        <div class="modal-footer-btn mt-3 d-flex justify-content-center">
                            <button type="button" class="btn me-2 btn-secondary fs-13 fw-medium p-2 px-3 shadow-none"
                                    data-bs-dismiss="modal">Cancel
                            </button>
                            <button type="submit" class="btn btn-primary fs-13 fw-medium p-2 px-3">Yes Delete</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
@push('scripts')
    <script src="{{asset('assets/js/jquery.dataTables.min.js')}}"></script>
    <script src="{{asset('assets/js/dataTables.bootstrap5.min.js')}}"></script>
    {{ $dataTable->scripts() }}
@endpush
@push('styles')
    <link rel="stylesheet" href="{{asset('assets/css/dataTables.bootstrap5.min.css')}}">
@endpush
