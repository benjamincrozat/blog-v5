<?php

namespace App;

use Tempest\Highlight\CommonMark\CodeBlockRenderer;
use League\CommonMark\GithubFlavoredMarkdownConverter;
use Tempest\Highlight\CommonMark\InlineCodeBlockRenderer;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;

class Str extends \Illuminate\Support\Str
{
    public static function markdown($string, array $options = [], array $extensions = []) : string  // @pest-ignore-type
    {
        $converter = new GithubFlavoredMarkdownConverter($options);

        $environment = $converter
            ->getEnvironment()
            ->addRenderer(FencedCode::class, new CodeBlockRenderer)
            ->addRenderer(Code::class, new InlineCodeBlockRenderer);

        foreach ($extensions as $extension) {
            $environment->addExtension($extension);
        }

        return (string) $converter->convert($string);
    }
}
