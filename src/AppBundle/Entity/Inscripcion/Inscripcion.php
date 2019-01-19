<?php

namespace AppBundle\Entity\Inscripcion;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * Inscripcion
 *
 * @ORM\Table(name="inscripcion")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Inscripcion\InscripcionRepository")
 */
class Inscripcion
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
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Periodo\Periodo")
     * @ORM\JoinColumn(name="periodo_id", referencedColumnName="id")
     */
    private $periodo;

    /**
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Curso\Curso")
     * @ORM\JoinColumn(name="curso_id", referencedColumnName="id")
     */
    private $curso;

    /**
     *
     * @ORM\ManyToMany(targetEntity="AppBundle\Entity\Estudiante\Estudiante")
     * @ORM\JoinTable(name="inscripcion_estudiantes",
     *      joinColumns={@ORM\JoinColumn(name="inscripcion_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="estudiante_id", referencedColumnName="id")}
     * )
     */
    private $estudiantes;

    public function __construct()
    {
        $this->estudiantes = new ArrayCollection();
    }


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
     * Set periodo.
     *
     * @param \AppBundle\Entity\Periodo\Periodo|null $periodo
     *
     * @return Inscripcion
     */
    public function setPeriodo(\AppBundle\Entity\Periodo\Periodo $periodo = null)
    {
        $this->periodo = $periodo;

        return $this;
    }

    /**
     * Get periodo.
     *
     * @return \AppBundle\Entity\Periodo\Periodo|null
     */
    public function getPeriodo()
    {
        return $this->periodo;
    }

    /**
     * Set curso.
     *
     * @param \AppBundle\Entity\Curso\Curso|null $curso
     *
     * @return Inscripcion
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
     * Add estudiante.
     *
     * @param \AppBundle\Entity\Estudiante\Estudiante $estudiante
     *
     * @return Inscripcion
     */
    public function addEstudiante(\AppBundle\Entity\Estudiante\Estudiante $estudiante)
    {
        if (!$this->estudiantes->contains($estudiante)) {
            $this->estudiantes[] = $estudiante;
        }

        return $this;
    }

    /**
     * Remove estudiante.
     *
     * @param \AppBundle\Entity\Estudiante\Estudiante $estudiante
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeEstudiante(\AppBundle\Entity\Estudiante\Estudiante $estudiante)
    {
        return $this->estudiantes->removeElement($estudiante);
    }

    /**
     * Get estudiantes.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getEstudiantes()
    {
        return $this->estudiantes;
    }
}
