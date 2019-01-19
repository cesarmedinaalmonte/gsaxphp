<?php

namespace AppBundle\Controller;


use AppBundle\Entity\Periodo\Periodo;
use AppBundle\Utils\Rest\RestResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class PeriodoController
 * @package AppBundle\Controller
 *
 * @Route("/periodos")
 */
class PeriodoController extends Controller
{
    /**
     * @Route("/actual", name="periodos_actual", methods={"GET"})
     */
    public function getPeriodoActivoAction(){
        $periodo = $this->getDoctrine()->getRepository(Periodo::class)->findOneBy(['activo' => true]);
        return new RestResponse([$this->get('jms_serializer')->toArray($periodo)]);
    }

}