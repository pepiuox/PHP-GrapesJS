<?php $changePIN = new changePIN();
?>
<div class="container">
    <div class="row">
        <!-- /.login-logo -->
        <div class="card">
            <div class="card-body login-card-body">
                <h4>Change to a new PIN.</h4>

                <form method="post" class="form">
                    <div class="mb-3">
                        <label class="form-label" for="email">Enter your recovery phrase <span class="red">*</span>:
                        </label> 
                        <input type="text" name="recoveryphrase" id="recoveryphrase" class="form-control" placeholder="Recover phrase"  autocomplete="off"
                               required autofocus>

                    </div>     
                    <div class="mb-3">
                        <label class="form-label" for="email">Enter your old PIN <span class="red">*</span>:
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
