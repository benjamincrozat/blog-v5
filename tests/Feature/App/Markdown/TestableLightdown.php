<?php

namespace Tests\Feature\App\Markdown;

use App\Markdown\Lightdown;
use League\CommonMark\Node\Node;

class TestableLightdown extends Lightdown
{
    public static function textFromChildren(Node $node) : string
    {
        return parent::childrenToText($node);
    }
}
