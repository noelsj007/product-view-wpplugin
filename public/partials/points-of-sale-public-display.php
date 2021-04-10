<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://lucianotonet.com
 * @since      1.0.0
 *
 * @package    Points_Of_Sale
 * @subpackage Points_Of_Sale/public/partials
 */
?>
<div id="points_of_sale">
	<div class="blockTop">
		<p>
			Encontre <b>Lake Side Beer</b> em qualquer um dos nossos <span><?php echo $total_stores ?></span> pontos de venda.			
		</p>
	</div>
	<div id="maps">
		<div class="map"></div>
		<div class="menuBox">
			<div class="block">				
				<select id="map-pos-regions">		
					<?php foreach ($states as $state) { ?>	
						<option value="<?php echo $state['name'] ?>" data-total-stores="<?php echo $state['total-stores'] ?>" data-cities="<?php echo htmlentities( json_encode( $state['cities'] ) ) ?>"><?php echo $state['name'] ?></option>
					<?php } ?>													
				</select>
				<select id="map-pos-cities">
					<option>Selecione uma cidade</option>
				</select>
				<select id="map-pos-neighborhoods">
					<option>Selecione um bairro</option>
				</select>
			</div>
		</div>
		<!-- <div class="shadow"></div> -->
	</div>
</div>