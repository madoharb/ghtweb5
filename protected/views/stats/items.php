<?php
/**
 * @var Controller $this
 * @var array $content
 */
?>

<?php foreach($content as $itemId => $row) { ?>
    <table class="table">
        <thead>
            <tr>
                <th colspan="7"><?php echo CHtml::encode($row['itemInfo']->name) ?> (общее кол-во: <?php echo number_format($row['maxTotalItems'], 0, '', '.') ?>, кол-во персонажей: <?php echo count($row['characters']) ?>)</th>
            </tr>
        </thead>
        <tbody>
            <?php if($row['characters']) { ?>
                <tr>
                    <td width="5%">#</td>
                    <td><?php echo Yii::t('main', 'Персонаж') ?></td>
                    <td width="15%">PvP/PK</td>
                    <td width="20%"><?php echo Yii::t('main', 'Клан') ?></td>
                    <td width="15%"><?php echo Yii::t('main', 'Время в игре') ?></td>
                    <td width="10%"><?php echo Yii::t('main', 'Статус') ?></td>
                    <td width="15%"><?php echo Yii::t('main', 'Кол-во') ?></td>
                </tr>
                <?php foreach($row['characters'] as $i => $character) { ?>
                    <tr<?php echo ($i % 2 == 0 ? ' class="even"' : '') ?>>
                        <td><?php echo ++$i ?></td>
                        <td>
                            <?php echo CHtml::encode($character['char_name']) ?>
                            <p style="font-size: 13px;" class="help-block"><?php echo Lineage::getClassName($character['base_class']) ?> [<?php echo $character['level'] ?>]</p>
                        </td>
                        <td><?php echo $character['pvpkills'] ?>/<?php echo $character['pkkills'] ?></td>
                        <td><?php
                            $clan_link = e($character['clan_name']);
                            if($this->_gs->stats_clan_info)
                            {
                                $clan_link = HTML::link($character['clan_name'], array('/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $character['clan_id']));
                            }
                            echo ($character['clan_name'] == '' ? Yii::t('main', 'Не в клане') : $clan_link);
                            ?></td>
                        <td><?php echo Lineage::getOnlineTime($character['onlinetime']) ?></td>
                        <td><?php echo ($row['online']
                                ? '<span class="status-online" title="' . Yii::t('main', 'В игре') . '"></span>'
                                : '<span class="status-offline" title="' . Yii::t('main', 'Не в игре') . '"></span>') ?></td>
                        <td><?php echo number_format($character['maxCountItems'], 0, '', '.') ?></td>
                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                    <td colspan="7"><?php echo Yii::t('main', 'Владельцев нет') ?></td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
<?php } ?>
