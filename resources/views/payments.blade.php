
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
                            <h4 class="card-title mb-0 flex-grow-1">All Payments</h4>
                        </div><!-- end card header -->

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Subscription Id</th>
                                            <th scope="col">Amount</th>
                                            <th scope="col">Package</th>
                                            <th scope="col">Status</th>
                                            <th scope="col">Date</th>
                                            <th scope="col">User</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $i = 1;
                                        @endphp
                                        @isset($payments)
                                        @foreach($payments as $payment)
                                        @php
                                            $paymentPrice = $payment->price_id ?? '';
                                            $plan = App\Models\Plan::where('plan_id', $paymentPrice)->first();
                                        @endphp
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td>{{$payment->stripe_subscription_id}}</td>
                                                <td>{{$payment->stripe_price}}</td>
                                                <td>{{$plan->plan_type ?? ''}}</td>
                                                <td>{{$payment->payment_status}}</td>
                                                <td>{{$payment->created_at}}</td>
                                                <td>{{$payment->user->name}}</td>
                                            </tr>
                                            @php
                                                $i++
                                            @endphp
                                        @endforeach
                                        @endisset                                        
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
                                    <label for="lastName" class="form-label d-block">User Image</label>
                                    <input type="file" class="form-control" style="display: none;">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">User Name</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter Username">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">User Email</label>
                                    <input type="text" class="form-control" id="lastName" placeholder="Enter Email">
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
<script>
    $(document).on("click", ".del-cat", function(){
        let id = $(this).attr("data-id");
        $("#listingId").val(id);
        $(".bs-delete-modal-center").modal("show");
    });
    $(document).on("click", ".edit-cat", function(){
        let id = $(this).attr("data-id");
        $("#listingId").val(id);
        $(".bs-edit-modal-center").modal("show");
    });
</script>
@endsection