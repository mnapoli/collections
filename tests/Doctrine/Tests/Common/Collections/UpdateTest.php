<?php
namespace Doctrine\Tests\Common\Collections;

use Doctrine\Common\Collections\Update;

class UpdateTest extends \PHPUnit_Framework_TestCase
{
    public function testOperations()
    {
        $update = new Update();

        $this->assertEmpty($update->getOperations());

        $operation = $this->getMockForAbstractClass('Doctrine\Common\Collections\Operation\Operation');
        $update->set('foo', $operation);

        $this->assertSame(array('foo' => $operation), $update->getOperations());
    }
}
