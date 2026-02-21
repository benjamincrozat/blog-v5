<?php

namespace App\Actions;

use Exception;
use App\Scraper\Webpage;
use Illuminate\Support\Facades\Process;

/**
 * Defines the Scrape implementation.
 */
class Scrape
{
    public function scrape(string $url, ?string $proxyServer = null) : Webpage
    {
        $pythonPath = base_path('venv/bin/python');

        $scriptPath = base_path('scraper.py');

        $proxyUsername = config('services.smartproxy.proxy_username');

        $proxyPassword = config('services.smartproxy.proxy_password');

        $command = escapeshellarg($pythonPath);
        $command .= ' ' . escapeshellarg($scriptPath);
        $command .= ' ' . escapeshellarg($url);

        if ($proxyServer) {
            $command .= ' --proxy ' . escapeshellarg("https://$proxyServer");

            if (! empty($proxyUsername)) {
                $command .= ' --proxy-username ' . escapeshellarg($proxyUsername);
            }

            if (! empty($proxyPassword)) {
                $command .= ' --proxy-password ' . escapeshellarg($proxyPassword);
            }
        }

        $process = Process::run($command);

        if ($process->failed()) {
            throw new Exception($process->output() . "\n\n" . $process->errorOutput());
        }

        $decoded = json_decode($process->output(), true);

        if (! is_array($decoded)) {
            throw new Exception('Invalid scraper output.');
        }

        return new Webpage(...$decoded);
    }
}
