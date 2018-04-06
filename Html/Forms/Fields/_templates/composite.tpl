{**
* @name composite.tpl Affichage des champs de type éditorial
* @author web-Projet.com (contact@web-projet.com)
* @package wp\Html\Forms\Fields
* @version 1.0
**}
<div class="full-editorial">
	{foreach $field->getTabbedFields() as $tabbedField}
		{include file=$tabbedField->getTemplate() field=$tabbedField}
	{/foreach}
</div>