<?php
namespace App\Module\Core\Block\Frontend;

use App\System\Block;

class HeaderBlock extends Block {

    protected $_template = "core/header";

    protected $_options = [
        "class" => "homepage-header",
        "show_jumbotron" => true
    ];

}