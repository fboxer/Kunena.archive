<?php
/**
 * @version		$Id: $
 * @package		Kunena
 * @subpackage	com_kunena
 * @copyright	Copyright (C) 2008 - 2009 Kunena Team. All rights reserved.
 * @license		GNU General Public License <http://www.gnu.org/copyleft/gpl.html>
 * @link		http://www.kunena.com
 */

defined('_JEXEC') or die;
?>
<?php if (isset($this->path)): ?>
<!-- Pathway -->
<div class="pathway">
<span class="path-element-first"><?php echo JHtml::_('klink.kunena', 'atag', 'Kunena'); ?></span>
<?php
foreach ($this->path as &$category):
	if (!$category->parent):
?>
<span class="path-element"><?php echo JHtml::_('klink.categories', 'atag', $category->id, $this->escape($category->name), $this->escape($category->name)); ?></span>
<?php else: ?>
<span class="path-element"><?php echo JHtml::_('klink.category', 'atag', $category->id, $this->escape($category->name), $this->escape($category->name)); ?></span>
<?php
	endif;
endforeach;
?>
<?php // <br /> ?>
<span class="path-element-last"><?php if (isset($this->messages[0])) echo $this->escape($this->messages[0]->subject); ?></span>
<?php // <div class="path-element-users">(X viewing)&nbsp;(X) Guest</div> ?>
</div>
<!-- / Pathway -->
<?php endif; ?>