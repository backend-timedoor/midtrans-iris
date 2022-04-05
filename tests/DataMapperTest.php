<?php

namespace Timedoor\TmdMidtransIris;

use PHPUnit\Framework\TestCase;
use Timedoor\TmdMidtransIris\Utils\DataMapper;

class DummyData extends DataMapper
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $date;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of date
     */ 
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set the value of date
     *
     * @return  self
     */ 
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    protected function getMapper(): array
    {
        return [
            'setId' => 'id',
            'setName' => 'name',
            'setDate' => 'date'
        ];
    }
}

class DataMapperTest extends TestCase
{
    public function testMappingObject()
    {
        $dummy = DummyData::fromArray([
            'id'    => 123,
            'name'  => 'John Doe',
            'date'  => '2022-04-05'
        ]);

        $this->assertEquals(123, $dummy->getId());
        $this->assertEquals('John Doe', $dummy->getName());
        $this->assertEquals('2022-04-05', $dummy->getDate());

        $dummy2 = DummyData::fromArray([
            'ids'   => 123,
            'name'  => 'Jane Doe'
        ]);

        $this->assertNull($dummy2->getId());
        $this->assertEquals('Jane Doe', $dummy2->getName());
        $this->assertNull($dummy2->getDate());
    }
}