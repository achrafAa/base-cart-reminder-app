<?php

namespace Achraf\framework\Interfaces;

interface QueueableInterface
{
    public function handle(): void;
}
