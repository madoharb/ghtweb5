<table class="table">
    <thead>
        <tr>
            <th width="5%"><?php echo Yii::t('main', 'Место') ?></th>
            <th><?php echo Yii::t('main', 'Персонаж') ?></th>
            <th width="14%"><?php echo Yii::t('main', 'PvP/PK') ?></th>
            <th width="20%"><?php echo Yii::t('main', 'Клан') ?></th>
            <th width="20%"><?php echo Yii::t('main', 'Время в игре') ?></th>
            <th><?php echo Yii::t('main', 'Статус') ?></th>
        </tr>
    </thead>
    <tbody>
        <?php if($content) { ?>
            <?php foreach($content as $i => $row) { ?>
                <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                    <td><?php echo ++$i ?></td>
                    <td><?php echo e($row['char_name']) ?>
                        <p class="help-block" style="font-size: 13px;"><?php echo Lineage::getClassName($row['base_class']) ?> [<?php echo $row['level'] ?>]</p></td>
                    <td><?php echo $row['pvpkills'] ?>/<?php echo $row['pkkills'] ?></td>
                    <td><?php echo clanAllyCrest('clan', $row['clan_id'], $this->_gs->id, $row['clan_crest']) ?>
                    <?php
                    $clan_link = e($row['clan_name']);
                    if($this->_gs->stats_clan_info)
                    {
                        $clan_link = HTML::link($row['clan_name'], array('/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $row['clan_id']));
                    }
                    echo ($row['clan_name'] == '' ? '-' : $clan_link);
                    ?></td>
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