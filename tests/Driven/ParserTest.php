<?php

namespace Tests\Driven;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ParserTest extends TestCase
{
    public function testParseMethod() {
        $parser = new Parser();
    }
}
