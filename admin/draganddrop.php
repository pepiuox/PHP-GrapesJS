<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="styles.css">
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js" integrity="sha512-894YE6QWD5I59HgZOGReFYm4dnWc1Qt5NtvYSaNcOP+u1T9qYdvdihz0PPSiiqn/+/3e7Jo4EaG7TubfWGUrMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        <script src="https://kit.fontawesome.com/fc3420a326.js" crossorigin="anonymous"></script>
        <title>Document</title>
        <style>
            .navigation {
                padding: 0;
                margin: 0;
                border: 0;
                line-height: 1;
            }

            .navigation ul,
            .navigation ul li,
            .navigation ul ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            .navigation ul {
                position: relative;
                z-index: 500;
                float: left;
            }

            .navigation ul li {
                float: left;
                min-height: 0.05em;
                line-height: 1em;
                vertical-align: middle;
                position: relative;
            }

            .navigation ul li.hover,
            .navigation ul li:hover {
                position: relative;
                z-index: 510;
                cursor: default;
            }

            .navigation ul ul {
                visibility: hidden;
                position: absolute;
                top: 100%;
                left: 0px;
                z-index: 520;
                width: 100%;
            }

            .navigation ul ul li {
                float: none;
            }

            .navigation ul ul ul {
                top: 0;
                right: 0;
            }

            .navigation ul li:hover > ul {
                visibility: visible;
            }

            .navigation ul ul {
                top: 0;
                left: 99%;
            }

            .navigation ul li {
                float: none;
            }

            .navigation ul ul {
                margin-top: 0.05em;
            }

            .navigation {
                width: 13em;
                background: #333333;
                font-family: 'roboto', Tahoma, Arial, sans-serif;
                zoom: 1;
            }

            .navigation:before {
                content: '';
                display: block;
            }

            .navigation:after {
                content: '';
                display: table;
                clear: both;
            }

            .navigation a {
                display: block;
                padding: 1em 1.3em;
                color: #ffffff;
                text-decoration: none;
                text-transform: uppercase;
            }

            .navigation > ul {
                width: 13em;
            }

            .navigation ul ul {
                width: 37em;
            }

            .navigation > ul > li > a {
                border-right: 0.3em solid #34A65F;
                color: #ffffff;
            }

            .navigation > ul > li > a:hover {
                color: #ffffff;
            }

            .navigation > ul > li a:hover,
            .navigation > ul > li:hover a {
                background: #34A65F;
            }

            .navigation li {
                position: relative;
            }

            .navigation ul li.has-sub > a:after {
                content: '»';
                position: absolute;
                right: 1em;
            }

            .navigation ul ul li.first {
                -webkit-border-radius: 0 3px 0 0;
                -moz-border-radius: 0 3px 0 0;
                border-radius: 0 3px 0 0;
            }

            .navigation ul ul li.last {
                -webkit-border-radius: 0 0 3px 0;
                -moz-border-radius: 0 0 3px 0;
                border-radius: 0 0 3px 0;
                border-bottom: 0;
            }

            .navigation ul ul {
                -webkit-border-radius: 0 3px 3px 0;
                -moz-border-radius: 0 3px 3px 0;
                border-radius: 0 3px 3px 0;
            }

            .navigation ul ul {
                border: 1px solid #34A65F;
            }

            .navigation ul ul a {
                color: #ffffff;
            }

            .navigation ul ul a:hover {
                color: #ffffff;
            }

            .navigation ul ul li {
                border-bottom: 1px solid #0F8A5F;
            }

            .navigation ul ul li:hover > a {
                background: #4eb1ff;
                color: #ffffff;
            }

            .navigation.align-right > ul > li > a {
                border-left: 0.3em solid #34A65F;
                border-right: none;
            }

            .navigation.align-right {
                float: right;
            }

            .navigation.align-right li {
                text-align: right;
            }

            .navigation.align-right ul li.has-sub > a:before {
                content: '+';
                position: absolute;
                top: 50%;
                left: 15px;
                margin-top: -6px;
            }

            .navigation.align-right ul li.has-sub > a:after {
                content: none;
            }

            .navigation.align-right ul ul {
                visibility: hidden;
                position: absolute;
                top: 0;
                left: -100%;
                z-index: 598;
                width: 100%;
            }

            .navigation.align-right ul ul li.first {
                -webkit-border-radius: 3px 0 0 0;
                -moz-border-radius: 3px 0 0 0;
                border-radius: 3px 0 0 0;
            }

            .navigation.align-right ul ul li.last {
                -webkit-border-radius: 0 0 0 3px;
                -moz-border-radius: 0 0 0 3px;
                border-radius: 0 0 0 3px;
            }

            .navigation.align-right ul ul {
                -webkit-border-radius: 3px 0 0 3px;
                -moz-border-radius: 3px 0 0 3px;
                border-radius: 3px 0 0 3px;
            }
        </style>
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif;
            }

            .container {
                width: 100%;
                height: 100vh;
                display: flex;
                justify-content: center;
                align-items: center;
                gap: 50px;
            }
            .slist {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            .slist li {
                margin: 5px;
                padding: 10px;
                border: 1px solid #333;
                background: #eaeaea;
            }
            .slist li.hint {
                background: #fea;
            }
            .slist li.active {
                background: #ffd4d4;
            }
            .language,
            .selected {
                width: 300px;
                height: 600px;

                position: relative;
                display: flex;
                flex-direction: column;

            }

            .heading1,
            .heading2 {
                font-size: 20px;
                font-weight: 600;
            }

            #language-list {
                border: 1px dotted black;
                width: 100%;
                height: 80%;
                position: absolute;
                bottom: 0;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                gap: 10px;
            }
            #selected-list{
                border: 1px dotted black;
                width: 100%;
                height: 20%;
                /* position: absolute; */

            }

            .list-item {
                width: 280px;
                height: 35px;
                font-size:13px;
                padding: 10px;
                border: 2px solid lightgray;
                border-radius: 5px;
                ;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
            .list-item.dragging{
                opacity:0.5;
                /* color:red; */
            }
            #searchBar {
                width: 250px;
                height: 50px;
                margin: 20px auto;
            }
            .display-list{
                width:100%;
                min-height:20px;
                overflow: auto;
                border:1px solid blue;
                display: flex;
                flex-direction: column;
                justify-content: flex-start;
                align-items: center;
                gap: 10px;
                overflow: auto;
            }
        </style>
        <script>
            $('.child').hide(); //Hide children by default

    $('.parent').children().click(function () {
        event.preventDefault();
        $(this).children('.child').slideToggle('slow');
        $(this).find('span').toggle();
    });
        </script>
        <script>
         
var btn = document.querySelector('.add');
var remove = document.querySelector('.list-item');

function dragStart(e) {
  this.style.opacity = '0.4';
  dragSrcEl = this;
  e.dataTransfer.effectAllowed = 'move';
  e.dataTransfer.setData('text/html', this.innerHTML);
};

function dragEnter(e) {
  this.classList.add('over');
}

function dragLeave(e) {
  e.stopPropagation();
  this.classList.remove('over');
}

function dragOver(e) {
  e.preventDefault();
  e.dataTransfer.dropEffect = 'move';
  return false;
}

function dragDrop(e) {
  if (dragSrcEl != this) {
    dragSrcEl.innerHTML = this.innerHTML;
    this.innerHTML = e.dataTransfer.getData('text/html');
  }
  return false;
}

function dragEnd(e) {
  var listItens = document.querySelectorAll('.list-item');
  [].forEach.call(listItens, function(item) {
    item.classList.remove('over');
  });
  this.style.opacity = '1';
}

function addEventsDragAndDrop(el) {
  el.addEventListener('dragstart', dragStart, false);
  el.addEventListener('dragenter', dragEnter, false);
  el.addEventListener('dragover', dragOver, false);
  el.addEventListener('dragleave', dragLeave, false);
  el.addEventListener('drop', dragDrop, false);
  el.addEventListener('dragend', dragEnd, false);
}

var listItens = document.querySelectorAll('.list-item');
[].forEach.call(listItens, function(item) {
  addEventsDragAndDrop(item);
});

function addNewItem() {
  var newItem = document.querySelector('.input').value;
  if (newItem != '') {
    document.querySelector('.input').value = '';
    var li = document.createElement('li');
    var attr = document.createAttribute('draggable');
    var ul = document.querySelector('ul');
    li.className = 'draggable';
    attr.value = 'true';
    li.setAttributeNode(attr);
    li.appendChild(document.createTextNode(newItem));
    ul.appendChild(li);
    addEventsDragAndDrop(li);
  }
}

btn.addEventListener('click', addNewItem);
        </script>
        <script>
            // Variables
var orangeSquare = document.getElementById("drop-element");
var pinkSquareContainer = document.getElementsByClassName("list-item")[0];

//Feature detection from Modernizr
var div = document.createElement("div");

if ("draggable" in div || ("ondragstart" in div && "ondrop" in div))
  console.log("Drag and Drop API is supported!");

// Draggable Element Functions
function onDragStartForPinkSquare(event) {
  event.dataTransfer.setData("text/plain", event.target.id); // "draggable-element"
  // define allowed effects
  event.dataTransfer.effectsAllowed = "move";

  // change cursor style
  event.target.style.cursor = "move";
   
  // To possibly create a drag image then hide the original
  setTimeout(()=>event.target.classList.add('hide'), 0);
}

function onDragEndForPinkSquare(event) {
  event.target.style.cursor = "pointer";
  event.target.classList.remove('hide');
}

// Generic onDragOver and onDrop Functions
function onDragOver(event) {
  event.preventDefault();
  event.dataTransfer.dropEffect = "move";
}

function onDrop(event, color) {
  event.preventDefault();

  // Extract id of element and get it's reference
  var id = event.dataTransfer.getData("text/plain");
  var pinkSquaere = document.getElementById(id);

  // Only append element, if it's not already appended to that elem
  // i.e. if that element is not it's parent

  if (color === "pink") {
    if (!pinkSquaere.parentNode.isSameNode(pinkSquareContainer))
      event.target.appendChild(pinkSquaere);
  } else {
    if (!pinkSquaere.parentNode.isSameNode(orangeSquare))
      event.target.appendChild(pinkSquaere);
  }
}

// Functions for drop zone 1 (Orange Square)

function onDragOverForOrangeSquare(event) {
  onDragOver(event);
}

function onDropForOrangeSquare(event) {
 onDrop(event, "orange");
}

// Functions for drop zone 2 (Pink bordered Square)

function onDragOverForPinkSquareContainer(event) {
  onDragOver(event);
}

function onDropForPinkSquareContainer(event) {
  onDrop(event, "pink");
}
        </script>
        <script>
            window.addEventListener("DOMContentLoaded", function(){
  slist("language-list");
});
        </script>
        <script>
            const searchbar = document.querySelector("#searchBar");
        const langlist = document.querySelector("#language-list");
        const selectedlist = document.querySelector("#selected-list");
        const lists = document.querySelectorAll(".list");
        const displayList = document.querySelector(".display-list");
        const listitems = document.querySelectorAll(".list-item");
        const selected = document.querySelector("#selected-list");


        searchbar.addEventListener("keyup", (e) => {
          let searchlist = langlist.getElementsByTagName("div");
          for (let search of searchlist) {
            console.log(search.innerText);
            if (search.innerText.toLowerCase().indexOf(e.target.value) > -1) {
              search.style.display = "";
            } else {
              search.style.display = "none";
            }
          }
        });

        listitems.forEach((item) => {
          registerEventsOnList(item);
        });

        lists.forEach((list) => {
          list.addEventListener("dragover", (e) => {
            e.preventDefault();
            let draggingCard = document.querySelector(".dragging");
            let cardAfterDraggingCard = getCardAfterDraggingCard(list, e.clientY);
            if (cardAfterDraggingCard) {
              cardAfterDraggingCard.parentNode.insertBefore(draggingCard,cardAfterDraggingCard);
            } else {
              console.log(list)
              list.appendChild(draggingCard);
            }
          });
        });

        function getCardAfterDraggingCard(list, yDraggingCard) {
          let listItem = [...list.querySelectorAll(".list-item:not(.dragging)")];
          return listItem.reduce(
            (closestCard, nextCard) => {
              let nextCardRect = nextCard.getBoundingClientRect();
              let offset = yDraggingCard - nextCardRect.top - nextCardRect.height / 2;
              if (offset < 0 && offset > closestCard.offset) {
                return { offset, element: nextCard };
              } else {
                return closestCard;
              }
            },
            { offset: Number.NEGATIVE_INFINITY }
          ).element;
        }
        function registerEventsOnList(item) {
          item.addEventListener("dragstart", (e) => {
            item.classList.add("dragging");
          });

          item.addEventListener("dragend", (e) => {
            item.classList.remove("dragging");
          });
        }
        </script>
        <script>
            function slist (target) {
    // (A) GET LIST + ATTACH CSS CLASS
    target = document.getElementById(target);
    target.classList.add("slist");

    // (B) MAKE ITEMS DRAGGABLE + SORTABLE
    var items = target.getElementsByTagName("li"), current = null;
    for (let i of items) {
      // (B1) ATTACH DRAGGABLE
      i.list-item = true;
    
      // (B2) DRAG START - YELLOW HIGHLIGHT DROPZONES
      i.addEventListener("dragstart", function (ev) {
        current = this;
        for (let it of items) {
          if (it != current) { it.classList.add("hint"); }
        }
      });
    
      // (B3) DRAG ENTER - RED HIGHLIGHT DROPZONE
      i.addEventListener("dragenter", function (ev) {
        if (this != current) { this.classList.add("active"); }
      });

      // (B4) DRAG LEAVE - REMOVE RED HIGHLIGHT
      i.addEventListener("dragleave", function () {
        this.classList.remove("active");
      });

      // (B5) DRAG END - REMOVE ALL HIGHLIGHTS
      i.addEventListener("dragend", function () {
        for (let it of items) {
          it.classList.remove("hint");
          it.classList.remove("active");
        }
      });
 
      // (B6) DRAG OVER - PREVENT THE DEFAULT "DROP", SO WE CAN DO OUR OWN
      i.addEventListener("dragover", function (evt) {
        evt.preventDefault();
      });
 
      // (B7) ON DROP - DO SOMETHING
      i.addEventListener("drop", function (evt) {
        evt.preventDefault();
        if (this != current) {
          let currentpos = 0, droppedpos = 0;
          for (let it=0; it<items.length; it++) {
            if (current == items[it]) { currentpos = it; }
            if (this == items[it]) { droppedpos = it; }
          }
          if (currentpos < droppedpos) {
            this.parentNode.insertBefore(current, this.nextSibling);
          } else {
            this.parentNode.insertBefore(current, this);
          }
        }
      });
    }
  }
        </script>

        <script>
          /* draggable element */
  const item = document.querySelector('.list-item');

  item.addEventListener('dragstart', dragStart);

  function dragStart(e) {
      e.dataTransfer.setData('text/plain', e.target.id);
      setTimeout(() => {
          e.target.classList.add('hide');
      }, 0);
  }


  /* drop targets */
  const boxes = document.querySelectorAll('.list');

  boxes.forEach(box => {
      box.addEventListener('dragenter', dragEnter);
      box.addEventListener('dragover', dragOver);
      box.addEventListener('dragleave', dragLeave);
      box.addEventListener('drop', drop);
  });


  function dragEnter(e) {
      e.preventDefault();
      e.target.classList.add('drag-over');
  }

  function dragOver(e) {
      e.preventDefault();
      e.target.classList.add('drag-over');
  }

  function dragLeave(e) {
      e.target.classList.remove('drag-over');
  }

  function drop(e) {
      e.target.classList.remove('drag-over');

      // get the draggable element
      const id = e.dataTransfer.getData('text/plain');
      const draggable = document.getElementById(id);

      // add it to the drop target
      e.target.appendChild(draggable);

      // display the draggable element
      draggable.classList.remove('hide');
  }
        </script>
    </head>
    <body>
        <div class="navigation">
            <ul>
                <li class="has-sub"> <a href="#">Menu 1</a>
                    <ul>
                        <li class="has-sub">
                            <div>
                                    hello
                                </div>
                        </li>
                    </ul>
                </li>
                <li class="has-sub"> <a href="#">Menu 2</a>
                    <ul>
                        <li><a href="#">Submenu 2.1</a></li>
                        <li><a href="#">Submenu 2.2</a></li>
                    </ul>
                </li>
                <li class="has-sub"> <a href="#">Menu 3</a>
                    <ul>
                        <li><a href="#">Submenu 3.1</a></li>
                        <li><a href="#">Submenu 3.2</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="container">
            <div class="language">
                <p class="heading1">Language List</p>
                <div class="inputbox"><i class="fas fa-search"></i>
                    <input type="text" name="searchBar" id="searchBar" placeholder="Search Language" /></div>
                <div class="list" id="language-list">
                    <div class="list-item" draggable="true"><div class="lang-text">Angular</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">C Language</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">C++</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">Java</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">JavaScript</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">Objective-C</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">PHP</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">Python</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">React JS</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">SQL</div><i class="fas fa-grip-vertical"></i></div>
                    <div class="list-item" draggable="true"><div class="lang-text">Vue JS</div><i class="fas fa-grip-vertical"></i></div>
                </div>
            </div>
            <div class="selected">
                <p class="heading2">Selected List</p>
                <div class="emptybox on"></div>
                <div class="list" id="selected-list">

                </div>
                <div class="display-list list">

                </div>
            </div>
        </div>
    </body>
</html>
