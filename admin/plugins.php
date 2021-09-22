<form method="post" class="form-horizontal" role="form" id="add_plugins_app" enctype="multipart/form-data">
    <div class="form-group">
        <label for="plugins">Plugins:</label>
        <input type="text" class="form-control" id="plugins" name="plugins">
    </div>
    <div class="form-group">
        <label for="pluginsOpts">PluginsOpts:</label>
        <input type="text" class="form-control" id="pluginsOpts" name="pluginsOpts">
    </div>
    <div class="form-group">
        <label for="script">Script:</label>
        <input type="text" class="form-control" id="script" name="script">
    </div>
    <div class="form-group">
        <label for="css">Css:</label>
        <input type="text" class="form-control" id="css" name="css">
    </div>
    <div class="form-group">
        <label for="buttons">Buttons:</label>
        <input type="text" class="form-control" id="buttons" name="buttons">
    </div>
    <div class="form-group">
        <label for="plugins_script">Plugins script:</label>
        <input type="text" class="form-control" id="plugins_script" name="plugins_script">
    </div>
    <div class="form-group">
        <label for="plugins_css">Plugins css:</label>
        <input type="text" class="form-control" id="plugins_css" name="plugins_css">
    </div>
    <div class="form-group">
        <button type="submit" id="addrow" name="addrow" class="btn btn-primary">
            <span class="fas fa-plus-square" onclick="dVals();"></span> Add</button>
    </div>
</form>

<form role="form" id="add_plugins_app" method="POST">
    <div class="form-group">
        <label for="plugins" class ="control-label col-sm-3">Plugins:</label>
        <input type="text" class="form-control" id="plugins" name="plugins" value="gjs-component-countdown">
    </div>
    <div class="form-group">
        <label for="pluginsOpts" class ="control-label col-sm-3">PluginsOpts:</label>
        <input type="text" class="form-control" id="pluginsOpts" name="pluginsOpts" value="">
    </div>
    <div class="form-group">
        <label for="script" class ="control-label col-sm-3">Script:</label>
        <input type="text" class="form-control" id="script" name="script" value="">
    </div>
    <div class="form-group">
        <label for="css" class ="control-label col-sm-3">Css:</label>
        <input type="text" class="form-control" id="css" name="css" value="">
    </div>
    <div class="form-group">
        <label for="buttons" class ="control-label col-sm-3">Buttons:</label>
        <input type="text" class="form-control" id="buttons" name="buttons" value="">
    </div>
    <div class="form-group">
        <label for="plugins_script" class ="control-label col-sm-3">Plugins script:</label>
        <input type="text" class="form-control" id="plugins_script" name="plugins_script" value="">
    </div>
    <div class="form-group">
        <label for="plugins_css" class ="control-label col-sm-3">Plugins css:</label>
        <input type="text" class="form-control" id="plugins_css" name="plugins_css" value="">
    </div>
    <div class="form-group">
        <button type="submit" id="editrow" name="editrow" class="btn btn-primary">
            <span class = "fas fa-plus-square"></span> Edit</button>
    </div>
</form>
