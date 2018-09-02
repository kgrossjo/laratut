<?php

namespace App;

use \App\Helper;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    
    public function removeDocumentFromIndex()
    {
        // Get list of terms in the document from
        // terms table.
        $terms = Term::query()->where('document_id', '=', $this->id)->get();
        // Decrement number of documents this term 
        // appears in.
        foreach ($terms as $term) {
            $vocabularies = Vocabulary::query()->where('term', '=', $term->term)->get();
            // We're only expecting one, but it's easy to iterate.
            foreach ($vocabularies as $v) {
                $v->ndocuments -= 1;
                if ($v->ndocuments < 0) {
                    // something went wrong...
                    $v->ndocuments = 0;
                }
                $v->save();
            }
        }
        // Delete all terms for this document.
        Term::query()->where('document_id', '=', $this->id)->delete();
    }
    
    public function addDocumentToIndex()
    {
        // keys of $terms are words, values are number
        // times this term occurs in this document
        $terms = $this->computeDocumentTerms();
        // Add a record for each term.
        foreach ($terms as $term => $tf) {
            $obj = new Term();
            $obj->term = $term;
            $obj->document_id = $this->id;
            $obj->count = $tf;
            $obj->save();
        }
        // Bump the document frequency for each term.
        foreach ($terms as $term => $tf) {
            $vocabularies = Vocabulary::query()->where('term', '=', $term)->get();
            $found = false;
            // Should be only one but it's easy to iterate.
            foreach ($vocabularies as $v) {
                $found = true;
                $v->ndocuments += 1;
                $v->save();
            }
            if (! $found) {
                $v = new Vocabulary();
                $v->term = $term;
                $v->ndocuments = 1;
                $v->save();
            }
        }
    }
    
    // We take the title of the document, followed by
    // the content of the document, as a long string.
    // We split this into words.  We clean each word.
    // We stem each (cleaned) word.
    // Now we count the number of times each stem occurs.
    // Return hash mapping stem to number.
    public function computeDocumentTerms()
    {
        $result = [];
        $s = $this->title . ' ' . $this->content;
        $stems = Helper::splitStringIntoStems($s);
        foreach ($stems as $stem) {
            if (! array_key_exists($stem, $result)) {
                $result[$stem] = 0;
            }
            $result[$stem] += 1;
        }
        return $result;
    }
}
