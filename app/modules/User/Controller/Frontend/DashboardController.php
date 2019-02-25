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

class DashboardController extends Controller {

    public function indexAction() {
        $tipModel = new User\Tip();
        $paymentModel = new User\Payment();
        $year = $this->getRequest()->query->get("year",date("Y"));

        $added_tips_stats = $tipModel->countTipsByUser(App::get()->getUser()->id_user);
        $finished_tips_stats = $tipModel->countTipsByUser(App::get()->getUser()->id_user, User\Tip::TIP_STATUS_FINISHED);
        $income_stats = $paymentModel->findIncomeByUser(App::get()->getUser()->id_user, $year);

        $added_stats = [
            User\Tip::TIP_TYPE_MORTGAGE => 0,
            User\Tip::TIP_TYPE_ENERGY => 0,
            User\Tip::TIP_TYPE_REALITY => 0
        ];
        $finished_stats = [
            User\Tip::TIP_TYPE_MORTGAGE => 0,
            User\Tip::TIP_TYPE_ENERGY => 0,
            User\Tip::TIP_TYPE_REALITY => 0
        ];
        $income = [];
        foreach ([1,2,3,4,5,6,7,8,9,10,11,12] as $m) {
            $income[$m."/".$year] = 0;
        }
        foreach ($income_stats as $income_stat) {
            $income[$income_stat->month."/".$income_stat->year] = $income_stat->total;
        }
        foreach ($added_tips_stats as $stat) {
            $added_stats[$stat->tip_type] += intval($stat->cnt);
        }
        foreach ($finished_tips_stats as $stat) {
            $finished_stats[$stat->tip_type] += intval($stat->cnt);
        }

        $this->getView()->setVars([
            "added_stats_data" => json_encode(array_values($added_stats)),
            "finished_stats_data" => json_encode(array_values($finished_stats)),
            "income" => [
                "labels" => json_encode(array_keys($income)),
                "data" => json_encode(array_values($income))
            ],
            "year" => $year
        ]);
    }

}