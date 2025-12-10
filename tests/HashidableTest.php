<?php

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Workbench\App\Models\CustomConfigModel;
use Workbench\App\Models\TestModel;

beforeEach(function () {
    // Create a test model for each test
    $this->model = TestModel::query()->create(['name' => 'Test']);
});

test('generates hashid for model', function () {
    expect($this->model->hashid)->toBeString()
        ->and(strlen($this->model->hashid))->toBeGreaterThanOrEqual(16);
});

test('finds model by hashid', function () {
    $found = TestModel::findByHashid($this->model->hashid);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($this->model->id);
});

test('returns null for invalid hashid', function () {
    $found = TestModel::findByHashid('invalid-hashid-here');

    expect($found)->toBeNull();
});

test('throws exception with findByHashidOrFail for invalid hashid', function () {
    TestModel::findByHashidOrFail('invalid-hashid-here');
})->throws(ModelNotFoundException::class);

test('uses hashid as route key', function () {
    expect($this->model->getRouteKey())->toBe($this->model->hashid)
        ->and($this->model->getRouteKeyName())->toBe('hashid');
});

test('resolves route binding with hashid', function () {
    $resolved = $this->model->resolveRouteBinding($this->model->hashid);

    expect($resolved)->not->toBeNull()
        ->and($resolved->id)->toBe($this->model->id);
});

test('generates consistent hashids', function () {
    $hashid1 = $this->model->hashid;
    $hashid2 = $this->model->hashid;

    expect($hashid1)->toBe($hashid2);
});

test('generates different hashids for different IDs', function () {
    $model2 = TestModel::query()->create(['name' => 'Test 2']);

    expect($this->model->hashid)->not->toBe($model2->hashid);
});

test('hashid is reversible', function () {
    $hashid = $this->model->hashid;
    $found = TestModel::findByHashid($hashid);

    expect($found->id)->toBe($this->model->id)
        ->and($found->name)->toBe($this->model->name);
});

test('per-model config works correctly', function () {
    $customModel = CustomConfigModel::query()->create(['name' => 'Custom']);
    $hashid = $customModel->hashid;

    // Should have prefix and suffix
    expect($hashid)->toStartWith('custom_')
        ->and($hashid)->toEndWith('_v1');
});

test('prefix and suffix are applied correctly', function () {
    $customModel = CustomConfigModel::query()->create(['name' => 'Custom']);
    $hashid = $customModel->hashid;

    // Verify format: prefix_hash_suffix
    $parts = explode('_', $hashid);
    expect($parts[0])->toBe('custom')
        ->and(end($parts))->toBe('v1');
});

test('can find model with prefixed hashid', function () {
    $customModel = CustomConfigModel::query()->create(['name' => 'Custom']);
    $hashid = $customModel->hashid;

    $found = CustomConfigModel::findByHashid($hashid);

    expect($found)->not->toBeNull()
        ->and($found->id)->toBe($customModel->id);
});
