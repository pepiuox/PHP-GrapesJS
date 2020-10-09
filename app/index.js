/*!
 * grapesjs-components-farmer
 * grapesjs plugin to build components for different css framework
 * 
 * @version v0.5.1
 * @author friends@niiknow.org
 * @homepage https://niiknow.github.io/grapesjs-components-farmer/
 * @repository https://github.com/niiknow/grapesjs-components-farmer.git
 */
(function webpackUniversalModuleDefinition(root, factory) {
	if(typeof exports === 'object' && typeof module === 'object')
		module.exports = factory(require("grapesjs"), require("jQuery"));
	else if(typeof define === 'function' && define.amd)
		define("grapesjs-components-farmer", ["grapesjs", "jQuery"], factory);
	else if(typeof exports === 'object')
		exports["grapesjs-components-farmer"] = factory(require("grapesjs"), require("jQuery"));
	else
		root["grapesjs-components-farmer"] = factory(root["grapesjs"], root["jQuery"]);
})(window, function(__WEBPACK_EXTERNAL_MODULE_grapesjs__, __WEBPACK_EXTERNAL_MODULE_jquery__) {
return /******/ (function(modules) { // webpackBootstrap
/******/ 	// The module cache
/******/ 	var installedModules = {};
/******/
/******/ 	// The require function
/******/ 	function __webpack_require__(moduleId) {
/******/
/******/ 		// Check if module is in cache
/******/ 		if(installedModules[moduleId]) {
/******/ 			return installedModules[moduleId].exports;
/******/ 		}
/******/ 		// Create a new module (and put it into the cache)
/******/ 		var module = installedModules[moduleId] = {
/******/ 			i: moduleId,
/******/ 			l: false,
/******/ 			exports: {}
/******/ 		};
/******/
/******/ 		// Execute the module function
/******/ 		modules[moduleId].call(module.exports, module, module.exports, __webpack_require__);
/******/
/******/ 		// Flag the module as loaded
/******/ 		module.l = true;
/******/
/******/ 		// Return the exports of the module
/******/ 		return module.exports;
/******/ 	}
/******/
/******/
/******/ 	// expose the modules object (__webpack_modules__)
/******/ 	__webpack_require__.m = modules;
/******/
/******/ 	// expose the module cache
/******/ 	__webpack_require__.c = installedModules;
/******/
/******/ 	// define getter function for harmony exports
/******/ 	__webpack_require__.d = function(exports, name, getter) {
/******/ 		if(!__webpack_require__.o(exports, name)) {
/******/ 			Object.defineProperty(exports, name, { enumerable: true, get: getter });
/******/ 		}
/******/ 	};
/******/
/******/ 	// define __esModule on exports
/******/ 	__webpack_require__.r = function(exports) {
/******/ 		if(typeof Symbol !== 'undefined' && Symbol.toStringTag) {
/******/ 			Object.defineProperty(exports, Symbol.toStringTag, { value: 'Module' });
/******/ 		}
/******/ 		Object.defineProperty(exports, '__esModule', { value: true });
/******/ 	};
/******/
/******/ 	// create a fake namespace object
/******/ 	// mode & 1: value is a module id, require it
/******/ 	// mode & 2: merge all properties of value into the ns
/******/ 	// mode & 4: return value when already ns object
/******/ 	// mode & 8|1: behave like require
/******/ 	__webpack_require__.t = function(value, mode) {
/******/ 		if(mode & 1) value = __webpack_require__(value);
/******/ 		if(mode & 8) return value;
/******/ 		if((mode & 4) && typeof value === 'object' && value && value.__esModule) return value;
/******/ 		var ns = Object.create(null);
/******/ 		__webpack_require__.r(ns);
/******/ 		Object.defineProperty(ns, 'default', { enumerable: true, value: value });
/******/ 		if(mode & 2 && typeof value != 'string') for(var key in value) __webpack_require__.d(ns, key, function(key) { return value[key]; }.bind(null, key));
/******/ 		return ns;
/******/ 	};
/******/
/******/ 	// getDefaultExport function for compatibility with non-harmony modules
/******/ 	__webpack_require__.n = function(module) {
/******/ 		var getter = module && module.__esModule ?
/******/ 			function getDefault() { return module['default']; } :
/******/ 			function getModuleExports() { return module; };
/******/ 		__webpack_require__.d(getter, 'a', getter);
/******/ 		return getter;
/******/ 	};
/******/
/******/ 	// Object.prototype.hasOwnProperty.call
/******/ 	__webpack_require__.o = function(object, property) { return Object.prototype.hasOwnProperty.call(object, property); };
/******/
/******/ 	// __webpack_public_path__
/******/ 	__webpack_require__.p = "/";
/******/
/******/
/******/ 	// Load entry module and return exports
/******/ 	return __webpack_require__(__webpack_require__.s = 0);
/******/ })
/************************************************************************/
/******/ ({

/***/ "./example/bootstrap4.js":
/*!*******************************!*\
  !*** ./example/bootstrap4.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var config = {
  canvas: {
    styles: ['https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css'],
    scripts: ['https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js']
  }
};
/* harmony default export */ __webpack_exports__["default"] = (config);

/***/ }),

/***/ "./example/custom-blocks.js":
/*!**********************************!*\
  !*** ./example/custom-blocks.js ***!
  \**********************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony default export */ __webpack_exports__["default"] = (function (editor) {
  var opts = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var bm = editor.BlockManager;
  bm.add('contact', {
    label: "<svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n        <path class=\"gjs-block-svg-path\" d=\"M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z\" fill-rule=\"nonzero\"></path>\n        <path class=\"gjs-block-svg-path\" d=\"M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z\" fill-rule=\"nonzero\"></path>\n        <rect class=\"gjs-block-svg-path\" x=\"2\" y=\"15\" width=\"10\" height=\"3\" rx=\"0.5\"></rect>\n      </svg>\n      <div class=\"gjs-block-label\">Contact</div></div>",
    category: opts.cateogyrLabel || 'Templates',
    content: {
      components: [{
        type: 'comp_input',
        label_attr: 'Name',
        name_attr: 'name',
        placeholder_attr: 'Name'
      }, {
        type: 'comp_input',
        label_attr: 'Email',
        name_attr: 'email',
        placeholder_attr: 'Email',
        type_attr: 'email'
      }, {
        type: 'comp_textarea',
        name_attr: 'message',
        placeholder_attr: 'Message',
        label_attr: 'Message'
      }, {
        type: 'comp_submit'
      }]
    }
  });
  bm.add('address', {
    label: "<svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n        <path class=\"gjs-block-svg-path\" d=\"M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z\" fill-rule=\"nonzero\"></path>\n        <path class=\"gjs-block-svg-path\" d=\"M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z\" fill-rule=\"nonzero\"></path>\n        <rect class=\"gjs-block-svg-path\" x=\"2\" y=\"15\" width=\"10\" height=\"3\" rx=\"0.5\"></rect>\n      </svg>\n      <div class=\"gjs-block-label\">Address</div></div>",
    category: opts.cateogyrLabel || 'Templates',
    content: {
      components: [{
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            type_attr: 'email',
            label_attr: 'Email',
            name_attr: 'email',
            placeholder_attr: 'Email'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'First name',
            name_attr: 'first_name',
            placeholder_attr: 'First name'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Last name',
            name_attr: 'last_name',
            placeholder_attr: 'Last name'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Street address',
            name_attr: 'address1',
            placeholder_attr: 'Street address'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Address line 2',
            name_attr: 'address2',
            placeholder_attr: 'Address line 2'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'City',
            name_attr: 'city',
            placeholder_attr: 'City'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'State / Province / Region',
            name_attr: 'state',
            placeholder_attr: 'State / Province / Region'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Postal / Zip Code',
            name_attr: 'postal',
            placeholder_attr: 'Postal / Zip Code'
          }]
        }]
      }]
    }
  });
  bm.add('donation', {
    label: "<svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n        <path class=\"gjs-block-svg-path\" d=\"M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z\" fill-rule=\"nonzero\"></path>\n        <path class=\"gjs-block-svg-path\" d=\"M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z\" fill-rule=\"nonzero\"></path>\n        <rect class=\"gjs-block-svg-path\" x=\"2\" y=\"15\" width=\"10\" height=\"3\" rx=\"0.5\"></rect>\n      </svg>\n      <div class=\"gjs-block-label\">Donation</div></div>",
    category: opts.cateogyrLabel || 'Templates',
    content: {
      components: [{
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            type_attr: 'email',
            label_attr: 'Email',
            name_attr: 'email',
            placeholder_attr: 'Email'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'First name',
            name_attr: 'first_name',
            placeholder_attr: 'First name'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Last name',
            name_attr: 'last_name',
            placeholder_attr: 'Last name'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Street address',
            name_attr: 'address1',
            placeholder_attr: 'Street address'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Address line 2',
            name_attr: 'address2',
            placeholder_attr: 'Address line 2'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'City',
            name_attr: 'city',
            placeholder_attr: 'City'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'State / Province / Region',
            name_attr: 'state',
            placeholder_attr: 'State / Province / Region'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Postal / Zip Code',
            name_attr: 'postal',
            placeholder_attr: 'Postal / Zip Code'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            type_attr: 'phone',
            label_attr: 'Phone #000-000-000',
            name_attr: 'phone',
            placeholder_attr: '000-000-000',
            pattern_attr: '000-000-000'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_checkbox',
            label_attr: 'I\'m retired or unemployed.',
            name_attr: 'is_retired_or_unemployed'
          }]
        }]
      }, {
        type: 'comp_row',
        components: [{
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Occupation',
            name_attr: 'occupation',
            placeholder_attr: 'Occupation'
          }]
        }, {
          type: 'comp_col',
          components: [{
            type: 'comp_input',
            label_attr: 'Employer',
            name_attr: 'employer',
            placeholder_attr: 'Employer'
          }]
        }]
      }]
    }
  });
});

/***/ }),

/***/ "./example/foundation.js":
/*!*******************************!*\
  !*** ./example/foundation.js ***!
  \*******************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var config = {
  canvas: {
    styles: ['https://cdn.jsdelivr.net/npm/foundation-sites@6.6.2/dist/css/foundation.min.css'],
    scripts: ['https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/foundation/6.6.2/js/foundation.min.js']
  },
  comps: {
    'comp_input': {
      label: 'Input',
      classes: 'cell large-12',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%>\n          <input <%= it.required_attr ? 'required ' : '' %>type=\"<%=it.type_attr%>\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n        </label>\n      "
    },
    'comp_select': {
      label: 'Select',
      classes: 'cell large-12',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%>\n          <select <%= it.multiple_attr ? 'multiple ' : '' %><%= it.required_attr ? 'required ' : '' %>id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\">\n            <option selected>-- Please select an option -- </option>\n            <% (it.option_attr + \"\").trim().split(\"\\n\").forEach(function(option){ %>\n            <% var msgProps = option.split('::');\n            %> <option value=\"<%= msgProps[0]%>\"><%= msgProps[1] || msgProps[0] %></option>\n            <% }); %>\n          </select>\n        </label>\n      "
    },
    'comp_textarea': {
      label: 'Textarea',
      classes: 'cell large-12',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%>\n          <textarea <%= it.required_attr ? 'required ' : '' %><%= it.rows_attr ? 'rows=\"' + it.rows_attr + '\" ' : '' %><%= it.rows_attr ? 'cols=\"' + it.rows_attr + '\" ' : '' %>id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\"><%= it.value_attr || '' %></textarea>\n        </label>\n      "
    },
    'comp_checkbox': {
      label: 'Checkbox',
      classes: 'cell large-12',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\">\n          <input <%= it.required_attr ? 'required ' : '' %>type=\"checkbox\"id=\"<%=it.name_attr%>\"  name=\"<%=it.name_attr%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n          <%=it.label_attr%>\n        </label>\n      "
    },
    'comp_hidden': {
      label: 'Hidden Input',
      useTag: 'div',
      template: "\n        <input <%= it.required_attr ? 'required ' : '' %>type=\"hidden\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n      "
    },
    'comp_submit': {
      label: 'Submit Button',
      classes: 'button',
      useTag: 'button',
      template: '<%= it.label_attr %>'
    },
    'comp_row': {
      label: 'Row',
      useTag: 'div',
      classes: 'grid-x'
    },
    'comp_col': {
      label: 'Column',
      useTag: 'div',
      classes: 'cell'
    },
    'comp_col1': {
      label: '1 Column',
      useTag: 'div',
      classes: 'large-12'
    },
    'comp_col2': {
      label: '2 Columns',
      useTag: 'div',
      classes: 'large-6'
    },
    'comp_col3': {
      label: '3 Columns',
      useTag: 'div',
      classes: 'large-4'
    },
    'comp_text': {
      label: 'Text'
    },
    'comp_image': {
      label: 'Image'
    },
    'comp_map': {
      label: 'Map'
    }
  }
};
/* harmony default export */ __webpack_exports__["default"] = (config);

/***/ }),

/***/ "./example/googlemd.js":
/*!*****************************!*\
  !*** ./example/googlemd.js ***!
  \*****************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
var config = {
  canvas: {
    styles: ['https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css'],
    scripts: ['https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js']
  },
  comps: {
    'comp_input': {
      label: 'Input',
      classes: 'input-field col s12',
      useTag: 'div',
      template: "\n        <input <%= it.required_attr ? 'required ' : '' %>type=\"<%=it.type_attr%>\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%></label>\n      "
    },
    'comp_select': {
      label: 'Select',
      classes: 'input-field col s12',
      useTag: 'div',
      template: "\n        <select <%= it.multiple_attr ? 'multiple ' : '' %><%= it.required_attr ? 'required ' : '' %>id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\">\n          <option selected>-- Please select an option -- </option>\n          <% (it.option_attr + \"\").trim().split(\"\\n\").forEach(function(option){ %>\n          <% var msgProps = option.split('::');\n          %> <option value=\"<%= msgProps[0]%>\"><%= msgProps[1] || msgProps[0] %></option>\n          <% }); %>\n        </select>\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%></label>\n      "
    },
    'comp_textarea': {
      label: 'Textarea',
      classes: 'input-field col s12',
      useTag: 'div',
      template: "\n        <textarea <%= it.required_attr ? 'required ' : '' %><%= it.rows_attr ? 'rows=\"' + it.rows_attr + '\" ' : '' %><%= it.rows_attr ? 'cols=\"' + it.rows_attr + '\" ' : '' %>id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\"><%= it.value_attr || '' %></textarea>\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%></label>\n      "
    },
    'comp_checkbox': {
      label: 'Checkbox',
      classes: 'row',
      useTag: 'div',
      template: "\n        <div class=\"input-field col s12\">\n          <label for=\"<%=it.name_attr%>\">\n            <input <%= it.required_attr ? 'required ' : '' %>type=\"checkbox\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n            <span><%=it.label_attr%></span>\n          </label>\n        </div>\n      "
    },
    'comp_hidden': {
      label: 'Hidden Input',
      useTag: 'div',
      template: "\n        <input <%= it.required_attr ? 'required ' : '' %>type=\"hidden\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n      "
    },
    'comp_submit': {
      label: 'Submit Button',
      classes: 'btn waves-effect waves-light',
      useTag: 'button',
      template: '<%= it.label_attr %>'
    },
    'comp_row': {
      label: 'Row',
      useTag: 'div',
      classes: 'row'
    },
    'comp_col': {
      label: 'Column',
      useTag: 'div'
    },
    'comp_col1': {
      label: '1 Column',
      useTag: 'div',
      classes: 'col s12'
    },
    'comp_col2': {
      label: '2 Columns',
      useTag: 'div',
      classes: 'col s6'
    },
    'comp_col3': {
      label: '3 Columns',
      useTag: 'div',
      classes: 'col s4'
    },
    'comp_text': {
      label: 'Text'
    },
    'comp_image': {
      label: 'Image'
    },
    'comp_map': {
      label: 'Map'
    }
  }
};
/* harmony default export */ __webpack_exports__["default"] = (config);

/***/ }),

/***/ "./example/index.js":
/*!**************************!*\
  !*** ./example/index.js ***!
  \**************************/
/*! no exports provided */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _init__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./init */ "./example/init.js");
/* harmony import */ var _bootstrap4__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! ./bootstrap4 */ "./example/bootstrap4.js");
/* harmony import */ var _foundation__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! ./foundation */ "./example/foundation.js");
/* harmony import */ var _googlemd__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./googlemd */ "./example/googlemd.js");





if (window.location.href.indexOf('css=foundation') > 0) {
  console.log('Foundation CSS');
  Object(_init__WEBPACK_IMPORTED_MODULE_0__["default"])(_foundation__WEBPACK_IMPORTED_MODULE_2__["default"]);
} else if (window.location.href.indexOf('css=materialize') > 0) {
  console.log('Google Materialize CSS');
  Object(_init__WEBPACK_IMPORTED_MODULE_0__["default"])(_googlemd__WEBPACK_IMPORTED_MODULE_3__["default"]);
} else {
  console.log('Bootstrap 4 CSS');
  Object(_init__WEBPACK_IMPORTED_MODULE_0__["default"])(_bootstrap4__WEBPACK_IMPORTED_MODULE_1__["default"]);
}

/***/ }),

/***/ "./example/init.js":
/*!*************************!*\
  !*** ./example/init.js ***!
  \*************************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _src__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ../src */ "./src/index.js");
/* harmony import */ var grapesjs__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! grapesjs */ "grapesjs");
/* harmony import */ var grapesjs__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(grapesjs__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_2__);
/* harmony import */ var _custom_blocks_js__WEBPACK_IMPORTED_MODULE_3__ = __webpack_require__(/*! ./custom-blocks.js */ "./example/custom-blocks.js");




/* harmony default export */ __webpack_exports__["default"] = (function () {
  var opts = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};
  var config = {
    container: '#gjs',
    height: '100%',
    noticeOnUnload: 0,
    showOffsets: 1,
    autorender: 1,
    allowScripts: 1,
    storageManager: {
      type: 'none'
    },
    forceClass: 0,
    avoidInlineStyle: 1,
    styleManager: {
      clearProperties: 1
    },
    fromElement: 1,
    canvas: opts.canvas,
    plugins: ['grapesjs-components-farmer', 'gjs-plugin-ckeditor'],
    pluginsOpts: {
      'grapesjs-components-farmer': {
        formNextId: 1,
        panel: 1,
        comps: opts.comps
      },
      'gjs-plugin-ckeditor': {
        position: 'center',
        options: {
          startupFocus: true,
          // Allows any class and any inline style
          extraAllowedContent: '*(*);*{*}',
          // Disable auto-formatting, class removing, etc.
          allowedContent: true,
          enterMode: CKEDITOR.ENTER_BR,
          extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
          toolbar: [{
            name: 'styles',
            items: ['Font', 'FontSize']
          }, ['Bold', 'Italic', 'Underline', 'Strike'], {
            name: 'paragraph',
            items: ['NumberedList', 'BulletedList']
          }, {
            name: 'links',
            items: ['Link', 'Unlink']
          }, {
            name: 'colors',
            items: ['TextColor', 'BGColor']
          }]
        }
      }
    }
  };
  var editor = grapesjs__WEBPACK_IMPORTED_MODULE_1___default.a.init(config);
  Object(_custom_blocks_js__WEBPACK_IMPORTED_MODULE_3__["default"])(editor, {
    categoryLabel: 'Templates'
  });
  var pfx = editor.getConfig().stylePrefix;
  var modal = editor.Modal;
  var cmdm = editor.Commands;
  var codeViewer = editor.CodeManager.getViewer('CodeMirror').clone();
  var pnm = editor.Panels;
  var container = document.createElement('div');
  codeViewer.set({
    codeName: 'htmlmixed',
    readOnly: 0,
    theme: 'hopscotch',
    autoBeautify: true,
    autoCloseTags: true,
    autoCloseBrackets: true,
    lineWrapping: true,
    styleActiveLine: true,
    smartIndent: true,
    indentWithTabs: true
  });
  cmdm.add('html-export-usable', {
    run: function run(editor, sender) {
      sender && sender.set('active', 0);
      var viewer = codeViewer.editor;
      modal.setTitle('Usable HTML');

      if (!viewer) {
        var txtarea = document.createElement('textarea');
        container.appendChild(txtarea);
        codeViewer.init(txtarea);
        viewer = codeViewer.editor;
      }

      modal.setContent('');
      modal.setContent(container);
      codeViewer.setContent(editor.runCommand('get-usable-html'));
      modal.open();
      viewer.refresh();
    }
  });
  pnm.addButton('options', [{
    id: 'export',
    className: 'fa fa-download',
    command: 'html-export-usable',
    attributes: {
      title: 'Export usable HTML'
    }
  }]);
});

/***/ }),

/***/ "./node_modules/lodash._reinterpolate/index.js":
/*!*****************************************************!*\
  !*** ./node_modules/lodash._reinterpolate/index.js ***!
  \*****************************************************/
/*! no static exports found */
/***/ (function(module, exports) {

/**
 * lodash 3.0.0 (Custom Build) <https://lodash.com/>
 * Build: `lodash modern modularize exports="npm" -o ./`
 * Copyright 2012-2015 The Dojo Foundation <http://dojofoundation.org/>
 * Based on Underscore.js 1.7.0 <http://underscorejs.org/LICENSE>
 * Copyright 2009-2015 Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 * Available under MIT license <https://lodash.com/license>
 */

/** Used to match template delimiters. */
var reInterpolate = /<%=([\s\S]+?)%>/g;

module.exports = reInterpolate;


/***/ }),

/***/ "./node_modules/lodash.template/index.js":
/*!***********************************************!*\
  !*** ./node_modules/lodash.template/index.js ***!
  \***********************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global, module) {/**
 * Lodash (Custom Build) <https://lodash.com/>
 * Build: `lodash modularize exports="npm" -o ./`
 * Copyright OpenJS Foundation and other contributors <https://openjsf.org/>
 * Released under MIT license <https://lodash.com/license>
 * Based on Underscore.js 1.8.3 <http://underscorejs.org/LICENSE>
 * Copyright Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 */
var reInterpolate = __webpack_require__(/*! lodash._reinterpolate */ "./node_modules/lodash._reinterpolate/index.js"),
    templateSettings = __webpack_require__(/*! lodash.templatesettings */ "./node_modules/lodash.templatesettings/index.js");

/** Used to detect hot functions by number of calls within a span of milliseconds. */
var HOT_COUNT = 800,
    HOT_SPAN = 16;

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0,
    MAX_SAFE_INTEGER = 9007199254740991;

/** `Object#toString` result references. */
var argsTag = '[object Arguments]',
    arrayTag = '[object Array]',
    asyncTag = '[object AsyncFunction]',
    boolTag = '[object Boolean]',
    dateTag = '[object Date]',
    domExcTag = '[object DOMException]',
    errorTag = '[object Error]',
    funcTag = '[object Function]',
    genTag = '[object GeneratorFunction]',
    mapTag = '[object Map]',
    numberTag = '[object Number]',
    nullTag = '[object Null]',
    objectTag = '[object Object]',
    proxyTag = '[object Proxy]',
    regexpTag = '[object RegExp]',
    setTag = '[object Set]',
    stringTag = '[object String]',
    symbolTag = '[object Symbol]',
    undefinedTag = '[object Undefined]',
    weakMapTag = '[object WeakMap]';

var arrayBufferTag = '[object ArrayBuffer]',
    dataViewTag = '[object DataView]',
    float32Tag = '[object Float32Array]',
    float64Tag = '[object Float64Array]',
    int8Tag = '[object Int8Array]',
    int16Tag = '[object Int16Array]',
    int32Tag = '[object Int32Array]',
    uint8Tag = '[object Uint8Array]',
    uint8ClampedTag = '[object Uint8ClampedArray]',
    uint16Tag = '[object Uint16Array]',
    uint32Tag = '[object Uint32Array]';

/** Used to match empty string literals in compiled template source. */
var reEmptyStringLeading = /\b__p \+= '';/g,
    reEmptyStringMiddle = /\b(__p \+=) '' \+/g,
    reEmptyStringTrailing = /(__e\(.*?\)|\b__t\)) \+\n'';/g;

/**
 * Used to match `RegExp`
 * [syntax characters](http://ecma-international.org/ecma-262/7.0/#sec-patterns).
 */
var reRegExpChar = /[\\^$.*+?()[\]{}|]/g;

/**
 * Used to match
 * [ES template delimiters](http://ecma-international.org/ecma-262/7.0/#sec-template-literal-lexical-components).
 */
var reEsTemplate = /\$\{([^\\}]*(?:\\.[^\\}]*)*)\}/g;

/** Used to detect host constructors (Safari). */
var reIsHostCtor = /^\[object .+?Constructor\]$/;

/** Used to detect unsigned integer values. */
var reIsUint = /^(?:0|[1-9]\d*)$/;

/** Used to ensure capturing order of template delimiters. */
var reNoMatch = /($^)/;

/** Used to match unescaped characters in compiled string literals. */
var reUnescapedString = /['\n\r\u2028\u2029\\]/g;

/** Used to identify `toStringTag` values of typed arrays. */
var typedArrayTags = {};
typedArrayTags[float32Tag] = typedArrayTags[float64Tag] =
typedArrayTags[int8Tag] = typedArrayTags[int16Tag] =
typedArrayTags[int32Tag] = typedArrayTags[uint8Tag] =
typedArrayTags[uint8ClampedTag] = typedArrayTags[uint16Tag] =
typedArrayTags[uint32Tag] = true;
typedArrayTags[argsTag] = typedArrayTags[arrayTag] =
typedArrayTags[arrayBufferTag] = typedArrayTags[boolTag] =
typedArrayTags[dataViewTag] = typedArrayTags[dateTag] =
typedArrayTags[errorTag] = typedArrayTags[funcTag] =
typedArrayTags[mapTag] = typedArrayTags[numberTag] =
typedArrayTags[objectTag] = typedArrayTags[regexpTag] =
typedArrayTags[setTag] = typedArrayTags[stringTag] =
typedArrayTags[weakMapTag] = false;

/** Used to escape characters for inclusion in compiled string literals. */
var stringEscapes = {
  '\\': '\\',
  "'": "'",
  '\n': 'n',
  '\r': 'r',
  '\u2028': 'u2028',
  '\u2029': 'u2029'
};

/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof global == 'object' && global && global.Object === Object && global;

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

/** Detect free variable `exports`. */
var freeExports =  true && exports && !exports.nodeType && exports;

/** Detect free variable `module`. */
var freeModule = freeExports && typeof module == 'object' && module && !module.nodeType && module;

/** Detect the popular CommonJS extension `module.exports`. */
var moduleExports = freeModule && freeModule.exports === freeExports;

/** Detect free variable `process` from Node.js. */
var freeProcess = moduleExports && freeGlobal.process;

/** Used to access faster Node.js helpers. */
var nodeUtil = (function() {
  try {
    // Use `util.types` for Node.js 10+.
    var types = freeModule && freeModule.require && freeModule.require('util').types;

    if (types) {
      return types;
    }

    // Legacy `process.binding('util')` for Node.js < 10.
    return freeProcess && freeProcess.binding && freeProcess.binding('util');
  } catch (e) {}
}());

/* Node.js helper references. */
var nodeIsTypedArray = nodeUtil && nodeUtil.isTypedArray;

/**
 * A faster alternative to `Function#apply`, this function invokes `func`
 * with the `this` binding of `thisArg` and the arguments of `args`.
 *
 * @private
 * @param {Function} func The function to invoke.
 * @param {*} thisArg The `this` binding of `func`.
 * @param {Array} args The arguments to invoke `func` with.
 * @returns {*} Returns the result of `func`.
 */
function apply(func, thisArg, args) {
  switch (args.length) {
    case 0: return func.call(thisArg);
    case 1: return func.call(thisArg, args[0]);
    case 2: return func.call(thisArg, args[0], args[1]);
    case 3: return func.call(thisArg, args[0], args[1], args[2]);
  }
  return func.apply(thisArg, args);
}

/**
 * A specialized version of `_.map` for arrays without support for iteratee
 * shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 */
function arrayMap(array, iteratee) {
  var index = -1,
      length = array == null ? 0 : array.length,
      result = Array(length);

  while (++index < length) {
    result[index] = iteratee(array[index], index, array);
  }
  return result;
}

/**
 * The base implementation of `_.times` without support for iteratee shorthands
 * or max array length checks.
 *
 * @private
 * @param {number} n The number of times to invoke `iteratee`.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the array of results.
 */
function baseTimes(n, iteratee) {
  var index = -1,
      result = Array(n);

  while (++index < n) {
    result[index] = iteratee(index);
  }
  return result;
}

/**
 * The base implementation of `_.unary` without support for storing metadata.
 *
 * @private
 * @param {Function} func The function to cap arguments for.
 * @returns {Function} Returns the new capped function.
 */
function baseUnary(func) {
  return function(value) {
    return func(value);
  };
}

/**
 * The base implementation of `_.values` and `_.valuesIn` which creates an
 * array of `object` property values corresponding to the property names
 * of `props`.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {Array} props The property names to get values for.
 * @returns {Object} Returns the array of property values.
 */
function baseValues(object, props) {
  return arrayMap(props, function(key) {
    return object[key];
  });
}

/**
 * Used by `_.template` to escape characters for inclusion in compiled string literals.
 *
 * @private
 * @param {string} chr The matched character to escape.
 * @returns {string} Returns the escaped character.
 */
function escapeStringChar(chr) {
  return '\\' + stringEscapes[chr];
}

/**
 * Gets the value at `key` of `object`.
 *
 * @private
 * @param {Object} [object] The object to query.
 * @param {string} key The key of the property to get.
 * @returns {*} Returns the property value.
 */
function getValue(object, key) {
  return object == null ? undefined : object[key];
}

/**
 * Creates a unary function that invokes `func` with its argument transformed.
 *
 * @private
 * @param {Function} func The function to wrap.
 * @param {Function} transform The argument transform.
 * @returns {Function} Returns the new function.
 */
function overArg(func, transform) {
  return function(arg) {
    return func(transform(arg));
  };
}

/** Used for built-in method references. */
var funcProto = Function.prototype,
    objectProto = Object.prototype;

/** Used to detect overreaching core-js shims. */
var coreJsData = root['__core-js_shared__'];

/** Used to resolve the decompiled source of functions. */
var funcToString = funcProto.toString;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/** Used to detect methods masquerading as native. */
var maskSrcKey = (function() {
  var uid = /[^.]+$/.exec(coreJsData && coreJsData.keys && coreJsData.keys.IE_PROTO || '');
  return uid ? ('Symbol(src)_1.' + uid) : '';
}());

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Used to infer the `Object` constructor. */
var objectCtorString = funcToString.call(Object);

/** Used to detect if a method is native. */
var reIsNative = RegExp('^' +
  funcToString.call(hasOwnProperty).replace(reRegExpChar, '\\$&')
  .replace(/hasOwnProperty|(function).*?(?=\\\()| for .+?(?=\\\])/g, '$1.*?') + '$'
);

/** Built-in value references. */
var Buffer = moduleExports ? root.Buffer : undefined,
    Symbol = root.Symbol,
    getPrototype = overArg(Object.getPrototypeOf, Object),
    propertyIsEnumerable = objectProto.propertyIsEnumerable,
    symToStringTag = Symbol ? Symbol.toStringTag : undefined;

var defineProperty = (function() {
  try {
    var func = getNative(Object, 'defineProperty');
    func({}, '', {});
    return func;
  } catch (e) {}
}());

/* Built-in method references for those with the same name as other `lodash` methods. */
var nativeIsBuffer = Buffer ? Buffer.isBuffer : undefined,
    nativeKeys = overArg(Object.keys, Object),
    nativeMax = Math.max,
    nativeNow = Date.now;

/** Used to convert symbols to primitives and strings. */
var symbolProto = Symbol ? Symbol.prototype : undefined,
    symbolToString = symbolProto ? symbolProto.toString : undefined;

/**
 * Creates an array of the enumerable property names of the array-like `value`.
 *
 * @private
 * @param {*} value The value to query.
 * @param {boolean} inherited Specify returning inherited property names.
 * @returns {Array} Returns the array of property names.
 */
function arrayLikeKeys(value, inherited) {
  var isArr = isArray(value),
      isArg = !isArr && isArguments(value),
      isBuff = !isArr && !isArg && isBuffer(value),
      isType = !isArr && !isArg && !isBuff && isTypedArray(value),
      skipIndexes = isArr || isArg || isBuff || isType,
      result = skipIndexes ? baseTimes(value.length, String) : [],
      length = result.length;

  for (var key in value) {
    if ((inherited || hasOwnProperty.call(value, key)) &&
        !(skipIndexes && (
           // Safari 9 has enumerable `arguments.length` in strict mode.
           key == 'length' ||
           // Node.js 0.10 has enumerable non-index properties on buffers.
           (isBuff && (key == 'offset' || key == 'parent')) ||
           // PhantomJS 2 has enumerable non-index properties on typed arrays.
           (isType && (key == 'buffer' || key == 'byteLength' || key == 'byteOffset')) ||
           // Skip index properties.
           isIndex(key, length)
        ))) {
      result.push(key);
    }
  }
  return result;
}

/**
 * Assigns `value` to `key` of `object` if the existing value is not equivalent
 * using [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * for equality comparisons.
 *
 * @private
 * @param {Object} object The object to modify.
 * @param {string} key The key of the property to assign.
 * @param {*} value The value to assign.
 */
function assignValue(object, key, value) {
  var objValue = object[key];
  if (!(hasOwnProperty.call(object, key) && eq(objValue, value)) ||
      (value === undefined && !(key in object))) {
    baseAssignValue(object, key, value);
  }
}

/**
 * The base implementation of `assignValue` and `assignMergeValue` without
 * value checks.
 *
 * @private
 * @param {Object} object The object to modify.
 * @param {string} key The key of the property to assign.
 * @param {*} value The value to assign.
 */
function baseAssignValue(object, key, value) {
  if (key == '__proto__' && defineProperty) {
    defineProperty(object, key, {
      'configurable': true,
      'enumerable': true,
      'value': value,
      'writable': true
    });
  } else {
    object[key] = value;
  }
}

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

/**
 * The base implementation of `_.isArguments`.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
 */
function baseIsArguments(value) {
  return isObjectLike(value) && baseGetTag(value) == argsTag;
}

/**
 * The base implementation of `_.isNative` without bad shim checks.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a native function,
 *  else `false`.
 */
function baseIsNative(value) {
  if (!isObject(value) || isMasked(value)) {
    return false;
  }
  var pattern = isFunction(value) ? reIsNative : reIsHostCtor;
  return pattern.test(toSource(value));
}

/**
 * The base implementation of `_.isTypedArray` without Node.js optimizations.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
 */
function baseIsTypedArray(value) {
  return isObjectLike(value) &&
    isLength(value.length) && !!typedArrayTags[baseGetTag(value)];
}

/**
 * The base implementation of `_.keys` which doesn't treat sparse arrays as dense.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */
function baseKeys(object) {
  if (!isPrototype(object)) {
    return nativeKeys(object);
  }
  var result = [];
  for (var key in Object(object)) {
    if (hasOwnProperty.call(object, key) && key != 'constructor') {
      result.push(key);
    }
  }
  return result;
}

/**
 * The base implementation of `_.keysIn` which doesn't treat sparse arrays as dense.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */
function baseKeysIn(object) {
  if (!isObject(object)) {
    return nativeKeysIn(object);
  }
  var isProto = isPrototype(object),
      result = [];

  for (var key in object) {
    if (!(key == 'constructor' && (isProto || !hasOwnProperty.call(object, key)))) {
      result.push(key);
    }
  }
  return result;
}

/**
 * The base implementation of `_.rest` which doesn't validate or coerce arguments.
 *
 * @private
 * @param {Function} func The function to apply a rest parameter to.
 * @param {number} [start=func.length-1] The start position of the rest parameter.
 * @returns {Function} Returns the new function.
 */
function baseRest(func, start) {
  return setToString(overRest(func, start, identity), func + '');
}

/**
 * The base implementation of `setToString` without support for hot loop shorting.
 *
 * @private
 * @param {Function} func The function to modify.
 * @param {Function} string The `toString` result.
 * @returns {Function} Returns `func`.
 */
var baseSetToString = !defineProperty ? identity : function(func, string) {
  return defineProperty(func, 'toString', {
    'configurable': true,
    'enumerable': false,
    'value': constant(string),
    'writable': true
  });
};

/**
 * The base implementation of `_.toString` which doesn't convert nullish
 * values to empty strings.
 *
 * @private
 * @param {*} value The value to process.
 * @returns {string} Returns the string.
 */
function baseToString(value) {
  // Exit early for strings to avoid a performance hit in some environments.
  if (typeof value == 'string') {
    return value;
  }
  if (isArray(value)) {
    // Recursively convert values (susceptible to call stack limits).
    return arrayMap(value, baseToString) + '';
  }
  if (isSymbol(value)) {
    return symbolToString ? symbolToString.call(value) : '';
  }
  var result = (value + '');
  return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;
}

/**
 * Copies properties of `source` to `object`.
 *
 * @private
 * @param {Object} source The object to copy properties from.
 * @param {Array} props The property identifiers to copy.
 * @param {Object} [object={}] The object to copy properties to.
 * @param {Function} [customizer] The function to customize copied values.
 * @returns {Object} Returns `object`.
 */
function copyObject(source, props, object, customizer) {
  var isNew = !object;
  object || (object = {});

  var index = -1,
      length = props.length;

  while (++index < length) {
    var key = props[index];

    var newValue = customizer
      ? customizer(object[key], source[key], key, object, source)
      : undefined;

    if (newValue === undefined) {
      newValue = source[key];
    }
    if (isNew) {
      baseAssignValue(object, key, newValue);
    } else {
      assignValue(object, key, newValue);
    }
  }
  return object;
}

/**
 * Creates a function like `_.assign`.
 *
 * @private
 * @param {Function} assigner The function to assign values.
 * @returns {Function} Returns the new assigner function.
 */
function createAssigner(assigner) {
  return baseRest(function(object, sources) {
    var index = -1,
        length = sources.length,
        customizer = length > 1 ? sources[length - 1] : undefined,
        guard = length > 2 ? sources[2] : undefined;

    customizer = (assigner.length > 3 && typeof customizer == 'function')
      ? (length--, customizer)
      : undefined;

    if (guard && isIterateeCall(sources[0], sources[1], guard)) {
      customizer = length < 3 ? undefined : customizer;
      length = 1;
    }
    object = Object(object);
    while (++index < length) {
      var source = sources[index];
      if (source) {
        assigner(object, source, index, customizer);
      }
    }
    return object;
  });
}

/**
 * Used by `_.defaults` to customize its `_.assignIn` use to assign properties
 * of source objects to the destination object for all destination properties
 * that resolve to `undefined`.
 *
 * @private
 * @param {*} objValue The destination value.
 * @param {*} srcValue The source value.
 * @param {string} key The key of the property to assign.
 * @param {Object} object The parent object of `objValue`.
 * @returns {*} Returns the value to assign.
 */
function customDefaultsAssignIn(objValue, srcValue, key, object) {
  if (objValue === undefined ||
      (eq(objValue, objectProto[key]) && !hasOwnProperty.call(object, key))) {
    return srcValue;
  }
  return objValue;
}

/**
 * Gets the native function at `key` of `object`.
 *
 * @private
 * @param {Object} object The object to query.
 * @param {string} key The key of the method to get.
 * @returns {*} Returns the function if it's native, else `undefined`.
 */
function getNative(object, key) {
  var value = getValue(object, key);
  return baseIsNative(value) ? value : undefined;
}

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

/**
 * Checks if `value` is a valid array-like index.
 *
 * @private
 * @param {*} value The value to check.
 * @param {number} [length=MAX_SAFE_INTEGER] The upper bounds of a valid index.
 * @returns {boolean} Returns `true` if `value` is a valid index, else `false`.
 */
function isIndex(value, length) {
  var type = typeof value;
  length = length == null ? MAX_SAFE_INTEGER : length;

  return !!length &&
    (type == 'number' ||
      (type != 'symbol' && reIsUint.test(value))) &&
        (value > -1 && value % 1 == 0 && value < length);
}

/**
 * Checks if the given arguments are from an iteratee call.
 *
 * @private
 * @param {*} value The potential iteratee value argument.
 * @param {*} index The potential iteratee index or key argument.
 * @param {*} object The potential iteratee object argument.
 * @returns {boolean} Returns `true` if the arguments are from an iteratee call,
 *  else `false`.
 */
function isIterateeCall(value, index, object) {
  if (!isObject(object)) {
    return false;
  }
  var type = typeof index;
  if (type == 'number'
        ? (isArrayLike(object) && isIndex(index, object.length))
        : (type == 'string' && index in object)
      ) {
    return eq(object[index], value);
  }
  return false;
}

/**
 * Checks if `func` has its source masked.
 *
 * @private
 * @param {Function} func The function to check.
 * @returns {boolean} Returns `true` if `func` is masked, else `false`.
 */
function isMasked(func) {
  return !!maskSrcKey && (maskSrcKey in func);
}

/**
 * Checks if `value` is likely a prototype object.
 *
 * @private
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a prototype, else `false`.
 */
function isPrototype(value) {
  var Ctor = value && value.constructor,
      proto = (typeof Ctor == 'function' && Ctor.prototype) || objectProto;

  return value === proto;
}

/**
 * This function is like
 * [`Object.keys`](http://ecma-international.org/ecma-262/7.0/#sec-object.keys)
 * except that it includes inherited enumerable properties.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 */
function nativeKeysIn(object) {
  var result = [];
  if (object != null) {
    for (var key in Object(object)) {
      result.push(key);
    }
  }
  return result;
}

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

/**
 * A specialized version of `baseRest` which transforms the rest array.
 *
 * @private
 * @param {Function} func The function to apply a rest parameter to.
 * @param {number} [start=func.length-1] The start position of the rest parameter.
 * @param {Function} transform The rest array transform.
 * @returns {Function} Returns the new function.
 */
function overRest(func, start, transform) {
  start = nativeMax(start === undefined ? (func.length - 1) : start, 0);
  return function() {
    var args = arguments,
        index = -1,
        length = nativeMax(args.length - start, 0),
        array = Array(length);

    while (++index < length) {
      array[index] = args[start + index];
    }
    index = -1;
    var otherArgs = Array(start + 1);
    while (++index < start) {
      otherArgs[index] = args[index];
    }
    otherArgs[start] = transform(array);
    return apply(func, this, otherArgs);
  };
}

/**
 * Sets the `toString` method of `func` to return `string`.
 *
 * @private
 * @param {Function} func The function to modify.
 * @param {Function} string The `toString` result.
 * @returns {Function} Returns `func`.
 */
var setToString = shortOut(baseSetToString);

/**
 * Creates a function that'll short out and invoke `identity` instead
 * of `func` when it's called `HOT_COUNT` or more times in `HOT_SPAN`
 * milliseconds.
 *
 * @private
 * @param {Function} func The function to restrict.
 * @returns {Function} Returns the new shortable function.
 */
function shortOut(func) {
  var count = 0,
      lastCalled = 0;

  return function() {
    var stamp = nativeNow(),
        remaining = HOT_SPAN - (stamp - lastCalled);

    lastCalled = stamp;
    if (remaining > 0) {
      if (++count >= HOT_COUNT) {
        return arguments[0];
      }
    } else {
      count = 0;
    }
    return func.apply(undefined, arguments);
  };
}

/**
 * Converts `func` to its source code.
 *
 * @private
 * @param {Function} func The function to convert.
 * @returns {string} Returns the source code.
 */
function toSource(func) {
  if (func != null) {
    try {
      return funcToString.call(func);
    } catch (e) {}
    try {
      return (func + '');
    } catch (e) {}
  }
  return '';
}

/**
 * Performs a
 * [`SameValueZero`](http://ecma-international.org/ecma-262/7.0/#sec-samevaluezero)
 * comparison between two values to determine if they are equivalent.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to compare.
 * @param {*} other The other value to compare.
 * @returns {boolean} Returns `true` if the values are equivalent, else `false`.
 * @example
 *
 * var object = { 'a': 1 };
 * var other = { 'a': 1 };
 *
 * _.eq(object, object);
 * // => true
 *
 * _.eq(object, other);
 * // => false
 *
 * _.eq('a', 'a');
 * // => true
 *
 * _.eq('a', Object('a'));
 * // => false
 *
 * _.eq(NaN, NaN);
 * // => true
 */
function eq(value, other) {
  return value === other || (value !== value && other !== other);
}

/**
 * Checks if `value` is likely an `arguments` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an `arguments` object,
 *  else `false`.
 * @example
 *
 * _.isArguments(function() { return arguments; }());
 * // => true
 *
 * _.isArguments([1, 2, 3]);
 * // => false
 */
var isArguments = baseIsArguments(function() { return arguments; }()) ? baseIsArguments : function(value) {
  return isObjectLike(value) && hasOwnProperty.call(value, 'callee') &&
    !propertyIsEnumerable.call(value, 'callee');
};

/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */
var isArray = Array.isArray;

/**
 * Checks if `value` is array-like. A value is considered array-like if it's
 * not a function and has a `value.length` that's an integer greater than or
 * equal to `0` and less than or equal to `Number.MAX_SAFE_INTEGER`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is array-like, else `false`.
 * @example
 *
 * _.isArrayLike([1, 2, 3]);
 * // => true
 *
 * _.isArrayLike(document.body.children);
 * // => true
 *
 * _.isArrayLike('abc');
 * // => true
 *
 * _.isArrayLike(_.noop);
 * // => false
 */
function isArrayLike(value) {
  return value != null && isLength(value.length) && !isFunction(value);
}

/**
 * Checks if `value` is a buffer.
 *
 * @static
 * @memberOf _
 * @since 4.3.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a buffer, else `false`.
 * @example
 *
 * _.isBuffer(new Buffer(2));
 * // => true
 *
 * _.isBuffer(new Uint8Array(2));
 * // => false
 */
var isBuffer = nativeIsBuffer || stubFalse;

/**
 * Checks if `value` is an `Error`, `EvalError`, `RangeError`, `ReferenceError`,
 * `SyntaxError`, `TypeError`, or `URIError` object.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an error object, else `false`.
 * @example
 *
 * _.isError(new Error);
 * // => true
 *
 * _.isError(Error);
 * // => false
 */
function isError(value) {
  if (!isObjectLike(value)) {
    return false;
  }
  var tag = baseGetTag(value);
  return tag == errorTag || tag == domExcTag ||
    (typeof value.message == 'string' && typeof value.name == 'string' && !isPlainObject(value));
}

/**
 * Checks if `value` is classified as a `Function` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a function, else `false`.
 * @example
 *
 * _.isFunction(_);
 * // => true
 *
 * _.isFunction(/abc/);
 * // => false
 */
function isFunction(value) {
  if (!isObject(value)) {
    return false;
  }
  // The use of `Object#toString` avoids issues with the `typeof` operator
  // in Safari 9 which returns 'object' for typed arrays and other constructors.
  var tag = baseGetTag(value);
  return tag == funcTag || tag == genTag || tag == asyncTag || tag == proxyTag;
}

/**
 * Checks if `value` is a valid array-like length.
 *
 * **Note:** This method is loosely based on
 * [`ToLength`](http://ecma-international.org/ecma-262/7.0/#sec-tolength).
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a valid length, else `false`.
 * @example
 *
 * _.isLength(3);
 * // => true
 *
 * _.isLength(Number.MIN_VALUE);
 * // => false
 *
 * _.isLength(Infinity);
 * // => false
 *
 * _.isLength('3');
 * // => false
 */
function isLength(value) {
  return typeof value == 'number' &&
    value > -1 && value % 1 == 0 && value <= MAX_SAFE_INTEGER;
}

/**
 * Checks if `value` is the
 * [language type](http://www.ecma-international.org/ecma-262/7.0/#sec-ecmascript-language-types)
 * of `Object`. (e.g. arrays, functions, objects, regexes, `new Number(0)`, and `new String('')`)
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an object, else `false`.
 * @example
 *
 * _.isObject({});
 * // => true
 *
 * _.isObject([1, 2, 3]);
 * // => true
 *
 * _.isObject(_.noop);
 * // => true
 *
 * _.isObject(null);
 * // => false
 */
function isObject(value) {
  var type = typeof value;
  return value != null && (type == 'object' || type == 'function');
}

/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

/**
 * Checks if `value` is a plain object, that is, an object created by the
 * `Object` constructor or one with a `[[Prototype]]` of `null`.
 *
 * @static
 * @memberOf _
 * @since 0.8.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a plain object, else `false`.
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 * }
 *
 * _.isPlainObject(new Foo);
 * // => false
 *
 * _.isPlainObject([1, 2, 3]);
 * // => false
 *
 * _.isPlainObject({ 'x': 0, 'y': 0 });
 * // => true
 *
 * _.isPlainObject(Object.create(null));
 * // => true
 */
function isPlainObject(value) {
  if (!isObjectLike(value) || baseGetTag(value) != objectTag) {
    return false;
  }
  var proto = getPrototype(value);
  if (proto === null) {
    return true;
  }
  var Ctor = hasOwnProperty.call(proto, 'constructor') && proto.constructor;
  return typeof Ctor == 'function' && Ctor instanceof Ctor &&
    funcToString.call(Ctor) == objectCtorString;
}

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

/**
 * Checks if `value` is classified as a typed array.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a typed array, else `false`.
 * @example
 *
 * _.isTypedArray(new Uint8Array);
 * // => true
 *
 * _.isTypedArray([]);
 * // => false
 */
var isTypedArray = nodeIsTypedArray ? baseUnary(nodeIsTypedArray) : baseIsTypedArray;

/**
 * Converts `value` to a string. An empty string is returned for `null`
 * and `undefined` values. The sign of `-0` is preserved.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 * @example
 *
 * _.toString(null);
 * // => ''
 *
 * _.toString(-0);
 * // => '-0'
 *
 * _.toString([1, 2, 3]);
 * // => '1,2,3'
 */
function toString(value) {
  return value == null ? '' : baseToString(value);
}

/**
 * This method is like `_.assignIn` except that it accepts `customizer`
 * which is invoked to produce the assigned values. If `customizer` returns
 * `undefined`, assignment is handled by the method instead. The `customizer`
 * is invoked with five arguments: (objValue, srcValue, key, object, source).
 *
 * **Note:** This method mutates `object`.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @alias extendWith
 * @category Object
 * @param {Object} object The destination object.
 * @param {...Object} sources The source objects.
 * @param {Function} [customizer] The function to customize assigned values.
 * @returns {Object} Returns `object`.
 * @see _.assignWith
 * @example
 *
 * function customizer(objValue, srcValue) {
 *   return _.isUndefined(objValue) ? srcValue : objValue;
 * }
 *
 * var defaults = _.partialRight(_.assignInWith, customizer);
 *
 * defaults({ 'a': 1 }, { 'b': 2 }, { 'a': 3 });
 * // => { 'a': 1, 'b': 2 }
 */
var assignInWith = createAssigner(function(object, source, srcIndex, customizer) {
  copyObject(source, keysIn(source), object, customizer);
});

/**
 * Creates an array of the own enumerable property names of `object`.
 *
 * **Note:** Non-object values are coerced to objects. See the
 * [ES spec](http://ecma-international.org/ecma-262/7.0/#sec-object.keys)
 * for more details.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Object
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 *   this.b = 2;
 * }
 *
 * Foo.prototype.c = 3;
 *
 * _.keys(new Foo);
 * // => ['a', 'b'] (iteration order is not guaranteed)
 *
 * _.keys('hi');
 * // => ['0', '1']
 */
function keys(object) {
  return isArrayLike(object) ? arrayLikeKeys(object) : baseKeys(object);
}

/**
 * Creates an array of the own and inherited enumerable property names of `object`.
 *
 * **Note:** Non-object values are coerced to objects.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category Object
 * @param {Object} object The object to query.
 * @returns {Array} Returns the array of property names.
 * @example
 *
 * function Foo() {
 *   this.a = 1;
 *   this.b = 2;
 * }
 *
 * Foo.prototype.c = 3;
 *
 * _.keysIn(new Foo);
 * // => ['a', 'b', 'c'] (iteration order is not guaranteed)
 */
function keysIn(object) {
  return isArrayLike(object) ? arrayLikeKeys(object, true) : baseKeysIn(object);
}

/**
 * Creates a compiled template function that can interpolate data properties
 * in "interpolate" delimiters, HTML-escape interpolated data properties in
 * "escape" delimiters, and execute JavaScript in "evaluate" delimiters. Data
 * properties may be accessed as free variables in the template. If a setting
 * object is given, it takes precedence over `_.templateSettings` values.
 *
 * **Note:** In the development build `_.template` utilizes
 * [sourceURLs](http://www.html5rocks.com/en/tutorials/developertools/sourcemaps/#toc-sourceurl)
 * for easier debugging.
 *
 * For more information on precompiling templates see
 * [lodash's custom builds documentation](https://lodash.com/custom-builds).
 *
 * For more information on Chrome extension sandboxes see
 * [Chrome's extensions documentation](https://developer.chrome.com/extensions/sandboxingEval).
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category String
 * @param {string} [string=''] The template string.
 * @param {Object} [options={}] The options object.
 * @param {RegExp} [options.escape=_.templateSettings.escape]
 *  The HTML "escape" delimiter.
 * @param {RegExp} [options.evaluate=_.templateSettings.evaluate]
 *  The "evaluate" delimiter.
 * @param {Object} [options.imports=_.templateSettings.imports]
 *  An object to import into the template as free variables.
 * @param {RegExp} [options.interpolate=_.templateSettings.interpolate]
 *  The "interpolate" delimiter.
 * @param {string} [options.sourceURL='templateSources[n]']
 *  The sourceURL of the compiled template.
 * @param {string} [options.variable='obj']
 *  The data object variable name.
 * @param- {Object} [guard] Enables use as an iteratee for methods like `_.map`.
 * @returns {Function} Returns the compiled template function.
 * @example
 *
 * // Use the "interpolate" delimiter to create a compiled template.
 * var compiled = _.template('hello <%= user %>!');
 * compiled({ 'user': 'fred' });
 * // => 'hello fred!'
 *
 * // Use the HTML "escape" delimiter to escape data property values.
 * var compiled = _.template('<b><%- value %></b>');
 * compiled({ 'value': '<script>' });
 * // => '<b>&lt;script&gt;</b>'
 *
 * // Use the "evaluate" delimiter to execute JavaScript and generate HTML.
 * var compiled = _.template('<% _.forEach(users, function(user) { %><li><%- user %></li><% }); %>');
 * compiled({ 'users': ['fred', 'barney'] });
 * // => '<li>fred</li><li>barney</li>'
 *
 * // Use the internal `print` function in "evaluate" delimiters.
 * var compiled = _.template('<% print("hello " + user); %>!');
 * compiled({ 'user': 'barney' });
 * // => 'hello barney!'
 *
 * // Use the ES template literal delimiter as an "interpolate" delimiter.
 * // Disable support by replacing the "interpolate" delimiter.
 * var compiled = _.template('hello ${ user }!');
 * compiled({ 'user': 'pebbles' });
 * // => 'hello pebbles!'
 *
 * // Use backslashes to treat delimiters as plain text.
 * var compiled = _.template('<%= "\\<%- value %\\>" %>');
 * compiled({ 'value': 'ignored' });
 * // => '<%- value %>'
 *
 * // Use the `imports` option to import `jQuery` as `jq`.
 * var text = '<% jq.each(users, function(user) { %><li><%- user %></li><% }); %>';
 * var compiled = _.template(text, { 'imports': { 'jq': jQuery } });
 * compiled({ 'users': ['fred', 'barney'] });
 * // => '<li>fred</li><li>barney</li>'
 *
 * // Use the `sourceURL` option to specify a custom sourceURL for the template.
 * var compiled = _.template('hello <%= user %>!', { 'sourceURL': '/basic/greeting.jst' });
 * compiled(data);
 * // => Find the source of "greeting.jst" under the Sources tab or Resources panel of the web inspector.
 *
 * // Use the `variable` option to ensure a with-statement isn't used in the compiled template.
 * var compiled = _.template('hi <%= data.user %>!', { 'variable': 'data' });
 * compiled.source;
 * // => function(data) {
 * //   var __t, __p = '';
 * //   __p += 'hi ' + ((__t = ( data.user )) == null ? '' : __t) + '!';
 * //   return __p;
 * // }
 *
 * // Use custom template delimiters.
 * _.templateSettings.interpolate = /{{([\s\S]+?)}}/g;
 * var compiled = _.template('hello {{ user }}!');
 * compiled({ 'user': 'mustache' });
 * // => 'hello mustache!'
 *
 * // Use the `source` property to inline compiled templates for meaningful
 * // line numbers in error messages and stack traces.
 * fs.writeFileSync(path.join(process.cwd(), 'jst.js'), '\
 *   var JST = {\
 *     "main": ' + _.template(mainText).source + '\
 *   };\
 * ');
 */
function template(string, options, guard) {
  // Based on John Resig's `tmpl` implementation
  // (http://ejohn.org/blog/javascript-micro-templating/)
  // and Laura Doktorova's doT.js (https://github.com/olado/doT).
  var settings = templateSettings.imports._.templateSettings || templateSettings;

  if (guard && isIterateeCall(string, options, guard)) {
    options = undefined;
  }
  string = toString(string);
  options = assignInWith({}, options, settings, customDefaultsAssignIn);

  var imports = assignInWith({}, options.imports, settings.imports, customDefaultsAssignIn),
      importsKeys = keys(imports),
      importsValues = baseValues(imports, importsKeys);

  var isEscaping,
      isEvaluating,
      index = 0,
      interpolate = options.interpolate || reNoMatch,
      source = "__p += '";

  // Compile the regexp to match each delimiter.
  var reDelimiters = RegExp(
    (options.escape || reNoMatch).source + '|' +
    interpolate.source + '|' +
    (interpolate === reInterpolate ? reEsTemplate : reNoMatch).source + '|' +
    (options.evaluate || reNoMatch).source + '|$'
  , 'g');

  // Use a sourceURL for easier debugging.
  // The sourceURL gets injected into the source that's eval-ed, so be careful
  // with lookup (in case of e.g. prototype pollution), and strip newlines if any.
  // A newline wouldn't be a valid sourceURL anyway, and it'd enable code injection.
  var sourceURL = hasOwnProperty.call(options, 'sourceURL')
    ? ('//# sourceURL=' +
       (options.sourceURL + '').replace(/[\r\n]/g, ' ') +
       '\n')
    : '';

  string.replace(reDelimiters, function(match, escapeValue, interpolateValue, esTemplateValue, evaluateValue, offset) {
    interpolateValue || (interpolateValue = esTemplateValue);

    // Escape characters that can't be included in string literals.
    source += string.slice(index, offset).replace(reUnescapedString, escapeStringChar);

    // Replace delimiters with snippets.
    if (escapeValue) {
      isEscaping = true;
      source += "' +\n__e(" + escapeValue + ") +\n'";
    }
    if (evaluateValue) {
      isEvaluating = true;
      source += "';\n" + evaluateValue + ";\n__p += '";
    }
    if (interpolateValue) {
      source += "' +\n((__t = (" + interpolateValue + ")) == null ? '' : __t) +\n'";
    }
    index = offset + match.length;

    // The JS engine embedded in Adobe products needs `match` returned in
    // order to produce the correct `offset` value.
    return match;
  });

  source += "';\n";

  // If `variable` is not specified wrap a with-statement around the generated
  // code to add the data object to the top of the scope chain.
  // Like with sourceURL, we take care to not check the option's prototype,
  // as this configuration is a code injection vector.
  var variable = hasOwnProperty.call(options, 'variable') && options.variable;
  if (!variable) {
    source = 'with (obj) {\n' + source + '\n}\n';
  }
  // Cleanup code by stripping empty strings.
  source = (isEvaluating ? source.replace(reEmptyStringLeading, '') : source)
    .replace(reEmptyStringMiddle, '$1')
    .replace(reEmptyStringTrailing, '$1;');

  // Frame code as the function body.
  source = 'function(' + (variable || 'obj') + ') {\n' +
    (variable
      ? ''
      : 'obj || (obj = {});\n'
    ) +
    "var __t, __p = ''" +
    (isEscaping
       ? ', __e = _.escape'
       : ''
    ) +
    (isEvaluating
      ? ', __j = Array.prototype.join;\n' +
        "function print() { __p += __j.call(arguments, '') }\n"
      : ';\n'
    ) +
    source +
    'return __p\n}';

  var result = attempt(function() {
    return Function(importsKeys, sourceURL + 'return ' + source)
      .apply(undefined, importsValues);
  });

  // Provide the compiled function's source by its `toString` method or
  // the `source` property as a convenience for inlining compiled templates.
  result.source = source;
  if (isError(result)) {
    throw result;
  }
  return result;
}

/**
 * Attempts to invoke `func`, returning either the result or the caught error
 * object. Any additional arguments are provided to `func` when it's invoked.
 *
 * @static
 * @memberOf _
 * @since 3.0.0
 * @category Util
 * @param {Function} func The function to attempt.
 * @param {...*} [args] The arguments to invoke `func` with.
 * @returns {*} Returns the `func` result or error object.
 * @example
 *
 * // Avoid throwing errors for invalid selectors.
 * var elements = _.attempt(function(selector) {
 *   return document.querySelectorAll(selector);
 * }, '>_>');
 *
 * if (_.isError(elements)) {
 *   elements = [];
 * }
 */
var attempt = baseRest(function(func, args) {
  try {
    return apply(func, undefined, args);
  } catch (e) {
    return isError(e) ? e : new Error(e);
  }
});

/**
 * Creates a function that returns `value`.
 *
 * @static
 * @memberOf _
 * @since 2.4.0
 * @category Util
 * @param {*} value The value to return from the new function.
 * @returns {Function} Returns the new constant function.
 * @example
 *
 * var objects = _.times(2, _.constant({ 'a': 1 }));
 *
 * console.log(objects);
 * // => [{ 'a': 1 }, { 'a': 1 }]
 *
 * console.log(objects[0] === objects[1]);
 * // => true
 */
function constant(value) {
  return function() {
    return value;
  };
}

/**
 * This method returns the first argument it receives.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category Util
 * @param {*} value Any value.
 * @returns {*} Returns `value`.
 * @example
 *
 * var object = { 'a': 1 };
 *
 * console.log(_.identity(object) === object);
 * // => true
 */
function identity(value) {
  return value;
}

/**
 * This method returns `false`.
 *
 * @static
 * @memberOf _
 * @since 4.13.0
 * @category Util
 * @returns {boolean} Returns `false`.
 * @example
 *
 * _.times(2, _.stubFalse);
 * // => [false, false]
 */
function stubFalse() {
  return false;
}

module.exports = template;

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js"), __webpack_require__(/*! ./../webpack/buildin/module.js */ "./node_modules/webpack/buildin/module.js")(module)))

/***/ }),

/***/ "./node_modules/lodash.templatesettings/index.js":
/*!*******************************************************!*\
  !*** ./node_modules/lodash.templatesettings/index.js ***!
  \*******************************************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

/* WEBPACK VAR INJECTION */(function(global) {/**
 * Lodash (Custom Build) <https://lodash.com/>
 * Build: `lodash modularize exports="npm" -o ./`
 * Copyright OpenJS Foundation and other contributors <https://openjsf.org/>
 * Released under MIT license <https://lodash.com/license>
 * Based on Underscore.js 1.8.3 <http://underscorejs.org/LICENSE>
 * Copyright Jeremy Ashkenas, DocumentCloud and Investigative Reporters & Editors
 */
var reInterpolate = __webpack_require__(/*! lodash._reinterpolate */ "./node_modules/lodash._reinterpolate/index.js");

/** Used as references for various `Number` constants. */
var INFINITY = 1 / 0;

/** `Object#toString` result references. */
var nullTag = '[object Null]',
    symbolTag = '[object Symbol]',
    undefinedTag = '[object Undefined]';

/** Used to match HTML entities and HTML characters. */
var reUnescapedHtml = /[&<>"']/g,
    reHasUnescapedHtml = RegExp(reUnescapedHtml.source);

/** Used to match template delimiters. */
var reEscape = /<%-([\s\S]+?)%>/g,
    reEvaluate = /<%([\s\S]+?)%>/g;

/** Used to map characters to HTML entities. */
var htmlEscapes = {
  '&': '&amp;',
  '<': '&lt;',
  '>': '&gt;',
  '"': '&quot;',
  "'": '&#39;'
};

/** Detect free variable `global` from Node.js. */
var freeGlobal = typeof global == 'object' && global && global.Object === Object && global;

/** Detect free variable `self`. */
var freeSelf = typeof self == 'object' && self && self.Object === Object && self;

/** Used as a reference to the global object. */
var root = freeGlobal || freeSelf || Function('return this')();

/**
 * A specialized version of `_.map` for arrays without support for iteratee
 * shorthands.
 *
 * @private
 * @param {Array} [array] The array to iterate over.
 * @param {Function} iteratee The function invoked per iteration.
 * @returns {Array} Returns the new mapped array.
 */
function arrayMap(array, iteratee) {
  var index = -1,
      length = array == null ? 0 : array.length,
      result = Array(length);

  while (++index < length) {
    result[index] = iteratee(array[index], index, array);
  }
  return result;
}

/**
 * The base implementation of `_.propertyOf` without support for deep paths.
 *
 * @private
 * @param {Object} object The object to query.
 * @returns {Function} Returns the new accessor function.
 */
function basePropertyOf(object) {
  return function(key) {
    return object == null ? undefined : object[key];
  };
}

/**
 * Used by `_.escape` to convert characters to HTML entities.
 *
 * @private
 * @param {string} chr The matched character to escape.
 * @returns {string} Returns the escaped character.
 */
var escapeHtmlChar = basePropertyOf(htmlEscapes);

/** Used for built-in method references. */
var objectProto = Object.prototype;

/** Used to check objects for own properties. */
var hasOwnProperty = objectProto.hasOwnProperty;

/**
 * Used to resolve the
 * [`toStringTag`](http://ecma-international.org/ecma-262/7.0/#sec-object.prototype.tostring)
 * of values.
 */
var nativeObjectToString = objectProto.toString;

/** Built-in value references. */
var Symbol = root.Symbol,
    symToStringTag = Symbol ? Symbol.toStringTag : undefined;

/** Used to convert symbols to primitives and strings. */
var symbolProto = Symbol ? Symbol.prototype : undefined,
    symbolToString = symbolProto ? symbolProto.toString : undefined;

/**
 * By default, the template delimiters used by lodash are like those in
 * embedded Ruby (ERB) as well as ES2015 template strings. Change the
 * following template settings to use alternative delimiters.
 *
 * @static
 * @memberOf _
 * @type {Object}
 */
var templateSettings = {

  /**
   * Used to detect `data` property values to be HTML-escaped.
   *
   * @memberOf _.templateSettings
   * @type {RegExp}
   */
  'escape': reEscape,

  /**
   * Used to detect code to be evaluated.
   *
   * @memberOf _.templateSettings
   * @type {RegExp}
   */
  'evaluate': reEvaluate,

  /**
   * Used to detect `data` property values to inject.
   *
   * @memberOf _.templateSettings
   * @type {RegExp}
   */
  'interpolate': reInterpolate,

  /**
   * Used to reference the data object in the template text.
   *
   * @memberOf _.templateSettings
   * @type {string}
   */
  'variable': '',

  /**
   * Used to import variables into the compiled template.
   *
   * @memberOf _.templateSettings
   * @type {Object}
   */
  'imports': {

    /**
     * A reference to the `lodash` function.
     *
     * @memberOf _.templateSettings.imports
     * @type {Function}
     */
    '_': { 'escape': escape }
  }
};

/**
 * The base implementation of `getTag` without fallbacks for buggy environments.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the `toStringTag`.
 */
function baseGetTag(value) {
  if (value == null) {
    return value === undefined ? undefinedTag : nullTag;
  }
  return (symToStringTag && symToStringTag in Object(value))
    ? getRawTag(value)
    : objectToString(value);
}

/**
 * The base implementation of `_.toString` which doesn't convert nullish
 * values to empty strings.
 *
 * @private
 * @param {*} value The value to process.
 * @returns {string} Returns the string.
 */
function baseToString(value) {
  // Exit early for strings to avoid a performance hit in some environments.
  if (typeof value == 'string') {
    return value;
  }
  if (isArray(value)) {
    // Recursively convert values (susceptible to call stack limits).
    return arrayMap(value, baseToString) + '';
  }
  if (isSymbol(value)) {
    return symbolToString ? symbolToString.call(value) : '';
  }
  var result = (value + '');
  return (result == '0' && (1 / value) == -INFINITY) ? '-0' : result;
}

/**
 * A specialized version of `baseGetTag` which ignores `Symbol.toStringTag` values.
 *
 * @private
 * @param {*} value The value to query.
 * @returns {string} Returns the raw `toStringTag`.
 */
function getRawTag(value) {
  var isOwn = hasOwnProperty.call(value, symToStringTag),
      tag = value[symToStringTag];

  try {
    value[symToStringTag] = undefined;
    var unmasked = true;
  } catch (e) {}

  var result = nativeObjectToString.call(value);
  if (unmasked) {
    if (isOwn) {
      value[symToStringTag] = tag;
    } else {
      delete value[symToStringTag];
    }
  }
  return result;
}

/**
 * Converts `value` to a string using `Object.prototype.toString`.
 *
 * @private
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 */
function objectToString(value) {
  return nativeObjectToString.call(value);
}

/**
 * Checks if `value` is classified as an `Array` object.
 *
 * @static
 * @memberOf _
 * @since 0.1.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is an array, else `false`.
 * @example
 *
 * _.isArray([1, 2, 3]);
 * // => true
 *
 * _.isArray(document.body.children);
 * // => false
 *
 * _.isArray('abc');
 * // => false
 *
 * _.isArray(_.noop);
 * // => false
 */
var isArray = Array.isArray;

/**
 * Checks if `value` is object-like. A value is object-like if it's not `null`
 * and has a `typeof` result of "object".
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is object-like, else `false`.
 * @example
 *
 * _.isObjectLike({});
 * // => true
 *
 * _.isObjectLike([1, 2, 3]);
 * // => true
 *
 * _.isObjectLike(_.noop);
 * // => false
 *
 * _.isObjectLike(null);
 * // => false
 */
function isObjectLike(value) {
  return value != null && typeof value == 'object';
}

/**
 * Checks if `value` is classified as a `Symbol` primitive or object.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to check.
 * @returns {boolean} Returns `true` if `value` is a symbol, else `false`.
 * @example
 *
 * _.isSymbol(Symbol.iterator);
 * // => true
 *
 * _.isSymbol('abc');
 * // => false
 */
function isSymbol(value) {
  return typeof value == 'symbol' ||
    (isObjectLike(value) && baseGetTag(value) == symbolTag);
}

/**
 * Converts `value` to a string. An empty string is returned for `null`
 * and `undefined` values. The sign of `-0` is preserved.
 *
 * @static
 * @memberOf _
 * @since 4.0.0
 * @category Lang
 * @param {*} value The value to convert.
 * @returns {string} Returns the converted string.
 * @example
 *
 * _.toString(null);
 * // => ''
 *
 * _.toString(-0);
 * // => '-0'
 *
 * _.toString([1, 2, 3]);
 * // => '1,2,3'
 */
function toString(value) {
  return value == null ? '' : baseToString(value);
}

/**
 * Converts the characters "&", "<", ">", '"', and "'" in `string` to their
 * corresponding HTML entities.
 *
 * **Note:** No other characters are escaped. To escape additional
 * characters use a third-party library like [_he_](https://mths.be/he).
 *
 * Though the ">" character is escaped for symmetry, characters like
 * ">" and "/" don't need escaping in HTML and have no special meaning
 * unless they're part of a tag or unquoted attribute value. See
 * [Mathias Bynens's article](https://mathiasbynens.be/notes/ambiguous-ampersands)
 * (under "semi-related fun fact") for more details.
 *
 * When working with HTML you should always
 * [quote attribute values](http://wonko.com/post/html-escaping) to reduce
 * XSS vectors.
 *
 * @static
 * @since 0.1.0
 * @memberOf _
 * @category String
 * @param {string} [string=''] The string to escape.
 * @returns {string} Returns the escaped string.
 * @example
 *
 * _.escape('fred, barney, & pebbles');
 * // => 'fred, barney, &amp; pebbles'
 */
function escape(string) {
  string = toString(string);
  return (string && reHasUnescapedHtml.test(string))
    ? string.replace(reUnescapedHtml, escapeHtmlChar)
    : string;
}

module.exports = templateSettings;

/* WEBPACK VAR INJECTION */}.call(this, __webpack_require__(/*! ./../webpack/buildin/global.js */ "./node_modules/webpack/buildin/global.js")))

/***/ }),

/***/ "./node_modules/webpack/buildin/global.js":
/*!***********************************!*\
  !*** (webpack)/buildin/global.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

var g;

// This works in non-strict mode
g = (function() {
	return this;
})();

try {
	// This works if eval is allowed (see CSP)
	g = g || new Function("return this")();
} catch (e) {
	// This works if the window reference is available
	if (typeof window === "object") g = window;
}

// g can still be undefined, but nothing to do about it...
// We return undefined, instead of nothing here, so it's
// easier to handle this case. if(!global) { ...}

module.exports = g;


/***/ }),

/***/ "./node_modules/webpack/buildin/module.js":
/*!***********************************!*\
  !*** (webpack)/buildin/module.js ***!
  \***********************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = function(module) {
	if (!module.webpackPolyfill) {
		module.deprecate = function() {};
		module.paths = [];
		// module.parent = undefined by default
		if (!module.children) module.children = [];
		Object.defineProperty(module, "loaded", {
			enumerable: true,
			get: function() {
				return module.l;
			}
		});
		Object.defineProperty(module, "id", {
			enumerable: true,
			get: function() {
				return module.i;
			}
		});
		module.webpackPolyfill = 1;
	}
	return module;
};


/***/ }),

/***/ "./src/blocks.js":
/*!***********************!*\
  !*** ./src/blocks.js ***!
  \***********************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

/* harmony default export */ __webpack_exports__["default"] = (function (editor) {
  var opts = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var bm = editor.BlockManager;
  var c = opts.comps;
  var blocks = c.blocks;
  var cats = c.blockCategories;

  if (opts.resetBlocks) {
    bm.getAll().reset();
  }

  var allBlocks = {
    category: opts.categoryLabel
  }; // CORE BLOCKS

  bm.add('comp_col1').set(_objectSpread({
    label: c.comp_col1.label,
    attributes: {
      "class": 'gjs-fonts gjs-f-b1'
    },
    content: {
      type: 'comp_row',
      components: [{
        type: 'comp_col',
        classes: c.comp_col1.classes.concat(c.comp_col.classes)
      }]
    }
  }, allBlocks));
  bm.add('comp_col2').set(_objectSpread({
    label: c.comp_col2.label,
    attributes: {
      "class": 'gjs-fonts gjs-f-b2'
    },
    content: {
      type: 'comp_row',
      components: [{
        type: 'comp_col',
        classes: c.comp_col2.classes.concat(c.comp_col.classes)
      }, {
        type: 'comp_col',
        classes: c.comp_col2.classes.concat(c.comp_col.classes)
      }]
    }
  }, allBlocks));
  bm.add('comp_col3').set(_objectSpread({
    label: c.comp_col3.label,
    attributes: {
      "class": 'gjs-fonts gjs-f-b3'
    },
    content: {
      type: 'comp_row',
      components: [{
        type: 'comp_col',
        classes: c.comp_col3.classes.concat(c.comp_col.classes)
      }, {
        type: 'comp_col',
        classes: c.comp_col3.classes.concat(c.comp_col.classes)
      }, {
        type: 'comp_col',
        classes: c.comp_col3.classes.concat(c.comp_col.classes)
      }]
    }
  }, allBlocks));
  bm.add('comp_submit', _objectSpread({
    label: "\n      <div style=\"color: #fff;\n    background-color: #007bff;\n    border-color: #007bff;\n    display: inline-block;\n    text-align: center;\n    white-space: nowrap;\n    vertical-align: middle;\n    border: 1px solid transparent;\n    padding-left: 5px;\n    padding-right: 5px;\n    font-size: 1rem;\n    line-height: 1.5;\n    border-radius: .25rem;\n    margin-top: 12px;\n    margin-bottom: 16px;\n    width: 50px;\">&nbsp;</div>\n      <div class=\"gjs-block-label\">".concat(c.comp_submit.label, "</div>\n    "),
    content: {
      type: 'comp_submit'
    }
  }, allBlocks));
  bm.add('comp_input', _objectSpread({
    label: "\n    <svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n      <path class=\"gjs-block-svg-path\" d=\"M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z\"></path>\n      <polygon class=\"gjs-block-svg-path\" points=\"4 10 5 10 5 14 4 14\"></polygon>\n    </svg>\n    <div class=\"gjs-block-label\">".concat(c.comp_input.label, "</div>"),
    content: {
      type: 'comp_input'
    }
  }, allBlocks));
  bm.add('comp_textarea', _objectSpread({
    label: "\n    <svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n      <path class=\"gjs-block-svg-path\" d=\"M22,7.5 C22,6.6 21.5,6 20.75,6 L3.25,6 C2.5,6 2,6.6 2,7.5 L2,16.5 C2,17.4 2.5,18 3.25,18 L20.75,18 C21.5,18 22,17.4 22,16.5 L22,7.5 Z M21,17 L3,17 L3,7 L21,7 L21,17 Z\"></path>\n      <polygon class=\"gjs-block-svg-path\" points=\"4 8 5 8 5 12 4 12\"></polygon>\n      <polygon class=\"gjs-block-svg-path\" points=\"19 7 20 7 20 17 19 17\"></polygon>\n      <polygon class=\"gjs-block-svg-path\" points=\"20 8 21 8 21 9 20 9\"></polygon>\n      <polygon class=\"gjs-block-svg-path\" points=\"20 15 21 15 21 16 20 16\"></polygon>\n    </svg>\n    <div class=\"gjs-block-label\">".concat(c.comp_textarea.label, "</div>"),
    content: {
      type: 'comp_textarea'
    }
  }, allBlocks));
  bm.add('comp_select', _objectSpread({
    label: "\n    <svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n      <path class=\"gjs-block-svg-path\" d=\"M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z\" fill-rule=\"nonzero\"></path>\n      <polygon class=\"gjs-block-svg-path\" transform=\"translate(18.500000, 12.000000) scale(1, -1) translate(-18.500000, -12.000000) \" points=\"18.5 11 20 13 17 13\"></polygon>\n      <rect class=\"gjs-block-svg-path\" x=\"4\" y=\"11.5\" width=\"11\" height=\"1\"></rect>\n    </svg>\n    <div class=\"gjs-block-label\">".concat(c.comp_select.label, "</div>"),
    content: {
      type: 'comp_select'
    }
  }, allBlocks));
  bm.add('comp_file', _objectSpread({
    label: c.comp_file.label,
    attributes: {
      "class": 'fa fa-file'
    },
    content: {
      type: 'comp_file'
    }
  }, allBlocks));
  bm.add('comp_checkbox', _objectSpread({
    label: c.comp_checkbox.label,
    attributes: {
      "class": 'fa fa-check-square'
    },
    content: {
      type: 'comp_checkbox'
    }
  }, allBlocks));
  bm.add('comp_hidden', _objectSpread({
    label: "<svg class=\"gjs-block-svg\" viewBox=\"0 0 24 24\" xmlns=\"http://www.w3.org/2000/svg\">\n      <path class=\"gjs-block-svg-path\" d=\"M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z\"></path>\n      <polygon class=\"gjs-block-svg-path\" points=\"4 10 5 10 5 14 4 14\"></polygon>\n    </svg>\n    <div class=\"gjs-block-label\">".concat(c.comp_hidden.label, "</div>"),
    content: {
      type: 'comp_hidden'
    }
  }, allBlocks));
  bm.add('comp_text', _objectSpread({
    label: c.comp_text.label,
    attributes: {
      "class": 'gjs-fonts gjs-f-text'
    },
    content: {
      type: 'text',
      content: 'Insert your text here',
      style: {
        padding: '10px'
      },
      activeOnRender: 1
    }
  }, allBlocks));
  bm.add('comp_image', _objectSpread({
    label: c.comp_image.label,
    attributes: {
      "class": 'gjs-fonts gjs-f-image'
    },
    content: {
      style: {
        color: 'black'
      },
      type: 'image',
      activeOnRender: 1
    }
  }, allBlocks));
  bm.add('comp_map', _objectSpread({
    label: c.comp_map.label,
    attributes: {
      "class": 'fa fa-map-o'
    },
    content: {
      type: 'map',
      style: {
        height: '350px'
      }
    }
  }, allBlocks));
  bm.add('comp_recaptcha', _objectSpread({
    label: "\n      <image src=\"https://www.gstatic.com/recaptcha/api2/logo_48.png\">\n      <br /><br />\n    <div class=\"gjs-block-label\">".concat(c.comp_recaptcha.label, "</div>"),
    content: {
      type: 'comp_recaptcha'
    }
  }, allBlocks));
  bm.add('comp_stripe', _objectSpread({
    label: c.comp_stripe.label,
    attributes: {
      "class": 'fa fa-cc-stripe'
    },
    content: {
      type: 'comp_stripe'
    }
  }, allBlocks));
});

/***/ }),

/***/ "./src/consts.js":
/*!***********************!*\
  !*** ./src/consts.js ***!
  \***********************/
/*! exports provided: cmdDeviceDesktop, cmdDeviceTablet, cmdDeviceMobile, cmdClear */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cmdDeviceDesktop", function() { return cmdDeviceDesktop; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cmdDeviceTablet", function() { return cmdDeviceTablet; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cmdDeviceMobile", function() { return cmdDeviceMobile; });
/* harmony export (binding) */ __webpack_require__.d(__webpack_exports__, "cmdClear", function() { return cmdClear; });
var cmdDeviceDesktop = 'set-device-desktop',
    cmdDeviceTablet = 'set-device-tablet',
    cmdDeviceMobile = 'set-device-mobile',
    cmdClear = 'canvas-clear';

/***/ }),

/***/ "./src/index.js":
/*!**********************!*\
  !*** ./src/index.js ***!
  \**********************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
/* harmony import */ var lodash_template__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! lodash.template */ "./node_modules/lodash.template/index.js");
/* harmony import */ var lodash_template__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(lodash_template__WEBPACK_IMPORTED_MODULE_1__);
/* harmony import */ var grapesjs__WEBPACK_IMPORTED_MODULE_2__ = __webpack_require__(/*! grapesjs */ "grapesjs");
/* harmony import */ var grapesjs__WEBPACK_IMPORTED_MODULE_2___default = /*#__PURE__*/__webpack_require__.n(grapesjs__WEBPACK_IMPORTED_MODULE_2__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }




/* harmony default export */ __webpack_exports__["default"] = (grapesjs__WEBPACK_IMPORTED_MODULE_2___default.a.plugins.add('grapesjs-components-farmer', function (editor) {
  var opts = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var commands = editor.Commands;
  var pn = editor.Panels;
  var opts_comps = opts.comps || {};
  var default_comps = {
    'comp_input': {
      label: 'Input',
      classes: 'form-group',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%></label>\n        <input <%= it.required_attr ? 'required ' : '' %>type=\"<%=it.type_attr%>\" class=\"form-control\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n      "
    },
    'comp_select': {
      label: 'Select',
      classes: 'form-group',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%></label>\n        <select <%= it.multiple_attr ? 'multiple ' : '' %><%= it.required_attr ? 'required ' : '' %>class=\"form-control\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\">\n          <option selected>-- Please select an option -- </option>\n          <% (it.option_attr + \"\").trim().split(\"\\n\").forEach(function(option){ %>\n          <% var msgProps = option.split('::');\n          %> <option value=\"<%= msgProps[0]%>\"><%= msgProps[1] || msgProps[0] %></option>\n          <% }); %>\n        </select>\n      "
    },
    'comp_file': {
      label: 'File',
      classes: 'custom-file',
      useTag: 'div',
      template: "\n        <input <%= it.required_attr ? 'required ' : '' %>type=\"file\" class=\"custom-file-input\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\" <%= it.accept_attr ? 'accept=\"' + it.accept_attr + '\"' : '' %> <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n        <label for=\"<%=it.name_attr%>\" class=\"custom-file-label\"><%=it.label_attr%></label>\n      "
    },
    'comp_textarea': {
      label: 'Textarea',
      classes: 'form-group',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\"><%=it.label_attr%></label>\n        <textarea <%= it.required_attr ? 'required ' : '' %><%= it.rows_attr ? 'rows=\"' + it.rows_attr + '\" ' : '' %><%= it.rows_attr ? 'cols=\"' + it.rows_attr + '\" ' : '' %>class=\"form-control\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" placeholder=\"<%=it.placeholder_attr || ''%>\"><%= it.value_attr || '' %></textarea>\n      "
    },
    'comp_checkbox': {
      label: 'Checkbox',
      classes: 'form-check',
      useTag: 'div',
      template: "\n        <label for=\"<%=it.name_attr%>\" class=\"form-check-label\">\n          <input <%= it.required_attr ? 'required ' : '' %>type=\"checkbox\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" class=\"form-check-input\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n          <%=it.label_attr%>\n        </label>\n      "
    },
    'comp_hidden': {
      label: 'Hidden Input',
      useTag: 'div',
      template: "\n        <input <%= it.required_attr ? 'required ' : '' %>type=\"hidden\" id=\"<%=it.name_attr%>\" name=\"<%=it.name_attr%>\" <%= it.value_attr ? 'value=\"' + it.value_attr + '\"' : '' %>/>\n      "
    },
    'comp_submit': {
      label: 'Submit Button',
      classes: 'btn btn-primary btn-block',
      useTag: 'button',
      template: '<%= it.label_attr %>'
    },
    'comp_row': {
      label: 'Row',
      useTag: 'div',
      classes: 'row'
    },
    'comp_col': {
      label: 'Column',
      useTag: 'div',
      classes: 'col'
    },
    'comp_col1': {
      label: '1 Column',
      useTag: 'div'
    },
    'comp_col2': {
      label: '2 Columns',
      useTag: 'div'
    },
    'comp_col3': {
      label: '3 Columns',
      useTag: 'div'
    },
    'comp_text': {
      label: 'Text'
    },
    'comp_image': {
      label: 'Image'
    },
    'comp_map': {
      label: 'Map'
    },
    'comp_recaptcha': {
      label: 'reCaptcha',
      classes: 'form-group',
      template: "\n        <script src=\"https://www.google.com/recaptcha/api.js?render=<%= it.sitekey_attr %>\"></script>\n        <input type=\"hidden\" name=\"g-recaptcha-response\" id=\"g-recaptcha-response\" data-sitekey=\"<%= it.sitekey_attr || '' %>\" data-action=\"<%= it.action_attr || '' %>\" />\n      "
    },
    'comp_stripe': {
      label: 'Payment',
      classes: 'form-group',
      template: "\n        <script src=\"https://js.stripe.com/v3/\"></script>\n        <div id=\"stripeElement\" data-key=\"<%= it.publickey_attr || '' %>\" data-hide-postal=\"<%= it.hidepostal_attr || 'false' %>\">&nbsp;</div>\n      "
    }
  }; // provide defaults

  for (var k in default_comps) {
    var x = default_comps[k];
    var y = opts_comps[k];

    if (y) {
      for (var j in y) {
        y[j] = y[j] || x[j];
      }

      opts_comps[k] = y;
    } else {
      opts_comps[k] = x;
    }

    opts_comps[k]['classes'] = (opts_comps[k]['classes'] || '').split(' ');
  }

  var options = _objectSpread({
    formNextId: 1,
    categoryLabel: 'Basic',
    formFieldsPrefix: 'field',
    showStylesOnChange: 1,
    addResource: function addResource(url) {
      var type = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : 'script';
      var doc = editor.Canvas.getDocument();
      var head = doc.head || doc.getElementsByTagName('head')[0];
      var res = document.createElement(type);

      if (type === 'script') {
        res.src = url;
      } else if (type === 'link') {
        res.rel = 'stylesheet';
        res.href = url;
      }

      head.appendChild(res);
    }
  }, opts);

  options.comps = options.comps || opts_comps;

  var compileTemplates = function compileTemplates() {
    Object.keys(options.comps).forEach(function (k) {
      var t = options.comps[k];

      if (t && typeof t.template === 'string') {
        t.template = lodash_template__WEBPACK_IMPORTED_MODULE_1___default()(t.template);
      }
    });
  };
  /** Prevent Input Default Actions **/


  var preventInputDefaults = function preventInputDefaults() {
    var el = editor.Canvas.getBody();
    var win = el.ownerWindow;
    var body = jquery__WEBPACK_IMPORTED_MODULE_0___default()(el);
    body.click(function (e) {
      var event = e || win.event;
      var target = event.target || event.srcElement;
      var tagName = (target.tagName + '').toLowerCase();

      if (tagName === 'input') {
        event.preventDefault();
        return false;
      }
    });
  };

  commands.add('get-usable-html', {
    run: function run() {
      // get the builder xml
      var html = editor.getHtml(); // convert into html

      for (var _k in options.comps) {
        // default tag as div unless useTag value is provided
        var tag = options.comps[_k].useTag || 'div';
        html = html.replace(new RegExp('<' + _k, 'g'), '<' + tag);
        html = html.replace(new RegExp('</' + _k + '>', 'g'), '</' + tag + '>');
        html = html.replace(new RegExp(" data-gjs-type=\"".concat(_k, "\" "), 'g'), ' ');
        html = html.replace(new RegExp(' data\-highlightable\=\"1\" ', 'g'), ' ');
      }

      return html;
    }
  }); // Add plugins

  __webpack_require__(/*! ./traits */ "./src/traits.js")["default"](editor, options);

  __webpack_require__(/*! ./blocks */ "./src/blocks.js")["default"](editor, options);

  if (opts.panel) {
    __webpack_require__(/*! ./panels */ "./src/panels.js")["default"](editor, options);
  }

  editor.on('load', function () {
    compileTemplates();
    setTimeout(function () {
      var doc = editor.Canvas.getDocument();
      var head = doc.head || doc.getElementsByTagName('head')[0];
      var css = "\nbody {\n  padding: 10px;\n}\n\ncomp_recaptcha,\ncomp_row,\ncomp_col,\ncomp_hidden {\n  min-height: 2rem !important;\n}\n\ncomp_row {\n  display: block;\n  width: 100%;\n  margin-left: 15px;\n  margin-right: 15px;\n}\n\ncomp_col {\n  min-width: 100px;\n}\n\ncomp_hidden, comp_recaptcha, comp_stripe {\n  display: block;\n  width: 100%;\n  min-width: 100%;\n}\n";
      var style = doc.createElement('style');
      style.type = 'text/css';

      if (style.styleSheet) {
        // This is required for IE8 and below.
        style.styleSheet.cssText = css;
      } else {
        style.appendChild(doc.createTextNode(css));
      }

      head.appendChild(style);
      preventInputDefaults();
    }, 10);
  });
}));

/***/ }),

/***/ "./src/panels.js":
/*!***********************!*\
  !*** ./src/panels.js ***!
  \***********************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var _consts__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! ./consts */ "./src/consts.js");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_1___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_1__);


/* harmony default export */ __webpack_exports__["default"] = (function (editor, opts) {
  var cmds = editor.Commands;
  var pn = editor.Panels;
  var eConfig = editor.getConfig();
  var crc = 'create-comp';
  var mvc = 'move-comp';
  var swv = 'sw-visibility';
  var expt = 'export-template';
  var osm = 'open-sm';
  var otm = 'open-tm';
  var ola = 'open-layers';
  var obl = 'open-blocks';
  var ful = 'fullscreen';
  var prv = 'preview';
  eConfig.showDevices = 0;
  var txtConfirm = opts.textCleanCanvas || 'Are you sure to clean the canvas?';
  cmds.add(_consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceDesktop"], function (e) {
    return e.setDevice('Desktop');
  });
  cmds.add(_consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceTablet"], function (e) {
    return e.setDevice('Tablet');
  });
  cmds.add(_consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceMobile"], function (e) {
    return e.setDevice('Mobile portrait');
  });
  cmds.add(_consts__WEBPACK_IMPORTED_MODULE_0__["cmdClear"], function (e) {
    return confirm(txtConfirm) && e.runCommand('core:canvas-clear');
  });
  pn.getPanels().reset([{
    id: 'commands',
    buttons: [{}]
  }, {
    id: 'options',
    buttons: [{
      id: swv,
      command: swv,
      context: swv,
      active: 1,
      className: 'fa fa-square-o',
      attributes: {
        title: 'Show Borders'
      }
    }, {
      id: prv,
      context: prv,
      command: function command(e) {
        return e.runCommand(prv);
      },
      className: 'fa fa-eye',
      attributes: {
        title: 'Preview'
      }
    }, {
      id: ful,
      command: ful,
      context: ful,
      className: 'fa fa-arrows-alt',
      attributes: {
        title: 'Fullscreen'
      }
    }, {
      id: expt,
      className: 'fa fa-code',
      command: function command(e) {
        return e.runCommand(expt);
      },
      attributes: {
        title: 'Show code'
      }
    }, {
      id: 'undo',
      className: 'fa fa-undo',
      command: function command(e) {
        return e.runCommand('core:undo');
      },
      attributes: {
        title: 'Undo'
      }
    }, {
      id: 'redo',
      className: 'fa fa-repeat',
      command: function command(e) {
        return e.runCommand('core:redo');
      },
      attributes: {
        title: 'Redo'
      }
    }, {
      id: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdClear"],
      className: 'fa fa-trash',
      command: function command(e) {
        return e.runCommand(_consts__WEBPACK_IMPORTED_MODULE_0__["cmdClear"]);
      },
      attributes: {
        title: 'Clear canvas'
      }
    }]
  }, {
    id: 'views',
    buttons: [{
      id: osm,
      command: osm,
      className: 'fa fa-paint-brush',
      attributes: {
        title: 'Style Manager'
      }
    }, {
      id: otm,
      command: otm,
      className: 'fa fa-cog',
      attributes: {
        title: 'Settings'
      }
    }, {
      id: ola,
      command: ola,
      className: 'fa fa-bars',
      attributes: {
        title: 'Layers'
      }
    }, {
      id: obl,
      command: obl,
      className: 'fa fa-th-large',
      active: 1,
      attributes: {
        title: 'Blocks'
      }
    }]
  }]); // Add devices buttons

  var panelDevices = pn.addPanel({
    id: 'devices-c'
  });
  panelDevices.get('buttons').add([{
    id: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceDesktop"],
    command: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceDesktop"],
    className: 'fa fa-desktop',
    active: 1,
    attributes: {
      title: 'Desktop'
    }
  }, {
    id: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceTablet"],
    command: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceTablet"],
    className: 'fa fa-tablet',
    attributes: {
      title: 'Tablet'
    }
  }, {
    id: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceMobile"],
    command: _consts__WEBPACK_IMPORTED_MODULE_0__["cmdDeviceMobile"],
    className: 'fa fa-mobile',
    attributes: {
      title: 'Mobile'
    }
  }]);
  editor.on('load', function () {
    var pn = editor.Panels;
    var editorEl = jquery__WEBPACK_IMPORTED_MODULE_1___default()(editor.getEl()); // Load and show settings and style manager

    var openTmBtn = pn.getButton('views', 'open-tm');
    openTmBtn && openTmBtn.set('active', 1);
    var openSm = pn.getButton('views', 'open-sm');
    openSm && openSm.set('active', 1); // Add Settings Sector

    var traitsSector = jquery__WEBPACK_IMPORTED_MODULE_1___default()("<div class=\"gjs-sm-sector no-select\">\n<div class=\"gjs-sm-title\"><span class=\"icon-settings fa fa-cog\"></span> Settings</div>\n<div class=\"gjs-sm-properties\"></div></div>");
    var traitsProps = traitsSector.find('.gjs-sm-properties'); // copy from settings tab into traits sector and then hide settings tab

    traitsProps.append(editorEl.find('.gjs-trt-traits'));
    editorEl.find('.gjs-pn-views .fa-cog').hide();
    editorEl.find('.gjs-sm-sectors').before(traitsSector);
    traitsSector.find('.gjs-sm-title').on('click', function () {
      var traitStyle = traitsProps.get(0).style;
      var hidden = traitStyle.display === 'none';

      if (hidden) {
        traitStyle.display = 'block';
      } else {
        traitStyle.display = 'none';
      }
    });
    var openBl = pn.getButton('views', obl);
    openBl && openBl.set('active', 1); // On component change show the Style Manager

    opts.showStylesOnChange && editor.on('component:selected', function () {
      var openSmBtn = pn.getButton('views', osm);
      var openLayersBtn = pn.getButton('views', ola); // Don't switch when the Layer Manager is on or
      // there is no selected component

      if ((!openLayersBtn || !openLayersBtn.get('active')) && editor.getSelected()) {
        openSmBtn && openSmBtn.set('active', 1);
      }
    });
  });
});

/***/ }),

/***/ "./src/traits.js":
/*!***********************!*\
  !*** ./src/traits.js ***!
  \***********************/
/*! exports provided: default */
/***/ (function(module, __webpack_exports__, __webpack_require__) {

"use strict";
__webpack_require__.r(__webpack_exports__);
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0__ = __webpack_require__(/*! jquery */ "jquery");
/* harmony import */ var jquery__WEBPACK_IMPORTED_MODULE_0___default = /*#__PURE__*/__webpack_require__.n(jquery__WEBPACK_IMPORTED_MODULE_0__);
function ownKeys(object, enumerableOnly) { var keys = Object.keys(object); if (Object.getOwnPropertySymbols) { var symbols = Object.getOwnPropertySymbols(object); if (enumerableOnly) symbols = symbols.filter(function (sym) { return Object.getOwnPropertyDescriptor(object, sym).enumerable; }); keys.push.apply(keys, symbols); } return keys; }

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; if (i % 2) { ownKeys(Object(source), true).forEach(function (key) { _defineProperty(target, key, source[key]); }); } else if (Object.getOwnPropertyDescriptors) { Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)); } else { ownKeys(Object(source)).forEach(function (key) { Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key)); }); } } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }


/* harmony default export */ __webpack_exports__["default"] = (function (editor) {
  var opts = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var tm = editor.TraitManager;
  var dc = editor.DomComponents;
  var bm = editor.BlockManager;
  var defaultType = dc.getType('default');
  var selectType = dc.getType('select') || defaultType;
  var defaultModel = defaultType.model;
  var defaultView = defaultType.view;
  var textType = dc.getType('text');
  var fieldTraits = defaultModel.prototype.defaults.traits.concat([{
    type: 'text',
    name: 'name_attr',
    label: 'Name',
    placeholder: 'Enter a field name..'
  }, {
    type: 'text',
    name: 'label_attr',
    label: 'Label',
    placeholder: 'Enter a field label..'
  }]);
  var inputTraits = fieldTraits.concat([{
    type: 'text',
    name: 'value_attr',
    label: 'Value',
    changeProp: 1,
    placeholder: 'Enter a default value..'
  }, {
    type: 'text',
    name: 'placeholder_attr',
    label: 'Placeholder',
    changeProp: 1,
    placeholder: 'Enter placeholder text..'
  }, {
    type: 'checkbox',
    name: 'required_attr',
    changeProp: 1,
    label: 'Required'
  }, {
    type: 'text',
    name: 'pattern_attr',
    changeProp: 1,
    label: 'Pattern'
  }, {
    type: 'number',
    name: 'min_attr',
    changeProp: 1,
    label: 'Min'
  }, {
    type: 'number',
    name: 'max_attr',
    changeProp: 1,
    label: 'Max'
  }]); // text, password, email, number, url, tel, search, date, datetime,
  // datetime-local, month, week, time, range, or color
  // exclude: file

  var inputTypeTrait = {
    type: 'select',
    name: 'type_attr',
    label: 'Type',
    options: [{
      name: 'Text',
      value: 'text'
    }, {
      name: 'Password',
      value: 'password'
    }, {
      name: 'Email',
      value: 'email'
    }, {
      name: 'Number',
      value: 'number'
    }, {
      name: 'URL',
      value: 'url'
    }, {
      name: 'Phone',
      value: 'tel'
    }, // { name: 'Search', value: 'tel' },
    {
      name: 'Date',
      value: 'date'
    }, {
      name: 'Date Time',
      value: 'datetime-local'
    }, // { name: 'Month', value: 'month' },
    // { name: 'Week', value: 'week' },
    {
      name: 'Range',
      value: 'range'
    }, // { name: 'Time', value: 'time' },
    {
      name: 'Color Picker',
      value: 'color'
    }]
  }; // advanced traits

  tm.addType('option_attr', {
    events: {
      'onchange': 'onChange'
    },

    /**
    * Returns the input element
    * @return {HTMLElement}
    */
    getInputEl: function getInputEl() {
      if (!this.inputEl) {
        var input = document.createElement('textarea');
        input.value = this.target.get('option_attr');
        this.inputEl = input;
      }

      return this.inputEl;
    },

    /**
     * Triggered when the value of the model is changed
     */
    onValueChange: function onValueChange() {
      this.target.set('option_attr', this.model.get('value'));
    }
  });
  var myDefaultModel = defaultModel.extend({
    defaults: _objectSpread(_objectSpread({}, defaultModel.prototype.defaults), {}, {
      draggable: true,
      droppable: true,
      copyable: false,
      label_attr: 'Label'
    }),
    // force regenerate of HTML
    toHTML: function toHTML() {
      var cf = opts.comps[this.attributes.tagName];
      var rst = this.genHtml();

      if (cf && cf.useTag) {
        if (cf.useTag === 'innerHTML') {
          return rst.html();
        } else if (cf.useTag === 'innerText') {
          return this.genHtml()[0].innerText;
        }
      }

      return this.genHtml()[0].outerHTML;
    },
    getTraitValues: function getTraitValues() {
      var values = _objectSpread({}, this.attributes);

      this.get('traits').each(function (trait) {
        var k = trait.get('name');
        values[k] = trait.get('value') || values[k];
      });
      return values;
    },
    myInitDefaults: function myInitDefaults() {
      var that = this;

      var values = _objectSpread({}, that.attributes);

      that.get('traits').each(function (trait) {
        var k = trait.get('name'); // set defaults

        if (k.indexOf('_attr') > 0) {
          trait.set('value', values[k]);
        }
      });
    },
    ensureNameAttr: function ensureNameAttr(attrs) {
      var model = this;
      var nameTrait = model.get('traits').where({
        name: 'name_attr'
      })[0];

      if (nameTrait) {
        var name = attrs.name_attr;

        if (!name) {
          name = "".concat(opts.formFieldsPrefix).concat(opts.formNextId++);
          nameTrait.set('value', name);
          model.set('name_attr', name);
          return true;
        }
      }

      return false;
    },
    // function to use with listener
    genHtml: function genHtml() {
      if (!this.view || !this.view.el) {
        var el = document.createElement(this.attributes.tagName);
        return this.generateHtml(el, this.attributes.tagName);
      }

      return this.generateHtml(this.view.el, this.attributes.tagName);
    },
    generateHtml: function generateHtml(el, k) {
      var model = this;
      var $el = jquery__WEBPACK_IMPORTED_MODULE_0___default()(el || model.view.el);
      var attrs = model.getTraitValues();
      var $k = $k || model.get('tagName');

      if (opts && opts.comps && opts.comps[$k]) {
        var templateFn = opts.comps[$k].template;

        if (typeof templateFn === 'function') {
          if (!model.ensureNameAttr(attrs)) {
            $el.empty();
            $el.html(templateFn({
              it: attrs || {}
            }));
          }
        }
      }

      return $el;
    }
  }); // override default view to generate html

  var myDefaultView = defaultView.extend({
    onRender: function onRender() {
      if (this.model.genHtml) {
        this.model.genHtml();
      }

      return this;
    }
  }); // BEGIN: components

  dc.addType('comp_input', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_input',
        type_attr: 'text',
        traits: inputTraits.concat(inputTypeTrait),
        classes: ['comp_input'].concat(opts.comps.comp_input.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:label_attr', that.genHtml);
        that.listenTo(that, 'change:name_attr', that.genHtml);
        that.listenTo(that, 'change:placeholder_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  }); // Select Box

  dc.addType('comp_select', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_select',
        option_attr: 'MN::Minnesota\nTX::Texas',
        label_attr: 'Select',
        traits: [{
          type: 'option_attr'
        }, {
          type: 'checkbox',
          name: 'required_attr',
          changeProp: 1,
          label: 'Required'
        }, {
          type: 'checkbox',
          name: 'multiple_attr',
          changeProp: 1,
          label: 'Multiple'
        }].concat(fieldTraits),
        classes: ['comp_select'].concat(opts.comps.comp_select.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:label_attr', that.genHtml);
        that.listenTo(that, 'change:name_attr', that.genHtml);
        that.listenTo(that, 'change:multiple_attr', that.genHtml);
        that.listenTo(that, 'change:option_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_textarea', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, defaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_textarea',
        label_attr: 'Message',
        traits: [{
          type: 'checkbox',
          name: 'required_attr',
          changeProp: 1,
          label: 'Required'
        }, {
          type: 'text',
          name: 'placeholder_attr',
          label: 'Placeholder',
          changeProp: 1,
          placeholder: 'Enter placeholder text..'
        }, {
          type: 'number',
          name: 'rows_attr',
          label: 'Rows',
          changeProp: 1,
          placeholder: 'Enter field rows/line-height..'
        }, {
          type: 'number',
          name: 'cols_attr',
          label: 'Columns',
          changeProp: 1,
          placeholder: 'Enter field columns/width..'
        }].concat(fieldTraits),
        classes: ['comp_textarea'].concat(opts.comps.comp_textarea.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:label_attr', that.genHtml);
        that.listenTo(that, 'change:name_attr', that.genHtml);
        that.listenTo(that, 'change:placeholder_attr', that.genHtml);
        that.listenTo(that, 'change:cols_attr', that.genHtml);
        that.listenTo(that, 'change:rows_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_file', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, defaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_file',
        label_attr: 'Choose file...',
        traits: [{
          type: 'checkbox',
          name: 'required_attr',
          changeProp: 1,
          label: 'Required'
        }, {
          type: 'text',
          name: 'placeholder_attr',
          label: 'Placeholder',
          changeProp: 1,
          placeholder: 'Enter placeholder text..'
        }, {
          type: 'string',
          name: 'accept_attr',
          label: 'Accept',
          changeProp: 1,
          placeholder: 'image/jpeg, image/png, image/gif'
        }].concat(fieldTraits),
        classes: ['comp_file'].concat(opts.comps.comp_file.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:label_attr', that.genHtml);
        that.listenTo(that, 'change:name_attr', that.genHtml);
        that.listenTo(that, 'change:placeholder_attr', that.genHtml);
        that.listenTo(that, 'change:accept_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_checkbox', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_checkbox',
        label_attr: 'I accept the terms and use',
        traits: [{
          type: 'checkbox',
          name: 'required_attr',
          changeProp: 1,
          label: 'Required'
        }].concat(fieldTraits),
        classes: ['comp_checkbox'].concat(opts.comps.comp_checkbox.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:label_attr', that.genHtml);
        that.listenTo(that, 'change:name_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_hidden', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_hidden',
        traits: [{
          type: 'text',
          name: 'name_attr',
          label: 'Name',
          changeProp: 1,
          placeholder: 'Enter a field name...'
        }],
        classes: ['comp_hidden'].concat(opts.comps.comp_hidden.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:name_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_submit', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_submit',
        label_attr: 'Send',
        traits: defaultModel.prototype.defaults.traits.concat([{
          type: 'text',
          name: 'label_attr',
          label: 'Label',
          placeholder: 'Enter a field label..'
        }]),
        classes: ['comp_submit'].concat(opts.comps.comp_submit.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:label_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_row', {
    model: defaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, defaultModel.prototype.defaults), {}, {
        tagName: 'comp_row',
        // Can be dropped inside other elements
        draggable: true,
        copyable: false,
        // Can drop other elements inside it
        droppable: 'comp_col',
        classes: ['comp_row'].concat(opts.comps.comp_row.classes)
      })
    }),
    view: defaultView
  });
  dc.addType('comp_col', {
    model: defaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, defaultModel.prototype.defaults), {}, {
        tagName: 'comp_col',
        // Can be dropped only inside `row` elements
        draggable: 'comp_row',
        // Can drop other elements inside it
        droppable: true,
        copyable: false,
        classes: ['comp_col'].concat(opts.comps.comp_col.classes)
      })
    }),
    view: defaultView
  });
  dc.addType('comp_recaptcha', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_recaptcha',
        sitekey_attr: 'sitekey',
        action_attr: 'contact-us',
        traits: defaultModel.prototype.defaults.traits.concat([{
          type: 'text',
          name: 'sitekey_attr',
          label: 'Site key',
          placeholder: 'e.g. sitekey'
        }, {
          type: 'text',
          name: 'action_attr',
          label: 'Action',
          placeholder: 'recaptcha action name'
        }]),
        classes: ['comp_recaptcha'].concat(opts.comps.comp_recaptcha.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:sitekey_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
  dc.addType('comp_stripe', {
    model: myDefaultModel.extend({
      defaults: _objectSpread(_objectSpread({}, myDefaultModel.prototype.defaults), {}, {
        draggable: true,
        droppable: false,
        copyable: false,
        tagName: 'comp_stripe',
        hidepostal_attr: 'false',
        traits: defaultModel.prototype.defaults.traits.concat([{
          type: 'text',
          name: 'publickey_attr',
          label: 'Public key',
          placeholder: 'e.g. stripe public key'
        }, {
          type: 'checkbox',
          name: 'hidepostal_attr',
          label: 'Hide postal'
        }]),
        classes: ['comp_stripe'].concat(opts.comps.comp_recaptcha.classes)
      }),
      init: function init() {
        var that = this;
        that.myInitDefaults();
        that.listenTo(that, 'change:publickey_attr', that.genHtml);
      }
    }),
    view: myDefaultView
  });
});

/***/ }),

/***/ 0:
/*!********************************!*\
  !*** multi ./example/index.js ***!
  \********************************/
/*! no static exports found */
/***/ (function(module, exports, __webpack_require__) {

module.exports = __webpack_require__(/*! /Users/techsupport/Desktop/work/niiknow/grapesjs-components-farmer/example/index.js */"./example/index.js");


/***/ }),

/***/ "grapesjs":
/*!***************************!*\
  !*** external "grapesjs" ***!
  \***************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_grapesjs__;

/***/ }),

/***/ "jquery":
/*!*************************!*\
  !*** external "jQuery" ***!
  \*************************/
/*! no static exports found */
/***/ (function(module, exports) {

module.exports = __WEBPACK_EXTERNAL_MODULE_jquery__;

/***/ })

/******/ });
});
//# sourceMappingURL=index.js.map