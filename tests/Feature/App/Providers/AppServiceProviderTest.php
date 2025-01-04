<?php

use Illuminate\Database\Eloquent\Model;

it('makes all models strict by default', function () {
    expect(Model::preventsLazyLoading())->toBeTrue();
    expect(Model::preventsSilentlyDiscardingAttributes())->toBeTrue();
    expect(Model::preventsAccessingMissingAttributes())->toBeTrue();
});

it('makes all models unguarded by default', function () {
    expect(Model::isUnguarded())->toBeTrue();
});
