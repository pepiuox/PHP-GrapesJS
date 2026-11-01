<?php
// menu
function getLink($mid)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM pages WHERE id=?");
    $stmt->execute([$mid]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return "builder.php?id=" . $row["id"];
}

function dropdown()
{
    global $conn;
    $active = 1;
    $parent = 0;
    $stmt = $conn->prepare("SELECT * FROM pages WHERE active=?");
    $stmt->execute([$active]);
    while ($mrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $parents[] = $mrow["parent"];
    }
    $stmt->closeCursor();
    $stmt1 = $conn->prepare(
        "SELECT id, title, link, type, parent FROM pages WHERE parent=? AND active=?"
    );
    $stmt1->execute([$parent, $active]);
    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        
        $mid = $row["id"];
        $plink = SITE_PATH . $row["link"];
        if($row["type"]==='File'){
            continue;
        }

        if (in_array($row["id"], $parents)) {
            echo '<li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" data-bs-toggle="dropdown" data-bs-auto-close="outside" href="' .
                $plink .
                '">' .
                $row["title"] .
                "</a>" .
                "\n";
            echo second($mid, $plink);
            echo "</li>" . "\n";
        } else {
            echo '<li class="nav-item"><a class="nav-link" href="' .
                $plink .
                '">' .
                $row["title"] .
                "</a></li>" .
                "\n";
        }
        
    }
    $stmt1->closeCursor();
}

function second($mid, $plink)
{
    global $conn;
    $active = 1;

    $stmt = $conn->prepare("SELECT * FROM pages WHERE active=?");
    $stmt->execute([$active]);
    while ($mrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $parents[] = $mrow["parent"];
    }
    $stmt->closeCursor();

    $stmt1 = $conn->prepare(
        "SELECT id, title, link, parent FROM pages WHERE parent=? AND active=?"
    );
    $stmt1->execute([$mid, $active]);
    echo '<ul class="dropdown-menu">' . "\n";
    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        $sid = $row["id"];
        $slink = $plink . "/" . $row["link"];
        if (in_array($row["id"], $parents)) {
            echo '<li class="nav-item dropdown dropend"><a class="dropdown-item" href="' .
                $slink .
                '">' .
                $row["title"] .
                "</a>" .
                "\n";
            echo third($sid, $slink);
            echo "</li>" . "\n";
        } else {
            echo '<li><a class="dropdown-item" href="' .
                $slink .
                '">' .
                $row["title"] .
                "</a></li>" .
                "\n";
        }
    }
    $stmt1->closeCursor();
    echo "</ul>" . "\n";
}

function third($sid, $plink)
{
    global $conn;
    $active = 1;

    $stmt = $conn->prepare("SELECT * FROM pages WHERE active=?");
    $stmt->execute([$active]);
    while ($mrow = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $parents[] = $mrow["parent"];
    }
    $stmt->closeCursor();
    $stmt1 = $conn->prepare(
        "SELECT id, title, link, parent FROM pages WHERE parent=? AND active=?"
    );
    $stmt1->execute([$sid, $active]);
    echo '<ul class="dropdown-menu dropdown-submenu submenu">' . "\n";
    while ($row = $stmt1->fetch(PDO::FETCH_ASSOC)) {
        $sbid = $row["id"];
        $slink = $plink . "/" . $row["link"];

        if (in_array($row["id"], $parents)) {
            echo '<li class="dropdown-hover"><a class="dropdown-item" href="' .
                $slink .
                '">' .
                $row["title"] .
                "</a>" .
                "\n";
            echo third($sbid, $slink);
            echo "</li>" . "\n";
        } else {
            echo '<li><a class="dropdown-item" href="' .
                $slink .
                '">' .
                $row["title"] .
                "</a></li>" .
                "\n";
        }
    }
    $stmt1->closeCursor();
    echo "</ul>" . "\n";
}

if(empty($menu)){
    $menu = 1;
}
global $conn;
$rmenu = $conn->prepare("SELECT * FROM menu_options WHERE id=?");
$rmenu->execute([$menu]);
$rmopt = $rmenu->fetch(PDO::FETCH_ASSOC);
$rmenu->closeCursor();
$id_menu = $rmopt["id_menu"];
$fluid = "";
$placement = "";
$aligment = "";
$background = "";
$color = "";

if ($rmopt["fluid"] === "yes") {
    $fluid = "container-fluid";
} else {
    $fluid = "container";
}

if ($rmopt["placement"] === "top") {
    $placement = "fixed-top";
} elseif ($rmopt["placement"] === "bottom") {
    $placement = "fixed-bottom";
} else {
    $placement = "sticky-top";
}

if ($rmopt["aligment"] === "start") {
    $aligment = "justify-content-start";
} elseif ($rmopt["aligment"] === "center") {
    $aligment = "justify-content-center";
} else {
    $aligment = "justify-content-end";
}

if ($rmopt["background"] === "primary") {
    $background = "bg-primary";
} elseif ($rmopt["background"] === "secondary") {
    $background = "bg-secondary";
} elseif ($rmopt["background"] === "light") {
    $background = "bg-light";
} elseif ($rmopt["background"] === "dark") {
    $background = "bg-dark";
} elseif ($rmopt["background"] === "info") {
    $background = "bg-info";
} elseif ($rmopt["background"] === "success") {
    $background = "bg-success";
} elseif ($rmopt["background"] === "warning") {
    $background = "bg-warning";
} else {
    $background = "bg-danger";
}

if ($rmopt["color"] === "light") {
    $color = "light";
} else {
    $color = "dark";
}
?>
<nav id="<?php echo $id_menu; ?>" class="navbar navbar-expand-lg shadow <?php echo $background; ?>" data-bs-theme="<?php echo $color; ?>">
 <div class="<?php echo $fluid; ?>">
 	 <a class="navbar-brand" href="<?php echo SITE_PATH; ?>">
            <?php if (file_exists(SITE_BRAND_IMG)) { ?>
                <img src="<?php echo SITE_BRAND_IMG; ?>" alt="" width="30" height="24">
            <?php } else { ?>
                <?php echo SITE_NAME; ?>
            <?php } ?>
        </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#<?php echo $id_menu; ?>" aria-controls="<?php echo $id_menu; ?>"  aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
     
	 <div class="collapse navbar-collapse <?php echo $aligment; ?>" id="<?php echo $id_menu; ?>">		 
            <ul class="navbar-nav dropdown-hover-all me-auto mb-2 mb-lg-0">               
<?php echo dropdown(); ?>
            </ul>
	
<form class="d-flex input-group w-auto">
    <div class="input-group">
      <input
        type="search"
        class="form-control rounded"
        placeholder="Search"
        aria-label="Search"
        aria-describedby="search-addon"
      />
      <span class="input-group-text border-0" id="search-addon">
        <i class="fas fa-search"></i>
      </span>
	  <?php
   if ($fname != "register") { ?>
		    <a class="btn btn-secondary btn-outline-info" role="button" href="<?php echo SITE_PATH; ?>signin/register">Registrarse <i class="fa-solid fa-unlock"></i></a> 
			<?php }
   if ($fname != "login") { ?>
		    <a class="btn btn-secondary btn-outline-info" role="button" href="<?php echo SITE_PATH; ?>signin/login">Ingresar <i class="fa-solid fa-user"></i></a>
			<?php }
   ?>
    </div>
    </form>
        </div>
    </div>
</nav>

