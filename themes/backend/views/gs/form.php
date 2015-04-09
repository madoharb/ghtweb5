<?php
/**
 * @var GameServersController $this
 * @var Gs $model
 * @var ActiveForm $form
 */

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('webroot.themes.' . themeName() . '.assets'), FALSE, -1, YII_DEBUG);

js($assetsUrl . '/js/gs/index.js', CClientScript::POS_END);

Yii::import('application.modules.deposit.extensions.Deposit.Deposit');

$title__ = Yii::t('backend', 'Игровые сервера');
$this->pageTitle = $title__;
$this->breadcrumbs = array(
    $title__ => array('/backend/gameServers/index'),
    (request()->getParam('gs_id') ? Yii::t('backend', 'Редактирование') : Yii::t('backend', 'Добавление')),
);
?>


<?php $form = $this->beginWidget('ActiveForm', array(
    'id' => $this->getId() . '-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
    ),
)) ?>

    <?php echo $form->errorSummary($model) ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <legend><?php echo Yii::t('backend', 'Разное') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'name', array('placeholder' => $model->getAttributeLabel('name'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'ip', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'ip', array('placeholder' => $model->getAttributeLabel('ip'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'port', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'port', array('placeholder' => $model->getAttributeLabel('port'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'login_id', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'login_id', CHtml::listData(Ls::model()->findAll(), 'id', 'name'), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'version', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'version', app()->params['server_versions'],array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'status', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'status', $model->getStatusList(), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'allow_teleport', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'allow_teleport', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'teleport_time', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'teleport_time', array('placeholder' => $model->getAttributeLabel('teleport_time'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Время в минутах через сколько игрок сможет повторно телепортироваться.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'fake_online', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'fake_online', array('placeholder' => $model->getAttributeLabel('fake_online'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Указывайте в процентах, к примеру игроков в игре 100 если вписать 10 то выведет на сайте 110. 0 - отключит накрутку') ?></p>
        </div>
    </div>

    <legend><?php echo Yii::t('backend', 'Подключение к базе данных') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_host', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_host', array('placeholder' => $model->getAttributeLabel('db_host'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'IP адрес БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_port', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_port', array('placeholder' => $model->getAttributeLabel('db_port'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Порт от БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_user', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_user', array('placeholder' => $model->getAttributeLabel('db_user'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Пользователь от БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_pass', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->passwordField($model, 'db_pass', array('placeholder' => $model->getAttributeLabel('db_pass'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Пароль от БД сервера.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'db_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'db_name', array('placeholder' => $model->getAttributeLabel('db_name'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Название БД сервера.') ?></p>
        </div>
    </div>

    <legend><?php echo Yii::t('backend', 'Статистика') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_allow', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_allow', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_total', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_total', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_pvp', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_pvp', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_pk', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_pk', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_clans', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_clans', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_castles', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_castles', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_online', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_online', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_clan_info', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_clan_info', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_top', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_top', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_rich', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_rich', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_items', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'stats_items', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_items_list', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'stats_items_list', array('placeholder' => $model->getAttributeLabel('stats_items_list'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Вводить ID предметов через запятую, к примеру: 57, 4037, 567') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_cache_time', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'stats_cache_time', array('placeholder' => $model->getAttributeLabel('stats_cache_time'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'В минутах.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'stats_count_results', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'stats_count_results', array('placeholder' => $model->getAttributeLabel('stats_count_results'), 'class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Сколько строк вывести на странице.') ?></p>
        </div>
    </div>

    <legend><?php echo Yii::t('backend', 'Рейты') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'spoil', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'spoil', array('placeholder' => $model->getAttributeLabel('spoil'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'q_drop', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'q_drop', array('placeholder' => $model->getAttributeLabel('q_drop'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'q_reward', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'q_reward', array('placeholder' => $model->getAttributeLabel('q_reward'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'rb', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'rb', array('placeholder' => $model->getAttributeLabel('rb'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'erb', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'erb', array('placeholder' => $model->getAttributeLabel('erb'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'exp', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'exp', array('placeholder' => $model->getAttributeLabel('exp'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'sp', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'sp', array('placeholder' => $model->getAttributeLabel('sp'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'adena', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'adena', array('placeholder' => $model->getAttributeLabel('adena'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'drop', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'drop', array('placeholder' => $model->getAttributeLabel('drop'), 'class' => 'form-control')) ?>
        </div>
    </div>

    <legend><?php echo Yii::t('backend', 'Сервисы') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'services_premium_allow', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'services_premium_allow', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
        </div>
    </div>

    <div class="form-group services-premium-settings">
        <?php echo $form->labelEx($model, 'services_premium_cost', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <p>
                <a class="glyphicon glyphicon-plus" rel="tooltip" title="<?php echo Yii::t('backend', 'Добавить параметры') ?>"></a>
            </p>
            <ul class="list-group form-inline">
                <?php if($model->services_premium_cost) { ?>
                    <?php foreach($model->services_premium_cost as $i => $cost) { ?>
                        <li class="list-group-item" data-id="<?php echo $i ?>">
                            <?php echo Yii::t('backend', 'Кол-во дней') ?>: <input type="text" name="Gs[services_premium_cost][<?php echo $i ?>][days]" value="<?php echo $cost['days'] ?>" class="form-control">
                            <?php echo Yii::t('backend', 'Кол-во монет') ?>: <input type="text" name="Gs[services_premium_cost][<?php echo $i ?>][cost]" value="<?php echo $cost['cost'] ?>" class="form-control">
                            <a class="glyphicon glyphicon-minus" title="<?php echo Yii::t('backend', 'Удалить') ?>" rel="tooltip"></a>
                        </li>
                    <?php } ?>
                <?php } else { ?>
                    <li class="list-group-item" data-id="0">
                        <?php echo Yii::t('backend', 'Кол-во дней') ?>: <input type="text" name="Gs[services_premium_cost][0][days]" class="form-control">
                        <?php echo Yii::t('backend', 'Кол-во монет') ?>: <input type="text" name="Gs[services_premium_cost][0][cost]" class="form-control">
                        <a class="glyphicon glyphicon-minus" title="<?php echo Yii::t('backend', 'Удалить') ?>" rel="tooltip"></a>
                    </li>
                <?php } ?>
            </ul>
        </div>
    </div>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'services_remove_hwid_allow', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'services_remove_hwid_allow', array(Yii::t('backend', 'выкл'), Yii::t('backend', 'вкл')), array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Работает не на всех сборках.') ?></p>
        </div>
    </div>

    <legend><?php echo Yii::t('backend', 'Разное') ?></legend>

    <div class="form-group">
        <?php echo $form->labelEx($model, 'currency_name', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'currency_name', array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Название внутриигровой валюты на сервере.') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'deposit_allow', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'deposit_allow', $model->getStatusList(), array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Вкл/Выкл возможность пополнить баланс своего аккаунта.') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'deposit_payment_system', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->dropDownList($model, 'deposit_payment_system', $model->getAggregatorsList(), array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Платёжная система через которую игрок сможет пополнять свой баланс.') ?></p>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'deposit_desc', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'deposit_desc', array('class' => 'form-control')) ?>
        </div>
    </div>
    <div class="form-group">
        <?php echo $form->labelEx($model, 'deposit_course_payments', array('class' => 'col-lg-3 control-label')) ?>
        <div class="col-lg-9">
            <?php echo $form->textField($model, 'deposit_course_payments', array('class' => 'form-control')) ?>
            <p class="help-block"><?php echo Yii::t('backend', 'Сколько "Валюта" юзер будет отдавать за одну игровую валюту.') ?></p>
        </div>
    </div>

    <div class="form-group">
        <div class="col-sm-offset-3 col-sm-9">
            <button type="submit" class="btn btn-primary"><?php echo (request()->getParam('gs_id') ? Yii::t('backend', 'Сохранить') : Yii::t('backend', 'Создать')) ?></button>
        </div>
    </div>

<?php $this->endWidget() ?>
