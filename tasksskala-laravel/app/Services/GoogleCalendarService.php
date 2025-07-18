<?php

namespace App\Services;

use Google\Client;
use Google\Service\Calendar;
use App\Models\GoogleOAuthToken;
use App\Models\Colaborador;
use Carbon\Carbon;

class GoogleCalendarService
{
    private Client $client;
    
    public function __construct()
    {
        $this->client = new Client();
        $this->client->setClientId(config('services.google.client_id'));
        $this->client->setClientSecret(config('services.google.client_secret'));
        $this->client->setRedirectUri(config('services.google.redirect_uri'));
        $this->client->setAccessType('offline');
        $this->client->setPrompt('consent');
        $this->client->setScopes([
            Calendar::CALENDAR_READONLY,
            Calendar::CALENDAR_EVENTS_READONLY
        ]);
    }
    
    public function getAuthorizationUrl(): string
    {
        return $this->client->createAuthUrl();
    }
    
    public function handleCallback(string $code, Colaborador $colaborador): void
    {
        $token = $this->client->fetchAccessTokenWithAuthCode($code);
        
        if (isset($token['error'])) {
            throw new \Exception('Erro ao obter token: ' . $token['error']);
        }
        
        $colaborador->googleOAuthToken()->updateOrCreate(
            ['colaborador_id' => $colaborador->id],
            [
                'access_token' => $token['access_token'],
                'refresh_token' => $token['refresh_token'] ?? null,
                'expires_in' => $token['expires_in'],
                'token_created_at' => now(),
            ]
        );
    }
    
    public function getUpcomingEvents(Colaborador $colaborador, int $maxResults = 10): array
    {
        $oauthToken = $colaborador->googleOAuthToken;
        
        if (!$oauthToken) {
            return [];
        }
        
        if ($oauthToken->isExpired() && $oauthToken->refresh_token) {
            $this->refreshToken($oauthToken);
        }
        
        $this->client->setAccessToken($oauthToken->access_token);
        
        $service = new Calendar($this->client);
        $calendarId = config('services.google.calendar_id', 'primary');
        
        $optParams = [
            'maxResults' => $maxResults,
            'orderBy' => 'startTime',
            'singleEvents' => true,
            'timeMin' => now()->toRfc3339String(),
            'timeMax' => now()->addDays(30)->toRfc3339String(),
        ];
        
        try {
            $results = $service->events->listEvents($calendarId, $optParams);
            $events = $results->getItems();
            
            return array_map(function ($event) {
                $start = $event->getStart();
                $end = $event->getEnd();
                
                return [
                    'id' => $event->getId(),
                    'summary' => $event->getSummary(),
                    'description' => $event->getDescription(),
                    'location' => $event->getLocation(),
                    'start' => $start->getDateTime() ?: $start->getDate(),
                    'end' => $end->getDateTime() ?: $end->getDate(),
                    'is_all_day' => !$start->getDateTime(),
                    'html_link' => $event->getHtmlLink(),
                ];
            }, $events);
        } catch (\Exception $e) {
            return [];
        }
    }
    
    private function refreshToken(GoogleOAuthToken $oauthToken): void
    {
        $this->client->setAccessToken($oauthToken->access_token);
        $this->client->setRefreshToken($oauthToken->refresh_token);
        
        $newToken = $this->client->fetchAccessTokenWithRefreshToken();
        
        if (isset($newToken['error'])) {
            throw new \Exception('Erro ao renovar token: ' . $newToken['error']);
        }
        
        $oauthToken->update([
            'access_token' => $newToken['access_token'],
            'expires_in' => $newToken['expires_in'],
            'token_created_at' => now(),
        ]);
    }
    
    public function revokeAccess(Colaborador $colaborador): bool
    {
        $oauthToken = $colaborador->googleOAuthToken;
        
        if (!$oauthToken) {
            return true;
        }
        
        try {
            $this->client->revokeToken($oauthToken->access_token);
            $oauthToken->delete();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}