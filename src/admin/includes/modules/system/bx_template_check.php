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
       return array('text' => '<div style="text-align: center;">'.xtc_button(BUTTON_SAVE).xtc_button_link(BUTTON_CANCEL, xtc_href_link(FILENAME_MODULE_EXPORT, 'set='.$_GET['set'].'&module='.$this->code))."</div>");
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
																												'True', 
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

  public function custom(): void {
    global $messageStack;

    // Moduldateien dürfen erst entfernt werden, nachdem das Modul logisch
    // aus dem System abgemeldet wurde.
    if ($this->check()) {
      $messageStack->add_session(MODULE_BX_TEMPLATE_CHECK_TEXT_UNINSTALL_FIRST, 'error');
      return;
    }

    $delete = (isset($_GET['delete']) && $_GET['delete'] === 'true');

    if ($delete !== true) {
      return;
    }

    $result = true;
      
    // Diese Liste enthält die in der Live-Installation ausgerollten Dateien.
    $dirs_and_files   = array();
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'includes/extra/css/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'includes/extra/functions/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'includes/extra/javascript/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'includes/extra/menu/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'images/icons/heading/bx_template_check.png';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'images/icons/folder_closed.png';
    $dirs_and_files[] = DIR_FS_CATALOG.DIR_ADMIN.'images/icons/folder_open.png';
    $dirs_and_files[] = DIR_FS_CATALOG.'lang/german/modules/system/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.'lang/english/modules/system/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.'lang/german/extra/admin/bx_template_check.php';
    $dirs_and_files[] = DIR_FS_CATALOG.'lang/english/extra/admin/bx_template_check.php';
      
    // Dateien löschen
    foreach ($dirs_and_files as $dir_or_file) {
    if (!$this->rrmdir($dir_or_file)) {
      $messageStack->add_session($dir_or_file.MODULE_BX_TEMPLATE_CHECK_TEXT_COULD_NOT_BE_DELETED, 'error');
      $result = false;
    }
    }
      
    if ($result === true) {
      $messageStack->add_session(MODULE_BX_TEMPLATE_CHECK_TEXT_SUCCSESSFULLY_REMOVED, 'success');
      } else {
      $messageStack->add_session(MODULE_BX_TEMPLATE_CHECK_TEXT_DELETE_FAILED, 'error');
      }
      
    // Datei selbst löschen
    unlink(DIR_FS_CATALOG.DIR_ADMIN.'includes/modules/system/bx_template_check.php');
  }

  private function rrmdir(string $dir): bool {
    if (is_dir($dir)) {
      $objects = scandir($dir);
      foreach ($objects as $object) {
        if ($object != "." && $object != "..") {
          if (filetype($dir."/".$object) == "dir") {
            $this->rrmdir($dir."/".$object);
          } else {
            unlink($dir."/".$object);
          }
        }
      }
      reset($objects);
      rmdir($dir);
      return true;
    } elseif (is_file($dir)) {
      unlink($dir);
      return true;
    }
    return false;
  }

}
