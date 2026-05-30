<?php
/* -----------------------------------------------------------------------------------------
   $Id: admin/includes/modules/system/bx-template-check.php 1000 2022-05-5221 13:00:00Z benax $

	 modified eCommerce Shopsoftware
   http://www.modified-shop.org

   Copyright (c) 2009 - 2013 [www.modified-shop.org]
   -----------------------------------------------------------------------------------------
   Released under the GNU General Public License
   ---------------------------------------------------------------------------------------*/

defined( '_VALID_XTC' ) or die( 'Direct Access to this location is not allowed.' );

class bx_template_check {
	public string $code;
	public string $version;
	public string $development_status; // 'p' = production ready, 'd' = in development
	public string $title;
	public string $description;
	public int $sort_order;
	public bool $enabled;
	private bool $_check;

  public function __construct() {
		$this->code        = 'bx_template_check';
    $this->version     = '0.5.0';
    $this->title       = MODULE_BX_TEMPLATE_CHECK_TEXT_TITLE;
    $this->description = MODULE_BX_TEMPLATE_CHECK_DESC;
    $this->sort_order  = defined('MODULE_BX_TEMPLATE_CHECK_SORT_ORDER') ? MODULE_BX_TEMPLATE_CHECK_SORT_ORDER : 0;
		$this->enabled     = (defined('MODULE_BX_TEMPLATE_CHECK_STATUS') && MODULE_BX_TEMPLATE_CHECK_STATUS === 'True');
		$this->development_status = '';
   }

  /**
   * Keine direkte Verarbeitung notwendig
   * Modified Framework handled Form-Submission automatisch
   */
  public function process() {
    // Keine Implementierung erforderlich
  }

  public function display(): array {
		return array();
	}

  public function check(): bool {
    if (!isset($this->_check)) {
      $check_query = xtc_db_query("SELECT configuration_value 
                                     FROM ".TABLE_CONFIGURATION."
                                    WHERE configuration_key = 'MODULE_BX_TEMPLATE_CHECK_STATUS'");
      $this->_check = xtc_db_num_rows($check_query);
    }
    return $this->_check;
  }
    
  public function install(): void {
		xtc_db_query("ALTER TABLE ".TABLE_ADMIN_ACCESS." ADD bx_template_check INTEGER(1)");
		xtc_db_query("UPDATE ".TABLE_ADMIN_ACCESS." SET bx_template_check = 1");

		xtc_db_query("INSERT INTO ".TABLE_CONFIGURATION." ( configuration_id,
		                                                    configuration_key, 
																												configuration_value, 
																												configuration_group_id, 
																												sort_order, 
																												date_added, 
																												use_function, 
																												set_function )
																							 VALUES ( '', 
																							          'MODULE_BX_TEMPLATE_CHECK_STATUS',
																												'true', 
																												'6', 
																												'1', 
																												now(), 
																												'', 
																												'xtc_cfg_select_option(array(\'True\', \'False\'), ')");		
	}

  public function remove(): void {
    xtc_db_query("DELETE FROM ".TABLE_CONFIGURATION." WHERE configuration_key in ('".implode("', '", $this->keys())."')");
		xtc_db_query("ALTER TABLE ".TABLE_ADMIN_ACCESS." DROP COLUMN IF EXISTS bx_template_check");    
  }

  public function keys(): array {
    $key = array(
      'MODULE_BX_TEMPLATE_CHECK_STATUS',
    );
    return $key;
  }
}
