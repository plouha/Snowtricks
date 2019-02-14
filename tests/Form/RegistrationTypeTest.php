<?php 
// tests/Form/Type/TestedTypeTest.php
namespace App\Tests\Form;

use App\Form\RegistrationType;
use App\Entity\User;
use App\Model\TestObject;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Form\Form;

class RegistrationTypeTest extends TypeTestCase
{
    /**
     * @inheritdoc
     */
    protected function getExtensions()
    {
        $validator = $this->createMock(ValidatorInterface::class);

        $validator
            ->method('validate')
            ->will($this->returnValue(new ConstraintViolationList()));

        $validator
            ->method('getMetadataFor')
            ->will($this->returnValue(new ClassMetadata(Form::class)));

        return [
            new ValidatorExtension($validator)
        ];
    }

    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@email.com',
            'pseudo' => 'test',
            'plainPassword' => ['first' => 'password', 'second' => 'password']
        ];

        $objectToCompare = new User();
        // $objectToCompare will retrieve data from the form submission; pass it as the second argument
        $form = $this->factory->create(RegistrationType::class, $objectToCompare);

        $object = new User();
        // ...populate $object properties with the data stored in $formData
        $object ->setEmail('test@email.com');
        $object ->setPseudo('test');
        $object ->setPlainPassword('password');
        // submit the data to the form directly
        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());

        // check that $objectToCompare was modified as expected when the form was submitted
        $this->assertEquals($object, $objectToCompare);

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }
}