if(typeof(jQuery.fn.wpdkTooltip)==="undefined"){+function(c){var b=function(e,d){this.type=this.options=this.enabled=this.timeout=this.hoverState=this.$element=null;this.init("wpdkTooltip",e,d)};b.DEFAULTS={animation:true,placement:"top",selector:false,template:'<div class="wpdk-tooltip"><div class="tooltip-arrow"></div><div class="tooltip-inner"></div></div>',trigger:"hover focus",title:"",delay:0,html:false,container:false};b.prototype.init=function(k,h,f){this.enabled=true;this.type=k;this.$element=c(h);this.options=this.getOptions(f);var j=this.options.trigger.split(" ");for(var g=j.length;g--;){var e=j[g];if(e=="click"){this.$element.on("click."+this.type,this.options.selector,c.proxy(this.toggle,this))}else{if(e!="manual"){var l=e=="hover"?"mouseenter":"focusin";var d=e=="hover"?"mouseleave":"focusout";this.$element.on(l+"."+this.type,this.options.selector,c.proxy(this.enter,this));this.$element.on(d+"."+this.type,this.options.selector,c.proxy(this.leave,this))}}}this.options.selector?(this._options=c.extend({},this.options,{trigger:"manual",selector:""})):this.fixTitle()};b.prototype.getDefaults=function(){return b.DEFAULTS};b.prototype.getOptions=function(d){d=c.extend({},this.getDefaults(),this.$element.data(),d);if(d.delay&&typeof d.delay=="number"){d.delay={show:d.delay,hide:d.delay}}return d};b.prototype.getDelegateOptions=function(){var d={};var e=this.getDefaults();this._options&&c.each(this._options,function(f,g){if(e[f]!=g){d[f]=g}});return d};b.prototype.enter=function(e){var d=e instanceof this.constructor?e:c(e.currentTarget)[this.type](this.getDelegateOptions()).data("wpdk."+this.type);clearTimeout(d.timeout);d.hoverState="in";if(!d.options.delay||!d.options.delay.show){return d.show()}d.timeout=setTimeout(function(){if(d.hoverState=="in"){d.show()}},d.options.delay.show)};b.prototype.leave=function(e){var d=e instanceof this.constructor?e:c(e.currentTarget)[this.type](this.getDelegateOptions()).data("wpdk."+this.type);clearTimeout(d.timeout);d.hoverState="out";if(!d.options.delay||!d.options.delay.hide){return d.hide()}d.timeout=setTimeout(function(){if(d.hoverState=="out"){d.hide()}},d.options.delay.hide)};b.prototype.show=function(){var p=c.Event("show.wpdk."+this.type);if(this.hasContent()&&this.enabled){this.$element.trigger(p);this.$element.addClass("wpdk-visible");if(p.isDefaultPrevented()){return}var o=this;var k=this.tip();this.setContent();if(this.options.animation){k.addClass("fade")}var j=typeof this.options.placement=="function"?this.options.placement.call(this,k[0],this.$element[0]):this.options.placement;var t=/\s?auto?\s?/i;var u=t.test(j);if(u){j=j.replace(t,"")||"top"}k.detach().css({top:0,left:0,display:"block"}).addClass(j);this.options.container?k.appendTo(this.options.container):k.insertAfter(this.$element);var q=this.getPosition();var d=k[0].offsetWidth;var m=k[0].offsetHeight;if(u){var i=this.$element.parent();var h=j;var r=document.documentElement.scrollTop||document.body.scrollTop;var s=this.options.container=="body"?window.innerWidth:i.outerWidth();var n=this.options.container=="body"?window.innerHeight:i.outerHeight();var l=this.options.container=="body"?0:i.offset().left;j=j=="bottom"&&q.top+q.height+m-r>n?"top":j=="top"&&q.top-r-m<0?"bottom":j=="right"&&q.right+d>s?"left":j=="left"&&q.left-d<l?"right":j;k.removeClass(h).addClass(j)}var g=this.getCalculatedOffset(j,q,d,m);this.applyPlacement(g,j);this.hoverState=null;var f=function(){o.$element.trigger("shown.wpdk."+o.type)};c.support.transition&&this.$tip.hasClass("fade")?k.one(c.support.transition.end,f).emulateTransitionEnd(150):f()}};b.prototype.applyPlacement=function(i,j){var g;var k=this.tip();var f=k[0].offsetWidth;var n=k[0].offsetHeight;var e=parseInt(k.css("margin-top"),10);var h=parseInt(k.css("margin-left"),10);if(isNaN(e)){e=0}if(isNaN(h)){h=0}i.top=i.top+e;i.left=i.left+h;c.offset.setOffset(k[0],c.extend({using:function(o){k.css({top:Math.round(o.top),left:Math.round(o.left)})}},i),0);k.addClass("in");var d=k[0].offsetWidth;var l=k[0].offsetHeight;if(j=="top"&&l!=n){g=true;i.top=i.top+n-l}if(/bottom|top/.test(j)){var m=0;if(i.left<0){m=i.left*-2;i.left=0;k.offset(i);d=k[0].offsetWidth;l=k[0].offsetHeight}this.replaceArrow(m-f+d,d,"left")}else{this.replaceArrow(l-n,l,"top")}if(g){k.offset(i)}};b.prototype.replaceArrow=function(f,e,d){this.arrow().css(d,f?(50*(1-f/e)+"%"):"")};b.prototype.setContent=function(){var e=this.tip();var d=this.getTitle();e.find(".tooltip-inner")[this.options.html?"html":"text"](d);e.removeClass("fade in top bottom left right")};b.prototype.hide=function(){var f=this;var h=this.tip();var g=c.Event("hide.wpdk."+this.type);function d(){if(f.hoverState!="in"){h.detach()}f.$element.trigger("hidden.wpdk."+f.type)}this.$element.trigger(g);this.$element.removeClass("wpdk-visible");if(g.isDefaultPrevented()){return}h.removeClass("in");c.support.transition&&this.$tip.hasClass("fade")?h.one(c.support.transition.end,d).emulateTransitionEnd(150):d();this.hoverState=null;return this};b.prototype.fixTitle=function(){var d=this.$element;if(d.attr("title")||typeof(d.attr("data-original-title"))!="string"){d.attr("data-original-title",d.attr("title")||"").attr("title","")}};b.prototype.hasContent=function(){return this.getTitle()};b.prototype.getPosition=function(){var d=this.$element[0];return c.extend({},(typeof d.getBoundingClientRect=="function")?d.getBoundingClientRect():{width:d.offsetWidth,height:d.offsetHeight},this.$element.offset())};b.prototype.getCalculatedOffset=function(d,g,e,f){return d=="bottom"?{top:g.top+g.height,left:g.left+g.width/2-e/2}:d=="top"?{top:g.top-f,left:g.left+g.width/2-e/2}:d=="left"?{top:g.top+g.height/2-f/2,left:g.left-e}:{top:g.top+g.height/2-f/2,left:g.left+g.width}};b.prototype.getTitle=function(){var f;var d=this.$element;var e=this.options;f=d.attr("data-original-title")||(typeof e.title=="function"?e.title.call(d[0]):e.title);return f};b.prototype.tip=function(){return this.$tip=this.$tip||c(this.options.template)};b.prototype.arrow=function(){return this.$arrow=this.$arrow||this.tip().find(".tooltip-arrow")};b.prototype.validate=function(){if(!this.$element[0].parentNode){this.hide();this.$element=null;this.options=null}};b.prototype.enable=function(){this.enabled=true};b.prototype.disable=function(){this.enabled=false};b.prototype.toggleEnabled=function(){this.enabled=!this.enabled};b.prototype.toggle=function(f){var d=f?c(f.currentTarget)[this.type](this.getDelegateOptions()).data("wpdk."+this.type):this;d.tip().hasClass("in")?d.leave(d):d.enter(d)};b.prototype.destroy=function(){clearTimeout(this.timeout);this.hide().$element.off("."+this.type).removeData("wpdk."+this.type)};var a=c.fn.wpdkTooltip;c.fn.wpdkTooltip=function(d){return this.each(function(){var g=c(this);var f=g.data("wpdk.wpdkTooltip");var e=typeof d=="object"&&d;if(!f&&d=="destroy"){return}if(!f){g.data("wpdk.wpdkTooltip",(f=new b(this,e)))}if(typeof d=="string"){f[d]()}})};c.fn.wpdkTooltip.Constructor=b;c.fn.wpdkTooltip.noConflict=function(){c.fn.wpdkTooltip=a;return this};c(".wpdk-has-tooltip").wpdkTooltip();wpdk_add_action(WPDKUIComponents.REFRESH_TOOLTIP,function(){c(".wpdk-has-tooltip").wpdkTooltip()})}(jQuery)};