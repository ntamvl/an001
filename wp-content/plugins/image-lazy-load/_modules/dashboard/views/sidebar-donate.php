<?php
/**
* Settings screen sidebar for free plugins that don't have a Pro version, so we allow
* the user to donate and/or opt into the WP Cube mailing list
*/
?>
<!-- Donate -->
<div class="postbox">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class="hndle"><span><?php _e('About', $this->plugin->name); ?></span></h3>
    
    <div class="option">
    	<p class="description"><?php _e('Found this plugin useful? Donations to help with support and development are appreciated.', $this->plugin->name); ?></p>	
    </div>
    
    <div class="option">
    	<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<p>
				<input type="hidden" name="cmd" value="_s-xclick">
				<input type="hidden" name="hosted_button_id" value="RBAHE4QWKJX3N">
				<input type="hidden" name="on0" value="Donation">
				<select name="os0">
					<option value="$10">$10</option>
					<option value="$15">$15</option>
					<option value="$20">$20</option>
					<option value="$30">$30</option>
					<option value="$50">$50</option>
				</select> 
				<input type="hidden" name="currency_code" value="USD">
				<input type="submit" name="donate" value="<?php _e('Donate Now', $this->plugin->name); ?>" class="button button-primary" />
				<img alt="" border="0" src="https://www.paypalobjects.com/en_GB/i/scr/pixel.gif" width="1" height="1" />
			</p>
		</form>
    </div>
</div>

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