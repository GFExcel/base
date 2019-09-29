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
     * @param int $form_id The form id to retrieve the entries from.
     * @param string[] $search_criteria (Optional) search criteria.
     * @param string [] $sorting (Optinal) sorting criteria.
     * @return mixed[]|iterable All entries for a form.
     */
    public function getEntries(int $form_id, array $search_criteria = [], array $sorting = []): iterable;

    /**
     * Should return the download url of the form.
     * @since $ver$
     * @param int $form_id The form id to retrieve the url for.
     * @return string|null The url, or null if not avaialbe.
     */
    public function getDownloadUrl(int $form_id): ?string;
}