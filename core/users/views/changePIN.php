<?php $changePIN = new changePIN();
?>
<div class="container">
    <div class="row">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <h4>Change to a new PIN.</h4>
				<h5>Your new PIN was sending to your email</h5>
                <form method="post" class="form">
					<div class="mb-3">
                        <label class="form-label" for="email">Confirm your emmail. <span class="red">*</span>:
                        </label> 
                        <input type="text" name="email" id="email" class="form-control" placeholder="Confirm your email"  autocomplete="off"
                               required autofocus>

                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="recoveryphrase">Enter your recovery phrase. <span class="red">*</span>:
                        </label> 
                        <input type="text" name="recoveryphrase" id="recoveryphrase" class="form-control" placeholder="Recover phrase"  autocomplete="off"
                               required autofocus>

                    </div>     
                    <div class="mb-3">
                        <label class="form-label" for="pin">Enter your current PIN. <span class="red">*</span>:
                        </label> 
                        <input type="password" name="pin" id="pin"
                               placeholder="PIN" class="form-control" autocomplete="off"
                               required autofocus>
                    </div>
                    <div class="row">
                        <div class="input-group mb-3">

                            <button type="submit" name="changePIN" class="btn btn-primary btn-block">Change PIN</button>
                        </div>
                        <!-- /.col -->
                    </div>
                </form>
            </div>
            <!-- /.login-card-body -->
        </div>
    </div>
</div>
