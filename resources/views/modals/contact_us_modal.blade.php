<div class="modal fade bs-edit-modal-center" id="contact_us_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="addtool">
                    <div class="row mb-2" style="display: flex;">
                        <div class="col-2">
                            <input class="form-control" type="text" value="[NAME]" id="nameConstant" onclick="copyNameText()" style="width: 80%; cursor: pointer;" readonly title="Click to copy constant OTP">
                        </div>
                        <div class="col-2">
                            <input class="form-control" type="text" value="[EMAIL]" id="emailConstant" onclick="copyEmailText()" style="width: 90%; cursor: pointer;" readonly title="Click to copy constant OTP">
                        </div>
                        <div class="col-2">
                            <input class="form-control" type="text" value="[MESSAGE]" id="messageConstant" onclick="copyMessageText()" style="width: 95%; cursor: pointer;" readonly title="Click to copy constant OTP">
                        </div>
                    </div>
                    <form action="{{route('store.email')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="contactUsType" value="" name="type">
                        <div class="row g-3">
                            <div class="col-xxl-6">
                                <div>
                                    <label class="form-label">Email Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Enter Email Subject" value="{{$contactUsEmail->subject ?? ''}}" required>
                                    @error("subject")
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label class="form-label">Email Description</label>
                                    @error("subject")
                                        <span class="text-danger">{{$message}}</span>
                                    @enderror
                                    <textarea  class="form-control summernote" name="email_description" id="" cols="30" rows="10" placeholder="Enter the email description" required>{{$contactUsEmail->message ?? ''}}</textarea>
                                </div>
                            </div>
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
<script>
    // Adding these copy function here to differentiate between different Modals
    function copyNameText(){
        let inputTag = document.getElementById("nameConstant");
        inputTag.select();
        document.execCommand("copy");
        alert("Text Copied");
    }

    function copyEmailText(){
        let inputTag = document.getElementById("emailConstant");
        inputTag.select();
        document.execCommand("copy");
        alert("Text Copied");
    }

    function copyMessageText(){
        let inputTag = document.getElementById("messageConstant");
        inputTag.select();
        document.execCommand("copy");
        alert("Text Copied");
    }
</script>
