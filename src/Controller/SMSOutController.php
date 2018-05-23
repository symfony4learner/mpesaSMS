<?php

namespace App\Controller;

use App\Entity\SMSOut;
use App\Form\SMSOutType;
use App\Repository\SMSOutRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\SendMessage;

/**
 * @Route("/")
 */
class SMSOutController extends Controller
{
    /**
     * @Route("/", name="sms_out_index", methods="GET")
     */
    public function index(SMSOutRepository $sMSOutRepository): Response
    {
        return $this->render('sms_out/index.html.twig', ['sms_outs' => $sMSOutRepository->findAll()]);
    }

    /**
     * @Route("/new", name="sms_out_new", methods="GET|POST")
     */
    public function new(Request $request, SendMessage $sendMessage): Response
    {
        $sMSOut = new SMSOut();
        $form = $this->createForm(SMSOutType::class, $sMSOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $form_data = $form->getData();
            // var_dump($form_data);
            $phone_no = $form_data->getSendTo();
            $message = $form_data->getMessage();

            $send = $sendMessage->sendMessage($phone_no, $message);
            $this->addFlash('success', "Message sent");

            $em = $this->getDoctrine()->getManager();
            $em->persist($sMSOut);
            $em->flush();

            return $this->redirectToRoute('sms_out_index');
        }

        return $this->render('sms_out/new.html.twig', [
            'sms_out' => $sMSOut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sms_out_show", methods="GET")
     */
    public function show(SMSOut $sMSOut): Response
    {
        return $this->render('sms_out/show.html.twig', ['sms_out' => $sMSOut]);
    }

    /**
     * @Route("/{id}/edit", name="sms_out_edit", methods="GET|POST")
     */
    public function edit(Request $request, SMSOut $sMSOut): Response
    {
        $form = $this->createForm(SMSOutType::class, $sMSOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('sms_out_edit', ['id' => $sMSOut->getId()]);
        }

        return $this->render('sms_out/edit.html.twig', [
            'sms_out' => $sMSOut,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="sms_out_delete", methods="DELETE")
     */
    public function delete(Request $request, SMSOut $sMSOut): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sMSOut->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($sMSOut);
            $em->flush();
        }

        return $this->redirectToRoute('sms_out_index');
    }
}
