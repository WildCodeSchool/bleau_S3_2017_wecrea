<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use WeCreaBundle\Entity\Artist;
use WeCreaBundle\Entity\Nature;
use WeCreaBundle\Entity\Command;
use WeCreaBundle\Entity\Images;
use WeCreaBundle\Entity\SentMessage;
use WeCreaBundle\Entity\Status;
use WeCreaBundle\Entity\Subscriber;
use WeCreaBundle\Entity\Work;
use WeCreaBundle\Form\ArtistType;
use WeCreaBundle\Form\ImagesType;
use WeCreaBundle\Form\SentMessageType;
use WeCreaBundle\Form\WorkType;

class AdminController extends Controller
{
    /* Render the page for the creation of a new artist & some or all of his/her works */
    public function newArtistWorkAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $artist = new Artist();
        $image = new Images();
        $work = new Work();

        $formArtist = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $formImageArtist = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $formWorkImage = $this->createForm('WeCreaBundle\Form\ImagesWorkType', $image);
        $formLastWorkImages = $this->createForm('WeCreaBundle\Form\LastImagesWorkType', $image);
        $formWork = $this->createForm('WeCreaBundle\Form\WorkType', $work);

        if($request->isXmlHttpRequest()) {
            $newArtist = $request->request->get('wecreabundle_artist');
            $newWork = $request->request->get('wecreabundle_work');
            $newArtistImage = $request->request->get('wecreabundle_images');
            $newWorkImage = $request->request->get('wecreabundle_images_work');
            $lastWorkImage = $request->request->get('wecreabundle_images_last_work_images');

            $lastOrNew = isset($newWorkImage) || isset($lastWorkImage) ?
                isset($newWorkImage) ? $lastOrNew = 'new' : $lastOrNew = 'last' :
                    $lastOrNew = NULL;

            if(isset($newWork)) {
                $formWork->handleRequest($request);

                $natureId = $request->request->get('wecreabundle_work')['nature'];
                $nature = $em->getRepository('WeCreaBundle:Nature')->findOneById($natureId);

                $work->setNature($nature);

                $idArt = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
                $work->setArtist($artist);

                $em->persist($work);
                $em->flush();

                /* We send back the data regarding the profile */
                $work = $em->getRepository('WeCreaBundle:Work')->findAll();

                /* Let's get the last work created */
                $work = $work[count($work) - 1];

                $encoders = new JsonEncoder();
                $normalizer = new ObjectNormalizer();
                $normalizer->setIgnoredAttributes(array("artist", "nature"));
                $serializer = new Serializer(array($normalizer), array($encoders));

                $jsonWork = $serializer->serialize($work, "json");

                $response = new Response($jsonWork);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            if($newArtist){
                $formArtist->handleRequest($request);

                $em->persist($artist);
                $em->flush();

                /* We send back the data regarding the profile */
                $artist = $em->getRepository('WeCreaBundle:Artist')->findAll();
                /* Let's return the latest profile created */
                $artist = $artist[count($artist)-1];

                $encoders = array(new JsonEncoder());
                $normalizer = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizer, $encoders);

                $jsonArtist = $serializer->serialize($artist, "json");

                $response = new Response($jsonArtist);
                $response->headers->set('Content-Type','application/json');

                return $response;
            }

            if(isset($newArtistImage)) {
                $formImageArtist->handleRequest($request);
                $file = $request->files->get('wecreabundle_images')['url'];

                $fileName = uniqId() . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);
                $image->setUrl($fileName);

                /* As the artist profile has been created
                *  before the submission of the new image,
                *  we can make the association
                */
                $idArt = $request->request->get('idArt');
                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($idArt);
                $artist->addImage($image);

                $em->persist($image);
                $em->flush();

                /* We send back the data regarding the freshly
                *  created image to enable deletion
                */
                $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($fileName);

                $encoders = array(new JsonEncoder());
                $normalizer = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizer, $encoders);

                $jsonImage = $serializer->serialize($image, "json");

                $response = new Response($jsonImage);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }

            if(isset($lastOrNew)) {

                if($lastOrNew == "new"){
                    $formWorkImage->handleRequest($request);
                    $file = $request->files->get('wecreabundle_images_work')['url'];
                }
                else if($lastOrNew == "last"){
                    $formLastWorkImages->handleRequest($request);
                    $file = $request->files->get('wecreabundle_images_last_work_images')['url'];
                }

                $fileName = uniqId() . '.' . $file->guessExtension();
                $file->move($this->getParameter('image_directory'), $fileName);

                $image->setUrl($fileName);

                if(!empty($request->request->get('idWork'))){
                    $idWork = $request->request->get('idWork');
                    $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
                    $work->addImage($image);
                }

                $em->persist($image);
                $em->flush();

                /* We send back the data regarding the freshly
                *  created image to enable modifications
                */
                $image = $em->getRepository('WeCreaBundle:Images')->findOneByUrl($fileName);

                $encoders = array(new JsonEncoder());
                $normalizer = array(new ObjectNormalizer());
                $serializer = new Serializer($normalizer, $encoders);

                $jsonImage = $serializer->serialize($image, "json");

                $response = new Response($jsonImage);
                $response->headers->set('Content-Type', 'application/json');

                return $response;
            }
        }

        return $this->render('@WeCrea/Admin/artist_work_creation.html.twig', array(
            'artist' => $artist,
            'form' => $formArtist->createView(),
            'formImage' => $formImageArtist->createView(),
            'formWorkImage' => $formWorkImage->createView(),
            'formWork' => $formWork->createView(),
        ));
    }



    /*
     * Method for deleting a specific image linked to a specific work
     */

    public function deleteWorkImageAjaxAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        /* Stores the work id */
        $idWork = $request->request->get('idWork');
        /* Looks for the work corresponding to the id */
        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
        /* Stores the image id */
        $idImg = $request->request->get('idImg');
        /* Looks for the image corresponding to the id */
        $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg);
        /* Gets the url of the image */
        $url = $image->getUrl();
        /* Let's remove the images the work is linked with */
        $work->removeImage($image);
        /* Check if the image exists within the images folder */
        $path = $this->getParameter('image_directory')."/".$url;
        /* If yes, unlink the image */
        if(file_exists($path)){
            unlink($path);
        }
        /* Remove this image */
        $em->remove($image);
        /* Update the database */
        $em->flush();
        /* Confirm the image has been deleted successfully */
        return new Response("L'image a bien été supprimée");
    }

    /*
     * Method for deleting the work & all its images
     */

    public function deleteWorkAjaxAction(Request $request){
        dump($request);
        $em = $this->getDoctrine()->getManager();
        $idWork = $request->request->get('idWork');

        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($idWork);
        $images = $work->getImages();

        if(isset($images) && !empty($images)){
            for($i=0; $i<count($images); ++$i) {
                $idImg = $images[$i]->getId();
                $image = $em->getRepository('WeCreaBundle:Images')->findOneById($idImg);
                $url = $image->getUrl();

                $path = $this->getParameter('image_directory')."/".$url;

                if(file_exists($path)){
                    unlink($path);
                }
                $em->remove($image);
            }
        }

        $em->remove($work);
        $em->flush();

        return new Response("L'oeuvre ".$work->getTitle()." et ses images ont bien été supprimées");
    }

    /*
     * Access to the page for editing a specific artist profile & his/her works
     */

    public function editArtistWorkAction($id, Request $request)
    {
        $image = new Images();
        $work = new Work();

        $em = $this->getDoctrine()->getManager();
        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);
        $works = $em->getRepository('WeCreaBundle:Work')->findByArtist(array(
            'artist' => $artist
        ));

        if(!empty($work)) {
            foreach ($works as $key) {
                $editWorkForms[] =
                    $this
                        ->get('form.factory')
                        ->createNamedBuilder(
                            'works_'.$key->getId(),
                            WorkType::class, $key
                        )
                        ->getForm()
                        ->createView()
                ;
            }
        }

        $editArtistForm = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
        $artistImageForm = $this->createForm('WeCreaBundle\Form\ImagesType', $image);
        $workImageForm = $this->createForm('WeCreaBundle\Form\ImagesWorkType', $image);
        $workForm = $this->createForm('WeCreaBundle\Form\WorkType', $work);
        $lastWorksImageForm = $this->createForm('WeCreaBundle\Form\LastImagesWorkType', $image);

        if($request->isXmlHttpRequest()){

            $idWork = $request->request->get('wecreabundle_work')['id'];
            $idArtist = $request->request->get('wecreabundle_artist')['id'];
            $natureId = $request->request->get('wecreabundle_work')['nature'];

            /* If the user updates a work */
            if(isset($idWork) && isset($natureId)){

                $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);
                $workForm = $this->createForm('WeCreaBundle\Form\WorkType', $work);
                $workForm->handleRequest($request);
                $nature = $em->getRepository('WeCreaBundle:Nature')->findOneById($natureId);
                $work->setNature($nature);

                $em->flush();

                return new Response("L'oeuvre ".$work->getTitle()." a bien été mise à jour");
            }

            /* If the user updates the artist profile */
            if(isset($idArtist)){

                $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);
                $artistForm = $this->createForm('WeCreaBundle\Form\ArtistType', $artist);
                $artistForm->handleRequest($request);

                $em->flush();

                return new Response("Le profil de " .$artist->getFirstname(). " " .$artist->getName(). " a bien été mis à jour");
            }
        }

        /* If there's at least on completed form
        * If not, that means that the administrator has previously
        * removed all the works in the edit form
        */
        if(!empty($editWorkForms)) {
            return $this->render('@WeCrea/Admin/artist_work_edition.html.twig', array(
                /* The artist */
                'artist' => $artist,
                /* The works */
                'works' => $works,
                /* Completed artist form */
                'editArtistForm' => $editArtistForm->createView(),
                /* New image form for the artist profile */
                'formImage' => $artistImageForm->createView(),
                /* Completed work forms */
                'editWorkForms' => $editWorkForms,
                /* new work form */
                'formWork' => $workForm->createView(),
                /* new image form for a new work */
                'workImageForm' => $workImageForm->createView(),
                /* New image form for existed works */
                'lastWorksImageForm' => $lastWorksImageForm->createView()
            ));
        }
        else {
            return $this->render('@WeCrea/Admin/artist_work_edition.html.twig', array(
                /* The artist */
                'artist' => $artist,
                /* The works */
                'works' => $works,
                /* Completed artist form */
                'editArtistForm' => $editArtistForm->createView(),
                /* New image form for the artist profile */
                'formImage' => $artistImageForm->createView(),
                /* Completed work forms */
                'formWork' => $workForm->createView(),
                /* new image form for a new work */
                'workImageForm' => $workImageForm->createView(),
                /* New image form for existed works */
                'lastWorksImageForm' => $lastWorksImageForm->createView()
            ));
        }
    }

    public function adminHomeAction() {
        return $this->render('@WeCrea/Admin/home_admin.html.twig');
    }

    public function subscribeAction(){
        $em = $this->getDoctrine()->getManager();
        $subscribers = $em->getRepository('WeCreaBundle:Subscriber')->findBy([], array(
           'date' => 'DESC'
        ));

        return $this->render('WeCreaBundle:Admin:subscribers.html.twig', array(
           'subscribers' => $subscribers
        ));
    }

    public function unsubscribeAction($token){
        $em = $this->getDoctrine()->getManager();
        $subscriber = $em->getRepository('WeCreaBundle:Subscriber')->findOneByToken($token);
        $em->remove($subscriber);
        $em->flush();

        return $this->redirectToRoute("we_crea_admin_subscribers_info");
    }

    public function contactAction(){

        $contacts = $this->getDoctrine()->getManager()
                         ->getRepository('WeCreaBundle:Contact')
                         ->findBy([], ['date' => 'DESC']);

        return $this->render('WeCreaBundle:Admin:contact.html.twig', [
            'contacts' => $contacts
        ]);
    }

    public function commandStatusAction()
    {
        $em = $this->getDoctrine()->getManager();

        $commands = $em->getRepository(Command::class)
                       ->findBy([], ['date' => 'DESC']);

        return $this->render('WeCreaBundle:Admin:command_status.html.twig', [
            'commands' => $commands
        ]);
    }

    public function deleteCommandAction(Request $request)
    {
    	if ($request->isXmlHttpRequest())
	    {
		    $idCommand = $request->request->get('id');

		    $em = $this->getDoctrine()->getManager();
		    $command = $em->getRepository(Command::class)->findOneById($idCommand);
		    $em->remove($command);
		    $em->flush();

		    return new JsonResponse(array('msg' => 'ok'));
	    }
    }

    public function sendEmailToCustomerAction($email, Request $request)
    {
        $sentMessage = new SentMessage();

        $form = $this->createForm(SentMessageType::class, $sentMessage);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($sentMessage);
            $em->flush();

            if ($sentMessage->getId() != NULL) {
                $message = new \Swift_Message();
                $message->setSubject($sentMessage->getSubject());
                $message->setFrom($this->getParameter('mailer_user'));
                $message->setTo($sentMessage->getEmail());
                $message->setBody(
                    $this->renderView("WeCreaBundle:Admin:sent_message.html.twig", array(
                        'message' => $sentMessage
                    )),
                    'text/html');
                $this->get('mailer')->send($message);

                $request->getSession()->getFlashbag()->add("notice", "Message bien envoyé à " . $sentMessage->getEmail());
            } else {
                $request->getSession()->getFlashbag()->add("notice", "Erreur lors de la création du message. Veuillez réessayer.");
            }

            return $this->redirectToRoute('we_crea_admin_command_status');
        }

        return $this->render('WeCreaBundle:Admin:new_message.html.twig', array(
            'form' => $form->createView(),
            'email' => $email
        ));
    }

    public function sentEmailsAction()
    {
        $em = $this->getDoctrine()->getManager();
        $sentMessages = $em->getRepository("WeCreaBundle:SentMessage")
                           ->findBy([], ['date' => "DESC"]);

        return $this->render('WeCreaBundle:Admin:all_messages_sent.html.twig', array(
           'messages' => $sentMessages
        ));
    }

    public function legalAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $legals = $em->getRepository('WeCreaBundle:Legal')->findAll();
        $legal = $legals[0];

        $form = $this->createForm('WeCreaBundle\Form\LegalType', $legal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            return $this->redirectToRoute('we_crea_admin_legal');
        }

        return $this->render('@WeCrea/Admin/legal.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function changeStatusAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $id = $request->request->get('id');
        $command = $em->getRepository(Command::class)->findOneById($id);

        if($request->isXmlHttpRequest())
        {
            $idStatus = $request->request->get('status');
            $status = $em->getRepository(Status::class)->findOneById($idStatus);

            $command->setStatus($status);
            $em->flush();

            return new Response("La mise à jour a bien été effectuée");
        }
    }

    public function sitemapAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        // urls
        $urls = [];
        // hostname du website
        $hostname = $request->getHost();

        /* Tous les artistes liste globale */
        $urls[] = ['loc' => $this->get('router')->generate('we_crea_artists_list'), 'changefreq' => 'weekly', 'priority' => '1.0'];
        /* Tous les artistes un par un */
        $artistes = $em->getRepository('WeCreaBundle:Artist')->findAll();
        foreach ($artistes as $artiste) {
            $urls[] = ['loc' => $this->get('router')->generate('we_crea_artist', array('id' => $artiste->getId()/*,'slug' => $artiste->getSlug()*/)), 'changefreq' => 'weekly', 'priority' => '1.0'];
        }

        /* Toutes les oeuvres toutes les nature*/
        $urls[] = ['loc' => $this->get('router')->generate('we_crea_works', array('nature' => null)), 'changefreq' => 'weekly', 'priority' => '1.0'];

        /* Toutes les oeuvres par nature */
        $natures = $em->getRepository(Nature::class)->getNatureName();
        foreach ($natures as $nature) {
            if ($nature !== 'Tous' && $nature !== null) {
                $works = $em->getRepository('WeCreaBundle:Work')->getWorkByNature($nature['name']);
                foreach ($works as $work) {
                    /* Toutes les oeuvres par nature */
                    $urls[] = ['loc' => $this->get('router')->generate('we_crea_works', array('nature' => $nature['name'])), 'changefreq' => 'weekly', 'priority' => '1.0'];
                    /* Toutes les oeuvres d'une nature spécifique */
                    $urls[] = ['loc' => $this->get('router')->generate('we_crea_work', array('id' => $work->getId())), 'changefreq' => 'weekly', 'priority' => '1.0'];
                }
            }
        }


        /* Page actu */
        //$urls[] = ['loc' => $this->get('router')->generate('we_crea_actu'), 'changefreq' => 'weekly', 'priority' => '1.0'];
        /* Page concept */
        $urls[] = ['loc' => $this->get('router')->generate('we_crea_concept'), 'changefreq' => 'weekly', 'priority' => '1.0'];
        /* Home page */
        $urls[] = ['loc' => $this->get('router')->generate('we_crea_homepage'), 'changefreq' => 'weekly', 'priority' => '1.0'];


        // Once our array is filled, we define the controller response
        $response = new Response();
        $response->headers->set('Content-Type', 'xml');

        return $this->render('@WeCrea/Admin/sitemap.xml.twig', [
            'urls' => $urls,
            'hostname' => $hostname
        ]);
    }
}
