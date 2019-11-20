<!--{$TopicPath}-->

<!--{* ▼BREAD CRUMBS *}-->
<div id="navBreadCrumb">
<!--{if $smarty.server.PHP_SELF != '/index.php'}-->
<a href="<!--{$smarty.const.HTTP_URL}-->">業務用エアコン販売の空調センターTOP</a> >
<!--{if $arrTopicPath}-->
<!--{foreach from=$arrTopicPath item=Topic}-->
<!--{$Topic}-->
<!--{/foreach}-->
<!--{else}--><!--{$tpl_title|h}-->
<!--{/if}-->
<!--{/if}-->
&nbsp;</div>
<!--{* ▲BREAD CRUMBS *}-->