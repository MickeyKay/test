<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       http://wordpress.org/plugins/test
 * @since      1.0.0
 *
 * @package    Test
 * @subpackage Test/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the dashboard-specific stylesheet and JavaScript.
 *
 * @package    Test
 * @subpackage Test/admin
 * @author     Mickey Kay mickey@mickeykaycreative.com
 */
class Test_Admin {

	/**
	 * The main plugin instance.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      Test    $plugin    The main plugin instance.
	 */
	private $plugin;

	/**
	 * The slug of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_slug    The slug of this plugin.
	 */
	private $plugin_slug;

	/**
	 * The display name of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The plugin display name.
	 */
	protected $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
     * Plugin options.
     *
     * @since  1.0.0
     *
     * @var    string
     */
    protected $options;

	/**
	 * The instance of this class.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Test_Admin    $instance    The instance of this class.
	 */
	private static $instance = null;

	/**
     * Creates or returns an instance of this class.
     *
     * @return    Test_Admin    A single instance of this class.
     */
    public static function get_instance( $plugin ) {

        if ( null == self::$instance ) {
            self::$instance = new self( $plugin );
        }

        return self::$instance;

    }

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @var      string    $plugin_slug       The name of this plugin.
	 * @var      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin ) {

		$this->plugin = $plugin;
		$this->plugin_slug = $this->plugin->get( 'slug' );
		$this->plugin_name = $this->plugin->get( 'name' );
		$this->version = $this->plugin->get( 'version' );
		$this->options = $this->plugin->get( 'options' );

	}

	/**
	 * Register the stylesheets for the admin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		$min_suffix = $this->plugin->get_min_suffix();
		wp_enqueue_style( "{$this->plugin_slug}-admin", plugin_dir_url( __FILE__ ) . "css/test-admin{$min_suffix}.css", array(), $this->version, 'all' );

	}

	/**
	 * Register the scripts for the admin.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		$min_suffix = $this->plugin->get_min_suffix();
		wp_enqueue_script( "{$this->plugin_slug}-admin", plugin_dir_url( __FILE__ ) . "js/test-admin{$min_suffix}.js", array( 'jquery' ), $this->version, false );

	}

	/**
	 * Add settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_page() {

		$this->settings_page = add_options_page(
			__( 'Test', 'test' ), // Page title
			__( 'Test', 'test' ), // Menu title
			'manage_options', // Capability
			$this->plugin_slug, // Page ID
			array( $this, 'do_settings_page' ) // Callback
		);

	}

	/**
	 * Output contents of settings page.
	 *
	 * @since 1.0.0
	 */
	public function do_settings_page() {

		?>
		<div class="wrap <?php echo $this->plugin_slug; ?>-settings">
	        <h1><?php echo $this->plugin_name; ?></h1>
	        <?php

			// Set up tab/settings.
			$tab_base_url = "?page={$this->plugin_slug}";
			$active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : null;

			?>
	        <h2 class="nav-tab-wrapper">
	        	<a href="<?php echo $tab_base_url; ?>&tab=tab-1" class="nav-tab <?php echo ( ! $active_tab || 'tab-1' == $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Tab 1', 'test' ); ?></a>
	        	<a href="<?php echo $tab_base_url; ?>&tab=tab-2" class="nav-tab <?php echo ( 'tab-2' == $active_tab ) ? 'nav-tab-active' : ''; ?>"><?php esc_html_e( 'Tab 2', 'test' ); ?></a>
	        </h2>
			<form action='options.php' method='post'>
				<?php

				// Set up settings fields.
				settings_fields( $this->plugin_slug );

				if ( ( ! $active_tab || 'tab-1' == $active_tab ) ) {
					$this->output_tab_settings( 'tab-1' );
				} elseif ( 'tab-2' == $active_tab ) {
					$this->output_tab_settings( 'tab-2' );
				}

				submit_button();
				?>
			</form>
		</div>
		<?php

	}

	/**
	 * Add settings fields to the settings page.
	 *
	 * @since 1.0.0
	 */
	public function add_settings_fields() {

		register_setting(
			$this->plugin_slug, // Option group
			$this->plugin_slug, // Option name
			array( $this, 'validate_settings' ) // Sanitization
		);

		/**
		 * Tab 1 Settings
		 */
		$tab = 'tab-1';
		add_settings_section(
			$tab, // Section ID
			null, // Title
			null, // Callback
			"{$this->plugin_slug}-{$tab}" // Page
		);

		$id = 'field_1';
		add_settings_field(
			$id, // ID
			__( 'Option 1', 'test' ), // Title
			array( $this, 'render_checkbox' ), // Callback
			"{$this->plugin_slug}-{$tab}", // Page
			$tab, // Section
			array( // Args
				'id'          => $id,
				'description' => __( 'This is option 1.', 'test' ),
				'save_null'   => false,
			)
		);

		/**
		 * Tab 2 Settings
		 */
		$tab = 'tab-2';
		add_settings_section(
			$tab, // Section ID
			null, // Title
			null, // Callback
			"{$this->plugin_slug}-{$tab}" // Page
		);

		$id = 'field_2';
		add_settings_field(
			$id, // ID
			__( 'Option 2', 'test' ), // Title
			array( $this, 'render_checkbox' ), // Callback
			"{$this->plugin_slug}-{$tab}", // Page
			$tab, // Section
			array( // Args
				'id'          => $id,
				'description' => __( 'This is option 2.', 'test' ),
				'save_null'   => false,
			)
		);

	}

	/**
	 * Output appropriate tab settings.
	 *
	 * Output all tab settings on each tab, so that we don't overwrite
	 * any option values as blank, however simply hide those options
	 * that aren't meant to be exposed on this tab.
	 *
	 * @since 1.0.0
	 *
	 * @param string $tab Tab ID.
	 */
	public function output_tab_settings( $tab ) {

		global $wp_settings_fields, $wp_settings_sections;

		$page = "{$this->plugin_slug}-{$tab}";

		// Get settings tabs pages for this plugin.
		$settings_pages = array_keys( $wp_settings_fields );
		$plugin_settings_pages = array_filter( $settings_pages, array( $this, 'filter_plugin_settings_pages' ) );

		foreach ( $plugin_settings_pages as $settings_page ) {


			if ( $page == $settings_page ) {
				do_settings_sections( $settings_page );
			} else {
				echo '<div class=" tab hidden">';
				do_settings_sections( $settings_page );
				echo '</div>';
			}
		}


	}

	/**
	 * Filter the plugin settings pages array to only include settings
	 * tabs.
	 *
	 * @since 1.0.0
	 *
	 * @param string $settings_page Settings page slug.
	 *
	 * @return int true|false Whether or not the settings page is a tabbed settings page.
	 */
	public function filter_plugin_settings_pages( $settings_page ) {
		return strpos( $settings_page, "{$this->plugin_slug}-tab-" ) === 0;
	}

	/**
	 * Validate saved settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array   $input Saved inputs.
	 *
	 * @return array Update settings.
	 */
	public function validate_settings( $input ) {

		$new_input = $input;

		return $new_input;

	}

	/*===========================================
	 * Field rendering functions.
	===========================================*/

	/**
	 * Render checkbox input for settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Args from add_settings_field().
	 */
	public function render_checkbox( $args ) {

		// Set up option name and value.
		if ( isset( $args['secondary_id'] ) ) {
			$option_name = $this->get_option_name( $args['id'], $args['secondary_id'] );
			$option_value = $this->get_option_value( $args['id'], $args['secondary_id'] );
		} else {
			$option_name = $this->get_option_name( $args['id'] );
			$option_value = $this->get_option_value( $args['id'] );
		}

		$checked = isset( $option_value ) ? $option_value : null;

		// Get post type REST info.
		if ( isset ( $args['post_type_object'] ) ) {

			$post_type_object = $args['post_type_object'];
			$init_rest_base = isset( $post_type_object->rest_base ) ? $post_type_object->rest_base : '';

			// Get checked value based on saved value, or existing value if option doesn't exist.
			if ( isset( $option_value ) ) {
				$checked = $option_value;
			} elseif ( $init_rest_base ) {
				$checked = true;
			}

		}

		// Render hidden input set to 0 to save unchecked value as non-null.
		if ( empty( $args['save_null'] ) ) {

			printf(
				'<input type="hidden" value="0" id="%s" name="%s"/>',
				"{$option_name}-no-value",
				$option_name
			);

		}

		printf(
			'<label for="%s"><input type="checkbox" value="1" id="%s" name="%s" %s/>&nbsp;%s</label>',
			$option_name,
			$option_name,
			$option_name,
			checked( 1, $checked, false ),
			! empty( $args['description'] ) ? '<span class="rae-description">' . esc_html( $args['description'] ) . '</span>': ''
		);

	}

	/**
	 * Render text input for settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Args from add_settings_field().
	 */
	public function render_text_input( $args ) {

		// Set up option name and value.
		if ( isset( $args['secondary_id'] ) ) {
			$option_name = $this->get_option_name( $args['id'], $args['secondary_id'] );
			$option_value = $this->get_option_value( $args['id'], $args['secondary_id'] );
		} else {
			$option_name = $this->get_option_name( $args['id'] );
			$option_value = $this->get_option_value( $args['id'] );
		}

		$value = $option_value;

		// Get post type REST info.
		if ( ! $value && isset ( $args['post_type_object'] ) ) {

			$post_type_object = $args['post_type_object'];
			$rest_base = isset( $post_type_object->rest_base ) ? $post_type_object->rest_base : '';

			// Auto-generate initial rest_base if not already set.
			if ( ! $rest_base ) {
				$rest_base = sanitize_title_with_dashes( $args['post_type_object']->labels->name );
			}

			$value = $rest_base;

		}

		printf(
			'%s<input type="text" value="%s" id="%s" name="%s" class="regular-text %s"/>%s',
			! empty( $args['sub_heading'] ) ? '<b>' . $args['sub_heading'] . '</b><br />' : '',
			$value,
			$option_name,
			$option_name,
			! empty( $args['class'] ) ? $args['class'] : '',
			! empty( $args['description'] ) ? sprintf( '<br /><p class="description" for="%s">%s</p>', $option_name, esc_html( $args['description'] ) ): ''
		);

	}

	/**
	 * Render select for settings.
	 *
	 * @since 1.0.0
	 *
	 * @param array $args Args from add_settings_field().
	 */
	public function render_select( $args ) {

		if ( ! isset( $args['options'] ) ) {
			return;
		}

		// Set up option name and value.
		if ( isset( $args['secondary_id'] ) ) {
			$option_name = $this->get_option_name( $args['id'], $args['secondary_id'] );
			$option_value = $this->get_option_value( $args['id'], $args['secondary_id'] );
		} else {
			$option_name = $this->get_option_name( $args['id'] );
			$option_value = $this->get_option_value( $args['id'] );
		}

		printf(
			'<select id="%s" name="%s" %s"/>',
			$option_name,
			$option_name,
			! empty( $args['class'] ) ? $args['class'] : ''
		);

		// Output each option.
		foreach ( $args['options'] as $option_slug => $option_name ) {

			printf(
				'<option %s value="%s"/>%s</option>',
				selected( $option_value, $option_slug, false ),
				$option_slug,
				esc_html( $option_name )
			);

		}

		echo '</select>';

	}

	/*===========================================
	 * Helper functions.
	===========================================*/

	/**
	 * Get option name based on primary and secondary id's.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $option_id    Primary option id.
	 * @param string  $secondary_id Secondary option id.
	 *
	 * @return string Option name.
	 */
	private function get_option_name( $option_id, $secondary_id = '' ) {
		if ( $secondary_id ) {
			return sprintf( '%s[%s][%s]', $this->plugin_slug, $option_id, $secondary_id );
		} else {
			return sprintf( '%s[%s]', $this->plugin_slug, $option_id );
		}
	}

	/**
	 * Get option value based on primary and secondary id's.
	 *
	 * @since 1.0.0
	 *
	 * @param string  $option_id    Primary option id.
	 * @param string  $secondary_id Secondary option id.
	 *
	 * @return mixed Option value.
	 */
	private function get_option_value( $option_id, $secondary_id = '' ) {

		if ( $secondary_id ) {
			return isset( $this->options[ $option_id ][ $secondary_id ] ) ? $this->options[ $option_id ][ $secondary_id ] : null;
		} else {
			return isset( $this->options[ $option_id ] ) ? $this->options[ $option_id ] : null;
		}

	}

}
