<!--{*
 * This file is part of EC-CUBE PAYMENT MODULE
 *
 * Copyright(c) 2000-2011 LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.net/product/payment/
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.
 *}-->
<ul class="level1">
    <li id="navi-order-index"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'index'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/<!--{$smarty.const.DIR_INDEX_PATH}-->"><span>受注管理</span></a></li>
    <li id="navi-order-add"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'add'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/edit.php?mode=add"><span>受注登録</span></a></li>
    <li id="navi-order-status"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'status'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/status.php"><span>対応状況管理</span></a></li>

    <!--{* クレジット決済状況 *}-->
    <!--{if $gmopg_enableCardStatusChange}-->
    <li id="navi-order-gmopg-credit-status"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'gmopg_credit_status'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/gmopg_credit_status.php"><span>クレジット決済状況</span></a></li>
    <!--{/if}-->

    <!--Paypal決済状況-->
    <!--{if $gmopg_enablePaypal}-->
    <li id="navi-order-gmopg-paypal-status"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'gmopg_paypal_status'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/gmopg_paypal_status.php"><span>PayPal決済状況</span></a></li>
    <!--{/if}-->

    <!--iD決済状況-->
    <!--{if $gmopg_enableNetid}-->
    <li id="navi-order-gmopg-netid-status"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'gmopg_netid_status'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/gmopg_netid_status.php"><span>iD決済状況</span></a></li>
    <!--{/if}-->

    <!--Au決済状況-->
    <!--{if $gmopg_enableAu}-->
    <li id="navi-order-gmopg-au-status"
        class="<!--{if $tpl_mainno == 'order' && $tpl_subno == 'gmopg_au_status'}-->on<!--{/if}-->"
    ><a href="<!--{$smarty.const.ROOT_URLPATH}--><!--{$smarty.const.ADMIN_DIR}-->order/gmopg_au_status.php"><span>auかんたん決済状況</span></a></li>
    <!--{/if}-->
</ul>
