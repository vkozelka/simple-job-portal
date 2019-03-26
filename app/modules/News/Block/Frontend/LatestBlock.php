<?php
namespace App\Module\News\Block\Frontend;

use App\Module\News\Model\News;
use App\System\App;
use App\System\Block;

class LatestBlock extends Block {

    protected $_template = "news/latest";

    public function loadNews()
    {
        App::get()->getProfiler()->start("App::News::LatestBlock::loadJobs");
        $news = (new News())->findAllNews(true, "created_at", "DESC");
        $this->setVars([
            "news" => $news
        ]);
        App::get()->getProfiler()->stop("App::News::LatestBlock::loadJobs");
        return $this;
    }

}