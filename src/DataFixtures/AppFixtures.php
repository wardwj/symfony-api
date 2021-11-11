<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\BlogPost;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $blogPost = new BlogPost();
        $blogPost->setTitle("A first post!");
        $blogPost->setPublished(new \DateTime('2021-10-01 12:00:00'));
        $blogPost->setContent("Post text!");
        $blogPost->setAuthor("William Ward");
        $blogPost->setSlug("a-first-post");

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle("A second post!");
        $blogPost->setPublished(new \DateTime('2021-10-02 12:00:00'));
        $blogPost->setContent("Post text!");
        $blogPost->setAuthor("William Ward");
        $blogPost->setSlug("a-second-post");

        $manager->persist($blogPost);

        $manager->flush();
    }
}
