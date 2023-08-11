<?php

namespace Tests\Unit\App\Models;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\TestCase;

abstract class ModelTestCase extends TestCase
{
    abstract protected function model(): Model;
    abstract protected function expectedTraits(): array;
    abstract protected function expectedFillable(): array;
    abstract protected function expectedCasts(): array;

    public function test_traits(): void
    {
        $traits = array_keys(class_uses($this->model()));
        $this->assertEquals($this->expectedTraits(), $traits);
    }

    public function test_fillable()
    {
        $fillable = $this->model()->getFillable();
        $this->assertEquals($this->expectedFillable(), $fillable);
    }

    public function test_increments_must_be_false()
    {
        $increments = $this->model()->incrementing;
        $this->assertFalse($increments);
    }

    public function test_has_casts()
    {
        $cats = $this->model()->getCasts();
        $this->assertEquals($this->expectedCasts(), $cats);
    }
}
