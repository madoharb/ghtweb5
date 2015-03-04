<?php

/**
 * Class RegisterForm
 *
 * @property Gs[] $gs_list
 * @property int $gs_id
 * @property Lineage $l2
 * @property string $prefix
 * @property string $login
 * @property string $password
 * @property string $re_password
 * @property string $email
 * @property string $referer
 * @property string $verifyCode
 * @property Users $refererInfo
 */
class RegisterForm extends CFormModel
{
    public $gs_list = array();
    public $gs_id;

    public $l2;

    public $prefix;
    public $login;
    public $password;
    public $re_password;
    public $email;
    public $referer = '';
    public $verifyCode;
    public $refererInfo;



    public function rules()
    {
        return array(
            array('gs_id,prefix,login,password,re_password,email,referer,verifyCode', 'filter', 'filter' => 'trim'),
            array('gs_id,login,password,re_password,email', 'required'),
            array('login', 'length', 'min' => Users::LOGIN_MIN_LENGTH, 'max' => Users::LOGIN_MAX_LENGTH),
            array('password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH),
            array('re_password', 'length', 'min' => Users::PASSWORD_MIN_LENGTH, 'max' => Users::PASSWORD_MAX_LENGTH),
            array('re_password', 'compare', 'compareAttribute' => 'password', 'message' => Yii::t('main', 'Поля «{compareAttribute}» и «{attribute}» не совпадают.')),
            array('email', 'email', 'message' => Yii::t('main', 'Введите корректный Email адрес.')),
            array('referer', 'length', 'allowEmpty' => TRUE, 'min' => Users::REFERER_MIN_LENGTH, 'max' => Users::REFERER_MAX_LENGTH),
            array('verifyCode', 'captcha', 'allowEmpty' => !CCaptcha::checkRequirements() || config('register.captcha.allow') == 0, 'message' => Yii::t('main', 'Код с картинки введен не верно.')),
            array('email', 'checkBadEmail'),
            array('login', 'checkLoginChars'),
            array('gs_id', 'gsIsExists'),
            array('email', 'emailUnique'),
            array('login', 'loginUnique'),
            array('referer', 'refererIsExists'),
        );
    }

    /**
     * Проверка символов в логине
     *
     * @param string $attr
     */
    public function checkLoginChars($attr)
    {
        if(!$this->hasErrors($attr))
        {
            $pattern = '/^[' . Users::LOGIN_REGEXP . ']{' . Users::LOGIN_MIN_LENGTH . ',' . Users::LOGIN_MAX_LENGTH . '}$/';

            if(!preg_match($pattern, $this->$attr))
            {
                $this->addError($attr, Yii::t('main', 'В логине разрешены следующие символы: :chars', array(':chars' => Users::LOGIN_REGEXP)));
            }
        }
    }

    protected function afterConstruct()
    {
        $dependency = new CDbCacheDependency("SELECT COUNT(0), MAX(UNIX_TIMESTAMP(updated_at)) FROM {{gs}} WHERE status = :status");
        $dependency->params = array('status' => ActiveRecord::STATUS_ON);
        $dependency->reuseDependentData = TRUE;

        $res = Gs::model()->cache(3600 * 24, $dependency)->opened()->findAll();

        if($res)
        {
            foreach($res as $gs)
            {
                $this->gs_list[$gs['id']] = $gs;
            }
        }

        unset($res, $gs);

        if(count($this->gs_list) == 1)
        {
            $this->gs_id = key($this->gs_list);
        }

        parent::afterConstruct();
    }

    protected function beforeValidate()
    {
        // Проверка префикса
        if(config('prefixes.allow'))
        {
            $validator = CValidator::createValidator('length', $this, 'prefix', array(
                'is' => (int) config('prefixes.length')
            ));

            $this->validatorList->add($validator);
        }

        return parent::beforeValidate();
    }

    /**
     * Проверка реферера
     *
     * @param attributes
     * @param $params
     */
    public function refererIsExists($attributes, $params)
    {
        $cookieName = app()->params['cookie_referer_name'];
        $cookie     = request()->cookies[$cookieName];

        if($this->referer == '' && isset($cookie->value))
        {
            $this->referer = $cookie->value;
        }

        if($this->referer != '')
        {
            $lsId = $this->gs_list[$this->gs_id]['login_id'];

            $this->refererInfo = Users::model()->find('referer = :referer AND ls_id = :ls_id', array(
                'referer' => $this->referer,
                'ls_id'   => $lsId,
            ));

            if(!$this->refererInfo)
            {
                $this->referer = '';
            }
        }
    }

    /**
     * Проверка сервера
     *
     * @param $attributes
     * @param $params
     */
    public function gsIsExists($attributes, $params)
    {
        if(!isset($this->gs_list[$this->gs_id]))
        {
            $this->addError(__FUNCTION__, Yii::t('main', 'Выберите сервер.'));
        }
    }

    /**
     * Проверка Email адреса в черном списке
     *
     * @param $attribute
     * @param $params
     */
    public function checkBadEmail($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            if(is_file($path = Yii::getPathOfAlias('app.data') . '/badEmails.txt'))
            {
                $emails = file_get_contents($path);
                $emails = explode("\n", $emails);
                $email  = explode('@', $this->email);
                $email  = $email[1];

                foreach($emails as $v)
                {
                    $v = trim($v);

                    if($v == $email)
                    {
                        $this->addError(__FUNCTION__, Yii::t('main', 'Email :email в списке запрещенных, введите другой.', array(':email' => '<b>' . $this->email . '</b>')));
                        break;
                    }
                }
            }
        }
    }

    /**
     * Проверка Email на уникальность
     *
     * @param $attribute
     * @param $params
     */
    public function emailUnique($attribute, $params)
    {
        if(!$this->hasErrors() && !config('register.multiemail'))
        {
            $email = $this->email;
            $lsId  = $this->gs_list[$this->gs_id]['login_id'];

            $res = db()->createCommand("SELECT COUNT(0) FROM `{{users}}` WHERE `email` = :email AND ls_id = :ls_id LIMIT 1")
                ->bindParam('email', $email, PDO::PARAM_STR)
                ->bindParam('ls_id', $lsId, PDO::PARAM_INT)
                ->queryScalar();

            if($res)
            {
                $this->addError('email', Yii::t('main', 'Email :email уже существует.', array(':email' => '<b>' . $this->email . '</b>')));
            }
        }
    }

    /**
     * Проверка Логина на уникальность
     *
     * @param $attribute
     * @param $params
     */
    public function loginUnique($attribute, $params)
    {
        if(!$this->hasErrors())
        {
            $login = $this->getLogin();
            $lsId  = $this->gs_list[$this->gs_id]['login_id'];

            $res = db()->createCommand("SELECT COUNT(0) FROM `{{users}}` WHERE `login` = :login AND ls_id = :ls_id LIMIT 1")
                ->bindParam('login', $login, PDO::PARAM_STR)
                ->bindParam('ls_id', $lsId, PDO::PARAM_INT)
                ->queryScalar();

            if($res)
            {
                $this->addError('login', Yii::t('main', 'Логин :login уже существует.', array(':login' => '<b>' . $login . '</b>')));
                return;
            }

            // Проверка логина на сервере
            try
            {
                $this->l2 = l2('ls', $lsId)->connect();

                $res = $this->l2->getDb()->createCommand("SELECT COUNT(0) FROM {{accounts}} WHERE login = :login LIMIT 1")
                    ->bindParam('login', $login, PDO::PARAM_STR)
                    ->queryScalar();

                if($res)
                {
                    $this->addError('login', Yii::t('main', 'Логин :login уже существует.', array(':login' => '<b>' . $login . '</b>')));
                }
            }
            catch(Exception $e)
            {
                $this->addError('login', $e->getMessage());
            }
        }
    }

    public function attributeLabels()
    {
        return array(
            'gs_id'       => Yii::t('main', 'Сервер'),
            'prefix'      => Yii::t('main', 'Префикс'),
            'login'       => Yii::t('main', 'Логин'),
            'password'    => Yii::t('main', 'Пароль'),
            're_password' => Yii::t('main', 'Повтор пароля'),
            'email'       => Yii::t('main', 'Email'),
            'referer'     => Yii::t('main', 'Реферальный код'),
            'verifyCode'  => Yii::t('main', 'Код с картинки'),
        );
    }

    public function getPrefixes()
    {
        $prefixes = array();
        $length   = config('prefixes.length');

        for($i = 0; $i < config('prefixes.count_for_list'); $i++)
        {
            $prefixes[] = strtolower(randomString($length));
        }

        return array_combine($prefixes, $prefixes);
    }

    /**
     * Возвращает логин с префиксом
     *
     * @return string
     */
    public function getLogin()
    {
        return strtolower($this->prefix . $this->login);
    }

    /**
     * Регистрация аккаунта
     */
    public function registerAccount()
    {
        $login = $this->getLogin();

        // Регистрация через почту
        if(config('register.confirm_email'))
        {
            $activatedHash = Users::generateActivatedHash();

            $user = $this->_createAccount();

            app()->notify->registerStep1($this->email, array(
                'hash' => $activatedHash,
            ));

            $cache = new CFileCache();
            $cache->init();

            $cache->set('registerActivated' . $activatedHash, array(
                'user_id'  => $user->getPrimaryKey(),
                'password' => $this->password,
                'email'    => $this->email,
            ), (int) config('register.confirm_email.time') * 60);

            user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Вы успешно зарегистрировали аккаунт. На почту :email отправлены инструкции по активации аккаута.', array(':email' => '<b>' . $this->email . '</b>')));
        }
        else
        {
            $ls_transaction = $this->l2->getDb()->beginTransaction();

            try
            {
                // Создаю аккаунт на сервере
                $this->l2->insertAccount($login, $this->password);

                $user = $this->_createAccount();

                app()->notify->registerNoEmailActivated($this->email, array(
                    'server_name' => $this->gs_list[$this->gs_id]['name'],
                    'login'       => $login,
                    'password'    => $this->password,
                    'referer'     => $user->referer,
                ));

                $ls_transaction->commit();

                user()->setFlash(FlashConst::MESSAGE_SUCCESS, Yii::t('main', 'Вы успешно зарегистрировали аккаунт. Приятной игры.'));

                //$this->downloadFileInfoAfterRegister();
            }
            catch(Exception $e)
            {
                $ls_transaction->rollback();

                user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));
                Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, __FILE__ . '::' . __LINE__);
            }
        }

        return TRUE;
    }

    /**
     * Загрузка файла с данными после регистрации
     *
     * @return void
     */
    public function downloadFileInfoAfterRegister()
    {
        $gs = $this->gs_list[$this->gs_id];

        $fileName = time() . '.txt';

        $body = Yii::t('main', 'Логин') . ": " . $this->getLogin() . "\r\n" .
            Yii::t('main', 'Пароль') . ": " . $this->password . "\r\n" .
            Yii::t('main', 'Сервер') . ": " . CHtml::encode($gs['name']);

        request()->sendFile($fileName, $body, 'text/plain', FALSE);
    }

    /**
     * Создание аккаунта на сайте
     *
     * @return Users
     */
    private function _createAccount()
    {
        $transaction = db()->beginTransaction();

        $login = $this->getLogin();

        try
        {
            // Создаю нового юзера
            $user = new Users();

            $user->login     = $login;
            $user->password  = $this->password;
            $user->email     = $this->email;
            $user->activated = (config('register.confirm_email') ? Users::STATUS_INACTIVATED : Users::STATUS_ACTIVATED);
            $user->role      = Users::ROLE_DEFAULT;
            $user->ls_id     = $this->gs_list[$this->gs_id]['login_id'];

            $user->save(FALSE);

            // Referer
            if($this->referer != '' && $this->refererInfo)
            {
                $referals = new Referals();

                $referals->referer = $this->refererInfo->getPrimaryKey();
                $referals->referal = $user->getPrimaryKey();

                $referals->save(FALSE);
            }

            // Удаляю реферальную куку
            if(isset(request()->cookies[app()->params['cookie_referer_name']]))
            {
                unset(request()->cookies[app()->params['cookie_referer_name']]);
            }

            $transaction->commit();

            return $user;
        }
        catch(Exception $e)
        {
            $transaction->rollback();

            // Удаляю созданный аккаунт на сервере
            $this->l2->getDb()->createCommand("DELETE FROM {{accounts}} WHERE login = :login LIMIT 1")
                ->bindParam('login', $login, PDO::PARAM_STR)
                ->execute();

            user()->setFlash(FlashConst::MESSAGE_ERROR, Yii::t('main', 'Произошла ошибка! Попробуйте повторить позже.'));

            return FALSE;
        }
    }
}
 