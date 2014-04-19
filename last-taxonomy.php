<?php
/* =================================== Last Taxonomy Widget =================================== */

class Last_Taxonomy_widget extends WP_Widget {

		//function to set up widget in admin
		function Last_Taxonomy_widget() {
		
				$widget_ops = array( 'classname' => 'last-taxo', 
				'description' => __('A widget that will display the last taxonomy list.', 'last-taxo') );
				
				$control_ops = array( 'width' => 200, 'height' => 350, 'id_base' => 'last-taxo' );
				$this->WP_Widget( 'last-taxo', __('Last Taxonomy', 'last-taxo'), $widget_ops, $control_ops );
		
		}


		//function to echo out widget on sidebar
		function widget( $args, $instance ) {
		extract( $args );
		
				$title = $instance['title'];
				$sTaxo = $instance['taxo'];
				
				if (!$iNumber = (int)$instance['number']) {
					$iNumber = 10;
				} else if ($iNumber < 1) {
					$iNumber = 1;
				} else if ($iNumber > 15) {
					$iNumber = 15;
				}
				
				echo $before_widget;
				echo '<div class="last_taxonomy">';
		
				// if user written title echo out
				if ( $title ){
				echo $before_title . $title . $after_title;
				}
				
			    $aTaxoParams = array('orderby' => 'id',
									 'order' => 'DESC',
									 'hide_empty' => false,
									 'number' => $iNumber,);
									 
				$oTerms = get_terms($sTaxo, $aTaxoParams);
				
				?>			
				
				<!--Now we print out speciifc user informations to screen!-->
				<ul class="last_taxo_list">
				<?php foreach ($oTerms as $oTerm): ?>
					<li><a href="<?php echo home_url() . '/' . $oTerm->taxonomy . 's/' . $oTerm->slug; ?>"><?php echo str_replace('$$', ',', $oTerm->name); ?></a></li>
				<?php endforeach; ?>
				</ul>
				<!--end-->
				
				<?php

				echo '</div>';
				echo $after_widget;
		
		 }//end of function widget



		//function to update widget setting
		function update( $new_instance, $old_instance ) {
		
				$instance = $old_instance;
				$instance['title'] = strip_tags( $new_instance['title'] );
				$instance['taxo'] = strip_tags( $new_instance['taxo'] );
				$instance['number'] = (int)$new_instance['number'];
				return $instance;
		
		}//end of function update


		//function to create Widget Admin form
		function form($instance) {
		
				$instance = wp_parse_args( (array) $instance, array( 'title' => '','taxo' => '','number' => '') );
				
				$instance['title'] = $instance['title'];
				$instance['taxo'] = $instance['taxo'];
				if (!isset($instance['number']) || !$iNumber = (int)$instance['number']) {
					$iNumber = 5;
				}
						
				?>

				<p>
				<label for="<?php echo $this->get_field_id('title'); ?>">Widget Title:</label> 
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
				 type="text" value="<?php echo $instance['title']; ?>" />
				</p>
				
				<p>
				<label for="<?php echo $this->get_field_id( 'taxo' ); ?>">Select Taxonomy:</label> 
				<select id="<?php echo $this->get_field_id( 'taxo' );?>" name="<?php echo $this->get_field_name( 'taxo' );?>" class="widefat" style="width:100%;">

				<?php
				$instance = $instance['taxo'];
				
				$aArgs = array('public'   => true,
							   '_builtin' => false,); 
				$sOutput = 'objects'; // or names
				
				$oTaxonomies = get_taxonomies($aArgs, $sOutput);
				
				$sEcho = '';
				
				if ($oTaxonomies) {
					foreach ($oTaxonomies  as $oTaxonomy) {
						$sEcho .='<option value="' . $oTaxonomy->name . '"';
						
						if ($instance == $oTaxonomy->name) {
							$sEcho .= 'selected="selected"';
						} 
						
						$sEcho .= '>' . ucfirst($oTaxonomy->name) . 's' . '</option>';
					}
				}
				
				echo $sEcho;
				?>
				</select>
				
				</p>
				
				<p>
				<label for="<?php echo $this->get_field_id('number'); ?>">Number:</label> 
				<input size="3" id="<?php echo $this->get_field_id('number'); ?>" name="<?php echo $this->get_field_name('number'); ?>"
				 type="text" value="<?php echo $iNumber; ?>" />
				</p>
				
				<?php
		
	      }//end of function form($instance)

}//end of  Class

register_widget('Last_Taxonomy_widget');