<?php
/*
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
 */

// {{{ requires
require_once '../require.php';
require_once(MODULE_REALDIR . "mdl_pg_mulpay/class/pages/admin/order/LC_Page_Mdl_PG_MULPAY_Admin_Order_PayPal_Status.php");

// }}}
// {{{ generate page

$objPage = new LC_Page_Mdl_PG_MULPAY_Admin_Order_PayPal_Status();
register_shutdown_function(array($objPage, 'destroy'));
$objPage->init();
$objPage->process();
?>
