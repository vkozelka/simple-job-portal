<?php
namespace App\Module\Job\Controller\Admin;

use App\Module\Core\Controller\Admin\BaseController;
use App\Module\Job\Form\JobForm;
use App\Module\Job\Model\Job;
use App\System\App;
use App\System\Form;
use App\System\Mvc\Controller;
use Symfony\Component\HttpFoundation\Request;

class IndexController extends BaseController {

    public function indexAction()
    {
        App::get()->getProfiler()->start("App::Admin::Job::IndexController::indexAction");
        $jobs = (new Job())->findAllJobs(false);
        $this->getView()->setVar("jobs",$jobs);
        App::get()->getProfiler()->stop("App::Admin::Job::IndexController::indexAction");
    }

    public function newAction() {
        App::get()->getProfiler()->start("App::Admin::Job::IndexController::newAction");
        $form = new JobForm();

        if ($this->getRequest()->isMethod(Request::METHOD_POST)) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $jobEntity = (new \App\Module\Job\Entity\Job())->setData($form->getValues()->all());
                if ((new Job())->save($jobEntity)) {
                    $this->flash("Pracovní pozice uložena", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "job",
                        "controller" => "index"
                    ]);
                }
            }
            $form->setData($this->getRequest()->request->all());
        }

        $this->getView()->setVar("jobForm", $form);
        $this->__setFormOptions();
        App::get()->getProfiler()->stop("App::Admin::Job::IndexController::newAction");
    }

    public function editAction($id) {
        App::get()->getProfiler()->start("App::Admin::Job::IndexController::newAction");

        if (!$id) {
            $this->flash("Chybý požadavek");
            return $this->redirect("admin",["module" => "job", "controller" => "index"]);
        }
        /** @var $job \App\Module\Job\Entity\Job */
        $job = (new Job())->findFirst($id);
        if (!$job) {
            $this->flash("Pracovní pozice nebyla nalezena");
            return $this->redirect("admin",["module" => "job", "controller" => "index"]);
        }

        $form = new JobForm(Form::FORM_ACTION_UPDATE,$job);

        if ($this->getRequest()->isMethod(Request::METHOD_POST)) {
            if ($form->isValid($this->getRequest()->request->all())) {
                $job->setData($form->getValues()->all());
                if ((new Job())->save($job)) {
                    $this->flash("Pracovní pozice uložena", Controller::FLASH_SUCCESS);
                    return $this->redirect("admin",[
                        "module" => "job",
                        "controller" => "index"
                    ]);
                }
            }
            $form->setData($this->getRequest()->request->all());
        } else {
            $form->setData($job->getData());
        }

        $this->getView()->setVar("jobForm", $form);
        $this->getView()->setVar("job", $job);
        $this->__setFormOptions();
        App::get()->getProfiler()->stop("App::Admin::Job::IndexController::newAction");
    }

    public function deleteAction($id) {
        App::get()->getProfiler()->start("App::Admin::Job::IndexController::deleteAction");

        if (!$id) {
            $this->flash("Chybý požadavek");
            return $this->redirect("admin",["module" => "job", "controller" => "index"]);
        }
        /** @var $job \App\Module\Job\Entity\News */
        $job = (new Job())->findFirst($id);
        if (!$job) {
            $this->flash("Pracovní pozice nebyla nalezena");
            return $this->redirect("admin",["module" => "job", "controller" => "index"]);
        }

        if ((new Job())->delete($job)) {
            $this->flash("Pracovní pozice smazána", Controller::FLASH_SUCCESS);
            return $this->redirect("admin",[
                "module" => "job",
                "controller" => "index"
            ]);
        } else {
            $this->flash("Pracovní pozice nebyla smazána", Controller::FLASH_ERROR);
            return $this->redirect("admin",[
                "module" => "job",
                "controller" => "index"
            ]);
        }

        App::get()->getProfiler()->stop("App::Admin::Job::IndexController::deleteAction");
    }

    private function __setFormOptions() {
        $this->getView()->setVar("currencyOptions", [
            Job::CURRENCY_CZK => "Kč",
            Job::CURRENCY_EUR => "€"
        ]);
        $this->getView()->setVar("contractOptions", [
            Job::CONTRACT_FULL_TIME => App::get()->getTranslator()->trans("admin.job.contractType.".Job::CONTRACT_FULL_TIME),
            Job::CONTRACT_PART_TIME => App::get()->getTranslator()->trans("admin.job.contractType.".Job::CONTRACT_PART_TIME),
            Job::CONTRACT_OTHER => App::get()->getTranslator()->trans("admin.job.contractType.".Job::CONTRACT_OTHER),
        ]);
        $this->getView()->setVar("salaryTypeOptions", [
            Job::SALARY_TYPE_NET => App::get()->getTranslator()->trans("admin.job.salaryType.".Job::SALARY_TYPE_NET),
            Job::SALARY_TYPE_GROSS => App::get()->getTranslator()->trans("admin.job.salaryType.".Job::SALARY_TYPE_GROSS)
        ]);
        $this->getView()->setVar("salaryPeriodOptions", [
            Job::SALARY_PERIOD_HOUR => App::get()->getTranslator()->trans("admin.job.salaryPeriod.".Job::SALARY_PERIOD_HOUR),
            Job::SALARY_PERIOD_MONTH => App::get()->getTranslator()->trans("admin.job.salaryPeriod.".Job::SALARY_PERIOD_MONTH)
        ]);
    }

}
