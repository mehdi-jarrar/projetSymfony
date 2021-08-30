<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;
use App\Entity\News;
use App\Entity\Category;


class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index()
    {
    
    $repository = $this->getDoctrine()->getRepository(News::class);
    $news = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
    
    return $this->render('index.html.twig', array('news'=>$news));

    }
    /**
     * @Route("/news", name="news")
     */
    public function news()
    {
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        if ($authChecker->isGranted('ROLE_ADMIN') || $authChecker->isGranted('ROLE_USER') ) {
            $repository = $this->getDoctrine()->getRepository(Category::class);
            $cat = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
             
            
            return $this->render('add.html.twig' ,array('cat'=>$cat));
        } else{
            
            return $this->render('login.html.twig');
        }
        
    }
     /**
     * @Route("/mynews", name="mynews")
     */
    public function mynews()
    {
     
        
  
        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');
        
        if ($authChecker->isGranted('ROLE_ADMIN')  ) {
            $repository = $this->getDoctrine()->getRepository(News::class);
            $news = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            return $this->render('mynews.html.twig', array('news'=>$news));

        }elseif( $authChecker->isGranted('ROLE_USER')){
            $user=$this->getUser();  
           
            
            $news = $user->getNews() ; 
            
            
            return $this->render('mynews.html.twig', array('news'=>$news));

        }else{
            var_dump("error") ; 
            die(); 
        }
        
    }




    
    /**
     * @Route("/delnews", name="delnews")
     */
    public function delnews(Request $request)
    {
        $idn=$request->get('id') ; 
        $repository = $this->getDoctrine()->getRepository(News::class);

        $new = $repository->find($idn);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($new);
        $entityManager->flush();

        $authChecker = $this->container->get('security.authorization_checker');
        $router = $this->container->get('router');

        if ($authChecker->isGranted('ROLE_ADMIN')  ) {
            $repository = $this->getDoctrine()->getRepository(News::class);
            $news = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            return $this->render('mynews.html.twig', array('news'=>$news));

        }elseif( $authChecker->isGranted('ROLE_USER')){
            $user=$this->getUser();  
            $news = $user->getNews() ; 
            return $this->render('mynews.html.twig', array('news'=>$news));

        }


        
    }





    /**
     * @Route("/enreg", name="enreg")
     */
    public function enreg()
    {
        return $this->render('register.html.twig');
    }



    /**
     * @Route("/modify/{id}", name="modify")
     */
    public function Modify(int $id)
    {
        $new = $this->getDoctrine()
        ->getRepository(News::class)
        ->find($id);
        
        $repository = $this->getDoctrine()->getRepository(Category::class);
            $cat = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
             
        return $this->render('modify.html.twig' ,array('new'=>$new , 'cat'=>$cat ) );
    }

    /**
     * @Route("/single/{id}", name="single")
     */
    public function Single(int $id)
    {
        $new = $this->getDoctrine()
        ->getRepository(News::class)
        ->find($id);
        
        return $this->render('single.html.twig' ,array('new'=>$new) );
    }

    /**
     * @Route("/update", name="update")
     */
    public function update(Request $request)
    {

        

         $idn=$request->get('id') ; 
        $repository = $this->getDoctrine()->getRepository(News::class);

        $new = $repository->find($idn);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($new);
        $entityManager->flush();



        $description   = $request->get('editor1');
        $title   = $request->get('title');
        $idc   = $request->get('category');
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($idc);
        
        
        $img   = $request->get('image');
        $date = new \DateTime();

        $new = new News() ; 
        $new->AddCategory($category) ; 
        $user=$this->getUser(); 
        $new->setTitle($title) ;
        $new->setSubject($description) ;
        $new->setDate($date) ;
        $new->AddUser($user) ; 

        $tmpFilePath = $_FILES['image']['tmp_name'];

                  if ($tmpFilePath != "")
                  {
                   
                    $name = $_FILES["image"]["name"];
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $filename = md5(time().$_FILES['image']['name']).'.jpg';
                    $newFilePath = "img/" . $filename;
                    if(move_uploaded_file($tmpFilePath, $newFilePath))
                    {
                     $new->setImage($filename);
                    }
                  }
                  $em = $this->getDoctrine()->getManager();
            $em->persist($new);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(News::class);
            $news = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            
        
                return $this->render('index.html.twig', array('news'=>$news));




    }

    

     /**
     * @Route("/addnews", name="addnews")
     */
    public function Addnews(Request $request)
    {
        $description   = $request->get('editor1');
        $title   = $request->get('title');
        $idc   = $request->get('category');
        $category = $this->getDoctrine()
        ->getRepository(Category::class)
        ->find($idc);
        
        
        $img   = $request->get('image');
        $date = new \DateTime();

        $new = new News() ; 
        $new->AddCategory($category) ; 
        $user=$this->getUser(); 
        $new->setTitle($title) ;
        $new->setSubject($description) ;
        $new->setDate($date) ;
        $new->AddUser($user) ; 

        $tmpFilePath = $_FILES['image']['tmp_name'];

                  if ($tmpFilePath != "")
                  {
                   
                    $name = $_FILES["image"]["name"];
                    $ext = pathinfo($name, PATHINFO_EXTENSION);
                    $filename = md5(time().$_FILES['image']['name']).'.jpg';
                    $newFilePath = "img/" . $filename;
                    if(move_uploaded_file($tmpFilePath, $newFilePath))
                    {
                     $new->setImage($filename);
                    }
                  }
                  $em = $this->getDoctrine()->getManager();
            $em->persist($new);
            $em->flush();
            
            $repository = $this->getDoctrine()->getRepository(News::class);
            $news = $repository->createQueryBuilder('e')->addOrderBy('e.id', 'DESC')->getQuery()->execute();
            
        
                return $this->render('index.html.twig', array('news'=>$news));




    }


    
}
