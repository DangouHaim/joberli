<?php
$id = $_GET['id'];
if (!isset($_GET['id'])){$id == 1;}
if($id) {
    global $wpdb;

    $user = $wpdb->get_results("SELECT * FROM wp_users WHERE ID = " . $id);
	$user = $user[0];
}
?>
<style>.section {padding-bottom: 0px!important;}</style>
<script type="text/javascript">
	jQuery("title").html("<?=$user->user_nicename?> — Joberli");
	//jQuery(".fw-container h1").html("Профиль <?=$user->user_nicename?>");
</script>
<div class="col-lg-9 col-md-8">
	<h4>Личные данные: </h4>
	<label>Имя:&nbsp</label><?=get_user_meta( $id, 'first_name', true );?><br>
	<label>Фамилия:&nbsp</label><?=get_user_meta( $id, 'last_name', true );?><br><br>
	<h4>Контакты:</h4>
	<label>Почта:&nbsp</label><a class="btnInfoNotBack" href="mailto:<?=get_user_meta($id, 'email_to_use_for_contact_form', true);?>"><?=get_user_meta($id, 'email_to_use_for_contact_form', true);?></a><br>
	<label>Телефон:&nbsp</label><a class="btnInfoNotBack" href="tel:<?=get_user_meta($id, 'phone', true)?>"><?=get_user_meta($id, 'phone', true)?></a><br><br>
	<h4>О себе: </h4>
	<label><?=get_user_meta($id, 'description', true);?></label><br>
</div>
<div class="col-lg-3 col-md-4">
	<div class="sidebar">
		<div class="sidebar-item">
			<div class="sidebar-title">Данные</div>
			<div class="user-info user-profile-info">
				<label><?=$user->user_nicename?></label></br>
				<div class="profile_img"><?=get_avatar($id, 100)?></div>
				<strong><a href="/messages/?user=<?=$id?>&tab=chat" class="btn btn-sm btnWriteProfile">Написать на сайте</a></strong>
				<label><?echo get_user_online($id) == 1?"Сейчас на сайте":"Не в сети";?></label>
				<label>На сайте с <?=substr($user->user_registered,0,4)?> года</label>
			</div>
		</div>
		<div class="sidebar-item">
			<div class="sidebar-title">Поделиться</div>
			<div class="shareBlock">
			Поделиться профилем в: <div class="ya-share2" data-description="Профиль <?=$user->user_nicename?> на Joberli" data-title="Профиль <?=$user->user_nicename?> на Joberli" data-url="<?=getPartnerLink()?>" data-image="https://joberli.ru/wp-content/uploads/2019/07/Bezymyannyj.png" data-services="vkontakte,twitter,facebook,odnoklassniki,viber,whatsapp,telegram"></div>
			</div>
		</div>
	</div>
</div>
<?php
		$args = array(
			'author'        =>  $id,
			'orderby'       =>  'post_date',
			'order'         =>  'DESC',
			'post_type'     =>  'download',
			'posts_per_page'=>  '4'
		);
		$eddrd_query = new WP_Query($args);

		if( $eddrd_query->have_posts()) { ?>
		<br><h4>Последние товары: </h4>
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
			<strong><a href="https://joberli.ru/vendor/<?=$user->user_login?>" class="btn btn-sm btnWriteProfile">Посмотреть все товары</a></strong>
		</div>
			<?php wp_reset_query();
	}
?>