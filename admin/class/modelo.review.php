<?php
class Review extends Persistencia {
    var $roteiro = null;
    var $cliente = null;
    var $review;
    var $date;
    var $name;
    var $email;

    function getRandomicos($qtd){
        $sql = "select * from ag_review order by rand() limit 0,$qtd";
        return $this->getSQL($sql);
    }
}