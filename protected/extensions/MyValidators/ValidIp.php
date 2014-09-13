<?php

class ValidIp extends CValidator
{
    protected function validateAttribute($object, $attribute)
    {
        if($object->{$attribute})
        {
            $ipList = explode("\n", $object->{$attribute});

            if($ipList)
            {
                foreach($ipList as $ip)
                {
                    $ip = str_replace(array("\n", "\r"), '', trim($ip));

                    if(!ip2long($ip))
                    {
                        $object->addError($attribute, Yii::t('main', ':bad_ip - не является верным IP адресом.', array(':bad_ip' => $ip)));
                    }
                }
            }
        }
    }
}
 