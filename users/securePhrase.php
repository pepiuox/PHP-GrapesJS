<?php $recoveryphrase = new RecoveryPhrase;
?>

<div class="container">
	<div class="row pt-2">
		<div class="card">
			<div class="card-body">
				<form method="post" role="form">
					<h3 class="cnt">Create your recovery phrase? </h3>
					<hr class="colorgraph">
					<p class="">Enter your security phrase. Keep this phrase in a safe place as you will be asked in security cases. </p>
					<div class="mb-3">
						<label class="form-label" for="email">Enter your PIN <span class="red">*</span>:
						</label> <input type="password" name="pin" id="pin"
										placeholder="PIN" class="form-control" autocomplete="off"
										required autofocus>
					</div>
					<div class="mb-3">
						<label class="form-label" for="email">Recovery phrase <span class="red">*</span>:
						</label> <input type="text" name="rvphrase" id="rvphrase"
										placeholder="Frase de recuperación" class="form-control" autocomplete="off"
										required autofocus>
					</div>
					<div class="mb-3">
						<input type="submit" name="makerecoveryphrase"
							   value="Save recovery phrase " class="btn btn-primary"/>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
