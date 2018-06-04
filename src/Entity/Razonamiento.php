<?php
/**
 * PHP version 7.2
 * src\Entity\Razonamiento.php
 */

namespace TDW18\Usuarios\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Razonamiento
 *
 * @package TDW18\Usuarios\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name = "razonamientos"
 * )
 */

class Razonamiento implements \JsonSerializable
{
    /**
     * Id rationing
     *
     * @var integer
     *
     * @ORM\Column(
     *     name     = "idRationing",
     *     type     = "integer",
     *     nullable = false
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected $idRationing;

    /**
     * @var integer $idSolution
     *
     * @ORM\Column(
     *     name     = "idSolution",
     *     type     = "integer",
     *     nullable = false
     *     )
     *
     * @ORM\ManyToOne(targetEntity="Solucion", inversedBy="soluciones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idSolution", referencedColumnName="idAnswer", nullable=false)
     * })
     */
    private $idSolution;

    /**
     * Title
     *
     * @var string
     *
     * @ORM\Column(
     *     name     = "title",
     *     type     = "string",
     *     length   = 60,
     *     nullable = false,
     *     unique   = false
     *     )
     */
    private $title;

    /**
     * Justify Rationing
     *
     * @var boolean
     *
     * @ORM\Column(
     *     name     = "justifyRationing",
     *     type     = "boolean",
     *     options  = { "default" = false },
     *     unique   = false
     *     )
     */
    private $justifyRationing;

    /**
     * Solution constructor.
     *
     * @param string $title
     * @param bool $justifyRationing
     * @param int $idSolution
     */
    public function __construct(
        string $title = '',
        bool $justifyRationing = false,
        int $idSolution = 0
    ) {
        $this->idRationing          = 0;
        $this->idSolution             = $idSolution;
        $this->title                = $title;
        $this->justifyRationing     = $justifyRationing;
    }

    /**
     * Get idRationing
     *
     * @return int
     */
    public function getIdRationing(): int
    {
        return $this->idRationing;
    }

    /**
     * Get idSolution
     *
     * @return int
     */
    public function getIdAnswer(): int
    {
        return $this->idSolution;
    }

    /**
     * Set idSolution
     *
     * @param int $id
     * @return Razonamiento
     */
    public function setIdAnswer(int $id): Razonamiento
    {
        $this->idSolution = $id;
        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title title
     *
     * @return Razonamiento
     */
    public function setTitle(string $title): Razonamiento
    {
        $this->title= $title;
        return $this;
    }

    /**
     * Get justifyRationing
     *
     * @return boolean
     */
    public function getJustifyRationing(): bool
    {
        return $this->justifyRationing;
    }

    /**
     * Set justifyRationing
     *
     * @param boolean $justifyRationing justifyRationing
     *
     * @return Razonamiento
     */
    public function setJustifyRationing(bool $justifyRationing): Razonamiento
    {
        $this->justifyRationing = $justifyRationing;
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
        return '[ rationing ' .
            '(idSolution=' . $this->idSolution . ', ' .
            'idRationing=' . $this->idRationing . ', ' .
            'title="' . $this->getTitle() . '", ' .
            'justifyRationing="' . $this->getJustifyRationing() . '", ' .
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
        return [
            'rationing' => [
                'idSolution' => $this->idSolution,
                'idRationing' => $this->idRationing,
                'title' => $this->getTitle(),
                'justifyRationing' => $this->getJustifyRationing()
            ]
        ];
    }
}

/**
 * Razonamiento definition
 *
 * @SWG\Definition(
 *     definition = "Razonamiento",
 *     required = { "idSolution", "title", "justifyRationing" },
 *     @SWG\Property(
 *          property    = "idRationing",
 *          description = "Rationing Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *     @SWG\Property(
 *          property    = "idSolution",
 *          description = "Answer Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "title",
 *          description = "Title",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "justifyRationing",
 *          description = "Justify rationing",
 *          type        = "boolean"
 *      ),
 *      example = {
 *          "rationing" = {
 *              "idRationing"          = 1508,
 *              "idSolution"           = 1508,
 *              "title"                = "Porque si",
 *              "justifyRationing"     = true
 *          }
 *     }
 * )
 *
 * @SWG\Parameter(
 *      name        = "idRationing",
 *      in          = "path",
 *      description = "ID of rationing",
 *      required    = true,
 *      type        = "integer",
 *      format      = "int32"
 * )
 */

/**
 * Razonamiento data definition
 *
 * @SWG\Definition(
 *      definition = "RazonamientoData",
 *     @SWG\Property(
 *          property    = "idSolution",
 *          description = "Id Solution",
 *          type        = "integer"
 *      ),
 *      @SWG\Property(
 *          property    = "title",
 *          description = "Title",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "justifyRationing",
 *          description = "Justify rationing",
 *          type        = "string"
 *      ),
 *      example = {
 *          "idSolution"           = 1,
 *          "title"                = "Porque si",
 *          "justifyRationing"     = true
 *      }
 * )
 */

/**
 * Razonamiento array definition
 *
 * @SWG\Definition(
 *     definition = "RazonamientoArray",
 *      @SWG\Property(
 *          property    = "razonamientos",
 *          description = "Razonamiento array",
 *          type        = "array",
 *          items       = {
 *              "$ref": "#/definitions/Razonamiento"
 *          }
 *      )
 * )
 */
