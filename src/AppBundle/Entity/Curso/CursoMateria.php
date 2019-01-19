<?php

namespace AppBundle\Entity\Curso;

use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * CursoMateria
 *
 * @ORM\Table(name="curso_materia")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Curso\CursoMateriaRepository")
 */
class CursoMateria
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @Serializer\MaxDepth(1)
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Curso\Curso", inversedBy="materias")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     */
    private $curso;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Docente\Docente")
     * @ORM\JoinColumn(name="docente_id", referencedColumnName="id")
     */
    private $docente;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Materia\Materia")
     * @ORM\JoinColumn(name="materia_id", referencedColumnName="id")
     */
    private $materia;


    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set curso.
     *
     * @param \AppBundle\Entity\Curso\Curso|null $curso
     *
     * @return CursoMateria
     */
    public function setCurso(\AppBundle\Entity\Curso\Curso $curso = null)
    {
        $this->curso = $curso;

        return $this;
    }

    /**
     * Get curso.
     *
     * @return \AppBundle\Entity\Curso\Curso|null
     */
    public function getCurso()
    {
        return $this->curso;
    }

    /**
     * Set docente.
     *
     * @param \AppBundle\Entity\Docente\Docente|null $docente
     *
     * @return CursoMateria
     */
    public function setDocente(\AppBundle\Entity\Docente\Docente $docente = null)
    {
        $this->docente = $docente;

        return $this;
    }

    /**
     * Get docente.
     *
     * @return \AppBundle\Entity\Docente\Docente|null
     */
    public function getDocente()
    {
        return $this->docente;
    }

    /**
     * Set materia.
     *
     * @param \AppBundle\Entity\Materia\Materia|null $materia
     *
     * @return CursoMateria
     */
    public function setMateria(\AppBundle\Entity\Materia\Materia $materia = null)
    {
        $this->materia = $materia;

        return $this;
    }

    /**
     * Get materia.
     *
     * @return \AppBundle\Entity\Materia\Materia|null
     */
    public function getMateria()
    {
        return $this->materia;
    }
}
