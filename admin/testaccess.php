<?php
session_start();
$connfile = '../config/dbconnection.php';
if (file_exists($connfile)) {
    require '../config/dbconnection.php';
    require 'Autoload.php';
    $login = new UserClass();
    $check = new CheckValidUser();
    $levels = new AccessLevel();
    $_SESSION['levels'] = $level_user;
} else {
    header('Location: ../installer/install.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>test</title>
<script src="<?php echo $base; ?>assets/plugins/jquery/jquery.min.js" type="text/javascript"></script>
        
    </head>
    <body>
        <link href="<?php echo $base; ?>assets/css/sortablemenu.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $base; ?>assets/js/sortablemenu.js" type="text/javascript"></script>

<style>
    #sortable-row { list-style: none; }
    #sortable-row li { margin-bottom:4px; padding:2px 10px; background-color:#EEEEEE;cursor:move;}
    #sortable-row li.ui-state-highlight { height: 20px; background-color:#F0F0F0;border:#ccc 2px dotted;}
</style>
<script>
    $(function () {
        $("#sortable-row").sortable({
            connectWith: "#sortable-row",
            placeholder: "ui-state-highlight",
            update: function (event, ui) {
                $(this).children().each(function (index) {
                    $(this).find('ul').last().html(index + 1);
                });
            }
        });
    });

    function saveOrder() {
        var selectedLanguage = new Array();
        $('ul#sortable-row li').each(function () {
            selectedLanguage.push($(this).attr("id"));
        });
        document.getElementById("row_order").value = selectedLanguage;
    }
</script>
        <?php
        $run_qry = $conn->query("SELECT * FROM table_config");
        $total_found = $run_qry->num_rows;
        if ($total_found > 0) {
            $my_value = $run_qry->fetch_assoc();
            $myTable = explode(',', $my_value['table_name']);
        } else {
            $myTable = '';
        }

        $tables = array();
        $sql = "SHOW TABLES FROM newcms";
        $result = mysqli_query($conn, $sql);
        $arrayCount = 0;
        while ($row = mysqli_fetch_row($result)) {

            $tables[$arrayCount] = $row[0];
            $arrayCount++; // only do this to make sure it starts at index 0
        }
        foreach ($tables as $tname) {
            $remp = str_replace("_", " ", $tname);
            echo '<div class="checkbox">' . "\n";
            echo '<label for="checkboxes-' . $i++ . '">';
            echo '<input type="checkbox" id="checkboxes-' . $x++ . '" name="tables[]" value="' . $tname . '" ';
            if (in_array($tname, $myTable)) {
                echo "checked";
            }
            echo '> ';
            echo ucfirst($remp) . '</label>' . "\n";
            echo '</div>' . "\n";
        }
        ?>
        <style>

            #clock {
                font-family: sans-serif;
                color: #161616;
                font-size: 20px;
                text-align: center;
                padding-top: 40px;
                padding-bottom: 40px;
            }
        </style>

        <div id="clock"></div>
        <p id="result"></p>
        <script>
            function setCookie(cname, cvalue, exdays) {
  const d = new Date();
  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
  let expires = "expires="+d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  let name = cname + "=";
  let ca = document.cookie.split(';');
  for(let i = 0; i < ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}

function checkCookie() {
  let user = getCookie("username");
  if (user != "") {
    alert("Welcome again " + user);
  } else {
    user = prompt("Please enter your name:", "");
    if (user != "" && user != null) {
      setCookie("username", user, 365);
    }
  }
}

// Store
sessionStorage.setItem("lastname", "Mantilla");

// Retrieve
document.getElementById("result").innerHTML = sessionStorage.getItem("lastname"); 
$(document).ready(function () {
createCookie("lastname", "Mantilla", "10");
});

function createCookie(name, value, days) {
    var expires;
      
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
        expires = "; expires=" + date.toGMTString();
    }
    else {
        expires = "";
    }
      
    document.cookie = escape(name) + "=" + 
        escape(value) + expires + "; path=/";
}

    window.onload = displayClock();
            
function displayClock(){
  var display = new Date().toLocaleTimeString();
  var clock = document.getElementById('clock');
  clock.innerText = display;
  setTimeout(displayClock, 1000); 
}
        </script>
        <p id="demo"></p>
        <?php
        if (isset($_SESSION['lastname'])) {
            echo $_SESSION['lastname'];
        } else {
            echo 'No existe session';
        }
        echo $_COOKIE["lastname"];
        ?>
        <p id="calc"></p>
        <p><span class="convertedHour"></span> : <span class="convertedMin"></span></p>
        <br>
        <script>
            // Get today's date
            const attempts = new Date('2021-08-30 10:52:39');
            
            const today = new Date();
            const res =Math.floor(((today - attempts)/999)/59);
            document.getElementById("calc").innerHTML = res;
            
            $(document).ready(function() {
    var totalMinutes = $('#calc').html();

    var hours = Math.floor(totalMinutes / 60);          
    var minutes = totalMinutes % 60;

    $('.convertedHour').html(hours);
    $('.convertedMin').html(minutes);    
});
            
            // Compare today with October 3rd
            if (today.getHours() === 20 && today.getMinutes() === 12) {
              document.getElementById("demo").innerHTML = "It's now 3rd.";
            } else {
              document.getElementById("demo").innerHTML  = "It's not now 3rd.";
            }
        </script>
        <?php
        $time1 = new DateTime('2021-08-28 21:46:41');
        $time2 = new DateTime();

        echo round(abs($time2->format('Y-m-d H:i:s') - $time1->format('Y-m-d H:i:s')) / 60);
        /*
          $time_diff = $time1->diff($time3);

          echo $time_diff->h . ' hours ';
          echo $time_diff->i . ' minutes ';
          echo $time_diff->s . ' seconds';
         */
        echo '<br> 1';
//echo date('Y-m-d H:i:s');
        echo '<br>';
        $dayinpass = '2021-08-28 21:46:41';
        $today = time();
        $dayinpass = strtotime($dayinpass);
        echo round(abs($today - $dayinpass) / 60);
        echo '<br>2';
        $path = basename($_SERVER['SCRIPT_FILENAME'], '.php');
        echo $path . '.php';
        echo '<br>3';
        if ($login->isLoggedIn() === true) {
            echo 'Hello User: ';
            echo $_SESSION['levels'];
            if ($levels->DefaulRoles($level_user) === 9) {
                echo 'Hello ' . $level_usser;
            }
        }

        function getBrowser() {
            $u_agent = $_SERVER['HTTP_USER_AGENT'];
            $bname = 'Unknown';
            $platform = 'Unknown';
            $version = "";

            //First get the platform?
            if (preg_match('/linux/i', $u_agent)) {
                $platform = 'linux';
            } elseif (preg_match('/macintosh|mac os x/i', $u_agent)) {
                $platform = 'mac';
            } elseif (preg_match('/windows|win32/i', $u_agent)) {
                $platform = 'windows';
            }

            // Next get the name of the useragent yes seperately and for good reason
            if (preg_match('/MSIE/i', $u_agent) && !preg_match('/Opera/i', $u_agent)) {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            } elseif (preg_match('/Firefox/i', $u_agent)) {
                $bname = 'Mozilla Firefox';
                $ub = "Firefox";
            } elseif (preg_match('/OPR/i', $u_agent)) {
                $bname = 'Opera';
                $ub = "Opera";
            } elseif (preg_match('/Chrome/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
                $bname = 'Google Chrome';
                $ub = "Chrome";
            } elseif (preg_match('/Safari/i', $u_agent) && !preg_match('/Edge/i', $u_agent)) {
                $bname = 'Apple Safari';
                $ub = "Safari";
            } elseif (preg_match('/Netscape/i', $u_agent)) {
                $bname = 'Netscape';
                $ub = "Netscape";
            } elseif (preg_match('/Edge/i', $u_agent)) {
                $bname = 'Edge';
                $ub = "Edge";
            } elseif (preg_match('/Trident/i', $u_agent)) {
                $bname = 'Internet Explorer';
                $ub = "MSIE";
            }

            // finally get the correct version number
            $known = array('Version', $ub, 'other');
            $pattern = '#(?<browser>' . join('|', $known) .
                    ')[/ ]+(?<version>[0-9.|a-zA-Z.]*)#';
            if (!preg_match_all($pattern, $u_agent, $matches)) {
                // we have no matching number just continue
            }
            // see how many we have
            $i = count($matches['browser']);
            if ($i != 1) {
                //we will have two since we are not using 'other' argument yet
                //see if version is before or after the name
                if (strripos($u_agent, "Version") < strripos($u_agent, $ub)) {
                    $version = $matches['version'][0];
                } else {
                    $version = $matches['version'][1];
                }
            } else {
                $version = $matches['version'][0];
            }

            // check if we have a number
            if ($version == null || $version == "") {
                $version = "?";
            }

            return array(
                'userAgent' => $u_agent,
                'name' => $bname,
                'version' => $version,
                'platform' => $platform,
                'pattern' => $pattern
            );
        }

// now try it
        $ua = getBrowser();
        $yourbrowser = "Your browser: " . $ua['name'] . " " . $ua['version'] . " on " . $ua['platform'] . " reports: <br >" . $ua['userAgent'];
        print_r($yourbrowser);
        ?>


        <div id="myBrowser"></div>
        <script>
document.onload = GetBrowser();

function GetBrowser(){
        var browser = '';
var browserVersion = 0;

if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
    browser = 'Opera';
} else if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
    browser = 'MSIE';
} else if (/Navigator[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
    browser = 'Netscape';
} else if (/Chrome[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
    browser = 'Chrome';
} else if (/Safari[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
    browser = 'Safari';
    /Version[\/\s](\d+\.\d+)/.test(navigator.userAgent);
    browserVersion = new Number(RegExp.$1);
} else if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
    browser = 'Firefox';
}
if(browserVersion === 0){
    browserVersion = parseFloat(new Number(RegExp.$1));
}
var myBrowser = document.getElementById('myBrowser');
myBrowser.innerText = browser + "*" + browserVersion;
}
        </script>

    </body>
</html>
