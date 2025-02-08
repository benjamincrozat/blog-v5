<?php

use Tests\TestCase;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;

pest()
    ->extend(TestCase::class)
    ->use(LazilyRefreshDatabase::class)
    ->in('Feature');
