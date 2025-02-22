<!DOCTYPE html>

<html class="no-js" <?php language_attributes(); ?>>

<head>

  <meta charset="<?php bloginfo('charset'); ?>">

  <meta name="viewport" content="width=device-width">

  <?php if (!function_exists('has_site_icon') || !has_site_icon()) {

    $themefavicon = get_theme_mod('olam_theme_favicon');

    $themefavicon = olam_replace_site_url($themefavicon);

    if (isset($themefavicon) && (strlen($themefavicon) > 0)) { ?>

      <link rel="shortcut icon" type="image/x-icon" href="<?php echo esc_url($themefavicon); ?>">

    <?php }
  } else {
    wp_site_icon();
  } ?>

  <?php

  $customcss = get_theme_mod('olam_custom_css');

  if (isset($customcss) && (strlen($customcss) > 0)) { ?>

    <style type="text/css">
      <?php echo esc_html($customcss); ?>
    </style>

  <?php } ?>

  <?php wp_head(); ?>

  <!-- Begin Talk-Me {literal} -->
  <script type='text/javascript'>
  	(function(d, w, m) {
  		window.supportAPIMethod = m;
  		var s = d.createElement('script');
  		s.type ='text/javascript'; s.id = 'supportScript'; s.charset = 'utf-8';
  		s.async = true;
  		var id = '53df08b6925355829138eaf9c3a91a9c';
  		s.src = '//lcab.talk-me.ru/support/support.js?h='+id;
  		var sc = d.getElementsByTagName('script')[0];
  		w[m] = w[m] || function() { (w[m].q = w[m].q || []).push(arguments); };
  		if (sc) sc.parentNode.insertBefore(s, sc);
  		else d.documentElement.firstChild.appendChild(s);
  	})(document, window, 'TalkMe');
  </script>
  <!-- {/literal} End Talk-Me -->
</head>

<?php

$bodyClassArray = array();

$olamheadersticky = get_theme_mod('olam_header_sticky');

$olamcategoryfilter = get_theme_mod('olam_category_filter');

if (isset($olamheadersticky) && $olamheadersticky == 1) {

  $bodyClassArray[] = "header-sticky";
}

?>



<body <?php body_class($bodyClassArray); ?>>

  <!--[if lt IE 8]>

            <p class="browserupgrade"><?php echo wp_kses(__('You are using an <strong>outdated</strong> browser. Please upgrade your browser to improve your experience.</p>', 'olam'), array('p' => array(), 'strong' => array())); ?>

            <![endif]-->

  <?php

  $olamthemepreloader = get_theme_mod('olam_theme_preloader');

  if (isset($olamthemepreloader) && $olamthemepreloader == 1) {
    include('includes/preloader.php');
  } ?>

  <div class="wrapper">

    <div class="middle-area<?php if (class_exists('Walker_EDD_Review')) {
                              echo ' edd-review-middle';
                            } ?>">

      <?php

      $header_overlay = olam_get_page_option(get_the_ID(), 'olam_transparent_header_overlay');

      if (($header_overlay) && ($header_overlay == 1)) {

        $headerClass .= 'header-transparent-overlay';
      }

      ?>

      <div class="header-wrapper header-bg <?php echo esc_attr($headerClass); ?>">

        <!-- Header -->

        <?

        $nopadding = "";

        if (is_user_logged_in()) {

          $nopadding = "nopadding";
        }

        ?>

        <header id="header" class="header navbar-fixed-top <? echo $nopadding; ?>">

          <div class="container">

            <?php

            $olamlogocenter = get_theme_mod('olam_logo_center');

            if (isset($olamlogocenter) && $olamlogocenter == 1) { ?> <div class="logo-center"><?php } else { ?> <div><?php }



                                                                                                                      ?>



                <div class="header-section">

                  <div class="header-wrap">

                    <div class="header-col col-logo">

                      <div class="logo">

                        <a href="<?php echo get_site_url(); ?>">

                          <?php $olamlogo = olam_replace_site_url(get_theme_mod('olam_theme_logo')); ?>

                          <img class="site-logo" src="<?php if (isset($olamlogo) && strlen($olamlogo) > 0) {
                                                        echo esc_url($olamlogo);
                                                      } else {
                                                        echo esc_url(get_template_directory_uri()) . '/img/logo.png';
                                                      } ?>" alt="<?php echo get_bloginfo('name'); ?>">

                        </a>

                      </div>

                    </div>

                    <div class="header-col col-nav">

                      <nav id="nav">

                        <form method="GET" action="<?php echo home_url(); ?>" class="display-none-md">



                          <div class="search-fields">

                            <input name="s" value="<?php echo (isset($_GET['s'])) ? $_GET['s'] : null; ?>" type="text" placeholder="<?php esc_html_e('Поиск...', 'olam'); ?>">

                            <input type="hidden" name="post_type" value="download">

                            <span class="search-btn"><input type="submit"></span>

                          </div>

                        </form>

                        <?php if (has_nav_menu('header-top-menu')) {
                          wp_nav_menu(array('theme_location' => 'header-top-menu'));
                        } ?>

                        <ul class="shop-nav" style="margin-left: 30px">

                          <? if (is_user_logged_in()) : ?>

                            <li class="mouseHover add-item display-none-md" data-discription="Добавить товар">

                              <a href="/vendor-dashboard/?task=new-product"><i class="demo-icons icon-plus"></i></a>

                            </li>

                          <? endif ?>

                          <? if (is_user_logged_in()) : ?>

                            <li class="messageClosedDialog elMes display-none-md">

                              <a href="#" class="mouseHover" data-discription="Сообщения"><i class="fa fa-envelope" style="font-size: 20px;"></i></a>

                            </li>

                          <? endif ?>

                          <? if (is_user_logged_in()) : ?>

                            <li class="mouseHover display-none-md" data-discription="Сохранённые посты">

                              <a href="/saved-posts/"><i class="fa fa-heart" style="font-size: 18px;"></i></a>

                            </li>

                          <? endif ?>

                          <li class="display-none-md">

                            <?php olam_print_mini_cart(); ?>

                          </li>





                          <li style="padding: 2px 5px 0px 5px;" class="mouseHover display-none-md" data-discription="Ваш баланс">

                            <?

                            if (is_user_logged_in()) {

                              echo "<a href='/addAccount/'>" . getAccount(get_current_user_id()) . " ₽" . "</a>";
                            }

                            ?>

                          </li>

                          <?

                          global $current_user;

                          get_currentuserinfo();

                          ?>

                          <li class="no-hover mouseHover" data-discription="<?=$current_user->user_login?>" onclick="location.href = '/vendor-dashboard/?task=profile';">

                            <?

                            if (is_user_logged_in()) {

                              ?>
                              <script>
                                  TalkMe("setClientInfo", {
                                name: "<?=$current_user->user_login?>",
                                email: "<?=$current_user->user_email?>"
                              });
                              </script>
                              <div class="loggedUser">

                                <div class="user-ico">

                                  <?

                                  echo get_avatar($current_user->ID, 35);

                                  ?>
                                </div>

                              </div>

                            <?

                            }

                            ?>

                          </li>

                          <li><?php if (!is_user_logged_in()) { ?> <a href="#" class="login-button login-trigger"><?php esc_html_e("Войти", "olam"); ?></a><?php } else { ?><a href="<?php echo wp_logout_url(home_url()); ?>" class="login-button logout mouseHover" data-discription="Выход"><?php esc_html_e('Logout', 'olam'); ?></a><?php  } ?></li>

                          <li class="display-md">
                            <div id="mmenu-button">
                              <a href="#mmenu" style="display: flex;"><i class="fa fa-list" style="font-size: 25px; margin-bottom: -2px;"></i></a>
                            </div>
                          </li>

                          <? if(is_user_logged_in()) : ?>
                            <li><a href="/vendor-dashboard/?task=logout" class="mouseHover" data-discription="Выход"><i class="fa fa-sign-out-alt fix" style="font-size: 20px"></i></a></li>
                          <? endif ?>

                          <li></li>

                        </ul>

                      </nav>

                    </div>

                    <div class="header-col col-shop">

                    </div>

                  </div>

                  <div class="nav-toggle">

                    <span></span>

                    <span></span>

                    <span></span>

                  </div>

                  <!-- mobile navigation -->

                  <div class="mob-nav">

                  </div>

                </div>

              </div>

            </div>

        </header>

        <span class="tooltip-blue bottom_tooltip-blue" style="z-index: 3000;"></span>

        <!-- Header End -->



        <? get_template_part("popup_message_box"); ?>



        <?php if (!is_front_page()) { ?>

          <!-- Search Section-->

          <?php $pageHeaderOption = olam_get_page_option(get_the_ID(), "olam_enable_header_search"); ?>

          <?php if (is_tax("download_category") || is_tax("download_tag") || (($pageHeaderOption)) || (is_search() && get_query_var('post_type') == "download")) { ?>

            <div class="section-first colored-section" data-speed="4" data-type="background">

              <div class="container">

                <div class="product-search">

                  <div class="product-search-form">

                    <form method="GET" action="<?php echo home_url(); ?>">

                      <?php if (isset($olamcategoryfilter) && $olamcategoryfilter == 1) {

                        $taxonomies = array('download_category');

                        $args = array('orderby' => 'count', 'hide_empty' => true);

                        echo olam_get_terms_dropdown($taxonomies, $args);
                      } ?>

                      <div class="search-fields">

                        <input name="s" value="<?php echo (isset($_GET['s'])) ? $_GET['s'] : null; ?>" type="text" placeholder="<?php esc_html_e('Поиск..', 'olam'); ?>">

                        <input type="hidden" name="post_type" value="download">

                        <span class="search-btn"><input type="submit"></span>

                      </div>

                    </form>

                  </div>

                  <span class="clearfix"></span>

                </div>

              </div>

            </div>

          <?php }
        } ?>
        <!-- Search -->

      </div>

      <nav id="mmenu" class="hidden">
        <ul>
          <li>
            <div class="product-search">

              <div class="product-search-form">

                <form method="GET" action="<?php echo home_url(); ?>">

                  <?php if (isset($olamcategoryfilter) && $olamcategoryfilter == 1) {

                    $taxonomies = array('download_category');

                    $args = array('orderby' => 'count', 'hide_empty' => true);

                    echo olam_get_terms_dropdown($taxonomies, $args);
                  } ?>

                  <div class="search-fields">

                    <input name="s" value="<?php echo (isset($_GET['s'])) ? $_GET['s'] : null; ?>" type="text" placeholder="<?php esc_html_e('Поиск..', 'olam'); ?>">

                    <input type="hidden" name="post_type" value="download">

                    <span class="search-btn"><input type="submit"></span>

                  </div>

                </form>

              </div>
            </div>
          </li>
          <?
          if (is_user_logged_in()) {

            echo "<li><a href='/addAccount/'>" . getAccount(get_current_user_id()) . " ₽" . " Ваш баланс</a></li>";
          }
          ?>
          <li><a href="/"><i class="fa fa-home fix" style="font-size: 20px"></i>Домой</a></li>
          <? if(is_user_logged_in()) : ?>
            <li><a href="/vendor-dashboard/?task=new-product"><i class="demo-icons icon-plus fix right-fix"></i>Добавить товар</a></li>
          <? endif ?>
          <? if(is_user_logged_in()) : ?>
            <li><a href="/messages/"><i class="fa fa-envelope fix" style="font-size: 20px;"></i>Сообщения</a></li>
          <? endif ?>
          <li><a href="/saved-posts/"><i class="fa fa-heart fix" style="font-size: 18px;"></i>Сохранённые посты</a></li>
          <li><a href="/checkout/"><i class="demo-icon icon-cart"></i>Корзина</a></li>
          <? if(is_user_logged_in()) : ?>
            <li><a href="/vendor-dashboard/?task=logout"><i class="fa fa-sign-out-alt fix" style="font-size: 20px"></i> Выход</a></li>
          <? endif ?>
        </ul>
      </nav>
