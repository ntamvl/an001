if(typeof(jQuery.fn.wpdkRibbonize)==="undefined"){(function(e,c,a,g){var d="wpdkRibbonize",f={propertyName:"value"};function b(i,j,h){this.element=i;this.content=j;this.options=e.extend({},f,h);this._defaults=f;this._name=d;this.init()}b.prototype={init:function(){if(!e(this.element).next().hasClass("wpdk-ribbonize")){var h="";h+='<div class="wpdk-ribbonize fade right">';h+='<div class="wpdk-ribbonize-arrow"></div>';h+='<div class="wpdk-ribbonize-inner">'+this.content+"</div>";h+="</div>";e(this.element).after(h);if(""!==this.content){e(this.element).next().addClass("in")}}}};e.fn[d]=function(i,h){return this.each(function(){if(!e.data(this,"plugin_"+d)){e.data(this,"plugin_"+d,new b(this,i,h))}else{if(""!==i){e(this).next().find(".wpdk-ribbonize-inner").html(i);e(this).next().addClass("in")}else{if(""==i){e(this).next().removeClass("in")}}}})}})(jQuery,window,document)};