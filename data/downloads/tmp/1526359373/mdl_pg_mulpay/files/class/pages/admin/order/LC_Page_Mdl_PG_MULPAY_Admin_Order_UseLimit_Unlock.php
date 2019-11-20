<?php
/*
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
require_once CLASS_EX_REALDIR . 'page_extends/admin/LC_Page_Admin_Ex.php';

require_once MODULE_REALDIR . 'mdl_pg_mulpay/class/utils/LC_Mdl_PG_MULPAY_AccountLock.php';


/**
 * GMOクレジット入力のロックを解除する のページクラス.
 */
class LC_Page_Mdl_PG_MULPAY_Admin_Order_UseLimit_Unlock extends LC_Page_Admin_Ex {
    /**
     * Page を初期化する.
     *
     * @return void
     */
    function init() {
        parent::init();

        $obj_LC_Mdl_PG_MULPAY_Export =& LC_Mdl_PG_MULPAY_Export::getInstance();
        $this->tpl_mainpage = $obj_LC_Mdl_PG_MULPAY_Export->getTplDirPath() . 'admin/order/gmopg_use_limit_unlock.tpl';
        $this->tpl_mainno = 'order';
        $this->tpl_subno = 'gmopg_use_limit_unlock';
        $this->tpl_subtitle = 'クレジット入力ロック解除';
    }

    /**
     * Page のプロセス.
     *
     * @return void
     */
    function process() {
        $this->action();
        $this->sendResponse();
    }

    /**
     * Page のアクション.
     *
     * @return void
     */
    function action() {
        $objDb = new SC_Helper_DB_Ex();

        // パラメータ管理クラス
        $objFormParam = new SC_FormParam_Ex();
        // パラメータ情報の初期化
        $this->lfInitParam($objFormParam);
        $objFormParam->setParam($_POST);
        // 入力値の変換
        $objFormParam->convParam();

        $this->arrForm = $objFormParam->getFormParamList();

        switch ($this->getMode()) {
        case 'unlock':
            $this->lfUnlockAccount($objFormParam->getValue('ipaddress'));
        case 'search':
            // 検索結果の表示
            $this->lfAccountDisp($objFormParam->getValue('search_ipaddress'));
            break;

        default:
            break;
        }
    }

    /**
     *  パラメータ情報の初期化
     *  @param SC_FormParam
     */
    function lfInitParam(&$objFormParam) {
        $objFormParam->addParam("IPアドレス", "search_ipaddress", 15, '', array("MAX_LENGTH_CHECK"));
        $objFormParam->addParam("IPアドレス", "ipaddress", 15, '', array("MAX_LENGTH_CHECK"));
    }

    /**
     * デストラクタ.
     *
     * @return void
     */
    function destroy() {
        parent::destroy();
    }

    //ステータス一覧の表示
    function lfAccountDisp($ipAddr) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        if (empty($objPG)) {
            $this->tpl_linemax = 0;
            $this->arrAccounts = array();
            return;
        }

        $limitMin   = $objPG->getUserSettings('limit_min');
        $limitCount = $objPG->getUserSettings('limit_count');
        $lockMin    = $objPG->getUserSettings('lock_min');

        $objAL =& LC_Mdl_PG_MULPAY_AccountLock::getInstance
            ($limitMin, $limitCount, $lockMin);

        $accounts = $objAL->getLockList($ipAddr);
        $this->tpl_linemax = count($accounts);

        $sort = array();
        foreach ($accounts as $key => &$account) {
            $sort[$key] = $account['date_time'];

            $dt = DateTime::createFromFormat('YmdHis', $account['date_time']);
            $account['date_time'] = $dt->format('Y/m/d H:i:s');

            $dt->add(new DateInterval('PT' . $lockMin . 'M'));
            $account['unlock_date_time'] = $dt->format('Y/m/d H:i:s');

            $isLock = $objAL->isLock($account['ipaddress']);
            $account['lock_status'] = $isLock ? "ロック中" : "";
            $account['is_lock'] = $isLock;
        }

        // エラー検出日時の降順でソート
        array_multisort($sort, SORT_DESC, $accounts);

        // 検索結果の取得
        $this->arrAccounts = $accounts;
    }

    /**
     * ロック中のアカウントをロック解除します
     */
    function lfUnlockAccount($ipAddr) {
        $objPG =& LC_Mdl_PG_MULPAY::getInstance();

        if (empty($ipAddr)) {
            return;
        }

        $limitMin   = $objPG->getUserSettings('limit_min');
        $limitCount = $objPG->getUserSettings('limit_count');
        $lockMin    = $objPG->getUserSettings('lock_min');

        $objAL =& LC_Mdl_PG_MULPAY_AccountLock::getInstance
            ($limitMin, $limitCount, $lockMin);

        $objAL->unLock($ipAddr);
    }
}
?>
