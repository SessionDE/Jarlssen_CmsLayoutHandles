<?php

/**
 * Modify cms_page table to add a page type field; manually set all existing pages to 0 (standard page).
 */
$installer = $this;
$cmsTable = $installer->getTable( 'cms/page' );
$installer->getConnection( )->addColumn( $cmsTable, 'page_type', "INTEGER DEFAULT 0" );
$installer->getConnection()->query( "UPDATE {$installer->getTable('cms/page')} SET page_type = 0" );