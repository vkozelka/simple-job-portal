<?php
namespace App\Module\Page\Controller\Frontend;

use App\Module\Page\Model\Page;
use App\System\App;
use App\System\Mvc\Controller;

class DetailController extends Controller
{

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Page::DetailController::indexAction");

        $news=(new Page())->findFirstBy('slug',$this->getRouteParam('slug'));

        $this->getView()->setVar("page", $news);
        $this->getView()->setCustomTemplate($news->template);

        App::get()->getProfiler()->stop("App::Page::DetailController::indexAction");
        return $this->getView();
    }
}