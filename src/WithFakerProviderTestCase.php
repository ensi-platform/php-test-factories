<?php

namespace Ensi\TestFactories;

trait WithFakerProviderTestCase
{
    protected function setUpWithFakerProviderTestCase(): void
    {
        FakerProvider::$optionalAlways = null;
    }
}
