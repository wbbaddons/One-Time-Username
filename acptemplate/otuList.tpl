{include file='header' pageTitle='wcf.acp.user.otu.list'}

<header class="boxHeadline">
	<h1>{lang}wcf.acp.user.otu.list{/lang}</h1>
	
	<script data-relocate="true">
		//<![CDATA[
		$(function() {
			new WCF.Action.Delete('wcf\\data\\user\\otu\\blacklist\\entry\\UserOtuBlacklistEntryAction', '.jsOTURow');
		});
		//]]>
	</script>
</header>

<div class="contentNavigation">
	{pages print=true assign=pagesLinks controller="OTUList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}
	
	{hascontent}
		<nav>
			<ul>
				{content}
					{event name='contentNavigationButtonsTop'}
				{/content}
			</ul>
		</nav>
	{/hascontent}
</div>

{if $objects|count}
	<div class="tabularBox tabularBoxTitle marginTop">
		<header>
			<h2>{lang}wcf.acp.user.otu.list{/lang} <span class="badge badgeInverse">{#$items}</span></h2>
		</header>
		
		<table class="table">
			<thead>
				<tr>
					<th class="columnTitle columnUsername{if $sortField == 'username'} active {@$sortOrder}{/if}" colspan="2"><a href="{link controller='OTUList'}pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.user.username{/lang}</a></th>
					<th class="columnText columnLastOwner{if $sortField == 'lastOwner'} active {@$sortOrder}{/if}"><a href="{link controller='OTUList'}pageNo={@$pageNo}&sortField=lastOwner&sortOrder={if $sortField == 'lastOwner' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.user.otu.list.lastOwner{/lang}</a></th>
					{if OTU_BLACKLIST_LIFETIME > -1}<th class="columnText columnTime{if $sortField == 'time'} active {@$sortOrder}{/if}"><a href="{link controller='OTUList'}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">{lang}wcf.acp.user.otu.list.expires{/lang}</a></th>{/if}
					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=entry}
					<tr class="jsOTURow">
						<td class="columnIcon">
							<span class="icon icon16 icon-remove jsDeleteButton jsTooltip pointer" title="{lang}wcf.global.button.delete{/lang}" data-object-id="{$entry->entryID}" data-confirm-message="{lang}wcf.acp.otu.delete.sure{/lang}"></span>
							
							{event name='rowButtons'}
						</td>
						<td class="columnTitle columnUsername">{$entry->username}</td>
						<td class="columnText columnLastOwner">{if $entry->userID != null}<a href="{link controller="UserEdit" id=$entry->userID}{/link}">{$entry->lastOwner}</a>{/if}</td>
						{if OTU_BLACKLIST_LIFETIME > -1}<td class="columnText columnTime" title="{@($entry->time + OTU_BLACKLIST_LIFETIME * 86400)|date}">{@($entry->time + OTU_BLACKLIST_LIFETIME * 86400)|dateDiff:TIME_NOW:true}</a></td>{/if}
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
		
	</div>
	
	<div class="contentNavigation">
		{@$pagesLinks}
		
		{hascontent}
			<nav>
				<ul>
					{content}
						{event name='contentNavigationButtonsBottom'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</div>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
