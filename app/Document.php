<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use \Nadar\Stemming\Stemm;

class Document extends Model
{
    // When we index a document, we will first
    // remove the document from the collection,
    // then we will add it.
    // 
    // Removing a document means to enumerate
    // all terms that appear in the document.
    // Then for each such term, we decrement
    // the number of documents it appears in
    // (in the vocabulary table).
    // Then we delete all occurrences of the 
    // document from the terms table.
    //
    // Adding a document means to compute the
    // terms in that document, together with 
    // the number of times that term occurs.
    // Then for each term that occurs in the document,
    // we increment the number of documents
    // in the vocabulary table, and we add the
    // term with number of occurrences to the 
    // terms table.
    //
    // TODO: Think about design alternatives.
    // Maybe we need to measure to decide which
    // option to select.
    //
    // * Instead of going over the terms and
    // manually deleting each vocabulary entry,
    // we could also do it with a single
    // statement: delete from vocabularies
    // where term in (select term from terms
    // where document_id = ?)
    //
    // * Maybe we can skip the vocabularies table
    // entirely, and maybe we can dynamically
    // compute the document frequency (number of
    // documents with this term) using something
    // like: select count(*) from select distinct
    // term from terms where document_id = ?
    //
    // * We will want to add some indexes.
    
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
    
    // TODO add a test for computeDocumentTerms
    // The vocabulary table gets a number instead
    // of the term.  Is it the term id?  Is it 
    // some count?  I'm not clear.
    // Update: this has now been fixed, but a test
    // would be good, anyway.
    
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
        $words = preg_split("/[ .,;:()!?-]+/", $s);
        // If the word begins or ends with a quote, remove the
        // quote.  (In this way, we will keep the apostrophe
        // in "don't", for example.)
        $clean_words = array_map(
            function ($w) {
                $w = preg_replace("/^['\"]+/", "", $w);
                $w = preg_replace("/['\"]+$/", "", $w);
                return $w;
            },
            $words
        );
        // Eliminate the empty string.
        $nonempty_words = array_filter(
            $clean_words,
            function ($w) {
                return ! empty($w);
            }
        );
        // Lower-case
        $lower_words = array_map(
            function ($w) {
                return strtolower($w);
            },
            $nonempty_words
        );
        $stems = array_map(
            function ($w) {
                return Stemm::stem($w, 'en');
            },
            $lower_words
        );
        foreach ($stems as $stem) {
            if (! array_key_exists($stem, $result)) {
                $result[$stem] = 0;
            }
            $result[$stem] += 1;
        }
        return $result;
    }
}
