<?php

namespace Kingmaker\Filament\FilterSets\Tests\Fixtures;

use Illuminate\Database\Eloquent\Model;

class TestPost extends Model
{
    protected $table = 'test_posts';

    protected $guarded = [];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'word_count' => 'integer',
        ];
    }
}
