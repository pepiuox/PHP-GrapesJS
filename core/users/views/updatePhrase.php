<?php
$upprhase = new RecoveryPhrase();
?>
<div class="container">
 <div class="row pt-2">
  <div class="card">
<div class="card-body">
 <form method="post" role="form">
  <h3 class="cnt">Change your recovery phrase? </h3>
  <hr class="colorgraph">
  <p class="">Enter your current security phrase. Keep this phrase in a safe place as you will be asked in security cases. </p>

					<div class="mb-3">
<label class="form-label" for="pin">Enter your PIN <span class="red">*</span>:
</label> <input type="password" name="pin" id="pin"
 placeholder="Enter your PIN code" class="form-control" autocomplete="off"
 required autofocus>
  </div>
  <div class="mb-3">
<label class="form-label" for="rvphrase">Current Recovery phrase <span class="red">*</span>:
</label> <input type="text" name="rvphrase" id="rvphrase"
 placeholder="Actual frase de recuperación" class="form-control" autocomplete="off"
 required autofocus>
  </div>
					<div class="mb-3">
<label class="form-label" for="urvphrase">New Recovery phrase <span class="red">*</span>:
</label> <input type="text" name="urvphrase" id="urvphrase"
 placeholder="Nueva frase de recuperación" class="form-control" autocomplete="off"
 required autofocus>
  </div>
  <div class="mb-3">
<input type="submit" name="updaterecoveryphrase"
 value="Save recovery phrase " class="btn btn-primary"/>
  </div>
 </form>
</div>
  </div>
 </div>
</div>
