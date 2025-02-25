<?php

namespace Tests\Unit;

use App\Models\Musician;
use Tests\TestCase;

class MusicianTest extends TestCase
{
    public function test_vocalist_is_properly_extracted()
    {
        $musician = Musician::create([
            'name' => 'John Doe',
            'instruments' => ['Vocals', 'Guitar'],
            'other' => null,
        ]);

        $this->assertTrue($musician->vocalist);
        $this->assertEquals(['guitar'], $musician->instruments);
        $this->assertEquals(['Vocals', 'guitar'], $musician->all_instruments);
    }

    public function test_non_vocalist_is_properly_handled()
    {
        $musician = Musician::create([
            'name' => 'Jane Doe',
            'instruments' => ['Guitar', 'Bass'],
            'other' => null,
        ]);

        $this->assertFalse($musician->vocalist);
        $this->assertEquals(['guitar', 'bass'], $musician->instruments);
    }
}
