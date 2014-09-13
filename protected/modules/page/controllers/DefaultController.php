<?php

class DefaultController extends FrontendBaseController
{
    public function actions()
    {
        return array(
            'index' => 'application.modules.page.controllers.actions.DetailAction',
        );
    }
}