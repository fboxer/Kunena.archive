<?php
/**
 * @version $Id$
 * Kunena Component
 * @package Kunena
 *
 * @Copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

require_once (dirname ( __FILE__ ) . '/kunena.php');

/**
 * Kunena Keywords Table
 * Provides access to the #__kunena_keywords table
 */
class TableKunenaKeywords extends KunenaTable
{
	var $id = null;
	var $name = null;
	var $public_count = null;
	var $total_count = null;

	function __construct($db) {
		parent::__construct ( '#__kunena_keywords', 'id', $db );
	}

	function check() {
		$this->name = trim($this->name);
		if (!$this->name) {
			$this->setError(JText::_('COM_KUNENA_LIB_TABLE_KEYWORDS_ERROR_EMPTY'));
		}
		return ($this->getError () == '');
	}
}