<?php global $products; ?>
<?php echo EDD_FES()->dashboard->product_list_status_bar(); ?>
<div id="tabs" class="hidden fes-vendor-menu">
  <ul>
    <li><a href="#fragment-1">Мои товары</a></li>
	<li><a href="#fragment-2">Мои покупки</a></li>
	<li><a href="#fragment-3">Мои задачи</a></li>
	<li><a href="#fragment-4">Завершённые</a></li>
  </ul>

  <div id="fragment-1">
  	<div id="fes-vendor-store-link">
		<?php echo EDD_FES()->vendors->get_vendor_store_url_dashboard(); ?>
	</div>
	<?
		$args = array(
			'author'        =>  get_current_user_id(),
			'orderby'       =>  'post_date',
			'order'         =>  'ASC',
			'post_type'     =>  'download',
			'posts_per_page'=>  '999999'
		);
		$query = new WP_Query($args);
		$ids = implode(",", wp_list_pluck( $query->posts, 'ID' ));
		wp_reset_query();

		echo do_shortcode("[downloads ids='" . $ids . "']");
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
										$rating = getProductRating($orderId);

										if($i + 1 < $rating) {
											$class = "clicked";
										}

										if($i + 1 == $rating) {
											$class = "clicked marked";
										}
									?>
									<div class="fa fa-star <?=$class?>" data-id="<?=$i+1?>" title="<?=$i+1?>"></div>
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


<div class="modal fade" id="voteModal" tabIndex="-1" role="dialog" aria-labelledby="voteModalLabel">
    <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" style="color: white;">Спасибо за покупку!</h4>
      </div>
      <div class="modal-body">
<div class="productVote" data-where="popup">
								Оцените товар и продавца:<br><br>
								<? for( $i = 0; $i < 5; $i++) : ?>
									<div class="fa fa-star" data-id="<?=$i+1?>" title="<?=$i+1?>" style="font-size: 18px;"></div>
								<? endfor ?>
							</div>
      </div>
    </div>
  </div>
</div>