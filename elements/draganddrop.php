<html>
    <head>
        <title>drag and drop</title>
        <style>
            .item{
                border:1px solid #000;
                width:160px;
                height:160px;
            }
            .box{
                border:1px solid #000;
                width:170px;
                height:170px;
                padding:10px
            }
            #drop_container{
                border:1px solid #000;
                width:160px;
                height:160px;
                padding:5px;
                float:left;
                margin:20px;
            }
            #dropback_container{
                border:1px solid #000;
                width:160px;
                height:160px;
                padding:5px;
                float:left;
                margin:20px;
            }

            div.draggable {
                position: absolute;
                left: 20px;
                right: 20px;
                border: 5px solid gray;
                overflow: hidden;
            }

            div.handle {
                padding: 3px;
                position: absolute;
                left: 0;
                top: 0;
                right: 0;
                background: gold;
                height: 25px;
            }

            div.body {
                padding: 3px;
                position: absolute;
                left: 0;
                top: 25px;
                right: 0;
                bottom: 0;
                background: cyan;
            }

            * { /* this style disables selection for all elements on web page */
                -webkit-touch-callout: none; /* iOS Safari                         */
                -webkit-user-select: none; /* Safari                             */
                -khtml-user-select: none; /* Konqueror HTML                     */
                -moz-user-select: none; /* Firefox in the past (old versions) */
                -ms-user-select: none; /* Internet Explorer (>=10) / Edge    */
                user-select: none; /* Currently supported:               */
                /* Chrome, Opera and Firefox          */
            }

        </style>
    </head>
    <body>
        
        <div class="box"><div class="item" draggable="true"></div></div>
        <div class="box"></div>
        <div id="drop_container" ondrop="drop(event,'drop_container')" ondragover="DopHere(event)"></div>
        <div id="dropback_container" ondrop="drop(event,'dropback_container')" ondragover="DopHere(event)">
            <img id="image_drag" src="https://www.tejasrana.com/wp-content/uploads/2015/06/TR.png" draggable="true" ondragstart="image_drags(event)">
        </div>
        <script>
        /* draggable element */
const item = document.querySelector('.item');

item.addEventListener('dragstart', dragStart);

function dragStart(e) {
    e.dataTransfer.setData('text/plain', e.target.id);
    setTimeout(() => {
        e.target.classList.add('hide');
    }, 0);
}

/* drop targets */
const boxes = document.querySelectorAll('.box');

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
        <script>
            function onDragStart(event) {
          event
            .dataTransfer
            .setData('text/plain', event.target.id);

          event
            .currentTarget
            .style
            .backgroundColor = 'yellow';
        }
    
        function DopHere(ev) {
            ev.preventDefault();
        }
        function image_drags(ev) {
            ev.dataTransfer.setData("text", ev.target.id);
        }
        function drop(ev,div_id) {
               ev.preventDefault();
     
               var hasImages =hasImage(div_id);
               var data = ev.dataTransfer.getData("text");
               if(hasImages==false){       
                 var data = ev.dataTransfer.getData("text");
                 ev.target.appendChild(document.getElementById(data));
               }else{
                 alert('div already have an image.');
               }
        }
        function hasImage(id) {
               var arrival_div=document.getElementById(id);
               var childElements = document.getElementById(id).childNodes;
               for (var i = 0; i < childElements.length; i++) {
                 if (childElements[i].localName != null && childElements[i].localName.toLowerCase() == "img") {
                   return true;
                 }
               }  
               return false;
        }
 
        </script>


        <script type="text/javascript">

          function getMargins(element) {
                      var style = element.currentStyle || window.getComputedStyle(element);

              var result = {
                  getX: function() {
                      return parseInt(style.marginLeft);
                  },
                  getY: function() {
                      return parseInt(style.marginTop);
                  }
              };
		
              return result;
          }

          function prepareDragging(element, handle) {
              var dragging = false;

              var clickX, clickY;
              var positionX, positionY;

              var style = element.style;

              function onMouseDown(e) {
                  clickX = e.clientX;
                  clickY = e.clientY;

                  var margins = getMargins(element);

                      // this approach prevents agains different margin sizes
                  positionX = element.offsetLeft - margins.getX();
                  positionY = element.offsetTop - margins.getY();

                  dragging = true;
              }

              function onMouseUp(e) {
                  dragging = false;
              }
      
              function onMouseMove(e) {
                  if (dragging) {
                      var x = positionX + e.clientX - clickX;
                      var y = positionY + e.clientY - clickY;
                
                      style.left = x + 'px';
                      style.top = y + 'px';
                  }
              }

              handle.addEventListener('mousedown', onMouseDown);  
              window.addEventListener('mouseup', onMouseUp);  
              window.addEventListener('mousemove', onMouseMove);
        
              var remove = function() {
                  if (remove) {
                      handle.removeEventListener('mousedown', onMouseDown);  
                      window.removeEventListener('mouseup', onMouseUp); 
                      window.removeEventListener('mousemove', onMouseMove);

                      remove = null;
                  }
              };
      
              return remove;
          }

        </script>
    </body>
</html>
