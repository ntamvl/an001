<?php
function nk_action_callback() {
	check_ajax_referer('nk_yantrakaar');

	if(isset($_POST['id']))
	{
		$id=$_POST['id'];
		

		$referrer = $_SERVER['HTTP_REFERER'];
		$check = end(explode('=',$referrer));

		if($check == 'nk-publish' || $check == 'nk-publish-page' )
		{
			$change_type = 'draft';
		}  else if($check == 'nk-draft' || $check == 'nk-draft-page')
		{
			$change_type = 'publish';
		} else {
			$change_type= false;
		}


		if($check === "nk-bulk" || $check === "nk-publish" || $check === "nk-draft" || $check === "nk-publish-page" || $check === "nk-draft-page") // check referrer
		{
			function change_post_status($post_id,$change){
				$current_post = get_post( $post_id, 'ARRAY_A' );
				$current_post['post_status'] = $change;
					
				if($change == false)
				{
			 	echo "Invalid Entry";
			 	die();
				}
				wp_update_post($current_post);
				echo $current_post['post_type']." with title - <span class='nk_title'>".$current_post['post_title']." </span> - has been moved to type - <span class='nk_type'> ".$change."</span><br>";
			}
			if ( current_user_can( 'manage_options' ) ){
				
				foreach ($id as $cid)
				{
				change_post_status($cid,$change_type);
				}
			
			} else {
				return 'nk_not';
				die();
			}

			//header('Location: '.$referrer);
			die();
		} else {
			echo "Error call ";
			//header('Location: '.$referrer);
			die();
		}
	} // end if

	die();
} // end nk_action_callback