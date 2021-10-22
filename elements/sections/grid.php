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
      box-shadow: 0 0.3rem 0.5rem rgba(0, 0, 0, 0.1);
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

    .pi-draggable>.col-md-12 {
      width: 100%;
      float: left
    }

    .pi-draggable>.col-md-8 {
      width: 66.6%;
      float: left
    }

    .pi-draggable>.col-md-6 {
      width: 50%;
      float: left
    }

    .pi-draggable>.col-md-4 {
      width: 33.3%;
      float: left
    }

    .pi-draggable>.col-md-3 {
      width: 25%;
      float: left
    }

    .pi-draggable>.col-md-2 {
      width: 16.6%;
      float: left
    }

    [class*=col-]:empty:after {
      content: "Col" !important;
    }

    [class*=col-]:empty {
      background-color: rgba(0, 0, 0, .1) !important;
      border: 2px solid white;
    }
  </style>
  <br>
  <div class="row pi-draggable">
    <div class="col-md-12"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-6"></div>
    <div class="col-md-6"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-2"></div>
    <div class="col-md-2"></div>
    <div class="col-md-2"></div>
    <div class="col-md-2"></div>
    <div class="col-md-2"></div>
    <div class="col-md-2"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-4"></div>
    <div class="col-md-8"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-8"></div>
    <div class="col-md-4"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-6"></div>
    <div class="col-md-6"></div>
    <div class="col-md-6"></div>
    <div class="col-md-6"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
    <div class="col-md-3"></div>
  </div>
  <div class="row pi-draggable">
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
    <div class="col-md-4"></div>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="sections.js" style=""></script>
</body>

</html>