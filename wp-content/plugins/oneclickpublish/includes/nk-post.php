<?php
function nk_drafted_post(){

	$args1 = array(
			'post_status'=> 'draft',
			'post_type'=>'post',
			'posts_per_page'=> -1,
	);
	$wp_query1 = new WP_Query($args1);
	echo '<div class="wrap about-wrap"><h1>'.__("List of all the Post that are in Draft","domain").'</h1><br><div >';
	echo '<h2 class="nav-tab-wrapper"><a href="javascript:void(0)" class="nav-tab nav-tab-active">'.__( 'Drafted Post','domain' ).'</a></div><br><br>';
	echo '<div id="nk_result"></div><button class="draftThis" style="float:right;">Publish Selected Post ?</button><table class="widefat dataTable" cellspacing="0">';
	echo '<thead><tr><th>SrNo</th><th>'.__("Title of Post","domain").'</th><th>'.__('View','domain').'</th><th>'.__('Post Status','domain').'</th><th>'.__('Type','domain').'</th></tr></thead>';
	while ( $wp_query1->have_posts() ) {
		$wp_query1->the_post();
		echo '<tr><td>'.get_the_ID().'</td><td>' . __(get_the_title(),'domain') . '</td><td><a href="'.__(esc_attr__(get_permalink()),'domain').'">[view]</a></td><td>'.__(get_post_field('post_status',get_the_ID()),'domain').'</td><td>'.__(get_post_field('post_type',get_the_ID()),'domain').'</td></tr>';

	} // end while

	echo '</table></div>';
	wp_reset_postdata();
} // end nk_drafted_post

function nk_published_post(){
	$args = array('post_status'=> 'publish',
			'post_type'=>'post',
			'posts_per_page'=> -1,
	);

	$wp_query = new WP_Query($args);
	echo '<div class="wrap about-wrap"><h1>'.__("List of all the Post that are Published","domain").'</h1><br><div>';
	echo '<h2 class="nav-tab-wrapper"><a href="javascript:void(0)" class="nav-tab nav-tab-active">'.__( 'Published Post','domain' ).'</a></div><br><br>';
	echo '<div id="nk_result"></div><button class="draftThis" style="float:right;">Draft Selected Post ?</button><table class="widefat dataTable" cellspacing="0">';
	echo '<thead><tr><th>SrNo</th><th>'.__("Title of Post","domain").'</th><th>View</th><th>'.__("Post Status","domain").'</th><th>'.__("Type","domain").'</th></tr></thead>';
	while ( $wp_query->have_posts() ) {
		$wp_query->the_post();

		echo '<tr><td>'.get_the_ID().'</td><td>' .__(get_the_title(),'domain')  . '</td><td><a href="'.get_permalink().'">'.__('[view]','domain').'</a></td><td>'.__(get_post_field('post_status',get_the_ID()),'domain').'</td><td>'.__(get_post_field('post_type',get_the_ID()),'domain').'</td></tr>';

	} // end while
	echo '</table></div>';

	wp_reset_postdata();

} // end nk_published_post