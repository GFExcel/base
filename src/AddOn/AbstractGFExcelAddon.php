<?php

namespace GFExcel\AddOn;

use GFExcel\Language\Translate;

abstract class AbstractGFExcelAddon extends \GFAddon
{
    use Translate;

    /**
     * Holds the instance of the add-on.
     * @since $ver$
     * @var AbstractGFExcelAddon|null
     */
    protected static $instance = null;

    /**
     * Returns the single instance of this add-on.
     * @since $ver$
     */
    public static function get_instance(): AbstractGFExcelAddon
    {
        if (self::$instance === null) {
            throw new \RuntimeException('The container for Gravity Forms Entries in Excel is not set properly.');
        }

        return self::$instance;
    }

    /**
     * Setter for the current instance.
     *
     * Used to retrieve the instance from the container.
     *
     * @since $ver$
     * @param AbstractGFExcelAddon $instance
     */
    public static function set_instance(AbstractGFExcelAddon $instance): void
    {
        self::$instance = $instance;
    }

    /**
     * {@inheritdoc}
     *
     * Add's support for different actions.
     * @since $ver$
     */
    public function maybe_save_form_settings($form)
    {
        if ($this->is_save_postback()) {
            parent::maybe_save_form_settings($form);
        } elseif ($this->is_postback() && !rgempty('gfexcel-action')) {
            if (!$this->current_user_can_any($this->_capabilities_form_settings)) {
                \GFCommon::add_error_message(esc_html__("You don't have sufficient permissions to update the form settings.",
                    'gravityforms'));
                return false;
            }

            $method = $this->getActionMethodName();
            if ($method && method_exists($this, $method) && is_callable([$this, $method])) {
                $this->$method($form);
            } else {
                \GFCommon::add_error_message($this->translate(
                    sprintf('This action is not implemented.', $method)
                ));
            }
        }
    }

    /**
     * Generates a possible method name for a given action. eg. `enable-stuff` becomes `actionEnableStuff`.
     * @since $ver$
     * @return string|null The method name.
     */
    protected function getActionMethodName(): ?string
    {
        if (!rgempty('gfexcel-action')) {
            $value = rgpost('gfexcel-action');

            if (!empty($value) && $actions = preg_split('/[^a-z0-9]+/is', $value)) {
                return 'action' . implode(array_map('ucfirst', $actions));
            }
        }

        return null;
    }

    /**
     * Adds a simple information settings field.
     * @since $ver$
     * @param array $field The field object.
     * @param bool $echo Whether to directly echo the information.
     * @return string The html for the info.
     */
    public function settings_info(array $field, bool $echo = true): string
    {
        $field['html'] = sprintf('<p>%s</p>', $field['info'] ?? '');

        return $this->settings_html($field, $echo);
    }

    /**
     * Adds a html settings field.
     * @since $ver$
     * @param array $field The field object.
     * @param bool $echo Whether to directly echo the html.
     * @return string The html.
     */
    public function settings_html(array $field, bool $echo = true): string
    {
        $html = $field['html'] ?? '';

        if ($echo) {
            echo $html;
        }

        return $html;
    }

    /**
     * Render html row properly.
     * @since $ver$
     * @param array $field The field object.
     */
    public function single_setting_row_html(array $field): void
    {
        $this->single_setting_row_save($field);
    }

    /**
     * Render button row properly.
     * @since $ver$
     * @param array $field The field object.
     */
    public function single_setting_row_button(array $field): void
    {
        $this->single_setting_row_save($field);
    }

    /**
     * Render info row properly.
     * @since $ver$
     * @param array $field The field object.
     */
    public function single_setting_row_info(array $field): void
    {
        $this->single_setting_row_html($field);
    }

    /**
     * {@inheritdoc}
     * @since $ver$
     * @todo maybe make this a nice GFExcel icon?
     */
    public function plugin_settings_icon()
    {
        return '<i class="fa fa-table"></i>';
    }

    /**
     * {@inheritdoc}
     * @since $ver$
     * @todo maybe make this a nice GFExcel icon?
     */
    public function form_settings_icon()
    {
        return '<i class="fa fa-table"></i>';
    }


    /**
     * Add's submit button with an action.
     *
     * Makes this a sumbit `button` instead of an `input`.
     * @since $ver$
     */
    public function settings_button(array $field, bool $echo = true): string
    {
        $field['type'] = 'submit';
        $field['class'] = 'button-primary gfbutton';

        if (!rgar($field, 'label')) {
            $field['label'] = esc_html__('Update Settings', 'gravityforms');
        }

        if (!rgar($field, 'name')) {
            $field['name'] = 'gfexcel-action';
        }

        $attributes = $this->get_field_attributes($field);

        $html = sprintf(
            '<button type="%s" name="%s" value="%s" %s>%s</button>',
            esc_attr($field['type']),
            esc_attr($field['name']),
            esc_attr($field['value']),
            implode(' ', $attributes),
            esc_attr($field['label']),
        );

        if ($echo) {
            echo $html;
        }

        return $html;
    }

    /**
     * {@inheritdoc}
     *
     * Makes this a sumbit `button` instead of an `input`.
     * @since $ver$
     */
    public function settings_save($field, $echo = true): string
    {
        $field['name'] = 'gform-settings-save';
        return $this->settings_button($field, $echo);
    }

    /**
     * {@inheritdoc}
     * @since $ver$
     */
    public function render_uninstall(): void
    {
        // disable uninstall text.
    }
}
