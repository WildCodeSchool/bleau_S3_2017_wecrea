<?php

namespace WeCreaBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use WeCreaBundle\Entity\Command;
use WeCreaBundle\Entity\Concept;
use WeCreaBundle\Entity\Subscriber;
use WeCreaBundle\Entity\Contact;
use WeCreaBundle\Entity\Nature;
use WeCreaBundle\Entity\WorkPurchased;
use WeCreaBundle\Form\ContactType;
use WeCreaBundle\Form\ProfilFormType;
use WeCreaBundle\WeCreaBundle;

class UserController extends Controller
{
    public function indexAction()
    {
        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();
        $carrousels = $em->getRepository('WeCreaBundle:Carrousel')->findAll();
        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('WeCreaBundle:User:index.html.twig', array(
            'carrousels' => $carrousels,
            'bCount' => $bCount,
            'fCount' =>$fCount,
        ));
    }

    public function conceptAction() {
        $session = $this->get('session');

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $em = $this->getDoctrine()->getManager();
        $conceptPage = $em->getRepository(Concept::class)->getConceptPage();

        return $this->render('@WeCrea/User/concept.html.twig', array(
            'bCount' => $bCount,
            'fCount' => $fCount,
            'concept' => $conceptPage

        ));
    }
    public function worksShowAction() {
        $session = $this->get('session');

        $em = $this->getDoctrine()->getManager();
        $works = $em->getRepository('WeCreaBundle:Work')->findBy(array(), array('id'=>'desc'));
		$natures = $em->getRepository(Nature::class)->findAll();

        $container = $this->container;

        $favs = $session->get('favs');

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('WeCreaBundle:User:works.html.twig', array(
            'bCount' => $bCount,
            'fCount' =>$fCount,
            'works' => $works,
            'favs' => $favs,
	        'natures' => $natures
        ));
    }

    public function workShowAction($id) {
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $work = $em->getRepository('WeCreaBundle:Work')->findOneById($id);
        $images = $work->getImages();
        $caracts = $work->getCaracts();

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        return $this->render('WeCreaBundle:User:work.html.twig', array(
            'bCount' => $bCount,
            'work' => $work,
            'images' => $images,
            'fCount' => $fCount,
            'caracts' => $caracts
        ));
    }

    /* Method for displaying all the artists registered */
    public function artistsShowAction(){
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $artists = $em->getRepository('WeCreaBundle:Artist')->findBy(array(),
            array(
                'name' => 'ASC'
        ));

        return $this->render('WeCreaBundle:User:artists.html.twig', array(
            'bCount' => $bCount,
            'artists' => $artists,
            'fCount' => $fCount
        ));
    }

    /* Method for displaying a specific artist */
    public function artistShowAction($id){
        $em = $this->getDoctrine()->getManager();

        $session = $this->get('session');

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $artist = $em->getRepository('WeCreaBundle:Artist')->findOneById($id);

        return $this->render('WeCreaBundle:User:artist.html.twig', array(
            'artist' => $artist,
            'bCount' => $bCount,
            'fCount' => $fCount
        ));
    }

    /* Method for add a product in basket */
    public function addBasketAction(Request $request) {
        $session = $this->get('session');
        $req = $request->request;
        $idWork = $req->get('work_id');
        $carWork = $req->get('caract');
        $quant = $req->get('quantity');
        $pBasket = $session->get('basket');

        $pBasket [$idWork][$carWork] = $quant;

        $session->set('basket', $pBasket);

        return $this->redirectToRoute('we_crea_works');
    }

    /* Method for delete an article from basket */
    public function deleteBasketAction(Request $request) {
        $session = $this->get('session');
        $basket = $session->get('basket');
        $idWork = $request->query->get('id');
        $workName = $this->getDoctrine()->getManager()->getRepository('WeCreaBundle:Work')->findOneById($idWork)->getTitle();
        unset($basket[$idWork]);
        $session->set('basket', $basket);

        $content = array('name' => $workName);

        $response = new Response(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    public function showBasketAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        $bWorks =[];

        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $Works = $em->getRepository('WeCreaBundle:Work');
        $Caracts = $em->getRepository('WeCreaBundle:Caract');

        $pBasket = $session->get('basket');

        // UPDATE QUANTITY AJAX
        if($request->isXmlHttpRequest()) {
            $idWork = $request->get('work');
            $caract = $request->get('caract');
            $quant = $request->get('quant');
            $pBasket[$idWork][$caract]=$quant;
            $session->set('basket', $pBasket);

            return new Response('ok');
        }

        if(isset($pBasket) && !empty($pBasket)) {
            foreach ($pBasket as $idWork => $bCaracts)
            {
                $work = $Works->findOneById($idWork);

                foreach ($bCaracts as $bCaract => $quant)
                {
                    $caract = $Caracts->findOneById($bCaract);

                    $works [$idWork] = array(
                        'caract' => $caract,
                        'quant' => $quant
                    );
                }

                $works [$idWork] ['work'] = $work;
            }
        }
        else {
            $works=null;
        }

        return $this->render('@WeCrea/User/basket/summary.html.twig', array(
            'works' => $works,
            'bCount' => $bCount,
            'fCount' => $fCount
        ));
    }

    public function basketAddressAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        $formUser = $this->createForm(ProfilFormType::class, $user);

        if($request->isXmlHttpRequest()) {
            $address1 = $request->get('app_user_profil')['address1'];
            $zipCode1 = $request->get('app_user_profil')['zipCode1'];
            $town1 = $request->get('app_user_profil')['town1'];

            $address2 = $request->get('app_user_profil')['address2'];
            $zipCode2 = $request->get('app_user_profil')['zipCode2'];
            $town2 = $request->get('app_user_profil')['town2'];

            $user->setAddress1($address1);
            $user->setZipCode1($zipCode1);
            $user->setTown1($town1);

            $user->setAddress2($address2);
            $user->setZipCode2($zipCode2);
            $user->setTown2($town2);

            $em->flush();
            $content = $this->renderView('WeCreaBundle:User/basket:addressUser.html.twig', array(
                'user' => $user
            ));
            $response = new JsonResponse($content);

            return $response;
        }

        return $this->render('@WeCrea/User/basket/addressConfirm.html.twig', array(
            'user' => $user,
            'formUser' => $formUser->createView()
        ));
    }

    /* ----- Create Command ----- */
    public function commandAction() {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');

        $basket = $session->get('basket');

        $command = new Command();

        $status = $em->getRepository("WeCreaBundle:Status")->findOneById(1);
        $Works = $em->getRepository('WeCreaBundle:Work');
        $Caracts = $em->getRepository('WeCreaBundle:Caract');
        $user = $this->getUser();

        $command->setName($user->getName());
        $command->setAddressfact($user->getAddress1());
        $command->setAddressdel($user->getAddress2());
        $command->setZipCodefact($user->getZipCode1());
        $command->setZipCodedel($user->getZipCode2());
        $command->setTownfact($user->getTown1());
        $command->setTowndel($user->getTown2());
        $command->setMail($user->getEmail());

        $date = new \DateTime();
        $id_trans = intval(str_pad(rand(0,899999),6, "0", STR_PAD_LEFT));

        $command->setDate($date);
        $command->setNb($id_trans);
        $delivery = 0;

        foreach ($basket as $prod=>$caract) {
            $workPurchased = new WorkPurchased();
            $work = $Works->findOneById($prod);
            $time = $work->getTimelimit();

            if($time > $delivery) {
                $delivery = $time;
            }

            foreach ($caract as $key => $quant) {
                $wCaract = $Caracts->findOneById($key);
                $quant = $quant;
            }

            $workPurchased->setTitle($work->getTitle());
            $workPurchased->setCaract($wCaract->getDimension());
            $workPurchased->setQuant($quant);
            $workPurchased->setPrice($wCaract->getPrice());
            $workPurchased->setArtist($work->getArtist()->getName());

            $em->persist($workPurchased);

            $command->addWork($workPurchased);
        }

        $command->setStatus($status);
        $command->setDelivery($delivery);

        $em->persist($command);
        $user->addCommand($command);
        $em->flush();

        $works = $command->getWorks();
        $total = 0;

        foreach ($works as $work) {
            $price = $work->getPrice() * $work->getQuant();
            $total += $price;
        }

        $signature = utf8_encode('INTERACTIVE+'.$total.'00+TEST+978+PAYMENT+SINGLE+3+3+POST+'. $this->getParameter('merchant_site_id') .'+'.$date->format('YmdHis').'+'.$id_trans.'+http://wecrea.wcs-fontainebleau.fr/basket+http://wecrea.wcs-fontainebleau.fr/pay+http://wecrea.wcs-fontainebleau.fr/pay+http://wecrea.wcs-fontainebleau.fr/pay+V2+'.$this->getParameter('certif_test'));

        $signature = sha1($signature);

        $Tva = $em->getRepository('WeCreaBundle:Legal')->findAll();

        $tva = number_format($total * $Tva[0]->getTva() / 100, 2);
        $ttc = number_format($total + $tva, 2);


        return $this->render('@WeCrea/User/basket/payement.html.twig', array(
            'commands' => $command,
            'total' => $total,
            'signature' => $signature,
            'idTrans' => $id_trans,
            'tva' => $tva,
            'ttc' => $ttc,
            'Tva' => $Tva[0]->getTva()
        ));
    }

    // --- Command payement --- //
    public function payementAction($id) {
        $em = $this->getDoctrine()->getManager();

        $comand = $em ->getRepository('WeCreaBundle:Command')->findOneById($id);
        $date = new \DateTime();
        $id_trans = intval(str_pad(rand(0,899999),6, "0", STR_PAD_LEFT));
        $comand->setNb($id_trans);
        $comand->setDate($date);

        $works = $comand->getWorks();
        $total=0;
        foreach ($works as $work) {
            $price = $work->getPrice() * $work->getQuant();
            $total += $price;
        }

        $signature = utf8_encode('INTERACTIVE+'.$total.'00+TEST+978+PAYMENT+SINGLE+3+3+POST+'. $this->getParameter('merchant_site_id') .'+'.$date->format('YmdHis').'+'.$id_trans.'+http://wecrea.wcs-fontainebleau.fr/basket+http://wecrea.wcs-fontainebleau.fr/pay+http://wecrea.wcs-fontainebleau.fr/pay+http://wecrea.wcs-fontainebleau.fr/pay+V2+'.$this->getParameter('certif_test'));
        $signature = sha1($signature);

        $em->flush();
        $Tva = $em->getRepository('WeCreaBundle:Legal')->findAll();

        $tva = number_format($total * $Tva[0]->getTva() / 100, 2);
        $ttc = number_format($total + $tva, 2);

        return $this->render('@WeCrea/User/basket/payement.html.twig', array(
            'commands' => $comand,
            'total' => $total,
            'signature' => $signature,
            'idTrans' => $id_trans,
            'tva' => $tva,
            'ttc' => $ttc,
            'Tva' => $Tva[0]->getTva()
        ));
    }

    // --- API response --- //
    public function apiResponseAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $session = $this->get('session');
        $Status = $em->getRepository('WeCreaBundle:Status');
        $r = $request->request;
        $alls = $r->all();

        ksort($alls);
        $sign = '';
        foreach($alls as $key => $value) {
            if ($key != 'signature') {
                $sign .= $value . '+';
            }
        }
        $sign .= $this->getParameter('certif_test');
        $sign = utf8_encode($sign);
        $signature = sha1($sign);

        $commandId = $r->get('vads_trans_id');
        $prevSign = $r->get('signature');


        $command = $em->getRepository('WeCreaBundle:Command')->findOneByNb($commandId);

        if($command != null) {
            if ($signature == $prevSign){
                $response = $r->get('vads_trans_status');
            }
            else{
                $response = "Erreur lors du payment";
            }
            
            if ($response == 'AUTHORISED'){
                $status = $Status->findOneById(4);
                $session->remove('basket');

                /* Total price calculation */
                $price = NULL;
                $works = $command->getWorks();

                foreach($works as $work)
                {
                    $price += $work->getPrice() * $work->getQuant();
                }

                $Tva = $em->getRepository('WeCreaBundle:Legal')->findAll();

                $tva = number_format($price * $Tva[0]->getTva() / 100, 2);
                $ttc = number_format($price + $tva, 2);

                $legal = $Tva[0]->getMention();

                /* Let's send a command confirmation to the customer */
                $message = new \Swift_Message();
                $message->setSubject('Votre commande WeCrea - n°' . $command->getNb() . '');
                $message->setFrom('cotedesserts.info@gmail.com');
                $message->setTo($command->getMail());
                $message->setBody(
                    $this->renderView('@WeCrea/User/basket/command_confirmation.html.twig',
                        array(
                            'command' => $command,
                            'total_price' => $price,
                            'Tva' => $Tva[0],
                            'ttc' => $ttc,
                            'legal' => $legal
                    ))
                    , 'text/html'
                );
                $this->get('mailer')->send($message);
            }
            elseif ($response == 'REFUSED'){
                $status = $Status->findOneById(3);
            }
            elseif ($response == 'WAITING_AUTHORISATION' || $response == 'AUTHORISED_TO_VALIDATE') {
                $status = $Status->findOneById(2);
            }
        }

        $command->setStatus($status);
        $em->flush();

        $total = $r->get('vads_amount');

        return $this->render('@WeCrea/User/basket/return.html.twig', array(
            'status' => $response,
            'comand' => $command,
            'total' => $total
        ));
    }

    /* ----- Add Favs -----*/
    public function addFavAction(Request $request) {
        $session = $this->get('session');
        $favs = $session->get('favs');


        $idWork = $request->request->get('idWork');
        $workName =
            $this->getDoctrine()->getManager()
            ->getRepository('WeCreaBundle:Work')->findOneById($idWork)
            ->getTitle()
        ;

        if (!isset($favs[$idWork])){
            $favs[$idWork] = $workName;
        }

        $session->set('favs', $favs);

        $fCount = $this->container->get('favs')->countFavs($session);

        $content = array(
            'fCount' => $fCount,
            'name' => $workName
        );

        $response = new Response(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /* ----- delete favs -----*/
    public function deleteFavAction(Request $request) {
        $session = $this->get('session');

        $idFav = $request->request->get('idWork');
        $favs = $session->get('favs');
        unset($favs[$idFav]);
        $session->set('favs', $favs);

        $workName =
            $this->getDoctrine()->getManager()
                ->getRepository('WeCreaBundle:Work')->findOneById($idFav)
                ->getTitle()
        ;

        $fCount = $this->container->get('favs')->countFavs($session);

        $content = array(
            'fCount' => $fCount,
            'name' => $workName
        );

        $response = new Response(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /* ----- show favs ----- */
    public function showFavsAction() {
        $session = $this->get('session');
        $works = $this->getDoctrine()->getManager()->getRepository('WeCreaBundle:Work');
        $container = $this->container;

        $bCount = $container->get('app.basket')->countBasket($session);
        $fCount = $container->get('favs')->countFavs($session);

        $favObjs = [];
        $favs = $session->get('favs');

        if (isset($favs)) {
            foreach ($favs as $key => $fav) {
                $favObj = $works->findOneById($key);
                $favObjs [] = $favObj;
            }
        }

        return $this->render('@WeCrea/User/favs.html.twig', array(
            'favs' => $favObjs,
            'bCount' => $bCount,
            'fCount' => $fCount
        ));
    }

    /* ----- show user profil ----- */
    public function showProfilAction(Request $request) {
        $user = $this->getUser();
        $comands = $user->getCommands();
        $formUser = $this->createForm(ProfilFormType::class, $user);

        $formUser->handleRequest($request);
        if($formUser->isSubmitted() && $formUser->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $user = $this->getUser();
            $content = $this->renderView('@WeCrea/User/profil/profilResume.html.twig', array(
                'user' => $user
            ));

            $response = new JsonResponse($content);

            return $response;
        }

        return $this->render('@WeCrea/User/profil/profil.html.twig', array(
            'user' => $user,
            'comands' => $comands,
            'formUser' => $formUser->createView(),
        ));
    }

    public function searchAction(Request $request){
        $em = $this->getDoctrine()->getManager();

        $exp = htmlspecialchars($request->request->get('search'));

        if($exp != NULL) {
            $resultsWorks = $em->getRepository('WeCreaBundle:Work')->myFindByRegExpWorks($exp);
            $resultsArtists = $em->getRepository('WeCreaBundle:Work')->myFindByRegExpArtists($exp);

            /* If suggestion clicked & received as new request,
               count($expExploded) equals 3
               name, firstname, (Artist)
            */
            $expExploded = explode(' ', $exp);

            /* If the array contains at least 2 keys */
            count($expExploded) > 1 ?
                [$expArtist = $expExploded,
                 $artists = $em->getRepository('WeCreaBundle:Artist')->myFindProfilByNameAndFirstName($expArtist)] :
                [$expArtist = $exp,
                 $artists = $em->getRepository('WeCreaBundle:Artist')->myFindProfilByNameOrFirstname($expArtist)];

            if (!empty($resultsWorks || !empty($resultsArtists)) || !empty($artists)) {

                foreach ($resultsWorks as $result)
                {
                    $matchingWorks[$result['id']] = $result['id'];
                }

                foreach ($resultsArtists as $result)
                {
                    $matchingWorks[$result['id']] = $result['id'];
                }

                foreach($artists as $artist)
                {
                    $matchingArtists[] = $artist['id'];
                }

                if ($request->isXmlHttpRequest()) {
                    $works = $em->getRepository('WeCreaBundle:Work')->myFindWorksByIds($matchingWorks);
                    $response = array($works, $artists);
                    return new JsonResponse($response);
                }
                else{
                    if(isset($artists) && !empty($artists) && isset($resultsWorks) && !empty($resultsWorks) || isset($resultsArtists) && !empty($resultsArtists)){
                        $artists = $em->getRepository('WeCreaBundle:Artist')->myFindArtistsByIds($matchingArtists);
                        $works = $em->getRepository('WeCreaBundle:Work')->myFindWorksAllFieldsByIds($matchingWorks);
                        return $this->render('WeCreaBundle:User:search.html.twig', array(
                            'works' => $works,
                            'artists' => $artists,
                            'exp' => $exp
                        ));
                    }
                    else if(isset($resultsWorks) && !empty($resultsWorks)){
                        $works = $em->getRepository('WeCreaBundle:Work')->myFindWorksAllFieldsByIds($matchingWorks);
                        return $this->render('WeCreaBundle:User:search.html.twig', array(
                            'works' => $works,
                            'exp' => $exp
                        ));
                    }
                    else{
                        $artists = $em->getRepository('WeCreaBundle:Artist')->myFindArtistsByIds($matchingArtists);
                        return $this->render('WeCreaBundle:User:search.html.twig', array(
                            'artists' => $artists,
                            'exp' => $exp
                        ));
                    }
                }
            } else {
                if ($request->isXmlHttpRequest()) {
                    return new JsonResponse('Aucune suggestion');
                } else {
                    return $this->render('WeCreaBundle:User:search.html.twig', array(
                        'response' => "Aucun article ne correspond à votre recherche"
                    ));
                }
            }
        }
        else{
            return $this->render('WeCreaBundle:User:search.html.twig', array(
                'response' => "Aucun article ne correspond à votre recherche"
            ));
        }
    }

    public function actuAction(){
        $em = $this->getDoctrine()->getManager();
        $actu = $em->getRepository('WeCreaBundle:Actu')->findBy([], array(
            'date' => 'DESC'
    ));

        return $this->render('WeCreaBundle:User:actu.html.twig', array(
            'actus' => $actu
        ));
    }

    public function NewsletterAction(Request $request){
        if($request->isMethod('post')){

            $em = $this->getDoctrine()->getManager();
            $message = $request->getSession()->getFlashBag();

            $email = htmlspecialchars($request->request->get('email'));
            $hasAlreadySubscribed = $em->getRepository('WeCreaBundle:Subscriber')->findOneByEmail($email);

            if($hasAlreadySubscribed == NULL){
                $subscriber = new Subscriber();
                $subscriber->setEmail($email);
                $subscriber->setDate(new \DateTime());
                $subscriber->setToken(md5(uniqid()));

                $em->persist($subscriber);
                $em->flush();

                $subscriber->getId() != NULL ?
                    $message->add("Notice", "Vous avez bien été inscrit(e) à la newsletter"):
                    $message->add("Notice", "L'inscription à la newsletter a échoué. Veuillez réessayer.");
            }
            else{
                $message->add("Notice", "Vous êtes déjà inscrit à la newsletter");
            }

            return $this->redirect($request->headers->get('referer'));
        }
    }

    public function unsubscribeAction($token){
        $em = $this->getDoctrine()->getManager();

        $subscriberToDelete = $em->getRepository('WeCreaBundle:Subscriber')->findOneByToken($token);

        $subscriberToDelete != NULL ?
            array(
                $em->remove($subscriberToDelete),
                $em->flush(),
                $message = "L'adresse " . $subscriberToDelete->getEmail() . " a bien été supprimée. Vous pouvez vous réabonner à tout moment."
            ):
            $message = "Vous vous êtes déjà désabonné(e) de la newsletter.";

        return $this->render("WeCreaBundle:User:unsubscription_confirmation.html.twig", [
            'message' => $message
        ]);
    }

    public function contactAction(Request $request){
        $em = $this->getDoctrine()->getManager();
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $contact->setDate(new \DateTime());
            $em->persist($contact);
            $em->flush();

            $phone = $contact->getPhonenumber() != NULL ? $contact->getPhonenumber() : "Non renseigné";

            $message = new \Swift_Message();
            $message
                    ->setSubject('Nouveau message de '. $contact->getFirstName() . ' ' . $contact->getName())
                    ->setFrom('contact@wecrea.fr')
                    ->setTo('contact@wecrea.fr')
                    ->setBody(
                        '<p><b> Coordonnées du contact : </b><br /><br />
                            <b>Email : </b> '. $contact->getEmail() . '<br />
                            <b>Téléphone : </b> ' . $phone .
                            '</p>
                            <p><b> Message : </b><br /><br />' . nl2br($contact->getContent()) . '</p>'
                        ,'text/html'
                    );
            $this->get('mailer')->send($message);

            $request->getSession()->getFlashBag()->add("Notice", "Votre message a bien été envoyé.");
            return $this->redirectToRoute('we_crea_contact');
        }

        return $this->render('@WeCrea/User/contact.html.twig', [
           'form' => $form->createView()
        ]);
    }

    public function commandPdfAction(Request $request) {
        $em = $this->getDoctrine()->getManager();
        $id = $request->query->get('id');
        $command = $em->getRepository('WeCreaBundle:Command')->findOneById($id);

        $pdfName = $command->getNb(). uniqid() . '.pdf';
        $path = $this->getParameter('pdf'). '/' . $pdfName;

        $price = NULL;
        $works = $command->getWorks();

        foreach($works as $work)
        {
            $price += $work->getPrice() * $work->getQuant();
        }

        $Tva = $em->getRepository('WeCreaBundle:Legal')->findAll();

        $tva = number_format($price * $Tva[0]->getTva() / 100, 2);
        $ttc = number_format($price + $tva, 2);

        $legal = $Tva[0]->getMention();

        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView('@WeCrea/User/basket/pdfCommand.html.twig', array(
                'command' => $command,
                'Tva' => $Tva[0]->getTva(),
                'tva' => $tva,
                'ttc' => $ttc,
                'legal' => $legal
            )),
            $path
        );

        return new Response($pdfName);
    }
}
