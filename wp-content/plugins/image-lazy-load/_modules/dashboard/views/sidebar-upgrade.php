<?php
/**
* Settings screen sidebar for free plugins with a pro version. Display the reasons to upgrade
* and the mailing list.
*/
?>
<!-- Keep Updated -->
<div class="postbox">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class="hndle"><span><?php _e('Keep Updated', $this->plugin->name); ?></span></h3>
    
    <div class="option">
    	<p class="description"><?php _e('Subscribe to the newsletter and receive updates on our WordPress Plugins', $this->plugin->name); ?>.</p>
    </div>
    
    <form action="http://n7studios.createsend.com/t/r/s/jdutdyj/" method="post">
	    <div class="option">                    	
            <p>
		        <strong><?php _e('Email', $this->plugin->name); ?></strong>
		        <input id="fieldEmail" name="cm-jdutdyj-jdutdyj" type="email" required />
		    </p>
	    </div>
	    <div class="option">  
		    <p>
		    	<input type="submit" name="submit" value="<?php _e('Subscribe', $this->plugin->name); ?>" class="button button-primary" />
    		</p>
	    </div>
	</form> 
</div>