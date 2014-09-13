<?php 
 
class ServerStatus extends CWidget
{
    public function init()
    {
        if(($data = cache()->get(CacheNames::SERVER_STATUS)) === FALSE)
        {
            if(config('server_status.allow') == 1)
            {
                $data['content']     = array();
                $data['totalOnline'] = 0;

                $criteria = new CDbCriteria(array(
                    'select' => 't.name, t.id, t.fake_online, t.ip, t.port',
                    'scopes' => array('opened'),
                    'with'   => array('ls' => array(
                        'select' => 'ls.ip, ls.port, ls.name',
                        'scopes' => array('opened'),
                    ))
                ));

                $gsList = Gs::model()->findAll($criteria);

                if($gsList)
                {
                    foreach($gsList as $gs)
                    {
                        try
                        {
                            $l2 = l2('gs', $gs->id)->connect();

                            // Кол-во игроков
                            $online = $l2->getDb()->createCommand("SELECT COUNT(0) FROM `characters` WHERE `online` = 1")->queryScalar();

                            // Fake online
                            if(is_numeric($gs->fake_online) && $gs->fake_online > 0)
                            {
                                $online += Lineage::fakeOnline($online, $gs->fake_online);
                            }

                            $data['content'][$gs->id] = array(
                                'gs_status' => Lineage::getServerStatus($gs->ip, $gs->port),
                                'ls_status' => (isset($gs->ls) ? Lineage::getServerStatus($gs->ls->ip, $gs->ls->port) : 'offline'),
                                'online'    => $online,
                                'gs'        => $gs,
                            );

                            $data['totalOnline'] += $online;
                        }
                        catch(Exception $e)
                        {
                            $data[$gs->id]['error'] = $e->getMessage();
                        }
                    }
                }

                if(config('server_status.cache') > 0)
                {
                    cache()->set(CacheNames::SERVER_STATUS, $data, config('server_status.cache') * 60);
                }
            }
        }

        app()->controller->renderPartial('//server-status', $data);
    }
}
 