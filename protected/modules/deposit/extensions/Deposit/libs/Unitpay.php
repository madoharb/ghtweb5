<?php 
 
class Unitpay
{
    /**
     * check — запрос на проверку состояния абонента, pay — уведомление о списании, error — уведомление об ошибке
     * @var string
     */
    protected $method;

    /**
     * идентификатор абонента в системе Партнера
     * @var string
     */
    protected $account;

    /**
     * дата платежа в формате YYYY-mm-dd HH:ii:ss (например 2012-10-01 12:32:00)
     * @var string
     */
    protected $date;

    /**
     * буквенный код оператора (beeline, mts, mf, tele2 и т.д.)
     * @var string
     */
    protected $operator;

    /**
     * код платежной системы
     * see: https://unitpay.ru/doc#billingCodes
     * @var string
     */
    protected $paymentType;

    /**
     * телефон плательщика (передается только для мобильных платежей)
     * @var string
     */
    protected $phone;

    /**
     * ваш доход с данного платежа, в руб.
     * @var float
     */
    protected $profit;

    /**
     * ID вашего проекта
     * @var int
     */
    protected $projectId;

    /**
     * цифровая подпись, образуется как md5 хеш от склеивания всех значений параметров (кроме sign),
     * отсортированных по алфавиту и секретного ключа (доступен в настройках проекта)
     * @var string
     */
    protected $sign;

    /**
     * сумма списания с лицевого счета абонента, в руб.
     * @var float
     */
    protected $sum;

    /**
     * внутренний номер платежа в Unitpay
     * @var int
     */
    protected $unitpayId;

    /**
     * Публичный ключ
     * @var string
     */
    protected $publicKey;

    /**
     * Секретный ключ
     * @var string
     */
    protected $secretKey;



    public function __construct()
    {
        $this->method       = (string) request()->getParam('method');
        $this->account      = isset($_GET['params']['account']) ? (string) $_GET['params']['account'] : '';
        $this->date         = isset($_GET['params']['date']) ? (string) $_GET['params']['date'] : '';
        $this->operator     = isset($_GET['params']['operator']) ? (string) $_GET['params']['operator'] : '';
        $this->paymentType  = isset($_GET['params']['paymentType']) ? (string) $_GET['params']['paymentType'] : '';
        $this->phone        = isset($_GET['params']['phone']) ? (string) $_GET['params']['phone'] : '';
        $this->profit       = isset($_GET['params']['profit']) ? (float) $_GET['params']['profit'] : 0;
        $this->projectId    = isset($_GET['params']['projectId']) ? (int) $_GET['params']['projectId'] : (int) config('unitpay.project_id');
        $this->sign         = isset($_GET['params']['sign']) ? (string) $_GET['params']['sign'] : '';
        $this->sum          = isset($_GET['params']['sum']) ? (float) $_GET['params']['sum'] : 0;
        $this->unitpayId    = isset($_GET['params']['unitpayId']) ? (int) $_GET['params']['unitpayId'] : 0;

        $this->publicKey    = (string) config('unitpay.public_key');
        $this->secretKey    = (string) config('unitpay.secret_key');
    }

    public function getFormAction()
    {
        return 'https://unitpay.ru/pay/' . $this->publicKey;
    }

    public function getFields(Transactions $transaction)
    {
        return HTML::hiddenField('account', $transaction->id . ' ' . app()->controller->gs->id) .
            HTML::hiddenField('sum', $transaction->sum) .
            HTML::hiddenField('desc', app()->controller->gs->deposit_desc);
    }

    public function checkParams()
    {
        if(!$this->method || !isset($_REQUEST['params']) || !is_array($_REQUEST['params']))
        {
            throw new Exception(Yii::t('main', 'Некорректный запрос.'));
        }

        if($this->method == 'check')
        {
            echo $this->success('ok');
            app()->end();
        }

        if($this->method == 'error')
        {
            $errorMessage = (!empty($_REQUEST['params']['errorMessage']) ? $_REQUEST['params']['errorMessage'] : '');
            echo $this->error($errorMessage);
            app()->end();
        }

        if(!$this->unitpayId || !$this->sum || !$this->account)
        {
            throw new Exception(Yii::t('main', 'Отсутствуют обязательные параметры платежа.'));
        }

        return TRUE;
    }

    public function checkSignature()
    {
        if($this->sign != $this->md5sign($_REQUEST['params'], $this->secretKey))
        {
            throw new Exception(Yii::t('main', 'Некорректная цифровая подпись.'));
        }

        return TRUE;
    }

    private function md5sign($params, $secretKey)
    {
        ksort($params);

        if(isset($params['sign']))
        {
            unset($params['sign']);
        }

        return md5(join(null, $params) . $secretKey);
    }

    public function isSms()
    {
        return $this->paymentType == 'sms';
    }

    public function getSmsNumbers()
    {
        return FALSE;
        /*
        $cacheName = 'UNITPAYSMSLIST' . $this->projectId;

        $cache = new CFileCache();
        $cache->init();

        if(!($data = $cache->get($cacheName)))
        {
            $data      = array();
            $projectId = $this->projectId;
            $date      = date('YmdHHiiss');
            $secretKey = $this->secretKey;

            $params = array(
                'projectId' => $projectId,
                'date'      => $date,
            );

            ksort($params);

            $sign = md5(join(null, $params) . $secretKey);

            $content = file_get_contents('https://unitpay.ru/api?method=tariffs.sms&params[projectId]='.$projectId.'&params[sign]='.$sign.'&params[date]='.$date);
            $content = json_decode($content, TRUE);

            if(isset($content['result']))
            {
                $itemCost = app()->controller->gs->deposit_course_payments;

                foreach($content['result'] as $result)
                {
                    $countItem = floor($result['profitRub'] / $itemCost);

                    if($countItem < 1)
                    {
                        continue;
                    }

                    $data[$result['countryCode']][$result['operatorCode']][$countItem] = array(
                        'country_id' => $result['countryCode'],
                        'country_name' => $result['country'],
                        'operator_id' => $result['operatorCode'],
                        'operator_name' => $result['operator'],
                        'number' => $result['shortNumber'],
                        'prefix' => $result['prefix'],
                        'price' => $result['cost'],
                        'price_nds' => $result['costVat'],
                        'profit' => $result['profitRub'],
                        'currency' => $result['currency'],
                        'comment' => NULL,
                        'count_items' => $countItem,
                    );
                }

                // Sort
                foreach($data as $k => &$v)
                {
                    foreach($v as $oId => &$v2)
                    {
                        ksort($v2);
                    }
                }
            }

            $cache->set($cacheName, $data, 300);
        }

        return $data;*/
    }

    public function getId()
    {
        list($transactionId, $gsId) = explode(' ', $this->account);
        return (int) $transactionId;
    }

    public function getGsId()
    {
        list($transactionId, $gsId) = explode(' ', $this->account);
        return (int) $gsId;
    }

    public function getProfit()
    {
        return $this->profit;
    }

    public function getSum()
    {
        return $this->sum;
    }

    public function error($message)
    {
        return json_encode(array(
            'error' => array(
                'code' => -32000,
                'message' => $message,
            ),
        ));
    }

    public function success($message)
    {
        return json_encode(array(
            'result' => array(
                'message' => $message,
            ),
        ));
    }
}
 