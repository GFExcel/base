<?php

namespace GFExcel\Repository;

interface FormRepositoryInterface
{
    /**
     * Should retrieve every entry for a form id.
     *
     * Method should return an iterable, be it a generator, Iterator or a flat array. The resulting items should be
     * the default entry array Gravity Forms returns.
     *
     * @since $ver$
     * @param int $form_id
     * @param array $search_criteria
     * @param array $sorting
     * @return iterable All entries for a form.
     */
    public function getEntries(int $form_id, array $search_criteria = [], array $sorting = []): iterable;
}