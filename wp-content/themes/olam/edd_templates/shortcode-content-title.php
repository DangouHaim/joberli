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
	<a class="product-owner" href="<?echo "https://joberli.ru/profile/?id=".get_the_author_meta('ID');?>"><?php esc_html_e("", "olam"); ?> <?php the_author(); ?></a>

	<div class="details-bottom">
		<div class="product-options">
			<? if (is_user_logged_in()) : ?>
				<a href="#" data-id="<? echo get_the_ID(); ?>" class="post-save" title="<?php esc_attr_e('Сохранить', 'olam'); ?> "><i class="demo-icons icon-like"></i><i class="posts-count"><? echo get_saved_posts_count(get_the_ID()) ?></i></a>
			<? else : ?>
				<a href="#" data-id="<? echo get_the_ID(); ?>" class="noLoggedUser" title="<?php esc_attr_e('Сохранить', 'olam'); ?> "><i class="demo-icons icon-like"></i></a>
			<? endif ?>
			<?php if (!olam_check_if_added_to_cart(get_the_ID())) {
					$eddOptionAddtocart = edd_get_option('add_to_cart_text');
					$addCartText = (isset($eddOptionAddtocart) && $eddOptionAddtocart  != '') ? $eddOptionAddtocart : esc_html__("Добавить в корзину", "olam");
					if (edd_has_variable_prices(get_the_ID())) {

						$defaultPriceID = edd_get_default_variable_price(get_the_ID());
						$downloadArray = array('edd_action' => 'add_to_cart', 'download_id' => get_the_ID(), 'edd_options[price_id]' => $defaultPriceID);
					} else {
						$downloadArray = array('edd_action' => 'add_to_cart', 'download_id' => get_the_ID());
					}
					?>
				<? if (is_user_logged_in()) : ?>
					<? if (get_the_author_meta('ID') !== get_current_user_id()) : ?>
					<a href="#" title="<?php esc_attr_e('Купить сейчас', 'olam'); ?>"><i class="demo-icons icon-download fastPurchase" data-price="<?=edd_get_lowest_price_option(get_the_ID())?>" data-id="<?=get_the_ID()?>"></i></a>
					<? else : ?>
					<a href="/vendor-dashboard/?task=edit-product&post_id=<?=get_the_ID()?>" title="<?php esc_attr_e('Редактировать', 'olam'); ?>"><i class="fa fa-pencil" data-price="<?=edd_get_lowest_price_option(get_the_ID())?>" data-id="<?=get_the_ID()?>"></i></a>
					<a href="/vendor-dashboard/?task=delete-product&post_id=<?=get_the_ID()?>" title="<?php esc_attr_e('Удалить', 'olam'); ?>"><i class="fa fa-close" data-price="<?=edd_get_lowest_price_option(get_the_ID())?>" data-id="<?=get_the_ID()?>"></i></a>
					<? endif ?>
				<? else : ?>
					<a href="#" class="noLoggedUser" title="<?php esc_attr_e('Купить сейчас', 'olam'); ?>"><i class="demo-icons icon-download"></i></a>
				<? endif ?>
				<a href="<?php echo esc_url(add_query_arg($downloadArray, olam_get_current_page_url())); ?>" title="<?php echo esc_html($addCartText); ?>"><i class="demo-icons icon-cart"></i></a>
			<?php } else { ?>
				<a class="cart-added" href="<?php echo esc_url(edd_get_checkout_uri()); ?>" title="<?php esc_attr_e('Checkout', 'olam'); ?> "><i class="fa fa-check"></i></a>
			<?php } ?>
		</div>
		<?php $olamct = get_theme_mod('olam_show_cats');
			if (isset($olamct) && $olamct == 1) {

				$cat = wp_get_post_terms(get_the_ID(), 'download_category');
				$mlink = get_term_link($cat[0]->slug, 'download_category');
				$allCategoryName = "";
				foreach ($cat as $value) {
					$allCategoryName .= $value->name.", ";
				}
				$allCategoryName = substr($allCategoryName, 0, -2);
				?><div class="product-author" title="<?=$allCategoryName?>"><a href="<?php echo $mlink; ?>"><?php echo $cat[0]->name; ?></a><? echo count($cat) == 1?"":" и ещё ".(count($cat)-1);?></div><?php
				}else {
				?> <div class="product-author"><a href="<?php echo esc_url(add_query_arg('author_downloads', 'true', get_author_posts_url(get_the_author_meta('ID')))); ?>"><?php esc_html_e("Автор", "olam"); ?>: <?php the_author(); ?></a></div><?php
																																																																												}
																																																																												?>
	</div>
</div>