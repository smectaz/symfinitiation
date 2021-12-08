<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ArticleFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        $mesArticles = [
            ["title" => "titre 1", "content" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed tempor malesuada ante, et molestie massa pellentesque eget. Class aptent taciti sociosqu ad litora torquent per conubia nostra, per inceptos himenaeos.", "category" => "General"],
            ["title" => "titre 2", "content" => "Nam rhoncus tortor felis, ac interdum quam consectetur sed. Nunc ornare rutrum sapien, eget volutpat sapien cursus quis. Mauris sodales, nunc a tempor commodo, ex dui auctor massa, aliquam pretium lacus nulla pulvinar enim.", "category" => "General"],
            ["title" => "titre 3", "content" => ". Duis nisl sem, fringilla cursus euismod vel, laoreet at lectus. Maecenas vehicula ex orci, et consectetur mi efficitur semper. Nam nisi erat, consequat vitae tortor at, euismod malesuada odio. Pellentesque iaculis in mi non congue. Nunc sit amet enim et nibh venenatis consectetur vitae ut magna. ", "category" => "Urgent"],
            ["title" => "titre 4", "content" => "Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. Maecenas ullamcorper, dolor in venenatis maximus, magna sem rhoncus nunc, non euismod est nibh ut purus. Integer posuere hendrerit ultrices.", "category" => "Urgent"],
            ["title" => "titre 5", "content" => "Nullam sed venenatis nunc, ut mollis felis. Proin ut feugiat nisl. Vivamus semper tincidunt felis, eget ullamcorper est luctus sed. Donec vitae sapien id felis finibus laoreet. Aliquam fermentum nibh mi, sed consectetur mauris auctor eu. Mauris maximus placerat erat vulputate ullamcorper. ", "category" => "Divers"],
        ];

        foreach ($mesArticles as $monArticle) {
            $article = new article;

            $article->setTitle($monArticle['title']);
            $article->setContent($monArticle['content']);
            $article->setCategory($monArticle['category']);

            $manager->persist($article);

        }
        $manager->flush();
    }
}
