<?php

namespace App\Controllers;

use App\Log;

class IndexController extends BaseController {

    public function getIndex() {

        Log::logInfo('Visitando CTAS-Home');
        return $this->render('index.twig');
    }

}
