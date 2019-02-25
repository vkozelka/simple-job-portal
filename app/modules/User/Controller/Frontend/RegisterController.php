<?php
namespace App\Module\User\Controller\Frontend;

use App\Module\User\Form\AgreementForm;
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

class RegisterController extends Controller {

    public function indexAction() {
        $form = new RegisterForm();

        $partnerId = $this->getRequest()->query->get("partner", null);
        $partner = null;
        if ($partnerId) {
            $partner = (new User())->findFirstBy("salt", $partnerId);
            if (!$partner) {
                return $this->redirect("default",[
                    "module" => "user",
                    "controller" => "register"
                ]);
            }
        }
        if (Request::METHOD_POST === $this->getRequest()->getMethod()) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $values = $form->getValues();
                $data = $values->all();

                $random_password = substr(sha1(rand(1,time()).time()),0,16);

                $data["salt"] = md5(time().rand(1,time()));
                $data["password"] = crypt($random_password, $data["salt"]);
                if ($partner) {
                    $data["id_user_parent"] = $partner->id_user;
                }

                $entity = (new \App\Module\User\Entity\User())->setData($data);
                if ((new User())->save($entity)) {

                    $message = App::get()->getEmail()->getMessage();
                    $message->setFrom(["info@tipni.to" => "Tipni.to"])
                        ->setTo([$entity->email => $entity->first_name." ".$entity->last_name])
                        ->setSubject("Úspěšná registrace na portálu tipni.to");
                    App::get()->getEmail()->send($message,"user/register",[
                        "user" => $entity,
                        "password" => $random_password
                    ]);

                    $message = App::get()->getEmail()->getMessage();
                    $message->setFrom([$entity->email => $entity->first_name." ".$entity->last_name])
                        ->setTo(["info@tipni.to" => "tipni.to"])
                        ->setSubject("Nová registrace na portálu tipni.to");
                    App::get()->getEmail()->send($message,"user/register_system",[
                        "user" => $entity
                    ]);

                    $this->flash("Registrace byla úspěšná. Potvrzovací informace byly zaslány na email: ".$entity->email, Controller::FLASH_SUCCESS);
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
            "registerForm" => $form,
            "partner" => $partner
        ]);
    }

    public function agreementAction()
    {
        $user = (new User())->findFirstBy("salt", $this->getRequest()->query->get("user"));
        $form = new AgreementForm();
        if (Request::METHOD_POST === $this->getRequest()->getMethod()) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $data = $form->getValues()->all();

                $user->is_active = 1;
                if ((new User())->save($user)) {

                    $this->flash("Aktivace účtu byla úspěšná.", Controller::FLASH_SUCCESS);

                    $message = App::get()->getEmail()->getMessage();
                    $message->setFrom(["info@tipni.to" => "Tipni.to"])
                        ->setTo([$user->email => $user->first_name." ".$user->last_name])
                        ->setSubject("Úspěšná aktivace účtu na portálu tipni.to");
                    App::get()->getEmail()->send($message,"user/activation",[
                        "user" => $user
                    ]);

                    $message = App::get()->getEmail()->getMessage();
                    $message->setFrom([$user->email => $user->first_name." ".$user->last_name])
                        ->setTo(["info@tipni.to" => "tipni.to"])
                        ->setSubject("Nová aktivace účtu na portálu tipni.to");
                    App::get()->getEmail()->send($message,"user/activation_system",[
                        "user" => $user
                    ]);

                    return $this->redirect("default",[
                        "module" => "user",
                        "controller" => "auth",
                        "action" => "login"
                    ]);
                }
            }
        } else {
            if (!$user) {
                return $this->redirect("default",[
                    "module" => "user",
                    "controller" => "register"
                ]);
            }
        }
        $this->getView()->setVars([
            "user" => $user,
            "agreementForm" => $form
        ]);
    }

}