
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
                                <select name="users" class="form-select mb-3 w-25 userListingFilter" id="user">
                                    <option value="">All User</option>
                                    @isset($users)
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" @if(isset($user_id) && $user_id == $user->id) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                    @endisset
                                </select>
                                <!-- <form action="{{route('listings')}}" method="POST">
                                    @csrf
                                    <div class="form-control d-flex" style="width: 24%;">
                                        
                                        <button class="btn btn-primary mx-3" type="submit">Search</button>
                                    </div>
                                </form> -->
                        </div>
                        <div class="mt-3 mt-lg-0">
                            <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="{{url('add-listings')}}" type="button" class="btn shadow-none" style="background-color: #e30b0b !important;color:#fff;"><i class="ri-add-circle-line align-middle me-1"></i> Add new listing</a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form>
                        </div>
                    </div><!-- end card header -->
                </div>
                <!--end col-->
            </div>
            <!--end row-->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-header align-items-center d-flex">
                            <h4 class="card-title mb-0 flex-grow-1">Listings</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive table-card">
                                <table class="table table-borderless table-centered align-middle table-nowrap mb-0 data-table">
                                    <thead class="text-muted table-light">
                                        <tr>
                                            {{-- <th scope="col">Company Logo</th> --}}
                                            <th scope="col">Company Name</th>
                                            <th scope="col">Listing Link</th>
                                            <th scope="col" style="text-wrap: wrap;">Category/ies</th>
                                            <th scope="col">Package</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Screenshot Image</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody id="listing_table">
                                       
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
<div class="modal fade bs-delete-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('delete.listing')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="listingId" name="listing_id" value="">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to delete this listing?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn w-sm" style="background-color: #e30b0b !important;color:#fff;" id="delete-notification">Yes, Delete It!</button>
                        </div>
                    </div>
            </form>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


<div class="modal fade bs-edit-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="addtool">
                    <form action="javascript:void(0);">
                        <div class="row g-3">
                            <div class="col-xxl-12 text-center">
                                <div>
                                    <img src="{{ URL::asset('build/images/users/avatar-4.jpg') }}" alt="" class="rounded avatar-md shadow rounded-circle">
                                    <label for="lastName" class="form-label d-block">listing Image</label>
                                    <input type="file" class="form-control" style="display: none;">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">listing Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter lastname">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">listing Url</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter lastname">
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn" style="background-color: #e30b0b !important;color:#fff;">Update listing</button>
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

{{-- <img src="https://api.screenshotone.com/take?access_key=mSNI-jbk8LZg7w&url=https://vacationrentals.tools/&viewport_width=500&viewport_height=400&device_scale_factor=1&image_quality=80&format=jpg&block_ads=true&block_cookie_banners=true&full_page=false&block_trackers=true&block_banners_by_heuristics=false&delay=0&timeout=60" alt="Hello how are YOU"> --}}
<style>
    .addtool form label{
    font-weight: 600;
}

.upload-icon {
    display: block;
    text-align: center;
    cursor: pointer;
}
.upload-icon img{
    width: 80px;
    height: 80px;
    border-radius: 50px;
}
.upload-icon span{
    display: block;
    font-size:12px;
    font-weight: 600;
}
#IconUpload{
    display: none;
}
.addtool input:focus, section.addtool input :active, section.addtool input :visited {
  -webkit-box-shadow: none;
          box-shadow: none;
  outline: none;
  border-right: 1px solid #E30B0B;
  border-color: #E30B0B;
}
</style>
@endsection
@section('script')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.js"></script>
<script>
    $(document).on("click", ".del-cat", function(){
        let id = $(this).attr("data-id");
        $("#listingId").val(id);
        $(".bs-delete-modal-center").modal("show");
    });

    $(function () {      
      loadTable();
    });

    function loadTable(data={}){
        $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        bLengthChange: false,
        bInfo: false,
        pagingType: 'full_numbers',
        "bDestroy": true,
        ajax: {
            type: "POST", 
            url:"{{ route('listing.datatable') }}",
            data: {
                _token:'{{csrf_token()}}',
                userId : data.userId,
            }
        },
        columns: [
            {data: 'company_name', name: 'company_name'},
            {data: 'listing_link', name: 'listing_link'},
            {data: 'categories', name: 'categories'},
            {data: 'package', name: 'package'},
            {data: 'status', name: 'status'},
            {data: 'screenshot_image', name: 'screenshot_image'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
      });
    }

    $(document).on("change", ".userListingFilter", function(){
        let userId = $(this).find(":selected").val();
        let data = {userId: userId,}
        loadTable(data);
    })


</script>
@endsection