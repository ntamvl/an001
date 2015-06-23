<div class="inside">
<table class="form-table">
<tbody>
<tr>
<th style="width:20%;"><label for="titulo_serie"><?php _e( 'Series Title', 'mundothemes' ); ?></label></th>
<td><input type="text" name="titulo_serie" id="titulo_serie" value="<?php echo wpshed_get_custom_field( 'titulo_serie' ); ?>" style="width: 320px;" /></td>
</tr>
<tr>
<th style="width:20%;"><label for="url_serie"><?php _e( 'Slug URL main', 'mundothemes' ); ?></label></th>
<td><input type="text" name="url_serie" id="url_serie" value="<?php echo wpshed_get_custom_field( 'url_serie' ); ?>"  placeholder="<?php _e( 'Slug URL main', 'mundothemes' ); ?>" style="width: 320px;" /></td>
</tr>
<tr>
<th style="width:20%;"><label for="fecha_serie"><?php _e( 'Date posted', 'mundothemes' ); ?></label></th>
<td><input type="text" name="fecha_serie" id="fecha_serie" value="<?php echo wpshed_get_custom_field( 'fecha_serie' ); ?>" style="width: 320px;" /></td>
</tr>
<tr>
<th style="width:20%;"><label for="temporada_serie"><?php _e( 'Seasonal number', 'mundothemes' ); ?></label></th>
<td><input type="text" name="temporada_serie" id="temporada_serie" value="<?php echo wpshed_get_custom_field( 'temporada_serie' ); ?>" style="width: 50px;" /></td>
</tr>
<tr>
<th style="width:20%;"><label for="episodio_serie"><?php _e( 'Number episode', 'mundothemes' ); ?></label></th>
<td><input type="text" name="episodio_serie" id="episodio_serie" value="<?php echo wpshed_get_custom_field( 'episodio_serie' ); ?>" style="width: 50px;" /></td>
</tr>
</tbody>
</table>
</div>    