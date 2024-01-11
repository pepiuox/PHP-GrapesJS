<?php $changepass = new ChangePass(); ?>
<div class="container">
    <div class="row">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body ">
                <h4>Change to a new password.</h4>
                <h5>Your confirmation was sending to your email</h5>
                <p class="login-box-msg">You are only one step a way from your new password</p>
                <form method="post" class="form">
                    <div class="mb-3">
                        <label class="form-label" for="email">Confirm your email. <span class="red">*</span>:
                        </label>
                        <input type="text" name="email" id="email" class="form-control" placeholder="Confirm your email"
                            autocomplete="off" required autofocus>

                    </div>
                    <label class="form-label" for="pcassword">Enter your current password <span
                            class="red">*</span>:</label>
                    <div class="input-group mb-3">
                        <input type="password" name="cpassword" id="cpassword" class="form-control"
                            placeholder="Current Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <label class="form-label" for="recoveryphrase">Enter your Recover phrase <span
                            class="red">*</span>:</label>
                    <div class="input-group mb-3">
                        <input type="text" name="recoveryphrase" id="recoveryphrase" class="form-control"
                            placeholder="Recover phrase">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-user-lock"></span>
                            </div>
                        </div>
                    </div>
                    <label class="form-label" for="password">Enter your new password <span class="red">*</span>:</label>
                    <div class="input-group mb-3">
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="New Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <label class="form-label" for="password2">Confirm your new Password <span class="red">*</span>:
                    </label>
                    <div class="input-group mb-3">
                        <input type="password" name="password2" id="password2" class="form-control"
                            placeholder="Confirm Password">
                        <div class="input-group-append">
                            <div class="input-group-text">
                                <span class="fas fa-key"></span>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <button type="submit" name="changePassword" class="btn btn-primary btn-block">Change
                                password</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
