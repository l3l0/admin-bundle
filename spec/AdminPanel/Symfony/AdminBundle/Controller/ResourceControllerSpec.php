<?php

declare(strict_types=1);

namespace spec\AdminPanel\Symfony\AdminBundle\Controller;

use AdminPanel\Symfony\AdminBundle\Admin\Context\ContextManager;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\Context\ListElementContext;
use AdminPanel\Symfony\AdminBundle\Admin\CRUD\ListElement;
use AdminPanel\Symfony\AdminBundle\Admin\ResourceRepository\Element;
use AdminPanel\Symfony\AdminBundle\Doctrine\Admin\ResourceElement;
use AdminPanel\Symfony\AdminBundle\Event\AdminEvents;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceControllerSpec extends ObjectBehavior
{
    public function let(ContextManager $manager, EngineInterface $templating)
    {
        $this->beConstructedWith($templating, $manager, 'default_resource');
    }

    public function it_dispatch_event_if_displatcher_present(
        EventDispatcherInterface $dispatcher,
        Request $request,
        Response $response,
        ResourceElement $element,
        ContextManager $manager,
        ListElementContext $context,
        EngineInterface $templating
    ) {
        $this->setEventDispatcher($dispatcher);

        $dispatcher->dispatch(
            AdminEvents::CONTEXT_PRE_CREATE,
            Argument::type('AdminPanel\Symfony\AdminBundle\Event\AdminEvent')
        )->shouldBeCalled();

        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_resource', [], null)->willReturn($response);

        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    public function it_throw_exception_when_cant_find_context_builder_that_supports_admin_element(
        Element $element,
        ContextManager $manager,
        Request $request
    ) {
        $element->getId()->willReturn('my_awesome_resource');
        $manager->createContext(Argument::type('string'), $element)->willReturn(null);

        $this->shouldThrow('Symfony\Component\HttpKernel\Exception\NotFoundHttpException')
            ->during('resourceAction', [$element, $request]);
    }

    public function it_render_default_template_in_resource_action(
        Request $request,
        Response $response,
        ResourceElement $element,
        ContextManager $manager,
        ListElementContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(false);
        $context->getData()->willReturn([]);

        $templating->renderResponse('default_resource', [], null)->willReturn($response);
        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    public function it_render_template_from_element_in_resource_action(
        Request $request,
        Response $response,
        ResourceElement $element,
        ContextManager $manager,
        ListElementContext $context,
        EngineInterface $templating
    ) {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn(null);
        $context->hasTemplateName()->willReturn(true);
        $context->getTemplateName()->willReturn('custom_template');
        $context->getData()->willReturn([]);

        $templating->renderResponse('custom_template', [], null)->willReturn($response);
        $this->resourceAction($element, $request)->shouldReturn($response);
    }

    public function it_return_response_from_context_in_resource_action(
        Request $request,
        Response $response,
        ResourceElement $element,
        ContextManager $manager,
        ListElementContext $context
    ) {
        $manager->createContext('fsi_admin_resource', $element)->willReturn($context);
        $context->handleRequest($request)->willReturn($response);

        $this->resourceAction($element, $request)->shouldReturn($response);
    }
}
