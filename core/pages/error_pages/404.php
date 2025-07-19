<?php
//
//  This application develop by PEPIUOX.
//  Created by : Lab eMotion
//  Author : PePiuoX
//  Email  : contact@pepiuox.net
//

$login = new UsersClass();
?>
<div class="hold-transition login-page">
   <div class="login-box">
        <div class="login-logo">
            <div class="card text-center">
                <div class="card-body login-card-body">
                    <h4 class="card-title"> This page not exist</h4>
                    <p class="card-text">You will be directed to the main page.</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
setTimeout(function(){ 
window.location.href = "<?php echo SITE_PATH; ?>"; 
}, 5 * 1000);
</script>
 
