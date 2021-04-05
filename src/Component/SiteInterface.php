<?php

namespace App\Component;

interface SiteInterface
{
    public function getName(): string;
    public function hasChanged(): bool;
    public function getProductUrl(): string;
}