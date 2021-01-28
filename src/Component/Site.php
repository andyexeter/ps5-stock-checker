<?php

namespace App\Component;

interface Site
{
    public function getName(): string;
    public function hasChanged(): bool;
    public function getProductUrl(): string;
}