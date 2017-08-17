<?php

namespace Doxy\Model;

use Boost\BoostTrait;
use Boost\Accessors\ProtectedGettersTrait;
use Boost\Accessors\ProtectedSettersTrait;
use Boost\Accessors\ProtectedAccessorsTrait;

abstract class BaseModel
{
    use BoostTrait;
    use ProtectedAccessorsTrait;
}
