<?php
namespace App\Module\Job\Controller\Frontend;

use App\Module\Job\Model\Job;
use App\System\App;
use App\System\Mvc\Controller;

class DetailController extends Controller
{

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Job::DetailController::indexAction");

        $job=(new Job())->findFirstBy('slug',$this->getRouteParam('slug'));
        var_dump($job);exit;
        App::get()->getProfiler()->stop("App::Job::DetailController::indexAction");
    }
}