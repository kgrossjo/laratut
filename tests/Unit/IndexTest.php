<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Document;

class IndexTest extends TestCase
{
    /**
     * Test that we compute the correct
     * terms when indexing a document.
     */
    public function testComputeDocumentTerms()
    {
        $doc = new Document();
        $doc->title = "ta tb tc";
        $doc->content = "The quick brown fox jumps over the lazy dog.";
        $terms = $doc->computeDocumentTerms();
        $just_the_words = array_keys($terms);
        sort($just_the_words);
        $this->assertEquals(
            ['brown', 'dog', 'fox', 'jump', 'lazi', 'over', 'quick', 'ta', 'tb', 'tc', 'the'],
            $just_the_words,
            "Sorted words are as expected"
        );
    }
}
