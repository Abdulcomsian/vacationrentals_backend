
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
            <div class="row mb-3 pb-1">
                <div class="col-12">
                    <div class="d-flex align-items-lg-center flex-lg-row flex-column">
                        <div class="flex-grow-1">
                            <!-- <h4 class="fs-16 mb-1">Good Morning, Admin!</h4>
                            <p class="text-muted mb-0">Here's what's happening with your store
                                today.</p> -->
                        </div>
                        {{-- <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <button type="button" class="btn btn-danger shadow-none" data-bs-toggle="modal" data-bs-target=".bs-add-modal-center"><i class="ri-add-circle-line align-middle me-1"></i> Add new Package</button>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div> --}}
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">All Packages</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            <th scope="col">Plan Type</th>
                                            <th scope="col">Plan Name</th>
                                            <th scope="col">Discounted Price</th>
                                            <th scope="col">Recurring Price</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($plans as $plan)
                                            <tr>
                                                <td>{{$plan->plan_type}}</td>
                                                <td>{{$plan->plan_name}}</td>
                                                <td>
                                                    ${{$plan->discounted_price ?? '0'}}
                                                </td>
                                                <td>
                                                    ${{$plan->recurring_price ?? '0'}}
                                                </td>
                                                <td style="text-wrap: wrap;">
                                                   {{$plan->description}}
                                                </td>
                                                <td>
                                                    <a href="#" class="edit-cat text-success" data-id="{{$plan->id}}">
                                                        <i class="las la-pencil-alt fs-20"></i>
                                                    </a>
                                                    {{-- <a href="#" class="del-cat text-danger mx-2" data-bs-toggle="modal" data-bs-target=".bs-delete-modal-center">
                                                        <i class="lar la-trash-alt fs-20"></i>
                                                    </a> --}}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody><!-- end tbody -->
                                </table><!-- end table -->
                            </div>
                        </div>
                    </div> <!-- .card-->
                </div> <!-- .col-->
            </div> <!-- end row-->

        </div> <!-- end .h-100-->

    </div> <!-- end col -->
</div>
{{-- <div class="modal fade bs-delete-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="mt-2 text-center">
                    <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                    <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                        <h4>Are you sure ?</h4>
                        <p class="text-muted mx-4 mb-0">Are you sure you want to delete this Package?</p>
                    </div>
                </div>
                <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                    <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn w-sm" style="background-color: #e30b0b !important;color:#fff;" id="delete-notification">Yes, Delete It!</button>
                </div>
            </div>

        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div> --}}

{{-- <div class="modal fade bs-add-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="addtool">
                    <form action="#" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">Is Featured</label>
                                    <select class="form-select" aria-label="Default select example">
                                        <option selected="">Select your Status </option>
                                        <option value="1">Yes</option>
                                        <option value="2">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="price" class="form-label">Price</label>
                                    <input type="text" name="price" class="form-control" id="price" placeholder="Enter Price">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="validity" class="form-label">Validity</label>
                                    <input type="text" class="form-control" name="validity" id="lastName" placeholder="Enter lastname">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="description" class="form-label">Description</label>
                                    <textarea class="form-control" id="description" rows="3" name="description" placeholder="Enter Description"></textarea>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn" style="background-color: #e30b0b !important;color:#fff;">Add</button>
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
</div> --}}


<div class="modal fade bs-edit-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="addtool">
                    <form action="{{route('update.plan')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" name="plan_id" id="planId" value="">
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">Plan Type</label>
                                    <select class="form-select planSelect" name="plan_type" aria-label="Default select example">
                                        <option selected="">Select your Status </option>
                                        @foreach ($plans as $plan)
                                            <option value={{$plan->plan_type}}>{{$plan->plan_type}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="price" class="form-label">Discounted Price</label>
                                    <input type="text" name="plan_name" class="form-control" id="plan_name" placeholder="Enter Plan Name">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="price" class="form-label">Discounted Price</label>
                                    <input type="text" name="discounted_price" class="form-control" id="discounted_price" placeholder="Enter Price">
                                </div>
                            </div>

                            <div class="col-xxl-12">
                                <div>
                                    <label for="price" class="form-label">Recurring Price</label>
                                    <input type="text" name="recurring_price" class="form-control" id="recurring_price" placeholder="Enter Price">
                                </div>
                            </div>

                            <div class="col-xxl-12">
                                <div>
                                    <label for="description" class="form-label">Description (Should be seperated with comma)</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description list should be seperated with comma. ex . a, b, c, d"></textarea>
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn" style="background-color: #e30b0b !important;color:#fff;">Update</button>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).on("click", ".edit-cat", function(){
        let csrfToken = $('meta[name="csrf-token"]').attr('content');
        let id = $(this).attr('data-id');
        $("#planId").val(id);
        $.ajax({
            method: "POST",
            url: "{{ route('show.edit.plan') }}",
            data: { id: id, _token: csrfToken },
            dataType: 'json',
            success: function(response) {
                let plan = response.plan;
                $('#discounted_price').val(plan.discounted_price !== null ? plan.discounted_price : '0');
                $('#recurring_price').val(plan.recurring_price);
                $('#description').val(plan.description);
                $('#plan_name').val(plan.plan_name);
                $('.planSelect').val(plan.plan_type);
                $(".bs-edit-modal-center").modal("show");
            }
        });
        
    });
</script>
@endsection