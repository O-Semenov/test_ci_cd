<?php

/**
 * Joomla! Content Management System
 *
 * @copyright  (C) 2009 Open Source Matters, Inc. <https://www.joomla.org>
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\CMS\Form\Field;

use Joomla\CMS\Form\FormField;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Form Field class for the Joomla Platform.
 * Supports a multi line area for entry of plain text
 *
 * @link   https://html.spec.whatwg.org/multipage/form-elements.html#the-textarea-element
 * @since  1.7.0
 */
class TextareaField extends FormField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.7.0
     */
    protected $type = 'Textarea';

    /**
     * The number of rows in textarea.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $rows;

    /**
     * The number of columns in textarea.
     *
     * @var    mixed
     * @since  3.2
     */
    protected $columns;

    /**
     * The maximum number of characters in textarea.
     *
     * @var    mixed
     * @since  3.4
     */
    protected $maxlength;

    /**
     * Does this field support a character counter?
     *
     * @var    boolean
     * @since  4.0.0
     */
    protected $charcounter = false;

    /**
     * Name of the layout being used to render the field
     *
     * @var    string
     * @since  3.7
     */
    protected $layout = 'joomla.form.field.textarea';

    /**
     * Method to get certain otherwise inaccessible properties from the form field object.
     *
     * @param   string  $name  The property name for which to get the value.
     *
     * @return  mixed  The property value or null.
     *
     * @since   3.2
     */
    public function __get($name)
    {
        switch ($name) {
            case 'rows':
            case 'columns':
            case 'maxlength':
            case 'charcounter':
                return $this->$name;
        }

        return parent::__get($name);
    }

    /**
     * Method to set certain otherwise inaccessible properties of the form field object.
     *
     * @param   string  $name   The property name for which to set the value.
     * @param   mixed   $value  The value of the property.
     *
     * @return  void
     *
     * @since   3.2
     */
    public function __set($name, $value)
    {
        switch ($name) {
            case 'rows':
            case 'columns':
            case 'maxlength':
                $this->$name = (int) $value;
                break;

            case 'charcounter':
                $this->charcounter = strtolower($value) === 'true';
                break;

            default:
                parent::__set($name, $value);
        }
    }

    /**
     * Method to attach a Form object to the field.
     *
     * @param   \SimpleXMLElement  $element  The SimpleXMLElement object representing the `<field>` tag for the form field object.
     * @param   mixed              $value    The form field value to validate.
     * @param   string             $group    The field name group control value. This acts as an array container for the field.
     *                                      For example if the field has name="foo" and the group value is set to "bar" then the
     *                                      full field name would end up being "bar[foo]".
     *
     * @return  boolean  True on success.
     *
     * @see     FormField::setup()
     * @since   3.2
     */
    public function setup(\SimpleXMLElement $element, $value, $group = null)
    {
        $return = parent::setup($element, $value, $group);

        if ($return) {
            $this->rows        = isset($this->element['rows']) ? (int) $this->element['rows'] : false;
            $this->columns     = isset($this->element['cols']) ? (int) $this->element['cols'] : false;
            $this->maxlength   = isset($this->element['maxlength']) ? (int) $this->element['maxlength'] : false;
            $this->charcounter = isset($this->element['charcounter']) && strtolower($this->element['charcounter']) === 'true';
        }

        return $return;
    }

    /**
     * Method to get the textarea field input markup.
     * Use the rows and columns attributes to specify the dimensions of the area.
     *
     * @return  string  The field input markup.
     *
     * @since   1.7.0
     */
    protected function getInput()
    {
        // Trim the trailing line in the layout file
        return rtrim($this->getRenderer($this->layout)->render($this->collectLayoutData()), PHP_EOL);
    }

    /**
     * Method to get the data to be passed to the layout for rendering.
     *
     * @return  array
     *
     * @since 3.7
     */
    protected function getLayoutData()
    {
        $data = parent::getLayoutData();

        // Initialize some field attributes.
        $columns      = $this->columns ? ' cols="' . $this->columns . '"' : '';
        $rows         = $this->rows ? ' rows="' . $this->rows . '"' : '';
        $maxlength    = $this->maxlength ? ' maxlength="' . $this->maxlength . '"' : '';

        $extraData = [
            'maxlength'   => $maxlength,
            'rows'        => $rows,
            'columns'     => $columns,
            'charcounter' => $this->charcounter,
        ];

        return array_merge($data, $extraData);
    }
}
