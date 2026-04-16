<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function articlesPage()
    {
        $articles = [
            [
                'title' => 'How to Dress for Your Body Type: A Complete Guide to Flattering Fashion',
                'category' => 'Style Guide',
                'date' => 'August 18, 2025',
                'excerpt' => 'Discover how to identify your unique body shape and learn styling techniques to enhance your best features.',
                'image' => 'images/blog-1.jpg',
                'url' => 'https://femshades.com/how-to-dress-for-your-body-type/',
                'source' => 'Femshades',
            ],
            [
                'title' => 'Styling Basics: How to Dress for Your Body Type (Complete Guide 2025)',
                'category' => 'Body Positive',
                'date' => 'October 16, 2025',
                'excerpt' => 'Learn to style the five major body types with expert tips on cuts, fabrics, and silhouettes.',
                'image' => 'images/blog-2.jpg',
                'url' => 'https://www.stylementor.fashion/2025/10/202510styling-basics-how-to-dress-for-your-body-type.html.html',
                'source' => 'Style Mentor',
            ],
            [
                'title' => 'How to Dress for Your Body Type: Complete Style Guide',
                'category' => 'Trend Alert',
                'date' => 'August 26, 2025',
                'excerpt' => 'A comprehensive guide to dressing for pear, hourglass, apple, and rectangle body shapes.',
                'image' => 'images/blog-3.jpg',
                'url' => 'https://modelia.ai/blog/how-to-dress-for-your-body-type-guide',
                'source' => 'Modelia',
            ],
        ];

        return view('articles-page', compact('articles'));
    }
}