<?php

declare(strict_types=1);

namespace MediaMonks\Muban\EventSubscriber;

use MediaMonks\Muban\Component\Components;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Twig\Environment;
use Twig\Error\LoaderError;

class RequestSubscriber implements EventSubscriberInterface
{
    private static string $defaultRoute = 'muban/view';

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
            ['processRequest', 2049],
        ]];
    }

    public function processRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        if ($request->getMethod() !== Request::METHOD_POST && !str_ends_with($request->getUri(), $this->route ?? self::$defaultRoute)) {
            return;
        }

        $body = json_decode($request->getContent(), true);

        $component = $this->components->get($body['component']);
        $params = $body['parameters'];

        $violations = $this->validator->validate($params, $component->rules());
        if ($violations->count() > 0) {
            throw new ValidationFailedException('', $violations);
        }

        try {
            $event->setResponse(new Response($this->twig->render('@MediaMonksMuban/component/' . $component->getComponent() . '.html.twig', [
                'component' => $component::fromArray($params),
            ])));
        } catch (LoaderError $e) {
            $event->setResponse(new Response(null, Response::HTTP_NOT_FOUND));
        }
    }
}