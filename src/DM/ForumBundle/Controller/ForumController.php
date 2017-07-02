<?php
namespace DM\ForumBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use DM\ForumBundle\Entity\Post;

/**
 * Description of ForumController
 *
 * @author sam
 */

class ForumController extends Controller
  {
    public function indexAction()
    {
      return $this->render('DMForumBundle:Forum:index.html.twig',
          array(
              'test' => 'essai'
            ));
    }
    // ========================================
    public function FormAction(Request $request)
    {
      $message = new Post();

      $form = $this->createFormBuilder($message)
          ->add('author',   TextType::class)
          ->add('message',  TextType::class)
          ->add('save',     SubmitType::class)
          ->getForm()
          ;
      $form->handleRequest($request);

      if ($form->isSubmitted() && $form->isValid()) {

          $message = $form->getData();
          $message->setDatetime(new \DateTime());

          $em = $this->getDoctrine()->getManager();
          $em->persist($message);
          $em->flush();

          return $this->redirectToRoute('dm_forum_formSucces'
            //  ,array(
            //    'author'    => $message->getAuthor(),
            //    'message'   => $message->getMessage()
            //  )
            );
      }

      return $this->render('DMForumBundle:Forum:form.html.twig',
          array( 'form' => $form->createView())
          );
    }
    // ========================================
    public function FormSuccesAction()
    {
      return $this->render('DMForumBundle:Forum:formSucces.html.twig');
    }
    // ========================================
    public function ViewAction()
    {
      // http://localhost/forum_sf3/web/app_dev.php/forum/view
      $post = new Post();

      $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('DMForumBundle:Post');

      $post = $repository->findAll();

      if ($post !== null) {
        return $this->render('DMForumBundle:Forum:view.html.twig',
            array('post'    => $post));
      }
    }
  // ========================================
    public function ViewByAuthorAction($author)
    {
      // http://localhost/forum_sf3/web/app_dev.php/forum/viewByAuthor/olivier
      $repository = $this
          ->getDoctrine()
          ->getManager()
          ->getRepository('DMForumBundle:Post');

      $messages = $repository->findByAuthor($author);

      if (empty($messages)) {
        return $this->render('DMForumBundle:Forum:error.html.twig',
          array(
            'error'   => "L'auteur ".$author." n'a pas été trouvé dans la BDD"
            ));
      }

      if ($messages !== null) {
        return $this->render('DMForumBundle:Forum:viewByAuthor.html.twig',
            array(
              'messages'  => $messages,
              'author'    => $author
            ));
      }
      else return $this->render('DMForumBundle:Forum:error.html.twig',
          array(
            'error'   => "Erreur de recherche de l'auteur"
            ));
    }
    // ========================================
    // ========================================
    public function TestAction($value, Request $request)
    {
      / http://localhost/forum_sf3/web/app_dev.php/forum/test
      $id = $request->query->get('id');

      if ($id === null) {
        throw new \Symfony\Component\Translation\Exception\NotFoundResourceException("id de la requete inexistant");
      }

      return $this->render('DMForumBundle:Forum:test.html.twig',
          array(
              'test'      => 'essai',
              'value'     => $value,
              'id'        => $id
            ));
    }
    // ========================================
    public function TestHydrateAction($author, $message)
    {
      // http://localhost/forum_sf3/web/app_dev.php/forum/hydrate/sam/testdemessage
      $post = new Post();

      $post->setAuthor($author);
      $post->setMessage($message);
      $post->setDatetime(new \DateTime());

      $em = $this->getDoctrine()->getManager();

      $em->persist($post);

      $em->flush();

      return $this->render('DMForumBundle:Forum:testhydrate.html.twig',
          array(
            'author'    => $author,
            'message'   => $message
          ));
    }

  }
