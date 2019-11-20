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
$_mtb_order_card_status_change_nothing_change_master = array(	'CHECK'		=>array(), 
					'CAPTURE'	=>array('CANCEL'	=> "キャンセル"),
					'AUTH'		=>array('SALES'		=> "実売上", 
                                        'CANCEL'	=> "キャンセル"),
            		'SALES'		=>array('CANCEL'	=> "キャンセル"),
					'VOID'		=>array('AUTH'		=> "仮売上", 
										'CAPTURE'	=> "即時売上", 
										/*'SAUTH'		=> "簡易オーソリ"*/),
					'RETURN'	=>array('AUTH'		=> "仮売上", 
										'CAPTURE'	=> "即時売上", 
										/*'SAUTH'		=> "簡易オーソリ"*/),
            		'RETURNX'	=>array('AUTH'		=> "仮売上", 
										'CAPTURE'	=> "即時売上", 
										/*'SAUTH'		=> "簡易オーソリ"*/),
					'SAUTH'		=>array('CANCEL'	=> "キャンセル"),
            		'BEFORE'	=>array('AUTH'		=> "仮売上", 
										'CAPTURE'	=> "即時売上", 
										'SAUTH'		=> "簡易オーソリ", 
										'CHECK'		=> "有効性チェック"),
            		'CHANGE'	=>array('AUTH'		=> "仮売上", 
										'SALES'		=> "実売上",
										'CAPTURE'	=> "即時売上",));
?>
