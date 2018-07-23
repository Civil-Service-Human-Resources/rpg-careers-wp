<?php

class acf_rpg_team_helpers {

	public static function get_defaults() {
		return array(
			'choices'       =>	array(),
			'default_value' => '',
			'return_format' => 'array',
			'placeholder'   => __('Select a team', 'acf-rpg-team'),
		);
	}

	public static function render_field( $field ) {

		$teams = self::get_teams();

		$field['choices'] = $teams;

		$attrs = array(
			'id'				=> $field['id'],
			'class'				=> $field['class'],
			'name'				=> $field['name'],
			'data-placeholder'	=> $field['placeholder'],
		);

		$attrs['class'] .=  ' acf-rpg-team';
		$field['value'] = is_array($field['value']) ? array_map('trim', $field['value']) : trim($field['value']);

		?>
		<select <?php echo implode( ' ', array_map(function($val, $key) { return sprintf( '%1$s="%2$s"', $key, esc_attr($val) ); }, $attrs, array_keys( $attrs ))); ?>>
			<option value=""><?php esc_html_e('---Select a team---', 'acf-rpg-team'); ?></option>
			<?php if( is_array($field['choices']) ): ?>
			<?php foreach($field['choices'] as $code => $team): ?>
			<option value="<?php echo esc_attr($code); ?>"<?php if($code == $field['value']): ?> selected="selected"<?php endif; ?>><?php echo esc_html($team); ?></option>
			<?php endforeach; ?>
			<?php endif; ?>
		</select>
		<?php
	}

	public static function get_teams() {
		$teams = array();
		$currentteams = get_terms(array('taxonomy' => 'content_team','hide_empty' => false, 'parent' => 0));
		
        foreach($currentteams as $team) {
			//EXCLUDE BACK END TEAMS
			$back_end = get_term_meta($team->term_id, 'content_team_back_end_only', true);

			if($back_end === ''){
				$teams[$team->term_id] = $team->name;
			}

        }

		return $teams;
	}
}
?>