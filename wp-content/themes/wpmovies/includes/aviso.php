<?php 
# Modulo de noticiaciones Rapidas.
# Este modulo mostrara notificaciones que podemos hacer desaparecer con el ONCLICK.
# 19/03/2015 - Erick Meza.
$activar = get_option('activar_notice'); if ($activar == "true") { 
$notice_note = get_option('notice');
$notice_cookie = get_option('cookie_name');
$notice_dias = get_option('cookie_exp');
?>
<div id="cookiedata">
<div class="notes"<?php if($color = get_option('color_notice')) { ?> style="background: #<?php echo $color; ?>;"<?php } ?>>
<p><?php echo stripslashes($notice_note); ?> <a href="javascript:void(0);" class="cerrar" onclick="PonerCookie();"><b class="icon-close2"></b></a></p>
</div>
</div>
<script>
function getCookie(mundothemes){
var c_value = document.cookie;
var c_start = c_value.indexOf(" " + mundothemes + "=");
if (c_start == -1){
c_start = c_value.indexOf(mundothemes + "=");
}
if (c_start == -1){
c_value = null;
}else{
c_start = c_value.indexOf("=", c_start) + 1;
var c_end = c_value.indexOf(";", c_start);
if (c_end == -1){
c_end = c_value.length;
}
c_value = unescape(c_value.substring(c_start,c_end));
}
return c_value;
}
function setCookie(mundothemes,value,exdays){
var exdate=new Date();
exdate.setDate(exdate.getDate() + exdays);
var c_value=escape(value) + ((exdays==null) ? "" : "; expires="+exdate.toUTCString());
document.cookie=mundothemes + "=" + c_value;
}
if(getCookie('<?php echo $notice_cookie; ?>')!="1"){
document.getElementById("cookiedata").style.display="block";
}
function PonerCookie(){
setCookie('<?php echo $notice_cookie; ?>','1',<?php echo $notice_dias; ?>);
document.getElementById("cookiedata").style.display="none";
}
</script>
<?php } ?>