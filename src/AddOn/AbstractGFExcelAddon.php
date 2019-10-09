<?php

namespace GFExcel\AddOn;

use GFExcel\Action\ActionAware;
use GFExcel\Action\ActionInterface;
use GFExcel\Action\ActionAwareInteface;
use GFExcel\Contract\TemplateAwareInterface;
use GFExcel\Language\Translate;
use GFExcel\Template\TemplateAware;

abstract class AbstractGFExcelAddon extends \GFAddon implements ActionAwareInteface, TemplateAwareInterface
{
    use Translate, ActionAware, TemplateAware;

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

            if ($action = $this->getActionClass()) {
                // run action
                $action->fire($this, $form);
            } else {
                \GFCommon::add_error_message($this->translate('This action is not implemented.'));
            }
        }
    }

    /**
     * Helper function that add's (and translates) a message.
     * @since $ver$
     * @param string $message The message.
     */
    public function add_message(string $message): void
    {
        \GFCommon::add_message($this->translate($message));
    }

    /**
     * Helper function that add's (and translates) an error message.
     * @since $ver$
     * @param string $message The errorr message.
     */
    public function add_error_message(string $message): void
    {
        \GFCommon::add_error_message($this->translate($message));
    }

    /**
     * Generates a possible method name for a given action. eg. `enable-stuff` becomes `actionEnableStuff`.
     * @since $ver$
     * @return ActionInterface|null The action.
     */
    protected function getActionClass(): ?ActionInterface
    {
        if (!rgempty('gfexcel-action')) {
            $action = rgpost('gfexcel-action');
            if ($this->hasAction($action)) {
                return $this->getAction($action);
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

        if (!rgar($field, 'class')) {
            $field['class'] = 'button-primary gfbutton';
        }

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
            esc_attr($field['label'])
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
     * Makes sure the returned form object is fresh.
     * @since $ver$
     */
    public function get_form_settings($form)
    {
        return parent::get_form_settings($this->getFreshForm($form));
    }

    /**
     * {@inheritdoc}
     * Set the title of the AddOn on the form settings page.
     * @since $ver$
     */
    public function form_settings_page_title()
    {
        return $this->plugin_page_title();
    }

    /**
     * {@inheritdoc}
     * Fix returning of posted values of fields without a name.
     * @since $ver$
     */
    public function get_posted_settings(): array
    {
        $settings = parent::get_posted_settings();
        unset($settings['']); // remove empty name field.
        return $settings;
    }

    /**
     * {@inheritdoc}
     * Tries to locate a field template before falling back to the original function.
     * This makes it way easier to implement a custom field.
     *
     * @since $ver$
     */
    public function single_setting($field)
    {
        $template = 'field/' . $field['type'];
        if ($this->hasTemplate($template)) {
            $field['attributes'] = $this->get_field_attributes($field);
            $this->renderTemplate($template, $field);
        } else {
            parent::single_setting($field);
        }
    }

    /**
     * {@inheritdoc}
     * @since $ver$
     */
    public function render_uninstall(): void
    {
        // disable uninstall text.
    }

    /**
     * {@inheritdoc}
     * @since $ver$
     */
    public function add_default_save_button($sections)
    {
        // prevents adding unwanted default save button.
        return $sections;
    }

    /**
     * Refreshes a form object if needed.
     * @since $ver$
     * @param array $form The form object.
     * @return array A fresh Form object.
     */
    public function getFreshForm(array $form): array
    {
        // Settings might have changed during a postback, so cannot trust $form.
        if ($this->is_postback()) {
            $form = $this->get_current_form();
            $form_id = $form['id'];
            $form = gf_apply_filters(array('gform_admin_pre_render', $form_id), $form);
        }

        return $form;
    }

    /**
     * Get the assets path
     * @since $ver$
     * @return string The assets path.
     */
    public static function assets(): string
    {
        return plugin_dir_url(GFEXCEL_PLUGIN_FILE);
    }
}
