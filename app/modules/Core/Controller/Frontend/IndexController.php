<?php
namespace App\Module\Core\Controller\Frontend;

use App\System\App;
use App\System\Mvc\Controller;

class IndexController extends Controller {

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Core::IndexController::indexAction");
        App::get()->getProfiler()->stop("App::Core::IndexController::indexAction");
    }

}