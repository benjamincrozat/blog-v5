<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Actions\Sitemaps\GenerateSitemap;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Makes sitemap generation available as the app:generate-sitemap command.
 *
 * GenerateSitemap chooses the content and writes both files. This command only
 * starts that work and prints the regular sitemap path. It does not submit either
 * file to a search engine.
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
