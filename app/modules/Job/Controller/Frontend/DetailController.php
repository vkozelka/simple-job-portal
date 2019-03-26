<?php
namespace App\Module\Job\Controller\Frontend;

use App\Module\Job\Model\Job;
use App\System\App;
use App\System\Mvc\Controller;

class DetailController extends Controller
{

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Job::DetailController::indexAction");

        $job=(new Job())->findFirstBy('slug',$this->getRouteParam('slug'));

        $this->getView()->setVar("job", $job);

        App::get()->getProfiler()->stop("App::Job::DetailController::indexAction");
    }

    public function applicationAction() {
        $back_url = $this->getRequest()->server->get("HTTP_REFERER");
        $message = App::get()->getEmail()->getMessage();
        $message->setFrom([$this->getRequest()->request->get("email") => $this->getRequest()->request->get("name")." ".$this->getRequest()->request->get("surname")])
            ->setTo(["vkozelka@gmail.com" => "DeWaarde"])
            ->setSubject("Zájem o pracovní pozici z webu dewaarde.cz");

        // Check attachments
        $attachments = [];
        if (count($this->getRequest()->files->all())) {
            foreach ($this->getRequest()->files->all() as $fieldName => $attachment) {
                if ($attachment) {
                    $attachments[$this->getRequest()->files->get($fieldName)->getRealPath()] = $this->getRequest()->files->get($fieldName)->getClientOriginalName();
                }
            }
        }

        if (!App::get()->getEmail()->send($message,"job/application",[
            "name" => $this->getRequest()->request->get("name"),
            "surname" => $this->getRequest()->request->get("surname"),
            "email" => $this->getRequest()->request->get("email"),
            "phone" => $this->getRequest()->request->get("phone"),
            "job" => $this->getRequest()->request->get("job"),
            "message" => $this->getRequest()->request->get("message"),
        ],$attachments)) {
            $this->flash("Email se nepodařilo odeslat, zkuste to prosím za chvíli znovu. Děkujeme.", Controller::FLASH_ERROR);
        };

        return $this->redirectToUrl($back_url);
    }

}