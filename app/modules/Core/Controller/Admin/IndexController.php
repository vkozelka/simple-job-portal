<?php
namespace App\Module\Core\Controller\Admin;

use App\Module\Core\Form\Admin\LoginForm;
use App\Module\Core\Model\Admin\User;
use App\System\App;
use App\System\Mvc\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

class IndexController extends BaseController {

    protected $_needAdminLogged = false;

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Admin::Core::IndexController::indexAction");
        $form = new LoginForm();
        if (Request::METHOD_POST === $this->getRequest()->getMethod()) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $values = $form->getValues();

                /** @var $user \App\Module\Core\Entity\Admin\User */
                $user = (new User())->findFirstBy("username", $values->get("username"));
                if (!$user) {
                    $form->addError("username", new ConstraintViolation(
                        "system.validation.db_record_not_found",
                        "system.validation.db_record_not_found",
                        ["value" => $values->get("username")],null, null,$values->get("username")));
                } elseif ($user->password !== crypt($values->get("password"),$user->salt)) {
                    $form->addError("password", new ConstraintViolation(
                        "system.validation.password_incorrect",
                        "system.validation.password_incorrect",
                        [], null, null, ""));
                } elseif ($user->is_active == false) {
                    $form->addError("username", new ConstraintViolation(
                        "system.validation.user_not_active",
                        "system.validation.user_not_active",
                        [], null, null, ""));
                } else {
                    App::get()->getSession()->set("admin_user", $user);
                    $this->flash("Přihlášení bylo úspěšné", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "core",
                        "controller" => "dashboard",
                        "action" => "index"
                    ]);
                }
            } else {
                $form->setData($this->getRequest()->request->all());
            }
        }
        $this->getView()->setVar("loginForm", $form);
        App::get()->getProfiler()->stop("App::Admin::Core::IndexController::indexAction");
    }

    public function logoutAction()
    {
        App::get()->getSession()->remove("admin_user");
        $this->flash("Odhlášení bylo úspěšné", Controller::FLASH_SUCCESS);
        return $this->redirect("admin");
    }

}