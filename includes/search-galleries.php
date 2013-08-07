<?php

function ngs2_search_galleries($filter = '', $order_by = 'gid', $order_dir = 'DESC', $counter = TRUE) {

		global $wpdb, $ngg;


		$order_dir = ( $order_dir == 'DESC') ? 'DESC' : 'ASC';

		if($filter!='') {

			$galleries = $wpdb->get_results( "SELECT * FROM $wpdb->nggallery WHERE title LIKE '%".$filter."%' ORDER BY {$order_by} {$order_dir}", OBJECT_K );

		} else {

			$galleries = $wpdb->get_results( "SELECT * FROM $wpdb->nggallery ORDER BY {$order_by} {$order_dir} LIMIT 20", OBJECT_K );

		}

		if ( !$galleries )

			return array();

		if ( !$counter )

			return $galleries;


		// get the galleries information

 		foreach ($galleries as $key => $value) {

   			$galleriesID[] = $key;

   			// init the counter values

   			$galleries[$key]->counter = 0;	

		}
		

		// get the counter values

		$picturesCounter = $wpdb->get_results('SELECT galleryid, COUNT(*) as counter FROM '.$wpdb->nggpictures.' WHERE galleryid IN (\''.implode('\',\'', $galleriesID).'\') AND exclude != 1 GROUP BY galleryid', OBJECT_K);			


		if ( !$picturesCounter )

			return $galleries;

            
		// add the counter to the gallery objekt

 		foreach ($picturesCounter as $key => $value)

			$galleries[$value->galleryid]->counter = $value->counter;
		

		return $galleries;

	}

?>