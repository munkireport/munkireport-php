/* =========================================================
 * bootstrap-tabdrop.js 
 * http://www.eyecon.ro/bootstrap-tabdrop
 * =========================================================
 * Copyright 2012 Stefan Petre
 * Copyright 2014 Jose Ant. Aranda
 * 
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * ========================================================= */
 
!function(e){var t=function(){var t=[];var n=false;var r;var i=function(e){clearTimeout(r);r=setTimeout(s,100)};var s=function(){for(var e=0,n=t.length;e<n;e++){t[e].apply()}};return{register:function(r){t.push(r);if(n===false){e(window).bind("resize",i);n=true}},unregister:function(e){for(var n=0,r=t.length;n<r;n++){if(t[n]==e){delete t[n];break}}}}}();var n=function(n,r){this.element=e(n);this.options=r;this.dropdown=e('<li class="dropdown hide pull-right tabdrop"><a class="dropdown-toggle" data-toggle="dropdown" href="#">'+r.text+' <b class="caret"></b></a><ul class="dropdown-menu"></ul></li>').prependTo(this.element);if(this.element.parent().is(".tabs-below")){this.dropdown.addClass("dropup")}t.register(e.proxy(this.layout,this));this.layout()};n.prototype={constructor:n,layout:function(){function i(e){n.find("a.dropdown-toggle").html('<span class="display-tab"> '+e+' </span><b class="caret"></b>')}function s(){n.find("a.dropdown-toggle").html(r.text+' <b class="caret"></b>')}var t=[];var n=this.dropdown;var r=this.options;this.dropdown.removeClass("hide");this.element.append(this.dropdown.find("li")).find(">li").not(".tabdrop").each(function(){if(this.offsetTop>r.offsetTop){t.push(this)}});this.element.find(">li").not(".tabdrop").off("click");this.element.find(">li").not(".tabdrop").on("click",function(){s()});if(t.length>0){t=e(t);this.dropdown.find("ul").empty().append(t);this.dropdown.on("click","li",function(t){var n=e(this).text();i(n)});if(this.dropdown.find(".active").length==1){this.dropdown.addClass("active");i(this.dropdown.find(".active > a").text())}else{this.dropdown.removeClass("active");s()}}else{this.dropdown.addClass("hide")}}};e.fn.tabdrop=function(t){return this.each(function(){var r=e(this),i=r.data("tabdrop"),s=typeof t==="object"&&t;if(!i){r.data("tabdrop",i=new n(this,e.extend({},e.fn.tabdrop.defaults,s)))}if(typeof t=="string"){i[t]()}})};e.fn.tabdrop.defaults={text:'<i class="fa fa-align-justify"></i>',offsetTop:0};e.fn.tabdrop.Constructor=n}(window.jQuery)
