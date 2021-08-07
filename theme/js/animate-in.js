/**
 * animate-in.js 1.0.0
 * Animate elements on entrance
 * 
 * Usage:
 *
 * Make sure to add this to the <head> of your page to avoid flickering on load 
 * Based on https://andycaygill.github.io/scroll-entrance/
 */
(function() {

	//Set up defaults
  	var duration = "1000";
  	var heightOffset = 100;


    // document.write("<style id='temp-animate-in'>*[class^='animate-in'], *[class*=' animate-in'] {display:none}</style>")

  	function isElementVisible(elem) {

      var rect = elem.getBoundingClientRect();

      //Return true if any of the following conditions are met:
      return (
        // The top is in view: the top is more than 0 and less than the window height (the top of the element is in view)
        ( (rect.top + heightOffset) >= 0 && (rect.top + heightOffset) <= window.innerHeight ) || 
        // The bottom is in view: bottom position is greater than 0 and greater than the window height
        ( (rect.bottom + heightOffset) >= 0 && (rect.bottom + heightOffset) <= window.innerHeight ) ||
        // The top is above the viewport and the bottom is below the viewport
        ( (rect.top + heightOffset) < 0 && (rect.bottom + heightOffset) > window.innerHeight )
      )

  	}


	function update() {
		var nodes = document.querySelectorAll("*:not(.animate-in-done)[class^='animate-in'], *:not(.animate-in-done)[class*=' animate-in']")

		for (var i = 0; i < nodes.length; i++) {
			if (isElementVisible(nodes[i])) {
				nodes[i].classList.remove("out-of-viewport")
				nodes[i].classList.add("animate-in-done")
			} else {
				nodes[i].classList.add("out-of-viewport")
  		}
  	}
  }

	document.addEventListener("DOMContentLoaded", function(event) {
	  update()
    // setTimeout(function() {
    //   document.querySelector("#temp-animate-in").remove()
    // })
	});

	window.addEventListener("scroll", function() {
	  update()			
  })

})();

