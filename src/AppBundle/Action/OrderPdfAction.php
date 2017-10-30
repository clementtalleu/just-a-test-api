<?php

namespace AppBundle\Action;

use AppBundle\Entity\Order;
use Doctrine\ORM\EntityManager;
use Knp\Snappy\Pdf;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class OrderPdfAction
{
    private $entityManager;
    private $pdf;
    private $templating;

    public function __construct(EntityManager $entityManager, Pdf $pdf, \Twig_Environment $templating)
    {
        $this->entityManager = $entityManager;
        $this->pdf = $pdf;
        $this->templating = $templating;
    }

    /**
     * @Route(
     *     name="order_pdf",
     *     path="/orders/{id}/pdf",
     * )
     * @Method("GET")
     */
    public function __invoke(Request $request)
    {
        $order = $this->entityManager->getRepository(Order::class)->find($request->get('id'));

        if(!$order) {
            throw new NotFoundHttpException('Order does not exists');
        }

        $fileName = 'order_pdf_'.$order->getId().'.pdf';
        $path = __DIR__.'/../../../web/uploads/'.$fileName;

        if (file_exists($path)) {
            unlink($path);
        }

        $this->pdf->generateFromHtml(
            $this->templating->render('AppBundle:PDF:order_pdf.html.twig', ['order' => $order]),
            $path
        );

        if (!file_exists($path)) {
            throw new \Exception('File does not exist');
        }

        header('Content-Type: application/octet-stream');
        header("Content-Transfer-Encoding: Binary");
        header("Content-disposition: attachment; filename=\"" . basename($path) . "\"");
        readfile($path);

        return new Response('Ok', Response::HTTP_OK);
    }
}