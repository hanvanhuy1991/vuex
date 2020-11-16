<?php

namespace App\Http\Traits;

trait FullTextSearch
{
    /**
     * Convert text.
     *
     * @param [string] term.
     * @return [string] term converted.
     */

    protected function fullTextWildcards($term)
    {
        $reservedSymbols = ['-', '+', '<', '>', '@', '(', ')', '~'];
        $term = str_replace($reservedSymbols, '', $term);
        $words = explode(' ', $term);
        foreach ($words as $key => $word) {
            if (strlen($word) >= 1) {
                $words[$key] = '+' . $word  . '*';
            }
        }
        $searchTerm = implode(' ', $words);

        return $searchTerm;
    }

    /**
     * Query match against content.
     *
     * @param [query] query.
     * @param [array] columns.
     * @param [string] term.
     * @return [query] builder.
     */
    public function fullTextSearch($query, $columns, $term)
    {
        $column = implode(',', $columns);

        return $query->whereRaw("MATCH ({$column}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
    }

    /**
     * Query match against content.
     *
     * @param [query] query.
     * @param [array] columns.
     * @param [array] terms.
     * @return [query] builder.
     */
    public function fullTextSearchByArray($query, $columns, $terms)
    {
        $column = implode(',', $columns);

        return $query->where(function ($q) use ($column, $terms) {
            foreach ($terms as $term) {
                $q->orWhereRaw("MATCH ({$column}) AGAINST (? IN BOOLEAN MODE)", $this->fullTextWildcards($term));
            }
        });
    }
}
