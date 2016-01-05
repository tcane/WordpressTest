<?php

/**
 * Plugin Name: Custom Plugin
 * Description: Custom plugin que permite ABM de datos en la tabla custom_plugin
 * Version: 0.1
 * Author: Tomás Cané
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


if( !class_exists( 'CustomPlugin' ) ) :

	define('CP_TODO_FILE', __FILE__);
	define('CP_TODO_PATH', plugin_dir_path(__FILE__));

  final class CustomPlugin {
	 
	 /**
	 * @var una instancia
	 */
	 protected static $instance = null;
	  
	 /**
	 * CustomPlugin Instance
	 *
	 * Singleton
	 *
	 * @return CustomPlugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		register_activation_hook( __FILE__, array( $this, 'install' ) );
		
		add_action( 'admin_menu', array( $this, 'cp_admin_menu' ) );
	}  
	  
	/**
	* Install
	* 
	* @return 
	*/
	  public function install() {
   		global $wpdb;
		global $jal_db_version;
   		$tabla = $wpdb->prefix . "custom_plugin"; 
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $tabla (
											id smallint(5) NOT NULL AUTO_INCREMENT,
											valor varchar(55) DEFAULT '' NOT NULL,
											descripcion text NOT NULL,
											fecha datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
											UNIQUE KEY id (id)
											) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		  		  
		// setear versión DB  
		add_option( 'jal_db_version', $jal_db_version );  
		  
	  }
	  
	  /** 
		* Menu del Admin
		*
		*/
		public function cp_admin_menu() {
			add_menu_page( 'Pagina Custom Plugin', 'Custom Plugin', 'manage_options', 'custom-plugin-identifier', array( $this, 'admin_listar_datos'), plugins_url( 'custom-plugin/assets/images/icono2.png' ), 4 );
			add_submenu_page( 'custom-plugin-identifier', 'Agregar datos', 'Agregar datos', 'manage_options', 'agregar-custom-plugin-id', array( $this, 'admin_agregar_datos') );
		}
	  
	  	/**
		* Admin listar datos
		*
		*/
		public function admin_listar_datos() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			if (isset($_GET['elid'])) {
				global $wpdb;
				$tabla = $wpdb->prefix . "custom_plugin"; 
				$wpdb->delete( $tabla, array( 'id' => $_GET['elid'] ) );
			}
			global $wpdb;
			$tabla = $wpdb->prefix . "custom_plugin"; 
			$lista = $wpdb->get_results("SELECT id, valor, descripcion, fecha FROM $tabla ORDER BY fecha LIMIT 10");
			include('admin/listar.php');
		}
	  
	  	/**
		* Admin agregar datos
		*
		*/
		public function admin_agregar_datos() {
			if ( !current_user_can( 'manage_options' ) )  {
				wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
			}
			global $wpdb;
			$tabla = $wpdb->prefix . "custom_plugin"; 
			$insert = false;
			if (isset($_POST['cp_valor']) && !empty($_POST['cp_valor'])
				&& isset($_POST['cp_descripcion']) && !empty($_POST['cp_descripcion'])) {
				$valor = $_POST['cp_valor'];
				$descripcion = $_POST['cp_descripcion'];
				
				if (isset($_POST['ecpid']) && !empty($_POST['ecpid'])) {
					$wpdb->update($tabla, array( 
											'valor' => $valor,
											'descripcion' => $descripcion
										), 
										array( 'id' => $_POST['ecpid'] ));
				} else {
					if($wpdb->insert($tabla, array('valor' => $valor, 'descripcion' => $descripcion, 'fecha' => date('Y-m-d H:i:s')))) {
						$insert = true;
					}			
				}
				
			}
			$edit_id = '';
			if (isset($_GET['cpid'])) {
				$edit_id = $_GET['cpid'];
				$editData = $wpdb->get_row( "SELECT valor, descripcion FROM $tabla WHERE id = " . $edit_id );
			}
			include('admin/agregar.php');
		}
  }

endif; // class exists

/**
 * Returns the main instance of WC to prevent the need to use globals.
 *
 * @return WooCommerce
 */
function CPInstance() {
	return CustomPlugin::instance();
}
// Global for backwards compatibility.
$GLOBALS['CustomPlugin'] = CPInstance();