<?php

/**
 * Description of Form
 *
 * @author pahhan
 */
class Spv_Form_Form extends Spv_Widget_HtmlWidget
{
    /**
     * Parent form
     * @var Spv_Form_Form
     */
    private $parent;

    /**
     * Child forms
     * @var array of Spv_Form_Form
     */
    private $forms = array();

    private $is_submited = false;

    /**
     * Form name
     * @var string
     */
    protected $name;

    protected $label;

    /**
     * Form template name
     * @var string
     */
    protected $template;

    /**
     * Key to retrive Templating component from TemplatingDispatcher
     * @var string
     */
    protected $templating_key = 'spv_form';

    /**
     * Current value of form
     * @var mixed
     */
    protected $value;

    /**
     * Defines whether value maust be setted or not
     * @var type
     */
    protected $required = true;

    /**
     * Object for transforming source value (e.g. from database) to form value
     * @var Spv_Transformer_TwoWayTransformerInterface
     */
    protected $source_transformer;

    /**
     * Constructor
     * @param string $name
     * @param array $properties
     */
    public function __construct($name, array $properties = array())
    {
        $this->name = $name;
        foreach ($properties as $property => $value)
        {
            $this->$property = $value;
        }
        $this->init();
    }

    /**
     * Initialisation after construct
     * @return void
     */
    protected function init()
    {

    }

    /**
     * Returns templating engine for form templating_key which must be registered
     * in Spv_Form_TemplatingDispatcher
     *
     * @return Spv_Templating_TemplatingInterface
     */
    public function getTemplating()
    {
        return Spv_Form_TemplatingDispatcher::getInstance()->get($this->templating_key);
    }

    /**
     * Returns rendered template
     * @return string
     * @throws Spv_Form_FormException   Throws if template is not defined
     */
    public function render(array $vars = array())
    {
        if( !$this->template )
            throw new Spv_Form_FormException(sprintf('Template not defined in "%s" form', $this->name));

        if( isset($vars['attrs']) )
        {
            if( is_array($vars['attrs']) )
            {
                $this->setAttrs($vars['attrs']);
            }
            unset($vars['attrs']);
        }

        $vars['form'] = $this;

        return $this->getTemplating()->render($this->template, $vars);
    }

    public function renderLabel($text = NULL, array $attrs = array())
    {
        if(is_null($text) ) $text = $this->getLabel();
        return sprintf('<label for="%s" %s>%s</label>',
                $this->getAttrId(),
                $this->renderAttrs($attrs),
                $text
                );
    }

    /**
     * Echo rendered form
     * @return void
     */
    public function display(array $attrs = array())
    {
        $vars = array();
        if(count($attrs) ) $vars['attrs'] = $attrs;
        echo $this->render($vars);
    }

    /**
     * Sets template for form
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    /**
     * Sets templating key for form
     * @param string $templating_key
     */
    public function setTemplatingKey($templating_key)
    {
        $this->templating_key = $templating_key;
        return $this;
    }

    /**
     * REturns form name
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    public function getLabel()
    {
        return $this->label;
    }

    public function setLabel($label)
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Returns id
     * @return string
     */
    public function getId()
    {
        if( $this->parent )
            return sprintf ('%s_%s', $this->parent->getId(), $this->getName());

        return $this->getName();
    }

    /**
     * Returns name for tag's name property
     * @return string
     */
    public function getAttrName()
    {
        if( $this->parent )
            return sprintf ('%s[%s]', $this->parent->getAttrName(), $this->getName());

        return $this->getName();
    }

    /**
     * Returns id for tag's id property
     * @return string
     */
    public function getAttrId()
    {
        return sprintf ('%s_id', $this->getId());
    }

    /**
     * Returns form current value
     * @return mixed
     */
    public function getValue()
    {
        if( !$this->hasChildren() ) return $this->value;

        $value = array();
        foreach( $this->forms as $key => $form )
        {
            $value[$key] = $form->getValue();
        }
        return $value;
    }

    public function setValue($value)
    {
        $this->value = $value;
        return $this;
    }

    public function isSubmited()
    {
        return $this->is_submited;
    }


    public function isRequired()
    {
        return $this->required;
    }

    public function setIsRequired($required)
    {
        $this->required = (bool) $required;
        return $this;
    }

    /**
     * Sets parent form
     * @param Spv_Form_Form $parent
     */
    public function setParent(Spv_Form_Form $parent)
    {
        $this->parent = $parent;
    }

    /**
     * Add child form
     * @param Spv_Form_Form $form
     */
    public function addForm(Spv_Form_Form $form)
    {
        $this->forms[$form->getName()] = $form;
        $form->setParent($this);
    }

    /**
     * Check whether this form has child form
     * @param string $name
     * @return boolean
     */
    public function hasForm($name)
    {
        return array_key_exists($name, $this->forms);
    }

    /**
     * Returns child form
     * @param string $name
     * @return Spv_Form_Form
     * @throws Spv_Form_FormException   Throw if this form has not child
     */
    public function getForm($name)
    {
        if( !$this->hasForm($name) )
            throw new Spv_Form_FormException(sprintf('Form "%s" has not form "%s"', $this->name, $name));

        return $this->forms[$name];
    }

    /**
     * Returns child forms
     * @return array of Spv_Form_Form
     */
    public function getForms()
    {
        return $this->forms;
    }

    /**
     * Returns whether form has children or not
     * @return boolean
     */
    public function hasChildren()
    {
        return (bool) count($this->forms);
    }

    /**
     * Filtrates value binded to form. If form has children and value is array
     * this method keep only elements with key equals to children names.
     * @param type $value
     * @return type
     */
    protected function filterBindValue($value)
    {
        if( !$this->hasChildren() or !is_array($value)) return $value;

        $keys = array_keys($this->forms);
        $filtered_value = array();
        foreach( $keys as $key )
        {
            if( array_key_exists($key, $value) )
            {
                $filtered_value[$key] = $value[$key];
            }
        }

        return $filtered_value;
    }

    /**
     * Bind value to form
     * @param type $value
     */
    public function bind(array $value)
    {
        if( isset($value[$this->getName()]) )
        {
            $this->is_submited = true;
            $value = $this->filterBindValue($value[$this->getName()]);
            $this->setValue($value);

            foreach($this->forms as $form)
                $form->bind($value);
        }
    }

    /**
     * Bind value from source (databse for example), if source transformer
     * is setted, value transformed it.
     * @param mixed $value
     */
    public function bindSource($value)
    {
        if( $this->source_transformer and isset($value[$this->getName()]) )
        {
            $value[$this->getName()] = $this->source_transformer->forwardTransform($value[$this->getName()]);
        }

        $this->bind($value);
    }

    /**
     * Returns form value transformed by source transfomer
     * @return Spv_Transformer_TwoWayTransformerInterface
     */
    public function getSourceValue()
    {
        if( $this->hasChildren() )
        {
            $val = array();
            foreach ($this->forms as $key => $form)
            {
                $val[$key] = $form->getSourceValue();
            }
        }
        else
            $val = $this->getValue();



        if( $this->source_transformer )
            $val = $this->source_transformer->backwardTransform($val);

        return $val;
    }

    /**
     * Returns source transformer
     * @return type
     */
    public function getSourceTransformer()
    {
        return $this->source_transformer;
    }

    public function setSourceTransformer(Spv_Transformer_TwoWayTransformerInterface $source_transformer)
    {
        $this->source_transformer = $source_transformer;
        $source_transformer->setForm($this);
        return $this;
    }


}