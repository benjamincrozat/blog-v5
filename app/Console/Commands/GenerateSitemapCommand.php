<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Sitemaps\GenerateSitemap;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Builds the public sitemap.xml file from current content routes.
 */
#[AsCommand(
    name: 'app:generate-sitemap',
    description: 'Generate the sitemap.'
)]
class GenerateSitemapCommand extends Command
{
    public function handle(GenerateSitemap $generateSitemap) : void
    {
        $path = $generateSitemap->handle();

        $this->info("Sitemap generated successfully at $path");
    }
}
