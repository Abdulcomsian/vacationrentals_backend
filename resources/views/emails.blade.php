
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
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Sign Up</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Contact Us</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Listing Submission</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Payment</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>Listing Aapproval</td>
                                            <td>
                                                <a href="#" class="edit-cat text-success" previewlistener="true" >
                                                    <i class="las la-pencil-alt fs-20"></i>
                                                </a>
                                            </td>
                                        </tr>
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


<div class="modal fade bs-edit-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="addtool">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-6">
                                <div>
                                    <label class="form-label">Email Subject</label>
                                    <input type="text" class="form-control" placeholder="Enter Email Subject">
                                </div>
                            </div>
                            <div class="col-xxl-6">
                                <div>
                                    <label class="form-label">Button Link</label>
                                    <input type="text" class="form-control" placeholder="Button Link Here" disabled>
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label class="form-label">Overview</label>
                                    <textarea  class="form-control" name="" id="" cols="30" rows="3">Your one time verification OTP is {OTP Here}.</textarea>
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label class="form-label">Email Description</label>
                                    <textarea  class="form-control" name="" id="" cols="30" rows="10"></textarea>
                                </div>
                            </div>
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn" style="background-color: #e30b0b !important;color:#fff;">Update Email Template</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                        <!--end row-->
                    </form>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>

@endsection
@section('script')
<script>
    $(document).on("click", ".edit-cat", function(){
        let id = $(this).attr("data-id");
        $("#listingId").val(id);
        $(".bs-edit-modal-center").modal("show");
    });
</script>
@endsection