<?php

namespace Beapp\Push\Core\Template;

use Beapp\Push\Core\Push;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Interface PushTemplate
 * @package Beapp\Push\Core\Template
 */
interface PushTemplate
{
    /**
     * @param RouterInterface     $router
     * @param TranslatorInterface $translator
     *
     * @return Push
     */
    public function build(RouterInterface $router, TranslatorInterface $translator): Push;
}
