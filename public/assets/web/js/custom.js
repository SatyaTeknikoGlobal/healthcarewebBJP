var site_path=$("#site_path").val();var d=new Date();var n=d.getMilliseconds();$("#search_d").autocomplete({source:site_path+"model/getsearchresult.php?search_by="+$("#search_by").val(),focus:function(event,ui){event.preventDefault();$("#search_d").val(ui.item.label);},select:function(event,ui){event.preventDefault();$("#search_d").val(ui.item.value);}});(function(){"use strict";var medlife={init:function(){var self=this;self.cacheDom();self.bindEvents();self.initBackToTop();self.initCounerElement();},cacheDom:function(){this.toTop=$('.back-to-top');this.fancyBox=$('.fancybox');},bindEvents:function(){var self=this;$(document).on('click','.h-history-policy .burger a',self.toggleHistory);$(document).on('click','.yamm .dropdown-menu',function(e){e.stopPropagation();});$(document).on('hover','#dialog-link, #icons li',function(){$(this).addClass("ui-state-hover");},function(){$(this).removeClass("ui-state-hover");});objectFitImages();$('#dl-menu').dlmenu();if(this.fancyBox&&this.fancyBox.length>0){this.fancyBox.fancybox();}},initCounerElement:function(){var counterElem=$('.counter');if(counterElem&&counterElem.length>0){counterElem.counterUp({delay:10,time:1000});}},addRellaxAnimation:function(){var rellaxElem='.rellax',selector=$(rellaxElem);if(selector&&selector.length>0){if($(window).width()>768){var rellax=new Rellax(rellaxElem,{speed:-2,center:true,round:true});}}},initBackToTop:function(){var self=this;if(self.toTop&&self.toTop.length>0){$(window).scroll(function(){var toTopOffset=self.toTop.offset().top;var toTopHidden=1000;if(toTopOffset>toTopHidden){self.toTop.addClass('display');}else{self.toTop.removeClass('display');}});self.toTop.on('click',function(e){e.preventDefault();$('html, body').animate({scrollTop:0},'slow');});}},toggleHistory:function(){$('.h-history-policy ul').toggleClass('open');},};medlife.init();})();if($('.accordion-box').length){$('.accordion-box .acc-btn').click(function(){if($(this).hasClass('active')!==true){$('.accordion-box .acc-btn').removeClass('active');}
if($(this).next('.acc-content').is(':visible')){$(this).removeClass('active');$(this).next('.acc-content').slideUp(500);}else{$(this).addClass('active');$('.accordion-box .acc-content').slideUp(500);$(this).next('.acc-content').slideDown(500);}});}
function factCounter(){if($('.fact-counter').length){$('.fact-counter .counter-column.animated').each(function(){var $t=$(this),n=$t.find(".count-text").attr("data-stop"),r=parseInt($t.find(".count-text").attr("data-speed"),10);if(!$t.hasClass("counted")){$t.addClass("counted");$({countNum:$t.find(".count-text").text()}).animate({countNum:n},{duration:r,easing:"linear",step:function(){$t.find(".count-text").text(Math.floor(this.countNum));},complete:function(){$t.find(".count-text").text(this.countNum);}});}});}}
jQuery(document).ready(function(){new WOW().init();});$(".appointment-form .form-control").focus(function(){$(this).parent().addClass('focus is-val');});$(".appointment-form .form-control").blur(function(){$(this).parent().removeClass('focus');if(!$(this).val())$(this).parent().removeClass('is-val');});$(document).ready(function(){$("#media-owl").owlCarousel({navigation:true,items:4,margin:20,autoplay:true,loop:true});});$(document).ready(function(){$("#media-gal").owlCarousel({navigation:true,items:4,margin:20,autoplay:true,loop:true});});$(document).ready(function(){$("#media-event").owlCarousel({navigation:true,items:4,margin:20,autoplay:true,loop:true});});$(document).ready(function(){$("#media-health").owlCarousel({navigation:true,items:4,margin:20,autoplay:true,loop:true});});$(document).ready(function(){$("#media-social").owlCarousel({navigation:true,items:2,margin:20,autoplay:false,loop:true});});$(document).ready(function(){$('.mega-menu-title').click(function(){$(this).find('.collapse').toggleClass('in')
$(this).parent().siblings().find('.collapse').removeClass('in');});});function get_hospital(val)
{if(val!=''){var hs_name=$("#hospital").find("option:selected").text();$("#hs_name").html(hs_name);$("#app_text").show();$("#app_text_hs").show();}
else{$("#hs_name").html("");$("#app_text").hide();$("#app_text_hs").hide();}
var hs=$("#hospital").val();var dept=$("#department").val();if(hs!=''||dept!='')
{$("#app_text").show();}
else{$("#app_text").hide();}}
function get_department(val)
{if(val!=''){var dept_name=$("#department").find("option:selected").text();$("#dept_name").html(dept_name);$("#app_text").show();$("#app_text_dept").show();}
else{$("#dept_name").html("");$("#app_text").hide();$("#app_text_dept").hide();}
var hs=$("#hospital").val();var dept=$("#department").val();if(hs!=''||dept!='')
{$("#app_text").show();}
else{$("#app_text").hide();}}