<?php global $products; ?>
<?php echo EDD_FES()->dashboard->product_list_status_bar(); ?>
<div id="fes-vendor-store-link">
		<?php echo EDD_FES()->vendors->get_vendor_store_url_dashboard(); ?>
	</div>
<div id="tabs" class="hidden fes-vendor-menu">
  <ul>
    <li><a href="#fragment-1">Мои товары</a></li>
	<li><a href="#fragment-2">Мои покупки</a></li>
	<li><a href="#fragment-3">Мои задачи</a></li>
	<li><a href="#fragment-4">Завершённые</a></li>
  </ul>

  <div id="fragment-1">
	<?
		$args = array(
			'author'        =>  get_current_user_id(),
			'orderby'       =>  'post_date',
			'order'         =>  'ASC',
			'post_type'     =>  'download',
			'posts_per_page'=>  '999999'
		);
		$eddrd_query = new WP_Query($args);

		if( $eddrd_query->have_posts()) { ?>
			<div id="layero-related-downloads">
				<h3><?php echo $related_dl_title; ?></h3>
				<div id="edd-related-items-wrapper" class="edd-rp-single">
					<?php $countRow = 1; // Editted: for creating 3 item rows
					while ($eddrd_query->have_posts()) {
							$eddrd_query->the_post();
							if ($post->ID == $exclude_post_id) continue;

							if ($countRow%4 == 1) { // Editted: for creating 3 item rows
								echo "<div class='row'>";
							}	?>
								<div class="col-md-3">
									<div class="edd_download_inner">
										<div class="thumb">
											<?php
											$thumbID=get_post_thumbnail_id(get_the_ID());
											$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb');
											$featImage=$featImage[0]; 
											$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true);

											$square_img = get_post_meta(get_the_ID(),"download_item_square_img");

										// feat vid code start
											$videoCode=get_post_meta(get_the_ID(),"download_item_video_id"); 
											$audioCode=get_post_meta(get_the_ID(),"download_item_audio_id");		
											$itemSet=null;		
											$featFlag=null;	
											$videoFlag=null;				
											if(isset($videoCode[0]) && (strlen($videoCode[0])>0) ){
												$itemSet=1;	
												$videoUrl=$videoCode[0];
												//$videoUrl=wp_get_attachment_url($videoCode[0]); 
												
												$videoFlag=1; ?>
												<div class="media-thumb">
													<?php echo do_shortcode("[video src='".$videoUrl."']"); ?>
												</div> <?php
											}
											else if (!empty($square_img) && strlen($square_img[0])>0) {
												$featFlag=1; ?>
												   <a href="<?php the_permalink(); ?>">
													<span><i class="demo-icons icon-link"></i></span>
													<img src="<?php echo esc_url($square_img[0]); ?>" />
												</a> <?php
											 }
											else if((isset($featImage))&&(strlen($featImage)>0)){
												$featFlag=1;
												$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true); ?>
												<a href="<?php the_permalink(); ?>">
													<span><i class="demo-icons icon-link"></i></span>
													<img src="<?php echo esc_url($featImage); ?>" alt="<?php echo esc_attr($alt); ?>">
												</a><?php
											}
											if(!isset($videoFlag)){ 
												if(isset($audioCode[0]) && (strlen($audioCode[0])>0) ){
													$itemSet=1;
													$audioUrl=wp_get_attachment_url($audioCode[0]);
													?>
													<div class="media-thumb">
														<?php echo do_shortcode("[audio src='".$audioUrl."']"); ?>
													</div> <?php
												}

											} ?>
											<?php if(!(isset($featFlag))){ ?>
											<a href="<?php the_permalink(); ?>">
												<span><i class="demo-icons icon-link"></i></span>
												<img src="<?php echo get_template_directory_uri(); ?>/img/preview-image-default.jpg" alt="<?php echo esc_attr($alt); ?>">
											</a>
											<?php } ?>

										</div>
										<div class="product-details">
										<div class="product-name"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
										<div class="product-description"><?php echo get_the_excerpt(); ?></div>
										<div class="product-price"><?php edd_price(get_the_ID()); ?></div>

										<div class="loggedUser">
											<div class="user-ico">
												<?
													echo get_avatar(get_the_author_meta('ID'), 25);
												?>
											</div>
										</div>
										<a class="product-owner" href="<?=the_user_chat_link(get_the_author_meta('ID'))?>"><?php esc_html_e("", "olam"); ?> <?php the_author(); ?></a>
										<div class="details-bottom">
											<div class="product-options">	
											<?if(is_user_logged_in()):?>
											<a href="#" data-id="<? echo get_the_ID(); ?>" class="post-save" title="<?php esc_attr_e('Сохранить','olam'); ?> "><i class="demo-icons icon-like"></i><i class="posts-count"><? echo get_saved_posts_count(get_the_ID()) ?></i></a>
											<?else:?>
											<a href="#" data-id="<? echo get_the_ID(); ?>" class="noLoggedUser" title="<?php esc_attr_e('Сохранить','olam'); ?> "><i class="demo-icons icon-like"></i></a>
											<?endif?>   
												<?php if(!olam_check_if_added_to_cart(get_the_ID())){ 
													$eddOptionAddtocart=edd_get_option( 'add_to_cart_text' );
													$addCartText=(isset($eddOptionAddtocart) && $eddOptionAddtocart  != '') ?$eddOptionAddtocart:esc_html__("Добавить в корзину","olam");
													if(edd_has_variable_prices(get_the_ID())){														
														$defaultPriceID=edd_get_default_variable_price( get_the_ID() );
														$downloadArray=array('edd_action'=>'ade_to_cart','download_id'=>get_the_ID(),'edd_options[price_id]'=>$defaultPriceID);
													}
													else{
														$downloadArray=array('edd_action'=>'ade_to_cart','download_id'=>get_the_ID());
													}	
													?>
																												<?if(is_user_logged_in()):?>
												<a href="<?php echo esc_url(add_query_arg($downloadArray,edd_get_checkout_uri())); ?>" title="<?php esc_attr_e('Купить сейчас','olam'); ?>"><i class="demo-icons icon-download"></i></a>
												<?else:?>
												<a href="#" class="noLoggedUser"  title="<?php esc_attr_e('Купить сейчас','olam'); ?>"><i class="demo-icons icon-download"></i></a>
												<?endif?>
													<a href="<?php echo esc_url(add_query_arg($downloadArray,olam_get_current_page_url())); ?>" title="<?php echo esc_html($addCartText); ?>"><i class="demo-icons icon-cart"></i></a>   
													<?php } else { ?>
													<a class="cart-added" href="<?php echo esc_url(edd_get_checkout_uri()); ?>" title="<?php esc_attr_e('Checkout','olam'); ?> "><i class="fa fa-check"></i></a>    
													<?php } ?>
												</div>
												<?php $olamct=get_theme_mod('olam_show_cats');
														if(isset($olamct)&& $olamct==1 ){

													$cat = wp_get_post_terms(get_the_ID(),'download_category');
													$mlink = get_term_link($cat[0]->slug, 'download_category');
													$allCategoryName = "";
													foreach ($cat as $value) {
													  $allCategoryName .= $value->name.", ";
													}
													$allCategoryName = substr($allCategoryName, 0, -2);
													?><div class="product-author" title="<?=$allCategoryName?>"><a href="<?php echo $mlink; ?>"><?php echo $cat[0]->name; ?></a><? echo count($cat) == 1?"":" и ещё ".(count($cat)-1);?></div><?php
													}
													else{
													?> <div class="product-author"><a href="<?php echo esc_url(add_query_arg( 'author_downloads', 'true', get_author_posts_url( get_the_author_meta('ID')) )); ?>"><?php esc_html_e("By","olam"); ?>: <?php the_author(); ?></a></div><?php
													}
													?>
												</div>
											</div>
										</div>
								</div>

							<?php if ($countRow%4 == 0) { // Editted: for creating 3 item rows
								echo "</div>";
							}
							$countRow++; ?>
					<?php } ?>
					<?php if ($countRow%4 != 1) echo "</div>"; // Editted: for creating 3 item rows
				// This is to ensure there is no open div if the number of elements is not a multiple of 3 ?>
			</div>
			
		</div>
			<?php wp_reset_query();
	}
?>
  </div>

  <div id="fragment-2">
  	<table class="table fes-table table-condensed  table-striped" id="fes-product-list">
		<thead>
			<tr>
				<th><?php _e( 'Картинка', 'edd_fes' ); ?></th>
				<th><?php _e( '№', 'edd_fes' ); ?></th>
				<th><?php _e( 'Имя', 'edd_fes' ); ?></th>
				<th><?php _e( 'Статус', 'edd_fes' ); ?></th>
				<th><?php _e( 'Цена', 'edd_fes' ); ?></th>
				<th><?php _e( 'Действия','edd_fes') ?></th>
				<th><?php _e( 'Дата', 'edd_fes' ); ?></th>
				<?php do_action('fes-product-table-column-title'); ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$orders = array();

			$purchases = getUserPurchases();

			foreach ( $purchases as $purchase ) {
				$download = edd_get_download($purchase->postId);
				$download->purchase = $purchase;

				$orderId = $download->purchase->id;
					
				if(getOrderStatus($orderId) == "Выполнен"
					|| getOrderStatus($orderId) == "Отменён") {
					continue;
				}

				array_push($orders, $download);
			}

			if (count($orders) > 0 ){
				foreach ( $orders as $product ) : ?>
				<?php
					$orderId = $product->purchase->id;
				?>
				<tr>
					<td class = "fes-product-list-td"><?php

					$featImage=null;
					$theDownloadImage=get_post_meta($product->ID,'download_item_thumbnail_id'); 
					if(is_array($theDownloadImage) && (count($theDownloadImage)>0) ){
						$thumbID=$theDownloadImage[0];
						$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
						$featImage=$featImage[0];
					}
					else{
						$thumbID=get_post_thumbnail_id($product->ID);
						$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
						$featImage=$featImage[0];
					}

					?>
					<?php if(isset($featImage)&&strlen($featImage)>0) { 
						$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true);
						?> 
						<img src="<?php echo esc_url($featImage);  ?>" class="attachment-shop_thumbnail wp-post-image" alt="<?php echo esc_attr($alt); ?>">
						<?php } ?>
					</td>
					<td class = "fes-product-list-td"><?php echo $orderId; ?></td>
					<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_title($product->ID); ?></td>
					<td class = "fes-product-list-td"><?php echo getOrderStatus($orderId); ?></td>
					<td class = "fes-product-list-td"><?php echo $product->purchase->sum; ?></td>
					
					<td class = "fes-product-list-td">
						
						<?php EDD_FES()->dashboard->product_list_actions($product->ID); ?>

						<?php if( !isCancelledOrder($orderId) && !isOrderHasCancelRequest($orderId) && !isOrderDone($orderId) ): ?>
							<a href="#" class="tabs-button fa fa-times cancel-purchase" data-order-id="<?php echo $orderId; ?>" title="Отменить"></a>

						<?php endif; ?>

						<?php if( !isCancelledOrder($orderId) && !isOrderDone($orderId) && isOrderHasDoneRequest($orderId) ): ?>
							<a href="#" class="tabs-button fas fa-check confirm-order-done" data-order-id="<?php echo $orderId; ?>" title="Подтвердить выполнение"></a>
						<?php endif; ?>

						<a href="<?=the_user_chat_link(getOrderPostOwner($orderId))?>" class="tabs-button fa fa-comment" data-order-id="<?php echo $orderId; ?>" title="Связаться с исполнителем"></a>
						
					</td>

					<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_date($product->ID); ?></td>
					<?php do_action('fes-product-table-column-value'); ?>
				</tr>
				<?php endforeach;
			} else {
				echo '<tr><td colspan="7" class = "fes-product-list-td" >'. sprintf( _x('Нет элементов', 'FES lowercase plural setting for download','edd_fes'), EDD_FES()->helper->get_product_constant_name( $plural = true, $uppercase = false ) ).'</td></tr>';
			}
			?>
		</tbody>
	</table>
  </div>

  <div id="fragment-3">
  	<table class="table fes-table table-condensed  table-striped" id="fes-product-list">
		<thead>
			<tr>
				<th><?php _e( 'Картинка', 'edd_fes' ); ?></th>
				<th><?php _e( '№', 'edd_fes' ); ?></th>
				<th><?php _e( 'Имя', 'edd_fes' ); ?></th>
				<th><?php _e( 'Статус', 'edd_fes' ); ?></th>
				<th><?php _e( 'Цена', 'edd_fes' ); ?></th>
				<th><?php _e( 'Действия','edd_fes') ?></th>
				<th><?php _e( 'Дата', 'edd_fes' ); ?></th>
				<?php do_action('fes-product-table-column-title'); ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$orders = array();

			$purchases = getUserOrders();

			foreach ( $purchases as $purchase ) {
				$download = edd_get_download($purchase->postId);
				$download->purchase = $purchase;

				$orderId = $download->purchase->id;
					
				if(getOrderStatus($orderId) == "Выполнен"
					|| getOrderStatus($orderId) == "Отменён") {
					continue;
				}

				array_push($orders, $download);
			}

			if (count($orders) > 0 ){
				foreach ( $orders as $product ) : ?>
				<?php
					$orderId = $product->purchase->id;
				?>
				<tr>
					<td class = "fes-product-list-td"><?php

					$featImage=null;
					$theDownloadImage=get_post_meta($product->ID,'download_item_thumbnail_id'); 
					if(is_array($theDownloadImage) && (count($theDownloadImage)>0) ){
						$thumbID=$theDownloadImage[0];
						$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
						$featImage=$featImage[0];
					}
					else{
						$thumbID=get_post_thumbnail_id($product->ID);
						$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
						$featImage=$featImage[0];
					}

					?>
					<?php if(isset($featImage)&&strlen($featImage)>0) { 
						$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true);
						?> 
						<img src="<?php echo esc_url($featImage);  ?>" class="attachment-shop_thumbnail wp-post-image" alt="<?php echo esc_attr($alt); ?>">
						<?php } ?>
					</td>
					<td class = "fes-product-list-td"><?php echo $orderId; ?></td>
					<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_title($product->ID); ?></td>
					<td class = "fes-product-list-td"><?php echo getOrderStatus($orderId); ?></td>
					<td class = "fes-product-list-td"><?php echo $product->purchase->sum; ?></td>
					
					<td class = "fes-product-list-td">
						
						<?php EDD_FES()->dashboard->product_list_actions($product->ID); ?>


						<?php if( !isInProgress($orderId) && !isCancelledOrder($orderId) && !isOrderDone($orderId) ): ?>
							<a href="#" class="tabs-button fa fa-handshake-o set-order-in-progress" data-order-id="<?php echo $orderId; ?>" title="Принять заказ"></a>
						<?php endif; ?>

						<?php if( !isCancelledOrder($orderId) && !isOrderDone($orderId) ): ?>
							<a href="#" class="tabs-button fa fa-times cancel-order-confirm" data-order-id="<?php echo $orderId; ?>" title="Отменить"></a>
						<?php endif; ?>

						<?php if( !isCancelledOrder($orderId) && !isOrderDone($orderId) && isInProgress($orderId) && !isOrderHasDoneRequest($orderId) ): ?>
							<a href="#" class="tabs-button fas fa-check set-order-done" data-order-id="<?php echo $orderId; ?>" title="Завершить"></a>
						<?php endif; ?>

						<a href="<?=the_user_chat_link( getUser($orderId) )?>" class="tabs-button fa fa-comment t1" data-order-id="<?php echo $orderId; ?>" title="Связаться с клиентом"></a>

					</td>

					<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_date($product->ID); ?></td>
					<?php do_action('fes-product-table-column-value'); ?>
				</tr>
				<?php endforeach;
			} else {
				echo '<tr><td colspan="7" class = "fes-product-list-td" >'. sprintf( _x('Нет элементов', 'FES lowercase plural setting for download','edd_fes'), EDD_FES()->helper->get_product_constant_name( $plural = true, $uppercase = false ) ).'</td></tr>';
			}
			?>
		</tbody>
	</table>
  </div>

  <div id="fragment-4">
  	<table class="table fes-table table-condensed  table-striped" id="fes-product-list">
		<thead>
			<tr>
				<th><?php _e( 'Картинка', 'edd_fes' ); ?></th>
				<th><?php _e( '№', 'edd_fes' ); ?></th>
				<th><?php _e( 'Имя', 'edd_fes' ); ?></th>
				<th><?php _e( 'Статус', 'edd_fes' ); ?></th>
				<th><?php _e( 'Цена', 'edd_fes' ); ?></th>
				<th><?php _e( 'Действия','edd_fes') ?></th>
				<th><?php _e( 'Дата', 'edd_fes' ); ?></th>
				<?php do_action('fes-product-table-column-title'); ?>
			</tr>
		</thead>
		<tbody>
			<?php
			$orders = array();

			$purchases = getUserOrdersAndPurchases();

			foreach ( $purchases as $purchase ) {
				$download = edd_get_download($purchase->postId);
				$download->purchase = $purchase;

				$orderId = $download->purchase->id;
					
				if(getOrderStatus($orderId) != "Выполнен"
					&& getOrderStatus($orderId) != "Отменён") {
					continue;
				}

				array_push($orders, $download);
			}

			if (count($orders) > 0 ){
				foreach ( $orders as $product ) : ?>
				<?php
					$orderId = $product->purchase->id;
				?>
				<tr>
					<td class = "fes-product-list-td"><?php

					$featImage=null;
					$theDownloadImage=get_post_meta($product->ID,'download_item_thumbnail_id'); 
					if(is_array($theDownloadImage) && (count($theDownloadImage)>0) ){
						$thumbID=$theDownloadImage[0];
						$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
						$featImage=$featImage[0];
					}
					else{
						$thumbID=get_post_thumbnail_id($product->ID);
						$featImage=wp_get_attachment_image_src($thumbID,'olam-product-thumb-small');
						$featImage=$featImage[0];
					}

					?>
					<?php if(isset($featImage)&&strlen($featImage)>0) { 
						$alt = get_post_meta($thumbID, '_wp_attachment_image_alt', true);
						?> 
						<img src="<?php echo esc_url($featImage);  ?>" class="attachment-shop_thumbnail wp-post-image" alt="<?php echo esc_attr($alt); ?>">
						<?php } ?>
					</td>
					<td class = "fes-product-list-td"><?php echo $orderId; ?></td>
					<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_title($product->ID); ?></td>
					<td class = "fes-product-list-td"><?php echo getOrderStatus($orderId); ?></td>
					<td class = "fes-product-list-td"><?php echo $product->purchase->sum; ?></td>
					
					<td class = "fes-product-list-td">
						
						<?php EDD_FES()->dashboard->product_list_actions($product->ID); ?>

						<? if(isOrderPostOwner($orderId)) : ?>
							<a href="<?=the_user_chat_link( getOrderPostOwner($orderId) )?>" class="tabs-button fa fa-comment" data-order-id="<?php echo $orderId; ?>" title="Связаться с исполнителем"></a>
						<? else :?>
							<a href="<?=the_user_chat_link( getUser($orderId) )?>" class="tabs-button fa fa-comment" data-order-id="<?php echo $orderId; ?>" title="Связаться с клиентом"></a>
						<?endif?>
						<? if(getOrderStatus($orderId) == "Выполнен" && isUserOrder($orderId)) : ?>
							<div class="productVote" id="vote<?=$orderId?>" data-id="<?=$orderId?>">
								Ваша оценка:
								<? for( $i = 0; $i < 5; $i++) : ?>
									<?
										$class = "";
										$rating = getPostRating(getPost($orderId));

										if($i + 1 < $rating) {
											$class = "clicked";
										}

										if($i + 1 == $rating) {
											$class = "clicked marked";
										}
									?>
									<div class="fa fa-star <?=$class?>" data-id="<?=$i+1?>"></div>
								<? endfor ?>
							</div>
						<?endif?>
					</td>

					<td class = "fes-product-list-td"><?php echo EDD_FES()->dashboard->product_list_date($product->ID); ?></td>
					<?php do_action('fes-product-table-column-value'); ?>
				</tr>
				<?php endforeach;
			} else {
				echo '<tr><td colspan="7" class = "fes-product-list-td" >'. sprintf( _x('Нет элементов', 'FES lowercase plural setting for download','edd_fes'), EDD_FES()->helper->get_product_constant_name( $plural = true, $uppercase = false ) ).'</td></tr>';
			}
			?>
		</tbody>
	</table>
  </div>

</div>