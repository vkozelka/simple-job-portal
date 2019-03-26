<?php

namespace App\Module\News\Controller\Frontend;

use App\Module\News\Model\News;
use App\System\App;
use App\System\Mvc\Controller;

class ListController extends Controller
{

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::News::ListController::indexAction");
        $news = (new News())->findAllNews(true, "created_at", "DESC");
        $this->getView()->setVars([
            "news" => $news
        ]);
        App::get()->getProfiler()->stop("App::News::ListController::indexAction");
    }
}