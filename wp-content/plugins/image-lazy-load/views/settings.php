<div class="wrap">
    <div id="<?php echo $this->plugin->name; ?>-title" class="icon32"></div> 
    <h2 class="wpcube"><?php echo $this->plugin->displayName; ?> &raquo; <?php _e('Settings'); ?></h2>
           
    <?php    
    if (isset($this->message)) {
        ?>
        <div class="updated fade"><p><?php echo $this->message; ?></p></div>  
        <?php
    }
    if (isset($this->errorMessage)) {
        ?>
        <div class="error fade"><p><?php echo $this->errorMessage; ?></p></div>  
        <?php
    }
    ?> 
    
    <div id="poststuff">
    	<div id="post-body" class="metabox-holder columns-2">
    		<!-- Content -->
    		<div id="post-body-content">
    		
    			<!-- Form Start -->
		        <form id="post" name="post" method="post" action="admin.php?page=<?php echo $this->plugin->name; ?>">
		            <div id="normal-sortables" class="meta-box-sortables ui-sortable">                        
		                <div class="postbox">
		                    <h3 class="hndle"><?php _e('Settings', $this->plugin->name); ?></h3>
		                  
		                    <div class="option">
		                    	<p>
		                    		<strong><?php _e('Load image', $this->plugin->name); ?></strong>
		                    		<select name="<?php echo $this->plugin->name; ?>[load]" size="1">
		                    			<?php
		                    			$i = 0;
		                    			$step = 10;
		                    			while($i <= 100){
		                    				?>
											<option value="<?php echo $i; ?>"<?php echo ((isset($this->settings['load']) AND $this->settings['load'] == $i) ? ' selected' : ''); ?>><?php echo $i; ?></option>
		                    				<?php
		                    				$i = $i+$step;
		                    			}
		                    			?>
		                    		</select>
		                    		<?php _e('pixels before it reaches the enter the viewport', $this->plugin->name); ?>
		                    	</p>
		                    </div>

		                    <div class="option">
                                <p>
                                    <label for="mobile">
                                    	<strong><?php _e('Lazy load on mobile', $this->plugin->name); ?></strong>
                                        <input type="checkbox" name="<?php echo $this->plugin->name; ?>[mobile]" id="mobile" value="1"<?php echo ((isset($this->settings['mobile']) AND $this->settings['mobile'] == 1) ? ' checked' : ''); ?> />
                                    	<p class="description"><?php _e('Lazy loaded images may have an increased delay on mobile devices', $this->plugin->name); ?></p>
                                   	</label>
                                </p>
                            </div>
		                </div>
		                <!-- /postbox -->
		               
		            	<!-- Save -->
		                <div class="submit">
		                    <input type="submit" name="submit" value="<?php _e('Save', $this->plugin->name); ?>" class="button button-primary" /> 
		                </div>
					</div>
					<!-- /normal-sortables -->
			    </form>
			    <!-- /form end -->
    			
    		</div>
    		<!-- /post-body-content -->
    		
    		<!-- Sidebar -->
    		<div id="postbox-container-1" class="postbox-container">
    			<?php require_once($this->plugin->folder.'/_modules/dashboard/views/sidebar-donate.php'); ?>		
    		</div>
    		<!-- /postbox-container -->
    	</div>
	</div>       
</div>