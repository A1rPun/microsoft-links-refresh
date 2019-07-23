<?php

namespace A1rPun\MicrosoftLinksRefresh;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpFoundation\Response;

class MicrosoftLinksRefresh implements EventSubscriberInterface
{
    const USER_AGENTS_REGEX = "/(Word|Excel|PowerPoint|ms-office)/";
    const EXCLUDE_USER_AGENTS_REGEX = "/Microsoft Outlook/";
    const REFRESH_RESPONSE = '<html><head><meta http-equiv="refresh" content="0"/></head><body></body></html>';

    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        $userAgent = $request->headers->get('User-Agent');

        if (self::matchesUserAgent($userAgent)) {
            $event->setResponse(new Response(self::REFRESH_RESPONSE), 200);
        }
    }
    
    public static function getSubscribedEvents()
    {
        return array('kernel.request' => 'onKernelRequest');
    }
    
    private function matchesUserAgent(string $userAgent): bool
    {
        return preg_match(self::USER_AGENTS_REGEX, $userAgent) && !preg_match(self::EXCLUDE_USER_AGENTS_REGEX, $userAgent);
    }
}
