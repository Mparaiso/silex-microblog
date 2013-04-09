<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Description of PostType
 *
 * @author mark prades
 */
class PostType extends AbstractType {

    function buildForm(FormBuilderInterface $builder, array $options) {
        $builder->add("body", "textarea",array(
            "constraints"=>array(
                new Assert\Length(array("min"=>5,"max"=>255)),
            )
        ));
    }

    public function getName() {
        return "post";
    }

}

?>
