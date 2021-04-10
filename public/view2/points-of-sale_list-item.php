<li class="pos_list-item">
	<table border="0" bordercolor="">
		<tbody>
			<tr>
				<td width="50">							
					<?php 
						if ( count( $point_of_sale->_pos_marker ) ) {
							foreach ( $point_of_sale->_pos_marker as $image ) { ?>
					    		<img src="<?php echo $image['url'] ?>" alt="<?php echo $image['alt'] ?>" class="pos_marker"/>
					<?php 	}							
						}else{ ?>
							<a class="icon pos-marker"></a>
					<?php } ?>
				</td>
				<td align="left">
					<h4 class="">
						<?php echo $point_of_sale->post_title ?>
					</h4>					
					<address class="pos_address">	
						<?php echo $point_of_sale->_pos_street ?>, <?php echo $point_of_sale->_pos_number ?><br>
						<?php echo $point_of_sale->_pos_neighborhood ?> - <?php echo $point_of_sale->_pos_city ?> / <?php echo $point_of_sale->_pos_state ?>
						
						
					</address>			
					<small>
						<?php if( !empty($point_of_sale->_pos_phone) ) ?>
							<i class="fa fa-phone-square"></i><?php echo $point_of_sale->_pos_phone ?>
						<?php if( !empty($point_of_sale->_pos_email) ) ?>
							<a href="#"><i class="fa fa-envelope-square"></i><?php echo $point_of_sale->_pos_email ?></a> 
						<?php if( !empty($point_of_sale->_pos_more_info) ) ?>
							<i class="fa fa-globe"></i><?php echo $point_of_sale->_pos_more_info ?> 
							<i class="fa fa-facebook-square"></i>
					</small>							
				</td>
			</tr>
		</tbody>
	</table>	
</li>