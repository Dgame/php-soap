<?php

namespace Dgame\Soap\Component;

use Dgame\Soap\XmlElement;
use Exception;

/**
 * Class Request
 * @package Dgame\Soap\Component
 */
final class Request extends NamedNode
{
    /**
     * Request constructor.
     *
     * @param BiPROVersion $version
     */
    public function __construct(BiPROVersion $version)
    {
        parent::__construct();

        $this->appendElement($version);
    }

    /**
     * @param bool $confirm
     */
    public function setConfirm(bool $confirm)
    {
        $this->search('BestaetigeLieferungen')->setValue($confirm ? 'true' : 'false');
    }

    /**
     * @param int $id
     */
    public function setId(int $id)
    {
        $this->search('ID')->setValue(1);
    }

    /**
     * @param int $id
     */
    public function setConsumerId(int $id)
    {
        $this->search('ConsumerID')->setValue(1);
    }

    /**
     * @param string $name
     *
     * @return XmlElement
     */
    private function search(string $name): XmlElement
    {
        foreach ($this->getElements() as $element) {
            if ($element->getName() === $name) {
                return $element;
            }
        }

        $element = new XmlElement($name);
        $this->appendElement($element);

        return $element;
    }
}