<?php
include '../../config/dbconnection.php';
include '../header.php';
?>
<link rel="stylesheet" href="theme.css">
<link rel="stylesheet" href="../components/theme.css">

</head>

<body>
    <style> body {
            margin: 10px;
        }

        body>* {
            margin-bottom: 25px !important;
            left: 5% !important;
            max-width: 90% !important;
            overflow: visible;
            transition: all 0.5 ease-in;
        }

        /* .pi-draggable:hover {
          transform: translateY(-2px);
        } */

        .pi-draggable * {
            pointer-events: none !important;
        }

        .pi-draggable {
            pointer-events: all !important;
            position: relative;
            cursor: -webkit-grab !important;
            cursor: -moz-grab !important;
            cursor: grab !important;
        }

        .pi-draggable:active {
            cursor: -webkit-grabbing !important;
            cursor: -moz-grabbing !important;
            cursor: grabbing !important;
        }

    </style>
    <br>
    <a class="btn btn-primary pi-draggable" href="#">Button</a>
    <a class="btn btn-secondary pi-draggable" href="#">Secondary</a>
    <a class="btn btn-info pi-draggable" href="#">Info</a>
    <a class="btn btn-light pi-draggable" href="#">Light</a>
    <a class="btn btn-dark pi-draggable" href="#">Dark</a>
    <br>
    <a class="btn btn-outline-primary pi-draggable" href="#">Outline</a>
    <a class="btn btn-outline-secondary pi-draggable" href="#">Secondary</a>
    <a class="btn btn-outline-info pi-draggable" href="#">Info</a>
    <a class="btn btn-outline-light pi-draggable" href="#">Light</a>
    <a class="btn btn-outline-dark pi-draggable" href="#">Dark</a>
    <br>
    <a class="btn btn-link pi-draggable" href="#">Link</a>
    <br>
    <a class="btn btn-primary pi-draggable" href="#"><i class="fa fa-star fa-fw fa-1x py-1"></i></a>
    <a class="btn btn-primary pi-draggable" href="#"><i class="fa fa-download fa-fw"></i>&nbsp;Left icon </a>
    <a class="btn btn-outline-primary pi-draggable" href="#">Right icon <i class="fa fa-shopping-cart fa-fw"></i> </a>
    <br>
    <div class="btn-group pi-draggable">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropdown </button>
        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div>
    <div class="btn-group dropup pi-draggable">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropup </button>
        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div> <br>
    <div class="btn-group dropright pi-draggable">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropright </button>
        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div>
    <div class="btn-group dropleft pi-draggable">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> Dropleft </button>
        <div class="dropdown-menu"> <a class="dropdown-item" href="#">Action</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div><br>
    <div class="btn-group pi-draggable">
        <button type="button" class="btn btn-primary">Split Dropdown</button>
        <button type="button" class="btn btn-primary dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-expanded="false"></button>
        <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(128px, 38px, 0px); top: 0px; left: 0px; will-change: transform;">
            <a class="dropdown-item" href="#">Action</a>
            <a class="dropdown-item" href="#">Another action</a>
            <a class="dropdown-item" href="#">Something else here</a>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">Separated link</a>
        </div>
    </div>
    <br>
    <div class="btn-group pi-draggable">
        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown"> With Form </button>
        <div class="dropdown-menu">
            <form class="p-3">
                <div class="form-group">
                    <label for="exampleDropdownFormEmail1">Email address</label>
                    <input type="email" class="form-control" id="exampleDropdownFormEmail1" placeholder="email@example.com">
                </div>
                <div class="form-group">
                    <label for="exampleDropdownFormPassword1">Password</label>
                    <input type="password" class="form-control" id="exampleDropdownFormPassword1" placeholder="Password">
                </div>
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" id="dropdownCheck">
                    <label class="form-check-label" for="dropdownCheck"> Remember me </label>
                </div>
                <button type="submit" class="btn btn-primary">Sign in</button>
            </form>
            <div class="dropdown-divider"></div>
            <a class="dropdown-item" href="#">New around here? Sign up</a>
            <a class="dropdown-item" href="#">Forgot password?</a>
        </div>
    </div>
    <br>
    <button class="btn btn-primary pi-draggable" type="button">
        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
        Loading...
    </button>
    <br>
    <div class="pi-draggable btn-group"> <a href="#" class="btn btn-primary">Btn 1</a> <a href="#" class="btn btn-primary">Btn 2</a> <a href="#" class="btn btn-primary">Btn 3</a> </div>
    <br>
    <div class="pi-draggable btn-group"> <a href="#" class="btn btn-outline-primary">Btn A</a> <a href="#" class="btn btn-primary">Btn B</a> </div>
    <br>
    <div class="btn-group btn-group-toggle pi-draggable" data-toggle="buttons">
        <label class="btn btn-primary active">
            <input type="radio" name="options" id="option1" autocomplete="off" checked=""> Radio On </label>
        <label class="btn btn-primary">
            <input type="radio" name="options" id="option2" autocomplete="off"> Radio Off </label>
    </div>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#55acee" target="_blank"><i class="fa fa-twitter fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#55acee" target="_blank"><i class="fa fa-twitter fa-fw fa-1x py-1"></i> Tweet</a>
    <a class="btn btn-link pi-draggable" style="color:#55acee" target="_blank" href="#"><i class="fa fa-twitter fa-fw fa-1x py-1"></i> Twitter</a>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#3b5998" target="_blank"><i class="fa fa-facebook fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#3b5998" target="_blank"><i class="fa fa-facebook-square fa-fw fa-1x py-1"></i> Share</a>
    <a class="btn btn-link pi-draggable" href="#" style="color:#3b5998" target="_blank"><i class="fa fa-facebook-official fa-fw fa-1x py-1"></i> Facebook</a>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#dd4b39" target="_blank"><i class="fa fa-google-plus fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#dd4b39" target="_blank"><i class="fa fa-google-plus-circle fa-fw fa-1x py-1"></i> Share</a>
    <a class="btn btn-link pi-draggable" href="#" style="color:#dd4b39" target="_blank"><i class="fa fa-google-plus-official fa-fw fa-1x py-1"></i> Google</a>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#0976b4" target="_blank"><i class="fa fa-linkedin fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#0976b4" target="_blank"><i class="fa fa-linkedin-square fa-fw fa-1x py-1"></i>&nbsp;Link</a>
    <a class="btn btn-link pi-draggable" href="#" style="color:#0976b4" target="_blank"><i class="fa fa-linkedin-square fa-fw fa-1x py-1"></i> Linkedin</a>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#cc2127" target="_blank"><i class="fa fa-pinterest-p fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#cc2127" target="_blank"><i class="fa fa-pinterest-square fa-fw fa-1x py-1"></i> Pin it</a>
    <a class="btn btn-link pi-draggable" href="#" style="color:#cc2127" target="_blank"><i class="fa fa-pinterest fa-fw fa-1x py-1"></i> Pinterest</a>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#e52d27" target="_blank"><i class="fa fa-youtube-play fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#e52d27" target="_blank"><i class="fa fa-youtube fa-fw fa-1x py-1"></i> View</a>
    <a class="btn btn-link pi-draggable" href="#" style="color:#e52d27" target="_blank"><i class="fa fa-youtube-square fa-fw fa-1x py-1"></i> Youtube</a>
    <br>
    <a class="btn text-white pi-draggable" href="#" style="background:#ff4500" target="_blank"><i class="fa fa-reddit-alien fa-fw fa-1x py-1"></i></a>
    <a class="btn text-white pi-draggable" href="#" style="background:#ff4500" target="_blank"><i class="fa fa-reddit fa-fw fa-1x py-1"></i> Post</a>
    <a class="btn btn-link pi-draggable" href="#" style="color:#ff4500" target="_blank"><i class="fa fa-reddit fa-fw fa-1x py-1"></i> Reddit</a>
    <style>
        .hide{
            display: none;
        }
        .drag-over{
            border: dashed 3px red;
        }
        .item{
            background:#e52d27;
            width: 70px;
            height: 70px;
        }
        .box{
            background:#ff4500;
            width: 80px;
            height: 80px;
        }
    </style>
    <div class="container">
        <h4>hello</h4>
        <div class='drop-targets'>
            <div class='box'>
                <div class='item' id='id' draggable='true'>               
                </div>
            </div>
            <div class='box'>               
            </div>
            <div class='box'>               
            </div>
        </div>
    </div>
    <script>
   const item = document.querySelector(".item");
   item.addEventListener('dragstart', dragStart);
   function dragStart(e){
       e.dataTransfer.setData('text/plain', e.target.id);
       setTimeout(() =>{
           e.target.classList.add('hide');
       }, 0);               
   }
   const boxes = document.querySelectorAll(".box");
   boxes.forEach(box => {
       box.addEventListener('dragenter', dragEnter);
       box.addEventListener('dragover', dragOver);
       box.addEventListener('dragleave', dragLeave);
       box.addEventListener('drop', drop);
   });
   function dragEnter(e){
       e.preventDefault();
       e.target.classList.add('drag-over');
   }
   function dragOver(e){
       e.preventDefault();
       e.target.classList.add('drag-over');
   }
   function dragLeave(e){
       e.target.classList.remove('drag-over');
   }
   function drop(e){
       e.target.classList.remove('drag-over');
       const id =e.dataTransfer.getData('text/plain');
       const draggable = document.getElementById(id);
       e.target.appendChild(draggable);
       draggable.classList.remove('hide');
   }
    </script>
    <?php
    include '../footer.php';
    ?>

</body>
</html>
