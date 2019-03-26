<?php
namespace App\Module\Core\Controller\Admin;

use App\Module\Core\Form\Admin\LoginForm;
use App\Module\Core\Model\Admin\User;
use App\System\App;
use App\System\Mvc\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

class DashboardController extends BaseController {

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Admin::Core::IndexController::indexAction");
        App::get()->getProfiler()->stop("App::Admin::Core::IndexController::indexAction");
    }

}