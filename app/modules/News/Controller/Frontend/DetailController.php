<?php
namespace App\Module\News\Controller\Frontend;

use App\Module\News\Model\News;
use App\System\App;
use App\System\Mvc\Controller;

class DetailController extends Controller
{

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::News::DetailController::indexAction");

        $news=(new News())->findFirstBy('slug',$this->getRouteParam('slug'));

        $this->getView()->setVar("news", $news);

        App::get()->getProfiler()->stop("App::News::DetailController::indexAction");
    }
}