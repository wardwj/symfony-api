<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\BlogPost;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/{page}", name="blog_list", defaults={"page":5}, requirements={"page"="\d+"})
     */
    public function list($page = 1, Request $request)
    {
    	$limit = $request->get('limit', 10);

    	$repository = $this->getDoctrine()->getRepository(BlogPost::class);
        $items = $repository->findAll();

    	return $this->json(
    		[
    			'page' => $page,
    			'limit' => $limit,
    			'data' => array_map(function (BlogPost $item) {
    				return $this->generateUrl('blog_by_slug', ['slug'=> $item->getSlug()]);
    			}, self::POSTS)
    		]
    	);
    }

    /**
     * @Route("/post/{id}", name="blog_by_id", requirements={"id"="\d+"})
     */
    public function post(BlogPost $post)
    {
    	return $this->json($post);
    }

    /**
     * @Route("/post/{slug}", name="blog_by_slug")
     */
    public function postBySlug(BlogPost $post)
    {
        return $this->json($post);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request)
    {
    	$serializer = $this->get('serializer');

    	$blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

    	$em = $this->getDoctrine()->getManager();
    	$em->persist($blogPost);
    	$em->flush();

    	return $this->json($blogPost);
    }
}
