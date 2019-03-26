<?php
namespace App\Module\Core\Block\Admin;

use App\System\App;
use App\System\Block;

class FlashBlock extends Block {

    protected $_template = "core/flash";

    public function getAll() {
        $this->setVar("messages", App::get()->getSession()->getFlashBag()->all());
        return $this;
    }

}