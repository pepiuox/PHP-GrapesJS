window.addEventListener("resize", function () {
    "use strict";
    window.location.reload();
});
document.addEventListener("DOMContentLoaded", function (event) {


    /////// Prevent closing from click inside dropdown
    document.querySelectorAll('.dropdown-menu').forEach(function (element) {
        element.addEventListener('click', function (e) {
            e.stopPropagation();
        });
    });

    // make it as accordion for smaller screens
    if (window.innerWidth < 992) {

        // close all inner dropdowns when parent is closed
        document.querySelectorAll('.navbar .dropdown').forEach(function (everydropdown) {
            everydropdown.addEventListener('hidden.bs.dropdown', function () {
                // after dropdown is hidden, then find all submenus
                this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
                    // hide every submenu as well
                    everysubmenu.style.display = 'none';
                });
            });
        });

        document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
            element.addEventListener('click', function (e) {

                let nextEl = this.nextElementSibling;
                if (nextEl && nextEl.classList.contains('submenu')) {
                    // prevent opening link if link needs to open dropdown
                    e.preventDefault();
                    console.log(nextEl);
                    if (nextEl.style.display == 'block') {
                        nextEl.style.display = 'none';
                    } else {
                        nextEl.style.display = 'block';
                    }

                }
            });
        });
    }
    // end if innerWidth

});
// DOMContentLoaded  end

document.addEventListener("DOMContentLoaded", function () {
    // make it as accordion for smaller screens
    if (window.innerWidth < 992) {

        // close all inner dropdowns when parent is closed
        document.querySelectorAll('.navbar .dropdown').forEach(function (everydropdown) {
            everydropdown.addEventListener('hidden.bs.dropdown', function () {
                // after dropdown is hidden, then find all submenus
                this.querySelectorAll('.submenu').forEach(function (everysubmenu) {
                    // hide every submenu as well
                    everysubmenu.style.display = 'none';
                });
            });
        });

        document.querySelectorAll('.dropdown-menu a').forEach(function (element) {
            element.addEventListener('click', function (e) {
                let nextEl = this.nextElementSibling;
                if (nextEl && nextEl.classList.contains('submenu')) {
                    // prevent opening link if link needs to open dropdown
                    e.preventDefault();
                    if (nextEl.style.display == 'block') {
                        nextEl.style.display = 'none';
                    } else {
                        nextEl.style.display = 'block';
                    }

                }
            });
        });
    }
    // end if innerWidth
});
// DOMContentLoaded  end

(function ($bs) {
    const CLASS_NAME = 'has-child-dropdown-show';
    $bs.Dropdown.prototype.toggle = function (_orginal) {
        return function () {
            document.querySelectorAll('.' + CLASS_NAME).forEach(function (e) {
                e.classList.remove(CLASS_NAME);
            });
            let dd = this._element.closest('.dropdown').parentNode.closest('.dropdown');
            for (; dd && dd !== document; dd = dd.parentNode.closest('.dropdown')) {
                dd.classList.add(CLASS_NAME);
            }
            return _orginal.call(this);
        }
    }($bs.Dropdown.prototype.toggle);

    document.querySelectorAll('.dropdown').forEach(function (dd) {
        dd.addEventListener('hide.bs.dropdown', function (e) {
            if (this.classList.contains(CLASS_NAME)) {
                this.classList.remove(CLASS_NAME);
                e.preventDefault();
            }
            e.stopPropagation(); // do not need pop in multi level mode
        });
    });

    // for hover
    function getDropdown(element) {
        return $bs.Dropdown.getInstance(element) || new $bs.Dropdown(element);
    }

    document.querySelectorAll('.dropdown-hover, .dropdown-hover-all .dropdown').forEach(function (dd) {
        dd.addEventListener('mouseenter', function (e) {
            let toggle = e.target.querySelector(':scope>[data-bs-toggle="dropdown"]');
            if (!toggle.classList.contains('show')) {
                getDropdown(toggle).toggle();
            }
        });
        dd.addEventListener('mouseleave', function (e) {
            let toggle = e.target.querySelector(':scope>[data-bs-toggle="dropdown"]');
            if (toggle.classList.contains('show')) {
                getDropdown(toggle).toggle();
            }
        });
    });
})(bootstrap);

(function ($bs) {
    const CLASS_NAME = 'has-child-dropdown-show';
    $bs.Dropdown.prototype.toggle = function (_orginal) {
        return function () {
            document.querySelectorAll('.' + CLASS_NAME).forEach(function (e) {
                e.classList.remove(CLASS_NAME);
            });
            let dd = this._element.closest('.dropdown').parentNode.closest('.dropdown');
            for (; dd && dd !== document; dd = dd.parentNode.closest('.dropdown')) {
                dd.classList.add(CLASS_NAME);
            }
            return _orginal.call(this);
        }
    }($bs.Dropdown.prototype.toggle);
    document.querySelectorAll('.dropdown').forEach(function (dd) {
        dd.addEventListener('hide.bs.dropdown', function (e) {
            if (this.classList.contains(CLASS_NAME)) {
                this.classList.remove(CLASS_NAME);
                e.preventDefault();
            }
            e.stopPropagation();
        });
    });

    function getDropdown(element) {
        return $bs.Dropdown.getInstance(element) || new $bs.Dropdown(element);
    }
    document.querySelectorAll('.dropdown-hover, .dropdown-hover-all .dropdown').forEach(function (dd) {
        dd.addEventListener('mouseenter', function (e) {
            let toggle = e.target.querySelector(':scope>[data-bs-toggle="dropdown"]');
            if (!toggle.classList.contains('show')) {
                getDropdown(toggle).toggle();
            }
        });
        dd.addEventListener('mouseleave', function (e) {
            let toggle = e.target.querySelector(':scope>[data-bs-toggle="dropdown"]');
            if (toggle.classList.contains('show')) {
                getDropdown(toggle).toggle();
            }
        });
    });
})(bootstrap);

(function ($) {
    $('.dropdown-menu a.dropdown-toggle').on('mouseover', function (e) {
        if (!$(this).next().hasClass('show')) {
            $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
        }
        var $subMenu = $(this).next(".dropdown-menu");
        $subMenu.toggleClass('show');

        $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function (e) {
            $('.dropdown-submenu .show').removeClass("show");
        });

        return false;
    });
})(jQuery);


$(document).ready(function () {
    if ($(window).width() <= 831) {
        $(".nav-link.dropdown-toggle").removeAttr("data-bs-hover");
        $(".nav-link.dropdown-toggle").attr("data-bs-toggle", "dropdown");
    }
    $('.nav-link').click(function () {
        var href = $(this).attr('href');
        window.location.href = href;
    });

    var myDropdown = document.getElementsByClassName('dropdown-toggle');
    for (i = 0; i < myDropdown.length; i++) {
        myDropdown[i].addEventListener('click', function () {
            var el = this.nextElementSibling;
            el.style.display = el.style.display == 'block' ? 'none' : 'block';
        });
    }
});


// Wait for the DOM (Document Object Model) to be fully loaded
document.addEventListener('DOMContentLoaded', function(event) {

  // Hamburger menu
  var navbarToggler = document.querySelectorAll('.navbar-toggler')[0];
  navbarToggler.addEventListener('click', function(e) {
    e.target.children[0].classList.toggle('active');
  });

  // Select the <html> element
  var html = document.querySelectorAll('html')[0];

  // Select the first element with the attribute 'data-bs-toggle-theme'
  var themeToggle = document.querySelectorAll('*[data-bs-toggle-theme]')[0];

  // Set the default theme to 'dark' for the <html> element
  html.setAttribute('data-bs-theme', 'dark');

  // Check if a themeToggle element is found
  if (themeToggle) {
    // Add a click event listener to the themeToggle element
    themeToggle.addEventListener('click', function(event) {
      // Prevent the default behavior of the click event
      event.preventDefault();

      // Check the current theme attribute value of the <html> element
      if (html.getAttribute('data-bs-theme') === 'dark') {
        // If the current theme is 'dark', change it to 'light'
        html.setAttribute('data-bs-theme', 'light');
      } else {
        // If the current theme is not 'dark', change it back to 'dark'
        html.setAttribute('data-bs-theme', 'dark');
      }
    });
  }
});


