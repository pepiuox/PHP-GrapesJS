<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="theme.css">
</head>

<body>
  <style>
    body {
      margin: 10px;
    }

    body>* {
      margin-bottom: 25px !important;
      left: 5% !important;
      max-width: 90% !important;
      overflow: visible;
      transition: all 0.5 ease-in;
    }

    .row {
      width: 100%;
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

    img,
    iframe {
      min-height: 222px;
    }

    .pi-wrapper>* {
      width: 100%;
    }

    .carousel-item {
      align-items: center;
    }

    .embed-responsive {
      pointer-events: none;
    }

    .embed-responsive-item {
      pointer-events: all;
    }

    .pi-draggable>.col-md-6 {
      width: 50%;
      float: left
    }
  </style>
  <br>
  <div class="container">
    <div class="row">
      <div class="col-6 p-0 pr-1">
        <img class="img-fluid d-block pi-draggable" src="https://static.pingendo.com/img-placeholder-1.svg">
      </div>
      <div class="col-6 p-0 pl-1">
        <img class="img-fluid d-block pi-draggable rounded-circle" src="https://static.pingendo.com/img-placeholder-3.svg">
      </div>
    </div>
  </div>
  <i class="fa fa-2x fa-camera-retro pi-draggable"></i>&nbsp; <i class="fa fa-3x fa-camera-retro pi-draggable"></i>&nbsp; <i class="fa fa-4x fa-camera-retro pi-draggable"></i>&nbsp; <i class="fa fa-5x fa-camera-retro pi-draggable"></i><br>
  <i class="fa fa-spinner fa-spin fa-3x fa-fw pi-draggable"></i>
  <i class="fa fa-circle-o-notch fa-spin fa-3x fa-fw pi-draggable"></i>
  <i class="fa fa-refresh fa-spin fa-3x fa-fw pi-draggable"></i>
  <i class="fa fa-cog fa-spin fa-3x fa-fw pi-draggable"></i>
  <i class="fa fa-spinner fa-pulse fa-3x fa-fw pi-draggable"></i>
  <div class="carousel slide pi-draggable" data-ride="carousel" id="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active"> <img class="d-block img-fluid w-100" src="https://static.pingendo.com/cover-bubble-dark.svg">
        <div class="carousel-caption">
          <h5 class="m-0">Carousel</h5>
          <p>with controls</p>
        </div>
      </div>
      <div class="carousel-item"> <img class="d-block img-fluid w-100" src="https://static.pingendo.com/cover-bubble-light.svg">
        <div class="carousel-caption">
          <h5 class="m-0">Carousel</h5>
          <p>with controls</p>
        </div>
      </div>
      <div class="carousel-item"> <img class="d-block img-fluid w-100" src="https://static.pingendo.com/cover-moon.svg">
        <div class="carousel-caption">
          <h5 class="m-0">Carousel</h5>
          <p>with controls</p>
        </div>
      </div>
    </div> <a class="carousel-control-prev" href="#carousel" role="button" data-slide="prev"> <span class="carousel-control-prev-icon"></span> <span class="sr-only">Previous</span> </a> <a class="carousel-control-next" href="#carousel" role="button" data-slide="next"> <span class="carousel-control-next-icon"></span> <span class="sr-only">Next</span> </a>
  </div>
  <div id="carouselExampleIndicators" class="carousel slide carousel-fade pi-draggable" data-ride="carousel">
    <ol class="carousel-indicators">
      <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"> </li>
      <li data-target="#carouselExampleIndicators" data-slide-to="1"> </li>
      <li data-target="#carouselExampleIndicators" data-slide-to="2"> </li>
    </ol>
    <div class="carousel-inner">
      <div class="carousel-item active"> <img class="d-block img-fluid w-100" src="https://static.pingendo.com/cover-moon.svg">
        <div class="carousel-caption">
          <h5 class="m-0">Carousel</h5>
          <p>with indicators</p>
        </div>
      </div>
      <div class="carousel-item "> <img class="d-block img-fluid w-100" src="https://static.pingendo.com/cover-bubble-dark.svg">
        <div class="carousel-caption">
          <h5 class="m-0">Carousel</h5>
          <p>with indicators</p>
        </div>
      </div>
      <div class="carousel-item"> <img class="d-block img-fluid w-100" src="https://static.pingendo.com/cover-bubble-light.svg">
        <div class="carousel-caption">
          <h5 class="m-0">Carousel</h5>
          <p>with indicators</p>
        </div>
      </div>
    </div>
  </div>
  <div class="pi-wrapper pi-draggable">
    <iframe width="100%" height="400" src="https://maps.google.com/maps?q=New%20York&amp;z=14&amp;output=embed" scrolling="no" frameborder="0"></iframe>
  </div>
  <div class="pi-wrapper pi-draggable">
    <iframe width="100%" height="400" src="https://maps.google.com/maps?q=San%20Francisco&amp;z=12&amp;t=k&amp;output=embed" scrolling="no" frameborder="0"></iframe>
  </div>
  <div class="embed-responsive pi-draggable embed-responsive-16by9">
    <iframe src="https://www.youtube.com/embed/ctvlUvN6wSE?controls=0" allowfullscreen="" class="embed-responsive-item"></iframe>
  </div>
  <div class="embed-responsive pi-draggable embed-responsive-16by9">
    <iframe src="https://player.vimeo.com/video/239823487?title=0&amp;byline=0&amp;portrait=0" allowfullscreen="" class="embed-responsive-item"></iframe>
  </div>
  <div class="pi-draggable embed-responsive embed-responsive-16by9">
    <video src="https://static.pingendo.com/video-placeholder.mp4" class="embed-responsive-item" controls="controls"> Your browser does not support HTML5 video. </video>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="https://pingendo.com/assets/bootstrap/bootstrap-4.0.0-alpha.6.min.js"></script>
  <script src="sections.js"></script>
</body>

</html>