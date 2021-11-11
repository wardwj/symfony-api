<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\BlogPost;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {
        $user = $this->getReference('user_admin');

        $blogPost = new BlogPost();
        $blogPost->setTitle("A first post!");
        $blogPost->setPublished(new \DateTime('2021-10-01 12:00:00'));
        $blogPost->setContent("Post text!");
        $blogPost->setAuthor($user);
        $blogPost->setSlug("a-first-post");

        $manager->persist($blogPost);

        $blogPost = new BlogPost();
        $blogPost->setTitle("A second post!");
        $blogPost->setPublished(new \DateTime('2021-10-02 12:00:00'));
        $blogPost->setContent("Post text!");
        $blogPost->setAuthor($user);
        $blogPost->setSlug("a-second-post");

        $manager->persist($blogPost);

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {

    }

    public function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('admin');
        $user->setEmail('admin@blog.com');
        $user->setName('William Ward');

        $user->setPassword($this->passwordHasher->hashPassword(
            $user,
            'secret123'
        ));

        $this->addReference('user_admin', $user);

        $manager->persist($user);
        $manager->flush();
    }


}
