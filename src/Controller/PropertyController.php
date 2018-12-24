<?php
/**
 * User: YoannD
 * Date: 19/12/2018
 * Time: 14:19
 */

namespace App\Controller;

use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(): Response {

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

        return $this->render('property/index.html.twig', [
            'current_menu' => 'properties'
        ]);
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug" : "[a-z0-9\-]*"})
     *
     * @return Response
     */
    public function show(Property $property, string $slug): Response{

        if($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }

        return $this->render('property/show.html.twig', [
            'property' => $property,
            'current_menu' => 'properties'
        ]);
    }

}