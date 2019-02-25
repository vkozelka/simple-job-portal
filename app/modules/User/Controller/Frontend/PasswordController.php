<?php
namespace App\Module\User\Controller\Frontend;

use App\Module\User\Form\AgreementForm;
use App\Module\User\Form\ForgottenPasswordForm;
use App\Module\User\Form\LoginForm;
use App\Module\User\Form\PasswordForm;
use App\Module\User\Form\RegisterForm;
use App\Module\User\Model\User;
use App\System\App;
use App\System\Form;
use App\System\Mvc\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

class PasswordController extends Controller {

    public function indexAction() {
        $form = new PasswordForm();

        if (Request::METHOD_POST === $this->getRequest()->getMethod()) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $values = $form->getValues();

                /** @var $user \App\Module\User\Entity\User */
                $user = (new User())->findFirst(App::get()->getUser()->id_user);

                if ($values->get("new_password") !== $values->get("new_password_confirmation")) {
                    $form->addError("new_password", new ConstraintViolation(
                        "system.validation.passwords_not_match",
                        "system.validation.passwords_not_match",
                        [],null, null,$values->get("email")));
                } elseif (!$user) {
                    $form->addError("old_password", new ConstraintViolation(
                        "system.validation.db_record_not_found",
                        "system.validation.db_record_not_found",
                        ["value" => $values->get("email")],null, null,$values->get("email")));
                } elseif ($user->password !== crypt($values->get("old_password"),$user->salt)) {
                    $form->addError("old_password", new ConstraintViolation(
                        "system.validation.old_password_incorect",
                        "system.validation.old_password_incorect",
                        [],null, null,$values->get("email")));
                } else {
                    $user->salt = md5(time().rand(1,time()));
                    $user->password = crypt($values->get("new_password"), $user->salt);

                    if ((new User())->save($user)) {
                        $this->flash("Heslo bylo úspěšně změněno. Prosím přihlašte se s novým heslem.", Controller::FLASH_SUCCESS);
                        return $this->redirect("default",[
                            "module" => "user",
                            "controller" => "auth",
                            "action" => "logout"
                        ]);
                    } else {
                        $this->flash("Změna hesla se nezdařila. Zkuste to prosím znovu", Controller::FLASH_ERROR);
                    }
                }
            }
        }

        $this->getView()->setVars([
            "passwordForm" => $form
        ]);
    }

}