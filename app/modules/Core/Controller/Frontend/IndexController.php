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

    public function contactAction() {
        $back_url = $this->getRequest()->server->get("HTTP_REFERER");
        $message = App::get()->getEmail()->getMessage();
        $message->setFrom([$this->getRequest()->request->get("email") => $this->getRequest()->request->get("name")])
            ->setTo(["vkozelka@gmail.com" => "DeWaarde"])
            ->setSubject("Zpráva z webu dewaarde.cz");

        // Check attachments
        $attachments = [];
        if (count($this->getRequest()->files->all())) {
            foreach ($this->getRequest()->files->all() as $fieldName => $attachment) {
                $attachments[$this->getRequest()->files->get("attachment")->getRealPath()] = $this->getRequest()->files->get("attachment")->getClientOriginalName();
            }
        }

        if (!App::get()->getEmail()->send($message,"core/contact",[
            "name" => $this->getRequest()->request->get("name"),
            "email" => $this->getRequest()->request->get("email"),
            "subject" => $this->getRequest()->request->get("subject"),
            "message" => $this->getRequest()->request->get("message"),
        ],$attachments)) {
            $this->flash("Email se nepodařilo odeslat, zkuste to prosím za chvíli znovu. Děkujeme.", Controller::FLASH_ERROR);
        };

        return $this->redirectToUrl($back_url);
    }

}