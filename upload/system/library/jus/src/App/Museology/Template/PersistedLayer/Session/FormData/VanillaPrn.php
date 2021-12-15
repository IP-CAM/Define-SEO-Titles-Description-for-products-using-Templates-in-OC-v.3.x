<?php

namespace Jus\App\Museology\Template\PersistedLayer\Session\FormData;

use Jus\Core\FormInterface;
use Jus\Foundation\PrinterInterface;
use LogicException;
use DomainException;

/**
 * Printer class
 */
final class VanillaPrn implements PrinterInterface
{
    /**
     * @var array
     */
    private $i;
    
    /**
     * Cntr
     */
    public function __construct()
    {
        $this->i = [];
    }
    
    /**
     * @inheritDoc
     */
    public function with($key, $val)
    {
        $that = $this->blueprinted();
        $that->i[$key] = $val;
        return $that;
    }

    /**
     * @inheritDoc
     * @throws LogicException|DomainException
     * @return array[]
     */
    public function finished()
    {
        if (!isset($this->i['session']) || !is_a($this->i['session'], \Session::class)) {
            throw new LogicException("invalid type");
        }
        if (!isset($this->i['form']) || !$this->i['form'] instanceof FormInterface) {
            throw new LogicException("invalid type");
        }
        if (isset($this->i['session']['_preload'][$this->i['form']->uid()])) {
            $payload = $this->i['session']['_preload'][$this->i['form']->uid()];
        } else {
            $payload = null;
        }
        return $payload;
    }
    
    /**
     * Clones the instance
     * @return VanillaPrn
     */
    public function blueprinted()
    {
        $that = new self();
        $that->i = $this->i;
        return $that;
    }
}