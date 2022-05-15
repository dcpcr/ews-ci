<?php

namespace App\Controllers;

class CronController extends BaseController
{
    public function someFunc()
    {
        if ($this->request->isCLI()) {
            echo "this is okay";
            //TODO: Run your cron scripts
        } else {
            echo "You dont have access";
        }
    }
}