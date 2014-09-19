<?php $this->pageTitle = Yii::t('main', 'Статистика') ?>


<style>
.stats-list,
.server-list
{
    word-spacing: -4px;
}
.server-list
{
    margin: 0 0 30px;
}
.stats-list li:first-child,
.server-list li:first-child
{
    margin-left: 0;
}
.stats-list li,
.server-list li
{
    margin-left: 10px;
    display: inline-block;
    word-spacing: 0;
}
</style>

<?php if($gs_list) { ?>

    <ul class="list-unstyled server-list">
        <li><?php echo Yii::t('main', 'Выберите сервер.') ?>:</li>
        <?php foreach($gs_list as $gs) { ?>
            <li<?php echo ($gs_id == $gs->id ? ' class="active"' : '') ?>>
                <?php echo HTML::link($gs->name, array('/stats/default/index', 'gs_id' => $gs->id)) ?>
            </li>
        <?php } ?>
    </ul>

    <?php if($stats_types) { ?>
        
        <ul class="tabs">
            <?php foreach($stats_types as $stats_type) { ?>
                <li<?php echo ($stats_type == $type ? ' class="active"' : '') ?>>
                    <?php echo HTML::link(Lineage::statsName($stats_type), array('/stats/default/index', 'gs_id' => $gs_id, 'type' => $stats_type)) ?>
                </li>
            <?php } ?>
        </ul>

        <?php echo $content ?>
    
    <?php } ?>

<?php } else { ?>
    <div class="alert alert-info">
        <?php echo Yii::t('main', 'Сервер недоступен.') ?>
    </div>
<?php } ?>