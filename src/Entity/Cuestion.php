<?php
/**
 * PHP version 7.2
 * src\Entity\Cuestion.php
 */

namespace TDW18\Usuarios\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Cuestion
 *
 * @package TDW18\Usuarios\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="cuestiones",
 *     indexes={
 *          @ORM\Index(
 *              name="fk_creador_idx", columns={ "creador" }
 *          )
 *      }
 * )
 */
class Cuestion implements \JsonSerializable
{
    public const CUESTION_ABIERTA = 'abierta';
    public const CUESTION_CERRADA = 'cerrada';

    /**
     * @var int $idCuestion
     *
     * @ORM\Id()
     * @ORM\GeneratedValue( strategy="AUTO" )
     * @ORM\Column(
     *     name="idCuestion",
     *     type="integer"
     * )
     */
    protected $idCuestion;

    /**
     * @var string $enunciadoDescripcion
     *
     * @ORM\Column(
     *     name="enum_descripcion",
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    protected $enunciadoDescripcion;

    /**
     * @var bool $disponible
     *
     * @ORM\Column(
     *     name="enum_disponible",
     *     type="boolean",
     *     options={ "default" = false }
     * )
     */
    protected $enunciadoDisponible;

    /**
     * @var Usuario|null $creador
     *
     * @ORM\ManyToOne(targetEntity="Usuario", inversedBy="cuestiones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="creador", referencedColumnName="id", nullable=true)
     * })
     */
    protected $creador;

    /**
     * @var string $estado
     *
     * @ORM\Column(
     *     name="estado",
     *     type="string",
     *     length=7,
     *     options={ "default" = Cuestion::CUESTION_CERRADA }
     * )
     */
    protected $estado = Cuestion::CUESTION_CERRADA;

    /**
     * @var Collection|Categoria[]
     *
     * @ORM\ManyToMany(targetEntity="Categoria", mappedBy="cuestiones")
     * @ORM\OrderBy({ "idCategoria" = "ASC" })
     */
    protected $categorias;

    /**
     * Cuestion constructor.
     *
     * @param string|null $enunciadoDescripcion
     * @param Usuario|null $creador
     * @param bool $enunciadoDisponible
     * @throws \Doctrine\Common\CommonException
     */
    public function __construct(
        ?string $enunciadoDescripcion = '',
        ?Usuario $creador = null,
        bool $enunciadoDisponible = false
    ) {
        $this->enunciadoDescripcion = $enunciadoDescripcion;
        (null !== $creador)
            ? $this->setCreador($creador)
            : null;
        $this->enunciadoDisponible = $enunciadoDisponible;
        $this->estado = self::CUESTION_CERRADA;
        $this->categorias = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getIdCuestion(): int
    {
        return $this->idCuestion;
    }

    /**
     * @return string
     */
    public function getEnunciadoDescripcion(): string
    {
        return $this->enunciadoDescripcion;
    }

    /**
     * @param string $enunciadoDescripcion
     * @return Cuestion
     */
    public function setEnunciadoDescripcion(string $enunciadoDescripcion): Cuestion
    {
        $this->enunciadoDescripcion = $enunciadoDescripcion;
        return $this;
    }

    /**
     * @return bool
     */
    public function isEnunciadoDisponible(): bool
    {
        return $this->enunciadoDisponible;
    }

    /**
     * @param bool $disponible
     * @return Cuestion
     */
    public function setEnunciadoDisponible(bool $disponible): Cuestion
    {
        $this->enunciadoDisponible = $disponible;
        return $this;
    }

    /**
     * @return Usuario|null
     */
    public function getCreador(): ?Usuario
    {
        return $this->creador;
    }

    /**
     * @param Usuario $creador
     * @return Cuestion
     * @throws \Doctrine\Common\CommonException
     */
    public function setCreador(Usuario $creador): Cuestion
    {
        if (!$creador->isMaestro()) {
            throw new \Doctrine\Common\CommonException('Creador debe ser maestro');
        }
        $this->creador = $creador;
        return $this;
    }

    /**
     * @return string
     */
    public function getEstado(): string
    {
        return $this->estado;
    }

    /**
     * @return Cuestion
     */
    public function abrirCuestion(): Cuestion
    {
        $this->estado = self::CUESTION_ABIERTA;
        return $this;
    }

    /**
     * @return Cuestion
     */
    public function cerrarCuestion(): Cuestion
    {
        $this->estado = self::CUESTION_CERRADA;
        return $this;
    }

    /**
     * @return Collection|null
     */
    public function getCategorias(): ?Collection
    {
        return $this->categorias;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString()
    {
        $cod_categorias = (null === $this->getCategorias())
            ? null
            : $this->getCategorias()->map(
                function (Categoria $categoria) {
                    return $categoria->getIdCategoria();
                }
            );
        $txt_categorias = $cod_categorias
            ? '[' . implode(', ', $cod_categorias->getValues()) . ']'
            : '[ ]';
        return '[ cuestion ' .
            '(id=' . $this->getIdCuestion() . ', ' .
            'enum_descripción="' . $this->getEnunciadoDescripcion() . '", ' .
            'enum_disponible=' . ($this->isEnunciadoDisponible() ? '1' : '0') . ', ' .
            'creador=' . ($this->getCreador() ?? '[ - ]') . ', ' .
            'estado="' . $this->getEstado() . '" ' .
            'categorias=' . $txt_categorias .
            ') ]';
    }

    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    public function jsonSerialize()
    {
        $cod_categorias = (null === $this->getCategorias())
            ? null
            : $this->getCategorias()->map(
                function (Categoria $plan) {
                    return $plan->getIdCategoria();
                }
            );
        return [
            'cuestion' => [
                'idCuestion' => $this->getIdCuestion(),
                'enum_descripcion' => $this->getEnunciadoDescripcion(),
                'enum_disponible' => $this->isEnunciadoDisponible(),
                'creador' => $this->getCreador(),
                'estado' => $this->getEstado(),
                'categorias' => (null === $cod_categorias) ? null : $cod_categorias->toArray(),
            ]
        ];
    }
}