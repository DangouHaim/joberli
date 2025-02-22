<?php
class FES_User_Bio_Field extends FES_Field {
	/** @var string Version of field */
	public $version = '1.0.0';	
	
	/** @var bool For 3rd parameter of get_post/user_meta */
	public $single = true;

	/** @var array Supports are things that are the same for all fields of a field type. Like whether or not a field type supports jQuery Phoenix. Stored in obj, not db. */
	public $supports = array(
		'multiple'    => false,
		'is_meta'         => false,  // in object as public (bool) $meta;
		'forms'       => array(
			'registration'     => true,
			'submission'       => false,
			'vendor-contact'   => false,
			'profile'          => true,
			'login'            => false,
		),
		'position'    => 'specific',
		'permissions' => array(
			'can_remove_from_formbuilder' => true,
			'can_change_meta_key'         => true,
			'can_add_to_formbuilder'      => true,
		),
		'template'   => 'user_bio',
		'title'       => 'User Bio',
		'phoenix'    => true,
	);

	/** @var array Characteristics are things that can change from field to field of the same field type. Like the placeholder between two email fields. Stored in db. */
	public $characteristics = array(
		'name'            => 'description',
		'template'        => 'user_bio',
		'public'          => true,
		'required'        => true,
		'label'          => '',
		'css'            => '',
		'default'        => '',
		'size'           => '',
		'help'            => '',
		'placeholder'     => '',
		'cols'           => '50',
		'rows'            => '8',
		'rich'            => '',
	);

	public function set_title() {
		$title = _x( 'User Bio', 'FES Field title translation', 'edd_fes' );
		$title = apply_filters( 'fes_' . $this->name() . '_field_title', $title );
		$this->supports['title'] = $title;		
	}

	/** Returns the User_Bio to render a field in admin */
	public function render_field_admin( $user_id = -2, $readonly = -2 ) {
		if ( $user_id === -2 ) {
			$user_id = get_current_user_id();
		}

		if ( $readonly === -2 ) {
			$readonly = $this->readonly;
		}
		$user_id   = apply_filters( 'fes_render_user_bio_user_id_admin', $user_id, $this->id );
		$readonly  = apply_filters( 'fes_render_user_bio_readonly_admin', $readonly, $user_id, $this->id );
		$value     = $this->get_field_value_admin( $this->save_id, $user_id, $readonly );
		$required  = $this->required( $readonly );
		$req_class = $required ? 'required' : 'rich-editor';

		$output        = '';
		$output     .= sprintf( '<fieldset class="fes-el %1s %2s %3s">', $this->template(), $this->name(), $this->css() );
		$output        .= $this->label( $readonly );
		$rows		   = isset( $this->characteristics['rows'] ) ? $this->characteristics['rows'] : 8;
		$cols		   = isset( $this->characteristics['cols'] ) ? $this->characteristics['cols'] : 50;
		ob_start(); ?>
		<div class="fes-fields">
		<?php
		if ( isset( $this->characteristics['rich'] ) && $this->characteristics['rich'] == 'yes' ) {
			$options = array( 'editor_height' => $rows, 'quicktags' => false, 'editor_class' => $req_class );
			printf( '<span class="fes-rich-validation" data-required="%s" data-type="rich" data-id="%s"></span>', $this->characteristics['required'], $this->name() );
			wp_editor( $value, $this->name(), $options );

		} elseif ( isset( $this->characteristics['rich'] ) && $this->characteristics['rich'] == 'teeny' ) {
			$options = array( 'editor_height' => $rows, 'quicktags' => false, 'teeny' => true, 'editor_class' => $req_class );
			printf( '<span class="fes-rich-validation" data-required="%s" data-type="rich" data-id="%s"></span>', $this->characteristics['required'], $this->name() );
			wp_editor( $value, $this->name(), $options );
		} else {  ?>
			<textarea class="textareafield<?php echo $this->required_class( $readonly ); ?>" id="<?php echo $this->name(); ?>" name="<?php echo $this->name(); ?>" data-required="<?php echo $required; ?>" data-type="textarea"<?php $this->required_html5( $readonly ); ?> placeholder="<?php echo esc_attr( $this->placeholder() ); ?>" rows="<?php echo esc_attr( $rows ); ?>" cols="<?php echo esc_attr( $cols ); ?>"><?php echo esc_textarea( $value ) ?></textarea>
			<?php } ?>
		</div>
		<?php
		$output .= ob_get_clean();
		$output .= '</fieldset>';
		return $output;
		return $output;
	}

	/** Returns the User_Bio to render a field in frontend */
	public function render_field_frontend( $user_id = -2, $readonly = -2 ) {
		if ( $user_id === -2 ) {
			$user_id = get_current_user_id();
		}

		if ( $readonly === -2 ) {
			$readonly = $this->readonly;
		}
		$user_id   = apply_filters( 'fes_render_user_bio_user_id_frontend', $user_id, $this->id );
		$readonly  = apply_filters( 'fes_render_user_bio_readonly_frontend', $readonly, $user_id, $this->id );
		$value     = $this->get_field_value_frontend( $this->save_id, $user_id, $readonly );
		$required  = $this->required( $readonly );
		$req_class = $required ? 'required' : 'rich-editor';

		$output        = '';
		$output     .= sprintf( '<fieldset class="fes-el %1s %2s %3s">', $this->template(), $this->name(), $this->css() );
		$output        .= $this->label( $readonly );
		$rows		   = isset( $this->characteristics['rows'] ) ? $this->characteristics['rows'] : 8;
		$cols		   = isset( $this->characteristics['cols'] ) ? $this->characteristics['cols'] : 50;
		ob_start(); ?>
		<div class="fes-fields">
		<?php
		if ( isset( $this->characteristics['rich'] ) && $this->characteristics['rich'] == 'yes' ) {
			$options = array( 'editor_height' => $rows, 'quicktags' => false, 'editor_class' => $req_class );
			wp_editor( $value, $this->name(), $options );

		} elseif ( isset( $this->characteristics['rich'] ) && $this->characteristics['rich'] == 'teeny' ) {
			$options = array( 'editor_height' => $rows, 'quicktags' => false, 'teeny' => true, 'editor_class' => $req_class );
			wp_editor( $value, $this->name(), $options );
		} else {  ?>
			<textarea class="textareafield<?php echo $this->required_class( $readonly ); ?>" id="<?php echo $this->name(); ?>" name="<?php echo $this->name(); ?>" data-required="<?php echo $required; ?>" data-type="textarea"<?php $this->required_html5( $readonly ); ?> placeholder="<?php echo esc_attr( $this->placeholder() ); ?>" rows="<?php echo esc_attr( $rows ); ?>" cols="<?php echo esc_attr( $cols ); ?>"><?php echo esc_textarea( $value ) ?></textarea>
			<?php } ?>
		</div>
		<?php
		$output .= ob_get_clean();
		$output .= '</fieldset>';
		return $output;
	}

	/** Returns the User_Bio to render a field for the formbuilder */
	public function render_formbuilder_field( $index = -2, $insert = false ) {
		$removable = $this->can_remove_from_formbuilder();
		ob_start(); ?>
		<li class="user_bio">
			<?php $this->legend( $this->title(), $this->get_label(), $removable ); ?>
			<?php FES_Formbuilder_Templates::hidden_field( "[$index][template]", $this->template() ); ?>
			<?php FES_Formbuilder_Templates::field_div( $index, $this->name(), $this->characteristics, $insert ); ?>
				<?php FES_Formbuilder_Templates::public_radio( $index, $this->characteristics, $this->form_name ); ?>
				<?php FES_Formbuilder_Templates::standard( $index, $this ); ?>
				<?php FES_Formbuilder_Templates::common_text( $index, $this->characteristics ); ?>
			</div>
		</li>
		<?php
		return ob_get_clean();
	}

	public function validate( $values = array(), $save_id = -2, $user_id = -2 ) {
		$name = $this->name();
		$return_value = false;
		if ( !empty( $values[ $name ] ) ) {
			// no specific validation
		} else {
			// if required but isn't present
			if ( $this->required() ) {
				$return_value = __( 'Заполните это поле', 'edd_fes' );
			}
		}
		return apply_filters( 'fes_validate_' . $this->template() . '_field', $return_value, $values, $name, $save_id, $user_id );
	}

	public function sanitize( $values = array(), $save_id = -2, $user_id = -2 ) {
		$name = $this->name();
		if ( !empty( $values[ $name ] ) ) {
			$values[ $name ] = trim( $values[ $name ] );
			$values[ $name ] = wp_kses( $values[ $name ], fes_allowed_html_tags() );
		}
		return apply_filters( 'fes_sanitize_' . $this->template() . '_field', $values, $name, $save_id, $user_id );
	}
}
