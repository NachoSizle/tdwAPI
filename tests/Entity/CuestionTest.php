<?php
/**
 * PHP version 7.2
 * src\Entity\Usuario.php
 */

namespace TDW18\Usuarios\Tests\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use TDW18\Usuarios\Entity\Cuestion;
use PHPUnit\Framework\TestCase;
use TDW18\Usuarios\Entity\Usuario;

/**
 * Class CuestionTest
 *
 * @package TDW18\Usuarios\Tests\Entity
 * @group   cuestions
 * @coversDefaultClass \TDW18\Usuarios\Entity\Cuestion
 */
class CuestionTest extends TestCase
{
    /**
     * @var Cuestion $user
     */
    protected $cuestion;

    /**
     * Sets up the fixture.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->cuestion = new Cuestion();
    }

    /**
     * @covers ::__construct()
     */
    public function testConstructor()
    {
        self::assertEmpty($this->cuestion->getEnunciadoDescripcion());
        self::assertEmpty($this->cuestion->isEnunciadoDisponible());
        self::assertSame(null, $this->cuestion->getCreador());
        self::assertSame('cerrada', $this->cuestion->getEstado());
    }

    /**
     * @covers ::setEnunciadoDescripcion()
     * @covers ::getEnunciadoDescripcion()
     * @throws \Exception
     */
    public function testGetSetEnunciadoDescripcion()
    {
        static::assertEmpty($this->cuestion->getEnunciadoDescripcion());
        $enumDesc = 'UsEr TESt NaMe #';
        $this->cuestion->setEnunciadoDescripcion($enumDesc);
        static::assertSame($enumDesc, $this->cuestion->getEnunciadoDescripcion());
    }

    /**
     * @covers ::setEnunciadoDisponible()
     * @covers ::isEnunciadoDisponible()
     * @throws \Exception
     */
    public function testGetSetEnunciadoDisponible()
    {
        $this->cuestion->setEnunciadoDisponible(true);
        self::assertTrue($this->cuestion->isEnunciadoDisponible());

        $this->cuestion->setEnunciadoDisponible(false);
        self::assertFalse($this->cuestion->isEnunciadoDisponible());
    }

    /**
     * @covers ::getCreador()
     * @covers ::setCreador()
     */
    public function testGetSetCreador()
    {
        $userCreator = new Usuario();
        $userCreator->setMaestro(true);
        static::assertNotNull($this->cuestion->setCreador($userCreator));

        $this->cuestion->setEnunciadoDescripcion($userCreator);
        static::assertSame($userCreator, $this->cuestion->getCreador());
    }

    /**
     * @covers ::getEstado()
     * @covers ::setEstado()
     */
    public function testGetSetEstado()
    {
        $this->cuestion->setEstado(true);
        self::assertSame('abierta', $this->cuestion->getEstado());

        $this->cuestion->setEstado(false);
        self::assertSame('cerrada', $this->cuestion->getEstado());
    }

    public function testGetCuestiones()
    {
        self::assertEmpty($this->cuestion->getCategorias());
    }

    /**
     * @covers ::__toString()
     * @throws \Exception
     */
    public function testToString()
    {
        $enumDesc = 'USer Te$t nAMe #';
        $this->cuestion->setIdCuestion(0);
        $this->cuestion->setEnunciadoDescripcion($enumDesc);
        self::assertContains($enumDesc, $this->cuestion->__toString());
    }

    /**
     * @covers ::jsonSerialize()
     */
    public function testJsonSerialize()
    {
        $this->cuestion->setIdCuestion(0);
        $json = json_encode($this->cuestion);
        self::assertJson($json);
    }
}
