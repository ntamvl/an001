<?php
/**
* Settings screen sidebar for pro plugins. Display the support link and plugin information.
* As they've already purchased, we likely have them on our customer mailing list.
*/
?>
<!-- About -->
<div class="postbox">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class="hndle"><span><?php _e('About', $this->plugin->name); ?></span></h3>
    
    <div class="option">
    	<p>
    		<strong><?php _e('Version', $this->plugin->name); ?></strong>
    		<?php echo $this->plugin->version; ?>
    	</p>
    </div>
</div>

<!-- Support -->
<div class="postbox">
    <div class="handlediv" title="Click to toggle"><br /></div>
    <h3 class="hndle"><span><?php _e('Support', $this->plugin->name); ?></span></h3>
    
    <div class="option">
    	<p>
    		
    		<a href="<?php echo (isset($this->plugin->documentationURL) ? $this->plugin->documentationURL : '#'); ?>" target="_blank" class="button"><?php _e('Documentation', $this->plugin->name); ?></a>
    		<a href="admin.php?page=<?php echo $this->plugin->name; ?>-support" class="button button-secondary">
    			<?php _e('Support', $this->plugin->name); ?>
    		</a>
    	</p>
    </div>
</div>