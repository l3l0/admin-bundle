<?php


namespace spec\AdminPanel\Symfony\AdminBundle\Admin;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ManagerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Element $element
     */
    function it_remove_element_by_id($element)
    {
        $element->getId()->willReturn('foo');
        $this->addElement($element);

        $this->hasElement('foo')->shouldReturn(true);
        $this->removeElement('foo');
        $this->hasElement('foo')->shouldReturn(false);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Manager\Visitor $visitor
     */
    function it_accept_visitors($visitor)
    {
        $visitor->visitManager($this)->shouldBeCalled();
        $this->accept($visitor);
    }
}
