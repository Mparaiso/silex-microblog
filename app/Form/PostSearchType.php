<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Description of SearchType
 *
 * @author mark prades
 */
class PostSearchType extends AbstractType {

    function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("expression", "search", array("label"=>" ","constraints" => array(
                new Assert\Length(array("min" => 3, "max" => 50)))));
    }

    public function getName() {
        return "postSearch";
    }

//put your code here
}
