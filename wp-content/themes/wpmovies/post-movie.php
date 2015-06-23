<?php
/*
Template Name: Post Movies
*/
get_header(); 
# implementado solo para WP-MOVIES.
include_once 'sidebar_left.php'; ?>
<div class="items">
<?php include_once 'includes/header.php'; ?>
<div id="directorio">
<?php include_once 'includes/aviso.php'; ?>
<div class="it_header">
<h1><?php if($tex = get_option('text-15')) { echo $tex; } else { _e('Add new entry','mundothemes'); } ?></h1>
<span class="desc">
<p><?php if($tex = get_option('text-16')) { echo $tex; } else {  _e('Add all required fields.','mundothemes'); } ?></p>
</span>
</div>
<?php 
if($_GET['mt']) { 
require_once "includes/funciones/recaptchalib.php";
     $siteKey = get_option('public_key_rcth');
     $secret = get_option('private_key_rcth');
     $lang = "es";
     $resp = null;
     $error = null;
     $reCaptcha = new ReCaptcha($secret);
if ($_POST["g-recaptcha-response"]) {
    $resp = $reCaptcha->verifyResponse(
        $_SERVER["REMOTE_ADDR"],
        $_POST["g-recaptcha-response"]
    );
}
if ($resp != null && $resp->success) { 
include_once 'includes/funciones/posting.php';  ?>
<div class="enviado">
<span class="icon-check-circle"></span> <?php if($tex = get_option('text-17')) { echo $tex; } else { _e('Excellent, the data has been sent.','mundothemes'); } ?>
</div>
<?php } else { ?>
<div class="error">
<span class="icon-error"></span> <?php if($tex = get_option('text-18')) { echo $tex; } else { _e('Your publication could not be processed', 'mundothemes'); } ?>, <a href="javascript:history.back()"><?php if($tex = get_option('text-19')) { echo $tex; } else { _e('Try again', 'mundothemes'); } ?></a>
</div>
<?php } } else { agregar_pelicula(); } ?>
<?php drss_plus(); ?>
</div>
</div>
<?php include_once 'sidebar_right.php'; ?>
<?php get_footer(); ?>