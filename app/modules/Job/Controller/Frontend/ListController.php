<?php

namespace App\Module\Job\Controller\Frontend;

use App\Module\Job\Model\Job;
use App\System\App;
use App\System\Mvc\Controller;

class ListController extends Controller
{

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Job::ListController::indexAction");
        $jobs = (new Job())->findAllJobs(true, "created_at", "DESC");
        $this->getView()->setVars([
            "jobs" => $jobs
        ]);
        App::get()->getProfiler()->stop("App::Job::ListController::indexAction");
    }
}