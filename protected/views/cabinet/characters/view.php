<?php
$title_ = Yii::t('main', 'Персонажи');
$this->pageTitle = $title_;

$this->breadcrumbs=array(
    $title_ => array('/cabinet/characters/index'),
    Yii::t('main', 'Персонаж') . ' - ' . $character['char_name']
);
?>

<h4><?php echo Yii::t('main', 'Информация') ?></h4>

<table class="table">
    <tbody>
        <tr>
            <td><b><?php echo Yii::t('main', 'Имя') ?></b></td>
            <td><?php echo $character['char_name'] ?></td>
            <td><b><?php echo Yii::t('main', 'Пол') ?></b></td>
            <td><?php echo Lineage::getGender($character['sex']) ?></td>
            <td><b><?php echo Yii::t('main', 'Уровень') ?></b></td>
            <td><?php echo $character['level'] ?></td>
        </tr>
        <tr class="even">
            <td><b><?php echo Yii::t('main', 'Клан') ?></b></td>
            <td><?php echo ($character['clan_name'] ? e($character['clan_name']) : Yii::t('main', 'нет')) ?></td>
            <td><b><?php echo Yii::t('main', 'Тюрьма') ?></b></td>
            <td><?php echo ($character['jail'] ? Yii::t('main', 'в тюрьме') : Yii::t('main', 'не в тюрьме')) ?></td>
            <td><b><?php echo Yii::t('main', 'Основной класс') ?></b></td>
            <td><?php echo Lineage::getClassName($character['base_class']) ?></td>
        </tr>
        <tr>
            <td><b><?php echo Yii::t('main', 'Карма') ?></b></td>
            <td><?php echo $character['karma'] ?></td>
            <td><b><?php echo Yii::t('main', 'ПВП') ?></b></td>
            <td><?php echo $character['pvpkills'] ?></td>
            <td><b><?php echo Yii::t('main', 'ПК') ?></b></td>
            <td><?php echo $character['pkkills'] ?></td>
        </tr>
        <tr class="even">
            <td><b><?php echo Yii::t('main', 'Титул') ?></b></td>
            <td><?php echo ($character['title'] ? e($character['title']) : Yii::t('main', 'нет')) ?></td>
            <td><b><?php echo Yii::t('main', 'Статус') ?></b></td>
            <td><?php echo ($character['online'] ? Yii::t('main', 'в игре') : Yii::t('main', 'не в игре')) ?></td>
            <td><b><?php echo Yii::t('main', 'Время в игре') ?></b></td>
            <td><?php echo Lineage::getOnlineTime($character['onlinetime']) ?></td>
        </tr>
        <tr>
            <td><b>Exp</b></td>
            <td><?php echo number_format($character['exp'], 0, '', '.') ?></td>
            <td><b>Sp</b></td>
            <td><?php echo number_format($character['sp'], 0, '', '.') ?></td>
            <td></td>
            <td></td>
        </tr>
    </tbody>
</table>

<h4><?php echo Yii::t('main', 'Предметы') ?></h4>

<table class="table">
    <thead>
        <tr>
            <th></th>
            <th><?php echo Yii::t('main', 'Название') ?></th>
            <th><?php echo Yii::t('main', 'Кол-во') ?></th>
            <th><?php echo Yii::t('main', 'Заточка') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if($items) { ?>
            <?php $i = 0; foreach($items as $item) { ?>
                <?php
                // Если по какой-то причине в инвентаре находится предмет которого нет в базе предметов CMS, то не показываю его
                if(!isset($item['icon']))
                {
                    continue;
                }
                ?>
                <tr class="<?php echo $i++ % 2 == 0 ? 'odd' : 'even' ?>">
                    <td><?php echo Lineage::getItemIcon($item['icon'], $item['description']) ?></td>
                    <td><?php echo e($item['name']) ?> <?php echo Lineage::getItemGrade($item['crystal_type']) ?></td>
                    <td><?php echo number_format($item['count'], 0, '', '.') ?></td>
                    <td><?php echo $item['enchant_level'] ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="4"><?php echo Yii::t('main', 'Нет данных.') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>