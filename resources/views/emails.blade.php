
@extends('layouts.main')

@section('stylesheets')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Hind:wght@300;400;500;600;700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<link
    href="https://fonts.googleapis.com/css2?family=Ubuntu:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400;1,500;1,700&display=swap"
    rel="stylesheet">
<link rel="stylesheet" href="{{ asset('assets/css/dashboard.css') }}">
@endsection


@section('content')

@if(session('success'))
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Success! </strong>{{session('success')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif

@if(session('error'))
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <strong>Error! </strong>{{session('error')}}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
@endif
<div class="row">
    <div class="col">
        <div class="h-100">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Email Templates</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Forgot Password</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" id="forgotPassword" previewlistener="true" data-id="forgot_password">
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Sign Up</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" id="emailVerification" data-id="signup_email_verification" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Contact Us</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" id="contactUsEmail" data-id="contact_us_email" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Listing Submission</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" id="listingSubmission" data-id="listing_submission" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        {{-- <tr>
                                            <td>5</td>
                                            <td>Payment</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr> --}}
                                        {{-- <tr>
                                            <td>6</td>
                                            <td>Listing Approval</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" id="listing_approval" data-id="listing_approval" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr> --}}
                                    </tbody>
                                </table>
                                <!-- end table -->
                            </div>
                            <!-- end table responsive -->
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->
            </div> <!-- end row-->

        </div> <!-- end .h-100-->

    </div> <!-- end col -->
</div>

@include('modals.forgot_password_modal')
@include('modals.signup_email_verification')
@include('modals.contact_us_modal')
@include('modals.listing_submission_modal')
@include('modals.listing_approval')
@endsection
@section('script')
<script>
    $('.summernote').summernote({
        height: 300,
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['insert', ['ul', 'ol']]
        ]
    });

    $(document).on("click", "#forgotPassword", function(){
        let type = $(this).attr("data-id");
        $("#type").val(type);
        $("#forgot_password_modal").modal("show");
    });

    $(document).on("click", "#emailVerification", function(){
        let type = $(this).attr("data-id");
        $("#signupType").val(type);
        $("#email_verification_modal").modal("show");
    });
    
    $(document).on("click", "#contactUsEmail", function(){
        let type = $(this).attr("data-id");
        $("#contactUsType").val(type);
        $("#contact_us_modal").modal("show");
    });

    $(document).on("click", "#listingSubmission", function(){
        let type = $(this).attr("data-id");
        $("#listingSubmitType").val(type);
        $("#listing_submission_modal").modal("show");
    });

    $(document).on("click", "#listing_approval", function(){
        let type = $(this).attr("data-id");
        $("#listingApprovalType").val(type);
        $("#listing_approval_modal").modal("show");
    });
</script>
@endsection