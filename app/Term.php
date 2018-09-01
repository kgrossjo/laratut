<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * For each term, we list the document it appears
 * in, and then the number of times this term
 * appears in this document.
 * 
 * I guess the document id should be an fkey
 * into the documents table, and the term
 * should be an fkey into the vocabulary table.
 * 
 * @author kgrossjo
 *
 */
class Term extends Model
{
    //
}
