<?php
/**
 * User: YoannD
 * Date: 19/12/2018
 * Time: 14:19
 */

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Entity\PropertySearch;
use App\Form\ContactType;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class PropertyController extends AbstractController {

        /**
         * @var PropertyRepository
         */
        private $repository;

        /**
         * @var ObjectManager
         */
        private $em;

        public function __construct(PropertyRepository $repository, ObjectManager $em)
        {
            $this->repository = $repository;
            $this->em = $em;
        }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response {

        //ajout objet en base
        /*$property = new Property();
        $property->setTitle("test")
            ->setPrice(20000)
            ->setRooms(4)
            ->setBedrooms(2)
            ->setDescription("petite maison test")
            ->setSurface(60)
            ->setFloor(1)
            ->setHeat(1)
            ->setCity('rennes')
            ->setAddress("adresse test")
            ->setHeat(0)
            ->setPostalCode("35000")
            ->setSold(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($property);
        $em->flush();*/

        //recuperer en bdd
        /*$property = $this->repository->findAllVisible();
        dump($property);*/

        //maj en bdd
        /*$property = $this->repository->findAllVisible();
        $property[0]->setSold(true);
        $this->em->flush();*/

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1),
            12
        );

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties',
            'properties' => $properties,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug" : "[a-z0-9\-]*"})
     *
     * @return Response
     */
    public function show(Property $property, string $slug, Request $request, ContactNotification $notification): Response{

        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        //on génère le formulaire
        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm(ContactType::class, $contact);

        //traitement
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notification->notify(($contact));
            $this->addFlash('success', 'Votre message a bien été envoyé.');
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties',
            //envoie du formulaire à la vue
            'form' => $form->createView()
        ]);
    }

}