<?php
function my_custom_menu_page(){
	?>
<div class="wrap about-wrap">
<h1><?php _e( 'Welcome to One Click Publish' ); ?></h1>

 <div class="about-text">
 <?php _e('This is my simple attempt to help the wordpress community with I have learnt till now' ); ?>
 </div>

 <h2 class="nav-tab-wrapper">
 <a href="javascript:void(0)" class="nav-tab" style="background:rgb(151, 185, 218);">
 <?php _e( 'Version 2.0' ); ?>
 </a><a href="javascript:void(0)" class="nav-tab nav-tab-active" rel="about_id">
 <?php _e( 'About Plugin' ); ?>
 </a><a href="javascript:void(0)" class="nav-tab " rel="aboutme_id">
 <?php _e( 'About Me' ); ?>
 </a><a href="javascript:void(0)" class="nav-tab" rel="contactme_id">
 <?php _e( 'Contact Me' ); ?>
 </a>
 </h2>



 <div class="feature-section images-stagger-right" id="about_id">
  <div class="changelog">
 <h3><?php _e( 'About OneClickPublish' ); ?></h3>
 <a href="http://www.no-kt.com/" target="_blank"><img src="<?php echo esc_url( plugins_url('/img/logo.png',dirname(__FILE__) )); ?>" class="image-50" /></a>
 <h4><?php _e( 'How To Use ?' ); ?></h4>
 <p><?php _e( 'This plugin is very simple to use . Just click on the "Published Post" tab to get list of all post that are published and to unpublish it just click on "Draft It" button to move that post to draft . '  ); ?></p>
 <p><?php _e( 'To unpublish the post . Just click on the "Drafted Post" menu on the left and you will get the list of all the post that are in drafs . Click on "Publish It" button to publish the post .'  ); ?></p>
 
 <h4 style="color:rgb(155, 35, 35);"><?php _e( 'NOTE : Just Go on clicking on the "Publish It" or  "Draft It" buttons . Post Status will be updated Asynchronously and you will be notified appropriately! ' ); ?></h4>
 
 <h4><?php _e( 'Why did you make so simple Plugin ?' ); ?></h4>
 <p><?php _e( 'I searched internet on publishing my multipe posts that were in draft . I didnt get any good solution and the basic procedure of wordpress is not fast . Hence making this simple plugin was helpful to me when it comes to publish multiple post and I thought of making it available to others so that they can use it .' ); ?></p>
 
 <h4><?php _e( 'How should I add other post types , pages and custom post type in this plugin ?' ); ?></h4>
 <p><?php _e( 'Its very easy to play with the code of this plugin for developers and for non developers you can contact me via email and I will make it work for you.<br> "FOR FREE , OFCOURSE !" '  ); ?></p>
 </div>
 </div>
 
 
 <div class="feature-section images-stagger-right" id="aboutme_id">
  <div class="changelog">
 <h3><?php _e( 'About Me' ); ?></h3>
 <a href="http://www.no-kt.com/" target="_blank"><img src="<?php echo esc_url( plugins_url('/img/logo.png',dirname(__FILE__) )); ?>" class="image-50" /></a>
 <h4><?php _e( 'An IT Engineer' ); ?></h4>
 <p><?php _e( ' I’m just an IT Engineer who has interest in PHP , learning new frameworks and technologies trying to write down every programming experience as I’m learning it hoping that it will help someone out there .'  ); ?></p>
 <p><?php _e( ' You can also check my blog on <a href="http://www.no-kt.com/allposts/make-a-simple-plugin-in-wordpress/" target="_blank">how to make this plugin</a>, a simple one ofcourse. Just click on the logo on the right side to visit site homepage .'  ); ?></p>
 
 </div>
 </div>
 
 
 <div class="feature-section images-stagger-right" id="contactme_id">
  <div class="changelog">
 <h3><?php _e( 'Contact Me' ); ?></h3>
 <a href="http://www.no-kt.com/" target="_blank"><img src="<?php echo esc_url( plugins_url('/img/logo.png',dirname(__FILE__) )); ?>" class="image-50" /></a>
 <h4><?php _e( '<a href="http://www.no-kt.com/" target="_blank">NO-KT.COM</a>' ); ?></h4>
 <p><?php _e( 'Its my personal blog . You can learn a lot there . Do Visit and like <a href="https://www.facebook.com/gotnokt" target="_blank">NO-KT on Facebook</a> .'  ); ?></p>
 <p><?php _e( 'Also you can follow me on twitter <a href="https://twitter.com/intent/user?screen_name=yantrakaar" target="_blank">@yantrakaar</a>  .'  ); ?></p>
 <h4><?php _e( 'OR use the <a href="http://www.no-kt.com/contact-me/" target="_blank">Contact Form</a> on NO-KT.com	' ); ?></h4>
 </div>
 </div>
</div>
<?php 
} // end my_custom_page_menu