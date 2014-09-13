<?php

class DefaultController extends CController
{
    public $layout = '/layouts/master';


    public function actionIndex()
    {
        $this->render('index');
    }

	public function actionStep2()
	{
        $model = new Step2Form();

        if(isset($_POST['Step2Form']))
        {
            $model->attributes = $_POST['Step2Form'];

            if($model->validate())
            {
                $body = "<?php\n";
                $body .= "\n";
                $body .= "return array(\n";
                $body .= "    'connectionString' => 'mysql:host=" . $model->mysql_host . ";port=" . $model->mysql_port . ";dbname=" . $model->mysql_name . "',\n";
                $body .= "    'emulatePrepare' => TRUE,\n";
                $body .= "    'username' => '" . $model->mysql_user . "',\n";
                $body .= "    'password' => '" . $model->mysql_pass . "',\n";
                $body .= "    'charset' => 'utf8',\n";
                $body .= "    'tablePrefix' => 'ghtweb_',\n";
                $body .= "    'enableProfiling' => YII_DEBUG,\n";
                $body .= "    'enableParamLogging' => TRUE,\n";
                $body .= "    'schemaCachingDuration' => 3600,\n";
                $body .= ");";

                file_put_contents(Yii::getPathOfAlias('webroot.protected.config') . DIRECTORY_SEPARATOR . 'database.php', $body);

                $this->redirect(array('step3'));
            }
        }

		$this->render('step2', array(
            'model' => $model,
        ));
	}

    /**
     * Установка миграций
     */
    public function actionStep3()
    {
        try
        {
            $res = $this->runMigrationTool();
        }
        catch(Exception $e)
        {
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'Install::step3');
        }

        $this->render('step3', array(
            'res' => $res,
        ));
    }

    private function runMigrationTool()
    {
        $commandPath = Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . 'commands';
        $runner = new CConsoleCommandRunner();
        $runner->addCommands($commandPath);
        $args = array('yiic', 'mymigrate', '--interactive=0');
        ob_start();
        $runner->run($args);
        return htmlentities(ob_get_clean(), null, Yii::app()->charset);
    }

    /**
     * Настройка логин сервера
     */
    public function actionStep4()
    {
        $model = new Step4Form();

        if(isset($_POST['Step4Form']))
        {
            $model->setAttributes($_POST['Step4Form']);

            if($model->validate())
            {
                db()->createCommand()->insert('{{ls}}', array(
                    'name'          => $model->name,
                    'ip'            => $model->ip,
                    'port'          => $model->port,
                    'db_host'       => $model->db_host,
                    'db_port'       => $model->db_port,
                    'db_user'       => $model->db_user,
                    'db_pass'       => $model->db_pass,
                    'db_name'       => $model->db_name,
                    'version'       => $model->version,
                    'password_type' => $model->password_type,
                    'status'        => ActiveRecord::STATUS_ON,
                    'created_at'    => date('Y-m-d H:i:s'),
                ));

                $this->redirect(array('step5'));
            }
        }

        $this->render('step4', array(
            'model' => $model,
        ));
    }

    /**
     * Настройка гейм сервера
     */
    public function actionStep5()
    {
        $model = new Step5Form();

        if(isset($_POST['Step5Form']))
        {
            $model->setAttributes($_POST['Step5Form']);

            if($model->validate())
            {
                $loginId = db()->createCommand("SELECT id FROM {{ls}} LIMIT 1")->queryScalar();

                db()->createCommand()->insert('{{gs}}', array(
                    'name'          => $model->name,
                    'ip'            => $model->ip,
                    'port'          => $model->port,
                    'db_host'       => $model->db_host,
                    'db_port'       => $model->db_port,
                    'db_user'       => $model->db_user,
                    'db_pass'       => $model->db_pass,
                    'db_name'       => $model->db_name,
                    'login_id'      => $loginId,
                    'version'       => $model->version,
                    'status'        => ActiveRecord::STATUS_ON,
                    'created_at'    => date('Y-m-d H:i:s'),
                ));

                $this->redirect(array('step6'));
            }
        }

        $this->render('step5', array(
            'model' => $model,
        ));
    }

    /**
     * Создание админа
     */
    public function actionStep6()
    {
        $model = new Step6Form();

        if(isset($_POST['Step6Form']))
        {
            $model->setAttributes($_POST['Step6Form']);

            if($model->validate())
            {
                $transaction = db()->beginTransaction();

                try
                {
                    $loginId = db()->createCommand("SELECT id FROM {{ls}} LIMIT 1")->queryScalar();

                    db()->createCommand()->insert('{{users}}', array(
                        'login'             => $model->login,
                        'password'          => Users::hashPassword($model->password),
                        'email'             => $model->email,
                        'activated'         => Users::STATUS_ACTIVATED,
                        'referer'           => Users::generateRefererCode(),
                        'role'              => Users::ROLE_ADMIN,
                        'registration_ip'   => userIp(),
                        'ls_id'             => $loginId,
                        'created_at'        => date('Y-m-d H:i:s'),
                    ));

                    db()->createCommand()->insert('{{user_profiles}}', array(
                        'user_id' => db()->getLastInsertID(),
                        'balance' => 100500,
                    ));

                    $transaction->commit();

                    $this->redirect(array('step7'));
                }
                catch(Exception $e)
                {
                    $transaction->rollback();
                    user()->setFlash(FlashConst::MESSAGE_ERROR, $e->getMessage());
                }
            }
        }

        $this->render('step6', array(
            'model' => $model,
        ));
    }

    /**
     * Finish
     */
    public function actionStep7()
    {
        $this->render('step7');
    }

    public function actionError()
    {
        if($error = app()->errorHandler->error)
        {
            if(request()->isAjaxRequest)
            {
                $this->ajax['msg'] = $error;
                echo json_encode($this->ajax);
            }
            else
            {
                $this->render('//error', $error);
            }
        }
    }
}