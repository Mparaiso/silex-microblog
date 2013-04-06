<?php

namespace Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Validator\Constraints as Assert;

class AccountType extends AbstractType {

    function buildForm(\Symfony\Component\Form\FormBuilderInterface $builder, array $options) {
        $builder->add("username", "text", array("constraints" =>
                    array(new Assert\Length(array("min" => 3, "max" => 255)),
                        new Assert\Regex(
                                array("pattern" => "/(?!admin|administrator|superadmin)/")))))
                ->add("bio", "textarea", array(
                    "required" => false,
                    "attr" => array("rows" => 5),
                    "constraints" => array(new Assert\Length(array("max" => 255)))));
    }

    public function getName() {
        return "account";
    }

}

