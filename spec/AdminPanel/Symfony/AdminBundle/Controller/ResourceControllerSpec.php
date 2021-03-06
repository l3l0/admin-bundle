<?php


namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ResourceControllerSpec extends ObjectBehavior
{
    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    function let($manager, $templating)
    {
        $this->beConstructedWith($templating, $manager, 'default_resource');
    }

    /**
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    function it_dispatch_event_if_displatcher_present(
        $dispatcher,
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    )
    {
        $this->setEventDispatcher($dispatcher);

        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_resource', array(), null)->willReturn($response);

        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \Symfony\Component\HttpFoundation\Request $request
     */
    function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        $element,
        $manager,
        $request
    )
    {
        $element->getId()->willReturn('my_awesome_resource');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('resourceAction', array($element, $request));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext $context
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     */
    function it_render_default_template_in_resource_action(
        $request,
        $response,
        $element,
        $manager,
        $context,
        $templating
    )
    {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn(array());

        $templating->renderResponse('default_resource', array(), null)->willReturn($response);
        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Bundle\FrameworkBundle\Templating\DelegatingEngine $templating
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_render_template_from_element_in_resource_action(
        $manager,
        $element,
        $context,
        $request,
        $templating,
        $response
    )
    {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn(array());

        $templating->renderResponse('custom_template', array(), null)->willReturn($response);
        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    /**
     * @param \AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager $manager
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element $element
     * @param \AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Context\ResourceRepositoryContext $context
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Symfony\Component\HttpFoundation\Response $response
     */
    function it_return_response_from_context_in_resource_action(
        $manager,
        $element,
        $context,
        $request,
        $response
    )
    {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->resourceAction($element, $request)->shouldReturn($response);
    }
}
