<?php

namespace AppBundle\Entity\Curso;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as Serializer;

/**
 * Curso
 *
 * @ORM\Table(name="curso")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Curso\CursoRepository")
 */
class Curso
{
    const NIVELES = [
        ['id' => 'inicial', 'nombre' => 'Inicial'],
        ['id' => 'primaria', 'nombre' => 'Primaria'],
        ['id' => 'secundaria', 'nombre' => 'Secundaria']
    ];
    const SECCIONES = [
        ['id' => 'A', 'nombre' => 'A'],
        ['id' => 'B', 'nombre' => 'B'],
        ['id' => 'C', 'nombre' => 'C']
    ];

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string|null
     *
     * @ORM\Column(name="seccion", type="string", length=255, nullable=true)
     */
    private $seccion;

    /**
     * @var string|null
     *
     * @ORM\Column(name="nivel", type="string", length=255, nullable=true)
     */
    private $nivel;


    /**
     *
     * @ORM\OneToMany(targetEntity="AppBundle\Entity\Curso\CursoMateria", mappedBy="curso", cascade={"persist"})
     * @var ArrayCollection
     */
    private $materias;

    public function __construct()
    {
        $this->materias = new ArrayCollection();
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
     * Set nombre.
     *
     * @param string $nombre
     *
     * @return Curso
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre.
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set seccion.
     *
     * @param string|null $seccion
     *
     * @return Curso
     */
    public function setSeccion($seccion = null)
    {
        $this->seccion = $seccion;

        return $this;
    }

    /**
     * Get seccion.
     *
     * @return string|null
     */
    public function getSeccion()
    {
        return $this->seccion;
    }

    /**
     * Set nivel.
     *
     * @param string|null $nivel
     *
     * @return Curso
     */
    public function setNivel($nivel = null)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel.
     *
     * @return string|null
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Add materia.
     *
     * @param \AppBundle\Entity\Curso\CursoMateria $materia
     *
     * @return Curso
     */
    public function addMateria(\AppBundle\Entity\Curso\CursoMateria $materia)
    {
        if (!$this->materias->contains($materia)) {
            $materia->setCurso($this);
            $this->materias[] = $materia;
        }

        return $this;
    }

    /**
     * Remove materia.
     *
     * @param \AppBundle\Entity\Curso\CursoMateria $materia
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeMateria(\AppBundle\Entity\Curso\CursoMateria $materia)
    {
        return $this->materias->removeElement($materia);
    }

    /**
     * Get materias.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getMaterias()
    {
        return $this->materias;
    }
}
