
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
                        <form action="{{route('update.listing')}}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="hidden" value="{{$listingData['id']}}" name="listing_id">
                            <div class="card-header align-items-center d-flex">
                                <h4 class="card-title mb-0 flex-grow-1">Update Listing</h4>
                            </div><!-- end card header -->
    
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-xl-6 d-flex flex-column">
                                        <label for="" class="form-label">Select Company Category</label>
                                        @error('category')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <select class="form-control" name="category" id="category">
                                            <option value="">Select Category</option>
                                            @isset($categories)
                                                @foreach($categories as $category)
                                                    <option value="{{$category->id}}" {{isset($listingData['category_id']) && $listingData['category_id']==$category->id ? 'selected' : ''}}>{{$category->category_name}}</option>
                                                @endforeach
                                            @endisset
                                        </select>
                                    </div>
                                    <div class="col-xl-5 d-flex flex-column">
                                        <label for="" class="form-label">Choose Company Logo</label>
                                        @error('companyImage')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <input class="form-control" type="file" name="companyImage" value="">
                                    </div>
                                    <div class="col-xl-1 mt-4">
                                        @if(isset($listingData['company_logo']))
                                            <img src="{{asset('assets/listing_images/' . $listingData['company_logo'])}}"  class="avatar-xs rounded-circle shadow" alt="">
                                        @endif
                                    </div>
                                </div>
    
                                <div class="row mt-2">
                                    <div class="col-xl-6 d-flex flex-column">
                                        <label for="" class="form-label required">Company Name</label>
                                        @error('companyName')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <input type="text" class="form-control" name="companyName" value="{{$listingData['company_name'] ?? ''}}" placeholder="Enter company here...">
                                    </div>
    
                                    <div class="col-xl-6 d-flex flex-column">
                                        <label for="" class="form-label required">Company Tag Line</label>
                                        @error('companyTagLine')
                                            <span class="text-danger">{{$message}}</span>
                                        @enderror
                                        <input type="text" class="form-control" name="companyTagLine" value="{{$listingData['company_tagline'] ?? ''}}" placeholder="Enter company tag line here...">
                                    </div>
                                </div>
                                <div class="row mt-4">
                                    <div class="col-xl-12">
                                        <label for="" class="form-label">Add Short Description</label>
                                        
                                        <textarea name="short_description" id="editor" cols="30" rows="10">{{$listingData['short_description'] ?? ''}}</textarea>
                                    </div>
                                </div>
                                <div class="row mt-4 text-right">
                                    <div class="col-xl-12">
                                        <button type="submit" id="submitButton" class="btn btn-success">Add Listing</button>
                                    </div>
                                </div>
                            </div>
                        </form>                        
                    </div>
                </div>
            </div>
        </div>
    </div>
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
    .text-right{
        text-align: right;
    }
</style>
@endsection

@section('script')
<script>
    ClassicEditor
           .create( document.querySelector( '#editor' ) )
           .catch( error => {
               console.error( error );
           } );
</script>
@endsection