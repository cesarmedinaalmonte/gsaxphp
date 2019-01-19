<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Estudiante\Estudiante;
use AppBundle\Form\Estudiante\EstudianteType;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class EstudianteController
 * @package AppBundle\Controller
 *
 * @Route("/estudiantes")
 */
class EstudianteController extends Controller
{
    /**
     * @Route("/", name="lista_estudiantes", methods={"GET"})
     * @return RestResponse
     */
    public function indexAction(){
        $estudiantes = $this->getDoctrine()->getRepository(Estudiante::class)->findBy([],['nombre' => 'asc']);
        return new RestResponse($this->get('jms_serializer')->toArray($estudiantes));
    }

    /**
     * @Route("/buscar", name="lista_estudiantes", methods={"POST"})
     * @return RestResponse
     */
    public function buscarAction(Request $request){
        $data = json_decode($request->getContent(), true);
        $nombre = (array_key_exists('nombre', $data)) ? $data['nombre'] : null;

        if (!$nombre)
            return new RestResponse([]);

        $estudiantes = $this->getDoctrine()->getRepository(Estudiante::class)->buscarPorNombre($nombre);
        return new RestResponse($this->get('jms_serializer')->toArray($estudiantes));
    }

    /**
     * @Route("/{id}", name="estudiante_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @return JsonResponse
     */
    public function getByIdAction($id){
        $estudiante = $this->getDoctrine()->getRepository(Estudiante::class)->find($id);
        if (!$estudiante)
            return new RestResponse(null, 404, "Estudiante no encontrado.");

        return new RestResponse($this->get('jms_serializer')->toArray($estudiante));
    }

    /**
     * @Route("/", name="crear_estudiante", methods={"POST"})
     * @param Request $request
     * @return RestResponse
     */
    public function createAction(Request $request){
        $data = json_decode($request->getContent(), true);

        $estudiante = new Estudiante();

        $form = $this->createForm(EstudianteType::class, $estudiante);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        if (array_key_exists('fechaNacimiento', $data)){
            $dataFecha = $data['fechaNacimiento'];
            try {
                $fecha = new \DateTime($dataFecha);
                $estudiante->setFechaNacimiento($fecha);
            } catch (\Exception $ex) {
                return new RestResponse(null,400,"El formato de la fecha es invaliad, Ejemplo: 01/15/2019");
            }
        }

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($estudiante);
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($estudiante), 201);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error creando el estudiante.");
        }
    }

    /**
     * @Route("/{id}", name="actualizar_estudiante", methods={"PUT"}, requirements={"id"="\d+"})
     * @param Request $request
     * @param $id
     * @return RestResponse
     */
    public function updateAction(Request $request, $id){

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        $estudiante = $em->getRepository(Estudiante::class)->find($id);
        if (!$estudiante)
            return new RestResponse(null,404,"Estudiante no encontrado.");

        $form = $this->createForm(EstudianteType::class, $estudiante);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        if (array_key_exists('fechaNacimiento', $data)){
            $dataFecha = $data['fechaNacimiento'];
            try {
                $fecha = new \DateTime($dataFecha);
                $estudiante->setFechaNacimiento($fecha);
            } catch (\Exception $ex) {
                return new RestResponse(null,400,"El formato de la fecha es invaliad, Ejemplo: 01/15/2019");
            }
        }else{
            $estudiante->setFechaNacimiento(null);
        }

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($estudiante), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando el estudiante.");
        }
    }

}