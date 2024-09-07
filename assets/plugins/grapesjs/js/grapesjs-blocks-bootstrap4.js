/*! For license information please see grapesjs-blocks-bootstrap4.min.js.LICENSE.txt */ ! function(e, t) {
	"object" == typeof exports && "object" == typeof module ? module.exports = t(require("grapesjs")) : "function" == typeof define && define.amd ? define(["grapesjs"], t) : "object" == typeof exports ? exports["grapesjs-blocks-bootstrap4"] = t(require("grapesjs")) : e["grapesjs-blocks-bootstrap4"] = t(e.grapesjs)
}(self, (function(e) {
	return (() => {
		"use strict";
		var t = {
				187: (e, t, a) => {
					a.r(t), a.d(t, {
						default: () => qt
					});
					var n = a(520),
						o = a.n(n);
					const r = function(e) {
							e.Commands
						},
						i = function(e) {
							var t = e.TraitManager;
							t.addType("class_select", {
								events: {
									change: "onChange"
								},
								createInput: function(e) {
									e.trait;
									for (var t = this.model.get("options") || [], a = document.createElement("select"), n = this.target.view.el, o = function(e) {
											var o = document.createElement("option"),
												r = t[e].value;
											"" === r && (r = "GJS_NO_CLASS"), o.text = t[e].name, o.value = r;
											var i = Array.from(n.classList),
												l = r.split(" ");
											i.filter((function(e) {
												return l.includes(e)
											})).length === l.length && o.setAttribute("selected", "selected"), a.append(o)
										}, r = 0; r < t.length; r++) o(r);
									return a
								},
								onUpdate: function(e) {
									for (var t = e.elInput, a = e.component.getClasses(), n = this.model.get("options") || [], o = 0; o < n.length; o++) {
										var r = n[o].value;
										if (r && a.includes(r)) return void(t.value = r)
									}
									t.value = "GJS_NO_CLASS"
								},
								onEvent: function(e) {
									e.elInput;
									for (var t = e.component, a = (e.event, this.model.get("options").map((function(e) {
											return e.value
										}))), n = 0; n < a.length; n++)
										if (a[n].length > 0)
											for (var o = a[n].split(" "), r = 0; r < o.length; r++) o[r].length > 0 && t.removeClass(o[r]);
									var i = this.model.get("value");
									if (delete t.attributes.attributes[""], i.length > 0 && "GJS_NO_CLASS" !== i)
										for (var l = i.split(" "), s = 0; s < l.length; s++) t.addClass(l[s]);
									t.em.trigger("component:toggled")
								}
							});
							var a = t.getType("text");
							t.addType("content", {
								events: {
									keyup: "onChange"
								},
								onValueChange: function() {
									var e = this.model;
									e.target.set("content", e.get("value"))
								},
								getInputEl: function() {
									return this.inputEl || (this.inputEl = a.prototype.getInputEl.bind(this)(), this.inputEl.value = this.target.get("content")), this.inputEl
								}
							}), t.addType("content", {
								events: {
									keyup: "onChange"
								},
								onValueChange: function() {
									var e = this.model;
									e.target.set("content", e.get("value"))
								},
								getInputEl: function() {
									return this.inputEl || (this.inputEl = a.prototype.getInputEl.bind(this)(), this.inputEl.value = this.target.get("content")), this.inputEl
								}
							})
						};
					var l = function(e, t) {
						e.add("collapse", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="compress" class="svg-inline--fa fa-compress fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M436 192H312c-13.3 0-24-10.7-24-24V44c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v84h84c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm-276-24V44c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v84H12c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h124c13.3 0 24-10.7 24-24zm0 300V344c0-13.3-10.7-24-24-24H12c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h84v84c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm192 0v-84h84c6.6 0 12-5.4 12-12v-40c0-6.6-5.4-12-12-12H312c-13.3 0-24 10.7-24 24v124c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Components",
							content: {
								type: "collapse"
							}
						})
					};
					const s = function(e) {
						var t = e.DomComponents,
							a = t.getType("default"),
							n = a.model,
							o = a.view;
						t.addType("collapse", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Dropdown",
									classes: ["collapse"],
									droppable: !0,
									traits: [{
										type: "class_select",
										options: [{
											value: "",
											name: "Closed"
										}, {
											value: "show",
											name: "Open"
										}],
										label: "Initial state"
									}].concat(n.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("dropdown")) return {
										type: "dropdown"
									}
								}
							}),
							view: o.extend({})
						})
					};

					function c(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function d(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? c(Object(a), !0).forEach((function(t) {
								p(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : c(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function p(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var u = function(e, t) {
						e.add("dropdown", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="far" data-icon="caret-square-down" class="svg-inline--fa fa-caret-square-down fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M125.1 208h197.8c10.7 0 16.1 13 8.5 20.5l-98.9 98.3c-4.7 4.7-12.2 4.7-16.9 0l-98.9-98.3c-7.7-7.5-2.3-20.5 8.4-20.5zM448 80v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V80c0-26.5 21.5-48 48-48h352c26.5 0 48 21.5 48 48zm-48 346V86c0-3.3-2.7-6-6-6H54c-3.3 0-6 2.7-6 6v340c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Components",
							content: {
								type: "dropdown"
							}
						})
					};
					const m = function(e) {
						var t = e.DomComponents,
							a = t.getType("default"),
							n = a.model,
							o = a.view;

						function r(e) {
							var t = e._events["change:attributes"];
							return !!t && 0 !== t.filter((function(e) {
								return "setupToggle" === e.callback.name
							})).length
						}
						t.addType("dropdown", {
							model: n.extend({
								defaults: d(d({}, n.prototype.defaults), {}, {
									"custom-name": "Dropdown",
									classes: ["dropdown"],
									droppable: "a, button, .dropdown-menu",
									traits: [{
										type: "select",
										label: "Initial state",
										name: "initial_state",
										options: [{
											value: "",
											name: "Closed"
										}, {
											value: "show",
											name: "Open"
										}]
									}].concat(n.prototype.defaults.traits)
								}),
								init2: function() {
									this.append({
										type: "button",
										content: "Click to toggle",
										classes: ["btn", "dropdown-toggle"]
									})[0], this.append({
										type: "dropdown_menu"
									})[0];
									this.setupToggle(null, null, {
										force: !0
									});
									var e = this.components();
									e.bind("add", this.setupToggle.bind(this)), e.bind("remove", this.setupToggle.bind(this));
									var t = this.get("classes");
									t.bind("add", this.setupToggle.bind(this)), t.bind("change", this.setupToggle.bind(this)), t.bind("remove", this.setupToggle.bind(this))
								},
								setupToggle: function(e, t) {
									var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
										n = this.components().filter((function(e) {
											return e.getAttributes().class.split(" ").includes("dropdown-toggle")
										}))[0],
										o = this.components().filter((function(e) {
											return e.getAttributes().class.split(" ").includes("dropdown-menu")
										}))[0];
									if ((!0 === a.force || !0 !== a.ignore) && n && o) {
										r(n) || this.listenTo(n, "change:attributes", this.setupToggle), r(o) || this.listenTo(o, "change:attributes", this.setupToggle);
										var i = n.getAttributes();
										i.role = "button";
										var l = o.getAttributes();
										i.hasOwnProperty("data-toggle") || (i["data-toggle"] = "dropdown"), i.hasOwnProperty("aria-haspopup") || (i["aria-haspopup"] = !0), n.set("attributes", i, {
											ignore: !0
										}), i.hasOwnProperty("id") ? l["aria-labelledby"] = i.id : delete l["aria-labelledby"], o.set("attributes", l, {
											ignore: !0
										})
									}
								},
								updated: function(e, t) {
									if (t.hasOwnProperty("initial_state")) {
										var a = this.components().filter((function(e) {
												return e.getAttributes().class.split(" ").includes("dropdown-menu")
											}))[0],
											n = a.getAttributes();
										n.class.split(" ").includes("show") ? (n["aria-expanded"] = !1, a.removeClass("show")) : (n["aria-expanded"] = !0, a.addClass("show"))
									}
								}
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("dropdown")) return {
										type: "dropdown"
									}
								}
							}),
							view: o
						}), t.addType("dropdown_menu", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Dropdown Menu",
									classes: ["dropdown-menu"],
									draggable: ".dropdown",
									droppable: !0
								}),
								init2: function() {
									var e = {
										type: "link",
										classes: ["dropdown-item"],
										content: "Dropdown item"
									};
									this.append({
										type: "header",
										tagName: "h6",
										classes: ["dropdown-header"],
										content: "Dropdown header"
									}), this.append(e), this.append({
										type: "default",
										classes: ["dropdown-divider"]
									}), this.append(e)
								}
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("dropdown-menu")) return {
										type: "dropdown_menu"
									}
								}
							}),
							view: o
						})
					};
					var f = "tabs-",
						b = "".concat(f, "container"),
						g = "".concat(f, "navigation"),
						v = "".concat(f, "panes"),
						h = "".concat(f, "tab"),
						y = "".concat(f, "tab-pane");
					const w = {
						navigationName: g,
						tabPanesName: v,
						tabName: h,
						tabPaneName: y,
						navigationSelector: '[data-gjs-type="'.concat(g, '"]'),
						tabPanesSelector: '[data-gjs-type="'.concat(v, '"]'),
						tabSelector: '[data-gjs-type="'.concat(h, '"]'),
						tabPaneSelector: '[data-gjs-type="'.concat(y, '"]'),
						containerId: "data-".concat(b),
						navigationId: "data-".concat(g),
						tabPanesId: "data-".concat(v),
						tabId: "data-".concat(h),
						tabPaneId: "data-".concat(y)
					};
					var O = function(e, t) {
							var a = e.className;
							if ((a = a && a.toString()) && a.split(" ").indexOf(t) >= 0) return 1
						},
						j = function(e) {
							return e.toLowerCase().split(" ").map((function(e) {
								return e.charAt(0).toUpperCase() + e.slice(1)
							})).join(" ")
						};
					const x = '<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="window-maximize" class="svg-inline--fa fa-window-maximize fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 32H48C21.5 32 0 53.5 0 80v352c0 26.5 21.5 48 48 48h416c26.5 0 48-21.5 48-48V80c0-26.5-21.5-48-48-48zm-16 160H64v-84c0-6.6 5.4-12 12-12h360c6.6 0 12 5.4 12 12v84z"></path></svg>\r\n';

					function C(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function k(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? C(Object(a), !0).forEach((function(t) {
								T(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : C(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function T(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var P = function(e, t) {
						e.add("tabs", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="ellipsis-h" class="svg-inline--fa fa-ellipsis-h fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M328 256c0 39.8-32.2 72-72 72s-72-32.2-72-72 32.2-72 72-72 72 32.2 72 72zm104-72c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72zm-352 0c-39.8 0-72 32.2-72 72s32.2 72 72 72 72-32.2 72-72-32.2-72-72-72z"></path></svg>\r\n', "\n			<div>").concat(t.labels.tabs, "</div>\n		"),
							category: "Components",
							content: '\n			<ul class="nav nav-tabs" role="tablist">\n			  <li class="nav-item">\n				<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tab 1</a>\n			  </li>\n			  <li class="nav-item">\n				<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Tab 2</a>\n			  </li>\n			  <li class="nav-item">\n				<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Tab 3</a>\n			  </li>\n			</ul>\n			<div class="tab-content">\n			  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab"></div>\n			  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab"></div>\n			  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab"></div>\n			</div>\n		'
						}), e.add("tabs-tab", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="circle" class="svg-inline--fa fa-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8z"></path></svg>\r\n', "\n			<div>").concat(t.labels.tab, "</div>\n		"),
							category: "Components",
							content: {
								type: "tabs-tab"
							}
						}), e.add("tabs-tab-pane", {
							label: "\n			".concat(x, "\n			<div>").concat(t.labels.tabPane, "</div>\n		"),
							category: "Components",
							content: {
								type: "tabs-tab-pane"
							}
						})
					};
					const _ = function(e) {
						var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
							a = e.getType("default"),
							n = a.model,
							o = a.view,
							r = w.navigationName,
							i = w.tabSelector,
							l = t.classNavigation,
							s = r;
						e.addType(s, {
							model: n.extend({
								defaults: k(k({}, n.prototype.defaults), {}, {
									name: "Tabs Navigation",
									copyable: 0,
									draggable: !0,
									droppable: i,
									traits: [{
										type: "class_select",
										options: [{
											value: "nav-tabs",
											name: "Tabs"
										}, {
											value: "nav-pills",
											name: "Pills"
										}],
										label: "Type"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "Left"
										}, {
											value: "nav-fill",
											name: "Fill"
										}, {
											value: "nav-justified",
											name: "Justify"
										}],
										label: "Layout"
									}]
								}),
								init: function() {
									this.get("classes").pluck("name").indexOf(l) < 0 && this.addClass(l)
								}
							}, {
								isComponent: function(e) {
									if (O(e, l)) return {
										type: s
									}
								}
							}),
							view: o.extend({
								init: function() {
									var e = ["type", "layout"].map((function(e) {
										return "change:".concat(e)
									})).join(" ");
									this.listenTo(this.model, e, this.render);
									var t = this.model.components();
									t.length || t.add('\n						<ul class="nav nav-tabs" role="tablist">\n						  <li class="nav-item">\n							<a class="nav-link active" id="home-tab" data-toggle="tab" href="#home" role="tab" aria-controls="home" aria-selected="true">Tab 1</a>\n						  </li>\n						  <li class="nav-item">\n							<a class="nav-link" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false">Tab 2</a>\n						  </li>\n						  <li class="nav-item">\n							<a class="nav-link" id="contact-tab" data-toggle="tab" href="#contact" role="tab" aria-controls="contact" aria-selected="false">Tab 3</a>\n						  </li>\n						</ul>\n					')
								}
							})
						})
					};

					function L(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function S(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? L(Object(a), !0).forEach((function(t) {
								D(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : L(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function D(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					const N = function(e) {
						var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
							a = e.getType("default"),
							n = a.model,
							o = a.view,
							r = w.tabPanesName,
							i = w.tabPaneSelector,
							l = t.classTabPanes,
							s = r;
						e.addType(s, {
							model: n.extend({
								defaults: S(S({}, n.prototype.defaults), {}, {
									name: "Tabs Panes",
									copyable: 0,
									draggable: !0,
									droppable: i
								}),
								init: function() {
									this.get("classes").pluck("name").indexOf(l) < 0 && this.addClass(l)
								}
							}, {
								isComponent: function(e) {
									if (O(e, l)) return {
										type: s
									}
								}
							}),
							view: o.extend({
								init: function() {
									var e = this.model.components();
									e.length || e.add('\n						<div class="tab-content" id="myTabContent">\n						  <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">Tab pane 1</div>\n						  <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">Tab pane 2</div>\n						  <div class="tab-pane fade" id="contact" role="tabpanel" aria-labelledby="contact-tab">Tab pane 3</div>\n						</div>\n					')
								}
							})
						})
					};

					function M(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function A(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? M(Object(a), !0).forEach((function(t) {
								E(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : M(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function E(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					const I = function(e) {
						var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
							a = e.getType("default"),
							n = a.model,
							o = a.view,
							r = w.tabName,
							i = w.navigationSelector,
							l = t.classTab,
							s = r;
						e.addType(s, {
							model: n.extend({
								defaults: A(A({}, n.prototype.defaults), {}, {
									name: "Tab",
									tagName: "li",
									copyable: !0,
									draggable: i
								}),
								init: function() {
									this.get("classes").pluck("name").indexOf(l) < 0 && this.addClass(l)
								}
							}, {
								isComponent: function(e) {
									if (O(e, l)) return {
										type: s
									}
								}
							}),
							view: o.extend({
								init: function() {
									var e = this.model.components();
									e.length || e.add('\n			  <a class="nav-link active" id="tab-1" data-toggle="tab" href="#tab-pane-1" role="tab" aria-controls="tab" aria-selected="true">Tab</a>\n		  ')
								}
							})
						})
					};

					function B(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function z(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? B(Object(a), !0).forEach((function(t) {
								H(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : B(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function H(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					const F = function(e) {
						var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
							a = e.getType("default"),
							n = a.model,
							o = a.view,
							r = w.tabPaneName,
							i = w.tabPanesSelector,
							l = t.classTabPane,
							s = r;
						e.addType(s, {
							model: n.extend({
								defaults: z(z({}, n.prototype.defaults), {}, {
									name: "Tab Pane",
									copyable: !0,
									draggable: i,
									traits: ["id", {
										type: "class_select",
										options: [{
											value: "fade",
											name: "Fade"
										}, {
											value: "",
											name: "None"
										}],
										label: "Animation"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "Inactive"
										}, {
											value: "active",
											name: "Active"
										}],
										label: "Is Active"
									}]
								}),
								init: function() {
									this.get("classes").pluck("name").indexOf(l) < 0 && this.addClass(l)
								}
							}, {
								isComponent: function(e) {
									if (O(e, l)) return {
										type: s
									}
								}
							}),
							view: o
						})
					};

					function V(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function q(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? V(Object(a), !0).forEach((function(t) {
								R(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : V(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function R(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var Z = function(e, t) {
						e.add("form", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,5.5 C22,5.2 21.5,5 20.75,5 L3.25,5 C2.5,5 2,5.2 2,5.5 L2,8.5 C2,8.8 2.5,9 3.25,9 L20.75,9 C21.5,9 22,8.8 22,8.5 L22,5.5 Z M21,8 L3,8 L3,6 L21,6 L21,8 Z" fill-rule="nonzero"></path>\r\n	<path class="gjs-block-svg-path" d="M22,10.5 C22,10.2 21.5,10 20.75,10 L3.25,10 C2.5,10 2,10.2 2,10.5 L2,13.5 C2,13.8 2.5,14 3.25,14 L20.75,14 C21.5,14 22,13.8 22,13.5 L22,10.5 Z M21,13 L3,13 L3,11 L21,11 L21,13 Z" fill-rule="nonzero"></path>\r\n	<rect class="gjs-block-svg-path" x="2" y="15" width="10" height="3" rx="0.5"></rect>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: '\n		<form>\n		  <div class="form-group">\n			<label>Name</label>\n			<input name="name" placeholder="Type here your name" class="form-control"/>\n		  </div>\n		  <div class="form-group">\n			<label>Email</label>\n			<input name="email" type="email" placeholder="Type here your email" class="form-control"/>\n		  </div>\n		  <div class="form-check">\n			<input name="sex" type="checkbox" class="form-check-input" value="M">\n			<label class="form-check-label">M</label>\n		  </div>\n		  <div class="form-check">\n			<input name="sex" type="checkbox" class="form-check-input" value="F">\n			<label class="form-check-label">F</label>\n		  </div>\n		  <div class="form-group">\n			<label>Message</label>\n			<textarea name="message" class="form-control"></textarea>\n		  </div>\n		  <div class="form-group">\n			<button type="submit" class="btn btn-primary">Send</button>\n		  </div>\n		</form>\n	  '
						})
					};
					const U = function(e, t) {
						var a, n = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							o = e.getType("default"),
							r = o.model,
							i = o.view;
						n.formPredefinedActions && n.formPredefinedActions.length ? (a = {
							type: "select",
							label: n.labels.trait_action,
							name: "action",
							options: []
						}, n.formPredefinedActions.forEach((function(e) {
							a.options.push({
								value: e.value,
								name: e.name
							})
						}))) : a = {
							label: n.labels.trait_action,
							name: "action"
						}, e.addType("form", {
							model: r.extend({
								defaults: q(q({}, r.prototype.defaults), {}, {
									droppable: ":not(form)",
									draggable: ":not(form)",
									traits: [{
										type: "select",
										label: n.labels.trait_enctype,
										name: "enctype",
										options: [{
											value: "application/x-www-form-urlencoded",
											name: "application/x-www-form-urlencoded (default)"
										}, {
											value: "multipart/form-data",
											name: "multipart/form-data"
										}, {
											value: "text/plain",
											name: "text/plain"
										}]
									}, {
										type: "select",
										label: n.labels.trait_method,
										name: "method",
										options: [{
											value: "post",
											name: "POST"
										}, {
											value: "get",
											name: "GET"
										}]
									}, a]
								}),
								init: function() {
									this.listenTo(this, "change:formState", this.updateFormState)
								},
								updateFormState: function() {
									switch (this.get("formState")) {
										case "success":
											this.showState("success");
											break;
										case "error":
											this.showState("error");
											break;
										default:
											this.showState("normal")
									}
								},
								showState: function(e) {
									var t, a, n = e || "normal";
									"success" === n ? (t = "none", a = "block") : "error" === n ? (t = "block", a = "none") : (t = "none", a = "none");
									var o = this.getStateModel("success"),
										r = this.getStateModel("error"),
										i = o.getStyle(),
										l = r.getStyle();
									i.display = a, l.display = t, o.setStyle(i), r.setStyle(l)
								},
								getStateModel: function(e) {
									for (var t, a = e || "success", n = this.get("components"), o = 0; o < n.length; o++) {
										var r = n.models[o];
										if (r.get("form-state-type") === a) {
											t = r;
											break
										}
									}
									if (!t) {
										var i = formMsgSuccess;
										"error" === a && (i = formMsgError), t = n.add({
											"form-state-type": a,
											type: "text",
											removable: !1,
											copyable: !1,
											draggable: !1,
											attributes: {
												"data-form-state": a
											},
											content: i
										})
									}
									return t
								}
							}, {
								isComponent: function(e) {
									if ("FORM" === e.tagName) return {
										type: "form"
									}
								}
							}),
							view: i.extend({
								events: {
									submit: function(e) {
										e.preventDefault()
									}
								}
							})
						})
					};

					function $(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function G(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? $(Object(a), !0).forEach((function(t) {
								W(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : $(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function W(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var X = function(e, t) {
						e.add("input", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z"></path>\r\n	<polygon class="gjs-block-svg-path" points="4 10 5 10 5 14 4 14"></polygon>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: '<input name="input1" class="form-control"/>'
						})
					};
					const J = function(e, t) {
						var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							n = e.getType("default"),
							o = n.model,
							r = n.view;
						e.addType("input", {
							model: o.extend({
								defaults: G(G({}, o.prototype.defaults), {}, {
									"custom-name": a.labels.input,
									tagName: "input",
									draggable: "form .form-group",
									droppable: !1,
									traits: [t.value, t.name, t.placeholder, {
										label: a.labels.trait_type,
										type: "select",
										name: "type",
										options: [{
											value: "text",
											name: a.labels.type_text
										}, {
											value: "email",
											name: a.labels.type_email
										}, {
											value: "password",
											name: a.labels.type_password
										}, {
											value: "number",
											name: a.labels.type_number
										}, {
											value: "date",
											name: a.labels.type_date
										}, {
											value: "hidden",
											name: a.labels.type_hidden
										}]
									}, t.required]
								})
							}, {
								isComponent: function(e) {
									if ("INPUT" === e.tagName) return {
										type: "input"
									}
								}
							}),
							view: r
						})
					};

					function Y(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function K(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? Y(Object(a), !0).forEach((function(t) {
								Q(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : Y(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function Q(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var ee = function(e, t, a) {
						e.add("form_group_input", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z"></path>\r\n	<polygon class="gjs-block-svg-path" points="4 10 5 10 5 14 4 14"></polygon>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: '\n	  <div class="form-group">\n		<label>Name</label>\n		<input name="name" placeholder="Type here your name" class="form-control"/>\n	  </div>\n	  '
						}), e.add("input_group", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z"></path>\r\n	<polygon class="gjs-block-svg-path" points="4 10 5 10 5 14 4 14"></polygon>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: '\n	  <div class="input-group">\n		<div class="input-group-prepend">\n		  <span class="input-group-text">$</span>\n		</div>\n		<input name="input1" type="text" class="form-control" aria-label="Amount (to the nearest dollar)">\n		<div class="input-group-append">\n		  <span class="input-group-text">.00</span>\n		</div>\n	  </div>\n	  '
						})
					};
					const te = function(e, t) {
						var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							n = e.getType("default"),
							o = n.model,
							r = n.view;
						e.addType("input_group", {
							model: o.extend({
								defaults: K(K({}, o.prototype.defaults), {}, {
									"custom-name": a.labels.input_group,
									tagName: "div",
									traits: []
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("form_group_input")) return {
										type: "form_group_input"
									}
								}
							}),
							view: r
						})
					};

					function ae(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function ne(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? ae(Object(a), !0).forEach((function(t) {
								oe(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : ae(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function oe(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var re = function(e, t) {
						e.add("textarea", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,7.5 C22,6.6 21.5,6 20.75,6 L3.25,6 C2.5,6 2,6.6 2,7.5 L2,16.5 C2,17.4 2.5,18 3.25,18 L20.75,18 C21.5,18 22,17.4 22,16.5 L22,7.5 Z M21,17 L3,17 L3,7 L21,7 L21,17 Z"></path>\r\n	<polygon class="gjs-block-svg-path" points="4 8 5 8 5 12 4 12"></polygon>\r\n	<polygon class="gjs-block-svg-path" points="19 7 20 7 20 17 19 17"></polygon>\r\n	<polygon class="gjs-block-svg-path" points="20 8 21 8 21 9 20 9"></polygon>\r\n	<polygon class="gjs-block-svg-path" points="20 15 21 15 21 16 20 16"></polygon>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: '<textarea name="textarea1" class="form-control"></textarea>'
						})
					};
					const ie = function(e, t) {
						var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							n = e.getType("default"),
							o = n.view,
							r = e.getType("input"),
							i = r.model;
						e.addType("textarea", {
							model: i.extend({
								defaults: ne(ne({}, i.prototype.defaults), {}, {
									"custom-name": a.labels.textarea,
									tagName: "textarea",
									traits: [t.name, t.placeholder, t.required]
								})
							}, {
								isComponent: function(e) {
									if ("TEXTAREA" === e.tagName) return {
										type: "textarea"
									}
								}
							}),
							view: o
						})
					};

					function le(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function se(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? le(Object(a), !0).forEach((function(t) {
								ce(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : le(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function ce(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var de = function(e, t) {
						e.add("select", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z" fill-rule="nonzero"></path>\r\n	<polygon class="gjs-block-svg-path" transform="translate(18.500000, 12.000000) scale(1, -1) translate(-18.500000, -12.000000) " points="18.5 11 20 13 17 13"></polygon>\r\n	<rect class="gjs-block-svg-path" x="4" y="11.5" width="11" height="1"></rect>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: '<select class="form-control" name="select1">\n		'.concat(t ? '<option value="">'.concat(t, "</option>") : "", '\n		<option value="1">').concat(t, " 1</option>\n		</select>")
						})
					};
					const pe = function(e, t, a) {
						var n = arguments.length > 3 && void 0 !== arguments[3] ? arguments[3] : {},
							o = t.getType("default"),
							r = o.model,
							i = t.getType("input"),
							l = i.model,
							s = function() {
								return o.view.extend({
									events: {
										mousedown: "handleClick"
									},
									handleClick: function(e) {
										e.preventDefault()
									}
								})
							};
						t.addType("select", {
							model: r.extend({
								defaults: se(se({}, l.prototype.defaults), {}, {
									"custom-name": n.labels.select,
									tagName: "select",
									traits: [a.name, {
										label: n.labels.trait_options,
										type: "select-options"
									}, a.required]
								})
							}, {
								isComponent: function(e) {
									if ("SELECT" === e.tagName) return {
										type: "select"
									}
								}
							}),
							view: s()
						});
						var c = e.TraitManager;
						c.addType("select-options", {
							events: {
								keyup: "onChange"
							},
							onValueChange: function() {
								for (var e = this.model.get("value").trim().split("\n"), t = [], a = 0; a < e.length; a++) {
									var o = e[a].split(n.optionsStringSeparator),
										r = {
											tagName: "option",
											attributes: {}
										};
									o[1] ? (r.content = o[1], r.attributes.value = o[0]) : (r.content = o[0], r.attributes.value = o[0]), t.push(r)
								}
								this.target.get("components").reset(t), this.target.view.render()
							},
							getInputEl: function() {
								if (!this.$input) {
									for (var e = "", t = this.target.get("components"), a = 0; a < t.length; a++) {
										var o = t.models[a],
											r = o.get("attributes").value || "";
										e += "".concat(r).concat(n.optionsStringSeparator).concat(o.get("content"), "\n")
									}
									this.$input = document.createElement("textarea"), this.$input.value = e
								}
								return this.$input
							}
						})
					};

					function ue(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function me(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? ue(Object(a), !0).forEach((function(t) {
								fe(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : ue(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function fe(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var be = function(e, t) {
						e.add("checkbox", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="check-square" class="svg-inline--fa fa-check-square fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M400 480H48c-26.51 0-48-21.49-48-48V80c0-26.51 21.49-48 48-48h352c26.51 0 48 21.49 48 48v352c0 26.51-21.49 48-48 48zm-204.686-98.059l184-184c6.248-6.248 6.248-16.379 0-22.627l-22.627-22.627c-6.248-6.248-16.379-6.249-22.628 0L184 302.745l-70.059-70.059c-6.248-6.248-16.379-6.248-22.628 0l-22.627 22.627c-6.248 6.248-6.248 16.379 0 22.627l104 104c6.249 6.25 16.379 6.25 22.628.001z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Forms",
							content: '\n		<div class="form-check">\n		  <input class="form-check-input" type="checkbox" value="" id="defaultCheck1">\n		  <label class="form-check-label" for="defaultCheck1">\n			Default checkbox\n		  </label>\n		</div>\n	  '
						})
					};
					const ge = function(e, t) {
						var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							n = e.getType("default"),
							o = n.model,
							r = n.view,
							i = e.getType("input"),
							l = i.model;
						e.addType("checkbox", {
							model: o.extend({
								defaults: me(me({}, l.prototype.defaults), {}, {
									"custom-name": a.labels.checkbox_name,
									copyable: !1,
									droppable: !1,
									attributes: {
										type: "checkbox"
									},
									traits: [t.id, t.name, t.value, t.required, t.checked]
								}),
								init: function() {
									this.listenTo(this, "change:checked", this.handleChecked)
								},
								handleChecked: function() {
									var e = this.get("checked"),
										t = this.get("attributes"),
										a = this.view;
									e ? t.checked = !0 : delete t.checked, a && (a.el.checked = e), this.set("attributes", me({}, t))
								}
							}, {
								isComponent: function(e) {
									if ("INPUT" === e.tagName && "checkbox" === e.type) return {
										type: "checkbox"
									}
								}
							}),
							view: r.extend({
								events: {
									click: "handleClick"
								},
								handleClick: function(e) {
									e.preventDefault()
								}
							})
						})
					};

					function ve(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function he(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? ve(Object(a), !0).forEach((function(t) {
								ye(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : ve(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function ye(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var we = function(e, t) {
						e.add("radio", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="far" data-icon="dot-circle" class="svg-inline--fa fa-dot-circle fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M256 56c110.532 0 200 89.451 200 200 0 110.532-89.451 200-200 200-110.532 0-200-89.451-200-200 0-110.532 89.451-200 200-200m0-48C119.033 8 8 119.033 8 256s111.033 248 248 248 248-111.033 248-248S392.967 8 256 8zm0 168c-44.183 0-80 35.817-80 80s35.817 80 80 80 80-35.817 80-80-35.817-80-80-80z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Forms",
							content: '\n		<div class="form-check">\n		  <input class="form-check-input" type="radio" name="exampleRadios" id="exampleRadios1" value="option1" checked>\n		  <label class="form-check-label" for="exampleRadios1">\n			Default radio\n		  </label>\n		</div>\n	  '
						})
					};
					const Oe = function(e, t) {
							var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
								n = e.getType("checkbox");
							e.addType("radio", {
								model: n.model.extend({
									defaults: he(he({}, n.model.prototype.defaults), {}, {
										"custom-name": a.labels.radio,
										attributes: {
											type: "radio"
										}
									})
								}, {
									isComponent: function(e) {
										if ("INPUT" === e.tagName && "radio" === e.type) return {
											type: "radio"
										}
									}
								}),
								view: n.view
							})
						},
						je = ["primary", "secondary", "success", "info", "warning", "danger", "light", "dark"],
						xe = {
							lg: "Large",
							sm: "Small"
						},
						Ce = '<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z" fill-rule="nonzero"></path>\r\n	<rect class="gjs-block-svg-path" x="4" y="11.5" width="16" height="1"></rect>\r\n</svg>\r\n';

					function ke(e) {
						return function(e) {
							if (Array.isArray(e)) return Te(e)
						}(e) || function(e) {
							if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e)
						}(e) || function(e, t) {
							if (!e) return;
							if ("string" == typeof e) return Te(e, t);
							var a = Object.prototype.toString.call(e).slice(8, -1);
							"Object" === a && e.constructor && (a = e.constructor.name);
							if ("Map" === a || "Set" === a) return Array.from(e);
							if ("Arguments" === a || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)) return Te(e, t)
						}(e) || function() {
							throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function Te(e, t) {
						(null == t || t > e.length) && (t = e.length);
						for (var a = 0, n = new Array(t); a < t; a++) n[a] = e[a];
						return n
					}

					function Pe(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function _e(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? Pe(Object(a), !0).forEach((function(t) {
								Le(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : Pe(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function Le(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var Se = function(e, t) {
						e.add("button", {
							label: "".concat(Ce, "<div>").concat(t, "</div>"),
							category: "Forms",
							content: '<button class="btn btn-primary">Send</button>'
						})
					};
					const De = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("button", {
							model: a.extend({
								defaults: _e(_e({}, a.prototype.defaults), {}, {
									"custom-name": "Button",
									droppable: !1,
									attributes: {
										role: "button"
									},
									classes: ["btn"],
									traits: [{
										type: "content",
										label: "Text"
									}, {
										label: "Type",
										type: "select",
										name: "type",
										options: [{
											value: "submit",
											name: "Submit"
										}, {
											value: "reset",
											name: "Reset"
										}, {
											value: "button",
											name: "Button"
										}]
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(ke(je.map((function(e) {
											return {
												value: "btn-".concat(e),
												name: j(e)
											}
										}))), ke(je.map((function(e) {
											return {
												value: "btn-outline-".concat(e),
												name: j(e) + " (Outline)"
											}
										})))),
										label: "Context"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "Default"
										}].concat(ke(Object.keys(xe).map((function(e) {
											return {
												value: "btn-".concat(e),
												name: xe[e]
											}
										})))),
										label: "Size"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "Inline"
										}, {
											value: "btn-block",
											name: "Block"
										}],
										label: "Width"
									}].concat(a.prototype.defaults.traits)
								}),
								afterChange: function(e) {
									"button" === this.attributes.type && 0 === this.attributes.classes.filter((function(e) {
										return "btn" === e.id
									})).length && this.changeType("link")
								}
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("btn")) return {
										type: "button"
									}
								}
							}),
							view: n.extend({
								events: {
									click: "handleClick"
								},
								init: function() {
									this.listenTo(this.model, "change:content", this.updateContent)
								},
								updateContent: function() {
									this.el.innerHTML = this.model.get("content")
								},
								handleClick: function(e) {
									e.preventDefault()
								}
							})
						})
					};

					function Ne(e) {
						return function(e) {
							if (Array.isArray(e)) return Me(e)
						}(e) || function(e) {
							if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e)
						}(e) || function(e, t) {
							if (!e) return;
							if ("string" == typeof e) return Me(e, t);
							var a = Object.prototype.toString.call(e).slice(8, -1);
							"Object" === a && e.constructor && (a = e.constructor.name);
							if ("Map" === a || "Set" === a) return Array.from(e);
							if ("Arguments" === a || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)) return Me(e, t)
						}(e) || function() {
							throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function Me(e, t) {
						(null == t || t > e.length) && (t = e.length);
						for (var a = 0, n = new Array(t); a < t; a++) n[a] = e[a];
						return n
					}
					var Ae = function(e, t) {
						e.add("button_group", {
							label: "\n			".concat(Ce, "\n			<div>").concat(t, "</div>\n		"),
							category: "Forms",
							content: {
								type: "button_group"
							}
						})
					};
					const Ee = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("button_group", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Button Group",
									tagName: "div",
									classes: ["btn-group"],
									droppable: ".btn",
									attributes: {
										role: "group"
									},
									traits: [{
										type: "class_select",
										options: [{
											value: "",
											name: "Default"
										}].concat(Ne(Object.keys(xe).map((function(e) {
											return {
												value: "btn-group-" + e,
												name: xe[e]
											}
										})))),
										label: "Size"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "Horizontal"
										}, {
											value: "btn-group-vertical",
											name: "Vertical"
										}],
										label: "Size"
									}, {
										type: "Text",
										label: "ARIA Label",
										name: "aria-label",
										placeholder: "A group of buttons"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("btn-group")) return {
										type: "button_group"
									}
								}
							}),
							view: n
						})
					};
					var Ie = function(e, t) {
						e.add("button_toolbar", {
							label: "\n			".concat(Ce, "\n			<div>").concat(t, "</div>\n		"),
							category: "Forms",
							content: {
								type: "button_toolbar"
							}
						})
					};
					const Be = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("button_toolbar", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Button Toolbar",
									tagName: "div",
									classes: ["btn-toolbar"],
									droppable: ".btn-group",
									attributes: {
										role: "toolbar"
									},
									traits: [{
										type: "Text",
										label: "ARIA Label",
										name: "aria-label",
										placeholder: "A toolbar of button groups"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("btn-toolbar")) return {
										type: "button_toolbar"
									}
								}
							}),
							view: n
						})
					};

					function ze(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function He(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? ze(Object(a), !0).forEach((function(t) {
								Fe(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : ze(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function Fe(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var Ve = function(e, t) {
						e.add("label", {
							label: "\n	  ".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,11.875 C22,11.35 21.5,11 20.75,11 L3.25,11 C2.5,11 2,11.35 2,11.875 L2,17.125 C2,17.65 2.5,18 3.25,18 L20.75,18 C21.5,18 22,17.65 22,17.125 L22,11.875 Z M21,17 L3,17 L3,12 L21,12 L21,17 Z" fill-rule="nonzero"></path>\r\n	<rect class="gjs-block-svg-path" x="2" y="5" width="14" height="5" rx="0.5"></rect>\r\n	<polygon class="gjs-block-svg-path" fill-rule="nonzero" points="4 13 5 13 5 16 4 16"></polygon>\r\n</svg>\r\n', "\n	  <div>").concat(t, "</div>"),
							category: "Forms",
							content: "<label>Label</label>"
						})
					};
					const qe = function(e, t) {
						var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							n = e.getType("text"),
							o = n.model,
							r = n.view;
						e.addType("label", {
							model: o.extend({
								defaults: He(He({}, o.prototype.defaults), {}, {
									"custom-name": a.labels.label,
									tagName: "label",
									traits: [t.for]
								})
							}, {
								isComponent: function(e) {
									if ("LABEL" == e.tagName) return {
										type: "label"
									}
								}
							}),
							view: r
						})
					};
					var Re = function(e, t) {
						e.add("link", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="link" class="svg-inline--fa fa-link fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M326.612 185.391c59.747 59.809 58.927 155.698.36 214.59-.11.12-.24.25-.36.37l-67.2 67.2c-59.27 59.27-155.699 59.262-214.96 0-59.27-59.26-59.27-155.7 0-214.96l37.106-37.106c9.84-9.84 26.786-3.3 27.294 10.606.648 17.722 3.826 35.527 9.69 52.721 1.986 5.822.567 12.262-3.783 16.612l-13.087 13.087c-28.026 28.026-28.905 73.66-1.155 101.96 28.024 28.579 74.086 28.749 102.325.51l67.2-67.19c28.191-28.191 28.073-73.757 0-101.83-3.701-3.694-7.429-6.564-10.341-8.569a16.037 16.037 0 0 1-6.947-12.606c-.396-10.567 3.348-21.456 11.698-29.806l21.054-21.055c5.521-5.521 14.182-6.199 20.584-1.731a152.482 152.482 0 0 1 20.522 17.197zM467.547 44.449c-59.261-59.262-155.69-59.27-214.96 0l-67.2 67.2c-.12.12-.25.25-.36.37-58.566 58.892-59.387 154.781.36 214.59a152.454 152.454 0 0 0 20.521 17.196c6.402 4.468 15.064 3.789 20.584-1.731l21.054-21.055c8.35-8.35 12.094-19.239 11.698-29.806a16.037 16.037 0 0 0-6.947-12.606c-2.912-2.005-6.64-4.875-10.341-8.569-28.073-28.073-28.191-73.639 0-101.83l67.2-67.19c28.239-28.239 74.3-28.069 102.325.51 27.75 28.3 26.872 73.934-1.155 101.96l-13.087 13.087c-4.35 4.35-5.769 10.79-3.783 16.612 5.864 17.194 9.042 34.999 9.69 52.721.509 13.906 17.454 20.446 27.294 10.606l37.106-37.106c59.271-59.259 59.271-155.699.001-214.959z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Basic",
							content: {
								type: "link",
								content: "Link text"
							}
						})
					};
					const Ze = function(e) {
						var t = e.DomComponents,
							a = t.getType("text").model,
							n = t.getType("link").view;
						t.addType("link", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Link",
									tagName: "a",
									droppable: !0,
									editable: !0,
									traits: [{
										type: "text",
										label: "Href",
										name: "href",
										placeholder: "https://www.grapesjs.com"
									}, {
										type: "select",
										options: [{
											value: "",
											name: "This window"
										}, {
											value: "_blank",
											name: "New window"
										}],
										label: "Target",
										name: "target"
									}, {
										type: "select",
										options: [{
											value: "",
											name: "None"
										}, {
											value: "button",
											name: "Self"
										}, {
											value: "collapse",
											name: "Collapse"
										}, {
											value: "dropdown",
											name: "Dropdown"
										}],
										label: "Toggles",
										name: "data-toggle",
										changeProp: 1
									}].concat(a.prototype.defaults.traits)
								}),
								init2: function() {
									this.listenTo(this, "change:data-toggle", this.setupToggle), this.listenTo(this, "change:attributes", this.setupToggle)
								},
								setupToggle: function(e, t) {
									var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {};
									if (!0 !== a.ignore || !0 === a.force) {
										console.log("setup toggle");
										var n = this.getAttributes(),
											o = n.href;
										if (delete n["data-toggle"], delete n["aria-expanded"], delete n["aria-controls"], delete n["aria-haspopup"], o && o.length > 0 && o.match(/^#/)) {
											console.log("link has href");
											var r = this.em.get("Editor").DomComponents.getWrapper().find(o);
											if (r.length > 0) {
												console.log("referenced el found");
												var i = r[0],
													l = i.getAttributes(),
													s = l.class;
												if (s) {
													console.log("el has classes");
													var c = s.split(" "),
														d = ["collapse", "dropdown-menu"],
														p = c.filter((function(e) {
															return d.includes(e)
														}));
													if (p.length) {
														switch (console.log("link data-toggle matches el class"), p[0]) {
															case "collapse":
																n["data-toggle"] = "collapse"
														}
														n["aria-expanded"] = c.includes("show"), "collapse" === p[0] && (n["aria-controls"] = o.substring(1))
													}
												}
											}
										}
										this.set("attributes", n, {
											ignore: !0
										})
									}
								},
								classesChanged: function(e) {
									console.log("classes changed"), "link" === this.attributes.type && this.attributes.classes.filter((function(e) {
										return "btn" === e.id
									})).length > 0 && this.changeType("button")
								}
							}, {
								isComponent: function(e) {
									if (e && e.tagName && "A" === e.tagName) return {
										type: "link"
									}
								}
							}),
							view: n
						})
					};

					function Ue(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function $e(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? Ue(Object(a), !0).forEach((function(t) {
								Ge(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : Ue(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function Ge(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var We = function(e, t) {
						e.add("file-input", {
							label: "\n			".concat('<svg class="gjs-block-svg" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">\r\n	<path class="gjs-block-svg-path" d="M22,9 C22,8.4 21.5,8 20.75,8 L3.25,8 C2.5,8 2,8.4 2,9 L2,15 C2,15.6 2.5,16 3.25,16 L20.75,16 C21.5,16 22,15.6 22,15 L22,9 Z M21,15 L3,15 L3,9 L21,9 L21,15 Z"></path>\r\n	<polygon class="gjs-block-svg-path" points="4 10 5 10 5 14 4 14"></polygon>\r\n</svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Forms",
							content: '<input type="file" name="file" class="form-control-file" id="exampleFormControlFile1">'
						})
					};
					const Xe = function(e, t) {
						var a = arguments.length > 2 && void 0 !== arguments[2] ? arguments[2] : {},
							n = e.getType("default"),
							o = n.model,
							r = n.view,
							i = "file-input";
						e.addType(i, {
							model: o.extend({
								defaults: $e($e({}, o.prototype.defaults), {}, {
									"custom-name": a.labels.input,
									tagName: "input",
									draggable: "form .form-group",
									droppable: !1,
									traits: [t.name, t.required, {
										type: "checkbox",
										label: a.labels.trait_multiple,
										name: "multiple"
									}]
								})
							}, {
								isComponent: function(e) {
									if ("INPUT" === e.tagName && O(e, "form-control-file")) return {
										type: i
									}
								}
							}),
							view: r
						})
					};
					var Je = function(e, t) {
						e.add("bs-image", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="image" class="svg-inline--fa fa-image fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 448H48c-26.51 0-48-21.49-48-48V112c0-26.51 21.49-48 48-48h416c26.51 0 48 21.49 48 48v288c0 26.51-21.49 48-48 48zM112 120c-30.928 0-56 25.072-56 56s25.072 56 56 56 56-25.072 56-56-25.072-56-56-56zM64 384h384V272l-87.515-87.515c-4.686-4.686-12.284-4.686-16.971 0L208 320l-55.515-55.515c-4.686-4.686-12.284-4.686-16.971 0L64 336v48z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Media",
							content: {
								type: "bs-image"
							}
						})
					};
					const Ye = function(e) {
						var t = e.getType("image"),
							a = t.model,
							n = t.view,
							o = "bs-image";
						e.addType(o, {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Image",
									tagName: "img",
									resizable: 1,
									attributes: {
										src: "https://dummyimage.com/800x500/999/222"
									},
									classes: ["img-fluid"],
									traits: [{
										type: "text",
										label: "Source (URL)",
										name: "src"
									}, {
										type: "text",
										label: "Alternate text",
										name: "alt"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && "IMG" === e.tagName) return {
										type: o
									}
								}
							}),
							view: n
						})
					};
					var Ke = function(e, t) {
						e.add("bs-video", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fab" data-icon="youtube" class="svg-inline--fa fa-youtube fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M549.655 124.083c-6.281-23.65-24.787-42.276-48.284-48.597C458.781 64 288 64 288 64S117.22 64 74.629 75.486c-23.497 6.322-42.003 24.947-48.284 48.597-11.412 42.867-11.412 132.305-11.412 132.305s0 89.438 11.412 132.305c6.281 23.65 24.787 41.5 48.284 47.821C117.22 448 288 448 288 448s170.78 0 213.371-11.486c23.497-6.321 42.003-24.171 48.284-47.821 11.412-42.867 11.412-132.305 11.412-132.305s0-89.438-11.412-132.305zm-317.51 213.508V175.185l142.739 81.205-142.739 81.201z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Media",
							content: {
								type: "bs-video"
							}
						})
					};
					const Qe = function(e) {
							var t = e.getType("video"),
								a = t.model,
								n = t.view,
								o = "bs-embed-responsive";
							e.addType(o, {
								model: a.extend({
									defaults: Object.assign({}, a.prototype.defaults, {
										"custom-name": "Video",
										resizable: !1,
										droppable: !1,
										draggable: !1,
										copyable: !1,
										provider: "so",
										classes: ["embed-responsive-item"]
									})
								}, {
									isComponent: function(e) {
										if (e && "embed-responsive-item" === e.className) {
											var t = {
													provider: "so",
													type: o
												},
												a = /youtube\.com\/embed/.test(e.src),
												n = /youtube-nocookie\.com\/embed/.test(e.src),
												r = /player\.vimeo\.com\/video/.test(e.src),
												i = a || n || r;
											return ("VIDEO" == e.tagName || "IFRAME" == e.tagName && i) && (e.src && (t.src = e.src), i && (a ? t.provider = "yt" : n ? t.provider = "ytnc" : r && (t.provider = "vi"))), t
										}
									}
								}),
								view: n
							})
						},
						et = function(e) {
							var t = e.getType("default"),
								a = t.model,
								n = t.view,
								o = "bs-video";
							e.addType(o, {
								model: a.extend({
									defaults: Object.assign({}, a.prototype.defaults, {
										"custom-name": "Embed",
										tagName: "div",
										resizable: !1,
										droppable: !1,
										classes: ["embed-responsive", "embed-responsive-16by9"],
										traits: [{
											type: "class_select",
											options: [{
												value: "embed-responsive-21by9",
												name: "21:9"
											}, {
												value: "embed-responsive-16by9",
												name: "16:9"
											}, {
												value: "embed-responsive-4by3",
												name: "4:3"
											}, {
												value: "embed-responsive-1by1",
												name: "1:1"
											}],
											label: "Aspect Ratio"
										}].concat(a.prototype.defaults.traits)
									})
								}, {
									isComponent: function(e) {
										if (e && "embed-responsive" === e.className) return {
											type: o
										}
									}
								}),
								view: n.extend({
									init: function() {
										var e = ["Aspect Ratio"].map((function(e) {
											return "change:".concat(e)
										})).join(" ");
										this.listenTo(this.model, e, this.render);
										var t = this.model.components();
										t.length || t.add('<iframe class="embed-responsive-item" src="'.concat("https://download.blender.org/peach/bigbuckbunny_movies/BigBuckBunny_320x180.mp4", '"></iframe>'))
									}
								})
							})
						};
					var tt = function(e, t) {
						e.add("paragraph", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="paragraph" class="svg-inline--fa fa-paragraph fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M448 48v32a16 16 0 0 1-16 16h-48v368a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V96h-32v368a16 16 0 0 1-16 16h-32a16 16 0 0 1-16-16V352h-32a160 160 0 0 1 0-320h240a16 16 0 0 1 16 16z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Typography",
							content: {
								type: "paragraph",
								content: "Vivamus sagittis lacus vel augue laoreet rutrum faucibus dolor auctor. Duis mollis, est non commodo luctus."
							}
						})
					};
					const at = function(e) {
						var t = e.getType("text"),
							a = t.model,
							n = t.view;
						e.addType("paragraph", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Paragraph",
									tagName: "p",
									traits: [{
										type: "class_select",
										options: [{
											value: "",
											name: "No"
										}, {
											value: "lead",
											name: "Yes"
										}],
										label: "Lead?"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.tagName && "P" === e.tagName) return {
										type: "paragraph"
									}
								}
							}),
							view: n
						})
					};
					var nt = function(e, t) {
						e.add("header", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="heading" class="svg-inline--fa fa-heading fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M448 96v320h32a16 16 0 0 1 16 16v32a16 16 0 0 1-16 16H320a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h32V288H160v128h32a16 16 0 0 1 16 16v32a16 16 0 0 1-16 16H32a16 16 0 0 1-16-16v-32a16 16 0 0 1 16-16h32V96H32a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h160a16 16 0 0 1 16 16v32a16 16 0 0 1-16 16h-32v128h192V96h-32a16 16 0 0 1-16-16V48a16 16 0 0 1 16-16h160a16 16 0 0 1 16 16v32a16 16 0 0 1-16 16z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Typography",
							content: {
								type: "header",
								content: "Bootstrap heading"
							}
						})
					};
					const ot = function(e) {
							var t = e.getType("text"),
								a = t.model,
								n = t.view;
							e.addType("header", {
								model: a.extend({
									defaults: Object.assign({}, a.prototype.defaults, {
										"custom-name": "Header",
										tagName: "h1",
										traits: [{
											type: "select",
											options: [{
												value: "h1",
												name: "One (largest)"
											}, {
												value: "h2",
												name: "Two"
											}, {
												value: "h3",
												name: "Three"
											}, {
												value: "h4",
												name: "Four"
											}, {
												value: "h5",
												name: "Five"
											}, {
												value: "h6",
												name: "Six (smallest)"
											}],
											label: "Size",
											name: "tagName",
											changeProp: 1
										}, {
											type: "class_select",
											options: [{
												value: "",
												name: "None"
											}, {
												value: "display-1",
												name: "One (largest)"
											}, {
												value: "display-2",
												name: "Two "
											}, {
												value: "display-3",
												name: "Three "
											}, {
												value: "display-4",
												name: "Four (smallest)"
											}],
											label: "Display Heading"
										}].concat(a.prototype.defaults.traits)
									})
								}, {
									isComponent: function(e) {
										if (e && ["H1", "H2", "H3", "H4", "H5", "H6"].includes(e.tagName)) return {
											type: "header"
										}
									}
								}),
								view: n
							})
						},
						rt = '<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="credit-card" class="svg-inline--fa fa-credit-card fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M0 432c0 26.5 21.5 48 48 48h480c26.5 0 48-21.5 48-48V256H0v176zm192-68c0-6.6 5.4-12 12-12h136c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H204c-6.6 0-12-5.4-12-12v-40zm-128 0c0-6.6 5.4-12 12-12h72c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12H76c-6.6 0-12-5.4-12-12v-40zM576 80v48H0V80c0-26.5 21.5-48 48-48h480c26.5 0 48 21.5 48 48z"></path></svg>\r\n';
					var it = function(e, t) {
						e.add("card", {
							label: "\n			".concat(rt, "\n			<div>").concat(t.labels.card, "</div>\n		"),
							category: "Components",
							content: {
								type: "card"
							}
						}), e.add("card_container", {
							label: "\n			".concat(rt, "\n			<div>").concat(t.labels.card_container, "</div>\n		"),
							category: "Components",
							content: {
								type: "card_container"
							}
						})
					};
					const lt = function(e, t) {
						var a = t.DomComponents.getType("default"),
							n = a.model,
							o = a.view,
							r = e.getType("image"),
							i = r.model,
							l = r.view;
						e.addType("card", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Card",
									classes: ["card"],
									traits: [{
										type: "checkbox",
										label: "Image Top",
										name: "card-img-top",
										changeProp: 1
									}, {
										type: "checkbox",
										label: "Header",
										name: "card-header",
										changeProp: 1
									}, {
										type: "checkbox",
										label: "Image",
										name: "card-img",
										changeProp: 1
									}, {
										type: "checkbox",
										label: "Image Overlay",
										name: "card-img-overlay",
										changeProp: 1
									}, {
										type: "checkbox",
										label: "Body",
										name: "card-body",
										changeProp: 1
									}, {
										type: "checkbox",
										label: "Footer",
										name: "card-footer",
										changeProp: 1
									}, {
										type: "checkbox",
										label: "Image Bottom",
										name: "card-img-bottom",
										changeProp: 1
									}].concat(n.prototype.defaults.traits)
								}),
								init2: function() {
									this.listenTo(this, "change:card-img-top", this.cardImageTop), this.listenTo(this, "change:card-header", this.cardHeader), this.listenTo(this, "change:card-img", this.cardImage), this.listenTo(this, "change:card-img-overlay", this.cardImageOverlay), this.listenTo(this, "change:card-body", this.cardBody), this.listenTo(this, "change:card-footer", this.cardFooter), this.listenTo(this, "change:card-img-bottom", this.cardImageBottom), this.components().comparator = "card-order", this.set("card-img-top", !0), this.set("card-body", !0)
								},
								cardImageTop: function() {
									this.createCardComponent("card-img-top")
								},
								cardHeader: function() {
									this.createCardComponent("card-header")
								},
								cardImage: function() {
									this.createCardComponent("card-img")
								},
								cardImageOverlay: function() {
									this.createCardComponent("card-img-overlay")
								},
								cardBody: function() {
									this.createCardComponent("card-body")
								},
								cardFooter: function() {
									this.createCardComponent("card-footer")
								},
								cardImageBottom: function() {
									this.createCardComponent("card-img-bottom")
								},
								createCardComponent: function(e) {
									var t = this.get(e),
										a = e.replace(/-/g, "_").replace(/img/g, "image"),
										n = this.components(),
										o = n.filter((function(e) {
											return e.attributes.type === a
										}))[0];
									if (t && !o) {
										var r = n.add({
											type: a
										}).components();
										"card-header" === e && r.add({
											type: "header",
											tagName: "h4",
											style: {
												"margin-bottom": "0px"
											},
											content: "Card Header"
										}), "card-img-overlay" === e && (r.add({
											type: "header",
											tagName: "h4",
											classes: ["card-title"],
											content: "Card title"
										}), r.add({
											type: "text",
											tagName: "p",
											classes: ["card-text"],
											content: "Some quick example text to build on the card title and make up the bulk of the card's content."
										})), "card-body" === e && (r.add({
											type: "header",
											tagName: "h4",
											classes: ["card-title"],
											content: "Card title"
										}), r.add({
											type: "header",
											tagName: "h6",
											classes: ["card-subtitle", "text-muted", "mb-2"],
											content: "Card subtitle"
										}), r.add({
											type: "text",
											tagName: "p",
											classes: ["card-text"],
											content: "Some quick example text to build on the card title and make up the bulk of the card's content."
										}), r.add({
											type: "link",
											classes: ["card-link"],
											href: "#",
											content: "Card link"
										}), r.add({
											type: "link",
											classes: ["card-link"],
											href: "#",
											content: "Another link"
										})), this.order()
									} else t || o.destroy()
								},
								order: function() {}
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card")) return {
										type: "card"
									}
								}
							}),
							view: o
						}), e.addType("card_image_top", {
							model: i.extend({
								defaults: Object.assign({}, i.prototype.defaults, {
									"custom-name": "Card Image Top",
									classes: ["card-img-top"],
									"card-order": 1
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-img-top")) return {
										type: "card_image_top"
									}
								}
							}),
							view: l
						}), e.addType("card_header", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Card Header",
									classes: ["card-header"],
									"card-order": 2
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-header")) return {
										type: "card_header"
									}
								}
							}),
							view: o
						}), e.addType("card_image", {
							model: i.extend({
								defaults: Object.assign({}, i.prototype.defaults, {
									"custom-name": "Card Image",
									classes: ["card-img"],
									"card-order": 3
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-img")) return {
										type: "card_image"
									}
								}
							}),
							view: l
						}), e.addType("card_image_overlay", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Card Image Overlay",
									classes: ["card-img-overlay"],
									"card-order": 4
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-img-overlay")) return {
										type: "card_image_overlay"
									}
								}
							}),
							view: o
						}), e.addType("card_body", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Card Body",
									classes: ["card-body"],
									"card-order": 5
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-body")) return {
										type: "card_body"
									}
								}
							}),
							view: o
						}), e.addType("card_footer", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Card Footer",
									classes: ["card-footer"],
									"card-order": 6
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-footer")) return {
										type: "card_footer"
									}
								}
							}),
							view: o
						}), e.addType("card_image_bottom", {
							model: i.extend({
								defaults: Object.assign({}, i.prototype.defaults, {
									"custom-name": "Card Image Bottom",
									classes: ["card-img-bottom"],
									"card-order": 7
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("card-img-bottom")) return {
										type: "card_image_bottom"
									}
								}
							}),
							view: l
						}), e.addType("card_container", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Card Container",
									classes: ["card-group"],
									droppable: ".card",
									traits: [{
										type: "class_select",
										options: [{
											value: "card-group",
											name: "Group"
										}, {
											value: "card-deck",
											name: "Deck"
										}, {
											value: "card-columns",
											name: "Columns"
										}],
										label: "Layout"
									}].concat(n.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									var t = Array.from(e.classList || []),
										a = ["card-group", "card-deck", "card-columns"],
										n = t.filter((function(e) {
											return a.includes(e)
										}));
									if (e && e.classList && n.length) return {
										type: "card_container"
									}
								}
							}),
							view: o
						})
					};

					function st(e) {
						return function(e) {
							if (Array.isArray(e)) return ct(e)
						}(e) || function(e) {
							if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e)
						}(e) || function(e, t) {
							if (!e) return;
							if ("string" == typeof e) return ct(e, t);
							var a = Object.prototype.toString.call(e).slice(8, -1);
							"Object" === a && e.constructor && (a = e.constructor.name);
							if ("Map" === a || "Set" === a) return Array.from(e);
							if ("Arguments" === a || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)) return ct(e, t)
						}(e) || function() {
							throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function ct(e, t) {
						(null == t || t > e.length) && (t = e.length);
						for (var a = 0, n = new Array(t); a < t; a++) n[a] = e[a];
						return n
					}
					var dt = function(e, t) {
						e.add("badge", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="certificate" class="svg-inline--fa fa-certificate fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M458.622 255.92l45.985-45.005c13.708-12.977 7.316-36.039-10.664-40.339l-62.65-15.99 17.661-62.015c4.991-17.838-11.829-34.663-29.661-29.671l-61.994 17.667-15.984-62.671C337.085.197 313.765-6.276 300.99 7.228L256 53.57 211.011 7.229c-12.63-13.351-36.047-7.234-40.325 10.668l-15.984 62.671-61.995-17.667C74.87 57.907 58.056 74.738 63.046 92.572l17.661 62.015-62.65 15.99C.069 174.878-6.31 197.944 7.392 210.915l45.985 45.005-45.985 45.004c-13.708 12.977-7.316 36.039 10.664 40.339l62.65 15.99-17.661 62.015c-4.991 17.838 11.829 34.663 29.661 29.671l61.994-17.667 15.984 62.671c4.439 18.575 27.696 24.018 40.325 10.668L256 458.61l44.989 46.001c12.5 13.488 35.987 7.486 40.325-10.668l15.984-62.671 61.994 17.667c17.836 4.994 34.651-11.837 29.661-29.671l-17.661-62.015 62.65-15.99c17.987-4.302 24.366-27.367 10.664-40.339l-45.984-45.004z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Components",
							content: {
								type: "badge",
								content: "New!"
							}
						})
					};
					const pt = function(e) {
						var t = e.getType("text"),
							a = t.model,
							n = t.view;
						e.addType("badge", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Badge",
									tagName: "span",
									classes: ["badge"],
									traits: [{
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(st(je.map((function(e) {
											return {
												value: "badge-" + e,
												name: j(e)
											}
										})))),
										label: "Context"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "Default"
										}, {
											value: "badge-pill",
											name: "Pill"
										}],
										label: "Shape"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("badge")) return {
										type: "badge"
									}
								}
							}),
							view: n
						})
					};

					function ut(e) {
						return function(e) {
							if (Array.isArray(e)) return mt(e)
						}(e) || function(e) {
							if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e)
						}(e) || function(e, t) {
							if (!e) return;
							if ("string" == typeof e) return mt(e, t);
							var a = Object.prototype.toString.call(e).slice(8, -1);
							"Object" === a && e.constructor && (a = e.constructor.name);
							if ("Map" === a || "Set" === a) return Array.from(e);
							if ("Arguments" === a || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)) return mt(e, t)
						}(e) || function() {
							throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function mt(e, t) {
						(null == t || t > e.length) && (t = e.length);
						for (var a = 0, n = new Array(t); a < t; a++) n[a] = e[a];
						return n
					}
					var ft = function(e, t) {
						e.add("alert", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="exclamation-triangle" class="svg-inline--fa fa-exclamation-triangle fa-w-18" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 576 512"><path fill="currentColor" d="M569.517 440.013C587.975 472.007 564.806 512 527.94 512H48.054c-36.937 0-59.999-40.055-41.577-71.987L246.423 23.985c18.467-32.009 64.72-31.951 83.154 0l239.94 416.028zM288 354c-25.405 0-46 20.595-46 46s20.595 46 46 46 46-20.595 46-46-20.595-46-46-46zm-43.673-165.346l7.418 136c.347 6.364 5.609 11.346 11.982 11.346h48.546c6.373 0 11.635-4.982 11.982-11.346l7.418-136c.375-6.874-5.098-12.654-11.982-12.654h-63.383c-6.884 0-12.356 5.78-11.981 12.654z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Components",
							content: {
								type: "alert",
								content: "This is an alert—check it out!"
							}
						})
					};
					const bt = function(e) {
							var t = e.getType("text"),
								a = t.model,
								n = t.view;
							e.addType("alert", {
								model: a.extend({
									defaults: Object.assign({}, a.prototype.defaults, {
										"custom-name": "Alert",
										tagName: "div",
										classes: ["alert"],
										traits: [{
											type: "class_select",
											options: [{
												value: "",
												name: "None"
											}].concat(ut(je.map((function(e) {
												return {
													value: "alert-" + e,
													name: j(e)
												}
											})))),
											label: "Context"
										}].concat(a.prototype.defaults.traits)
									})
								}, {
									isComponent: function(e) {
										if (e && e.classList && e.classList.contains("alert")) return {
											type: "alert"
										}
									}
								}),
								view: n
							})
						},
						gt = '<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="columns" class="svg-inline--fa fa-columns fa-w-16" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M464 32H48C21.49 32 0 53.49 0 80v352c0 26.51 21.49 48 48 48h416c26.51 0 48-21.49 48-48V80c0-26.51-21.49-48-48-48zM224 416H64V160h160v256zm224 0H288V160h160v256z"></path></svg>\r\n';
					var vt = function(e, t) {
						e.add("media_object").set({
							label: "\n			".concat(gt, "\n			<div>").concat(t, "</div>\n		"),
							category: "Layout",
							content: '<div class="media">\n				 <img class="mr-3" src="">\n				 <div class="media-body">\n				 <h5>Media heading</h5>\n				 <div>Cras sit amet nibh libero, in gravida nulla. Nulla vel metus scelerisque ante sollicitudin. Cras purus odio, vestibulum in vulputate at, tempus viverra turpis. Fusce condimentum nunc ac nisi vulputate fringilla. Donec lacinia congue felis in faucibus.</div>\n				 </div>\n				 </div>'
						})
					};
					const ht = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("media_object", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Media Object",
									tagName: "div",
									classes: ["media"]
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("media")) return {
										type: "media"
									}
								}
							}),
							view: n
						}), e.addType("media_body", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Media Body",
									tagName: "div",
									classes: ["media-body"]
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("media-body")) return {
										type: "media_body"
									}
								}
							}),
							view: n
						})
					};
					var yt = function(e, t) {
						e.add("column_break").set({
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="equals" class="svg-inline--fa fa-equals fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M416 304H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32zm0-192H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h384c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Layout",
							content: {
								type: "column_break"
							}
						})
					};
					const wt = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("column_break", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Column Break",
									tagName: "div",
									classes: ["w-100"]
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("w-100")) return {
										type: "column_break"
									}
								}
							}),
							view: n
						})
					};

					function Ot(e) {
						return function(e) {
							if (Array.isArray(e)) return Ct(e)
						}(e) || function(e) {
							if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e)
						}(e) || xt(e) || function() {
							throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function jt(e, t) {
						return function(e) {
							if (Array.isArray(e)) return e
						}(e) || function(e, t) {
							if ("undefined" == typeof Symbol || !(Symbol.iterator in Object(e))) return;
							var a = [],
								n = !0,
								o = !1,
								r = void 0;
							try {
								for (var i, l = e[Symbol.iterator](); !(n = (i = l.next()).done) && (a.push(i.value), !t || a.length !== t); n = !0);
							} catch (e) {
								o = !0, r = e
							} finally {
								try {
									n || null == l.return || l.return()
								} finally {
									if (o) throw r
								}
							}
							return a
						}(e, t) || xt(e, t) || function() {
							throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function xt(e, t) {
						if (e) {
							if ("string" == typeof e) return Ct(e, t);
							var a = Object.prototype.toString.call(e).slice(8, -1);
							return "Object" === a && e.constructor && (a = e.constructor.name), "Map" === a || "Set" === a ? Array.from(e) : "Arguments" === a || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a) ? Ct(e, t) : void 0
						}
					}

					function Ct(e, t) {
						(null == t || t > e.length) && (t = e.length);
						for (var a = 0, n = new Array(t); a < t; a++) n[a] = e[a];
						return n
					}
					var kt = function(e, t) {
						e.add("column").set({
							label: "\n			".concat(gt, "\n			<div>").concat(t, "</div>\n		"),
							category: "Layout",
							content: {
								type: "column",
								classes: ["col"]
							}
						})
					};
					const Tt = function(e, t) {
						var a = e.getType("default"),
							n = a.model,
							o = a.view,
							r = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];
						e.addType("column", {
							model: n.extend({
								defaults: Object.assign({}, n.prototype.defaults, {
									"custom-name": "Column",
									draggable: ".row",
									droppable: !0,
									resizable: {
										updateTarget: function(e, a, n) {
											var o = t.getSelected();
											if (o) {
												var r = e.getRootNode().body.offsetWidth,
													i = "";
												r >= 1200 ? i = "xl" : r >= 992 ? i = "lg" : r >= 768 ? i = "md" : r >= 576 && (i = "sm");
												var l = .5 * (e.parentElement.offsetWidth / 12),
													s = a.w > e.offsetWidth + l,
													c = a.w < e.offsetWidth - l;
												if (s || c) {
													new RegExp("^col-" + i + "-\\d{1,2}$");
													i || new RegExp("^col-\\d{1,2}$");
													var d, p = !1,
														u = {},
														m = 0,
														f = null,
														b = function(e, t) {
															var a;
															if ("undefined" == typeof Symbol || null == e[Symbol.iterator]) {
																if (Array.isArray(e) || (a = xt(e)) || t && e && "number" == typeof e.length) {
																	a && (e = a);
																	var n = 0,
																		o = function() {};
																	return {
																		s: o,
																		n: function() {
																			return n >= e.length ? {
																				done: !0
																			} : {
																				done: !1,
																				value: e[n++]
																			}
																		},
																		e: function(e) {
																			throw e
																		},
																		f: o
																	}
																}
																throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
															}
															var r, i = !0,
																l = !1;
															return {
																s: function() {
																	a = e[Symbol.iterator]()
																},
																n: function() {
																	var e = a.next();
																	return i = e.done, e
																},
																e: function(e) {
																	l = !0, r = e
																},
																f: function() {
																	try {
																		i || null == a.return || a.return()
																	} finally {
																		if (l) throw r
																	}
																}
															}
														}(e.classList);
													try {
														for (b.s(); !(d = b.n()).done;) {
															var g = d.value;
															if (0 === g.indexOf("col-")) {
																var v = jt(g.split("-"), 3),
																	h = (v[0], v[1]),
																	y = v[2];
																y || (y = h, h = ""), u[h] = y, h === i && (f = g, m = y, p = !0)
															}
														}
													} catch (e) {
														b.e(e)
													} finally {
														b.f()
													}
													if (!p)
														for (var w = 0, O = ["", "xs", "sm", "md", "lg", "xl"]; w < O.length; w++) {
															var j = O[w];
															if (u[j] && (m = u[j], p = !0), j === i) break
														}
													var x = Number(m);
													s ? x++ : x--, x > 12 && (x = 12), x < 1 && (x = 1);
													var C = "col-" + i + "-" + x;
													i || (C = "col-" + x), o.addClass(C), f && f !== C && o.removeClass(f), o.getTrait((i || "xs") + "_width").view.postUpdate()
												}
											}
										},
										tl: 0,
										tc: 0,
										tr: 0,
										cl: 0,
										cr: 1,
										bl: 0,
										bc: 0,
										br: 0
									},
									traits: [{
										id: "xs_width",
										type: "class_select",
										options: [{
											value: "col",
											name: "Equal"
										}, {
											value: "col-auto",
											name: "Variable"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "col-" + e,
												name: e + "/12"
											}
										})))),
										label: "XS Width"
									}, {
										id: "sm_width",
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}, {
											value: "col-sm",
											name: "Equal"
										}, {
											value: "col-sm-auto",
											name: "Variable"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "col-sm-" + e,
												name: e + "/12"
											}
										})))),
										label: "SM Width"
									}, {
										id: "md_width",
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}, {
											value: "col-md",
											name: "Equal"
										}, {
											value: "col-md-auto",
											name: "Variable"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "col-md-" + e,
												name: e + "/12"
											}
										})))),
										label: "MD Width"
									}, {
										id: "lg_width",
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}, {
											value: "col-lg",
											name: "Equal"
										}, {
											value: "col-lg-auto",
											name: "Variable"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "col-lg-" + e,
												name: e + "/12"
											}
										})))),
										label: "LG Width"
									}, {
										id: "xl_width",
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}, {
											value: "col-xl",
											name: "Equal"
										}, {
											value: "col-xl-auto",
											name: "Variable"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "col-xl-" + e,
												name: e + "/12"
											}
										})))),
										label: "XL Width"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "offset-" + e,
												name: e + "/12"
											}
										})))),
										label: "XS Offset"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "offset-sm-" + e,
												name: e + "/12"
											}
										})))),
										label: "SM Offset"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "offset-md-" + e,
												name: e + "/12"
											}
										})))),
										label: "MD Offset"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "offset-lg-" + e,
												name: e + "/12"
											}
										})))),
										label: "LG Offset"
									}, {
										type: "class_select",
										options: [{
											value: "",
											name: "None"
										}].concat(Ot(r.map((function(e) {
											return {
												value: "offset-xl-" + e,
												name: e + "/12"
											}
										})))),
										label: "XL Offset"
									}].concat(n.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									var t = !1;
									if (e && e.classList && e.classList.forEach((function(e) {
											("col" == e || e.match(/^col-/)) && (t = !0)
										})), t) return {
										type: "column"
									}
								}
							}),
							view: o
						})
					};
					var Pt = function(e, t) {
						e.add("row").set({
							label: "\n			".concat(x, "\n			<div>").concat(t, "</div>\n		"),
							category: "Layout",
							content: {
								type: "row",
								classes: ["row"]
							}
						})
					};
					const _t = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("row", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Row",
									tagName: "div",
									draggable: ".container, .container-fluid",
									droppable: !0,
									traits: [{
										type: "class_select",
										options: [{
											value: "",
											name: "Yes"
										}, {
											value: "no-gutters",
											name: "No"
										}],
										label: "Gutters?"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && e.classList.contains("row")) return {
										type: "row"
									}
								}
							}),
							view: n
						})
					};
					var Lt = function(e, t) {
						e.add("container").set({
							label: "\n			".concat(x, "\n			<div>").concat(t, "</div>\n		"),
							category: "Layout",
							content: {
								type: "container",
								classes: ["container"]
							}
						})
					};
					const St = function(e) {
						var t = e.getType("default"),
							a = t.model,
							n = t.view;
						e.addType("container", {
							model: a.extend({
								defaults: Object.assign({}, a.prototype.defaults, {
									"custom-name": "Container",
									tagName: "div",
									droppable: !0,
									traits: [{
										type: "class_select",
										options: [{
											value: "container",
											name: "Fixed"
										}, {
											value: "container-fluid",
											name: "Fluid"
										}],
										label: "Width"
									}].concat(a.prototype.defaults.traits)
								})
							}, {
								isComponent: function(e) {
									if (e && e.classList && (e.classList.contains("container") || e.classList.contains("container-fluid"))) return {
										type: "container"
									}
								}
							}),
							view: n
						})
					};
					var Dt = function(e, t) {
						e.add("text", {
							label: "\n			".concat('<svg aria-hidden="true" width="24" height="50" focusable="false" data-prefix="fas" data-icon="font" class="svg-inline--fa fa-font fa-w-14" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path fill="currentColor" d="M432 416h-23.41L277.88 53.69A32 32 0 0 0 247.58 32h-47.16a32 32 0 0 0-30.3 21.69L39.41 416H16a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16h-19.58l23.3-64h152.56l23.3 64H304a16 16 0 0 0-16 16v32a16 16 0 0 0 16 16h128a16 16 0 0 0 16-16v-32a16 16 0 0 0-16-16zM176.85 272L224 142.51 271.15 272z"></path></svg>\r\n', "\n			<div>").concat(t, "</div>\n		"),
							category: "Typography",
							content: {
								type: "text",
								content: "Insert your text here"
							}
						})
					};
					const Nt = function(e) {
						var t = e.getType("default").model,
							a = e.getType("text").view;
						e.addType("text", {
							model: t.extend({
								defaults: Object.assign({}, t.prototype.defaults, {
									"custom-name": "Text",
									tagName: "div",
									droppable: !0,
									editable: !0
								})
							}, {}),
							view: a
						})
					};

					function Mt(e) {
						return function(e) {
							if (Array.isArray(e)) return At(e)
						}(e) || function(e) {
							if ("undefined" != typeof Symbol && Symbol.iterator in Object(e)) return Array.from(e)
						}(e) || function(e, t) {
							if (!e) return;
							if ("string" == typeof e) return At(e, t);
							var a = Object.prototype.toString.call(e).slice(8, -1);
							"Object" === a && e.constructor && (a = e.constructor.name);
							if ("Map" === a || "Set" === a) return Array.from(e);
							if ("Arguments" === a || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(a)) return At(e, t)
						}(e) || function() {
							throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")
						}()
					}

					function At(e, t) {
						(null == t || t > e.length) && (t = e.length);
						for (var a = 0, n = new Array(t); a < t; a++) n[a] = e[a];
						return n
					}
					const Et = function(e) {
							var t = je.concat(["white"]),
								a = e.getType("default"),
								n = a.model,
								o = a.view;
							e.addType("default", {
								model: n.extend({
									defaults: Object.assign({}, n.prototype.defaults, {
										tagName: "div",
										traits: [{
											type: "class_select",
											options: [{
												value: "",
												name: "Default"
											}].concat(Mt(t.map((function(e) {
												return {
													value: "text-" + e,
													name: j(e)
												}
											})))),
											label: "Text color"
										}, {
											type: "class_select",
											options: [{
												value: "",
												name: "Default"
											}].concat(Mt(t.map((function(e) {
												return {
													value: "bg-" + e,
													name: j(e)
												}
											})))),
											label: "Background color"
										}, {
											type: "class_select",
											options: [{
												value: "",
												name: "Default"
											}, {
												value: "border",
												name: "Full"
											}, {
												value: "border-top-0",
												name: "No top"
											}, {
												value: "border-right-0",
												name: "No right"
											}, {
												value: "border-bottom-0",
												name: "No bottom"
											}, {
												value: "border-left-0",
												name: "No left"
											}, {
												value: "border-0",
												name: "None"
											}],
											label: "Border width"
										}, {
											type: "class_select",
											options: [{
												value: "",
												name: "Default"
											}].concat(Mt(t.map((function(e) {
												return {
													value: "border border-" + e,
													name: j(e)
												}
											})))),
											label: "Border color"
										}, {
											type: "class_select",
											options: [{
												value: "",
												name: "Default"
											}, {
												value: "rounded",
												name: "Rounded"
											}, {
												value: "rounded-top",
												name: "Rounded top"
											}, {
												value: "rounded-right",
												name: "Rounded right"
											}, {
												value: "rounded-bottom",
												name: "Rounded bottom"
											}, {
												value: "rounded-left",
												name: "Rounded left"
											}, {
												value: "rounded-circle",
												name: "Circle"
											}, {
												value: "rounded-0",
												name: "Square"
											}],
											label: "Border radius"
										}, {
											type: "text",
											label: "ID",
											name: "id",
											placeholder: "my_element"
										}, {
											type: "text",
											label: "Title",
											name: "title",
											placeholder: "My Element"
										}]
									}),
									init: function() {
										var e = this.get("classes");
										e.bind("add", this.classesChanged.bind(this)), e.bind("change", this.classesChanged.bind(this)), e.bind("remove", this.classesChanged.bind(this)), this.init2()
									},
									init2: function() {},
									classesChanged: function() {},
									changeType: function(e) {
										var t = this.collection,
											a = t.indexOf(this),
											n = {
												type: e,
												style: this.getStyle(),
												attributes: this.getAttributes(),
												content: this.view.el.innerHTML
											};
										t.remove(this), t.add(n, {
											at: a
										}), this.destroy()
									}
								}),
								view: o
							})
						},
						It = function(e) {
							var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
								a = t,
								n = e.DomComponents,
								o = a.blocks,
								r = e.BlockManager,
								i = a.blockCategories,
								c = {
									id: {
										name: "id",
										label: a.labels.trait_id
									},
									for: {
										name: "for",
										label: a.labels.trait_for
									},
									name: {
										name: "name",
										label: a.labels.trait_name
									},
									placeholder: {
										name: "placeholder",
										label: a.labels.trait_placeholder
									},
									value: {
										name: "value",
										label: a.labels.trait_value
									},
									required: {
										type: "checkbox",
										name: "required",
										label: a.labels.trait_required
									},
									checked: {
										label: a.labels.trait_checked,
										type: "checkbox",
										name: "checked",
										changeProp: 1
									}
								};
							i.media && (o.image && (Je(r, a.labels.image), Ye(n)), o.video && (et(n), Ke(r, a.labels.video), Qe(n))), i.basic && (o.default && Et(n), o.text && (Dt(r, a.labels.text), Nt(n)), o.link && (Re(r, a.labels.link), Ze(e))), i.layout && (o.container && (Lt(r, a.labels.container), St(n)), o.row && (Pt(r, a.labels.row), _t(n)), o.column && (kt(r, a.labels.column), Tt(n, e), yt(r, a.labels.column_break), wt(n)), o.media_object && (vt(r, a.labels.media_object), ht(n))), i.components && (o.alert && (ft(r, a.labels.alert), bt(n)), o.tabs && (P(r, a), _(n, t), I(n, t), N(n, t), F(n, t)), o.badge && (dt(r, a.labels.badge), pt(n)), o.card && (it(r, a), lt(n, e)), o.collapse && (l(r, a.labels.collapse), s(e)), o.dropdown && (u(r, a.labels.dropdown), m(e))), i.typography && (o.header && (nt(r, a.labels.header), ot(n)), o.paragraph && (tt(r, a.labels.paragraph), at(n))), i.forms && (o.form && (Z(r, a.labels.form), U(n, c, t)), o.input && (X(r, a.labels.input), J(n, c, t), We(r, a.labels.file_input), Xe(n, c, t)), o.form_group_input && (ee(r, a.labels.form_group_input), te(n, c, t)), o.textarea && (re(r, a.labels.textarea), ie(n, c, t)), o.select && (de(r, a.labels.select), pe(e, n, c, t)), o.checkbox && (be(r, a.labels.checkbox), ge(n, c, t)), o.radio && (we(r, a.labels.radio), Oe(n, c, t)), o.label && (Ve(r, a.labels.label), qe(n, c, t)), o.button && (Se(r, a.labels.button), De(n)), o.button_group && (Ae(r, a.labels.button_group), Ee(n)), o.button_toolbar && (Ie(r, a.labels.button_toolbar), Be(n)))
						},
						Bt = function(e) {
							var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {},
								a = t,
								n = e.DeviceManager;
							if (a.gridDevices && (n.add("Extra Small", "575px"), n.add("Small", "767px"), n.add("Medium", "991px"), n.add("Large", "1199px"), n.add("Extra Large"), a.gridDevicesPanel)) {
								var o = e.Panels,
									r = e.Commands,
									i = o.addPanel({
										id: "devices-buttons"
									}),
									l = i.get("buttons");
								l.add([{
									id: "deviceXl",
									command: "set-device-xl",
									className: "fa fa-desktop",
									text: "XL",
									attributes: {
										title: "Extra Large"
									},
									active: 1
								}, {
									id: "deviceLg",
									command: "set-device-lg",
									className: "fa fa-desktop",
									attributes: {
										title: "Large"
									}
								}, {
									id: "deviceMd",
									command: "set-device-md",
									className: "fa fa-tablet",
									attributes: {
										title: "Medium"
									}
								}, {
									id: "deviceSm",
									command: "set-device-sm",
									className: "fa fa-mobile",
									attributes: {
										title: "Small"
									}
								}, {
									id: "deviceXs",
									command: "set-device-xs",
									className: "fa fa-mobile",
									attributes: {
										title: "Extra Small"
									}
								}]), r.add("set-device-xs", {
									run: function(e) {
										e.setDevice("Extra Small")
									}
								}), r.add("set-device-sm", {
									run: function(e) {
										e.setDevice("Small")
									}
								}), r.add("set-device-md", {
									run: function(e) {
										e.setDevice("Medium")
									}
								}), r.add("set-device-lg", {
									run: function(e) {
										e.setDevice("Large")
									}
								}), r.add("set-device-xl", {
									run: function(e) {
										e.setDevice("Extra Large")
									}
								})
							}
						};

					function zt(e, t) {
						var a = Object.keys(e);
						if (Object.getOwnPropertySymbols) {
							var n = Object.getOwnPropertySymbols(e);
							t && (n = n.filter((function(t) {
								return Object.getOwnPropertyDescriptor(e, t).enumerable
							}))), a.push.apply(a, n)
						}
						return a
					}

					function Ht(e) {
						for (var t = 1; t < arguments.length; t++) {
							var a = null != arguments[t] ? arguments[t] : {};
							t % 2 ? zt(Object(a), !0).forEach((function(t) {
								Ft(e, t, a[t])
							})) : Object.getOwnPropertyDescriptors ? Object.defineProperties(e, Object.getOwnPropertyDescriptors(a)) : zt(Object(a)).forEach((function(t) {
								Object.defineProperty(e, t, Object.getOwnPropertyDescriptor(a, t))
							}))
						}
						return e
					}

					function Ft(e, t, a) {
						return t in e ? Object.defineProperty(e, t, {
							value: a,
							enumerable: !0,
							configurable: !0,
							writable: !0
						}) : e[t] = a, e
					}
					var Vt = function(e) {
						e.Config.canvasCss += '\n	/* Layout */\n\n	.gjs-dashed .container, .gjs-dashed .container-fluid,\n	.gjs-dashed .row,\n	.gjs-dashed .col, .gjs-dashed [class^="col-"] {\n	  min-height: 1.5rem !important;\n	}\n	.gjs-dashed .w-100 {\n	  min-height: .25rem !important;\n	  background-color: rgba(0,0,0,0.1);\n	}\n	.gjs-dashed img {\n	  min-width: 25px;\n	  min-height: 25px;\n	  background-color: rgba(0,0,0,0.5);\n	}\n\n	/* Components */\n\n	.gjs-dashed .btn-group,\n	.gjs-dashed .btn-toolbar {\n	  padding-right: 1.5rem !important;\n	  min-height: 1.5rem !important;\n	}\n	.gjs-dashed .card,\n	.gjs-dashed .card-group, .gjs-dashed .card-deck, .gjs-dashed .card-columns {\n	  min-height: 1.5rem !important;\n	}\n	.gjs-dashed .collapse {\n	  display: block !important;\n	  min-height: 1.5rem !important;\n	}\n	.gjs-dashed .dropdown {\n	  display: block !important;\n	  min-height: 1.5rem !important;\n	}\n	.gjs-dashed .dropdown-menu {\n	  min-height: 1.5rem !important;\n	  display: block !important;\n	}\n  '
					};
					const qt = o().plugins.add("grapesjs-blocks-bootstrap4", (function(e) {
						var t = arguments.length > 1 && void 0 !== arguments[1] ? arguments[1] : {};
						window.editor = e;
						var a = t.blocks || {},
							n = t.labels || {},
							o = t.blockCategories || {};
						delete t.blocks, delete t.labels, delete t.blockCategories;
						var l = {
								default: !0,
								text: !0,
								link: !0,
								image: !0,
								container: !0,
								row: !0,
								column: !0,
								column_break: !0,
								media_object: !0,
								alert: !0,
								tabs: !0,
								badge: !0,
								button: !0,
								button_group: !0,
								button_toolbar: !0,
								card: !0,
								card_container: !0,
								collapse: !0,
								dropdown: !0,
								video: !0,
								header: !0,
								paragraph: !0,
								list: !0,
								form: !0,
								input: !0,
								form_group_input: !0,
								input_group: !0,
								textarea: !0,
								select: !0,
								label: !0,
								checkbox: !0,
								radio: !0
							},
							s = {
								container: "Container",
								row: "Row",
								column: "Column",
								column_break: "Column Break",
								media_object: "Media Object",
								alert: "Alert",
								tabs: "Tabs",
								tab: "Tab",
								tabPane: "Tab Pane",
								badge: "Badge",
								button: "Button",
								button_group: "Button Group",
								button_toolbar: "Button Toolbar",
								card: "Card",
								card_container: "Card Container",
								collapse: "Collapse",
								dropdown: "Dropdown",
								dropdown_menu: "Dropdown Menu",
								dropdown_item: "Dropdown Item",
								image: "Image",
								video: "Video",
								text: "Text",
								header: "Header",
								paragraph: "Paragraph",
								link: "Link",
								list: "Simple List",
								form: "Form",
								input: "Input",
								file_input: "File",
								form_group_input: "Form Group",
								input_group: "Input group",
								textarea: "Textarea",
								select: "Select",
								select_option: "- Select option -",
								option: "Option",
								label: "Label",
								checkbox: "Checkbox",
								radio: "Radio",
								trait_method: "Method",
								trait_enctype: "Encoding Type",
								trait_multiple: "Multiple",
								trait_action: "Action",
								trait_state: "State",
								trait_id: "ID",
								trait_for: "For",
								trait_name: "Name",
								trait_placeholder: "Placeholder",
								trait_value: "Value",
								trait_required: "Required",
								trait_type: "Type",
								trait_options: "Options",
								trait_checked: "Checked",
								type_text: "Text",
								type_email: "Email",
								type_password: "Password",
								type_number: "Number",
								type_date: "Date",
								type_hidden: "Hidden",
								type_submit: "Submit",
								type_reset: "Reset",
								type_button: "Button"
							},
							c = {
								layout: !0,
								media: !0,
								components: !0,
								typography: !0,
								basic: !0,
								forms: !0
							},
							d = Ht(Ht({}, {
								blocks: Object.assign(l, a),
								labels: Object.assign(s, n),
								blockCategories: Object.assign(c, o),
								optionsStringSeparator: "::",
								gridDevices: !0,
								gridDevicesPanel: !1,
								classNavigation: "nav",
								classTabPanes: "tab-content",
								classTabPane: "tab-pane",
								classTab: "nav-item"
							}), t);
						r(e, d), i(e, d), It(e, d), Bt(e, d), Vt(e)
					}))
				},
				520: t => {
					t.exports = e
				}
			},
			a = {};

		function n(e) {
			if (a[e]) return a[e].exports;
			var o = a[e] = {
				exports: {}
			};
			return t[e](o, o.exports, n), o.exports
		}
		return n.n = e => {
			var t = e && e.__esModule ? () => e.default : () => e;
			return n.d(t, {
				a: t
			}), t
		}, n.d = (e, t) => {
			for (var a in t) n.o(t, a) && !n.o(e, a) && Object.defineProperty(e, a, {
				enumerable: !0,
				get: t[a]
			})
		}, n.o = (e, t) => Object.prototype.hasOwnProperty.call(e, t), n.r = e => {
			"undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(e, Symbol.toStringTag, {
				value: "Module"
			}), Object.defineProperty(e, "__esModule", {
				value: !0
			})
		}, n(187)
	})()
}));
