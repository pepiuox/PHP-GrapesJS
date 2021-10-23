
<style>

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

