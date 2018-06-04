<?php
/**
 * PHP version 7.2
 * src\Entity\Usuario.php
 */

namespace TDW18\Usuarios\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

/**
 * Class Solucion
 *
 * @package TDW18\Usuarios\Entity
 *
 * @ORM\Entity()
 * @ORM\Table(
 *     name="soluciones"
 * )
 */

class Solucion implements \JsonSerializable
{
    /**
     * Id answer
     *
     * @var integer
     *
     * @ORM\Column(
     *     name     = "idAnswer",
     *     type     = "integer",
     *     nullable = false
     * )
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @SuppressWarnings(PHPMD.ShortVariable)
     */
    protected $idAnswer;

    /**
     * @var Cuestion|null $idQuestion
     *
     * @ORM\Column(
     *     name     = "idQuestion",
     *     type     = "integer",
     *     nullable = false
     *     )
     *
     * @ORM\ManyToOne(targetEntity="Cuestion", inversedBy="cuestiones")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="idQuestion", referencedColumnName="idCuestion", nullable=true)
     * })
     */
    private $idQuestion;

    /**
     * Username
     *
     * @var string
     *
     * @ORM\Column(
     *     name     = "student",
     *     type     = "string",
     *     length   = 60,
     *     nullable = false,
     *     unique   = false
     *     )
     */
    private $student;

    /**
     * Question Title
     *
     * @var boolean
     *
     * @ORM\Column(
     *     name     = "questionTitle",
     *     type     = "string",
     *     length   = 60,
     *     nullable = false,
     *     unique   = false
     *     )
     */
    private $questionTitle;


    /**
     * Proposed solution
     *
     * @var string
     *
     * @ORM\Column(
     *     name     = "proposedSolution",
     *     type     = "string",
     *     length   = 60,
     *     nullable = false,
     *     unique   = false
     *     )
     */
    private $proposedSolution;

    /**
     * Solution constructor.
     *
     * @param string $student username
     * @param string $questionTitle questionTitle
     * @param string $proposedSolution proposedSolution
     */
    public function __construct(
        string $student = '',
        string $questionTitle = '',
        string $proposedSolution = ''

    ) {
        $this->idAnswer          = 0;
        $this->idQuestion        = 0;
        $this->student          = $student;
        $this->questionTitle     = $questionTitle;
        $this->proposedSolution  = $proposedSolution;
    }

    /**
     * Get idAnswer
     *
     * @return int
     */
    public function getIdAnswer(): int
    {
        return $this->idAnswer;
    }

    /**
     * Get idQuestion
     *
     * @return int
     */
    public function getIdQuestion(): int
    {
        return $this->idQuestion;
    }

    /**
     * Set idQuestion
     *
     * @param int $id
     * @return Solucion
     */
    public function setIdQuestion(int $id): Solucion
    {
        $this->idQuestion = $id;
        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getStudent(): string
    {
        return $this->student;
    }

    /**
     * Set username
     *
     * @param string $student student
     *
     * @return Solucion
     */
    public function setStudent(string $student): Solucion
    {
        $this->student = $student;
        return $this;
    }

    /**
     * Get questionTitle
     *
     * @return string
     */
    public function getQuestionTitle(): string
    {
        return $this->questionTitle;
    }

    /**
     * Set questionTitle
     *
     * @param string $questionTitle questionTitle
     *
     * @return Solucion
     */
    public function setQuestionTitle(string $questionTitle): Solucion
    {
        $this->questionTitle = $questionTitle;
        return $this;
    }

    /**
     * Get proposedSolution
     *
     * @return string
     */
    public function getProposedSolution(): string
    {
        return $this->proposedSolution;
    }

    /**
     * Set proposedSolution
     *
     * @param string $proposedSolution proposedSolution
     *
     * @return Solucion
     */
    public function setProposedSolution(string $proposedSolution): Solucion
    {
        $this->proposedSolution = $proposedSolution;
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
        return '[ answer ' .
            '(id=' . $this->getIdAnswer() . ', ' .
            'idQuestion=' . $this->getIdQuestion() . ', ' .
            'student="' . $this->getStudent() . '", ' .
            'questionTitle="' . $this->getQuestionTitle() . '", ' .
            'proposedSolution="' . $this->getProposedSolution() . '", ' .
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
            'answer' => [
                'idAnswer' => $this->idAnswer,
                'idQuestion' => $this->getIdQuestion(),
                'student' => $this->getStudent(),
                'questionTitle' => $this->getQuestionTitle(),
                'proposedSolution' => $this->getProposedSolution(),
            ]
        ];
    }
}

/**
 * Solucion definition
 *
 * @SWG\Definition(
 *     definition    = "Solucion",
 *     required      = { "idQuestion", "student", "questionTitle", "proposedSolution" },
 *     @SWG\Property(
 *          property    = "idAnswer",
 *          description = "Answer Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *     @SWG\Property(
 *          property    = "idQuestion",
 *          description = "Question Id",
 *          type        = "integer",
 *          format      = "int32"
 *      ),
 *      @SWG\Property(
 *          property    = "student",
 *          description = "User name",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "questionTitle",
 *          description = "Question title",
 *          type        = "string"
 *      ),
 *     @SWG\Property(
 *          property    = "proposedSolution",
 *          description = "Proposed solution",
 *          type        = "string"
 *      ),
 *      example = {
 *          "answer" = {
 *              "idAnswer"          = 1508,
 *              "idQuestion"        = 1508,
 *              "student"          = "User name",
 *              "questionTitle"     = "¿Que es el software?",
 *              "proposedSolution"  = "Es lo mejor del mundo"
 *          }
 *     }
 * )
 *
 * @SWG\Parameter(
 *      name        = "idAnswer",
 *      in          = "path",
 *      description = "ID of answer",
 *      required    = true,
 *      type        = "integer",
 *      format      = "int32"
 * )
 */

/**
 * Solucion data definition
 *
 * @SWG\Definition(
 *      definition = "SolucionData",
 *     @SWG\Property(
 *          property    = "idQuestion",
 *          description = "Id Question",
 *          type        = "integer"
 *      ),
 *      @SWG\Property(
 *          property    = "student",
 *          description = "User name",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "questionTitle",
 *          description = "Question title",
 *          type        = "string"
 *      ),
 *      @SWG\Property(
 *          property    = "proposedSolution",
 *          description = "Proposed solution",
 *          type        = "string",
 *      ),
 *      example = {
 *          "idQuestion" = 1,
 *          "student"  = "username",
 *          "questionTitle"     = "¿Que es el software?",
 *          "proposedSolution"  = "Es lo mejor del mundo"
 *      }
 * )
 */

/**
 * Solucion array definition
 *
 * @SWG\Definition(
 *     definition = "SolucionArray",
 *      @SWG\Property(
 *          property    = "soluciones",
 *          description = "Solucion array",
 *          type        = "array",
 *          items       = {
 *              "$ref": "#/definitions/Solucion"
 *          }
 *      )
 * )
 */
