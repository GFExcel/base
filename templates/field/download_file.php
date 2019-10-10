<?php
/**
 * Template for filtering and downloading.
 * @var \GFExcel\AddOn\AbstractGFExcelAddon $this
 */
?>
<div class="download-block">
    <div class="date-field">
        <input autocomplete="off" placeholder="YYYY-MM-DD" type="text" id="start_date" name="start_date"/>
        <label for="start_date"><?= esc_html__('Start', 'gravityforms'); ?></label>
    </div>

    <div class="date-field">
        <input autocomplete="off" placeholder="YYYY-MM-DD" type="text" id="end_date" name="end_date"/>
        <label for="end_date"><?= esc_html__('End', 'gravityforms'); ?></label>
    </div>

    <div class="download-button">
        <?= $this->single_setting([
            'type' => 'button',
            'class' => 'button-primary',
            'label' => $this->translate('Download', true),
            'icon' => '<i class="fa fa-download"></i>',
        ]); ?>
    </div>
</div>
