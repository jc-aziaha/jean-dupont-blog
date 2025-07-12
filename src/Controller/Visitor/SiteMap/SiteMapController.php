<?php

namespace App\Controller\Visitor\SiteMap;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteMapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_visitor_sitemap_show', methods: ['GET'])]
    public function show(Request $request, PostRepository $postRepository): Response
    {
        $hostName = $request->getSchemeAndHttpHost();

        $urls = [];
        $urls[] = [
            'loc' => $this->generateUrl('app_visitor_welcome'),
        ];

        $postsPublished = $postRepository->findBy(['isPublished' => true], ['publishedAt' => 'DESC']);

        foreach ($postsPublished as $postPublished) {
            $urls[] = [
                'loc' => $this->generateUrl('app_visitor_blog_post_show', ['id' => $postPublished->getId(), 'slug' => $postPublished->getSlug()]),
                'lastmod' => $postPublished->getUpdatedAt()->format('Y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        $response = $this->render('sitemap/show.html.twig', [
            'hostname' => $hostName,
            'urls' => $urls,
        ]);

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
