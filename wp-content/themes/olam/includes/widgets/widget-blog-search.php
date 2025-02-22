<?php

add_action('widgets_init', 'Olam_Blog_Search');

function Olam_Blog_Search()
{
	register_widget('Olam_Blog_Search');
}

class Olam_Blog_Search extends WP_Widget {
	/**
	 * Sets up a new Search widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 */
	public function __construct() {
		$widget_ops = array('classname' => 'olam_widget_search', 'description' => esc_html__( "Форма блога для поиска ваших сайтов.","olam") );
		parent::__construct('olam_blog_search_widget', esc_html__('Olam поиск','olam'), $widget_ops);
	}

	/**
	 * Outputs the content for the current Search widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $args     Display arguments including 'before_title', 'after_title',
	 *                        'before_widget', and 'after_widget'.
	 * @param array $instance Settings for the current Search widget instance.
	 */
	
	public function widget( $args, $instance ) {
		/** This filter is documented in wp-includes/widgets/class-wp-widget-pages.php */
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base );
		echo $args['before_widget'];
		if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		}
		// Use current theme search form if it exists
		$form = '<form role="search" method="get" id="olam_searchform" class="searchform" action="' . esc_url( home_url( '/' ) ) . '"> <div>
		<label class="screen-reader-text" for="s">' . esc_html__( 'Искать:','olam' ) . '</label>	
		<input type="text" value="' . get_search_query() . '" name="s" id="s" />
		<input type="hidden" name="post_type" value="post">	 
		<input type="submit" id="searchsubmit" value="'. esc_html__( 'Поиск','olam' ) .'" />	
		</div>
		</form>';
		echo $form;
		echo $args['after_widget'];
	}

	/**
	 * Outputs the settings form for the Search widget.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $instance Current settings.
	 */

	public function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '') );
		$title = $instance['title'];
		?>
		<p><label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Заголовок:','olam'); ?> <input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo esc_attr($title); ?>" /></label></p>
		<?php
	}

	/**
	 * Handles updating settings for the current Search widget instance.
	 *
	 * @since 2.8.0
	 * @access public
	 *
	 * @param array $new_instance New settings for this instance as input by the user via
	 *                            WP_Widget::form().
	 * @param array $old_instance Old settings for this instance.
	 * @return array Updated settings.
	 */

	public function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$new_instance = wp_parse_args((array) $new_instance, array( 'title' => ''));
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		return $instance;
	}
}
