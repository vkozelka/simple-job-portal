<?php
namespace App\Module\News\Controller\Admin;

use App\Module\Core\Controller\Admin\BaseController;
use App\Module\News\Form\NewsForm;
use App\Module\News\Model\News;
use App\System\App;
use App\System\Mvc\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController {

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Admin::News::IndexController::indexAction");
        $news = (new News())->findAllNews(false);
        $this->getView()->setVar("news",$news);
        App::get()->getProfiler()->stop("App::Admin::News::IndexController::indexAction");
    }

    public function newAction() {
        App::get()->getProfiler()->start("App::Admin::News::IndexController::newAction");
        $form = new NewsForm();

        if ($this->getRequest()->isMethod(Request::METHOD_POST)) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $jobEntity = (new \App\Module\News\Entity\News())->setData($form->getValues()->all());
                if ((new News())->save($jobEntity)) {
                    $this->flash("Novinka uložena", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "news",
                        "controller" => "index"
                    ]);
                }
            }
            $form->setData($this->getRequest()->request->all());
        }

        $this->getView()->setVar("newsForm", $form);
        App::get()->getProfiler()->stop("App::Admin::News::IndexController::newAction");
    }

    public function editAction($id) {
        App::get()->getProfiler()->start("App::Admin::News::IndexController::newAction");

        if (!$id) {
            $this->flash("Chybý požadavek");
            return $this->redirect("admin",["module" => "news", "controller" => "index"]);
        }
        /** @var $job \App\Module\News\Entity\News */
        $news = (new News())->findFirst($id);
        if (!$news) {
            $this->flash("Novinka nebyla nalezena");
            return $this->redirect("admin",["module" => "news", "controller" => "index"]);
        }

        $form = new NewsForm();

        if ($this->getRequest()->isMethod(Request::METHOD_POST)) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $news->setData($form->getValues()->all());
                if ((new News())->save($news)) {
                    $this->flash("Novinka uložena", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "news",
                        "controller" => "index"
                    ]);
                }
            }
            $form->setData($this->getRequest()->request->all());
        } else {
            $form->setData($news->getData());
        }

        $this->getView()->setVar("newsForm", $form);
        $this->getView()->setVar("news", $news);
        App::get()->getProfiler()->stop("App::Admin::News::IndexController::newAction");
    }

    public function deleteAction($id) {
        App::get()->getProfiler()->start("App::Admin::News::IndexController::deleteAction");

        if (!$id) {
            $this->flash("Chybý požadavek");
            return $this->redirect("admin",["module" => "news", "controller" => "index"]);
        }
        /** @var $job \App\Module\News\Entity\News */
        $job = (new News())->findFirst($id);
        if (!$job) {
            $this->flash("Novinka nebyla nalezena");
            return $this->redirect("admin",["module" => "news", "controller" => "index"]);
        }

        if ((new News())->delete($job)) {
            $this->flash("Novinka smazána", Controller::FLASH_SUCCESS);
            return $this->redirect("admin",[
                "module" => "news",
                "controller" => "index"
            ]);
        } else {
            $this->flash("Novinka nebyla smazána", Controller::FLASH_ERROR);
            return $this->redirect("admin",[
                "module" => "news",
                "controller" => "index"
            ]);
        }

        App::get()->getProfiler()->stop("App::Admin::News::IndexController::deleteAction");
    }

}