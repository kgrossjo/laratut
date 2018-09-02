<?php

namespace App\Http\Controllers;

use \App\Document;
use \App\Vocabulary;
use \App\Term;
use \App\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use \Nadar\Stemming\Stemm;

class SearchController extends Controller
{
    /**
     * @return \Illuminate\Http\Response
     */
    public function showSearchForm()
    {
        return view('search.form');
    }

    /**
     * @return \Illuminate\Http\Response
     */
    public function executeSearch(Request $request)
    {
        $query = $request->input('query');
        $words = Helper::splitStringIntoStems($query);
        // For each word in the query, we find the matching
        // documents.
        $matches = $this->computeMatchesForWords($words);
        // Sort by relevance
        $result = $this->rankMatches($matches);
        return view('search.result', ['result' => $result]);
    }

    function computeMatchesForWords($words)
    {
        $result = [];
        foreach ($words as $w) {
            $result[$w] = $this->computeMatchesForOneWord($w);
        }
        return $result;
    }

    function computeMatchesForOneWord($word)
    {
        $document_frequency = 0;
        $vocabularies = Vocabulary::query()->where('term', '=', $word)->get();
        // There should be only one result, but it's easiest to just iterate.
        foreach ($vocabularies as $v) {
            $document_frequency = $v->ndocuments;
        }

        $terms = Term::query()->where('term', '=', $word)->get();
        return [
            'term' => $word,
            'document_frequency' => $document_frequency,
            'documents' => $terms
        ];
    }

    function rankMatches($matches)
    {
        $ndocuments = Document::query()->count();
        $scores = [];
        foreach ($matches as $m) {
            $df = $m['document_frequency'];
            if ($df == 0) continue;
            // If the term appears in more than half of all
            // documents, then it's too frequent, and we will
            // ignore it.  It is very likely that the term is
            // a "stop word" (e.g. "the", "and" -- words that 
            // don't carry much meaning and appear in almost
            // every text).
            if ($df * 2 > $ndocuments) continue;
            $documents = $m['documents'];
            foreach ($documents as $d) {
                if (! array_key_exists($d->document_id, $scores)) {
                    $scores[$d->document_id] = 0;
                }
                $scores[$d->document_id] += $d->count/$df;
            }
        }
        $result = [];
        foreach ($scores as $doc_id => $score) {
            $result []= [ 'id' => $doc_id, 'score' => $score ];
        }
        usort($result, function($a, $b) {
            if ($a['score'] < $b['score']) return +1;
            if ($a['score'] > $b['score']) return -1;
            return 0;
        });
        return $result;
    }
}
