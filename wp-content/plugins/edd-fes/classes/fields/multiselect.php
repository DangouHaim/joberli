<?php
class FES_Multiselect_Field extends FES_Field {
	/** @var string Version of field */
	public $version = '1.0.0';	
	
	/** @var bool For 3rd parameter of get_post/user_meta */
	public $single = true;

	/** @var array Supports are things that are the same for all fields of a field type. Like whether or not a field type supports jQuery Phoenix. Stored in obj, not db. */
	public $supports = array(
		'multiple'    => true,
		'is_meta'     => true,  // in object as public (bool) $meta;
		'forms'       => array(
			'registration'     => true,
			'submission'       => true,
			'vendor-contact'   => true,
			'profile'          => true,
			'login'            => true,
		),
		'position'    => 'custom',
		'permissions' => array(
			'can_remove_from_formbuilder' => true,
			'can_change_meta_key'         => true,
			'can_add_to_formbuilder'      => true,
		),
		'template'   => 'multiselect',
		'title'       => 'Multiselect',
		'phoenix'    => true,
	);

	/** @var array Characteristics are things that can change from field to field of the same field type. Like the placeholder between two text fields. Stored in db. */
	public $characteristics = array(
		'name'        => '',
		'template'   => 'multiselect',
		'public'      => true,
		'required'    => false,
		'label'       => '',
		'css'         => '',
		'default'     => '',
		'size'        => '',
		'help'        => '',
		'placeholder' => '',
		'first'       => '- Выбрать -',
		'selected'    => '',
		'options'     => '',
	);


	public function set_title() {
		$title = _x( 'Multiselect', 'FES Field title translation', 'edd_fes' );
		$title = apply_filters( 'fes_' . $this->name() . '_field_title', $title );
		$this->supports['title'] = $title;		
	}

	/** Returns the HTML to render a field in admin */
	public function render_field_admin( $user_id = -2, $readonly = -2 ) {
		if ( $user_id === -2 ) {
			$user_id = get_current_user_id();
		}

		if ( $readonly === -2 ) {
			$readonly = $this->readonly;
		}

		$user_id   = apply_filters( 'fes_render_multiselect_field_user_id_admin', $user_id, $this->id );
		$readonly  = apply_filters( 'fes_render_multiselect_field_readonly_admin', $readonly, $user_id, $this->id );
		$value     = $this->get_field_value_admin( $this->save_id, $user_id, $readonly );

		if ( $this->save_id > 0 ) {
			$selected = $this->get_meta( $this->save_id, $this->name(), $this->type );
		} else {
			$selected = ( $this->characteristics && isset( $this->characteristics['selected'] ) ) ? $this->characteristics['selected'] : '';
		}

		if ( ! is_array( $selected ) ){
			$one = $selected;
			$selected = array();
			$selected[0] = $one;
		}

		$multi     = ' multiple="multiple"';
		$data_type = 'multiselect';
		$css       = ' class="multiselect"';
		$output        = '';
		$output     .= sprintf( '<fieldset class="fes-el %1s %2s %3s">', $this->template(), $this->name(), $this->css() );
		$output    .= $this->label( $readonly );
		ob_start(); ?>

		<div class="fes-fields">
			<select<?php echo $css; ?> name="<?php echo $this->name(); ?>[]"<?php echo $multi; ?> data-required="false" data-type="<?php echo $data_type; ?>">
				<?php if ( !empty( $this->characteristics['first'] ) ) { ?>
					<option value=""><?php echo $this->characteristics['first']; ?></option>
				<?php } ?>
				<?php
		if ( $this->characteristics['options'] && count( $this->characteristics['options'] ) > 0 ) {
			foreach ( $this->characteristics['options'] as $option ) { 
				$is_selected = in_array( $option, $selected );
				?>
				<option value="<?php echo esc_attr( $option ); ?>"<?php selected( $is_selected, true ); ?>><?php echo $option; ?></option><?php
			}
		} ?>
			</select>
		</div>
		<?php
		$output .= ob_get_clean();
		$output .= '</fieldset>';
		return $output;
	}

	/** Returns the HTML to render a field in frontend */
	public function render_field_frontend( $user_id = -2, $readonly = -2 ) {
		if ( $user_id === -2 ) {
			$user_id = get_current_user_id();
		}

		if ( $readonly === -2 ) {
			$readonly = $this->readonly;
		}

		$user_id   = apply_filters( 'fes_render_multiselect_field_user_id_frontend', $user_id, $this->id );
		$readonly  = apply_filters( 'fes_render_multiselect_field_readonly_frontend', $readonly, $user_id, $this->id );
		$value     = $this->get_field_value_frontend( $this->save_id, $user_id, $readonly );
		$required  = $this->required( $readonly );
		if ( $this->save_id > 0 ) {
			$selected = $this->get_meta( $this->save_id, $this->name(), $this->type );
		} else {
			$selected = ( $this->characteristics && isset( $this->characteristics['selected'] ) ) ? $this->characteristics['selected'] : '';
		}

		if ( ! is_array( $selected ) ){
			$one = $selected;
			$selected = array();
			$selected[0] = $one;
		}		

		$multi     = ' multiple="multiple"';
		$data_type = 'multiselect';
		$css       = ' class="multiselect"';
		$output        = '';
		$output     .= sprintf( '<fieldset class="fes-el %1s %2s %3s">', $this->template(), $this->name(), $this->css() );
		$output    .= $this->label( $readonly );
		ob_start(); ?>

		<div class="fes-fields">
			<select<?php echo $css; ?> name="<?php echo $this->name(); ?>[]"<?php echo $multi; ?> data-required="<?php echo $required; ?>" data-type="<?php echo $data_type; ?>"<?php $this->required_html5( $readonly ); ?>>
				<?php if ( !empty( $this->characteristics['first'] ) ) { ?>
					<option value=""><?php echo $this->characteristics['first']; ?></option>
				<?php } ?>
				<?php
				if ( $this->characteristics['options'] && count( $this->characteristics['options'] ) > 0 ) {
					foreach ( $this->characteristics['options'] as $option ) {
						$is_selected = in_array( $option, $selected );
						?>
						<option value="<?php echo esc_attr( $option ); ?>"<?php selected( $is_selected, true ); ?>><?php echo $option; ?></option><?php
					}
				} ?>
			</select>
		</div>
		<?php
		$output .= ob_get_clean();
		$output .= '</fieldset>';
		return $output;
	}

	public function display_field( $user_id = -2, $single = false ) {
		if ( $user_id === -2 ) {
			$user_id = get_current_user_id();
		}
		$user_id   = apply_filters( 'fes_display_' . $this->template() . '_field_user_id', $user_id, $this->id );
		$value     = $this->get_field_value_frontend( $this->save_id, $user_id );
		ob_start(); ?>

			<?php if ( $single ) { ?>
			<table class="fes-display-field-table">
			<?php } ?>

			<tr class="fes-display-field-row <?php echo $this->template(); ?>" id="<?php echo $this->name(); ?>">
				<td class="fes-display-field-label"><?php echo $this->get_label(); ?></td>
				<td class="fes-display-field-values">
					<?php
					if ( ! is_array( $value ) ) {
						$value = explode( '|', $value );
					} else {
						$value = array_map( 'trim', $value );
					}
					$value = implode( ', ', $value );
					echo $value; ?>
				</td>
			</tr>

			<?php if ( $single ) { ?>
			</table>
			<?php } ?>
		<?php
		return ob_get_clean();
	}

	public function formatted_data( $user_id = -2 ) {
		if ( $user_id === -2 ) {
			$user_id = get_current_user_id();
		}

		$user_id   = apply_filters( 'fes_formatted_' . $this->template() . '_field_user_id', $user_id, $this->id );
		$value     = $this->get_field_value_frontend( $this->save_id, $user_id );
		if ( ! is_array( $value ) ) {
			$value = explode( '|', $value );
		} else {
			$value = array_map( 'trim', $value );
		}
		$value = implode( ', ', $value );
		return $value;
	}	


	/** Returns the HTML to render a field for the formbuilder */
	public function render_formbuilder_field( $index = -2, $insert = false ) {
		$removable    = $this->can_remove_from_formbuilder();
		$first_name  = sprintf( '%s[%d][first]', 'fes_input', $index );
		$first_value = $this->characteristics['first'];
		$help        = esc_attr( __( 'First element of the select dropdown. Leave this empty if you don\'t want to show this field', 'edd_fes' ) );
		ob_start(); ?>
		<li class="custom-field multiple_multiselect">
			<?php $this->legend( $this->title(), $this->get_label(), $removable ); ?>
			<?php FES_Formbuilder_Templates::hidden_field( "[$index][template]", $this->template() ); ?>

			<?php FES_Formbuilder_Templates::field_div( $index, $this->name(), $this->characteristics, $insert ); ?>
				<?php FES_Formbuilder_Templates::public_radio( $index, $this->characteristics, $this->form_name ); ?>
				<?php FES_Formbuilder_Templates::standard( $index, $this ); ?>

				<div class="fes-form-rows">
					<label><?php _e( 'Select Text', 'edd_fes' ); ?></label>
					<input type="text" class="smallipopInput" name="<?php echo $first_name; ?>" value="<?php echo $first_value; ?>" title="<?php echo $help; ?>">
				</div>

				<div class="fes-form-rows">
					<label><?php _e( 'Options', 'edd_fes' ); ?></label>

					<div class="fes-form-sub-fields">
						<?php FES_Formbuilder_Templates::radio_fields( $index, 'options', $this->characteristics ); ?>
					</div>
				</div>
			</div>
		</li>
		<?php
		return ob_get_clean();
	}

	public function validate( $values = array(), $save_id = -2, $user_id = -2 ) {
		$name = $this->name();
		$return_value = false;
		if ( ! empty( $values[ $name ] ) && isset( $values[ $name ][0] ) && $values[ $name ][0] === '' ){
			unset( $values [ $name ][0] );
		}

		if ( ! empty( $values[ $name ] ) ) {
			// if the value is set
		} else {
			// if required but isn't present
			if ( $this->required() ) {
				$return_value = __( 'Please select at least 1 option', 'edd_fes' );
			}
		}
		return apply_filters( 'fes_validate_' . $this->template() . '_field', $return_value, $values, $name, $save_id, $user_id );
	}

	public function sanitize( $values = array(), $save_id = -2, $user_id = -2 ) {
		$name = $this->name();
		if ( ! empty( $values[ $name ] ) ) {
			if ( is_array( $values[ $name ] ) ) {
				foreach ( $values[ $name ] as $key => $string ) {
					$values[ $name ][ $key ] = trim( $string );
					$values[ $name ][ $key ] = sanitize_text_field( $values[ $name ][ $key ] );
				}
			} else {
				$values[ $name ] = trim( $values[ $name ] );
				$values[ $name ] = sanitize_text_field( $values[ $name ] );
			}
		}
		return apply_filters( 'fes_sanitize_' . $this->template() . '_field', $values, $name, $save_id, $user_id );
	}
}
