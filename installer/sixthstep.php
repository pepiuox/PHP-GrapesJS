<?php
// Fifth step
// // check if exists user in table
if (isset($_POST['verifyuser'])) {

    $newuser = new installUser();
}
// // clean admin if exists user in table
if (isset($_POST['cleanuser'])) {

    $newuser = new installUser();
}
// Create user name for admin access
if (isset($_POST['register'])) {

    $newuser = new installUser();
}
?>
<div class="alert alert-success" role="alert">
<h5>Admin registration </h5>
</div>
<div class="mb-3">
<div class="alert alert-primary text-center" role="alert">
<h3>5.- Fifth step</h3>
</div>
<h4>Remember the data entered for the user.</h4>
<div class="progress">
<div class="progress-bar" role="progressbar" style="width: 86%;" aria-valuenow="86"
aria-valuemin="0" aria-valuemax="100">86%</div>
</div>
</div>
<div class="mb-3">
<h5 class="text-danger">We recommend you check if there are users with administration
levels. </h5>
<p>1.- Verify that there are no high-level users in the installation</p>
<button class="btn btn-info" type="submit" name="verifyuser" id="verifyuser">Verify user
admin</button>
<p>2.- Deleted high-level users in the installation</p>
<button class="btn btn-danger" type="submit" name="cleanuser" id="cleanuser">Clean user
admin</button>
</div>
<div class="input-group mb-3">
<input type="text" name="firstname" id="firstname" class="form-control"
placeholder="Firstname">
<span class="input-group-text fas fa-id-card"></span>
</div>
<div class="input-group mb-3">
<input type="text" name="lastname" id="lastname" class="form-control"
placeholder="Lastname">
<span class="input-group-text far fa-id-card"></span>
</div>
<div class="input-group mb-3">
<input type="text" name="username" id="username" class="form-control"
placeholder="Username">
<span class="input-group-text fas fa-user"></span>
</div>
<div class="input-group mb-3">
<input type="email" name="email" id="email" class="form-control" placeholder="Email">
<span class="input-group-text fas fa-envelope"></span>
</div>
<div class="input-group mb-3">
<input type="password" name="password" id="password" class="form-control"
placeholder="Password">
<span class="input-group-text fas fa-lock"></span>
</div>
<div class="input-group mb-3">
<input type="password" name="password2" id="password2" class="form-control"
placeholder="Retype password">
<span class="input-group-text fas fa-lock"></span>
</div>
<div class="mb-3">
<div class="icheck-primary">
<input type="checkbox" id="agreeTerms" name="agreeTerms" value="agree">
<label for="agreeTerms">
I agree to the <a href="#">terms</a>
</label>
</div>
</div>
<!-- /.col -->
<div class="mb-3">

<button type="submit" name="register" class="btn btn-primary btn-block">Register
user</button>
</div>