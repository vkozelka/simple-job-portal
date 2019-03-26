<?php
namespace App\Module\Page\Controller\Admin;

use App\Module\Core\Controller\Admin\BaseController;
use App\Module\Page\Form\PageForm;
use App\Module\Page\Model\Page;
use App\System\App;
use App\System\Mvc\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController {

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Admin::Page::IndexController::indexAction");
        $news = (new Page())->findAllPages(false);
        $this->getView()->setVar("pages",$news);
        App::get()->getProfiler()->stop("App::Admin::Page::IndexController::indexAction");
    }

    public function newAction() {
        App::get()->getProfiler()->start("App::Admin::Page::IndexController::newAction");
        $form = new PageForm();

        if ($this->getRequest()->isMethod(Request::METHOD_POST)) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $jobEntity = (new \App\Module\Page\Entity\Page())->setData($form->getValues()->all());
                if ((new Page())->save($jobEntity)) {
                    $this->flash("Stránka uložena", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "page",
                        "controller" => "index"
                    ]);
                }
            }
            $form->setData($this->getRequest()->request->all());
        }

        $this->getView()->setVar("pageForm", $form);
        App::get()->getProfiler()->stop("App::Admin::Page::IndexController::newAction");
    }

    public function editAction($id) {
        App::get()->getProfiler()->start("App::Admin::Page::IndexController::newAction");

        if (!$id) {
            $this->flash("Chybý požadavek");
            return $this->redirect("admin",["module" => "page", "controller" => "index"]);
        }
        /** @var $job \App\Module\Page\Entity\Page */
        $news = (new Page())->findFirst($id);
        if (!$news) {
            $this->flash("Stránka nebyla nalezena");
            return $this->redirect("admin",["module" => "page", "controller" => "index"]);
        }

        $form = new PageForm();

        if ($this->getRequest()->isMethod(Request::METHOD_POST)) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $news->setData($form->getValues()->all());
                if ((new Page())->save($news)) {
                    $this->flash("Stránka uložena", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "page",
                        "controller" => "index"
                    ]);
                }
            }
            $form->setData($this->getRequest()->request->all());
        } else {
            $form->setData($news->getData());
        }

        $this->getView()->setVar("pageForm", $form);
        $this->getView()->setVar("page", $news);
        App::get()->getProfiler()->stop("App::Admin::Page::IndexController::newAction");
    }

    public function deleteAction($id) {
        App::get()->getProfiler()->start("App::Admin::Page::IndexController::deleteAction");

        if (!$id) {
            $this->flash("Chybý požadavek");
            return $this->redirect("admin",["module" => "page", "controller" => "index"]);
        }
        /** @var $job \App\Module\Page\Entity\Page */
        $job = (new Page())->findFirst($id);
        if (!$job) {
            $this->flash("Stránka nebyla nalezena");
            return $this->redirect("admin",["module" => "page", "controller" => "index"]);
        }

        if ((new Page())->delete($job)) {
            $this->flash("Stránka smazána", Controller::FLASH_SUCCESS);
            return $this->redirect("admin",[
                "module" => "page",
                "controller" => "index"
            ]);
        } else {
            $this->flash("Stránka nebyla smazána", Controller::FLASH_ERROR);
            return $this->redirect("admin",[
                "module" => "page",
                "controller" => "index"
            ]);
        }

        App::get()->getProfiler()->stop("App::Admin::Page::IndexController::deleteAction");
    }

}