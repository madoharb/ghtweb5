<table class="table">
    <tbody>
        <?php foreach($content as $i => $row) { ?>
            <tr>
                <th colspan="2"><?php echo Lineage::getCastleName($row['castle_id']) ?></th>
            </tr>
            <tr class="<?php echo $i % 2 == 0 ? 'odd' : 'even' ?>">
                <td width="150"><?php echo Lineage::getCastleIcon($row['castle_id']) ?></td>
                <td>
                <?php echo Yii::t('main', 'Налог') ?>: <i><?php echo $row['tax_percent'] ?>%</i><br />
                <?php echo Yii::t('main', 'Дата осады') ?>: <i><?php echo formatDate(substr($row['sieg_date'], 0, 10)) ?></i><br />
                <?php echo Yii::t('main', 'Владелец') ?>: <?php echo ($row['owner'] ? ($this->_gs->stats_clan_info ? HTML::link($row['owner'], array('/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $row['owner_id'])) : $row['owner']) : '<i>NPC</i>') ?> <br />
                <?php echo Yii::t('main', 'Нападающие') ?>:
                <?php
                $f = array();
                if($row['forwards'] && is_array($row['forwards']))
                {
                    foreach($row['forwards'] as $fd)
                    {
                        if($this->_gs->stats_clan_info)
                        {
                            $f[] = HTML::link($fd['clan_name'], array('/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $fd['clan_id']));
                        }
                        else
                        {
                            $f[] = $fd['clan_name'];
                        }
                    }
                }
                else
                {
                    $f[] = Yii::t('main', 'Нет');
                }
                echo implode(', ', $f);
                ?> <br />
                <?php echo Yii::t('main', 'Защитники') ?>:
                <?php
                $f = array();
                if($row['defenders'] && is_array($row['defenders']))
                {
                    foreach($row['defenders'] as $fd)
                    {
                        if($this->_gs->stats_clan_info)
                        {
                            $f[] = HTML::link($fd['clan_name'], array('/stats/default/index', 'gs_id' => $this->_gs_id, 'type' => 'clan-info', 'clan_id' => $fd['clan_id']));
                        }
                        else
                        {
                            $f[] = $fd['clan_name'];
                        }
                    }
                }
                else
                {
                    $f[] = Yii::t('main', 'Нет');
                }
                echo implode(', ', $f);
                ?> <br />
                </td>
            </tr>
        <?php } ?>
    </tbody>
</table>