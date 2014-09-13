<?php
$title_ = Yii::t('main', 'Премиум аккаунт');
$this->pageTitle = $title_;

$this->breadcrumbs=array(
    Yii::t('main', 'Услуги') => array('/cabinet/services/index'),
    $title_
);
?>

<?php if($gs['services_premium_allow'] && $gs['services_premium_cost'] != '') { ?>

    <div class="alert alert-danger">
        <?php echo Yii::t('main', '<h4>ВНИМАНИЕ!!!</h4><p>Перед оплатой необходимо выйти с сервера (до ввода логина и пароля) иначе время к премиум аккаунту не начислится.</p>') ?>
    </div>

    <?php if($premium !== FALSE && isset($premium['dateEnd']) && $premium['dateEnd'] > 0) { ?>
        <div class="alert alert-info">
            <p><?php echo Yii::t('main', 'Премиум аккаунт действителен до :date_end', array(':date_end' => '<b>' . date('Y-m-d H:i', $premium['dateEnd']) . '</b>')) ?></p>
        </div>
    <?php } ?>

    <?php $this->widget('app.widgets.FlashMessages.FlashMessages') ?>

    <?php echo CHtml::beginForm() ?>

        <?php foreach($gs['services_premium_cost'] as $row) { ?>

            <?php $msg = Yii::t('main', '{n} день :cost|{n} дня :cost|{n} дней :cost|{n} дня :cost', array($row['days'], ':cost' => formatCurrency($row['cost']))) ?>
            
            <div class="button-group">
                <button type="submit" value="<?php echo $row['days'] ?>" class="button" name="period">
                    <span><?php echo $msg ?></span>
                </button>
            </div>

        <?php } ?>

    <?php echo CHtml::endForm() ?>

<?php } else { ?>
    <div class="alert alert-info">
        <?php echo Yii::t('main', 'Услуга отключена.') ?>
    </div>
<?php } ?>