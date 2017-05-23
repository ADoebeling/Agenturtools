<?php

namespace www1601com\Agenturtools;
class cmd
{
    protected $cmd = '';
    protected $cmdReturnVar = NULL;
    protected $regEx = '';
    protected $output = [];

    public function setCmd($cmd)
    {
        $this->cmd = $cmd;
        return $this;
    }

    public function setRegEx($regEx)
    {
        $this->regEx = $regEx;
        return $this;
    }

    public function exec()
    {
        exec($this->cmd, $this->output, $this->cmdReturnVar);
        $this->addLog();
        if ($this->cmdReturnVar != 0)
        {
            throw new exception("Error {$this->cmdReturnVar} on cmd {$this->cmd}");
        }
        return $this;
    }

    public function getRawArray()
    {
        return $this->output;
    }


    /**
     * @param string $returnFormat 'array'|'string'
     * @return array|bool|string
     */
    public function getParsed($returnFormat = 'array')
    {
        $matches = [];
        if ($returnFormat == 'array') {
            $return = [];
            foreach ($this->output as $line) {
                preg_match($this->regEx, $line, $matches);
                $return[] = $matches;
            }
        }
        elseif ($returnFormat == 'string') {
            $return = '';
            foreach ($this->output as $line) {
                preg_match($this->regEx, $line, $matches);
                unset($matches[0]);
                foreach ($matches as $element)
                {
                    $return .= empty($return) ? $element : "$element\n";
                }
            }
        }
        else
        {
            new \Exception("Unknown returnFormat '$returnFormat'");
            return false;
        }
        return $return;
    }

    /**
     * Run a command on shell, optional parse the result and return as array
     *
     * @todo Exception handler
     * @param string $cmd
     * @param string $regex
     * @param string $returnFormat
     * @return array|string|bool
     */
    public static function run($cmd, $regex = '', $returnFormat = 'array')
    {
        if (empty($regex)) {
            return (new cmd())
                ->setCmd($cmd)
                ->exec()
                ->getRawArray();
        } else {
            return (new cmd())
                ->setCmd($cmd)
                ->setRegEx($regex)
                ->exec()
                ->getParsed($returnFormat);
        }
    }

    /**
     * @todo Implement monolog
     */
    protected function addLog()
    {
        $logFile = __DIR__.'/../log/cmd_' . date("ymd") . '_SRV.log';
        $row = date("[Y-m-d h:i:s] ") . "[{$this->cmdReturnVar}] {$this->cmd}\n";
        foreach ($this->output as $line) $row .= "$line\n";
        $row .= "\n";
        file_put_contents($logFile, $row, FILE_APPEND);
    }

}