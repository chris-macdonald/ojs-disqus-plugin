{**
 * plugins/generic/disqus/settingsForm.tpl
 *
 * Copyright (c) 2013 Simon Fraser University Library
 * Copyright (c) 2003-2013 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * Disqus plugin settings
 *
 *}

{assign var="pageTitle" value="plugins.generic.disqus.manager.disqusSettings"}
{include file="common/header.tpl"}

{translate key="plugins.generic.disqus.manager.settings.description"}

<div class="separator"></div>

<br />

<form method="post" action="{plugin_url path="settings"}">
{include file="common/formErrors.tpl"}

<table width="100%" class="data">
	<tr valign="top">
		<td class="label">{fieldLabel name="disqusShortname" required="true" key="plugins.generic.disqus.manager.settings.disqusShortname"}</td>
		<td class="value"><input type="text" name="disqusShortname" id="disqusShortname" value="{$disqusShortname}" size="30" maxlength="255" class="textField" />
		<br />
		<span class="instruct">{translate key="plugins.generic.disqus.manager.settings.disqusShortnameInstructions"}</span>
	</td>
	</tr>
</table>

<br/>

<input type="submit" name="save" class="button defaultButton" value="{translate key="common.save"}"/><input type="button" class="button" value="{translate key="common.cancel"}" onclick="history.go(-1)"/>
</form>

<p><span class="formRequired">{translate key="common.requiredField"}</span></p>

{include file="common/footer.tpl"}
