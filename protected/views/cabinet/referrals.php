<?php
/**
 * @var ReferalsController $this
 * @var CActiveDataProvider $dataProvider
 * @var ReferalsProfit[] $data
 */

$title_ = Yii::t('main', 'Реферальная программа');
$this->pageTitle = $title_;

$this->breadcrumbs = array($title_);

$total = 0;
?>


<div class="alert alert-info">
    <p><?php echo Yii::t('main', 'С каждого приведенного пользователя по Вашей ссылке или коду вы получаете :percent% от всех совершенных им пожертвований.', array(':percent' => '<b>' . config('referral_program.percent') . '</b>')) ?></p>
</div>

<div class="partner">
    <span class="title"><?php echo Yii::t('main', 'Ваш заработок по <br />партнерской программе') ?></span>

    <?php if($data = $dataProvider->getData()) { ?>

        <ul class="clearfix">
            <?php foreach($data as $i => $row) { ?>
                <?php $total += $row->profit ?>
                    <li class="item">
                        <div class="text">
                            <span><span><?php echo Yii::t('main', 'Дата') ?>:</span> <?php echo date('Y-m-d H:i', strtotime($row->created_at)) ?></span>
                            <span><span><?php echo Yii::t('main', 'Заработано') ?>:</span> <?php echo formatCurrency($row->profit) ?></span>
                        </div>
                    </li>
            <?php } ?>
        </ul>

    <?php } else { ?>
        <div class="alert alert-info">
            <?php echo Yii::t('main', 'Нет данных') ?>
        </div>
    <?php } ?>

    <p class="gold"><span><?php echo Yii::t('main', 'Всего заработано') ?>:</span> <?php echo formatCurrency($total) ?></p>
    <div class="hint">
        <p>С каждого приведенного пользователя по <span>Вашей ссылке</span> или <span>коду</span>
        <br />Вы получаете <span><?php echo config('referral_program.percent') ?>%</span> от всех совершенных им пожертвований.</p>
    </div>
    <p class="link"><span><?php echo Yii::t('main', 'Ваша ссылка') ?>:</span> <?php echo app()->createAbsoluteUrl('/index/default/index') . '?' . app()->params['cookie_referer_name'] . '=' .  user()->get('referer') ?></p>
    <p class="code"><span><?php echo Yii::t('main', 'Ваш код') ?>:</span> <span><?php echo user()->get('referer') ?></span></p>
    <p class="users"><?php echo Yii::t('main', 'Зарегистрированных пользователей по вашей ссылке/коду') ?>: <span class="count"><?php echo $countReferals ?></span></p>
</div>