<?php
/**
 * PHP version 7.2
 * src\Entity\Usuario.php
 */

namespace TDW18\Usuarios\Tests\Entity;

use TDW18\Usuarios\Entity\Categoria;
use PHPUnit\Framework\TestCase;

/**
 * Class CategoriaTest
 *
 * @package TDW18\Usuarios\Tests\Entity
 * @group   categories
 * @coversDefaultClass \TDW18\Usuarios\Entity\Categoria
 */
class CategoriaTest extends TestCase
{
    /**
     * @var Categoria $categoria
     */
    protected $categoria;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $enumDesc = 'UsEr TESt NaMe #';
        $this->categoria = new Categoria($enumDesc, true);
    }

    /**
     * @covers ::__construct()
     */
    public function testConstructor()
    {
        self::assertNotEmpty($this->categoria->getPropuestaDescripcion());
        self::assertNotEmpty($this->categoria->isCorrecta());
        self::assertEmpty($this->categoria->getCuestiones());
    }

    /**
     * @covers ::setPropuestaDescripcion()
     * @covers ::getPropuestaDescripcion()
     * @throws \Exception
     */
    public function testGetSetPropuestaDescripcion()
    {
        static::assertNotEmpty($this->categoria->getPropuestaDescripcion());
        $enumDesc = 'UsEr TESt NaMe #';
        $this->categoria->setPropuestaDescripcion($enumDesc);
        static::assertSame($enumDesc, $this->categoria->getPropuestaDescripcion());
    }

    /**
     * @covers ::isCorrecta()
     * @covers ::setCorrecta()
     * @throws \Exception
     */
    public function testIsSetCorrecta()
    {
        $this->categoria->setCorrecta(true);
        self::assertTrue($this->categoria->isCorrecta());

        $this->categoria->setCorrecta(false);
        self::assertFalse($this->categoria->isCorrecta());
    }

    /**
     * @covers ::__toString()
     * @throws \Exception
     */
    public function testToString()
    {
        $enumDesc = 'USer Te$t nAMe #';
        $this->categoria->setIdCategoria(0);
        $this->categoria->setPropuestaDescripcion($enumDesc);
        self::assertContains($enumDesc, $this->categoria->__toString());
    }

    /**
     * @covers ::jsonSerialize()
     */
    public function testJsonSerialize()
    {
        $this->categoria->setIdCategoria(0);
        $json = json_encode($this->categoria);
        self::assertJson($json);
    }
}
