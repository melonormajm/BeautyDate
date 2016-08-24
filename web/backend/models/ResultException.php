<?php
/**
 * Created by PhpStorm.
 * User: abel
 * Date: 3/19/2015
 * Time: 9:29 PM
 */

namespace backend\models;


use yii\base\Exception;

class ResultException extends Exception {

    const CODE_SUCCESS = 'SUCCESS';
    const CODE_ERROR = 'ERROR';

    const MSG_AUTH_ERROR = 'Error de autenticacion';

    private $result;
    private $content;

    function __construct($result = '', $content = '')
    {
        parent::__construct();
        $this->result = $result;
        $this->content = $content;
    }

    public function setResult($result, $content = '') {
        $this->result = $result;
        $this->content = $content;
    }

    public function toArray() {
        return ['result'=>$this->result, 'content'=>$this->content];
    }

}