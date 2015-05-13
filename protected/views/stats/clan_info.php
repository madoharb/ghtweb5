<p>
    <?php echo Yii::t('main', 'Состав клана') ?>: <?php echo $clan_info['clan_name'] ?><br>
    <?php echo Yii::t('main', 'Алли') ?>: <?php echo ($clan_info['ally_name'] ? e($clan_info['ally_name']) : Yii::t('main', 'Нет')) ?><br>
    <?php echo Yii::t('main', 'Замок') ?>: <?php echo ($clan_info['hasCastle'] ? Lineage::getCastleName($clan_info['hasCastle']) : Yii::t('main', 'Нет')) ?><br>
    <?php echo Yii::t('main', 'Лидер') ?>: <?php echo e($clan_info['char_name']) ?> (<?php echo Lineage::getClassName($clan_info['base_class']) ?> <?php echo $clan_info['level'] ?>)
</p>

<table class="table">
    <thead>
        <tr>
            <th width="5%"><?php echo Yii::t('main', 'Место') ?></th>
            <th><?php echo Yii::t('main', 'Персонаж') ?></th>
            <th width="14%"><?php echo Yii::t('main', 'PvP/PK') ?></th>
            <th width="21%"><?php echo Yii::t('main', 'Время в игре') ?></th>
            <th width="13%"><?php echo Yii::t('main', 'Статус') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if($clanCharacters) { ?>
            <?php foreach($clanCharacters as $i => $row) { ?>
                <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                    <td><?php echo ++$i ?></td>
                    <td><?php echo e($row['char_name']) ?>
                        <p class="help-block" style="font-size: 13px;"><?php echo Lineage::getClassName($row['base_class']) ?> [<?php echo $row['level'] ?>]</p></td>
                    <td><?php echo $row['pvpkills'] ?>/<?php echo $row['pkkills'] ?></td>
                    <td><?php echo Lineage::getOnlineTime($row['onlinetime']) ?></td>
                    <td><?php echo ($row['online']
                            ? '<span class="status-online" title="' . Yii::t('main', 'В игре') . '"></span>'
                            : '<span class="status-offline" title="' . Yii::t('main', 'Не в игре') . '"></span>') ?></td>
                </tr>
            <?php } ?>
        <?php } else { ?>
            <tr>
                <td colspan="6"><?php echo Yii::t('main', 'Данных нет') ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>