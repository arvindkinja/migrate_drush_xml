<?php
/**
 * @file Contains the code to generate the custom drush commands.
 */
use Drupal\migrate_drush_xml\XmlImportController;
/**
 * Implements hook_drush_command().
 */
function migrate_drush_xml_drush_command() {
  $items = array();
  $items['import'] = [
    'description' => 'Import node',
    'arguments' => [
      'name' => 'Import node from xml file',
    ],
    'drupal dependencies' => ['custom_drush_command'],
    'aliases' => ['import'],
  ];
  return $items;
}

/**
 * Call back function drush_migrate_drush_xml_import()
 * The call back function name in the  following format
 *   drush_{module_name}_{item_id_for_command}()
 */
function drush_migrate_drush_xml_import($name = '') {
  $XmlImportController = new XmlImportController();
  $XmlImportController->import();
  drush_print('Import has been done!');
}
