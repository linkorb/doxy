<?php

namespace Doxy\Model;

use Collection\TypedArray;
use Collection\Identifiable;

class Server extends BaseModel implements Identifiable
{
    protected $name;
    protected $containerIp;
    protected $containerPort;
    protected $letsencryptEmail;
    protected $ssl = false;

    public function __construct($name)
    {
        $this->setName($name);
    }

    public function identifier()
    {
        return $this->name;
    }

    public function getLetsencrypt()
    {
        if ($this->letsencryptEmail) {
            return true;
        }
        return false;
    }
}
