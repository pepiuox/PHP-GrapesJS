<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" type="text/css">
  <link rel="stylesheet" href="theme.css">
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
    
    /* .pi-draggable:hover {
      text-decoration: underline;
    } */

    .pi-draggable:active {
      cursor: -webkit-grabbing !important;
      cursor: -moz-grabbing !important;
      cursor: grabbing !important;
    }
  </style>
  <br>
  <p class="lead pi-draggable">Lead paragraph. A wonderful serenity has taken possession of my entire soul.</p>
  <p class="pi-draggable">Paragraph. Then, my friend, when darkness overspreads my eyes, and heaven and earth seem to dwell in my soul and absorb its power, like the form of a beloved mistress, then I often think with longing.</p>
  <h6 class="pi-draggable">Heading 6</h6>
  <h5 class="pi-draggable">Heading 5</h5>
  <h4 class="pi-draggable">Heading 4 <span class="badge badge-pill badge-warning">!</span></h4>
  <h3 class="pi-draggable">Heading 3 <span class="badge badge-light"> New</span></h3>
  <h2 class="pi-draggable">Heading 2 <small class="text-muted">subtitle</small></h2>
  <h1 class="pi-draggable">Heading 1</h1>
  <h1 class="display-4 pi-draggable">Display 4</h1>
  <h1 class="display-3 pi-draggable">Display 3</h1>
  <h1 class="display-2 pi-draggable">Display 2</h1>
  <h1 class="display-1 pi-draggable">Display 1</h1>
  <p class="pi-draggable text-monospace">Monospace. Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
  <div class="blockquote pi-draggable">
    <p class="mb-0">Blockquoute</p>
    <div class="blockquote-footer">Someone famous in <cite title="Source Title">My memories</cite></div>
  </div>
  <ul class="list-inline pi-draggable">
    <li class="list-inline-item">One</li>
    <li class="list-inline-item">Two</li>
    <li class="list-inline-item">Three</li>
  </ul>
  <ul class="pi-draggable">
    <li>One</li>
    <li>Two</li>
    <li>Three</li>
  </ul>
  <ol class="pi-draggable">
    <li>One</li>
    <li>Two</li>
    <li>Three</li>
  </ol>
  <ol class="pi-draggable" type="a">
    <li>One</li>
    <li>Two</li>
    <li>Three</li>
  </ol>
  <ol class="pi-draggable" type="A">
    <li>One</li>
    <li>Two</li>
    <li>Three</li>
  </ol>
  <div class="table-responsive pi-draggable">
    <table class="table">
      <thead>
        <tr>
          <th>#</th>
          <th>First Name</th>
          <th>Last Name</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td>1</td>
          <td>Mark</td>
          <td>Otto</td>
        </tr>
        <tr>
          <td>2</td>
          <td>Jacob</td>
          <td>Thornton</td>
        </tr>
        <tr>
          <td>3</td>
          <td>Larry</td>
          <td>the Bird</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-responsive pi-draggable">
    <table class="table table-bordered ">
      <thead class="thead-dark">
        <tr>
          <th>#</th>
          <th>First</th>
          <th>Last</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th>1</th>
          <td>Mark</td>
          <td>Otto</td>
        </tr>
        <tr>
          <th>2</th>
          <td>Jacob</td>
          <td>Thornton</td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Larry</td>
          <td>the Bird</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-responsive pi-draggable">
    <table class="table table-striped table-borderless">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">First</th>
          <th scope="col">Last</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>Otto</td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Jacob</td>
          <td>Thornton</td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Larry</td>
          <td>the Bird</td>
        </tr>
      </tbody>
    </table>
  </div>
  <div class="table-responsive pi-draggable">
    <table class="table table-striped table-dark">
      <thead>
        <tr>
          <th scope="col">#</th>
          <th scope="col">First</th>
          <th scope="col">Last</th>
        </tr>
      </thead>
      <tbody>
        <tr>
          <th scope="row">1</th>
          <td>Mark</td>
          <td>Otto</td>
        </tr>
        <tr>
          <th scope="row">2</th>
          <td>Jacob</td>
          <td>Thornton</td>
        </tr>
        <tr>
          <th scope="row">3</th>
          <td>Larry</td>
          <td>the Bird</td>
        </tr>
      </tbody>
    </table>
  </div>
  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
  <script src="sections.js"></script>
</body>

</html>