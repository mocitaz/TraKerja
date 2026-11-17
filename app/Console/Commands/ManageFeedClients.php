<?php

namespace App\Console\Commands;

use App\Models\FeedClient;
use Illuminate\Console\Command;

class ManageFeedClients extends Command
{
    protected $signature = 'feed:client {action : create|list|revoke} {name? : Client name (create) or id (revoke)} {--per-minute=60 : Requests per minute}';

    protected $description = 'Issue, list or revoke read-only feed API keys (TraKerja 2.0, Fase 6)';

    public function handle(): int
    {
        switch ($this->argument('action')) {
            case 'create':
                $name = (string) $this->argument('name');
                if ($name === '') {
                    $this->error('Give the client a name, e.g. feed:client create asep');

                    return self::FAILURE;
                }
                [$client, $key] = FeedClient::issue($name, max(1, (int) $this->option('per-minute')));
                $this->info("Client #{$client->id} \"{$client->name}\" created ({$client->per_minute}/min).");
                $this->line('API key (shown once, store it in the client\'s secrets): '.$key);

                return self::SUCCESS;
            case 'list':
                $this->table(['id', 'name', 'key', 'per min', 'active', 'requests', 'last used'], FeedClient::orderBy('id')->get()
                    ->map(fn ($c) => [$c->id, $c->name, $c->key_prefix.'…', $c->per_minute, $c->is_active ? 'yes' : 'no', $c->requests, $c->last_used_at?->diffForHumans() ?? '–']));

                return self::SUCCESS;
            case 'revoke':
                $client = FeedClient::find((int) $this->argument('name'));
                if (! $client) {
                    $this->error('No client with that id.');

                    return self::FAILURE;
                }
                $client->update(['is_active' => false]);
                $this->info("Revoked client #{$client->id} \"{$client->name}\".");

                return self::SUCCESS;
        }
        $this->error('Action must be create, list or revoke.');

        return self::FAILURE;
    }
}
