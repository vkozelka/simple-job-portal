<?php
namespace App\Module\User\Controller\Frontend;

use App\Module\User\Form\LoginForm;
use App\Module\User\Model\User;
use App\System\App;
use App\System\Form;
use App\System\Mvc\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

class AuthController extends Controller {

    public function indexAction() {
        return $this->redirect("default",[
            "module" => "user",
            "controller" => "auth",
            "action" => "login"
        ]);
    }

    public function loginAction()
    {
        $form = new LoginForm();
        if (Request::METHOD_POST === $this->getRequest()->getMethod()) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $values = $form->getValues();

                /** @var $user \App\Module\User\Entity\User */
                $user = (new User())->findFirstBy("email", $values->get("email"));
                if (!$user) {
                    $form->addError("email", new ConstraintViolation(
                        "system.validation.db_record_not_found",
                        "system.validation.db_record_not_found",
                        ["value" => $values->get("email")],null, null,$values->get("email")));
                } elseif ($user->password !== crypt($values->get("password"),$user->salt)) {
                    $form->addError("password", new ConstraintViolation(
                        "system.validation.password_incorrect",
                        "system.validation.password_incorrect",
                        [], null, null, ""));
                } elseif ($user->is_active == false) {
                    $form->addError("email", new ConstraintViolation(
                        "system.validation.user_not_active",
                        "system.validation.user_not_active",
                        [], null, null, ""));
                } else {
                    App::get()->getSession()->set("user", $user);
                    $this->flash("Přihlášení bylo úspěšné", Controller::FLASH_SUCCESS);
                    return $this->redirect("default",[
                        "module" => "user",
                        "controller" => "dashboard",
                        "action" => "index"
                    ]);
                }
            } else {
                $form->setData($this->getRequest()->request->all());
            }
        }
        $this->getView()->setVar("loginForm", $form);
    }

    public function logoutAction() {
        App::get()->getSession()->remove("user");
        return $this->redirect("default",[
            "module" => "user",
            "controller" => "auth",
            "action" => "login"
        ]);
    }

}