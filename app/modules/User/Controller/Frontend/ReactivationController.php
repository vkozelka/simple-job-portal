<?php
namespace App\Module\User\Controller\Frontend;

use App\Module\User\Form\AgreementForm;
use App\Module\User\Form\ForgottenPasswordForm;
use App\Module\User\Form\LoginForm;
use App\Module\User\Form\RegisterForm;
use App\Module\User\Model\User;
use App\System\App;
use App\System\Form;
use App\System\Mvc\Controller;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\ConstraintViolation;

class ReactivationController extends Controller {

    public function indexAction() {
        $form = new ForgottenPasswordForm();

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
                } elseif ($user->is_active) {
                    $form->addError("email", new ConstraintViolation(
                        "system.validation.user_is_already_active",
                        "system.validation.user_is_already_active",
                        ["value" => $values->get("email")],null, null,$values->get("email")));
                } else {
                    $message = App::get()->getEmail()->getMessage();
                    $message->setFrom(["info@tipni.to" => "Tipni.to"])
                        ->setTo([$user->email => $user->first_name." ".$user->last_name])
                        ->setSubject("Opětovná aktivace na portálu tipni.to");
                    App::get()->getEmail()->send($message,"user/activate",[
                        "user" => $user
                    ]);

                    $this->flash("Potvrzovací zpráva byla zaslána na email: ".$user->email, Controller::FLASH_SUCCESS);
                    return $this->redirect("default",[
                        "module" => "user",
                        "controller" => "auth",
                        "action" => "login"
                    ]);
                }
            } else {
                $form->setData($this->getRequest()->request->all());
            }
        }

        $this->getView()->setVars([
            "passwordForm" => $form
        ]);
    }

}