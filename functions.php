//Function to render COGs field in admin product add/edit page
function show_cogs_admin() {

  global $thepostid;

  woocommerce_wp_text_input(
    array(
      'id'    => '_cost_of_goods',
	    'label' => 'Cost of Goods ($)',
	    'data-type' => 'price',
      'value' => get_post_meta( $thepostid, '_cost_of_goods', true ),
      'class' => 'wc_input_price short',
    )
  );
	
  $price = get_post_meta( $thepostid, '_price', true );
	$sale_price = get_post_meta( $thepostid, '_sale_price', true );
	$cogs = get_post_meta( $thepostid, '_cost_of_goods', true );
	$margin = '';

  if ( $sale_price && $cogs ) {
		$margin = 100 - sprintf( "%0.2f", ($cogs / $sale_price ) * 100 ); //Calculate margin if sale price is available
	} else if ( $price && $cogs ) {
		$margin = 100 - sprintf( "%0.2f", ($cogs / $price ) * 100 ); //Calculate margin if sale price is not available
	}

  //Render margin field in admin product add/edit page
	woocommerce_wp_text_input(
    array(
      'id'    => '_product_margin',
			'label' => 'Margin (%)',
			'data-type'	=> 'text',
      'placeholder' => $margin,
      'custom_attributes' => array ('readonly' => 'readonly',),
      'class' => 'wc_input_price short',
	  )
  );
}
add_action( 'woocommerce_product_options_pricing', 'show_cogs_admin' );

//Function to update COGs in admin product add/edit page
function process_product_cogs_data( $post_id ) {

  $product = wc_get_product( $post_id );

  $product->update_meta_data( '_cost_of_goods', wc_clean( wp_unslash( filter_input( INPUT_POST, '_cost_of_goods' ) ) ) );
  $product->save();
}
add_action( 'woocommerce_process_product_meta', 'process_product_cogs_data' );
