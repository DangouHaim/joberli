<?php
if(!olam_check_edd_exists()){
	return;
}

add_action('widgets_init', 'olam_social_share_widget');

function olam_social_share_widget()
{
	register_widget('olam_social_share_widget');
}

class olam_social_share_widget extends WP_Widget {

	function __construct()
	{
		$widget_ops = array('classname' => 'olam_social_share_widget', 'description' => esc_html__('Social Share icons widget. Used in Single Download Sidebar	','olam'));
		$control_ops = array('id_base' => 'olam_social_share_widget');
		parent::__construct('olam_social_share_widget', esc_html__('Olam Social Share widget','olam'), $widget_ops, $control_ops);
	}
	function widget($args, $instance)
	{
		extract($args);
		
		$instance['title'] = "Поделиться";

		$title = $instance['title'];
		echo $before_widget;
		?>
		<div class="sidebar-title"><i class="demo-icons icon-share"></i> <?php echo esc_html($title); ?> </div>
		<?php echo $this->olam_get_social_share_buttons(); ?>
		<?php
		echo $after_widget;
	}
	function update($new_instance, $old_instance)
	{
		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		return $instance;
	}
	function form($instance)
	{
		$defaults = array('title' => esc_html__('Social Share',"olam"));
		$instance = wp_parse_args((array) $instance, $defaults); ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php esc_html_e('Title','olam');?>:</label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" value="<?php echo esc_attr($instance['title']); ?>" />
		</p>
		<?php }
/**
 * Olam Function - Get Social Shares
 *
 * Get the social sharing buttons.
 * 
 */

function olam_get_social_share_buttons() {
	$html = '<div class="ya-share2" data-services="vkontakte,twitter,facebook,odnoklassniki,viber,whatsapp,telegram"></div>'; 
	return $html;
}
}