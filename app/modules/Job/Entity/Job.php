<?php

namespace App\Module\Job\Entity;

use App\System\Mvc\Model\Entity;

class Job extends Entity
{
    public $id;
    public $title;
    public $slug;
    public $description;
    public $salary_period = \App\Module\Job\Model\Job::SALARY_PERIOD_HOUR;
    public $salary_type = \App\Module\Job\Model\Job::SALARY_TYPE_NET;
    public $salary;
    public $currency = \App\Module\Job\Model\Job::CURRENCY_CZK;
    public $contract = \App\Module\Job\Model\Job::CONTRACT_FULL_TIME;
    public $is_active = 0;
    public $created_at;
    public $updated_at;
    public $deleted_at;

    public function getFormattedSalary() {
        $salary = "";
        if (strtolower($this->currency) == \App\Module\Job\Model\Job::CURRENCY_EUR) {
            $salary.= "€ ";
        }
        $salary.= number_format($this->salary,0,"."," ");
        if (strtolower($this->currency) == \App\Module\Job\Model\Job::CURRENCY_CZK) {
            $salary.= " Kč";
        }
        $salary.= " / ";
        if ($this->salary_period == \App\Module\Job\Model\Job::SALARY_PERIOD_HOUR) {
            $salary.= "hod";
        } elseif ($this->salary_period == \App\Module\Job\Model\Job::SALARY_PERIOD_MONTH) {
            $salary.= "měsíc";
        }
        if ($this->salary_type == \App\Module\Job\Model\Job::SALARY_TYPE_NET) {
            $salary .= " (čistého)";
        } elseif ($this->salary_type == \App\Module\Job\Model\Job::SALARY_TYPE_GROSS) {
            $salary .= " (hrubého)";
        }

        return $salary;
    }

}