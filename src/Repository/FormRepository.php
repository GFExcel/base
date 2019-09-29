<?php

namespace GFExcel\Repository;

class FormRepository implements FormRepositoryInterface
{
    /**
     * Gravity Forms Api.
     * @since $ver$
     * @var \GFAPI
     */
    protected $api;

    /**
     * FormRepository constructor.
     * @param \GFAPI $api
     */
    public function __construct(\GFAPI $api)
    {
        $this->api = $api;
    }

    /**
     * {@inheritdoc}
     * @since $ver$
     */
    public function getEntries(int $form_id, array $search_criteria = [], array $sorting = []): iterable
    {
        $page_size = 100;
        $i = 0;

        // prevent a multi-k database query to build up the array.
        $loop = true;
        while ($loop) {
            $paging = [
                'offset' => ($i * $page_size),
                'page_size' => $page_size,
            ];

            $new_entries = $this->api->get_entries($form_id, $search_criteria, $sorting, $paging);
            $count = count($new_entries);
            if ($count > 0) {
                foreach ($new_entries as $entry) {
                    yield $entry;
                }
            }

            $i += 1; // increase for the loop

            if ($count < $page_size) {
                $loop = false; // stop looping
            }
        }
    }
}
