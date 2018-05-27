<?php
/**
 * PHP version 7.2
 * src\Entity\Categoria.php
 */

namespace TDW18\Usuarios\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Categoria
 *
 * @package TDW18\Usuarios\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name                 = "categorias",
 *     )
 */
class Categoria implements \JsonSerializable
{
    /**
     * @var int $idCategoria
     *
     * @ORM\Id()
     * @ORM\GeneratedValue( strategy="AUTO" )
     * @ORM\Column(
     *     name="idCategoria",
     *     type="integer"
     * )
     */
    protected $idCategoria;

    /**
     * @var string $propuestaDescripcion
     *
     * @ORM\Column(
     *     name="prop_descripcion",
     *     type="string",
     *     length=255,
     *     nullable=true
     * )
     */
    protected $propuestaDescripcion;

    /**
     * @var bool $correcta
     *
     * @ORM\Column(
     *     name="enum_disponible",
     *     type="boolean",
     *     options={ "default" = false }
     * )
     */
    protected $correcta;

    /**
     * @var Collection|Cuestion[]
     *
     * @ORM\ManyToMany(targetEntity="Cuestion", inversedBy="categorias")
     * @ORM\JoinTable(
     *   name="cuestion_has_categoria",
     *   joinColumns={
     *     @ORM\JoinColumn(name="categoria_id", referencedColumnName="idCategoria")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="cuestion_id", referencedColumnName="idCuestion")
     *   }
     * )
     */
    protected $cuestiones;

    /**
     * Categoria constructor.
     * @param string $propuestaDescripcion
     * @param bool $correcta
     */
    public function __construct(string $propuestaDescripcion, bool $correcta)
    {
        $this->propuestaDescripcion = $propuestaDescripcion;
        $this->correcta = $correcta;
        $this->cuestiones = new ArrayCollection();
    }

    /**
     * @return int
     */
    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }

    /**
     * @return Categoria
     */
    public function setIdCategoria(int $id): Categoria
    {
        $this->idCategoria = $id;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getPropuestaDescripcion(): ?string
    {
        return $this->propuestaDescripcion;
    }

    /**
     * @param string $propuestaDescripcion
     * @return Categoria
     */
    public function setPropuestaDescripcion(string $propuestaDescripcion): Categoria
    {
        $this->propuestaDescripcion = $propuestaDescripcion;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCorrecta(): bool
    {
        return $this->correcta;
    }

    /**
     * @param bool $correcta
     * @return Categoria
     */
    public function setCorrecta(bool $correcta): Categoria
    {
        $this->correcta = $correcta;
        return $this;
    }

    /**
     * @return Collection|Cuestion[]
     */
    public function getCuestiones()
    {
        return $this->cuestiones;
    }

    /**
     * @param Cuestion $cuestion
     * @return bool
     */
    public function containsCuestion(Cuestion $cuestion): bool
    {
        return $this->cuestiones->contains($cuestion);
    }

    /**
     * Añade la cuestión a la categoría
     *
     * @param Cuestion $cuestion
     * @return Categoria
     */
    public function addCuestion(Cuestion $cuestion): Categoria
    {
        if ($this->cuestiones->contains($cuestion)) {
            return $this;
        }

        $this->cuestiones->add($cuestion);
        return $this;
    }

    /**
     * Elimina la cuestión de la categoría
     *
     * @param Cuestion $cuestion
     * @return Categoria|null La Categoría o nulo, si la categoría no contiene la cuestión
     */
    public function removeCuestion(Cuestion $cuestion): ?Categoria
    {
        if (!$this->cuestiones->contains($cuestion)) {
            return null;
        }

        $this->cuestiones->removeElement($cuestion);
        return $this;
    }

    /**
     * The __toString method allows a class to decide how it will react when it is converted to a string.
     *
     * @return string
     * @link http://php.net/manual/en/language.oop5.magic.php#language.oop5.magic.tostring
     */
    public function __toString(): string
    {
        $cod_cuestiones = (null === $this->getCuestiones())
            ? null
            : $this->getCuestiones()->map(
                function (Cuestion $cuestion) {
                    return $cuestion->getIdCuestion();
                }
            );
        $txt_cuestiones = $cod_cuestiones
            ? '[' . implode(', ', $cod_cuestiones->getValues()) . ']'
            : '[ ]';
        return '[ categoria ' .
            '(idCategoria=' . $this->getIdCategoria() . ', ' .
            'prop_descripción="' . $this->getPropuestaDescripcion() . '", ' .
            'enum_disponible="' . $this->isCorrecta() . '"' .
            ') ]';
    /**
        return '[ categoria ' .
            '(id=' . $this->getIdCategoria() . ', ' .
            'prop_descripción="' . $this->getPropuestaDescripcion() . '", ' .
            'cuestiones="' . $txt_cuestiones . '"' .
            ') ]';
     */
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
        $cod_cuestiones = (null === $this->getCuestiones())
            ? null
            : $this->getCuestiones()->map(
                function (Cuestion $cuestion) {
                    return $cuestion->getIdCuestion();
                }
            );

        return [
            'categoria' => [
                'idCategoria' => $this->getIdCategoria(),
                'prop_descripcion' => $this->getPropuestaDescripcion(),
                'enum_disponible' => $this->isCorrecta()
            ]
        ];
        /**
         * 'categorias' => (null === $cod_cuestiones) ? null : $cod_cuestiones->toArray(),
         */
    }
}

/**
 * Categoria definition
 *
 * @SWG\Definition(
 *     definition= "Categoria",
 *     required = { "idCategoria" },
 *     @SWG\Property(
 *          property    = "idCategoria",
 *          description = "Question Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "prop_descripcion",
 *          description = "Description of category",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "enum_disponible",
 *          description = "Available category",
 *          type        = "boolean"
 *      ),
 *      example = {
 *          "categoria" = {
 *              "idCategoria"       = 1508,
 *              "prop_descripcion" = "Software",
 *              "enum_disponible"    = true
 *          }
 *     }
 * )
 *
 * @SWG\Parameter(
 *      name        = "idCategoria",
 *      in          = "path",
 *      description = "ID of category",
 *      required    = true,
 *      type        = "integer",
 *      format      = "int32"
 * )
 */

/**
 * Categoria data definition
 *
 * @SWG\Definition(
 *      definition = "CategoriaData",
 *      @SWG\Property(
 *          property    = "idCategoria",
 *          description = "Category Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "prop_descripcion",
 *          description = "Description of category",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "enum_disponible",
 *          description = "Available category",
 *          type        = "boolean"
 *      ),
 *      example = {
 *          "prop_descripcion"  = "Software",
 *          "enum_disponible"     = true
 *      }
 * )
 */

/**
 * Categoria array definition
 *
 * @SWG\Definition(
 *     definition = "CategoriesArray",
 *      @SWG\Property(
 *          property    = "categorias",
 *          description = "Categories array",
 *          type        = "array",
 *          items       = {
 *              "$ref": "#/definitions/Categoria"
 *          }
 *      )
 * )
 */
