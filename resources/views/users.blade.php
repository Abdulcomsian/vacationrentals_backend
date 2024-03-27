
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
                                {{-- <select name="users" class="form-select mb-3 w-25" id="user">
                                    <option value="">All User</option>
                                    @isset($users)
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}" @if(isset($user_id) && $user_id == $user->id) selected @endif>{{$user->name}}</option>
                                    @endforeach
                                    @endisset
                                </select> --}}
                                <!-- <form action="{{route('listings')}}" method="POST">
                                    @csrf
                                    <div class="form-control d-flex" style="width: 24%;">
                                        
                                        <button class="btn btn-primary mx-3" type="submit">Search</button>
                                    </div>
                                </form> -->
                        </div>
                        <div class="mt-3 mt-lg-0">
                            {{-- <form action="javascript:void(0);">
                                <div class="row g-3 mb-0 align-items-center">
                                    <div class="col-auto">
                                        <a href="{{url('add-listings')}}" type="button" class="btn shadow-none" style="background-color: #e30b0b !important;color:#fff;"><i class="ri-add-circle-line align-middle me-1"></i> Add new listing</a>
                                    </div>
                                </div>
                                <!--end row-->
                            </form> --}}
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
                            <h4 class="card-title mb-0 flex-grow-1">All Users</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0 data-table">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">ID</th>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Email Verification Status</th>
                                            <th scope="col">User Status</th>
                                            <th scope="col">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
<div class="modal fade bs-delete-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('delete.user')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="userId" name="user_id" value="">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <lord-icon src="https://cdn.lordicon.com/gsqxdxog.json" trigger="loop" colors="primary:#f7b84b,secondary:#f06548" style="width:100px;height:100px"></lord-icon>
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to delete this User?</p>
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

<div class="modal fade bs-restore-modal-center" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{route('restore.user')}}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" id="restoreUserId" name="user_id" value="">
                    <div class="modal-header">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mt-2 text-center">
                            <img src="{{asset('images/system-solid-18-autorenew.gif')}}" alt="" width="100px" height="100px">
                            <div class="mt-4 pt-2 fs-15 mx-4 mx-sm-5">
                                <h4>Are you sure ?</h4>
                                <p class="text-muted mx-4 mb-0">Are you sure you want to Restore this User?</p>
                            </div>
                        </div>
                        <div class="d-flex gap-2 justify-content-center mt-4 mb-2">
                            <button type="button" class="btn w-sm btn-light" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn w-sm" style="background-color: #e30b0b !important;color:#fff;" id="delete-notification">Yes, Restore It!</button>
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
                    <form action="{{route('update.user')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="user_id" name="user_id" value="">
                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">Name</label>
                                    <input type="text" class="form-control" name="name" id="userName" placeholder="Enter Username" required>
                                    @error('name')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">Email</label>
                                    <input type="email" class="form-control" name="email" id="userEmail" placeholder="Enter Email" required>
                                    @error('email')
                                    <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn" style="background-color: #e30b0b !important;color:#fff;">Update User</button>
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
        $("#userId").val(id);
        $(".bs-delete-modal-center").modal("show");
    });
    $(document).on("click", ".edit-cat", function(){
        let id = $(this).attr("data-id");
        $("#user_id").val(id);
        $.ajax({
            method: "POST",
            url: "{{route('fetch.user.detail')}}",
            data: {
                _token: "{{csrf_token()}}",
                id: id,
            },
            success: function(res){
                let user = res.user;
                $('#userName').val(user.name);
                $('#userEmail').val(user.email);
                $(".bs-edit-modal-center").modal("show");
            },
        })
    });
    $(document).on("click", ".restore-cat", function(){
        let id = $(this).attr('data-id');
        $('#restoreUserId').val(id);
        $(".bs-restore-modal-center").modal("show");
    })

    $(function(){
        $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        searching: true,
        bLengthChange: false,
        bInfo: false,
        pagingType: 'full_numbers',
        "bDestroy": true,
        orderable:false,
        ajax: {
            type: "POST", 
            url:"{{ route('users.datatable') }}",
            data: {
                _token:'{{csrf_token()}}',
            }
        },
        columns: [
            {data: 'DT_RowIndex', orderable: false, searchable: false},
            {data: 'name', name: 'name', orderable: false},
            {data: 'email', name: 'email', orderable: false},
            {data: 'email_verification_status', name: 'email_verification_status', orderable: false},
            {data: 'user_status', name: 'user_status', orderable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
      });
    });
</script>
@endsection