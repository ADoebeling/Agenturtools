<?php
namespace ADoebeling;


class exception extends \Exception
{
    public function errorMessage() {

        $errorMsg = "{$this->getMessage()}\n\n blubb File: {$this->file}:{$this->getLine()}\n\n{$this->getTraceAsString()}";
        return $errorMsg;
    }
}

function cliException(exception $exception) {
    echo "<b>Exception:</b> " . $exception->getMessage();
}

