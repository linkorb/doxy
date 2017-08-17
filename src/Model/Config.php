<?php

namespace Doxy\Model;

use Collection\TypedArray;

class Config extends BaseModel
{
    protected $servers;

    public function __construct()
    {
        $this->servers = new TypedArray(Server::class);
    }
}
