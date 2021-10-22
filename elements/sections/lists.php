<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <base href="http://localhost:8000/" target="_blank">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" data-theme="true" href="theme.css">
</head>

<body>
  <style>
    body {
      margin: 10px;
    }

    body>* {
      margin-bottom: 25px !important;
      left: 5% !important;
      overflow: visible;
      max-width: 90% !important;
      transition: all 0.5;
    }

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
  <div class="list-group pi-draggable">
    <a href="#" class="list-group-item list-group-item-action active"> Cras justo odio </a>
    <a href="#" class="list-group-item list-group-item-action">Dapibus ac facilisis in</a>
    <a href="#" class="list-group-item list-group-item-action">Morbi leo risus</a>
    <a href="#" class="list-group-item list-group-item-action">Porta ac consectetur ac</a>
    <a href="#" class="list-group-item list-group-item-action disabled">Vestibulum at eros</a>
  </div>
  <ul class="list-group list-group-flush pi-draggable">
    <li class="list-group-item"><i class="fa fa-cloud text-primary mr-2"></i>Cras justo odio</li>
    <li class="list-group-item"><i class="fa fa-bookmark text-primary mr-2"></i> Dapibus ac facilisis in</li>
    <li class="list-group-item"><i class="fa fa-bell text-primary mr-2"></i> Morbi leo risus</li>
    <li class="list-group-item"><i class="fa fa-life-ring text-primary mr-2"></i> Porta ac consectetur ac</li>
    <li class="list-group-item"><i class="fa fa-paper-plane text-primary mr-2"></i> Vestibulum at eros</li>
  </ul>
  <ul class="list-group pi-draggable">
    <li class="list-group-item d-flex justify-content-between align-items-center"> Cras justo odio <span class="badge badge-primary badge-pill">14</span> </li>
    <li class="list-group-item d-flex justify-content-between align-items-center"> Dapibus ac facilisis in <span class="badge badge-primary badge-pill">2</span> </li>
    <li class="list-group-item d-flex justify-content-between align-items-center"> Morbi leo risus <span class="badge badge-primary badge-pill">1</span> </li>
  </ul>
  <ul class="list-group pi-draggable">
    <li class=" border-0 list-group-item d-flex justify-content-between align-items-center"> MY LISTS <i class="fa fa-list text-muted fa-lg"></i></li>
    <li class=" border-0 list-group-item d-flex justify-content-between align-items-center"> ANALYTICS <i class="fa fa-pie-chart text-muted fa-lg"></i></li>
    <li class=" border-0 list-group-item d-flex justify-content-between align-items-center"> SETTINGS <i class="fa fa-cog text-muted fa-lg"></i></li>
    <li class=" border-0 list-group-item d-flex justify-content-between align-items-center"> LOG OUT <i class="fa fa-sign-out text-muted fa-lg"></i></li>
  </ul>
  <div class="list-group pi-draggable">
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start active">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">List group</h5> <small>3 days ago</small>
      </div>
      <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p> <small>Donec id elit non mi porta.</small>
    </a>
    <a href="#" class="list-group-item list-group-item-action flex-column align-items-start">
      <div class="d-flex w-100 justify-content-between">
        <h5 class="mb-1">List group </h5> <small class="text-muted">3 days ago</small>
      </div>
      <p class="mb-1">Donec id elit non mi porta gravida at eget metus. Maecenas sed diam eget risus varius blandit.</p> <small class="text-muted">Donec id elit non mi porta.</small>
    </a>
  </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="https://pingendo.com/assets/bootstrap/bootstrap-4.0.0-alpha.6.min.js"></script>
  <script src="sections.js"></script>
</body>

</html>