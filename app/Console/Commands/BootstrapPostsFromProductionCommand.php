<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use App\MarkdownSync\BootstrapPostsFromProduction;
use Symfony\Component\Console\Attribute\AsCommand;

/**
 * Bootstraps markdown post files from the production database content.
 */
#[AsCommand(
    name: 'app:bootstrap-posts-from-production',
    description: 'Export active production posts to markdown files'
)]
class BootstrapPostsFromProductionCommand extends Command
{
    public function handle(BootstrapPostsFromProduction $bootstrap) : int
    {
        $directory = (string) ($this->option('directory') ?: 'resources/markdown/posts');
        $result = $bootstrap->handle($directory);

        $this->line('Production bootstrap summary');
        $this->table(
            ['Scanned', 'Exported', 'Skipped', 'Images uploaded', 'Images reused', 'Errors'],
            [[
                $result->scanned,
                $result->exported,
                $result->skipped,
                $result->imagesUploaded,
                $result->imagesReused,
                count($result->errors),
            ]]
        );

        if ($result->hasErrors()) {
            foreach ($result->errors as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $this->info('Production posts have been exported to markdown.');

        return self::SUCCESS;
    }

    protected function configure() : void
    {
        $this->addOption(
            'directory',
            null,
            InputOption::VALUE_OPTIONAL,
            'The markdown directory to export into',
            'resources/markdown/posts',
        );
    }
}
