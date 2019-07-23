<?php

namespace A1rPun\MicrosoftLinksRefresh;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class MicrosoftLinksRefresh
{
    const USER_AGENTS_REGEX = "/[^\w](Word|Excel|PowerPoint|ms-office|Konqueror.+KIO)([^\w]|\z)/";
    const EXCLUDE_USER_AGENTS_REGEX = "/Microsoft Outlook/";

    public function init(): void
    {
        // Register component
    }

    public function matching_user_agent(string $user_agent): bool
    {
        return preg_match(self::USER_AGENTS_REGEX, $user_agent) && !preg_match(self::EXCLUDE_USER_AGENTS_REGEX, $user_agent);
    }

    public function handle(): Response
    {
        $user_agent = 'Microsoft Outlook';
        if (self->matching_user_agent($user_agent)) {
            // If user agent is office app, refresh page
            $responseBody = "<html><head><meta http-equiv='refresh' content='0'/></head><body></body></html>";
            return new Response([
                'message' => $responseBody
            ]);
        }
    }
}
