<div class="card mb-4">
    <div class="card-body">
	<div id="btn-row">
            <button type="button" id="btn-perfil" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#edprofile">
	Editar perfil
            </button>
            <button type="button" id="btn-info" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#edinfo">
	Editar info
            </button>	
	</div>				  
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Nombre completo</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["firstname"] . " " . $rpro["lastname"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Genero sexual</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["gender"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Edad</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["age"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Fecha nacimiento</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["birthday"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Teléfono</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["public_phone"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Email</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["public_email"]; ?></p>
              </div>
            </div>
            <hr>          
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Profesión</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["profession"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Ocupación</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["occupation"]; ?></p>
              </div>
            </div>
            <hr>
        <div class="row">
              <div class="col-sm-3">
                <p class="mb-0">Biografia</p>
              </div>
              <div class="col-sm-9">
                <p class="text-muted mb-0"><?php echo $rpro["profile_bio"]; ?></p>
              </div>
            </div>        
	</div>
</div>
