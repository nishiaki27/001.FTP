<!--{* カゴ商品詳細表示 *}-->
<!--{* カゴ商品詳細表示 *}-->
<!--{if $arrCartList != ''}-->
<table>
  <!--{section name=cnt loop=$arrCartList step=-1 max=3}-->
    <!--{if $arrCartList[cnt].productsClass.name != ''}-->
      <tr>
      <td><img src="<!--{$TPL_URLPATH}-->resize_image.php?image=<!--{$arrCartList[cnt].productsClass.main_list_image|sfNoImageMainList|h}-->&width=30&height=30" /></td>
      <td><!--{$arrCartList[cnt].productsClass.name}--></td>
      <td><!--{$arrCartList[cnt].quantity}--></td>
      </tr>
    <!--{/if}-->
  <!--{/section}-->
</table>
<!--{/if}-->