<div class="modal fade bs-edit-modal-center" id="email_verification_modal" tabindex="-1" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-dialog-centered modal-lg">
    <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
            </div>
            <div class="modal-body">
                <div class="addtool">
                    <div class="row mb-2">
                        <div class="col-xxl-2">
                            <input class="form-control" type="text" value="[BUTTON_LINK]" id="buttonConstant" onclick="copySignupText()" style="width: 18%; cursor: pointer;" readonly title="Click to copy constant BUTTON LINK">
                        </div>
                    </div>
                    <form action="{{route('store.email')}}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="signupType" value="" name="type">
                        <div class="row g-3">
                            <div class="col-xxl-6">
                                <div>
                                    <label class="form-label">Email Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="Enter Email Subject" value="{{$signupEmail->subject ?? ''}}" required>
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
                                    <textarea  class="form-control summernote" name="email_description" id="" cols="30" rows="10" placeholder="Enter the email description" required>{{$signupEmail->message ?? ''}}</textarea>
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
    // to copy the otp constant
    function copySignupText(){
        let inputTag = document.getElementById("buttonConstant").select();
        document.execCommand("copy");
        alert("Button Link Constant Copied");
    }
</script>