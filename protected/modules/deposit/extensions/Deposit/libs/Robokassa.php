<?php 
 
class Robokassa
{
    /**
     * Для теста
     */
    const URI_DEVELOPMENT = 'http://test.robokassa.ru/Index.aspx';

    /**
     * Аккаунт активировали
     */
    const URI_PRODUCTION = 'https://merchant.roboxchange.com/Index.aspx';

    /**
     * Сумма к оплате
     * @var string
     */
    protected $OutSum;

    /**
     * ID заказа
     * @var int
     */
    protected $InvId;

    /**
     * Подпись
     * @var string
     */
    protected $SignatureValue;

    /**
     * Язык
     * @var string
     */
    protected $Culture;

    /**
     * Логин в robokassa.ru
     * @var string
     */
    protected $login;

    /**
     * Пароль 1
     * @var string
     */
    protected $password1;

    /**
     * Пароль 2
     * @var string
     */
    protected $password2;

    /**
     * Тест?
     * @var bool
     */
    protected $test;



    public function __construct()
    {
        $this->OutSum           = (string) request()->getParam('OutSum');
        $this->InvId            = (int) request()->getParam('InvId');
        $this->SignatureValue   = (string) request()->getParam('SignatureValue');

        $this->login            = (string) config('robokassa.login');
        $this->password1        = (string) config('robokassa.password');
        $this->password2        = (string) config('robokassa.password2');
        $this->test             = (bool) config('robokassa.test');
        $this->Culture          = (string) request()->getParam('Culture');
    }

    public function getFormAction()
    {
        return $this->test ? self::URI_DEVELOPMENT : self::URI_PRODUCTION;
    }

    public function getFields(Transactions $transaction)
    {
        $sum   = $transaction->sum;
        $id    = $transaction->id;

        $signature = md5($this->login . ':' . $sum . ':' . $id . ':' . $this->password1);

        return HTML::hiddenField('MrchLogin', $this->login) .
            HTML::hiddenField('OutSum', $sum) .
            HTML::hiddenField('InvId', $id) .
            HTML::hiddenField('Desc', app()->controller->gs->deposit_desc) .
            HTML::hiddenField('SignatureValue', $signature) .
            HTML::hiddenField('IncCurrLabel') .
            HTML::hiddenField('Culture', app()->getLanguage());
    }

    public function checkParams()
    {
        if(!$this->OutSum || !$this->InvId || !$this->SignatureValue)
        {
            throw new Exception('Некорректный запрос.');
        }

        return TRUE;
    }

    public function checkSignature()
    {
        $crc = strtoupper($this->SignatureValue);

        // Для success и fail подпись хэшируется по другому
        if($this->Culture)
        {
            $myCrc = strtoupper(md5($_REQUEST['OutSum'] . ':' . $_REQUEST['InvId'] . ':' . $this->password1));
        }
        else
        {
            $myCrc = strtoupper(md5($_REQUEST['OutSum'] . ':' . $_REQUEST['InvId'] . ':' . $this->password2));
        }

        if($crc != $myCrc)
        {
            throw new Exception('Некорректная цифровая подпись.');
        }

        return TRUE;
    }

    public function isSms()
    {
        return FALSE;
    }

    public function getId()
    {
        return $this->InvId;
    }

    public function getSum()
    {
        return $this->OutSum;
    }

    public function getSmsNumbers()
    {
        return FALSE;
    }

    public function error($message)
    {
        return 'ERROR' . $message;
    }

    public function success($message)
    {
        return 'OK' . $message;
    }
}
