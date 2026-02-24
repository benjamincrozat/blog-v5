<?php

namespace App\Markdown\Extensions;

use League\CommonMark\Extension\ExtensionInterface;
use Tempest\Highlight\CommonMark\CodeBlockRenderer;
use Tempest\Highlight\CommonMark\InlineCodeBlockRenderer;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;

/**
 * Provides custom renderers extension markdown behavior.
 */
class CustomRenderersExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment) : void
    {
        $environment
            ->addRenderer(FencedCode::class, new CodeBlockRenderer, 100)
            ->addRenderer(Code::class, new InlineCodeBlockRenderer, 100);
    }
}
