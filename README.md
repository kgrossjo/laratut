# laratut
Simple Laravel app, used for learning

## What does it do?

It's a typical CRUD app for entering "documents". A document only has two
fields, the title and the actual content. (Well, we also have the standard
Laravel fields for id and timestamps.)

But whenever a document is saved, we split the document text into words and
build a word index.

There is a search form that allows you to enter a query, and searching uses
the word index previously built.

The key point for searching is that it does ranking, i.e. it attempts to put
the most relevant document first. To illustrate:

* If you search for two words, then documents that contain both words will rank
  higher than documents that contain only one of the two words.
* If you search for two words, one rare word and one common word, then
  documents with the rare word will rank higher than documents with the common
  word. (Rationale: Say there is a word that only appears in one document. Then
  it's very likely that this is the right document when you search for that
  word.)

## TODO - What else needs to happen?

The code for the following items is in `\App\Document`.

* Instead of going over the terms and manually deleting each vocabulary 
  entry, we could also do it with a single statement:
  `delete from vocabularies where term in (select term from terms where document_id = ?)`

* Maybe we can skip the vocabularies table entirely, and maybe we can
  dynamically compute the document frequency (number of documents with this
  term) using something like: select count(*) from select distinct term from
  terms where document_id = ?

* We will want to add some indexes.
    

## Internal: How does indexing work?

See `\App\Document` for the actual logic.

When we index a document, we will first remove the document from the
collection, then we will add it.

Removing a document means to enumerate all terms that appear in the document.
Then for each such term, we decrement the number of documents it appears in
(in the vocabulary table).
Then we delete all occurrences of the document from the terms table.

Adding a document means to compute the terms in that document, together with
the number of times that term occurs. Then for each term that occurs in the
document, we increment the number of documents in the vocabulary table, and
we add the term with number of occurrences to the terms table.