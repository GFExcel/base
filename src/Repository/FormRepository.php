<?php

namespace GFExcel\Repository;

/**
 * Repository to retrieve all information for a form.
 * @since $ver$
 */
class FormRepository implements FormRepositoryInterface
{
    /**
     * Gravity Forms Api.
     * @since $ver$
     * @var \GFAPI
     */
    private $api;

    /**
     * FormRepository constructor.
     * @param \GFAPI $api A Gravity Forms API instance.
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

    /**
     * {@inheritdoc}
     * @since $ver$
     * @todo: implement.
     */
    public function getDownloadUrl(array $settings): ?string
    {
        if (!$hash = $settings['hash'] ?? null) {
            return null;
        }

        return sprintf('%s/%s', 'some_url', $hash);
    }
}
