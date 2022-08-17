<?php

declare(strict_types=1);

namespace MediaMonks\Muban\EventSubscriber;

use MediaMonks\Muban\Component\Components;
use MediaMonks\Muban\Component\GenericComponent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;

class RequestSubscriber implements EventSubscriberInterface
{
    private static string $defaultRoute = 'api/muban/view';

    public function __construct(
        private readonly Environment        $twig,
        private readonly Components         $components,
        private readonly ValidatorInterface $validator,
        private readonly ?string            $route = null)
    {

    }

    public static function getSubscribedEvents(): array
    {
        return [KernelEvents::REQUEST => [
            ['processRequest', 249], // subscribe below nelmio cors
        ]];
    }

    public function processRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if (HttpKernelInterface::MAIN_REQUEST !== $event->getRequestType()) {
            return;
        }

        if ($request->getMethod() !== Request::METHOD_POST && !str_ends_with($request->getUri(), $this->route ?? self::$defaultRoute)) return;

        if ($request->getMethod() === Request::METHOD_OPTIONS) {
            $event->setResponse(new Response(null, Response::HTTP_OK, [
                'Access-Control-Allow-Methods' => 'POST',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'Access-Control-Allow-Origin' => '*',
            ]));
            return;
        }

        $body = json_decode($request->getContent());

        // One can register their own component classes in the components collection.
        $component = $this->components->get($body->component) ?? new GenericComponent($body->component, $body->parameters);
        $params = $body->parameters;

        if (!$component instanceof GenericComponent) {
            $violations = $this->validator->validate((array)$params, $component->rules());
            if ($violations->count() > 0) {
                throw new ValidationFailedException('', $violations);
            }
        }

        try {
            $event->setResponse(new Response($this->twig->render('@MediaMonksMuban/component/' . $component->getComponent() . '.html.twig', [
                'component' => $component instanceof GenericComponent ? $component : $component::fromObject($params),
            ]), Response::HTTP_OK,  [
                'Access-Control-Allow-Methods' => 'POST',
                'Access-Control-Allow-Headers' => 'Content-Type, Authorization',
                'Access-Control-Allow-Origin' => '*',
            ]));
        } catch (LoaderError $e) {
            $event->setResponse(new Response(null, Response::HTTP_NOT_FOUND));
        }
    }
}