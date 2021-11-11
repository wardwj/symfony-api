<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\BlogPost;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @var Factory
     */
    private $faker;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        $this->faker = Factory::create();
    }
    public function load(ObjectManager $manager): void
    {
        $this->loadUsers($manager);
        $this->loadBlogPosts($manager);
        $this->loadComments($manager);
    }

    public function loadBlogPosts(ObjectManager $manager)
    {

        $user = $this->getReference('user_admin');

        for ($i = 0; $i < 100; $i++)
        {
            $blogPost = new BlogPost();
            $blogPost->setTitle($this->faker->realText(30));
            $blogPost->setPublished($this->faker->dateTime);
            $blogPost->setContent($this->faker->realText());
            $blogPost->setAuthor($user);
            $blogPost->setSlug($this->faker->slug);

            $this->setReference("blog_post_$i", $blogPost);

            $manager->persist($blogPost);
        }

        $manager->flush();
    }

    public function loadComments(ObjectManager $manager)
    {
        for ($i = 0; $i < 100; $i++)
        {
            for ($j = 0; $j < rand(1,10); $j++)
            {
                $comment = new Comment();
                $comment->setContent($this->faker->realText());
                $comment->setPublished($this->faker->dateTimeThisYear);
                $comment->setAuthor($this->getReference('user_admin'));
                $comment->setBlogPost($this->getReference("blog_post_$i"));

                $manager->persist($comment);
            }
        }

        $manager->flush();
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
