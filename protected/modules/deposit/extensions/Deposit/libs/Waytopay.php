<?php 

class Waytopay
{
    /**
     * Сумма поступившая на счет клиента
     * @var float
     */
    protected $wOutSum;

    /**
     * Номер счета в магазине
     * @var int
     */
    protected $wInvId;

    /**
     * 1 - платеж тестовый
     * 0 - платеж рабочий
     * @var int
     */
    protected $wIsTest;

    /**
     * Внутренний ID SMS в системе WAY to PAY
     * @var int
     */
    protected $wSmsId;

    /**
     * Номер на который отправили SMS
     * @var string
     */
    protected $wNumber;

    /**
     * Номер с которого отправили SMS
     * @var string
     */
    protected $wPhone;

    /**
     * Текст котрой был отправлен
     * @var string
     */
    protected $wText;

    /**
     * Стоимость SMS в местной валюте
     * @var float
     */
    protected $wCost;

    /**
     * Стоимость SMS в местной валюте с НДС
     * @var float
     */
    protected $wCost_nds;

    /**
     * Прибыль с SMS в Рублях
     * @var float
     */
    protected $wProfit;

    /**
     * Страна отправителя
     * @var string
     */
    protected $wCountry;

    /**
     * Оператор отправителя
     * @var string
     */
    protected $wOperator;

    /**
     * Контрольная сумма md5
     * see: http://waytopay.org/page/docs#57
     * @var string
     */
    protected $wSignature;

    /**
     * ID проекта на waytopay
     * @var int
     */
    protected $projectId;

    /**
     * Ключ проекта
     * @var string
     */
    protected $key;

    /**
     * ID SMS проекта на waytopay
     * @var int
     */
    protected $smsProjectId;

    /**
     * SMS ключ
     * @var string
     */
    protected $smsKey;



    public function __construct()
    {
        $this->wOutSum     = (float) request()->getParam('wOutSum');
        $this->wInvId      = (int) request()->getParam('wInvId');
        $this->wIsTest     = (int) request()->getParam('wIsTest', 0);
        $this->wSmsId      = (int) request()->getParam('wSmsId');
        $this->wNumber     = (string) request()->getParam('wNumber');
        $this->wPhone      = (string) request()->getParam('wPhone');
        $this->wText       = (string) request()->getParam('wText');
        $this->wCost       = (float) request()->getParam('wCost');
        $this->wCost_nds   = (float) request()->getParam('wCost_nds');
        $this->wProfit     = (float) request()->getParam('wProfit');
        $this->wCountry    = (string) request()->getParam('wCountry');
        $this->wOperator   = (string) request()->getParam('wOperator');
        $this->wSignature  = (string) request()->getParam('wSignature');

        $this->projectId    = (int) config('waytopay.project_id');
        $this->smsProjectId = (int) config('waytopay.sms.project_id');
        $this->smsKey       = (string) config('waytopay.sms.key');
        $this->key          = (string) config('waytopay.key');
    }

    public function getFormAction()
    {
        return 'https://waytopay.org/merchant/index';
    }

    public function getFields(Transactions $transaction)
    {
        return HTML::hiddenField('MerchantId', $this->projectId) .
            HTML::hiddenField('OutSum', $transaction->sum) .
            HTML::hiddenField('InvId', $transaction->id) .
            HTML::hiddenField('InvDesc', app()->controller->gs->deposit_desc) .
            HTML::hiddenField('IncCurr', 1);
    }

    public function checkParams()
    {
        if(!$this->wOutSum || !$this->wInvId || !$this->wSignature)
        {
            throw new Exception('Некорректный запрос.');
        }

        return TRUE;
    }

    public function checkSignature()
    {
        if($this->isSms())
        {
            $myCrc = strtoupper(md5($this->smsProjectId . ':' . $this->wOutSum . ':' . $this->wInvId . ':' .
                $this->smsKey . ':wSmsId=' . $this->wSmsId . ':wNumber=' . $this->wNumber . ':wPhone=' . $this->wPhone .
                ':wText=' . $this->wText . ':wCost=' . $this->wCost . ':wCost_nds=' . $this->wCost_nds . ':wProfit=' .
                $this->wProfit . ':wCountry=' . $this->wCountry . ':wOperator=' . $this->wOperator));
        }
        else
        {
            $myCrc = strtoupper(md5($this->projectId . ':' . $this->wOutSum . ':' . $this->wInvId . ':' . $this->key));
        }

        $crc = strtoupper($this->wSignature);

        if($crc != $myCrc)
        {
            throw new Exception('Некорректная цифровая подпись.');
        }

        return TRUE;
    }

    public function isSms()
    {
        return $this->wSmsId;
    }

    public function getSmsNumbers()
    {
        $projectId = 1;
        $cacheName = 'WTPSMSLIST' . $projectId;

        $cache = new CFileCache();
        $cache->init();

        if(!($data = $cache->get($cacheName)))
        {
            $res      = json_decode(file_get_contents('http://waytopay.org/api/getsmsjson/' . $this->smsProjectId), TRUE);
            $itemCost = app()->controller->gs->deposit_course_payments;
            $data     = array();

            foreach($res as $countryCode => $row)
            {
                $operators = array();

                foreach($row as $item)
                {
                    $countItems = floor($item['profit'] / $itemCost);

                    if($countItems < 1)
                    {
                        continue;
                    }

                    $item['count_items'] = $countItems;
                    $operators[$item['operator_id']][$countItems] = $item;
                }

                if(!$operators)
                {
                    continue;
                }

                $data[$countryCode] = $operators;
            }

            // Sort
            foreach($data as $k => &$v)
            {
                foreach($v as $oId => &$v2)
                {
                    ksort($v2);
                }
            }

            $cache->set($cacheName, $data, 300);
        }

        return $data;
    }

    public function getId()
    {
        if($this->wText)
        {
            list($userId, $gsId) = explode(' ', $this->wText);
            return (int) $userId;
        }
        else
        {
            return (int) $this->wInvId;
        }
    }

    public function getGsId()
    {
        list($userId, $gsId) = explode(' ', $this->wText);
        return (int) $gsId;
    }

    public function getProfit()
    {
        return $this->wProfit;
    }

    public function error($str)
    {
        return 'ERROR_' . $str;
    }

    public function success($str)
    {
        if($this->isSms())
        {
            return 'OK_SMS принято.';
        }

        return 'OK_' . $this->getId();
    }
}
 