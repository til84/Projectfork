<?php
/**
 * @package      pkg_projectfork
 * @subpackage   com_pfrepo
 *
 * @author       Tobias Kuhn (eaxs)
 * @copyright    Copyright (C) 2006-2013 Tobias Kuhn. All rights reserved.
 * @license      http://www.gnu.org/licenses/gpl.html GNU/GPL, see LICENSE.txt
 */

defined('_JEXEC') or die();

$allowed      = PFrepoHelper::getAllowedFileExtensions();
$user         = JFactory::getUser();
$config       = JComponentHelper::getParams('com_pfrepo');
$filter_admin = $config->get('filter_ext_admin');
$is_admin     = $user->authorise('core.admin');

// Restrict file extensions?
$txt_upload = '';

if ($is_admin && !$filter_admin) $allowed = array();

if (count($allowed)) {
    $txt_upload = JText::_('COM_PROJECTFORK_UPLOAD_ALLOWED_EXT') . ' ' . implode(', ', $allowed);
}

JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('pfhtml.script.form');
?>
<script type="text/javascript">
Joomla.submitbutton = function(task)
{
    if (task == 'file.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
        Joomla.submitform(task, document.getElementById('item-form'));
    }
    else {
        alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
    }
}
</script>
<form action="<?php echo JRoute::_('index.php?option=com_pfrepo&view=file&id=' . (int) $this->item->id . '&layout=edit'); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">

    <div class="width-60 fltlft span7">
        <fieldset class="adminform">
            <legend><?php echo empty($this->item->id) ? JText::_('COM_PROJECTFORK_NEW_FILE') : JText::_('COM_PROJECTFORK_EDIT_FILE'); ?></legend>
            <ul class="adminformlist unstyled">
                <li><?php echo $this->form->getLabel('dir_id') . $this->form->getInput('dir_id'); ?></li>
                <li>
                    <?php echo $this->form->getLabel('file') . $this->form->getInput('file'); ?>
                    <?php if (count($allowed)) : ?>
                        <div class="small"><?php echo $txt_upload; ?></div>
                    <?php endif; ?>
                </li>
                <li><?php echo $this->form->getLabel('title') . $this->form->getInput('title'); ?></li>
                <li><?php echo $this->form->getLabel('description') . $this->form->getInput('description'); ?></li>
            </ul>
            <div class="clr"></div>
        </fieldset>
    </div>

    <div class="width-40 fltrt span4">
        <?php echo JHtml::_('sliders.start','project-sliders-' . $this->item->id, array('useCookie'=>1)); ?>

            <?php echo JHtml::_('sliders.panel',JText::_('COM_PROJECTFORK_FIELDSET_PUBLISHING'), 'publishing-details'); ?>
            <fieldset class="panelform">
                <ul class="adminformlist unstyled">
                    <li><?php echo $this->form->getLabel('created_by') . $this->form->getInput('created_by'); ?></li>
                    <li><?php echo $this->form->getLabel('state') . $this->form->getInput('state'); ?></li>
                    <?php if ($this->item->modified_by) : ?>
                        <li><?php echo $this->form->getLabel('modified_by') . $this->form->getInput('modified_by'); ?></li>
                        <li><?php echo $this->form->getLabel('modified') . $this->form->getInput('modified'); ?></li>
                    <?php endif; ?>
                </ul>
            </fieldset>

            <?php echo JHtml::_('sliders.panel',JText::_('COM_PROJECTFORK_FIELDSET_LABELS'), 'labels'); ?>
            <fieldset class="panelform">
                <div id="jform_labels_element">
                    <div id="jform_labels_reload">
				        <?php echo $this->form->getInput('labels'); ?>
                    </div>
                </div>
            </fieldset>

            <?php
            $fieldsets = (array) $this->form->getFieldsets('attribs');

            if (count($fieldsets)) :
                foreach ($fieldsets as $name => $fieldset) :
                ?>
    				<?php echo JHtml::_('sliders.panel',JText::_($fieldset->label), $name.'-options'); ?>
    				<?php if (isset($fieldset->description) && trim($fieldset->description)) : ?>
    					<p class="tip"><?php echo $this->escape(JText::_($fieldSet->description));?></p>
    				<?php endif; ?>
    				<fieldset class="panelform">
    					<ul class="adminformlist unstyled">
    					    <?php foreach ($this->form->getFieldset($name) as $field) : ?>
    						    <li><?php echo $field->label . $field->input; ?></li>
    					    <?php endforeach; ?>
    					</ul>
    				</fieldset>
    			<?php
                endforeach;
            endif;
            ?>

       <?php echo JHtml::_('sliders.end'); ?>
       <div class="clr"></div>
    </div>

    <div class="clr"></div>

    <div class="width-100 fltlft span11">
		<?php echo JHtml::_('sliders.start', 'permissions-sliders-' . $this->item->id, array('useCookie'=>1)); ?>

			<?php echo JHtml::_('sliders.panel', JText::_('COM_PROJECTFORK_FIELDSET_RULES'), 'access-rules'); ?>
			<fieldset class="panelform">
                <p><?php echo JText::_('COM_PROJECTFORK_RULES_LABEL'); ?></p>
                <p><?php echo JText::_('COM_PROJECTFORK_RULES_NOTE'); ?></p>
				<div id="jform_rules_element">
                    <div id="jform_rules_reload" style="clear: both;">
                        <?php echo $this->form->getInput('rules'); ?>
                    </div>
                </div>
			</fieldset>
		<?php echo JHtml::_('sliders.end'); ?>
	</div>

    <div>
        <div id="jform_access_element">
            <div id="jform_access_reload">
                <?php echo $this->form->getInput('access'); ?>
            </div>
        </div>
        <?php
            echo $this->form->getInput('project_id');
            echo $this->form->getInput('created');
            echo $this->form->getInput('alias');
            echo $this->form->getInput('id');
            echo $this->form->getInput('elements');
        ?>
        <input type="hidden" name="task" value="" />
        <input type="hidden" name="return" value="<?php echo JRequest::getCmd('return');?>" />
        <input type="hidden" name="view" value="<?php echo htmlspecialchars($this->get('Name'), ENT_COMPAT, 'UTF-8');?>" />
        <input type="hidden" name="filter_parent_id" value="<?php echo intval($this->form->getValue('dir_id'));?>" />
        <?php echo JHtml::_('form.token'); ?>
    </div>
</form>
