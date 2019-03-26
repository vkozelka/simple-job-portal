<?php
namespace App\Module\Job\Block\Frontend;

use App\Module\Job\Model\Job;
use App\System\App;
use App\System\Block;

class LatestBlock extends Block {

    protected $_template = "job/latest";

    public function loadJobs()
    {
        App::get()->getProfiler()->start("App::Job::LatestBlock::loadJobs");
        $jobs = (new Job())->findAllJobs(true, "created_at", "DESC");
        $this->setVars([
            "jobs" => $jobs
        ]);
        App::get()->getProfiler()->stop("App::Job::LatestBlock::loadJobs");
        return $this;
    }

}