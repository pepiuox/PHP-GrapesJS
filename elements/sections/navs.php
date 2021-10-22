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

    .pi-wrapper>* {
      width: 100%;
    }
  </style>
  <ul class="nav pi-draggable">
    <li class="nav-item">
      <a href="#" class="nav-link active">Nav item</a>
    </li>
    <li class="nav-item">
      <a href="#" class="nav-link active">Nav item</a>
    </li>
    <li class="nav-item dropdown">
      <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Drop</a>
      <div class="dropdown-menu">
        <a class="dropdown-item" href="#">Action</a>
        <a class="dropdown-item" href="#">Another action</a>
        <a class="dropdown-item" href="#">Something else here</a>
        <div class="dropdown-divider"></div>
        <a class="dropdown-item" href="#">Separated link</a>
      </div>
    </li>
  </ul>
  <ul class="nav nav-pills pi-draggable">
    <li class="nav-item"> <a href="#" class="active nav-link">Nav pill</a> </li>
    <li class="nav-item"> <a class="nav-link" href="#">Nav pill</a> </li>
    <li class="nav-item"> <a href="#" class="nav-link disabled">Nav pill</a> </li>
  </ul>
  <ul class="nav nav-pills pi-draggable">
    <li class="nav-item"> <a href="#" class="active nav-link"><i class="d-block fa fa-lg fa-bell-o"></i></a> </li>
    <li class="nav-item"> <a href="#" class="nav-link"><i class="d-block fa fa-lg fa-area-chart"></i></a> </li>
    <li class="nav-item"> <a class="nav-link" href="#"><i class="d-block fa fa-lg fa-clock-o"></i></a> </li>
    <li class="nav-item"> <a href="#" class="nav-link"><i class="d-block fa fa-lg fa-cloud-upload"></i></a> </li>
  </ul>
  <ul class="pi-draggable breadcrumb">
    <li class="breadcrumb-item"> <a href="#">Home</a> </li>
    <li class="breadcrumb-item active">Link</li>
    <li class="breadcrumb-item active">Link</li>
  </ul>
  <ul class="pagination pi-draggable">
    <li class="page-item"> <a class="page-link" href="#"> <span>«</span></a> </li>
    <li class="page-item active"> <a class="page-link" href="#">1</a> </li>
    <li class="page-item"> <a class="page-link" href="#">2</a> </li>
    <li class="page-item"> <a class="page-link" href="#">3</a> </li>
    <li class="page-item"> <a class="page-link" href="#">4</a> </li>
    <li class="page-item"> <a class="page-link" href="#"> <span>»</span></a> </li>
  </ul>
  <ul class="pagination pi-draggable">
    <li class="page-item"> <a class="page-link" href="#">Prev</a> </li>
    <li class="page-item"> <a class="page-link" href="#">1</a> </li>
    <li class="page-item"> <a class="page-link" href="#">2</a> </li>
    <li class="page-item active"> <a class="page-link" href="#">3</a> </li>
    <li class="page-item"> <a class="page-link" href="#">4</a> </li>
    <li class="page-item"> <a class="page-link" href="#">Next</a> </li>
  </ul>
  <div class="pi-wrapper pi-draggable">
    <ul class="nav nav-tabs">
      <li class="nav-item"> <a href="" class="active nav-link" data-toggle="tab" data-target="#tabone">Tab 1</a> </li>
      <li class="nav-item"> <a class="nav-link" href="" data-toggle="tab" data-target="#tabtwo">Tab 2</a> </li>
      <li class="nav-item"> <a href="" class="nav-link" data-toggle="tab" data-target="#tabthree">Tab 3</a> </li>
    </ul>
    <div class="tab-content mt-2">
      <div class="tab-pane fade show active" id="tabone" role="tabpanel">
        <p class="">When I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms.</p>
      </div>
      <div class="tab-pane fade" id="tabtwo" role="tabpanel">
        <p class="">Who formed us in his own image, and the breath of that universal love which bears and sustains us. And then, my friend.</p>
      </div>
      <div class="tab-pane fade" id="tabthree" role="tabpanel">
        <p class="">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing.</p>
      </div>
    </div>
  </div>
  <div class="pi-wrapper pi-draggable">
    <ul class="nav nav-tabs">
      <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone"><i class="fa fa-home"></i> Tab 1</a> </li>
      <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo"><i class="fa fa-bed"></i> Tab 2</a> </li>
      <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree"><i class="fa fa-shower"></i> Tab 3</a> </li>
    </ul>
    <div class="tab-content mt-2">
      <div class="tab-pane fade show active" id="tabone" role="tabpanel">
        <p class="">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy.</p>
      </div>
      <div class="tab-pane fade" id="tabtwo" role="tabpanel">
        <p class="">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite.</p>
      </div>
      <div class="tab-pane fade" id="tabthree" role="tabpanel">
        <p class="">When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface.</p>
      </div>
    </div>
  </div>
  <div class="pi-wrapper pi-draggable">
    <ul class="nav nav-pills">
      <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone">Tab 1</a> </li>
      <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo">Tab 2</a> </li>
      <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree">Tab 3</a> </li>
    </ul>
    <div class="tab-content mt-2">
      <div class="tab-pane fade show active" id="tabone" role="tabpanel">
        <p class="">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite.</p>
      </div>
      <div class="tab-pane fade" id="tabtwo" role="tabpanel">
        <p class="">When, while the lovely valley teems with vapour around me, and the meridian sun strikes the upper surface.</p>
      </div>
      <div class="tab-pane fade" id="tabthree" role="tabpanel">
        <p class="">A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy.</p>
      </div>
    </div>
  </div>
  <div class="pi-wrapper pi-draggable">
    <ul class="nav nav-pills">
      <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone"><i class="fa fa-lg fa-envelope-open"></i> </a> </li>
      <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo"><i class="fa fa-lg fa-comment"></i></a> </li>
      <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree"><i class="fa fa-lg fa-cog"></i></a> </li>
    </ul>
    <div class="tab-content mt-2">
      <div class="tab-pane fade show active" id="tabone" role="tabpanel">
        <p class="">Who formed us in his own image, and the breath of that universal love which bears and sustains us. And then, my friend.</p>
      </div>
      <div class="tab-pane fade" id="tabtwo" role="tabpanel">
        <p class="">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite.</p>
      </div>
      <div class="tab-pane fade" id="tabthree" role="tabpanel">
        <p class="">When I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms.</p>
      </div>
    </div>
  </div>
    <div class="row  pi-draggable">
      <div class="col-3">
        <ul class="nav nav-pills flex-column">
          <li class="nav-item"> <a href="" class="active nav-link" data-toggle="pill" data-target="#tabone">Tab 1</a> </li>
          <li class="nav-item"> <a class="nav-link" href="" data-toggle="pill" data-target="#tabtwo">Tab 2</a> </li>
          <li class="nav-item"> <a href="" class="nav-link" data-toggle="pill" data-target="#tabthree">Tab 3</a> </li>
        </ul>
      </div>
      <div class="col-9">
        <div class="tab-content">
          <div class="tab-pane fade show active" id="tabone" role="tabpanel">
            <p class="">In my soul and absorb its power, like the form of a beloved mistress, then I often think with longing. A wonderful serenity has taken possession of my entire soul, like these sweet mornings of spring which I enjoy with my whole heart.</p>
          </div>
          <div class="tab-pane fade" id="tabtwo" role="tabpanel">
            <p class=""> I am alone, and feel the charm of existence in this spot, which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite sense of mere tranquil existence, that I neglect my talents.</p>
          </div>
          <div class="tab-pane fade" id="tabthree" role="tabpanel">
            <p class="">Which was created for the bliss of souls like mine. I am so happy, my dear friend, so absorbed in the exquisite. When I hear the buzz of the little world among the stalks, and grow familiar with the countless indescribable forms.</p>
          </div>
        </div>
      </div>
    </div>
  <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js"></script>
  <script src="https://pingendo.com/assets/bootstrap/bootstrap-4.0.0-alpha.6.min.js"></script>
  <script src="sections.js"></script>
</body>

</html>