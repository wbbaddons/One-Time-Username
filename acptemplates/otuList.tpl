{include file='header' pageTitle='wcf.acp.user.otu.list'}

<header class="contentHeader">
	<div class="contentHeaderTitle">
		<h1 class="contentTitle">{lang}wcf.acp.user.otu.list{/lang} <span class="badge badgeInverse">{#$items}</span></h1>
	</div>
	
</header>

{hascontent}
	<div class="paginationTop">
		{content}{pages print=true assign=pagesLinks controller="OtuList" link="pageNo=%d&sortField=$sortField&sortOrder=$sortOrder"}{/content}
	</div>
{/hascontent}

{if $objects|count}
	<div class="section tabularBox">
		<table
			class="table jsObjectActionContainer"
			data-object-action-class-name="wcf\data\user\otu\blacklist\entry\UserOtuBlacklistEntryAction"
		>
			<thead>
				<tr>
					<th class="columnTitle columnUsername{if $sortField == 'username'} active {@$sortOrder}{/if}" colspan="2">
						<a href="{link controller='OTUList'}pageNo={@$pageNo}&sortField=username&sortOrder={if $sortField == 'username' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">
							{lang}wcf.user.username{/lang}
						</a>
					</th>
					<th class="columnText columnLastOwner{if $sortField == 'lastOwner'} active {@$sortOrder}{/if}">
						<a href="{link controller='OTUList'}pageNo={@$pageNo}&sortField=lastOwner&sortOrder={if $sortField == 'lastOwner' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">
							{lang}wcf.acp.user.otu.list.lastOwner{/lang}
						</a>
					</th>
					{if OTU_BLACKLIST_LIFETIME > -1}
						<th class="columnText columnTime{if $sortField == 'time'} active {@$sortOrder}{/if}">
							<a href="{link controller='OTUList'}pageNo={@$pageNo}&sortField=time&sortOrder={if $sortField == 'time' && $sortOrder == 'ASC'}DESC{else}ASC{/if}{/link}">
								{lang}wcf.acp.user.otu.list.expires{/lang}
							</a>
						</th>
					{/if}
					{event name='columnHeads'}
				</tr>
			</thead>
			
			<tbody>
				{foreach from=$objects item=entry}
					<tr
						class="jsObjectActionObject"
						data-object-id="{$entry->entryID}"
					>
						<td class="columnIcon">
							{objectAction
								action="delete"
								confirmMessage='wcf.acp.user.otu.delete.sure'}
							
							{event name='rowButtons'}
						</td>
						<td class="columnTitle columnUsername">
							{$entry->username}
						</td>
						<td class="columnText columnLastOwner">
							{if $entry->userID != null}
								<a href="{link controller="UserEdit" id=$entry->userID}{/link}">
									{$entry->lastOwner}
								</a>
							{/if}
						</td>
						{if OTU_BLACKLIST_LIFETIME > -1}
							<td class="columnText columnTime" title="{@$entry->getExpiry()->getTimestamp()|date}">
								{@$entry->getExpiry()->getTimestamp()|dateDiff:TIME_NOW:true}
							</td>
						{/if}
						
						{event name='columns'}
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
	
	<footer class="contentFooter">
		{hascontent}
			<div class="paginationBottom">
				{content}{@$pagesLinks}{/content}
			</div>
		{/hascontent}
		
		{hascontent}
			<nav class="contentFooterNavigation">
				<ul>
					{content}
						{event name='contentFooterNavigation'}
					{/content}
				</ul>
			</nav>
		{/hascontent}
	</footer>
{else}
	<p class="info">{lang}wcf.global.noItems{/lang}</p>
{/if}

{include file='footer'}
