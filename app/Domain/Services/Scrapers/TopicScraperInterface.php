<?php

namespace App\Domain\Services\Scrapers;

interface TopicScraperInterface
{
    public function fetch(): array;

    public function getSourceType(): string;
}
