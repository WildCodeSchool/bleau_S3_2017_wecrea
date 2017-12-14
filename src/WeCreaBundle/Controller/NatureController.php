<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Form\NatureType;

class NatureController extends Controller
{
    public function editAction(Request $request)
    {
    	$em = $this->getDoctrine()->getManager();

    	$natures = $em->getRepository('WeCreaBundle:Nature')->findAll();
    	$forms = array();

		foreach ($natures as $key => $nature)
		{
			$name = str_replace(str_split(" 'áàâäãåçéèêëíìîïñóòôöõúùûüýÿ."), '_', strtolower($nature->getName()));
			$natures[$name] = $nature;
			unset($natures[$key]);

			$formBuilder = $this->get('form.factory')->createNamedBuilder($name, NatureType::class, $nature);
			$forms[$nature->getName()] = $formBuilder->getForm();

            $nature->previousImage = $nature->getImages()->getUrl();

			$forms[$nature->getName()]->handleRequest($request);
			$forms[$nature->getName()] = $formBuilder->getForm()->createView();
		}

		if ($request->isXmlHttpRequest())
		{
			$name = $request->request->get('name');
			$file = $natures[$name]->getImages()->getUrl();

			if ($file != null)
			{
				$this->get('uploader')->upload($file, $natures[$name]);
			}
			else{
				$natures[$name]->getImages()->setUrl($natures[$name]->previousImage);
			}
			$em->flush();

			$encoders = new JsonEncoder() ;
			$normalizer = new ObjectNormalizer() ;

			$normalizer->setIgnoredAttributes(array('works'));

			$serializer = new Serializer(array($normalizer), array($encoders));

			$nature = $serializer->serialize($natures[$name], 'json');

			$response = new Response($nature);

			$response->headers->set('Content-Type', 'application/json');

			return $response;
		}

        return $this->render('@WeCrea/Admin/artist/edit_card_isotop.html.twig', array(
            'forms' => $forms,
	        'natures' => $natures
        ));
    }

}
