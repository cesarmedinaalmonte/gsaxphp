<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Materia\Materia;
use AppBundle\Form\Materia\MateriaType;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class MateriaController
 * @package AppBundle\Controller
 *
 * @Route("/materias")
 */
class MateriaController extends Controller
{

    /**
     * @Route("/", name="lista_materias", methods={"GET"})
     * @return RestResponse
     */
    public function indexAction(){
        $materias = $this->getDoctrine()->getRepository(Materia::class)->findBy([],['nombre' => 'asc']);
        return new RestResponse($this->get('jms_serializer')->toArray($materias));
    }

    /**
     * @Route("/{id}", name="materia_by_id", methods={"GET"}, requirements={"id"="\d+"})
     * @param $id
     * @return RestResponse
     */
    public function getByIdAction($id){
        $materia = $this->getDoctrine()->getRepository(Materia::class)->find($id);
        if (!$materia)
            return new RestResponse(null, 404, "Materia no encontrado.");

        return new RestResponse($this->get('jms_serializer')->toArray($materia));
    }

    /**
     * @Route("/", name="crear_materias", methods={"POST"})
     * @param Request $request
     * @return RestResponse
     */
    public function createAction(Request $request){
        $data = json_decode($request->getContent(), true);

        $materia = new Materia();

        $form = $this->createForm(MateriaType::class, $materia);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        try{
            $em = $this->getDoctrine()->getManager();
            $em->persist($materia);
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($materia), 201);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error creando la materia.");
        }
    }

    /**
     * @Route("/{id}", name="actualizar_materia", methods={"PUT"}, requirements={"id"="\d+"})
     * @param Request $request
     * @return RestResponse
     */
    public function updateAction(Request $request, $id){

        $data = json_decode($request->getContent(), true);

        $em = $this->getDoctrine()->getManager();

        $materia = $em->getRepository(Materia::class)->find($id);
        if (!$materia)
            return new RestResponse(null,404,"Materia no encontrada.");

        $form = $this->createForm(MateriaType::class, $materia);
        $form->submit($data);
        if (!$form->isValid())
            return new RestResponse(null,400,"Error en formulario", $form);

        try{
            $em->flush();
            return new RestResponse($this->get('jms_serializer')->toArray($materia), 200);
        }catch (\Exception $exception){
            return new RestResponse( null, 500,"Ocurrio un error actualizando la materia.");
        }
    }

}