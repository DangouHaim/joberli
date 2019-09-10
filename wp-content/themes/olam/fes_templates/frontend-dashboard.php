<?php
$vendor_announcement = EDD_FES()->helper->get_option( 'fes-dashboard-notification', '' );
if ( $vendor_announcement ) {
	?>
	<div id="fes-vendor-announcements">
		<?php echo apply_filters( 'fes_dashboard_content', do_shortcode( $vendor_announcement ) ); ?>
	</div>
	<?php
}
?>

<div class="fes-comments-wrap">
	<table id="fes-comments-table">
		<tr>
			<th class="col-author"><?php  _e( 'Автор', 'olam' ); ?></th>
			<th class="col-content"><?php  _e( 'Коментарий', 'olam' ); ?></th>
		</tr>
		<?php echo EDD_FES()->dashboard->render_comments_table( 10 ); ?>
	</table>
</div>