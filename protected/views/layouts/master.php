<?php
/**
 * @var Controller $this
 */

$assetsUrl = app()->getAssetManager()->publish(Yii::getPathOfAlias('application.views.assets'), FALSE, -1, YII_DEBUG);

// jQuery
js('//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js');

// Font
css('//fonts.googleapis.com/css?family=PT+Sans:400,700&subset=latin,cyrillic');

// Bootstrap
js('//netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js', CClientScript::POS_END);

// Chosen
css($assetsUrl . '/js/libs/chosen/1.1.0/chosen.css');
js($assetsUrl . '/js/libs/chosen/1.1.0/chosen.jquery.min.js', CClientScript::POS_END);

// Scrollpane
css($assetsUrl . '/js/libs/scrollpane/jquery.jscrollpane.css');
js($assetsUrl . '/js/libs/scrollpane/jquery.mousewheel.js', CClientScript::POS_END);
js($assetsUrl . '/js/libs/scrollpane/jquery.jscrollpane.min.js', CClientScript::POS_END);

clientScript()->registerScript('scrollPane', "
    $('.scroll-pane').jScrollPane();
    var h = $('.jspDrag').height();
    $('.jspDrag').height(h - 4);
");

// Themes
js($assetsUrl . '/js/main.js', CClientScript::POS_END);
css($assetsUrl . '/css/reset.css');
css($assetsUrl . '/css/bootstrap.min.css');
css($assetsUrl . '/css/main.css');
?>
<!doctype html>
<html lang="<?php echo app()->language ?>">
    <head>
        <meta charset="UTF-8">

        <title><?php echo e($this->getPageTitle()) ?></title>

        <meta name="description" content="<?php echo e(config('meta.description')) ?>">
        <meta name="keywords" content="<?php echo e(config('meta.keywords')) ?>">

    </head>
    <body>

        <?php $this->widget('app.widgets.UserNotifications.UserNotifications') ?>

        <div id="layout">
            <div class="header-wrap">
                <header role="banner">
                    <h1 class="logo">
                        <a href="/"></a>
                    </h1>
                </header>
            </div>
            <nav>
                <?php $this->widget('zii.widgets.CMenu',array(
                    'htmlOptions' => array(
                        'class' => '',
                    ),
                    'items' => array(
                        array(
                            'label' => Yii::t('main', 'Главная'),
                            'url' => array('/index/default/index'),
                            'linkOptions' => array(
                                'data-text' => Yii::t('main', 'Главная')
                            ),
                        ),
                        array(
                            'label' => Yii::t('main', 'Регистрация'),
                            'url' => array('/register/default/index'),
                            'linkOptions' => array(
                                'data-text' => Yii::t('main', 'Регистрация')
                            ),
                        ),
                        array(
                            'label' => Yii::t('main', 'Статистика'),
                            'url' => array('/stats/default/index'),
                            'linkOptions' => array(
                                'data-text' => Yii::t('main', 'Статистика'),
                            ),
                        ),
                        array(
                            'label' => Yii::t('main', 'О Сервере'),
                            'url' => array('/page/default/index', 'page_name' => 'about'),
                            'linkOptions' => array(
                                'data-text' => Yii::t('main', 'О Сервере')
                            ),
                        ),
                        array(
                            'label' => Yii::t('main', 'Галерея'),
                            'url' => array('/gallery/default/index'),
                            'linkOptions' => array(
                                'data-text' => Yii::t('main', 'Галерея')
                            ),
                        ),
                        array(
                            'label' => Yii::t('main', 'Форум'),
                            'url' => 'http://forum.ghtweb.ru/',
                            'linkOptions' => array(
                                'data-text' => Yii::t('main', 'Форум'),
                                'target' => '_blank'
                            ),
                        ),
                    )
                )) ?>
            </nav>
            <div class="article-wrap">
                <article class="main clearfix" role="main">
                    <div class="sidebar">
                        <aside class="server-status">
                            <h2><?php echo Yii::t('main', 'Статус сервера') ?></h2>
                            <?php $this->widget('app.widgets.ServerStatus.ServerStatus') ?>
                        </aside>

                        <?php if(!user()->isGuest) { ?>
                            <aside class="menu">
                                <h2><?php echo Yii::t('main', 'Личный кабинет') ?></h2>
                                <p class="gold"><span><?php echo Yii::t('main', 'Баланс') ?>:</span> <b><?php echo formatCurrency(user()->get('balance')) ?></b></p>
                                <?php $this->widget('zii.widgets.CMenu',array(
                                    'htmlOptions' => array(
                                        'class' => 'cabinet-menu',
                                    ),
                                    'items' => array(
                                        array(
                                            'label' => Yii::t('main', 'Админка'),
                                            'url' => array('/backend/'),
                                            'visible' => user()->isAdmin()
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Главная'),
                                            'url' => array('/cabinet/default/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Пополнить баланс'),
                                            'url' => array('/cabinet/deposit/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Магазин'),
                                            'url' => array('/cabinet/shop/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Услуги'),
                                            'url' => array('/cabinet/services/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Смена пароля'),
                                            'url' => array('/cabinet/changePassword/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Безопасность'),
                                            'url' => array('/cabinet/security/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Персонажи'),
                                            'url' => array('/cabinet/characters/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Поддержка'),
                                            'url' => array('/cabinet/tickets/index')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Реферальная программа'),
                                            'url' => array('/cabinet/referals/index'),
                                            'visible' => config('referral_program.allow'),
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Мои бонусы'),
                                            'url' => array('/cabinet/bonuses/index'),
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Ввести бонус код'),
                                            'url' => array('/cabinet/bonuses/bonusCode'),
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'История пополнений'),
                                            'url' => array('/cabinet/transactionHistory/index'),
                                            'visible' => config('deposit.allow')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'История авторизаций'),
                                            'url' => array('/cabinet/authHistory/index'),
                                            'visible' => config('deposit.allow')
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Личные сообщения'),
                                            'url' => array('/cabinet/messages/index'),
                                        ),
                                        array(
                                            'label' => Yii::t('main', 'Выход'),
                                            'url' => array('/cabinet/default/logout')
                                        ),
                                    )
                                )) ?>
                            </aside>
                        <?php } else { ?>
                            <aside class="join">
                                <div class="block">
                                    <?php echo CHtml::link(Yii::t('main', 'Личный кабинет'), array('/login/default/index')) ?>
                                </div>
                            </aside>
                        <?php } ?>
                        <aside class="forum-threats">
                            <h2><?php echo Yii::t('main', 'Темы с форума') ?></h2>
                            <?php $this->widget('app.widgets.ForumThreads.ForumThreads') ?>
                        </aside>
                    </div>
                    <div class="content">

                        <?php if(strpos($_SERVER['REQUEST_URI'], 'cabinet') !== FALSE) { ?>
                            <div class="breadcrumbs">
                                <?php $this->widget('zii.widgets.CBreadcrumbs', array(
                                    'homeLink'  => '<li>' . HTML::link(Yii::t('main', 'Главная'), array('/cabinet/default/index')) . '</li>',
                                    'links'     => $this->breadcrumbs,
                                    'separator' => '<li class="divider">\</li>',
                                )) ?>
                            </div>
                        <?php } ?>

                        <!-- Виджет таймера обратного отсчета -->
                        <?php $this->widget('app.widgets.Timer.Timer', array(
                            'timeEnd' => strtotime('2017-01-01 00:00:00'), // Дата старта
                        )) ?>

                        <?php echo $content ?>
                    </div>
                </article>
            </div>
        </div>
    </body>
</html>

<!-- GHTWEB v5 -->