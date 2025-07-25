<!-- Require JS (REQUIRED) -->
<!-- Rename "main.default.js" to "main.js" and edit it if you need configure elFInder options or any things -->
<script data-main="./main-minimal.default.js" src="//cdnjs.cloudflare.com/ajax/libs/require.js/2.3.5/require.min.js"></script>
<script>
  define('elFinderConfig', {
// elFinder options (REQUIRED)
// Documentation for client options:
// https://github.com/Studio-42/elFinder/wiki/Client-configuration-options
defaultOpts: {
  url: 'php/connector.minimal.php' // connector URL (REQUIRED)
// bootCalback calls at before elFinder boot up 
,
  bootCallback: function(fm, extraObj) {
/* any bind functions etc. */
fm.bind('init', function() {
  // any your code
});
// for example set document.title dynamically.
var title = document.title;
fm.bind('open', function() {
  var path = '',
cwd = fm.cwd();
  if (cwd) {
path = fm.path(cwd.hash) || null;
  }
  document.title = path ? path + ':' + title : title;
}).bind('destroy', function() {
  document.title = title;
});
  }
},
managers: {
  // 'DOM Element ID': { /* elFinder options of this DOM Element */ }
  'elfinder': {}
}
  });
</script>
<!-- Element where elFinder will be created (REQUIRED) -->
<div id="elfinder"></div>

