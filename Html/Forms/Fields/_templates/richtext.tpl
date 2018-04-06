{**
* @name richtext.tpl Champ de type texte avec barre d'outils HTML
* @author web-Projet.com (contact@web-projet.com) - Déc. 2016
* @package wp\Html\Forms\Fields\
* @version 1.0
**}
<div class="form-group {$field->groupCss()}">
	<label for="{$field->id()}" class="{if $field->isRequired()}required{/if}">
		<span>{$field->label()}</span>
		{if $field->helpMsg() neq null}
			<span class="break">{$field->helpMsg()}</span>
		{/if}
	</label>
	<textarea lang="{$field->lang()}" placeholder="{$field->placeHolder()}" class="{$field->cssClass()}" name="{$field->name()}" id="{$field->id()}" {$field->attributes()}>
		 {$field->value()}
	</textarea>
</div>

<script>
	$("#{$field->id()}").ckeditor();
</script>