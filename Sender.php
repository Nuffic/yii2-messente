<?php


namespace nuffic\messente;

use yii\base\Component;

class Sender extends Component
{

    public function send() {
        die(var_dump('sent'));
    }

}