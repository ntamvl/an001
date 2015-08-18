<?php
/**
* WP Cube Dashboard Widget
*
* Included in all WP Cube plugins by default, it outputs 
* 
* @package WP Cube
* @subpackage Dashboard
* @author Tim Carr
* @version 1.0
* @copyright WP Cube
*/

if (isset($products) AND is_object($products)) {
	?>
	<div class="rss-widget">
		<img src="<?php echo $this->dashboardURL; ?>images/logo.png" class="alignleft" style="margin: 0 10px 0 0;" />
		<p><?php _e('Thanks for using our plugins - why not check out some of our other amazing Premium WordPress Plugins below?'); ?></p>
		<ul>
		<?php
		foreach ($products->item as $key=>$product) {
			?>
			<li>
				<a href="<?php echo (string) $product->link; ?>" target="_blank" class="rsswidget"><?php echo (string) $product->title; ?></a>
				<span class="rss-date"></span>
				<div class="rssSummary">
					<?php echo (string) $product->description; ?>		
				</div>
			</li>
			<?php	
		}
		?>
			<li><hr /><a href="http://www.wpcube.co.uk/?utm_source=wordpress&utm_medium=link&utm_content=dashboard&utm_campaign=general"><?php _e('Visit the WP Cube Web Site'); ?></a></li>
		</ul>
	</div>
	<?php
} else {
	?>
	<p><?php echo(__('Why not visit').' <a href="http://www.wpcube.co.uk/?utm_source=wordpress&utm_medium=link&utm_content=dashboard&utm_campaign=general" target="_blank">http://www.wpcube.co.uk</a> '.__('and check out some of our other amazing Premium WordPress Plugins?')); ?></p>
	<p><a href="http://www.wpcube.co.uk/?utm_source=wordpress&utm_medium=link&utm_content=dashboard&utm_campaign=general" target="_blank" class="button"><?php _e('Visit WP Cube'); ?></a></p>
	<?php
}
?>