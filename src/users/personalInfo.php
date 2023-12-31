<?php
$userid = $_SESSION["user_id"];
$hash = $_SESSION["hash"];
$ucode = $_SESSION["access_id"];

$respro = $uconn->prepare(
    "SELECT * FROM users_profiles p LEFT JOIN users_info i ON p.usercode = i.usercode WHERE p.idp = ? AND p.mkhash = ? "
);
$respro->bind_param("ss", $userid, $hash);
$respro->execute();
$prof = $respro->get_result();
$respro->close();
$rpro = $prof->fetch_assoc();

$ffold = $uconn->prepare(
    "SELECT folder_files FROM users_secures WHERE usercode = ? "
);
$ffold->bind_param("s", $ucode);
$ffold->execute();
$fold = $ffold->get_result();
$ffold->close();
$nfl = $fold->fetch_assoc();
$folder = $nfl['folder_files'];
$pathf = '../files/'.$folder;
$_SESSION['folderusr'] = $pathf.'/';
if( is_dir($pathf) === false )
{
    mkdir($pathf);
}

?> 
<style>
    .social{
        margin: auto 0;
        padding: 10px 20px 10px 20px;

    }
    .info{
        margin: auto 0;
        padding: 40px 30px 40px 30px;
    }
    .follow .btn {
        width: 120px;
    }

	#btn-row{
		text-align: left; 
		position: absolute;
		z-index: 900;
		top: 5px;
		right: 15px;
	}
	#bredes{
		position: absolute;
		z-index: 900;
		top: -15px;
		left: 75px;
	}
	#bimg{
		position: absolute;
		z-index: 900;
		top: 5px;
		left: 75px;
	} 
	.fa-globe:hover, 
	.fa-facebook:hover, 
	.fa-instagram:hover, 
	.fa-telegram:hover, 
	.fa-whatsapp:hover,
	.fa-tiktok:hover,
	.fa-x-twitter:hover,
	.fa-youtube:hover,
	.fa-tumblr:hover {
    color: red;
	}

#display_image{
	width: 430px;
	height: 480px;
	border: 1px solid blueviolet;
	background-position: center;
	background-size: cover;
}
@media (max-width: 991px) {
	#btn-row{
	  top: -15px;
	}
	#display_image{
	  width: 305px;
	  height: 316px;
	}
}
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-4">
            <div class="row">                   
                <div class="card mb-4">
                    <div class="card-body text-center">
                                    <button type="button" id="bimg" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#chgimg">
            Cambiar imagen
          </button>
                              <img src="<?php echo $pathf.'/'.$rpro["profile_image"]; ?>" class="card-img-top img-fluid" alt="<?php 
                                                  echo $rpro["firstname"] . " " . $rpro["lastname"]; ?>">
                              <h5 class="my-3"><?php echo $rpro["firstname"] . " " . $rpro["lastname"]; ?></h5>
                              <div class="col-12">
                              <div class="d-flex justify-content-center mb-2">
                                  <div class="col">
                                      <h5>200 K</h5>
                                      <p>Seguidores</p>
                                  <input type="submit" class="btn btn-primary" value="Seguir">
                                  </div>
                                  <div class="col">
                                      <h5>10 K</h5>
                                      <p>Siguiendo</p>
                                  <input type="submit" class="btn btn-primary" value="Siguiendo">
                                  </div>
                                  <div class="col">
                                      <h5>100 K</h5>
                                      <p>Vistas</p>
                                  <input type="submit" class="btn btn-primary" value="Vistas">
                                  </div>
                              </div>
                          </div>
                    </div>
                </div>
<?php include_once 'controls/socialmedia.php' ?>      
            </div>
        </div>
        <div class="col-lg-8">
<?php include_once 'controls/perfil.php'; ?>
            <div class="card mb-4">
		<div class="card-body">
                    <div id="btn-row">
                    <button type="button" id="btn-perfil" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#edlocal">
                      Completar información
                    </button>
                    <button type="button" id="btn-info" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#edprivacy">
                      Completar datos de privacidad
                    </button>	
                    </div>
                    <hr>
		</div>
            </div>
        </div>                      
    </div>
</div>
<?php include_once 'controls/update_info.php';?>
<?php include_once 'controls/update_social.php';?>
<?php include_once 'controls/update_profile.php';?>
<?php include_once 'controls/update_image.php';?>
<?php include_once 'controls/update_privacy.php';?>
<?php include_once 'controls/update_locations.php';?>
<button onclick="getLocation()">Try It</button>

<p id="demo"></p>

<script>
const x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
}

function showPosition(position) {
  x.innerHTML = "Latitude: " + position.coords.latitude + 
  "<br>Longitude: " + position.coords.longitude;
}
</script>
