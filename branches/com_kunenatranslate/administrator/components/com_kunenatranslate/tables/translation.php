<?php
/**
 * @version $Id$
 * Kunena Translate Component
 * 
 * @package	Kunena Translate
 * @Copyright (C) 2010 www.kunena.com All rights reserved
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.com
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

class TableTranslation extends JTable
{
	/** unique key
	 * @var labelid
	 */
	var $labelid = null;
	/** Language
	 * @var lang
	 */
	var $lang = null;
	/** translation
	 * @var translation 
	 */
	var $translation = null;
	/** time
	 * @var time
	 */
	var $time = null;
	
	function __construct(& $db){
		parent::__construct('#__kunenatranslate_translation', 'labelid', $db);
	}
	
	function loadTranslations($id=null,$lang=null, $client=null, $extension=null){
		$db =& $this->getDBO();
		$where = null;
		if(!empty($id) && is_array($id)){
			$n = count($id);
			$where = ' WHERE ';
			foreach ($id as $k=>$v){
				$where .= 'a.labelid='.$v;
				if($n>1 && $k<$n-1) $where .= ' OR ';
			}
		}elseif (!empty($id) && is_int($id)){
			$where = ' WHERE a.labelid='.$id;
		}
		if (!empty($lang) && is_string($lang)){
			if(!empty($where)) $where .= ' AND ';
			else $where = ' WHERE ';
			$where .= " a.lang='{$lang}'";
		}
		if(empty($client)){
			$client = $this->client;
		}
		if (!empty($client) && is_string($client)){
			if(!empty($where)) $where .= ' AND ';
			else $where = ' WHERE ';
			$where .= " b.client='{$client}'";
		}
		if(empty($extension)){
			$extension = $this->extension;
		}
		if (!empty($extension) && is_int($extension)){
			if(!empty($where)) $where .= ' AND ';
			else $where = ' WHERE ';
			$where .= " b.extension='{$extension}'";
		}
		$query = "SELECT a.*, b.label
				FROM {$this->_tbl} AS a
				LEFT JOIN #__kunenatranslate_label AS b
				ON a.labelid=b.id  
				{$where}";
		$db->setQuery($query);
		$result = $db->loadObjectlist();
		if ($result) {
			return $result;
		}
		else
		{
			$this->setError( $db->getErrorMsg() );
			return false;
		}
	}
	
	function store($data, $label= null, $client= null, $extension= null){
		$db = &$this->getDBO();
		if(!empty($label)){
			$query = "SELECT id FROM #__kunenatranslate_label WHERE label='{$label}'";
			$db->setQuery($query);
			$res = $db->loadAssoc();
			if(!empty($res)){
				$this->setError('Label already exist');
				return false;
			}
			$query = "INSERT INTO #__kunenatranslate_label (label, client, extension)
					VALUES ('{$label}','{$client}','{$extension}')";
			$db->setQuery($query);
			if(!$db->query()){
				$this->setError($this->_db->getErrorMsg());
				return false;
			}
		}
		$isin = false;
		foreach ($data as $kl=>$value) {
			foreach ($value as $k=>$val) {
				if(isset($val['insert']) && !empty($val['insert'])){
					if(!isset($insert)) $insert = "INSERT INTO {$this->_tbl} (labelid,lang,translation) VALUES ";
					if($isin == true) $insert .= " , "; 
					if($k == 0) $k = $db->insertid();
					$insert .= "({$k},'{$kl}','{$db->getEscaped($val['insert'])}')";
					$isin = true;
				}elseif(isset($val['update']) && !empty($val['update'])){ 
					$update = "UPDATE {$this->_tbl} SET translation='{$db->getEscaped($val['update'])}', time=''
								WHERE labelid={$k}
								AND lang='{$kl}'";
					$db->setQuery($update);
					if(!$db->query()){
						$this->setError($this->_db->getErrorMsg());
						return false;
					};
				}
			};
		}
		if(isset($insert)){
			$db->setQuery($insert);
			if(!$db->query()){
				$this->setError($this->_db->getErrorMsg());
				return false;
			};
		}
		return true;
	}
}