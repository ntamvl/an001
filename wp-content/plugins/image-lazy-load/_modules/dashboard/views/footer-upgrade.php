<?php
if (isset($this->plugin->upgradeReasons) AND is_array($this->plugin->upgradeReasons) AND count($this->plugin->upgradeReasons) > 0) {
	?>
	<div class="postbox">
	    <h3 class="hndle"><?php _e('Upgrade to Pro', $this->plugin->name); ?></h3>
		
		<div class="option">
	    	<ul>
		    	<?php
		    	foreach ($this->plugin->upgradeReasons as $reasonArr) {
		    		?>
		    		<li><strong><?php echo $reasonArr[0]; ?>:</strong> <?php echo $reasonArr[1]; ?></li>
		    		<?php	
		    	}
		    	?>
		    	<li><strong><?php _e('Support'); ?>: </strong><?php _e('Access to one on one email support'); ?></li>
		    	<li><strong><?php _e('Documentation'); ?>: </strong><?php _e('Detailed documentation on how to install and configure the plugin'); ?></li>
		    	<li><strong><?php _e('Updates'); ?>: </strong><?php _e('Receive one click update notifications, right within your WordPress Adminstration panel'); ?></li>
		    	<li><strong><?php _e('Seamless Upgrade'); ?>: </strong><?php _e('Retain all current settings when upgrading to Pro'); ?></li>
		    </ul>
	    </div>
	    
	    <div class="option">
	    	<p>
	    		<a href="<?php echo $this->plugin->upgradeURL; ?>?utm_source=wordpress&utm_medium=link&utm_content=settings&utm_campaign=general" class="button button-primary" target="_blank"><?php _e('Upgrade Now', $this->plugin->name); ?></a>
	    	</p>
	    </div>
	</div>
	<?php
}
?>